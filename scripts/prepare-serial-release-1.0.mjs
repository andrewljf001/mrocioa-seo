import { readFileSync, writeFileSync } from 'node:fs';
import { gzipSync, gunzipSync } from 'node:zlib';

const inputPath = process.argv[2];
const outputPath = process.argv[3] || inputPath;

if (!inputPath) {
  throw new Error('Usage: node scripts/prepare-serial-release-1.0.mjs <input.html> [output.html]');
}

const oldNote = `<div>{t('// 当前为开发调试版本，正式更改记录自 Release 1.0 起','// dev build — changelog starts at Release 1.0')}</div>`;
const releaseNotes = `<div>{t('Release 1.0 · 正式发行','Release 1.0 · Stable release')}</div>
              <div>{t('• 最多 4 个串口窗口，支持 MAIN / SUB 布局','• Up to 4 serial sessions with MAIN / SUB layouts')}</div>
              <div>{t('• Web Serial 授权、自动发现、插拔检测与自动重连','• Web Serial permission, discovery, hot-plug detection and auto reconnect')}</div>
              <div>{t('• ASCII / HEX 收发、文件发送、快捷发送与循环序列','• ASCII / HEX I/O, file transfer, quick send and looping sequences')}</div>
              <div>{t('• 100,000 行监视缓冲、搜索、过滤、记录与导出','• 100,000-line monitor buffer, search, filter, recording and export')}</div>
              <div>{t('• 实时多通道图表、CSV / PNG 导出与游标测量','• Live multi-channel charts, CSV / PNG export and cursor measurement')}</div>
              <div>{t('• AT、NMEA、JSON、Modbus RTU 与 EDID 协议解析','• AT, NMEA, JSON, Modbus RTU and EDID protocol decoding')}</div>
              <div>{t('• 数据仅在本机浏览器处理，不上传服务器','• Data is processed locally in the browser and is never uploaded')}</div>`;
const transpiledOldNote = `/*#__PURE__*/React.createElement("div", null, t('// 当前为开发调试版本，正式更改记录自 Release 1.0 起', '// dev build — changelog starts at Release 1.0'))`;
const releaseNotePairs = [
  ['Release 1.0 · 正式发行', 'Release 1.0 · Stable release'],
  ['• 最多 4 个串口窗口，支持 MAIN / SUB 布局', '• Up to 4 serial sessions with MAIN / SUB layouts'],
  ['• Web Serial 授权、自动发现、插拔检测与自动重连', '• Web Serial permission, discovery, hot-plug detection and auto reconnect'],
  ['• ASCII / HEX 收发、文件发送、快捷发送与循环序列', '• ASCII / HEX I/O, file transfer, quick send and looping sequences'],
  ['• 100,000 行监视缓冲、搜索、过滤、记录与导出', '• 100,000-line monitor buffer, search, filter, recording and export'],
  ['• 实时多通道图表、CSV / PNG 导出与游标测量', '• Live multi-channel charts, CSV / PNG export and cursor measurement'],
  ['• AT、NMEA、JSON、Modbus RTU 与 EDID 协议解析', '• AT, NMEA, JSON, Modbus RTU and EDID protocol decoding'],
  ['• 数据仅在本机浏览器处理，不上传服务器', '• Data is processed locally in the browser and is never uploaded'],
];
const transpiledReleaseNotes = releaseNotePairs
  .map(([zh, en]) => `/*#__PURE__*/React.createElement("div", null, t(${JSON.stringify(zh)}, ${JSON.stringify(en)}))`)
  .join(', ');
const quickSendDefaults = [
  ['查询版本', 'Firmware Version'],
  ['复位', 'Reset'],
  ['扫描 WiFi', 'Scan WiFi'],
  ['心跳', 'Heartbeat'],
];

function promoteSource(source, label) {
  const versionMatches = source.match(/v0\.2\.3/g) || [];
  let output = source;
  let changed = false;

  if (versionMatches.length) {
    const hasJsxNote = output.includes(oldNote);
    const hasTranspiledNote = output.includes(transpiledOldNote);
    if (versionMatches.length !== 2 || (!hasJsxNote && !hasTranspiledNote)) {
      const marker = output.search(/v0\.2\.3|dev build|开发调试版本/);
      const excerpt = marker >= 0 ? output.slice(Math.max(0, marker - 180), marker + 1400) : '';
      throw new Error(`${label}: expected two v0.2.3 labels and one development note; count=${versionMatches.length}; jsxNote=${hasJsxNote}; transpiledNote=${hasTranspiledNote}; excerpt=${JSON.stringify(excerpt)}`);
    }
    output = hasJsxNote
      ? output.replace(oldNote, releaseNotes)
      : output.replace(transpiledOldNote, transpiledReleaseNotes);
    output = output.replaceAll('v0.2.3', 'Release 1.0');
    changed = true;
  } else if (!output.includes('Release 1.0 · Stable release')) {
    throw new Error(`${label}: neither development nor Release 1.0 notes were found`);
  }

  const languagePattern = /localStorage\.getItem\('mrocioa\.lang'\)(\s*\|\|\s*)'bi'([\s\S]{0,100}?catch\s*\([^)]*\)\s*\{\s*return\s*)'bi'/;
  if (languagePattern.test(output)) {
    output = output.replace(languagePattern, (_match, separator, catchPrefix) =>
      `localStorage.getItem('mrocioa.lang')${separator}'en'${catchPrefix}'en'`);
    changed = true;
  } else if (!/localStorage\.getItem\('mrocioa\.lang'\)(\s*\|\|\s*)'en'/.test(output)) {
    throw new Error(`${label}: default language setting was not found`);
  }

  for (const [developmentLabel, releaseLabel] of quickSendDefaults) {
    if (output.includes(`'${developmentLabel}'`)) {
      output = output.replaceAll(`'${developmentLabel}'`, `'${releaseLabel}'`);
      changed = true;
    }
  }

  if (/v0\.2\.3|dev build|开发调试版本/.test(output)) {
    throw new Error(`${label}: development version record remains after promotion`);
  }
  if (!output.includes('Release 1.0 · Stable release') ||
      !output.includes('Data is processed locally in the browser and is never uploaded')) {
    throw new Error(`${label}: formal Release 1.0 feature record is incomplete`);
  }
  if (!/localStorage\.getItem\('mrocioa\.lang'\)(\s*\|\|\s*)'en'/.test(output)) {
    throw new Error(`${label}: English is not the default language`);
  }
  for (const [developmentLabel] of quickSendDefaults) {
    if (output.includes(`'${developmentLabel}'`)) {
      throw new Error(`${label}: non-English quick-send default remains: ${developmentLabel}`);
    }
  }

  return {
    source: output,
    changed,
  };
}

let html = readFileSync(inputPath, 'utf8');
const manifestPattern = /(<script type="__bundler\/manifest">\s*)(\{[\s\S]*?\})(\s*<\/script>)/;
const match = html.match(manifestPattern);
if (!match) throw new Error('Bundler manifest not found');

const manifest = JSON.parse(match[2]);
let matchedResources = 0;
let quickSendResources = 0;
const diagnostics = [];

for (const [uuid, entry] of Object.entries(manifest)) {
  if (!/(?:javascript|jsx|text)/i.test(entry.mime)) continue;
  const packed = Buffer.from(entry.data, 'base64');
  const unpacked = entry.compressed ? gunzipSync(packed) : packed;
  const source = unpacked.toString('utf8');
  if (/CHANGELOG|版本说明|dev build|v0\.|setVerOpen|SYSTEM SETTINGS/i.test(source)) {
    diagnostics.push({
      uuid,
      versions: [...new Set(source.match(/(?:Release\s+)?v?\d+\.\d+(?:\.\d+)?/gi) || [])],
      hasChangelog: /CHANGELOG|版本说明/i.test(source),
      hasDevNote: /dev build|开发调试版本/i.test(source),
    });
  }
  const isReleaseResource = source.includes('v0.2.3') || source.includes('Release 1.0 · Stable release');
  let promoted = isReleaseResource
    ? promoteSource(source, `resource ${uuid}`)
    : { source, changed: false };

  if (quickSendDefaults.some(([developmentLabel, releaseLabel]) =>
    promoted.source.includes(`'${developmentLabel}'`) || promoted.source.includes(`'${releaseLabel}'`))) {
    quickSendResources += 1;
    for (const [developmentLabel, releaseLabel] of quickSendDefaults) {
      if (promoted.source.includes(`'${developmentLabel}'`)) {
        promoted.source = promoted.source.replaceAll(`'${developmentLabel}'`, `'${releaseLabel}'`);
        promoted.changed = true;
      }
    }
  }

  if (promoted.changed) {
    const output = Buffer.from(promoted.source, 'utf8');
    entry.data = (entry.compressed ? gzipSync(output, { level: 9 }) : output).toString('base64');
  }
  if (isReleaseResource) matchedResources += 1;
}

if (matchedResources !== 2) {
  throw new Error(`Expected exactly two application resources, found ${matchedResources}; diagnostics=${JSON.stringify(diagnostics)}`);
}
if (quickSendResources !== 2) {
  throw new Error(`Expected exactly two quick-send resources, found ${quickSendResources}`);
}

const serializedManifest = JSON.stringify(manifest).replace(/<\//g, '<\\/');
html = html.replace(manifestPattern, `$1${serializedManifest}$3`);
writeFileSync(outputPath, html);

console.log(`Prepared Release 1.0: ${outputPath}`);
