# MROCIOA Web Serial Debugger launch — 2026-07-17

## Production result

- Published page: `https://mrocioa.com/web-serial-debugger/`
- WordPress page ID: `13530`
- Template: `page-serial-tool.php`
- Formal release: `Release 1.0` (published 2026-07-18).
- Release 1.0 asset SHA-256: `c65b720d504b8af9e779c3eaa4e9ce14b4231f472464a1f9e30758a15d98dd53`.
- Release 1.0 was produced reproducibly from the supplied standalone source. The old `v0.2.3` and development-build records were removed.
- The in-tool changelog now contains one formal Release 1.0 entry with the supported feature set, compatibility and local-only privacy behavior.
- English is the default language for new visitors. Existing saved language and quick-send preferences remain intact.
- No account or registration gate was added.
- Chrome and Microsoft Edge load the serial application. Other browsers receive a browser-specific notice and the iframe is not loaded.

## Navigation

Desktop menu `mrocioa-main-menu` (term ID 81):

- `TOOLS` — menu item `13531`
  - `S5 Pro Virtual Demo` — menu item `13532`, page `13476`
  - `Web Serial Debugger` — menu item `13533`, page `13530`

Mobile menu `mobile-navigation` (term ID 84):

- `TOOLS` — menu item `13534`
  - `S5 Pro Virtual Demo` — menu item `13535`, page `13476`
  - `Web Serial Debugger` — menu item `13536`, page `13530`

## SEO implementation

- Indexable production response: `index, follow`.
- Canonical: `https://mrocioa.com/web-serial-debugger/`.
- SEO title: `Web Serial Debugger & Online Serial Monitor | MROCIOA`.
- Meta description targets Web Serial debugger and online serial monitor intent.
- One visible H1, crawlable feature summary, usage steps, privacy notice and FAQ content.
- `WebApplication` and `FAQPage` structured data included.
- Internal links to the S5 Pro virtual demo and Contact Support.
- Page is present in `https://mrocioa.com/page-sitemap.xml`.
- Bing/IndexNow submission returned HTTP 200 on 2026-07-17.
- Google Search Console accepted the indexing request on 2026-07-18 and added the URL to the priority crawl queue.

## QA evidence

- PHP syntax passed locally and on production.
- Desktop production QA: existing Woodmart header/footer retained; TOOLS menu shown; iframe unpacked; serial authorization, HEX and ASCII controls visible.
- 390 × 844 production QA: page width remains within the viewport; the 1180 px application workspace scrolls horizontally inside its own stage; the Woodmart mobile toolbar is hidden on this template so it cannot cover tool controls.
- Browser gate QA: simulated Firefox showed only the Chrome/Edge notice. Restoring Chrome loaded the application normally.
- Public asset returns HTTP 200 with `text/html`.
- Fresh-browser QA confirmed English-only defaults and English quick-send preset names.
- Settings QA confirmed the expanded Release 1.0 feature record and no development-version entry.
- Public page cache was refreshed and the production page now references asset version `1784334687`.

## Product carousel

A compact four-product rotator is placed inside the upper Lab Tools header, above the application workspace. It cycles through S5 Pro, Thunderbolt 5, DisplayPort 2.1 and HDMI 2.1 hardware, pauses on hover or keyboard focus, respects reduced-motion preferences and does not push the serial workspace below a separate marketing section.

## Backups and recovery

- Pre-launch database export on VPS: `/tmp/mrocioa-before-serial-tool-20260717.sql` (9.3 MB).
- Previous W3TC page cache was moved, not deleted: `/var/www/mrocioa/wp-content/cache/page_enhanced/mrocioa.com.before-serial-tool-20260717`.
- The new page-cache directory is `/var/www/mrocioa/wp-content/cache/page_enhanced/mrocioa.com`.
- Pre-Release 1.0 standalone asset backup: `/var/www/mrocioa/wp-content/themes/woodmart-child/assets/serial-tool/mrocioa-serial-tool.html.before-release-1.0-20260718`.
- Backup SHA-256: `722cf8934504b483df69b6505019a9807c56d7d7983d1fbd17e18b72ecd59bbf`.

## Source synchronization

- VPS remained the authoritative source after launch.
- The local mirror was refreshed from the VPS on 2026-07-18, including a new database snapshot.
- Active custom theme and MU-plugin source was copied into the GitHub repository with production SHA-256 checksums.
- Formal Release 1.0 source, changelog and reproducible promotion script are included in GitHub.
