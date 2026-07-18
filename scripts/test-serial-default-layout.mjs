import assert from 'node:assert/strict';
import fs from 'node:fs';
import vm from 'node:vm';
import {gunzipSync} from 'node:zlib';

const sourceUrl = new URL('../serial-tool-source/MainScreen.jsx', import.meta.url);
const source = fs.readFileSync(sourceUrl, 'utf8');
const start = source.indexOf("const LAYOUT_KEY=");
const end = source.indexOf('function MainScreen');
assert.ok(start >= 0 && end > start, 'layout loader must exist before MainScreen');

function runLoader(initial = {}) {
  const values = new Map(Object.entries(initial));
  const localStorage = {
    getItem: key => values.has(key) ? values.get(key) : null,
    setItem: (key, value) => values.set(key, String(value)),
  };
  const context = {localStorage};
  vm.runInNewContext(source.slice(start, end) + '\nglobalThis.load=loadDefaultLayout;', context);
  return {layout: context.load(), values};
}

const fresh = runLoader();
assert.equal(fresh.layout, '4');
assert.equal(fresh.values.get('mrocioa.layout'), '4');

const upgraded = runLoader({'mrocioa.layout': '1'});
assert.equal(upgraded.layout, '4', 'old default single layout must migrate once');

const userSingle = runLoader({'mrocioa.layout': '1', 'mrocioa.layout-default-1+3-v1': '1'});
assert.equal(userSingle.layout, '1', 'user choice must persist after migration');

const invalid = runLoader({'mrocioa.layout': 'broken', 'mrocioa.layout-default-1+3-v1': '1'});
assert.equal(invalid.layout, '4');

const asset = fs.readFileSync(new URL('../wp/woodmart-child/assets/serial-tool/mrocioa-serial-tool.html', import.meta.url), 'utf8');
const manifestMatch = asset.match(/<script type="__bundler\/manifest">\s*(\{[\s\S]*?\})\s*<\/script>/);
assert.ok(manifestMatch, 'packed asset manifest must exist');
const manifest = JSON.parse(manifestMatch[1]);
const packedSources = Object.values(manifest).flatMap(entry => {
  if (!/(?:javascript|jsx|text)/i.test(entry.mime)) return [];
  const packed = Buffer.from(entry.data, 'base64');
  const unpacked = entry.compressed ? gunzipSync(packed) : packed;
  const text = unpacked.toString('utf8');
  return text.includes('window.MainScreen=MainScreen') ? [text] : [];
});
assert.equal(packedSources.length, 1, 'packed asset must contain one raw MainScreen resource');
assert.equal(packedSources[0], source, 'packed MainScreen must exactly match maintained source');

console.log('Default 1+3 layout checks passed.');
