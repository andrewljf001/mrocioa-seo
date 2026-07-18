import assert from 'node:assert/strict';
import fs from 'node:fs';
import vm from 'node:vm';
import {gunzipSync} from 'node:zlib';

const sourceUrl = new URL('../serial-tool-source/ProtocolPanel.jsx', import.meta.url);
const source = fs.readFileSync(sourceUrl, 'utf8');
const parserOnly = source.slice(0, source.indexOf('function ProtocolPanel'));
const context = {
  window: {MrocioaDesignSystem_b37fe9: {}},
  React: {createContext: value => ({value})},
  console,
};
vm.runInNewContext(
  parserOnly + '\nglobalThis.parsers={detect,decodeLine,protoList};',
  context,
  {filename: sourceUrl.pathname},
);

const {detect, decodeLine, protoList} = context.parsers;
const fixtures = [
  ['AT+GMR', 'at'],
  ['$GPGGA,123519,4807.038,N,01131.000,E,1,08,0.9,545.4,M,46.9,M,,*47', 'nmea'],
  ['sensor = {"temperature":23.5,"ok":true}', 'json'],
  ['01 03 00 00 00 0A C5 CD', 'modbus'],
  [':01030000000AF2', 'modbus-ascii'],
  ['cec rx = 40 44 41', 'cec'],
  ['cec src=4 dst=0 opcode=0x44 data=41', 'cec'],
  ['ir code = 0xE718FF00', 'ir'],
  ['IR code = ????', 'ir'],
  ['i2c write addr=0x50 reg=0x10 data=AA 55 ACK', 'i2c'],
  ['spi tx=9F rx=EF 40 18 mode=0 cs=0 speed=20MHz', 'spi'],
  ['RS485 RX baud=115200 frame=8N1 data=48 69', 'uart'],
  ['1-Wire ROM=28FF4C60161703A7', 'onewire'],
  ['CAN RX id=0x7E8 data=03 41 0D 28', 'can'],
  ['LIN RX id=0x12 data=01 02 03 checksum=0xD5', 'lin'],
  ['DMX RX = 00 FF 80 00', 'dmx'],
  ['B5 62 05 01 02 00 06 00 0E 37', 'ubx'],
  ['FE 00 01 01 01 00 00 00', 'mavlink'],
  ['G1 X10.0 Y20.0 F1200', 'gcode'],
  ['00 FF FF FF FF FF FF 00', 'edid'],
];

for (const [line, expected] of fixtures) {
  assert.equal(detect(line), expected, `detect(${JSON.stringify(line)})`);
  const rows = decodeLine(line, expected);
  assert.ok(rows.length > 0, `${expected} should return decoded rows`);
}

const irRows = decodeLine('ir code = 0xE718FF00', 'ir');
assert.ok(irRows.some(row => row.f.includes('IR CODE') && row.v === '0xE718FF00'));

const unknownIrRows = decodeLine('IR code = ????', 'ir');
assert.ok(unknownIrRows.some(row => row.f.includes('IR CODE') && row.v === '????'));

const cecRows = decodeLine('cec rx = 40 44 41', 'cec');
assert.ok(cecRows.some(row => row.f.includes('OPCODE') && row.v === '0x44'));
assert.ok(cecRows.some(row => row.f.includes('UI COMMAND') && row.m === 'Volume Up'));

const i2cRows = decodeLine('i2c write addr=0x50 reg=0x10 data=AA 55 ACK', 'i2c');
assert.ok(i2cRows.some(row => row.f.includes('ADDRESS') && row.v === '0x50'));
assert.ok(i2cRows.some(row => row.f.includes('DATA') && row.v === 'AA 55'));

const spiRows = decodeLine('spi tx=9F rx=EF 40 18 mode=0', 'spi');
assert.ok(spiRows.some(row => row.f.includes('COMMAND') && row.m === 'Read JEDEC ID'));
assert.ok(spiRows.some(row => row.f.includes('JEDEC ID') && row.v === 'EF 40 18'));

const canRows = decodeLine('CAN RX id=0x7E8 data=03 41 0D 28', 'can');
assert.ok(canRows.some(row => row.f === 'CAN ID' && row.v === '0x7E8'));
assert.ok(canRows.some(row => row.f.includes('VALUE') && row.v === '40 km/h'));

for (const required of ['i2c', 'spi', 'uart', 'onewire', 'cec', 'ir']) {
  assert.ok(protoList.some(([, value]) => value === required), `${required} must be selectable`);
}

const asset = fs.readFileSync(new URL('../wp/woodmart-child/assets/serial-tool/mrocioa-serial-tool.html', import.meta.url), 'utf8');
const manifestMatch = asset.match(/<script type="__bundler\/manifest">\s*(\{[\s\S]*?\})\s*<\/script>/);
assert.ok(manifestMatch, 'packed asset manifest must exist');
const manifest = JSON.parse(manifestMatch[1]);
const packedProtocolSources = Object.values(manifest).flatMap(entry => {
  if (!/(?:javascript|jsx|text)/i.test(entry.mime)) return [];
  const packed = Buffer.from(entry.data, 'base64');
  const unpacked = entry.compressed ? gunzipSync(packed) : packed;
  const text = unpacked.toString('utf8');
  return text.includes('window.ProtocolPanel=ProtocolPanel') ? [text] : [];
});
assert.equal(packedProtocolSources.length, 1, 'packed asset must contain one raw ProtocolPanel resource');
assert.equal(packedProtocolSources[0], source, 'packed ProtocolPanel must exactly match the maintained source');

console.log(`Protocol parser checks passed: ${fixtures.length} detection fixtures.`);
