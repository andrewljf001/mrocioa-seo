# MROCIOA Web Serial Debugger 1.0.1 Release Report

- Release date: 2026-07-18
- Status: Stable
- Production URL: `https://mrocioa.com/web-serial-debugger/`
- Release tag: `serial-tool-v1.0.1`
- Implementation commit: `bc206bb`

## Released scope

1. True browser fullscreen for the existing serial-tool iframe, with the site Header, Hero, product rotator and Footer removed from the fullscreen view, an on-screen exit control, `Esc` support and a page-filling fallback.
2. Semantic protocol detection for communication debug logs printed through the serial connection, including IR, HDMI-CEC, I²C, SPI, UART/RS-232/RS-485, 1-Wire, CAN/OBD-II, LIN, DMX/RDM, UBX, MAVLink, IEC 62056-21, DL/T 645, G-code and Modbus ASCII.
3. A one-time migration to the `1+3` MAIN/SUB default layout while preserving later user-selected layouts.
4. Formal `Release 1.0.1` version and Changelog records with development-only version labels removed from the packed application.

Remote mobile viewing, session sharing and remote control are not part of this release.

## Verification

- Protocol detection fixtures: 20 passed.
- Packed ProtocolPanel source consistency: passed.
- Default-layout and one-time migration checks: passed.
- Page-template, fullscreen wiring and fallback checks: passed.
- PHP syntax checks on the local release file and VPS target: passed.
- Release manifest and packed-resource audit: passed for both the canonical package and the public asset.
- Native fullscreen on macOS desktop Chrome was manually accepted by the owner on 2026-07-18.
- Production page returned HTTP 200 and exposed the expected SEO title, meta description, canonical URL and WebApplication structured data.
- `https://mrocioa.com/web-serial-debugger/` remains present in the production page sitemap.

No physical serial device was attached to the release environment. The existing 1.0.0 Web Serial connection and transfer foundation was not changed by this release; the owner accepted the verified candidate and explicitly authorized deployment.

## Production files and integrity

Only these two production files were replaced:

| File | SHA-256 |
| --- | --- |
| `wp-content/themes/woodmart-child/page-serial-tool.php` | `81115f8d6b3520ba3af155ba4d479b88ffd10f57825d3583dc1d3e8b6ea0293d` |
| `wp-content/themes/woodmart-child/assets/serial-tool/mrocioa-serial-tool.html` | `87c5de0cedd734cd3b5ccda05f212350e0512db36fe9ac5b0f18de6d3b9ba611` |

The repository release files, local formal mirror and VPS source files have identical hashes.

Cloudflare adds a small speculation-rules block to the public response, so the outer public-response hash and byte size differ from the canonical source file. The embedded release manifest and all nine packed resources were audited successfully against the canonical package.

## Rollback

- VPS backup: `/var/www/mrocioa/wp-content/codex-backups/serial-tool-before-1.0.1-20260718-133134/`
- Previous page cache moved to: `/tmp/mrocioa-web-serial-cache-before-1.0.1-20260718-133134`
- Previous production source hashes:
  - Page template: `5bea114127c0b4b34b174f9fa19bf1855e0bf5963d2897f3876790b642a95a5d`
  - Serial application: `c65b720d504b8af9e779c3eaa4e9ce14b4231f472464a1f9e30758a15d98dd53`

The backup contains the complete pre-1.0.1 page template and serial application. Deployment did not change WordPress content, menus, the database or other page templates.

## SEO and discovery

The release uses the existing canonical URL, which was already indexed through the site structure and remains in the sitemap. No new public URL was introduced, so a separate Google or Bing URL submission was not required for this version-only update.
