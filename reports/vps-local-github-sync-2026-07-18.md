# VPS, local mirror and GitHub synchronization — 2026-07-18

## Authority and scope

- Authoritative source: `rogersense-vps:/var/www/mrocioa`
- Local mirror: `/Users/lijianfeng/Documents/mrocioa网页优化/mrocioa-live-mirror`
- GitHub repository: `andrewljf001/mrocioa-seo`

The local mirror contains the full operational backup permitted by the sync
rules. GitHub contains only safe, deployable custom source and operational
records; it does not contain the production database, credentials, licensed
parent theme/plugins, uploads, caches or server backups.

## Local synchronization result

- 43,683 files evaluated.
- 629 files transferred from the VPS.
- 33,855 kB of changed file content synchronized.
- Latest database snapshot: `mrocioa-live-2026-07-18.sql.gz`.
- Database gzip integrity check passed.
- Post-sync checksum comparison found no content differences in the active
  `woodmart-child` theme or the active MU plugins.

## GitHub source snapshot

The repository now includes:

- Current `woodmart-child` PHP/CSS templates and WooCommerce overrides.
- The production Web Serial application asset.
- Formal Web Serial Debugger Release 1.0 source and changelog.
- Reproducible Release 1.0 promotion script.
- Current custom MU plugins.
- Production SHA-256 manifest in `wp/VPS-SOURCE-SHA256.txt`.
- Serial-tool launch, SEO and indexing record.

Database-driven WordPress objects such as page ID `13530` and the TOOLS menu
remain recoverable from the dated local database snapshot and are documented in
the launch report, but are intentionally not committed to the public repository.

## Web Serial Debugger Release 1.0

- VPS asset SHA-256: `c65b720d504b8af9e779c3eaa4e9ce14b4231f472464a1f9e30758a15d98dd53`.
- The VPS asset was copied back to the local mirror, then copied into the GitHub source snapshot.
- The three copies were verified byte-for-byte with the same SHA-256 value.
- Fresh-browser QA passed with English defaults and the formal Release 1.0 feature record.
- Existing browser preferences are preserved; they do not change the packaged default.
- The previous production asset remains available on the VPS as `mrocioa-serial-tool.html.before-release-1.0-20260718`.
- The public GitHub snapshot excludes the production database, credentials, licensed parent theme/plugins, uploads, caches and backups.
