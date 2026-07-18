# WordPress deployment source

This directory contains the public, deployable source synchronized from the
MROCIOA production VPS. The VPS is the authoritative source.

## Path mapping

- `woodmart-child/` -> `/var/www/mrocioa/wp-content/themes/woodmart-child/`
- Root-level PHP files -> `/var/www/mrocioa/wp-content/mu-plugins/`

Only active custom source is versioned. Production configuration, databases,
licensed parent-theme/plugin packages, uploads, caches, generated backups and
private credentials are intentionally excluded.

See `VPS-SOURCE-SHA256.txt` for the production checksums captured during the
2026-07-18 synchronization.
