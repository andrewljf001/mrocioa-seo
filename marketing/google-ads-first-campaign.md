# Google Ads 首个验证广告执行方案（MROCIOA）

> 版本：v1.0  
> 创建日期：2026-05-28  
> 目标：用最小预算快速验证“搜索广告是否能带来有效询盘/订单”

## 1. 本次验证目标（14 天）

- 核心目标：拿到首批高意向流量并验证转化路径。
- 量化目标（建议起始值）：
  - 点击率（CTR）>= 4%
  - 平均点击成本（CPC）<= 2.5 USD
  - 询盘转化成本（CPA）<= 35 USD
  - 14 天内获得 >= 8 条有效询盘（或 >= 2 笔直接订单）

## 2. 投放范围与预算

- 广告类型：Google Search（仅搜索）
- 地域：美国（先单国家，降低变量）
- 语言：英语
- 日预算：30 USD/天（14 天总计约 420 USD）
- 出价策略：
  - 第 1-4 天：Maximize Clicks（先拿数据）
  - 第 5 天起：若转化 >= 10，切换 Maximize Conversions

## 3. 广告结构（先做 1 个 Campaign + 3 个 Ad Group）

### Campaign

- 名称：`US-Search-MROCIOA-Validation-2026Q2`

### Ad Group A：品牌词（Brand）

- 关键词（Phrase/Exact）：
  - "mrocioa"
  - "mrocioa official"
  - "mrocioa hdmi switch"
- 目的：拦截品牌检索，保护品牌流量。

### Ad Group B：核心产品词（Core Product）

- 关键词（Phrase/Exact）：
  - "8k hdmi switch"
  - "hdmi switch with earc"
  - "hdmi 2.1 switch 5 in 1 out"
  - "5 port hdmi switch"
- 目的：拿到高购买意图流量。

### Ad Group C：场景词（Use Case）

- 关键词（Phrase/Exact）：
  - "best hdmi switch for ps5"
  - "hdmi switch for tv soundbar"
  - "fix hdmi port not enough"
- 目的：覆盖问题驱动型搜索。

### 首批否定词（Negative Keywords）

- free
- manual
- repair
- used
- diy
- driver
- crack
- torrent
- reddit

## 4. 可直接使用的广告文案（RSA）

每个 Ad Group 建议创建 1-2 条 RSA（Responsive Search Ad）。

### 标题（Headlines，最多填 15 条）

1. 8K HDMI 2.1 Switch with eARC
2. 5-Port HDMI Switch for PS5/Xbox
3. Smooth 4K120 & 8K60 Performance
4. Instant Switching, Stable Signal
5. Built for Home Theater Setups
6. One Switch for All Your Devices
7. MROCIOA Official Store
8. Fast Shipping & Secure Checkout
9. Upgrade Your HDMI Setup Today
10. Solve TV HDMI Port Limits Fast

### 描述（Descriptions，建议 4 条）

1. Connect multiple devices to one display with smooth 8K/4K output and eARC audio support.
2. Ideal for PS5, Xbox, Apple TV and more. Stable signal, easy setup, and fast switching.
3. Stop unplugging cables. Use one smart HDMI switch for a cleaner, better entertainment setup.
4. Shop at MROCIOA official store with secure checkout and responsive support.

### Sitelink（附加链接）

- 8K HDMI Switch 5-Port
- HDMI 2.1 Product Collection
- Shipping & Delivery
- Contact Support

## 5. 落地页要求（上线前必查）

- 页面与广告语一致：标题出现 “8K HDMI 2.1 Switch / eARC / 5-Port” 关键词。
- 首屏有清晰 CTA：`Buy Now` 或 `Get Yours Today`。
- 移动端加载速度可接受（建议 LCP < 3s）。
- 明确展示运费、送达时间、退换政策。
- 开启 GA4 + Google Ads Conversion（至少跟踪 Purchase / Add to Cart / Contact）。

## 6. 执行计划（一步步做）

## Day 0（今天，准备）

1. 确认 Google Ads 与 GA4 已关联。
2. 创建转化事件并测试是否回传。
3. 整理好最终落地页 URL（每个 Ad Group 对应 1 个主落地页）。
4. 在 Google Ads 新建 Campaign、3 个 Ad Group、关键词、否定词。
5. 每个 Ad Group 上线 1 条 RSA（先用本文文案）。

## Day 1-3（冷启动）

1. 每天检查一次搜索词报告。
2. 把明显不相关词加入否定词。
3. 观察 CTR、CPC，暂停 CTR 过低（<2%）广告。

## Day 4-7（第一轮优化）

1. 保留高点击标题，替换低表现标题 2-3 条。
2. 将预算向表现最好的 Ad Group 倾斜（+20% 到 +30%）。
3. 若某关键词点击高但无转化，降低出价或暂停。

## Day 8-14（验证结论）

1. 对比 3 个 Ad Group 的 CPA、转化率。
2. 输出“继续投放/停止投放/改页再投”的结论。
3. 沉淀下阶段关键词扩展清单（新增 10-20 个长尾词）。

## 7. 每日 10 分钟运营清单

1. 看昨日花费、点击、转化是否异常。
2. 看新增搜索词，补否定词。
3. 看广告资产评分，替换低表现标题/描述。
4. 记录 1 条结论到周报（做了什么、数据怎么变）。

## 8. 通过/失败判定标准（14 天结束）

- 通过（继续加预算）：
  - 有稳定转化，CPA 在可接受范围内。
  - 核心关键词能持续带来高意向流量。
- 待优化（不急着加预算）：
  - 有点击无转化，优先改落地页与报价信息。
- 失败（暂停重构）：
  - CTR 低、CPC 高、无有效转化，且优化后仍无改善。

## 9. 下一步扩展（通过后执行）

1. 从美国扩到加拿大/英国（分国家单独 Campaign）。
2. 增加竞品对比词与长尾场景词。
3. 进入 Shopping + Search 双线投放，做再营销闭环。

---

> 使用方式：本文件作为“首个 Google 广告执行蓝图”，按 Day 0 -> Day 14 逐步执行，并在每周复盘中更新结果。
