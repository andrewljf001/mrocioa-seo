#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
ZMIN X1i 本地打印服务 (PCLE 指令)
================================================================
版本记录 CHANGELOG
  v1.0 (2026-07-19)  初版：/print /raw /preview /status，libusb 直连，9100 中继
  v1.1 (2026-07-23)  临时加状态回读 /query（用于诊断缺纸）
  v2.0 (2026-07-25)  发布版清理：移除 9100 中继(RawRelayHandler)、build_pcle 与
                     /print /preview 接口 —— 标签工具只走 /raw 位图路径。
                     保留 /raw /status / 自检页 与 _drain。
  v1.4 (2026-07-23)  清理：macOS 上系统 USB 驱动占用控制端点，状态查询读不到，
                     移除 /query、read_port_status、status_hex 等测试代码，
                     回到干净的打印主路径。_drain 读空回传保留（防缓冲堵塞）。
----------------------------------------------------------------
用途:让浏览器里的标签设计工具能够调用直连本机的 ZMIN 条码打印机。
启动:  python3 zmin_print_server.py
测试:  浏览器打开 http://localhost:8631/
依赖:  pyusb + libusb（pip install pyusb）
================================================================
"""

import json
import time
import base64
import threading
import http.server
import socketserver

import usb.core
import usb.util

# ============ 配置 ============
DPI = 300              # X1i 为 300DPI
PORT = 8631            # HTTP 接口(供网页工具调用)
PRINTER_CLASS = 7      # USB 打印机类接口号（按此扫描设备，不认端口位置）
# =============================

DOTS_PER_MM = DPI / 25.4          # 300dpi → 11.811 点/毫米


def mm(v):
    """毫米转点(dots),四舍五入取整"""
    return int(round(float(v) * DOTS_PER_MM))


def _all_printer_ifaces():
    """列出所有 USB 打印机类(class 7)接口 —— 有些机型同时暴露 IPP-over-USB
    (protocol 4) 与原始 bulk (protocol 1/2)，写错接口时打印机会收下并丢弃。"""
    found = []
    for dev in usb.core.find(find_all=True):
        try:
            for cfg in dev:
                for intf in cfg:
                    if intf.bInterfaceClass == PRINTER_CLASS:
                        found.append((dev, intf))
        except Exception:
            continue
    return found


def _find_printer(iface_num=None):
    found = _all_printer_ifaces()
    if not found:
        return None, None
    if iface_num is not None:
        for dev, intf in found:
            if intf.bInterfaceNumber == int(iface_num):
                return dev, intf
        raise RuntimeError(f"未找到接口号 {iface_num}")
    # 优先原始 bulk 通道（protocol 1/2），避开 IPP-over-USB (protocol 4)
    for want in (2, 1):
        for dev, intf in found:
            if intf.bInterfaceProtocol == want:
                return dev, intf
    return found[0]


def _drain(ep_in):
    """读空打印机回传数据 —— 不做这一步会导致后续任务全部失效"""
    if ep_in is None:
        return b""
    data = b""
    for _ in range(1):
        try:
            chunk = ep_in.read(ep_in.wMaxPacketSize, timeout=100)
            if not len(chunk):
                break
            data += bytes(chunk)
        except usb.core.USBError:
            break
    return data


def printer_present():
    dev, _ = _find_printer()
    return dev is not None


_usb_lock = threading.Lock()


def send_bytes(data: bytes, iface_num=None):
    """通过 libusb 直接把原始字节写入打印机(线程安全)"""
    with _usb_lock:
        return _send_bytes_locked(data, iface_num)


def _send_bytes_locked(data: bytes, iface_num=None):
    dev, intf = _find_printer(iface_num)
    if dev is None:
        raise RuntimeError("未检测到打印机,请确认已开机并连接 USB")

    num = intf.bInterfaceNumber
    try:
        try:
            if dev.is_kernel_driver_active(num):
                dev.detach_kernel_driver(num)
        except (NotImplementedError, usb.core.USBError):
            pass
        try:
            dev.set_configuration()
        except usb.core.USBError:
            pass

        usb.util.claim_interface(dev, num)

        ep_out = usb.util.find_descriptor(
            intf, custom_match=lambda e:
            usb.util.endpoint_direction(e.bEndpointAddress) == usb.util.ENDPOINT_OUT)
        ep_in = usb.util.find_descriptor(
            intf, custom_match=lambda e:
            usb.util.endpoint_direction(e.bEndpointAddress) == usb.util.ENDPOINT_IN)

        if ep_out is None:
            raise RuntimeError("未找到 USB 批量输出端点")

        _drain(ep_in)                                     # 写入前清空残留
        written = 0
        chunk_size = 4096
        for i in range(0, len(data), chunk_size):
            written += ep_out.write(data[i:i + chunk_size], timeout=10000)
        time.sleep(0.2)
        status = _drain(ep_in)                            # 关键:取走回传数据

        msg = (f"已发送 {written} 字节 [iface {num} "
               f"cls{intf.bInterfaceClass}/sub{intf.bInterfaceSubClass}"
               f"/proto{intf.bInterfaceProtocol} ep0x{ep_out.bEndpointAddress:02x}]")
        if status:
            msg += f",回传 {len(status)} 字节"
        return msg

    finally:
        try:
            usb.util.release_interface(dev, num)
        except Exception:
            pass
        usb.util.dispose_resources(dev)


def send_to_printer(pcle: str):
    """把 PCLE 指令字符串送到打印机(中文按 GB18030 编码)"""
    return send_bytes(pcle.encode("gb18030", errors="replace"))


TEST_PAGE = """<!DOCTYPE html>
<html lang="zh-CN"><head><meta charset="utf-8"><title>ZMIN 打印服务</title>
<style>
 body{font-family:-apple-system,"PingFang SC",sans-serif;max-width:640px;
      margin:40px auto;padding:0 20px;color:#1b2a41;line-height:1.7}
 h1{font-size:20px} label{display:block;margin:14px 0 4px;font-size:13px;color:#556}
 input{width:100%;padding:8px 10px;border:1px solid #cfd6e0;border-radius:4px;font-size:14px}
 button{margin-top:20px;padding:10px 22px;background:#1b2a41;color:#fff;border:0;
        border-radius:4px;font-size:15px;cursor:pointer}
 #msg{margin-top:16px;font-size:14px}
 .row{display:flex;gap:12px}.row>div{flex:1}
</style></head><body>
<h1>ZMIN X1i 打印服务已运行 (v2.0)</h1>
<p>自检页。标签工具通过 <code>/raw</code> 发送位图打印。</p>
<div class="row"><div><label>标签宽 (mm)</label><input id="w" value="60"></div>
<div><label>标签高 (mm)</label><input id="h" value="30"></div></div>
<label>文字</label><input id="t" value="GJYF-ZNJG-Y-L">
<label>条码内容</label><input id="b" value="SN20260718001">
<button onclick="go()">打印测试标签</button>
<div id="msg"></div>
<script>
async function go(){
  const m=document.getElementById('msg'); m.textContent='发送中…';
  try{
    const r=await fetch('/selftest',{method:'POST',headers:{'Content-Type':'application/json'},
      body:JSON.stringify({ selftest:true, width_mm:+w.value, height_mm:+h.value })});
    const j=await r.json();
    m.textContent = j.ok ? '✓ 已送出:'+j.result : '✗ 失败:'+j.error;
    m.style.color = j.ok ? '#1f6e5c' : '#b3261e';
  }catch(e){ m.textContent='✗ '+e; m.style.color='#b3261e'; }
}
</script></body></html>"""


class Handler(http.server.BaseHTTPRequestHandler):

    def _cors(self):
        self.send_header("Access-Control-Allow-Origin", "*")
        self.send_header("Access-Control-Allow-Headers", "Content-Type")
        self.send_header("Access-Control-Allow-Methods", "POST, GET, OPTIONS")

    def _json(self, code, obj):
        body = json.dumps(obj, ensure_ascii=False).encode()
        self.send_response(code)
        self.send_header("Content-Type", "application/json; charset=utf-8")
        self.send_header("Content-Length", str(len(body)))
        self._cors()
        self.end_headers()
        self.wfile.write(body)

    def do_OPTIONS(self):
        self.send_response(204)
        self._cors()
        self.end_headers()

    def do_GET(self):
        if self.path in ("/", "/index.html"):
            body = TEST_PAGE.encode()
            self.send_response(200)
            self.send_header("Content-Type", "text/html; charset=utf-8")
            self.send_header("Content-Length", str(len(body)))
            self._cors()
            self.end_headers()
            self.wfile.write(body)
        elif self.path == "/devinfo":
            devs = []
            for dev, intf in _all_printer_ifaces():
                try:
                    eps = []
                    for e in intf:
                        eps.append({
                            "addr": f"0x{e.bEndpointAddress:02x}",
                            "dir": ("IN" if usb.util.endpoint_direction(e.bEndpointAddress)
                                    == usb.util.ENDPOINT_IN else "OUT"),
                            "type": usb.util.endpoint_type(e.bmAttributes),
                            "max": e.wMaxPacketSize,
                        })
                    devs.append({
                        "vid": f"0x{dev.idVendor:04x}", "pid": f"0x{dev.idProduct:04x}",
                        "iface": intf.bInterfaceNumber,
                        "class": intf.bInterfaceClass,
                        "subclass": intf.bInterfaceSubClass,
                        "protocol": intf.bInterfaceProtocol,
                        "endpoints": eps,
                    })
                except Exception as e:
                    devs.append({"error": str(e)})
            chosen = None
            try:
                _d, _i = _find_printer()
                if _i is not None:
                    chosen = _i.bInterfaceNumber
            except Exception:
                pass
            self._json(200, {"ok": True, "interfaces": devs, "chosen_iface": chosen})

        elif self.path == "/status":
            self._json(200, {
                "ok": True, "printer": "ZMIN (USB 直连)", "dpi": DPI,
                "connected": printer_present(),
            })
        else:
            self._json(404, {"ok": False, "error": "not found"})

    def do_POST(self):
        length = int(self.headers.get("Content-Length", 0))
        raw = self.rfile.read(length).decode("utf-8", "replace")
        try:
            job = json.loads(raw) if raw else {}
        except json.JSONDecodeError as e:
            return self._json(400, {"ok": False, "error": f"JSON 解析失败: {e}"})

        try:
            if self.path == "/selftest":
                # 位图自检：与标签工具同一条路径（GW 位图）
                wmm = float(job.get("width_mm", 60) or 60)
                hmm = float(job.get("height_mm", 30) or 30)
                wD, hD = mm(wmm), mm(hmm)
                bpr = (wD + 7) // 8
                # GW 位图约定：位 1 = 白，位 0 = 黑（与标签工具一致）
                bmp = bytearray(b"\xff" * (bpr * hD))
                def setpx(x, y):
                    if 0 <= x < wD and 0 <= y < hD:
                        bmp[y * bpr + (x >> 3)] &= ~(0x80 >> (x & 7)) & 0xFF
                for x in range(wD):          # 上下边框
                    for y in list(range(0, 6)) + list(range(hD - 6, hD)):
                        setpx(x, y)
                for y in range(hD):          # 左右边框
                    for x in list(range(0, 6)) + list(range(wD - 6, wD)):
                        setpx(x, y)
                for y in range(hD // 2 - mm(3), hD // 2 + mm(3)):   # 中间黑条
                    for x in range(mm(5), wD - mm(5)):
                        setpx(x, y)
                head = ("N\rq%d\rQ%d,%d\rH10\rS3\rGW0,0,%d,%d," % (wD, hD, mm(2), bpr, hD)).encode()
                tail = b"\rW1,1\r"
                result = send_bytes(head + bytes(bmp) + tail)
                self._json(200, {"ok": True, "result": result})

            elif self.path == "/raw":
                # 可选 iface：指定写入哪个打印机类接口（诊断用，缺省自动优选 bulk）
                ifn = job.get("iface")
                if job.get("b64"):
                    result = send_bytes(base64.b64decode(job["b64"]), ifn)
                else:
                    result = send_bytes(
                        job.get("pcle", "").encode("gb18030", errors="replace"), ifn)
                self._json(200, {"ok": True, "result": result})

            else:
                self._json(404, {"ok": False, "error": "not found"})

        except Exception as e:
            self._json(500, {"ok": False, "error": str(e)})

    def log_message(self, fmt, *args):
        print(f"  {self.address_string()} - {fmt % args}")


if __name__ == "__main__":
    socketserver.TCPServer.allow_reuse_address = True

    with socketserver.TCPServer(("127.0.0.1", PORT), Handler) as httpd:
        print("ZMIN 打印服务已启动 (v2.0 · 精简版)")
        print(f"  网页接口:  http://localhost:{PORT}/")
        print(f"  分辨率:    {DPI} DPI")
        print(f"  打印机在线: {'是' if printer_present() else '否 — 请检查电源与 USB'}")
        print("按 Control-C 停止\n")
        try:
            httpd.serve_forever()
        except KeyboardInterrupt:
            print("\n已停止")
