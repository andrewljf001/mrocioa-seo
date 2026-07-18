import {readFileSync, writeFileSync} from 'node:fs';
import {gzipSync, gunzipSync} from 'node:zlib';

const htmlPath = process.argv[2] || 'wp/woodmart-child/assets/serial-tool/mrocioa-serial-tool.html';
let html = readFileSync(htmlPath, 'utf8');
const manifestPattern = /(<script type="__bundler\/manifest">\s*)(\{[\s\S]*?\})(\s*<\/script>)/;
const match = html.match(manifestPattern);
if (!match) throw new Error('Bundler manifest not found');

const manifest = JSON.parse(match[2]);
let changed = 0;
for (const entry of Object.values(manifest)) {
  if (!/(?:javascript|jsx|text)/i.test(entry.mime)) continue;
  const packed = Buffer.from(entry.data, 'base64');
  const unpacked = entry.compressed ? gunzipSync(packed) : packed;
  const source = unpacked.toString('utf8');
  const normalized = source.replace(/Release 1\.0(?!\.)/g, 'Release 1.0.1');
  if (normalized === source) continue;
  const output = Buffer.from(normalized, 'utf8');
  entry.data = (entry.compressed ? gzipSync(output, {level: 9}) : output).toString('base64');
  changed += 1;
}

if (changed !== 1) throw new Error(`Expected one hidden compiled resource to normalize, found ${changed}`);
const serialized = JSON.stringify(manifest).replace(/<\//g, '<\\/');
html = html.replace(manifestPattern, `$1${serialized}$3`);
writeFileSync(htmlPath, html);
console.log(`Normalized hidden compiled version record in ${changed} resource.`);
