# MROCIOA Web Serial Debugger — Release History

## 1.0.1 — 2026-07-18

- Status: Stable
- Supported browsers: Desktop Google Chrome and Microsoft Edge

### Added

- Communication debug-log decoding for text printed by devices and firmware over the active serial connection.
- Automatic recognition and field decoding for Modbus ASCII, IR, HDMI-CEC, I²C, SPI, UART/RS-232/RS-485, 1-Wire, CAN/OBD-II, LIN, DMX512, RDM, u-blox UBX, MAVLink, IEC 62056-21, DL/T 645 and G-code, in addition to the existing AT, NMEA, JSON, Modbus RTU and EDID decoders.
- Named-field parsing for logs such as `ir code = 0xE718FF00`, `cec rx = 40 44 41`, `i2c write addr=0x50 reg=0x10 data=AA 55 ACK` and `CAN RX id=0x7E8 data=03 41 0D 28`.

### Changed

- The first visit and one-time upgrade from the previous default now open in the `1+3` four-pane MAIN/SUB layout so the multi-session capability is immediately visible.
- A layout selected manually after migration remains stored locally and is restored on refresh.

### Fixed

- `Enter full screen` now applies the Fullscreen API to the existing tool iframe instead of opening a separate page.
- Fullscreen hides the site Header, Hero, product rotator and Footer, requests hidden browser navigation UI, preserves the current serial-tool state and supports both `Esc` and an on-screen exit control.
- Browsers that reject or disable the Fullscreen API fall back to a page-filling focus mode without reloading the tool.
- Embedded and fullscreen views use the same site-aligned cyan-to-violet accent theme and high-contrast text.

### Compatibility and privacy

- Serial data continues to be processed locally in the browser and is not uploaded to MROCIOA servers.
- Unsupported browsers continue to receive the Chrome/Edge compatibility notice instead of the serial application.
- Mobile compatibility behavior is unchanged in this release.

### Verification

- Protocol detection and decoding fixtures: passed.
- Packed-source consistency checks: passed.
- Serial-page template and fullscreen fallback checks: passed.
- Native fullscreen on macOS desktop Chrome: manually accepted on 2026-07-18.
- Release package SHA-256, VPS rollback path, production checks, Git commit and tag are recorded after deployment.

## 1.0.0 — 2026-07-18

- Status: Stable
- Historical interface label: `Release 1.0`
- Supported browsers: Desktop Google Chrome and Microsoft Edge

### Included features

- Real Web Serial authorization, port discovery, hot-plug detection and automatic reconnection.
- Up to four independent serial sessions with MAIN, SUB and background session management.
- Baud rate, data bits, parity, stop bits, flow control, framing, DTR and RTS configuration.
- ASCII and HEX receive/transmit, multiline commands, file transfer, quick send and command history.
- One-shot and looping send sequences with per-line and per-cycle timing controls.
- A 100,000-line receive buffer with timestamps, pause, clear, search, filtering, highlighting and continuous recording.
- TXT and CSV log export, unattended keyword capture, surrounding-context capture and Wake Lock support.
- Live multi-channel chart discovery with CSV/PNG export and cursor measurement.
- Protocol decoding for AT commands, NMEA, JSON, Modbus RTU and EDID.
- Bilingual Chinese/English interface, persistent preferences and keyboard shortcuts.

### Privacy and compatibility

- Serial data is processed locally in the browser and is not uploaded to MROCIOA servers.
- Web Serial access requires a secure context and a user-approved device permission prompt.
- Unsupported browsers receive the Chrome/Edge compatibility notice instead of loading the application.
