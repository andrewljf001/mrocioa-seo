import assert from 'node:assert/strict';
import fs from 'node:fs';

const template = fs.readFileSync(new URL('../wp/woodmart-child/page-serial-tool.php', import.meta.url), 'utf8');
const script = template.match(/<script data-cfasync="false" id="mro-serial-tool-page-js">([\s\S]*?)<\/script>/)?.[1];
assert.ok(script, 'serial page script must exist');
assert.doesNotThrow(() => new Function(script), 'embedded page script must compile');

assert.match(template, /stage\.requestFullscreen \|\| stage\.webkitRequestFullscreen/);
assert.match(template, /result\.catch\(enterFocusMode\)/);
assert.match(template, /stage\.classList\.add\('is-focus-mode'\)/);
assert.match(template, /frame\.contentDocument\.addEventListener\('keydown', handleFullscreenEscape\)/);
assert.match(template, /mro-serial-stage:fullscreen \.mro-serial-frame/);
assert.match(template, /Exit full screen/);
assert.doesNotMatch(template, /class="mro-serial-fullscreen"[^>]*href=/);

assert.match(template, /<link rel="canonical"/);
assert.match(template, /"@type": "WebApplication"/);
assert.match(template, /Chrome \/ Edge required/);
assert.match(template, /if \(supported\)[\s\S]*frame\.src = frame\.dataset\.src/);
assert.match(template, /else \{[\s\S]*browserGate\.hidden = false;[\s\S]*fullScreen\.hidden = true/);

console.log('Serial page template checks passed.');
