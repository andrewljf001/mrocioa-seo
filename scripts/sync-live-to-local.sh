#!/usr/bin/env bash
set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/../.." && pwd)"
TARGET_DIR="$ROOT_DIR/mrocioa-live-mirror"
FILES_DIR="$TARGET_DIR/files"
DATABASE_DIR="$TARGET_DIR/database"
REMOTE_HOST="${MROCIOA_REMOTE_HOST:-rogersense-vps}"
REMOTE_PATH="${MROCIOA_REMOTE_PATH:-/var/www/mrocioa}"
STAMP="$(date +%F)"

mkdir -p "$FILES_DIR" "$DATABASE_DIR" "$TARGET_DIR/reports"

rsync -az --stats --human-readable \
  --exclude='wp-content/cache/' \
  --exclude='wp-content/wpvividbackups/' \
  --exclude='wp-content/wpvivid_staging/' \
  --exclude='wp-content/wpvivid_uploads/' \
  --exclude='wp-content/upgrade/' \
  --exclude='wp-content/upgrade-temp-backup/' \
  --exclude='wp-content/uploads/wc-logs/' \
  --exclude='wp-content/uploads/wpcf7_uploads/' \
  --exclude='wp-content/uploads/wpforms/' \
  --exclude='wp-content/uploads/woocommerce_uploads/' \
  --exclude='wp-content/uploads/wc-imports/' \
  --exclude='wp-config.php' \
  --exclude='.git/' \
  -e 'ssh -o BatchMode=yes -o ConnectTimeout=10' \
  "$REMOTE_HOST:$REMOTE_PATH/" \
  "$FILES_DIR/"

ssh -o BatchMode=yes -o ConnectTimeout=10 "$REMOTE_HOST" \
  "cd '$REMOTE_PATH' && wp db export - --add-drop-table --single-transaction --default-character-set=utf8mb4 | gzip -c" \
  > "$DATABASE_DIR/mrocioa-live-$STAMP.sql.gz"

gzip -t "$DATABASE_DIR/mrocioa-live-$STAMP.sql.gz"

echo "Synced files to: $FILES_DIR"
echo "Saved database snapshot: $DATABASE_DIR/mrocioa-live-$STAMP.sql.gz"
