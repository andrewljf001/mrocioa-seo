# Codex Project Memory

## 接管信息

- 接管时间：2026-05-27
- 接管对象：`mrocioa-seo`（Public）
- 仓库地址：`https://github.com/andrewljf001/mrocioa-seo`
- 本地路径：`/Users/lijianfeng/Documents/mrocioa网页优化/mrocioa-seo`
- 主分支：`main`

## 串口工具项目边界（2026-07-18）

- Web Serial Debugger 已拆分到独立产品目录：`/Users/lijianfeng/Documents/mrocioa网页优化/mrocioa-serial-tool`。
- 私有仓库：`https://github.com/andrewljf001/mrocioa-serial-tool`。
- 当前正式版本：`1.0.2`；网站部署来源提交 `cc6c8fd`，发行标签 `serial-tool-v1.0.2`。
- 串口功能、设计、测试、版本和未来协同服务只在独立产品仓库开发。
- 本 `mrocioa-seo` 仓库只保留正式 WordPress Adapter、打包 HTML、来源说明和 SHA-256 部署记录。
- 不直接修改网站仓库或 VPS 中的串口部署副本；先在产品仓库完成、测试、验收和发行，再同步部署文件。
- 远程协同界面遵循 Host-authoritative Viewer：远端完整同步测试主机的布局、图表、色彩和状态；无权限控件只以灰色禁用显示。
- 详细入口：`wp/SERIAL-TOOL-UPSTREAM.md`。

## 进度管理规则（强约束）

- 进度唯一入口：`progress/PROGRESS.md`
- 后续所有任务状态、完成记录、阶段进度百分比，只在 `PROGRESS.md` 更新
- 其他文档（周报、月报、报告）仅做补充说明，不作为主进度看板

## 当前状态快照（来自 PROGRESS.md）

- 阶段：Phase 1（基础建设与数据监控）
- 总进度：8%
- 开放任务：7
- 已完成任务：2（#4 细化关闭、#6 sitemap 完成）
- Milestone：v1.0 基础 SEO 建设（进行中）

## 执行节奏

- 持续任务 A：产品页 SEO（每工作日 1 页）
- 持续任务 B：Blog 发布（每周 2-3 次，周二/四/日）
- 每完成一个 Issue：关闭 Issue -> 回写 `PROGRESS.md` 完成记录 -> 更新总进度
