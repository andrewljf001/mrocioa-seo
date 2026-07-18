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
- `ProtocolPanel.jsx` — 协议解析（AT / I²C / SPI / EDID / CEC 解码表）

正式版支持真实 Web Serial 授权与收发、多会话、HEX/ASCII、记录导出、自动发送、图表和协议解析。串口数据仅在浏览器本机处理。
