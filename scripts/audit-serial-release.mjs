import assert from 'node:assert/strict';
import fs from 'node:fs';
import {gunzipSync} from 'node:zlib';

const root = new URL('../', import.meta.url);
const version = fs.readFileSync(new URL('serial-tool-source/VERSION', root), 'utf8').trim();
const changelog = fs.readFileSync(new URL('serial-tool-source/CHANGELOG.md', root), 'utf8');
const mainSource = fs.readFileSync(new URL('serial-tool-source/MainScreen.jsx', root), 'utf8');
const protocolSource = fs.readFileSync(new URL('serial-tool-source/ProtocolPanel.jsx', root), 'utf8');
const asset = fs.readFileSync(new URL('wp/woodmart-child/assets/serial-tool/mrocioa-serial-tool.html', root), 'utf8');
const page = fs.readFileSync(new URL('wp/woodmart-child/page-serial-tool.php', root), 'utf8');

assert.equal(version, '1.0.1');
assert.match(changelog, /^## 1\.0\.1 — 2026-07-18/m);
assert.match(mainSource, /Release 1\.0\.1 · Stable release/);
assert.match(mainSource, /mrocioa web serial debugger · Release 1\.0\.1/);
assert.doesNotMatch(mainSource, /v0\.2\.3|dev build|开发调试版本/);
assert.match(page, /request\.call\(stage, \{ navigationUI: 'hide' \}\)/);

const manifestMatch = asset.match(/<script type="__bundler\/manifest">\s*(\{[\s\S]*?\})\s*<\/script>/);
assert.ok(manifestMatch, 'packed asset manifest must exist');
const manifest = JSON.parse(manifestMatch[1]);
const textResources = Object.entries(manifest).flatMap(([uuid, entry]) => {
  if (!/(?:javascript|jsx|text)/i.test(entry.mime)) return [];
  const packed = Buffer.from(entry.data, 'base64');
  const unpacked = entry.compressed ? gunzipSync(packed) : packed;
  return [{uuid, source: unpacked.toString('utf8')}];
});

const mainResources = textResources.filter(item => item.source.includes('window.MainScreen=MainScreen'));
const protocolResources = textResources.filter(item => item.source.includes('window.ProtocolPanel=ProtocolPanel'));
assert.equal(mainResources.length, 1);
assert.equal(protocolResources.length, 1);
assert.equal(mainResources[0].source, mainSource);
assert.equal(protocolResources[0].source, protocolSource);

for (const item of textResources) {
  assert.doesNotMatch(item.source, /v0\.2\.3|dev build|开发调试版本/, `development record in ${item.uuid}`);
  assert.doesNotMatch(item.source, /Release 1\.0(?!\.)/, `non-canonical Release 1.0 label in ${item.uuid}`);
}

assert.match(mainResources[0].source, /localStorage/);
assert.ok(asset.length > 100_000, 'packed release asset is unexpectedly small');

console.log(JSON.stringify({
  version,
  assetBytes: Buffer.byteLength(asset),
  resources: textResources.length,
  mainResource: mainResources[0].uuid,
  protocolResource: protocolResources[0].uuid,
}, null, 2));
