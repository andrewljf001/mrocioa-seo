import {readFileSync, writeFileSync} from 'node:fs';
import {gzipSync, gunzipSync} from 'node:zlib';

const htmlPath = process.argv[2] || 'wp/woodmart-child/assets/serial-tool/mrocioa-serial-tool.html';
const sourcePath = process.argv[3] || 'serial-tool-source/ProtocolPanel.jsx';
const source = readFileSync(sourcePath, 'utf8');
let html = readFileSync(htmlPath, 'utf8');
const manifestPattern = /(<script type="__bundler\/manifest">\s*)(\{[\s\S]*?\})(\s*<\/script>)/;
const match = html.match(manifestPattern);
if (!match) throw new Error('Bundler manifest not found');

const manifest = JSON.parse(match[2]);
const targets = [];
for (const [uuid, entry] of Object.entries(manifest)) {
  if (!/(?:javascript|jsx|text)/i.test(entry.mime)) continue;
  const packed = Buffer.from(entry.data, 'base64');
  const unpacked = entry.compressed ? gunzipSync(packed) : packed;
  const current = unpacked.toString('utf8');
  if (!current.includes('window.ProtocolPanel=ProtocolPanel')) continue;
  const output = Buffer.from(source, 'utf8');
  entry.data = (entry.compressed ? gzipSync(output, {level: 9}) : output).toString('base64');
  targets.push(uuid);
}

if (targets.length !== 1) {
  throw new Error(`Expected one raw ProtocolPanel resource, found ${targets.length}`);
}

const serialized = JSON.stringify(manifest).replace(/<\//g, '<\\/');
html = html.replace(manifestPattern, `$1${serialized}$3`);
writeFileSync(htmlPath, html);
console.log(`Updated ProtocolPanel resource ${targets[0]} in ${htmlPath}`);
