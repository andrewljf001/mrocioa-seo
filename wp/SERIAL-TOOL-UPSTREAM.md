# Web Serial Debugger 上游说明

## 来源

- 独立产品目录：`../mrocioa-serial-tool/`（相对于工作区根目录）
- 私有 GitHub 仓库：`https://github.com/andrewljf001/mrocioa-serial-tool`
- 当前正式产品版本：`1.0.1`
- 当前网站部署来源提交：`0afa0af`
- 当前网站部署来源标签：`serial-tool-v1.0.1`

## 本网站仓库保留的部署文件

- `wp/woodmart-child/page-serial-tool.php`
- `wp/woodmart-child/assets/serial-tool/mrocioa-serial-tool.html`
- `wp/VPS-SOURCE-SHA256.txt` 中对应校验值

这些文件用于 WordPress 和 VPS 部署，不是产品开发源码。

## 禁止直接修改

不要在 `mrocioa-seo` 中直接开发串口功能或修复上述部署文件。正确流程是：

1. 在独立产品仓库更新设计和源码。
2. 运行产品测试与发行审计。
3. 冻结版本并生成正式构建。
4. 将 WordPress Adapter 和构建产物同步到本网站仓库。
5. 更新 SHA-256、发行来源标签和部署报告。
6. 本地验收后再部署 VPS。

紧急生产修复也必须回到产品仓库形成可追溯提交，不能只在 VPS 或网站副本中修改。
