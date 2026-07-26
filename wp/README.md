# WordPress deployment source

This directory contains the public, deployable source synchronized with the
MROCIOA production VPS. Product-specific upstream repositories are the
authoritative source for the Serial Tool and Label Tool; this directory keeps
their approved deployment snapshots.

## Path mapping

- `woodmart-child/` -> `/var/www/mrocioa/wp-content/themes/woodmart-child/`
- `plugins/labeltool/` -> `/var/www/mrocioa/wp-content/plugins/labeltool/`
- `services/labeltool-decode/pdf_svg_server.py` -> `/opt/labeltool/pdf_svg_server.py`
- `services/labeltool-decode/labeltool-decode.service` -> `/etc/systemd/system/labeltool-decode.service`
- Root-level PHP files -> `/var/www/mrocioa/wp-content/mu-plugins/`

Only active custom source is versioned. Production configuration, databases,
licensed parent-theme/plugin packages, uploads, caches, generated backups and
private credentials are intentionally excluded.

See `VPS-SOURCE-SHA256.txt` for production checksums. The base WordPress
snapshot was captured 2026-07-18; Label Tool production files were captured
2026-07-26.

The Web Serial Debugger files in `woodmart-child/` are release deployment
copies. Product development, tests and release governance belong to the
private upstream repository documented in `SERIAL-TOOL-UPSTREAM.md`.

The Label Design & Printing Tool files under `woodmart-child/`, `plugins/`
and `services/` are also release deployment copies. Its product, three-service
architecture, tests and release governance belong to the private upstream
repository documented in `LABEL-TOOL-UPSTREAM.md`.
