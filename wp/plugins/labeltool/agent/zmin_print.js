/* ════════════════════════════════════════════════════════════════════
   zmin_print.js —— ZMIN X1i 位图打印对接模块
   ────────────────────────────────────────────────────────────────────
   用法只有一句话:把标签画到一张 canvas 上,交给这里,它负责变成
   打印机能吃的东西。任意尺寸、任意内容、中文和排版所见即所得。

       await ZminPrint.print(myCanvas, { widthMm: 100, heightMm: 60 });

   依赖:本机运行的打印服务(默认 http://localhost:8631)。
   ════════════════════════════════════════════════════════════════════ */

window.ZminPrint = window.ZminPrint || (() => {

  const CFG = {
    api:   'http://localhost:8631',
    dpi:   300,          // X1i 为 300 DPI
    gapMm: 2,            // 标签间隙
    darkness: 10,        // 0–20
    speed:    3,         // 1–6
    threshold: 128       // 二值化阈值,越大越容易判为黑
  };

  const mmToDots = mm => Math.round(mm * CFG.dpi / 25.4);

  /* ── 1. 把任意 canvas 按目标尺寸重绘为精确点阵 ── */
  function toExactCanvas(src, wDots, hDots) {
    if (src.width === wDots && src.height === hDots) return src;
    const c = document.createElement('canvas');
    c.width = wDots; c.height = hDots;
    const g = c.getContext('2d');
    g.fillStyle = '#fff';
    g.fillRect(0, 0, wDots, hDots);
    g.imageSmoothingEnabled = false;          // 条码线条必须锐利,不能插值
    g.drawImage(src, 0, 0, wDots, hDots);
    return c;
  }

  /* ── 2. 二值化并打包成 1-bit 位图 ──
     PCLE 的 GW 指令约定:bit = 0 打印,bit = 1 不打印(与常规相反)  */
  function pack1bit(canvas, wDots, hDots) {
    const px = canvas.getContext('2d').getImageData(0, 0, wDots, hDots).data;
    const bytesPerRow = Math.ceil(wDots / 8);
    const out = new Uint8Array(bytesPerRow * hDots).fill(0xFF);   // 默认全白

    for (let y = 0; y < hDots; y++) {
      for (let x = 0; x < wDots; x++) {
        const i = (y * wDots + x) * 4;
        const a = px[i + 3];
        // 亮度加权,透明区域视为白
        const lum = a < 16 ? 255 : (px[i] * 0.299 + px[i + 1] * 0.587 + px[i + 2] * 0.114);
        if (lum < CFG.threshold) {
          const bit = 7 - (x % 8);
          out[y * bytesPerRow + (x >> 3)] &= ~(1 << bit);         // 置 0 = 打印
        }
      }
    }
    return { data: out, bytesPerRow };
  }

  /* ── 3. 组装 PCLE 指令(ASCII 头 + 二进制位图 + ASCII 尾) ── */
  function buildJob(bitmap, wDots, hDots, opt) {
    const enc = new TextEncoder();
    const head = enc.encode(
      'N\r' +
      `q${wDots}\r` +
      `Q${hDots},${mmToDots(opt.gapMm)}\r` +
      `H${opt.darkness}\r` +
      `S${opt.speed}\r` +
      `GW0,0,${bitmap.bytesPerRow},${hDots}`
    );
    const tail = enc.encode(`\rW${opt.copies}\r`);

    const job = new Uint8Array(head.length + bitmap.data.length + tail.length);
    job.set(head, 0);
    job.set(bitmap.data, head.length);
    job.set(tail, head.length + bitmap.data.length);
    return job;
  }

  /* ── 4. 二进制转 base64(分块处理,避免大位图爆栈) ── */
  function toBase64(bytes) {
    let s = '';
    const CHUNK = 0x8000;
    for (let i = 0; i < bytes.length; i += CHUNK)
      s += String.fromCharCode.apply(null, bytes.subarray(i, i + CHUNK));
    return btoa(s);
  }

  /* ── 对外接口 ── */

  /** 打印一张 canvas。widthMm / heightMm 必填,其余可选。 */
  async function print(canvas, opt = {}) {
    const o = {
      gapMm:    opt.gapMm    ?? CFG.gapMm,
      darkness: opt.darkness ?? CFG.darkness,
      speed:    opt.speed    ?? CFG.speed,
      copies:   opt.copies   ?? 1
    };
    if (!opt.widthMm || !opt.heightMm)
      throw new Error('必须提供 widthMm 与 heightMm');

    const wDots = mmToDots(opt.widthMm);
    const hDots = mmToDots(opt.heightMm);
    const exact = toExactCanvas(canvas, wDots, hDots);
    const bmp   = pack1bit(exact, wDots, hDots);
    const job   = buildJob(bmp, wDots, hDots, o);

    const r = await fetch(CFG.api + '/raw', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ b64: toBase64(job) })
    });
    const j = await r.json();
    if (!j.ok) throw new Error(j.error || '打印失败');
    return { ...j, wDots, hDots, bytes: job.length };
  }

  /** 查询打印机是否在线 */
  async function status() {
    try {
      const r = await fetch(CFG.api + '/status', { signal: AbortSignal.timeout(2000) });
      return await r.json();
    } catch {
      return { ok: false, connected: false, error: '打印服务未运行' };
    }
  }

  /** 创建一张按毫米尺寸、300dpi 对齐的画布,画完直接交给 print() */
  function createCanvas(widthMm, heightMm) {
    const c = document.createElement('canvas');
    c.width  = mmToDots(widthMm);
    c.height = mmToDots(heightMm);
    const g = c.getContext('2d');
    g.fillStyle = '#fff';
    g.fillRect(0, 0, c.width, c.height);
    g.fillStyle = '#000';
    c.mm  = mm => mm * CFG.dpi / 25.4;      // 毫米 → 像素,画图时用
    c.ctx = g;
    return c;
  }

  /** 走纸一张空白标签,用于校验尺寸设置 */
  async function feed(widthMm, heightMm, gapMm = CFG.gapMm) {
    const cmd = `N\rq${mmToDots(widthMm)}\rQ${mmToDots(heightMm)},${mmToDots(gapMm)}\rW1\r`;
    const r = await fetch(CFG.api + '/raw', {
      method: 'POST', headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ pcle: cmd })
    });
    return await r.json();
  }

  return { print, status, createCanvas, feed, config: CFG, mmToDots };
})();

if (typeof module !== 'undefined') module.exports = window.ZminPrint;
