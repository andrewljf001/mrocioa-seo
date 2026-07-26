#!/bin/bash
# ZMIN X1i 打印服务（8631）· 开机自启 + 崩溃自动拉起
# 用法：与 zmin_print_server.py 放同一文件夹，双击运行一次即可
#
# 注意：本服务刻意使用【系统 python3】，不建 venv。
# 原因：venv 里 pip 装的 pyusb 会带上自己的 libusb 后端，实测枚举得到设备
# （/status connected=true）、写入也报成功，但打印机收下数据后丢弃、不出纸。
# 系统 python3 + 系统 libusb 是已验证能打印的组合。勿改回 venv。
set -e
DIR="$(cd "$(dirname "$0")" && pwd)"
cd "$DIR"

PY="$(command -v python3)"
[ -z "$PY" ] && { echo "找不到 python3"; exit 1; }
echo "== 使用 Python: $PY"

if ! "$PY" -c 'import usb.core' 2>/dev/null; then
  echo "== 安装 pyusb 到系统 python3 (--user)"
  "$PY" -m pip install --user -q pyusb || "$PY" -m pip install --user -q --break-system-packages pyusb
fi
"$PY" -c 'import usb.core; print("  pyusb OK:", usb.core.__file__)'

# 旧版本若建过 venv，留着会让人误以为服务在用它 —— 提示但不删
[ -d "$DIR/.venv" ] && echo "  ! 检测到遗留 .venv（本服务不使用它，可自行删除）"

PLIST="$HOME/Library/LaunchAgents/com.zmin.printserver.plist"
mkdir -p "$HOME/Library/LaunchAgents"

cat > "$PLIST" <<EOF
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE plist PUBLIC "-//Apple//DTD PLIST 1.0//EN" "http://www.apple.com/DTDs/PropertyList-1.0.dtd">
<plist version="1.0"><dict>
  <key>Label</key><string>com.zmin.printserver</string>
  <key>ProgramArguments</key>
  <array><string>$PY</string><string>$DIR/zmin_print_server.py</string></array>
  <key>WorkingDirectory</key><string>$DIR</string>
  <key>RunAtLoad</key><true/>
  <key>KeepAlive</key><true/>
  <key>StandardOutPath</key><string>$HOME/Library/Logs/zmin-print.log</string>
  <key>StandardErrorPath</key><string>$HOME/Library/Logs/zmin-print.log</string>
</dict></plist>
EOF

launchctl bootout "gui/$(id -u)/com.zmin.printserver" 2>/dev/null || true
lsof -ti:8631 | xargs kill -9 2>/dev/null || true
launchctl bootstrap "gui/$(id -u)" "$PLIST"
launchctl kickstart -k "gui/$(id -u)/com.zmin.printserver"

sleep 2
echo "== 自检"
curl -s http://127.0.0.1:8631/status && echo "  ← 8631 在线" || echo "8631 未响应，看 ~/Library/Logs/zmin-print.log"
echo
echo "开机自启已注册（崩溃会自动拉起）。"
echo "手动重启：launchctl kickstart -k gui/\$(id -u)/com.zmin.printserver"
echo "停用：    launchctl bootout gui/\$(id -u)/com.zmin.printserver"
