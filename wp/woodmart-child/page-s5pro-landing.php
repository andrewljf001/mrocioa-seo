<?php
/**
 * Template Name: S5 Pro Landing Page
 */
get_header();
?>
<style>
.wd-page-title,.page-title-bar,.page-header,.woocommerce-breadcrumb{display:none!important}
body.page-template-page-s5pro-landing{background:#0d0d12!important}
body.page-template-page-s5pro-landing .site-footer{margin-top:0!important;padding-top:0!important}
body.page-template-page-s5pro-landing .col-full,
body.page-template-page-s5pro-landing #main,
body.page-template-page-s5pro-landing article.page,
body.page-template-page-s5pro-landing .entry-content,
body.page-template-page-s5pro-landing .site-main{padding:0!important;margin:0!important;background:transparent!important;max-width:100%!important;width:100%!important}
.lp-wrap{width:100vw;margin-left:calc(-50vw + 50%);background:#0a0a0f}
.lp-top{padding:28px 24px 20px;display:flex;align-items:center;justify-content:center;gap:32px}
.lp-top-text{flex:1;max-width:540px;text-align:center}
.lp-top-img{flex:0 0 auto}
.lp-top-img img{max-height:180px;width:auto;display:block;border-radius:8px}
@media(max-width:767px){.lp-top-img{display:none}}
.lp-top em{display:block;font-style:normal;color:#0CB4CF;font-size:11px;letter-spacing:.12em;margin-bottom:8px}
.lp-top h1{font-size:clamp(18px,2.8vw,36px);font-weight:600;line-height:1.25;margin:0 0 8px;background:linear-gradient(to right,#7B7FE8,#0CB4CF);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text}
.lp-top p{color:#8A9BB5;font-size:13px;margin:0 0 14px}
.lp-btn{display:inline-block;background:#0CB4CF;color:#0B0E14!important;border-radius:6px;padding:10px 36px;font-size:14px;font-weight:700;text-decoration:none}
.lp-demo{overflow:hidden;background:#e8eaed;position:relative}
#lp-demo .vx-eyebrow,#lp-demo .vx-title,#lp-demo .vx-sub{display:none!important}
#lp-demo .vx-page{overflow:hidden!important;box-shadow:none!important;border-radius:0!important;margin:0!important;background:transparent!important}
#lp-demo .vx-page .vx-wrap{padding-top:0!important;padding-bottom:34px!important;transition:transform 0.15s ease!important}
#lp-demo .vx-hints{display:grid!important;margin-top:24px!important}

/* ── 手机端（<600px）：保持不动 ── */
@media(max-width:600px){
  #lp-demo .vx-page .rmt{zoom:1!important;justify-self:center!important}
  #lp-demo .vx-page .ph{justify-self:center!important}
  #lp-demo .vx-page .dev{position:relative!important;overflow:visible!important}
  #lp-demo .vx-page .dev-pwr{position:absolute!important;bottom:6px!important;right:6px!important;top:auto!important;left:auto!important;transform:scale(1.5)!important;transform-origin:bottom right!important;z-index:10!important}
}

/* ── 中间段（600–1019px）：强制PC横排，只加电源键右下角 ── */
@media(min-width:600px) and (max-width:1019px){
  #lp-demo .vx-page .scene{grid-template-columns:auto 1fr auto!important;align-items:center!important}
  #lp-demo .vx-page .center-col{grid-column:auto!important;order:0!important}
  #lp-demo .vx-page .ph{zoom:1!important}
  #lp-demo .vx-page .rmt{zoom:1!important}
  #lp-demo .vx-page .dev{position:relative!important;overflow:visible!important}
  #lp-demo .vx-page .dev-pwr{position:absolute!important;bottom:6px!important;right:6px!important;top:auto!important;left:auto!important;transform:scale(1.5)!important;transform-origin:bottom right!important;z-index:10!important}
}
</style>
<?php
$demo_post = get_post( 13289 );
$raw = '';
if ( $demo_post ) {
    $raw = $demo_post->post_content;
    $raw = preg_replace( '#<script[^>]+id=["\']vx-fit["\'][^>]*>.*?</script>#is', '', $raw );
}
$s5pro      = wc_get_product( 10244 );
$avg_rating = ( $s5pro && $s5pro->get_average_rating() > 0 ) ? $s5pro->get_average_rating() : '4.7';
$rev_count  = ( $s5pro && $s5pro->get_review_count() > 0 )  ? $s5pro->get_review_count()  : 48;
?>
<div class="lp-wrap">
  <div class="lp-top">
    <div class="lp-top-text">
      <em>8K HDMI SWITCH &middot; HDMI 2.1 SWITCH &middot; 4K 120HZ</em>
      <h1>MROCIOA S5 Pro &mdash; 8K HDMI Switch, HDMI 2.1, 4K 120Hz</h1>
      <p>Switch inputs, test eARC, check CEC &mdash; with your actual devices.</p>
      <a class="lp-btn" href="https://mrocioa.com/product/8k-hdmi-switch-5-port-earc/">Buy S5 Pro &mdash; $79.99</a>
    </div>
    <div class="lp-top-img">
      <img src="https://mrocioa.com/wp-content/uploads/2025/08/mrocioa-8k-hdmi-2.1-switch-521arc-600x600.jpg" alt="MROCIOA S5 Pro 8K HDMI 2.1 Switch" width="200" height="200" loading="eager">
    </div>
  </div>
  <div class="lp-demo" id="lp-demo">
    <?php echo $raw; ?>
  </div>
</div>
<script type="application/ld+json">
{
  "@context": "https://schema.org/",
  "@type": "Product",
  "name": "MROCIOA S5 Pro 8K HDMI 2.1 Switch",
  "image": "https://mrocioa.com/wp-content/uploads/2025/08/mrocioa-8k-hdmi-2.1-switch-521arc-600x600.jpg",
  "description": "5-port HDMI 2.1 switch with eARC and ARC support, 4K 120Hz, 8K 60Hz, HDCP 2.3 cross-version compatibility for PS5, Xbox Series X and Apple TV 4K.",
  "brand": { "@type": "Brand", "name": "MROCIOA" },
  "sku": "521ARC",
  "aggregateRating": {
    "@type": "AggregateRating",
    "ratingValue": "<?php echo number_format( (float)$avg_rating, 1 ); ?>",
    "reviewCount": "<?php echo (int)$rev_count; ?>",
    "bestRating": "5",
    "worstRating": "1"
  },
  "hasMerchantReturnPolicy": {
    "@type": "MerchantReturnPolicy",
    "applicableCountry": "US",
    "returnPolicyCategory": "https://schema.org/MerchantReturnFiniteReturnWindow",
    "merchantReturnDays": 90,
    "returnMethod": "https://schema.org/ReturnByMail",
    "returnFees": "https://schema.org/FreeReturn"
  },
  "offers": {
    "@type": "Offer",
    "url": "https://mrocioa.com/product/8k-hdmi-switch-5-port-earc/",
    "priceCurrency": "USD",
    "price": "79.99",
    "availability": "https://schema.org/InStock",
    "itemCondition": "https://schema.org/NewCondition",
    "hasMerchantReturnPolicy": {
      "@type": "MerchantReturnPolicy",
      "applicableCountry": "US",
      "returnPolicyCategory": "https://schema.org/MerchantReturnFiniteReturnWindow",
      "merchantReturnDays": 90,
      "returnMethod": "https://schema.org/ReturnByMail",
      "returnFees": "https://schema.org/FreeReturn"
    },
    "shippingDetails": {
      "@type": "OfferShippingDetails",
      "shippingRate": { "@type": "MonetaryAmount", "value": "0", "currency": "USD" },
      "shippingDestination": { "@type": "DefinedRegion", "addressCountry": "US" },
      "deliveryTime": {
        "@type": "ShippingDeliveryTime",
        "handlingTime": { "@type": "QuantitativeValue", "minValue": 0, "maxValue": 1, "unitCode": "DAY" },
        "transitTime": { "@type": "QuantitativeValue", "minValue": 3, "maxValue": 7, "unitCode": "DAY" }
      }
    }
  }
}
</script>
<script data-cfasync="false" id="vx-fit">
(function(){
  var REF=1280, MREF=600, MAX_W=1280, BOTTOM_PAD=28;
  function fit(){
    var lp=document.getElementById('lp-demo');
    var w=document.querySelector('#lp-demo .vx-page .vx-wrap');
    if(!lp||!w)return;
    var cw=lp.offsetWidth;
    var refW=cw<MREF?MREF:REF;
    var scaleW=Math.min(cw,MAX_W);
    var s=scaleW/refW;
    var ml=Math.max(0,Math.floor((cw-refW*s)/2));
    w.style.cssText='';
    w.style.setProperty('width',refW+'px','important');
    w.style.setProperty('max-width','none','important');
    w.style.setProperty('margin-left',ml+'px','important');
    w.style.setProperty('margin-right','0','important');
    w.style.setProperty('overflow','visible','important');
    w.style.setProperty('padding-top','0','important');
    w.style.setProperty('padding-bottom','34px','important');
    w.style.setProperty('transform','scale('+s+')','important');
    w.style.setProperty('transform-origin','top left','important');
    var contentH=Math.ceil(w.getBoundingClientRect().height)+BOTTOM_PAD;
    var lpTop=lp.getBoundingClientRect().top+window.scrollY;
    var viewH=Math.max(contentH,window.innerHeight-lpTop);
    lp.style.height=viewH+'px';
    lp.style.overflow='hidden';
  }
  ['resize','orientationchange','load'].forEach(function(e){window.addEventListener(e,fit);});
  fit();setTimeout(fit,300);setTimeout(fit,1200);
})();
</script>
<?php get_footer(); ?>
