# Claude 工作说明 — mrocioa-seo 项目

> 本文件是 Claude 的固定说明，每次对话开始前请先读取此文件，然后再读取 PROGRESS.md 了解当前状态。

---

## 项目背景

- **网站**：mrocioa.com（独立站，主营 [需补充：品类]）
- **目标**：通过持续 SEO 优化，建立稳定 Google 自然流量，配合 Google Shopping 广告提升整体 ROI
- **仓库地址**：https://github.com/andrewljf001/mrocioa-seo
- **项目开始时间**：2026-05-24

---

## AI 分工

| 工具 | 职责 |
|------|------|
| ChatGPT | SEO 策略规划、内容选题、关键词策略 |
| **Claude** | 进度同步、看板更新、文档整理、具体优化执行 |
| Claude Code | 技术 SEO 脚本、数据批处理 |

Claude 的核心职责：
1. 读取仓库现有文档，快速理解当前进度，**不反复问已记录过的背景信息**
2. 执行用户布置的具体任务（写内容、更新文档、分析数据等）
3. 每次完成任务后，将结果 commit 回对应的文档

---

## 仓库结构说明

```
mrocioa-seo/
├── CLAUDE.md                  # 本文件 — Claude 工作说明 ⭐
├── README.md                  # 项目总览（面向人类）
├── progress/
│   ├── PROGRESS.md            # 当前进度主文件（每次必读）⭐
│   ├── phase-1-foundation.md  # Phase 1 详细记录
│   └── weekly-log.md          # 每周简报
├── research/
│   ├── keywords.md            # 关键词调研
│   └── competitors.md         # 竞品分析
└── reports/
    └── 2026-05.md             # 月度数据报告
```

---

## Claude 每次对话的标准流程

### 第一步：上下文同步（对话开始时）
1. 读取 `CLAUDE.md`（本文件）
2. 读取 `progress/PROGRESS.md`
3. 如用户提到关键词或竞品，顺带读取对应 research 文件
4. **无需再询问**项目背景、网站名称、分工等已记录的信息

### 第二步：执行任务
- 按用户指令执行，优先输出结果，减少确认环节
- 有歧义才问，明确的任务直接做

### 第三步：更新文档（任务完成后）
- 将完成结果 commit 进对应文件
- `PROGRESS.md` 记录里程碑与状态变更
- `weekly-log.md` 记录本周完成事项
- 具体内容存入对应子文件

---

## 文档更新规范

### PROGRESS.md 状态标记
- ✅ 已完成
- 🟡 进行中
- ⬜ 待开始
- ❌ 搁置/取消

### Commit 消息格式
```
[类型] 简要说明

类型：update / add / fix / research / report
示例：update: PROGRESS.md — 完成首页 meta 优化
      add: research/keywords.md — 补充长尾词列表
```

---

## 当前阶段重点（详见 PROGRESS.md）

以 PROGRESS.md 中记录的 Phase 和任务清单为准。

---

## 注意事项

1. **不要重复询问已在文档中记录的信息**，直接读文件
2. 每次对话尽量在结束前把产出 commit 进仓库，保持文档实时更新
3. 如遇到重大策略调整，需在 PROGRESS.md 中记录决策背景
4. 数据类内容（流量、排名）放 `reports/`，策略类放 `research/`，执行进度放 `progress/`

---

*最后更新：2026-05-24*
