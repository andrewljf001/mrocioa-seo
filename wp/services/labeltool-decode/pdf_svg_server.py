#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
pdf_svg_server.py — 标签打印工具 · PDF→标签 后端服务 v3

v3 新增 /decode：工业级条码解码（zxing-cpp）
  两路取图，读全浏览器读不出的码：
  ① 原生位图直读 —— 从 PDF 里按 xref 取出嵌入图的原始像素（零重采样损失），
     最近邻 6x 放大后解码（CCITTFax 低分辨率码的最优解法）
  ② 600dpi 高清渲染框选区域解码
  支持 Code128/39/93/EAN/UPC/ITF/Codabar/QR/DataMatrix/PDF417/Aztec 全码制，
  返回值+码制+位置(mm)。

依赖：pip install pymupdf zxing-cpp pillow
运行：python3 pdf_svg_server.py     # 监听 127.0.0.1:8632

接口（CORS 已开，仅本机）：
  GET  /status → {"ok":true,"engine":"...","v":3,"decode":true|false}
  POST /pdf-to-label?page=1   → 1:1 路径化 SVG + 可编辑文字层（v2 同）
  POST /pdf-to-svg?page=1     → 旧版兼容
  POST /decode?page=1&x=&y=&w=&h=  (mm，可省=整页)
       → {"ok":true,"codes":[{"text","format","x","y","w","h","src"}…]}
"""

import json
import http.server
import socketserver

try:
    import fitz  # PyMuPDF
except ImportError:
    raise SystemExit("缺少依赖：先执行  pip install pymupdf")

try:
    import zxingcpp
    from PIL import Image
    HAS_ZX = True
except ImportError:
    HAS_ZX = False

import os

# 部署形态：本机开发 = 127.0.0.1；VPS = 仍然 127.0.0.1，由 WordPress 插件反代（不暴露公网）
HOST = os.environ.get("LABELTOOL_HOST", "127.0.0.1")
PORT = int(os.environ.get("LABELTOOL_PORT", "8632"))
# 仅当确实需要跨域直连时才放开（默认同源反代，不需要）
CORS_ORIGINS = [o.strip() for o in os.environ.get("LABELTOOL_ORIGINS", "*").split(",") if o.strip()]
K = 25.4 / 72.0  # pt → mm
MAX_PDF_BYTES = 20 * 1024 * 1024  # 单文件上限 20MB
import threading
MAX_CONCURRENT = 3
_sema = threading.Semaphore(MAX_CONCURRENT)


def _color_hex(c):
    try:
        return "#{:02x}{:02x}{:02x}".format((c >> 16) & 255, (c >> 8) & 255, c & 255)
    except Exception:
        return "#000000"


def _fam(font):
    f = (font or "").lower()
    if "mono" in f or "courier" in f:
        return "mono"
    if ("serif" in f or "times" in f) and "sans" not in f:
        return "serif"
    return "sans"


def _pts(it):
    out = []
    for v in it[1:]:
        for a in ("x", "y"):
            pass
        try:
            out.append((v.x, v.y))
        except Exception:
            pass
    return out


def classify_drawings(page):
    """把页面矢量绘图分三类：
      shapes  — 线 / 直角框 / 实心块 / 圆角矩形 / 圆点（均转可编辑 rect，rd=圆角）
      vectors — 真正复杂的图形（logo 等），给出 bbox，后续导矢量片段
    """
    pw, ph = page.rect.width * K, page.rect.height * K
    shapes, vectors = [], []

    def add(x0, y0, x1, y1, kind, filled, lw, color, rd=0.0):
        x, y = min(x0, x1) * K, min(y0, y1) * K
        w, h = abs(x1 - x0) * K, abs(y1 - y0) * K
        if kind == "line":
            # 描边线的两端点连线宽度为 0 → 用真实描边宽度回填（居中撑开）
            tw = max(lw * K, 0.12)
            if w < tw:
                x -= (tw - w) / 2.0; w = tw
            if h < tw:
                y -= (tw - h) / 2.0; h = tw
        # 坐标保留 PDF 原值（描边中心线），不做任何外扩 —— 否则会改变框与框的间距。
        # 由各渲染路径自己把线摆到中心线上。
        if w < 0.15 and h < 0.15:
            return
        if w > pw * 0.995 and h > ph * 0.995:
            return
        shapes.append({"kind": kind, "x": round(x, 2), "y": round(y, 2),
                       "w": round(w, 2), "h": round(h, 2), "rd": round(rd * K, 2),
                       "th": round(max(lw * K, 0.12), 2), "filled": bool(filled),
                       "color": color})

    try:
        drawings = page.get_drawings()
    except Exception:
        return shapes, vectors

    for d in drawings:
        dt = d.get("type", "")
        lw = d.get("width") or 0.4
        col = _color_rgb(d.get("color")) or _color_rgb(d.get("fill")) or "#000000"
        items = d.get("items", [])
        ops = [it[0] for it in items]
        curves = [it for it in items if it[0] == "c"]
        r = d.get("rect")

        if not curves:
            for it in items:
                op = it[0]
                if op == "l":
                    p1, p2 = it[1], it[2]
                    add(p1.x, p1.y, p2.x, p2.y, "line", True, max(lw, 0.35), col)
                elif op == "re":
                    rr = it[1]
                    thin = min(rr.width, rr.height) * K <= 1.0
                    if thin or dt == "f":
                        add(rr.x0, rr.y0, rr.x1, rr.y1, "line" if thin else "block", True, lw, col)
                    else:
                        add(rr.x0, rr.y0, rr.x1, rr.y1, "rect", False, max(lw, 0.35), col)
                elif op == "qu":
                    q = it[1]
                    xs = [q.ul.x, q.ur.x, q.ll.x, q.lr.x]; ys = [q.ul.y, q.ur.y, q.ll.y, q.lr.y]
                    thin = min(max(xs) - min(xs), max(ys) - min(ys)) * K <= 1.0
                    add(min(xs), min(ys), max(xs), max(ys), "line" if thin else "block", True, lw, col)
            continue

        if r is None:
            continue
        w_mm, h_mm = r.width * K, r.height * K

        # 任何包含曲线的图元，只要外框细长，就当线（圆头端点的描边线就是这种）
        if min(w_mm, h_mm) <= 1.2 and max(w_mm, h_mm) >= 2.0:
            add(r.x0, r.y0, r.x1, r.y1, "line", True, lw, col)
            continue

        # 圆 / 椭圆（四条弧、无直线，近似正方）→ rect + 半径圆角
        only_curves = all(o == "c" for o in ops)
        squarish = h_mm and 0.75 <= (w_mm / h_mm) <= 1.33
        if only_curves and len(curves) <= 4 and squarish and max(w_mm, h_mm) <= 8:
            add(r.x0, r.y0, r.x1, r.y1, "block" if dt in ("f", "fs") else "rect",
                dt in ("f", "fs"), lw, col, rd=min(r.width, r.height) / 2.0)
            continue

        # 圆角矩形：4 条弧 + 直线边，弧的端点偏移＝圆角半径
        if len(curves) == 4 and len([o for o in ops if o in ("l", "re")]) <= 6:
            rs = []
            for c in curves:
                p = _pts(c)
                if len(p) >= 2:
                    rs.append(max(abs(p[0][0] - p[-1][0]), abs(p[0][1] - p[-1][1])))
            rd = sorted(rs)[len(rs) // 2] if rs else 0.0
            if rd and rd * K < min(w_mm, h_mm) / 2 + 0.01:
                add(r.x0, r.y0, r.x1, r.y1, "block" if dt in ("f", "fs") else "rect",
                    dt in ("f", "fs"), lw, col, rd=rd)
                continue

        # 其余→ 复杂矢量图形（logo 等）
        vectors.append([r.x0 * K, r.y0 * K, r.x1 * K, r.y1 * K])

    # 相邻/重叠的复杂图形合并成一块（logo 往往由几十条路径组成）
    merged = []
    for v in vectors:
        hit = None
        for m in merged:
            if not (v[2] < m[0] - 1.5 or v[0] > m[2] + 1.5 or v[3] < m[1] - 1.5 or v[1] > m[3] + 1.5):
                hit = m; break
        if hit:
            hit[0] = min(hit[0], v[0]); hit[1] = min(hit[1], v[1])
            hit[2] = max(hit[2], v[2]); hit[3] = max(hit[3], v[3])
        else:
            merged.append(list(v))
    return shapes, merged


def region_svg(doc, pno, box):
    """把一块区域导为矢量 SVG（不转位图）。box 为 mm。"""
    x0, y0, x1, y1 = [v / K for v in box]
    tmp = fitz.open()
    tmp.insert_pdf(doc, from_page=pno, to_page=pno)
    p = tmp[0]
    pad = 1.0
    rect = fitz.Rect(max(0, x0 - pad), max(0, y0 - pad),
                     min(p.rect.x1, x1 + pad), min(p.rect.y1, y1 + pad))
    try:
        p.set_cropbox(rect)
    except Exception:
        tmp.close(); return None, None
    svg = p.get_svg_image(text_as_path=True)
    tmp.close()
    svg = _strip_bg(svg)
    return svg, [round(rect.x0 * K, 2), round(rect.y0 * K, 2),
                 round(rect.width * K, 2), round(rect.height * K, 2)]


def _strip_bg(svg):
    """去掉裁剪后带出来的白页底（开头连续的白色填充元素），否则会遮住下面的黑条。"""
    import re
    pat = re.compile(r"<(?:path|rect)\b[^>]*?/>", re.S)
    out, pos = [], 0
    stop = False
    for m in pat.finditer(svg):
        if stop:
            break
        tag = m.group(0)
        white = re.search(r'fill="(#fff(?:fff)?|white)"', tag, re.I)
        if white and "stroke" not in tag:
            out.append((m.start(), m.end()))
        else:
            stop = True
    for a, b in reversed(out):
        svg = svg[:a] + svg[b:]
    return svg


def page_shapes(page):
    return classify_drawings(page)[0]


def _color_rgb(c):
    if not c:
        return None
    try:
        r, g, b = [int(round(v * 255)) for v in (list(c) + [0, 0, 0])[:3]]
        return "#{:02x}{:02x}{:02x}".format(r, g, b)
    except Exception:
        return None


def graphics_only_svg(page):
    """纯图形底图：线框/黑块/图片保留，所有文字剔除。
    前端在此底图上叠加可编辑文字层，避免文字双份（重影）。"""
    import re
    svg = page.get_svg_image(text_as_path=False)
    svg = re.sub(r"<text\b[\s\S]*?</text>", "", svg)
    svg = re.sub(r"<text\b[^>]*/>", "", svg)
    return svg


def _weight(font, flags):
    """从字体名解析真实字重（细体在热敏小字上更清，不能一律当普通体）"""
    f = (font or "").lower().replace(" ", "").replace("-", "").replace("_", "")
    for key, w in (("extralight", 200), ("ultralight", 200), ("semilight", 300), ("light", 300),
                   ("thin", 100), ("hairline", 100), ("book", 400), ("regular", 400), ("normal", 400),
                   ("medium", 500), ("demibold", 600), ("semibold", 600),
                   ("extrabold", 800), ("ultrabold", 800), ("heavy", 900), ("black", 900), ("bold", 700)):
        if key in f:
            return w
    return 700 if (flags or 0) & 16 else 400


def page_to_label(page):
    svg = page.get_svg_image(text_as_path=True)
    texts = []
    d = page.get_text("dict")
    for blk in d.get("blocks", []):
        for line in blk.get("lines", []):
            for sp in line.get("spans", []):
                s = (sp.get("text") or "")
                if not s.strip():
                    continue
                x0, y0, x1, y1 = sp["bbox"]
                size = sp.get("size", 8)
                org = sp.get("origin") or (x0, y1)
                # y 取“基线 − 0.8×字号”，与前端文字元素的定位口径一致
                ty = (org[1] - size * 0.88)
                texts.append({
                    "v": s,
                    "x": round(x0 * K, 2), "y": round(ty * K, 2),
                    "w": round((x1 - x0) * K, 2), "h": round((y1 - y0) * K, 2),
                    "fs": round(size * K, 2),
                    "ff": sp.get("font", ""),
                    "fam": _fam(sp.get("font", "")),
                    "color": _color_hex(sp.get("color", 0)),
                    "bold": bool(sp.get("flags", 0) & 16) or "bold" in (sp.get("font", "").lower()),
                    "wt": _weight(sp.get("font", ""), sp.get("flags", 0)),
                })
    return svg, texts


def _fmt_symbology(fmt):
    """zxing-cpp format → 前端 symbology 名（仅用于展示/归类）。
    真正画码以 raw 名字为准，前端直接映射到 bwip-js 码制 ID。"""
    f = (fmt or "").upper().replace("_", "").replace("-", "").replace(" ", "")
    if "BARCODEFORMAT." in f:
        f = f.split("BARCODEFORMAT.", 1)[1]
    for key, out in (("PDF417", "PDF417"), ("DATAMATRIX", "DM"), ("QRCODE", "QR"),
                     ("MICROQR", "QR"), ("RMQR", "QR"), ("AZTEC", "AZTEC"),
                     ("MAXICODE", "MAXICODE"), ("DATABAR", "DATABAR"),
                     ("EAN13", "EAN13"), ("EAN8", "EAN8"), ("UPCA", "UPCA"), ("UPCE", "UPCE"),
                     ("CODE39", "CODE39"), ("CODE93", "CODE93"), ("CODE128", "CODE128"),
                     ("CODABAR", "CODABAR"), ("ITF", "ITF")):
        if key in f:
            return out
    if f == "QR":
        return "QR"
    return "CODE128"


DIM2 = ("QR", "DM", "PDF417", "AZTEC", "MAXICODE")


def _is_2d(sym, raw):
    r = (raw or "").upper().replace("_", "")
    return sym in DIM2 or any(k in r for k in ("QRCODE", "DATAMATRIX", "PDF417", "AZTEC", "MAXICODE", "MICROQR", "RMQR"))


def page_to_contract(doc, page, page_w_mm, page_h_mm, decode=True):
    """产出标准标签契约：paper + preview(PNG) + elements[text/barcode/image]。"""
    elements = []
    # 文字元素
    _, texts = page_to_label(page)
    for t in texts:
        elements.append({
            "type": "text", "value": t["v"],
            "x": t["x"], "y": t["y"], "w": t["w"], "h": t["h"],
            "size": t["fs"], "font": t["fam"], "bold": t["bold"],
            "color": t["color"], "rotate": 0,
        })
    # 条码元素（强力解码，带位置）
    codes = []
    if decode and HAS_ZX:
        try:
            codes = decode_region(doc, page)
        except Exception:
            codes = []
    for c in codes:
        elements.append({
            "type": "barcode", "value": c["text"],
            "symbology": _fmt_symbology(c.get("format", "")),
            "x": c.get("x", 0), "y": c.get("y", 0),
            "w": c.get("w", 0), "h": c.get("h", 0), "rotate": 0,
        })
    # 速显预览图（后端渲染整页 PNG，前端秒贴）
    import base64
    Z = 300 / 72.0
    pix = page.get_pixmap(matrix=fitz.Matrix(Z, Z), alpha=False)
    preview = "data:image/png;base64," + base64.b64encode(pix.tobytes("png")).decode()
    # 同时附矢量 SVG + 文字层，供前端 1:1 可编辑渲染（契约超集，不破坏现有前端）
    svg, texts = page_to_label(page)
    return {
        "paper": {"w": round(page_w_mm, 2), "h": round(page_h_mm, 2), "gap": 3, "unit": "mm"},
        "preview": preview,
        "elements": elements,
        "svg": svg,
        "svg_nt": graphics_only_svg(page),
        "shapes": page_shapes(page),
        "texts": texts,
    }


def _measured_module(shapes, box):
    """从码框内的实际图元量出模块宽：最细竖条宽（二维码＝最小方块边长）。
    量不到就返回 None，由调用方退回估算。"""
    x, y, w, h = box[0], box[1], box[2], box[3]
    inner = []
    for s in shapes:
        cx, cy = s["x"] + s["w"] / 2, s["y"] + s["h"] / 2
        if x - 0.5 <= cx <= x + w + 0.5 and y - 0.5 <= cy <= y + h + 0.5:
            d = min(s["w"], s["h"])
            if d > 0.02:
                inner.append(d)
    if len(inner) < 3:
        return None
    inner.sort()
    # 取较小那一档的中位数，避开单个异常值
    k = max(1, len(inner) // 5)
    return sum(inner[:k]) / k


def _module_mm(text, sym, w, h):
    """推算码的模块宽（最细条/方块边长，mm）。
    二维码：方形 → 框宽 ÷ 估算模块数（由内容长度得版本）
    一维码：CODE128 每字符 11 模块 + 起止符 → 框宽 ÷ 总模块数
    """
    n = max(1, len(text or ""))
    try:
        if sym in ("QR", "DM", "AZTEC", "PDF417"):
            if sym == "QR":
                ver = 1
                while ver < 40 and n > (ver * 4 + 17) ** 2 // 40:
                    ver += 1
                mods = ver * 4 + 17
            elif sym == "DM":
                mods = 16 if n <= 12 else (22 if n <= 24 else 32)
            else:
                mods = 30
            return max(0.05, min(w, h) / mods)
        mods = 11 * n + 35  # CODE128: 起始+校验+终止 约 35 模块
        return max(0.05, w / mods)
    except Exception:
        return 0.3


def page_rebuild(doc, page):
    """唯一重建入口：把一页 PDF 拆成全可编辑对象。
    原则：能矢量就不位图。文字→text，线/框/块→rect，码→barcode/qr，
    只有真正的嵌入位图（且不是已解出的码）才输出 image。
    overlay = 300dpi 原样整页 PNG，仅作叠底对照，不打印。
    """
    import base64
    r = page.rect
    pw, ph = r.width * K, r.height * K
    els = []

    shapes_all, vecboxes = classify_drawings(page)

    codes = []
    if HAS_ZX:
        try:
            codes = decode_region(doc, page)
        except Exception:
            codes = []
    code_boxes = []
    for c in codes:
        sym = _fmt_symbology(c.get("format", ""))
        raw = c.get("format", "")
        box = (c.get("x", 0), c.get("y", 0), c.get("w", 0), c.get("h", 0))
        if not (box[2] and box[3]):
            continue  # 无位置的解码结果不生成元素（否则尺寸为 0 会撑爆）
        two_d = _is_2d(sym, raw)
        # 仅当码制名完全认不出时才看形状（近正方形 + 内容长 → 二维码）
        ratio = box[2] / box[3] if box[3] else 9
        if not two_d and not raw and 0.75 <= ratio <= 1.35 and len(c.get("text", "")) > 16:
            sym, two_d = "QR", True
        # 解码器给的框量到最外那根条的中心线，不含它自身的半个线宽
        # → 四边各外扩「半个模块宽 + 0.1mm」，首尾条/外圈模块才不会被当成独立线条
        mod = _measured_module(shapes_all, box) or _module_mm(c.get("text", ""), sym, box[2], box[3])
        pad = mod / 2.0 + 0.1
        code_boxes.append(box + (pad,))
        # 元素本身也取外沿尺寸（含首尾半个线宽），才与原码宽度一致
        bx, by = round(box[0] - mod / 2.0, 2), round(box[1] - mod / 2.0, 2)
        bw, bh = round(box[2] + mod, 2), round(box[3] + mod, 2)
        if two_d:
            els.append({"type": "qr", "q2": sym, "v": c["text"], "raw": raw,
                        "x": bx, "y": by, "w": bw, "h": bh})
        else:
            els.append({"type": "barcode", "fmt": sym, "txt": True, "v": c["text"], "raw": raw,
                        "x": bx, "y": by, "w": bw, "h": bh})
        # 测量校验框（黄色虚线、不打印）：看量到的模块宽/外沿对不对
        els.append({"type": "dbgbox", "x": bx, "y": by, "w": bw, "h": bh,
                    "note": "mod=%.3fmm" % mod})
    code_vals = set()
    code_raw = []
    for c in codes:
        t = c["text"]
        code_vals.add(t)
        code_vals.add("".join(ch for ch in t if ch.isalnum()))
        code_raw.append(t)

    def is_code_fragment(s):
        """这段字是否属于某个码的内容（拆行后的片段也算）"""
        k = s.strip()
        if len(k) < 3:
            return False
        ka = "".join(ch for ch in k if ch.isalnum())
        for t in code_raw:
            ta = "".join(ch for ch in t if ch.isalnum())
            if k in t or (ka and ka in ta):
                return True
        return False

    shapes = shapes_all

    def in_vec(bx, by, bw, bh):
        cx, cy = bx + bw / 2, by + bh / 2
        for vx0, vy0, vx1, vy1 in vecboxes:
            if vx0 - 0.5 <= cx <= vx1 + 0.5 and vy0 - 0.5 <= cy <= vy1 + 0.5:
                return True
        return False

    # 嵌入位图区域（码图等）—— 压在它下面的文字在原档里看不见，不能重建成可见大字
    img_boxes = []
    try:
        for info in page.get_image_info(xrefs=True):
            ix0, iy0, ix1, iy1 = [v * K for v in info["bbox"]]
            if (ix1 - ix0) > 1 and (iy1 - iy0) > 1:
                img_boxes.append((ix0, iy0, ix1 - ix0, iy1 - iy0))
    except Exception:
        pass

    def inside_box(boxes, bx, by, bw, bh):
        """图元必须整体落在码框内才算码的一部分（包在码外面的框不会被误删）。"""
        for b in boxes:
            kx, ky, kw, kh = b[0], b[1], b[2], b[3]
            p = b[4] if len(b) > 4 else 0.6
            if bx >= kx - p and by >= ky - p and bx + bw <= kx + kw + p and by + bh <= ky + kh + p:
                return True
        return False

    def in_box(boxes, bx, by, bw, bh, pad=0.6):
        cx, cy = bx + bw / 2, by + bh / 2
        for b in boxes:
            kx, ky, kw, kh = b[0], b[1], b[2], b[3]
            p = b[4] if len(b) > 4 else pad
            if kx - p <= cx <= kx + kw + p and ky - p <= cy <= ky + kh + p:
                return True
        return False

    def overlaps_code(bx, by, bw, bh):
        """文字中心落在码框内（或重叠过自身 30%）→ 视为在码区里"""
        if bw <= 0 or bh <= 0:
            return False
        cx, cy = bx + bw / 2, by + bh / 2
        for b in code_boxes:
            kx, ky, kw, kh = b[0], b[1], b[2], b[3]
            if kx <= cx <= kx + kw and ky <= cy <= ky + kh:
                return True
            ox = max(0, min(bx + bw, kx + kw) - max(bx, kx))
            oy = max(0, min(by + bh, ky + kh) - max(by, ky))
            if ox * oy / (bw * bh) > 0.3:
                return True
        return False

    def in_code(bx, by, bw, bh, s=""):
        # 只有“落在码框内 且 内容属于码载荷”的才丢（真正被码遮住的隐藏文字）
        # 与 logo 等位图并排/相邻的文字照常生成，靠图层顺序盖在位图之上
        return overlaps_code(bx, by, bw, bh) and is_code_fragment(s)

    _, texts = page_to_label(page)
    for t in texts:
        s = (t["v"] or "").strip()
        if not s:
            continue
        if s in code_vals or "".join(ch for ch in s if ch.isalnum()) in code_vals:
            continue  # 人眼可读行，由码元素自带
        if in_code(t["x"], t["y"], t["w"], t["h"], s):
            continue  # 被码/位图遮住的隐藏文字
        if in_vec(t["x"], t["y"], t["w"], t["h"]):
            continue  # 属于 logo 等矢量图形的字，随图形一起保留
        els.append({"type": "text", "v": t["v"], "x": t["x"], "y": t["y"],
                    "fs": t["fs"], "fam": t["fam"], "ff": t["ff"], "bold": t["bold"],
                    "wt": t.get("wt", 400),
                    "color": t["color"], "mw": t["w"], "mh": t["h"]})

    for s in shapes:
        if in_vec(s["x"], s["y"], s["w"], s["h"]):
            continue
        if inside_box(code_boxes, s["x"], s["y"], s["w"], s["h"]):
            continue  # 码本体的条/模块 → 由可编辑码元素代替
        els.append({"type": "rect", "x": s["x"], "y": s["y"], "w": s["w"], "h": s["h"],
                    "th": s["th"], "rd": s.get("rd", 0), "fill": s["kind"] != "rect",
                    "color": s["color"], "kind": s["kind"]})

    # 复杂图形（logo 等）→ 矢量片段，不转位图；落在码区的不要
    for vb in vecboxes:
        if inside_box(code_boxes, vb[0], vb[1], vb[2] - vb[0], vb[3] - vb[1]):
            continue
        try:
            svg, box = region_svg(doc, page.number, vb)
        except Exception:
            svg, box = None, None
        if svg and box:
            els.append({"type": "vector", "svg": svg,
                        "x": box[0], "y": box[1], "w": box[2], "h": box[3]})

    def is_code_area(bx, by, bw, bh):
        for b in code_boxes:
            cx, cy, cw, ch = b[0], b[1], b[2], b[3]
            ox = max(0, min(bx + bw, cx + cw) - max(bx, cx))
            oy = max(0, min(by + bh, cy + ch) - max(by, cy))
            if not (ox > 0 and oy > 0):
                continue
            inter = ox * oy
            if (bw * bh and inter / (bw * bh) > 0.15) or (cw * ch and inter / (cw * ch) > 0.15):
                return True
        return False

    try:
        for info in page.get_image_info(xrefs=True):
            xref = info.get("xref", 0)
            if not xref:
                continue
            x0, y0, x1, y1 = [v * K for v in info["bbox"]]
            bw, bh = x1 - x0, y1 - y0
            if bw < 0.5 or bh < 0.5:
                continue
            if is_code_area(x0, y0, bw, bh):
                continue  # 已解为矢量码，不再贴位图
            # 直接按区域从页面渲染（600dpi）：图像蒙版/透明/叠在黑底上的 logo 都与原档一致
            # （按 xref 抽原图遇到 stencil mask 会得到一片白）
            try:
                clip = fitz.Rect(x0 / K, y0 / K, x1 / K, y1 / K)
                Zi = 600 / 72.0
                pi = page.get_pixmap(matrix=fitz.Matrix(Zi, Zi), clip=clip, alpha=False)
                uri = "data:image/png;base64," + base64.b64encode(pi.tobytes("png")).decode()
            except Exception:
                continue
            els.append({"type": "image", "uri": uri, "cand": True,
                        "x": round(x0, 2), "y": round(y0, 2),
                        "w": round(bw, 2), "h": round(bh, 2)})
    except Exception:
        pass

    Z = 300 / 72.0
    pix = page.get_pixmap(matrix=fitz.Matrix(Z, Z), alpha=False)
    overlay = "data:image/png;base64," + base64.b64encode(pix.tobytes("png")).decode()

    # 页面原点不在 (0,0) 时（MediaBox/CropBox 偏移）统一归零
    ox, oy = r.x0 * K, r.y0 * K
    if abs(ox) > 0.01 or abs(oy) > 0.01:
        for e in els:
            e["x"] = round(e["x"] - ox, 2)
            e["y"] = round(e["y"] - oy, 2)

    stat = {
        "text": len([e for e in els if e["type"] == "text"]),
        "rect": len([e for e in els if e["type"] == "rect"]),
        "code": len([e for e in els if e["type"] in ("barcode", "qr")]),
        "vector": len([e for e in els if e["type"] == "vector"]),
        "image": len([e for e in els if e["type"] == "image"]),
    }
    return {"paper": {"w": round(pw, 2), "h": round(ph, 2)},
            "elements": els, "overlay": overlay, "stat": stat}


def decode_region(doc, page, x=None, y=None, w=None, h=None):
    """两路解码：600dpi 渲染 + 原生嵌入图直读。坐标均返回 mm。"""
    out = []

    def scan(img, ox_mm, oy_mm, sx, sy, src):
        try:
            for r in zxingcpp.read_barcodes(img):
                if not r.text:
                    continue
                try:
                    p = r.position
                    xs = [p.top_left.x, p.top_right.x, p.bottom_left.x, p.bottom_right.x]
                    ys = [p.top_left.y, p.top_right.y, p.bottom_left.y, p.bottom_right.y]
                    bx, by = ox_mm + min(xs) * sx, oy_mm + min(ys) * sy
                    bw, bh = (max(xs) - min(xs)) * sx, (max(ys) - min(ys)) * sy
                except Exception:
                    bx = by = bw = bh = 0
                out.append({"text": r.text, "format": str(r.format), "src": src,
                            "x": round(bx, 1), "y": round(by, 1), "w": round(bw, 1), "h": round(bh, 1)})
        except Exception:
            pass

    # ① 600dpi 渲染（框选区域或整页）
    Z = 600 / 72.0
    clip = None
    if w and h:
        clip = fitz.Rect(x / K, y / K, (x + w) / K, (y + h) / K)
    pix = page.get_pixmap(matrix=fitz.Matrix(Z, Z), clip=clip, alpha=False)
    img = Image.frombytes("RGB", [pix.width, pix.height], pix.samples)
    mmppx = 25.4 / 600.0
    scan(img, (x or 0.0), (y or 0.0), mmppx, mmppx, "render600")

    # ② 原生分辨率嵌入图直读（真实存储位，零重采样）+ 6x 最近邻放大
    try:
        for info in page.get_image_info(xrefs=True):
            xref = info.get("xref", 0)
            if not xref:
                continue
            bx0, by0, bx1, by1 = [v * K for v in info["bbox"]]
            if clip is not None and (bx1 < x or bx0 > x + w or by1 < y or by0 > y + h):
                continue
            try:
                p2 = fitz.Pixmap(doc, xref)
                if p2.alpha:
                    p2 = fitz.Pixmap(p2, 0)
                mode = "L" if p2.n == 1 else "RGB"
                if p2.n not in (1, 3):
                    p2 = fitz.Pixmap(fitz.csRGB, p2)
                    mode = "RGB"
                im2 = Image.frombytes(mode, [p2.width, p2.height], p2.samples).convert("L")
                big = im2.resize((im2.width * 6, im2.height * 6), Image.NEAREST)
                scan(big, bx0, by0, (bx1 - bx0) / big.width, (by1 - by0) / big.height, "native%dx%d" % (p2.width, p2.height))
            except Exception:
                continue
    except Exception:
        pass

    seen, ded = set(), []
    for r in out:
        if r["text"] in seen:
            continue
        seen.add(r["text"]); ded.append(r)
    return ded


def _extract_pdf(body: bytes, ctype: str) -> bytes:
    if "multipart/form-data" in ctype and "boundary=" in ctype:
        boundary = ("--" + ctype.split("boundary=", 1)[1].strip().strip('"')).encode()
        for part in body.split(boundary):
            j = part.find(b"\r\n\r\n")
            if j != -1 and (b"filename=" in part[:j].lower() or b"application/pdf" in part[:j].lower()):
                return part[j + 4:].rstrip(b"\r\n")
    return body


def _q(path, key, cast=float, default=None):
    if key + "=" in path:
        try:
            return cast(path.split(key + "=", 1)[1].split("&")[0])
        except ValueError:
            return default
    return default


class H(http.server.BaseHTTPRequestHandler):
    def _cors(self):
        origin = self.headers.get("Origin", "")
        if "*" in CORS_ORIGINS:
            allow = "*"
        elif origin and origin in CORS_ORIGINS:
            allow = origin
        else:
            allow = ""
        if allow:
            self.send_header("Access-Control-Allow-Origin", allow)
            if allow != "*":
                self.send_header("Vary", "Origin")
        self.send_header("Access-Control-Allow-Headers", "Content-Type")
        self.send_header("Access-Control-Allow-Methods", "POST, GET, OPTIONS")

    def _json(self, code, obj):
        b = json.dumps(obj, ensure_ascii=False).encode()
        self.send_response(code)
        self.send_header("Content-Type", "application/json; charset=utf-8")
        self.send_header("Content-Length", str(len(b)))
        self._cors(); self.end_headers(); self.wfile.write(b)

    def do_OPTIONS(self):
        self.send_response(204); self._cors(); self.end_headers()

    def do_GET(self):
        if self.path == "/status":
            ver = (fitz.__doc__ or "pymupdf").split("\n")[0]
            self._json(200, {"ok": True, "engine": ver, "v": 29, "decode": HAS_ZX,
                             "rebuild": True, "host": HOST, "port": PORT})
        else:
            self._json(404, {"ok": False, "error": "not found"})

    def do_POST(self):
        try:
            length = int(self.headers.get("Content-Length", 0))
            if length > MAX_PDF_BYTES:
                return self._json(413, {"ok": False, "error": "文件过大（上限 20MB）"})
            pdf = _extract_pdf(self.rfile.read(length), self.headers.get("Content-Type", ""))
            pg = int(_q(self.path, "page", float, 1) or 1)
            # 并发限流：超过上限则排队（最多等 30s），避免多人同时压垮 VPS
            if not _sema.acquire(timeout=30):
                return self._json(503, {"ok": False, "error": "服务繁忙，请稍后重试"})
            try:
                doc = fitz.open(stream=pdf, filetype="pdf")
                n = doc.page_count
                pg = max(1, min(n, pg))
                page = doc[pg - 1]
                if self.path.startswith("/rebuild"):
                    out = page_rebuild(doc, page)
                    doc.close()
                    out["ok"] = True; out["pages"] = n; out["page"] = pg
                    self._json(200, out)
                elif self.path.startswith("/pdf-to-label"):
                    r = page.rect
                    contract = page_to_contract(doc, page, r.width * K, r.height * K,
                                                decode=_q(self.path, "decode", int, 1) != 0)
                    doc.close()
                    contract["ok"] = True; contract["pages"] = n; contract["page"] = pg
                    self._json(200, contract)
                elif self.path.startswith("/pdf-to-svg"):
                    svg = page.get_svg_image(text_as_path=False)
                    doc.close()
                    self._json(200, {"ok": True, "pages": n, "page": pg, "svg": svg})
                elif self.path.startswith("/decode"):
                    if not HAS_ZX:
                        doc.close()
                        return self._json(500, {"ok": False, "error": "缺少解码依赖：pip install zxing-cpp pillow"})
                    x = _q(self.path, "x"); y = _q(self.path, "y")
                    w = _q(self.path, "w"); h = _q(self.path, "h")
                    codes = decode_region(doc, page, x, y, w, h)
                    doc.close()
                    self._json(200, {"ok": True, "pages": n, "page": pg, "codes": codes})
                else:
                    doc.close()
                    self._json(404, {"ok": False, "error": "not found"})
            finally:
                _sema.release()
        except Exception as e:
            self._json(500, {"ok": False, "error": str(e)})

    def log_message(self, *a):
        pass


if __name__ == "__main__":
    socketserver.ThreadingTCPServer.allow_reuse_address = True
    with socketserver.ThreadingTCPServer((HOST, PORT), H) as httpd:
        print(f"PDF→标签 服务 v29 已启动 · http://{HOST}:{PORT}")
        print("  /pdf-to-label (1:1)  ·  /decode (zxing-cpp 强力解码: %s)" % ("可用" if HAS_ZX else "未安装 → pip install zxing-cpp pillow"))
        httpd.serve_forever()
