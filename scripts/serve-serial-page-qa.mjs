import http from 'node:http';
import {readFileSync} from 'node:fs';

const port = Number(process.env.MRO_SERIAL_QA_PORT || 8766);
const templatePath = 'wp/woodmart-child/page-serial-tool.php';
const assetPath = 'wp/woodmart-child/assets/serial-tool/mrocioa-serial-tool.html';
const template = readFileSync(templatePath, 'utf8');
const css = template.match(/<style id="mro-serial-tool-page-css">([\s\S]*?)<\/style>/)?.[1];
const pageScript = template.match(/<script data-cfasync="false" id="mro-serial-tool-page-js">([\s\S]*?)<\/script>/)?.[1];
if (!css || !pageScript) throw new Error('Serial page CSS or script was not found');

const product = (name, detail, active = false) => `
  <a class="mro-serial-product-slide${active ? ' is-active' : ''}" href="#" aria-hidden="${active ? 'false' : 'true'}" tabindex="${active ? '0' : '-1'}">
    <span class="mro-serial-product-image" style="display:grid;place-items:center;color:#07141a;font-weight:800">M</span>
    <span class="mro-serial-product-copy"><span class="mro-serial-product-kicker">Featured hardware</span><strong class="mro-serial-product-name">${name}</strong><span class="mro-serial-product-detail">${detail}</span></span>
    <span class="mro-serial-product-arrow" aria-hidden="true">→</span>
  </a>`;

const page = `<!doctype html>
<html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Serial Tool Local QA</title>
<style>html,body{margin:0}#qa-header,#qa-footer{box-sizing:border-box;height:72px;padding:24px 32px;background:#fff;color:#111;font:700 16px Arial}#qa-footer{background:#111;color:#fff}${css}</style></head>
<body class="page-template-page-serial-tool"><header id="qa-header">MROCIOA · LOCAL QA HEADER</header>
<main id="primary" class="mro-serial-page">
  <section class="mro-serial-intro" aria-labelledby="mro-serial-title"><div class="mro-serial-intro-in">
    <div class="mro-serial-copy"><span class="mro-serial-eyebrow">MROCIOA Lab Tools</span><h1 id="mro-serial-title">Web Serial Debugger</h1><p>Connect, monitor, decode and export serial data directly in your browser. No installation, no upload — your device data stays on this computer.</p></div>
    <div class="mro-serial-products" id="mro-serial-products" aria-label="Featured MROCIOA products">${product('S5 Pro', '8K HDMI 2.1 Switch', true)}${product('Thunderbolt 5', '120Gbps · 240W Cable')}</div>
    <div class="mro-serial-actions"><span class="mro-serial-support" id="mro-serial-support">Checking Web Serial…</span><button class="mro-serial-fullscreen" type="button" aria-controls="mro-serial-stage" aria-pressed="false" hidden>Enter full screen&nbsp; ⛶</button></div>
  </div></section>
  <p class="mro-serial-mobile-note">Desktop Chrome or Microsoft Edge is recommended.</p>
  <section class="mro-serial-browser-gate" id="mro-serial-browser-gate" hidden><div class="mro-serial-browser-card"><span class="mro-serial-browser-icon">&gt;_</span><h2>Open this tool in Chrome or Microsoft Edge</h2><p>The Web Serial API is not available in your current browser.</p></div></section>
  <section class="mro-serial-stage" id="mro-serial-stage" aria-label="MROCIOA Web Serial application" hidden><button class="mro-serial-exit-fullscreen" id="mro-serial-exit-fullscreen" type="button" aria-label="Exit full screen" hidden>Exit full screen <span aria-hidden="true">· Esc</span></button><iframe class="mro-serial-frame" id="mro-serial-frame" data-src="/asset" title="MROCIOA Web Serial Debugger" allow="serial; screen-wake-lock" loading="eager" referrerpolicy="same-origin"></iframe></section>
</main><footer id="qa-footer">MROCIOA · LOCAL QA FOOTER</footer><script>${pageScript}<\/script></body></html>`;

const server = http.createServer((request, response) => {
  if (request.url === '/asset') {
    response.writeHead(200, {'content-type': 'text/html; charset=utf-8', 'cache-control': 'no-store'});
    response.end(readFileSync(assetPath));
    return;
  }
  response.writeHead(200, {'content-type': 'text/html; charset=utf-8', 'cache-control': 'no-store'});
  response.end(page);
});

server.listen(port, '127.0.0.1', () => {
  console.log(`Serial page QA: http://127.0.0.1:${port}/`);
});
