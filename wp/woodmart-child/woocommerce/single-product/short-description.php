<?php if ( is_product() && get_post_field('post_name', get_the_ID()) === '8k-hdmi-switch-5-port-earc' ) : ?>
<a id="s5pro-btn" href="#demo" style="display:block;text-align:center;margin-bottom:14px;padding:12px 16px;font-size:14px;text-decoration:none;border-radius:6px;border:none;font-weight:500;cursor:pointer;background:linear-gradient(to right,#6B6FE5,#00C6D4);color:#fff;letter-spacing:0.05em;">Test your setup &#8212; try it live &#8595;</a>
<script>
document.getElementById("s5pro-btn").addEventListener("click",function(e){
    e.preventDefault();
    var found=document.querySelector('[src*="s5pro-virtual-demo"],[data-src*="s5pro-virtual-demo"],[data-lazy-src*="s5pro-virtual-demo"]');
    if(found){
        var hdr=document.querySelector(".site-header,.wd-header,header");
        window.scrollTo({top:found.getBoundingClientRect().top+window.scrollY-(hdr?hdr.offsetHeight+10:80),behavior:"smooth"});
    }
});
</script>
<?php endif; ?>

<?php
/**
 * Single product short description
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/short-description.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

global $post;

$short_description = apply_filters( 'woocommerce_short_description', $post->post_excerpt );

if ( ! $short_description ) {
	return;
}

?>
<div class="woocommerce-product-details__short-description">
	<?php echo $short_description; // WPCS: XSS ok. ?>
</div>
