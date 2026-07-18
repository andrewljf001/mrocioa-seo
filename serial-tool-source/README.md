# UI Kit — 串口调试工具 mrocioa

Release 1.0 正式源码：顶部状态栏 + 多串口会话、左侧连接配置栏、中央数据监视终端、底部发送区、右侧可切换波形图与协议解析面板。

规范版本号：`1.0.0`（首次正式发行界面显示为 `Release 1.0`）。

- [发行规范](RELEASE-POLICY.md)
- [正式发行记录](CHANGELOG.md)

- `index.html` — Web Serial 应用入口
- `MainScreen.jsx` — 整体布局
- `SidebarConfig.jsx` — 连接配置 + 快捷发送 + 脚本
- `MonitorPanel.jsx` — 终端监视 + 发送区
- `PlotPanel.jsx` — 实时多通道波形图
- `ProtocolPanel.jsx` — 串口内容与通讯调试日志解析（AT、NMEA、Modbus、IR、CEC、I²C、SPI、UART/RS-485、1-Wire、CAN、LIN、DMX/RDM、EDID 等）

正式版支持真实 Web Serial 授权与收发、多会话、HEX/ASCII、记录导出、自动发送、图表和协议解析。串口数据仅在浏览器本机处理。

开发中的下一版本范围记录在 [NEXT-RELEASE.md](NEXT-RELEASE.md)。`CHANGELOG.md` 只记录已经正式发行的版本。
