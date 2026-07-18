# Web Serial Debugger — 下一版本候选范围

- 预计版本：`1.1.0`
- 当前状态：本地开发，范围尚未冻结
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

## 发行前规则

- 用户可在范围冻结前继续增加候选功能。
- 范围冻结后再更新 `VERSION`、正式 `CHANGELOG.md`、界面版本和发行说明。
- 完成自动测试、Chrome/Edge 本地测试、实际串口设备回归和用户验收之前，不部署 VPS、不合并 GitHub `main`、不建立正式标签。
