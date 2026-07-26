# 标签打印工具 · WordPress 插件

装到 `wp-content/plugins/labeltool/`，后台启用，页面写 `[label_tool]`。

## 目录

```
labeltool/
├─ labeltool.php     插件主体：短代码 + 解码服务反代 + 设置页 + 安装包分发
├─ app/index.html    工具本体（单文件，含全部依赖，离线可用）
└─ agent/            操作员本机打印服务，由设置页打包成 zip 供下载
```

## 它做了什么

**1. 短代码 `[label_tool]` → 全幅 iframe**

用 iframe 而不是直接注入 DOM，是因为主题的全局 CSS 会污染工具的界面，更要命的是会污染 `@page` 打印样式 —— 标签打印对尺寸零容忍，隔离是硬要求。

参数：`height`（默认 `calc(100vh - 32px)`）、`min`（默认 `720px`）。

未登录显示登录链接；已登录但无权限显示提示。

**2. 解码服务反向代理**

```
浏览器 ──同源 HTTPS──> /wp-json/labeltool/v1/svc/<token>/<endpoint>
                              │ 校验签名 token + WP 能力
                              ▼
                       http://127.0.0.1:8632/<endpoint>
```

放行的端点只有 `status` `pdf-to-label` `pdf-to-svg` `decode` `rebuild`，其余 404。

**token 塞在路径里**是刻意的设计：工具前端有 8 处 fetch，token 在 base URL 里意味着那 8 处一行都不用改，只需拼 `base + '/decode'`。token = `uid.exp.hmac`，有效期 8 小时，用 `wp_salt()` 签名。

**3. 设置页**（设置 → 标签工具）

- 实时探活解码服务，显示 ONLINE / OFFLINE
- 改解码服务内部地址（一般不用改）
- 设访问权限（能力名）
- 下载操作员打印服务安装包

## 环境要求

| 项 | 要求 | 原因 |
|---|---|---|
| PHP | ≥ 7.4 | — |
| `post_max_size` | ≥ 24M | PDF 上限 20M，经代理时 PHP 也要放得下 |
| `memory_limit` | ≥ 256M | 同上 |
| `ZipArchive` | 需要 | 打包安装包用；缺了就手工压缩 `agent/` |
| Nginx `client_max_body_size` | ≥ 24m | 否则大 PDF 报 413 |
| Nginx `fastcgi_read_timeout` | ≥ 120s | 复杂 PDF 解析可能十几秒 |

## 更新工具本体

工具源文件是 `标签打印工具.dc.html`。改完重新打包成单文件，覆盖 `app/index.html` 即可 —— 插件不需要动。

浏览器缓存靠 `?v=` 参数破除，改完记得同步 `labeltool.php` 里的 `LABELTOOL_VER`。

## 安全说明

- 解码服务只监听 `127.0.0.1`，不开放公网端口。所有访问必经 WordPress 鉴权。
- PyMuPDF 要解析用户上传的不可信 PDF，systemd 单元里已收紧（内存/CPU 上限、`ProtectSystem=strict`、独立系统账号）。见 `release/vps/labeltool-decode.service`。
- token 只授权解码代理，不携带任何 WP 写权限。
