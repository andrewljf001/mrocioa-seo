#!/usr/bin/env bash
# 标签工具 · 解码服务 VPS 一键安装
# 用法：sudo bash install-vps.sh
# 装完：服务监听 127.0.0.1:8632，由 WordPress 插件反代，不开放任何公网端口。

set -euo pipefail

APP=/opt/labeltool
SVC=labeltool-decode
HERE="$(cd "$(dirname "$0")" && pwd)"

[[ $EUID -eq 0 ]] || { echo "请用 sudo 运行"; exit 1; }

echo "==> 1/6 系统依赖"
if command -v apt-get >/dev/null; then
  apt-get update -qq
  apt-get install -y -qq curl python3 python3-venv python3-pip
elif command -v dnf >/dev/null; then
  dnf install -y -q curl python3 python3-pip
else
  echo "未识别的发行版，请手工安装 python3 / python3-venv"; exit 1
fi

echo "==> 2/6 服务账号"
id -u labeltool >/dev/null 2>&1 || useradd --system --home "$APP" --shell /usr/sbin/nologin labeltool

echo "==> 3/6 部署代码到 $APP"
mkdir -p "$APP"
install -m 644 "$HERE/pdf_svg_server.py" "$APP/pdf_svg_server.py"

echo "==> 4/6 Python 虚拟环境"
python3 -m venv "$APP/venv"
"$APP/venv/bin/pip" install -q --upgrade pip
"$APP/venv/bin/pip" install -q -r "$HERE/requirements.txt"
chown -R labeltool:labeltool "$APP"

echo "==> 5/6 systemd"
install -m 644 "$HERE/$SVC.service" "/etc/systemd/system/$SVC.service"
systemctl daemon-reload
systemctl enable --now "$SVC"
systemctl is-enabled --quiet "$SVC"
systemctl is-active --quiet "$SVC"

echo "==> 6/6 自检"
sleep 2
if curl -fsS --max-time 5 http://127.0.0.1:8632/status; then
  echo
  echo "✔ 解码服务已就绪（开机自启；异常退出后每 3 秒自动拉起）"
else
  echo "✘ 启动失败 —— 看日志： journalctl -u $SVC -n 50 --no-pager"
  exit 1
fi

cat <<'EOF'

────────────────────────────────────────────
下一步（在 WordPress 里）
  1. 上传插件目录 wp-plugin/labeltool/ 到 wp-content/plugins/
  2. 后台「插件」启用「标签打印工具 Label Tool」
  3.「设置 → 标签工具」确认解码服务显示 ONLINE
  4. 新建页面，内容写 [label_tool]，用全宽模板
  5. 在同一页面的设置里把安装包发给操作员（打印服务装本机）

注意：PHP 需要 post_max_size ≥ 24M（PDF 上限 20M）
      检查： php -i | grep post_max_size
────────────────────────────────────────────
EOF
