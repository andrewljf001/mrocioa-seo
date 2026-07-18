# Web Serial Debugger — Release 1.0.1 发行范围

- 版本：`1.0.1`（由项目所有者确认）
- 当前状态：Stable，已于 2026-07-18 正式发行
- 线上版本：`1.0.1`

## 已确认问题 1：真正的全屏

- 点击页面的 `Enter full screen` 后，对当前工具区域调用浏览器 Fullscreen API。
- 全屏中只显示同一个 Web Serial 工具；Header、Hero、产品轮播和 Footer 不显示。
- 不再通过打开独立页面模拟全屏，避免颜色变化、界面重复加载或串口会话丢失。
- 页面提供退出按钮，并支持 `Esc` 退出。
- 浏览器拒绝或不提供 Fullscreen API 时，自动进入覆盖整个网页的沉浸模式。
- 普通、原生全屏和沉浸回退模式使用同一 iframe 和同一套站点主题颜色。

## 已确认问题 2：通讯调试日志解析

解析对象是“设备或固件通过当前串口打印出来的通讯调试内容”，不是把串口本身误认为 IR、CEC、I²C、SPI 等物理接口。

示例：

```text
ir code = 0xE718FF00
cec rx = 40 44 41
i2c write addr=0x50 reg=0x10 data=AA 55 ACK
spi tx=9F rx=EF 40 18 mode=0
CAN RX id=0x7E8 data=03 41 0D 28
```

候选解析范围：

- 现有：AT、NMEA 0183、JSON、Modbus RTU、EDID。
- 扩展：Modbus ASCII、IR、HDMI-CEC、I²C、SPI、UART/RS-232/RS-485、1-Wire、CAN/OBD-II、LIN、DMX512、RDM、u-blox UBX、MAVLink、IEC 62056-21、DL/T 645、G-code。
- 自动识别优先读取日志关键词、方向和具名字段；若日志包含完整十六进制帧，再进一步解析地址、操作码、数据、校验和协议专用含义。
- 无法完整解析时仍保留原始日志，并明确说明缺少的字段，不把它误报成另一种物理接口。

## 已确认问题 3：默认展示 1+3 多窗布局

- 首次打开以及从旧版默认单窗升级时，默认展示 `1+3` 主从四窗布局，让用户直接看到多会话能力。
- 单窗和 `1+1` 仍保留为用户可选布局。
- 新默认值只迁移一次；用户之后手动选择的布局继续保存在本机，刷新页面不会被强制改回 `1+3`。

## 发行规则

- 本次范围已经冻结并完成发行；后续新增功能进入下一版本。
- `VERSION`、正式 `CHANGELOG.md`、界面版本和发行说明统一为 `1.0.1`。
- 本次没有改变已有 Web Serial 底层连接与收发逻辑；全屏已由用户在 macOS 桌面 Chrome 人工验收。发行环境未连接实际串口设备，这一限制已写入正式发行报告。

## 已完成验收

- 2026-07-18，macOS 桌面 Chrome 人工确认原生全屏通过。
- 全屏请求明确使用 `navigationUI: "hide"`；Chrome 标签栏、地址栏以及站点 Header、Hero、产品轮播和 Footer 均能隐藏。
- 页面退出按钮和 `Esc` 可退出，进入和退出过程继续使用同一个工具 iframe。
- Fullscreen API 被禁用或拒绝时的网页沉浸回退已通过本地自动化浏览器验证。

## 正式发行记录

- 线上应用 SHA-256：`87c5de0cedd734cd3b5ccda05f212350e0512db36fe9ac5b0f18de6d3b9ba611`
- WordPress 页面模板 SHA-256：`81115f8d6b3520ba3af155ba4d479b88ffd10f57825d3583dc1d3e8b6ea0293d`
- VPS 回退目录：`/var/www/mrocioa/wp-content/codex-backups/serial-tool-before-1.0.1-20260718-133134/`
- 实现提交：`bc206bb`
- 正式标签：`serial-tool-v1.0.1`
- VPS、仓库发行文件和本地正式镜像的两个文件校验值一致。
- 公网页面 HTTP、SEO、Canonical、WebApplication 结构化数据和 Sitemap 检查通过。

## 不属于 1.0.1 的后续方向

- 移动端不直接连接串口；远程只读观看方案另行设计和发行。
- 桌面 Chrome/Edge 作为串口主机，由操作员主动生成短时分享码或二维码。
- 手机、平板和其他浏览器只查看实时日志、连接状态、图表及解析结果，默认不能发送命令或修改串口配置。
- 详细边界记录在 `FUTURE-IDEAS.md`，不会作为 1.0.1 的隐藏功能上线。
