# Label Design & Printing Tool 上游说明

## 来源

- 独立产品目录：`../mrocioa-label-tool/`（相对于工作区根目录）
- 私有 GitHub 仓库：`https://github.com/andrewljf001/mrocioa-label-tool`
- 当前正式产品版本：`1.0.39`
- 当前网站部署来源提交：`87cdd42810ea6ff96a66d886f0133e461cb06199`
- 当前网站部署来源标签：`label-tool-v1.0.39`
- 线上页面：`https://mrocioa.com/label-printing-tool/`

## 本网站仓库保留的部署文件

- `wp/woodmart-child/page-label-tool.php`
- `wp/plugins/labeltool/`
- `wp/services/labeltool-decode/`
- `wp/VPS-SOURCE-SHA256.txt` 中对应生产校验值

这些文件是 WordPress 与 VPS 的部署副本，不是产品开发源码。

## 三服务边界

- 标签编辑、条码/二维码生成、本机模板和批量数据在浏览器本机运行；官方在线模板由 WordPress 保存，公开读、管理员写。
- PDF 解析、条码识别和可编辑重建由 VPS `127.0.0.1:8632` 完成，经 WordPress 同源 REST 代理调用。
- USB 打印由操作员电脑 `127.0.0.1:8631` 完成，不迁移到 VPS。

## 禁止直接修改

不要在 `mrocioa-seo` 中直接开发标签功能或修复上述部署副本。正确流程是：

1. 在独立产品仓库更新设计、实现和维护文档。
2. 运行 `npm test` 和发行审计。
3. 冻结版本并生成正式 manifest。
4. 将已批准的插件、WordPress Adapter 和 VPS 服务副本同步到本仓库。
5. 更新来源提交、标签和 SHA-256。
6. 本地验收并备份后再部署 VPS。

紧急生产修复也必须回到独立产品仓库形成可追溯版本，不能只在 VPS 或网站副本中修改。
