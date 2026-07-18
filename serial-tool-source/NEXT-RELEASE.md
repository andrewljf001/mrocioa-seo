# Web Serial Debugger — Release 1.0.1 发行范围

- 版本：`1.0.1`（由项目所有者确认）
- 当前状态：范围已冻结，准备正式发行
- 线上版本：`1.0.0`，不受本地候选修改影响

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

- 本次范围已冻结；后续新增功能进入下一版本。
- `VERSION`、正式 `CHANGELOG.md`、界面版本和发行说明统一为 `1.0.1`。
- 完成自动测试、Chrome/Edge 本地测试、实际串口设备回归和用户验收之前，不部署 VPS、不合并 GitHub `main`、不建立正式标签。

## 已完成验收

- 2026-07-18，macOS 桌面 Chrome 人工确认原生全屏通过。
- 全屏请求明确使用 `navigationUI: "hide"`；Chrome 标签栏、地址栏以及站点 Header、Hero、产品轮播和 Footer 均能隐藏。
- 页面退出按钮和 `Esc` 可退出，进入和退出过程继续使用同一个工具 iframe。
- Fullscreen API 被禁用或拒绝时的网页沉浸回退已通过本地自动化浏览器验证。

## 上线前剩余事项

- 确认移动端策略：完全限制为桌面 Chrome/Edge，或保留 Android Chrome 对 Bluetooth RFCOMM 串口的有限入口。
- 使用至少一台实际串口设备完成授权、连接、收发、断开和重新连接冒烟测试。
- 冻结本次功能范围后，更新 `VERSION`、正式 `CHANGELOG.md` 和界面发行记录。
