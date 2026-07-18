<?php
defined( 'ABSPATH' ) || exit;
global $product;
$featured_id = $product->get_image_id();
$gallery_ids = $product->get_gallery_image_ids();
$all_ids = $featured_id ? array_merge( [ $featured_id ], (array) $gallery_ids ) : (array) $gallery_ids;
if ( empty( $all_ids ) ) return;
?>
<div class="mro-gallery woocommerce-product-gallery" id="mro-gallery">
<div class="mro-gallery__main" id="mro-main">
<div class="mro-track" id="mro-track">
<?php foreach ( $all_ids as $i => $img_id ) :
    $s = wp_get_attachment_image_src( $img_id, 'woocommerce_single' );
    if ( ! $s ) continue;
    $alt      = trim( get_post_meta( $img_id, '_wp_attachment_image_alt', true ) ) ?: get_the_title();
    $webp_src = preg_replace( '/\.(jpe?g|png)$/i', '.webp', $s[0] );
?>
<img class="mro-gs<?php echo $i === 0 ? ' is-a' : ''; ?>" src="<?php echo esc_url( $webp_src ); ?>" width="<?php echo (int)$s[1]; ?>" height="<?php echo (int)$s[2]; ?>" alt="<?php echo esc_attr( $alt ); ?>" <?php echo $i === 0 ? 'fetchpriority="high" loading="eager" decoding="sync"' : 'loading="lazy"'; ?>>
<?php endforeach; ?>
</div>
</div>
<?php if ( count( $all_ids ) > 1 ) : ?>
<div class="mro-thumb-nav">
<button class="mro-tarrow mro-tarrow--prev" aria-label="Previous">&#8249;</button>
<div class="mro-gallery__thumbs" id="mro-thumbs" role="listbox">
<?php foreach ( $all_ids as $i => $img_id ) :
    $t = wp_get_attachment_image_src( $img_id, 'woocommerce_thumbnail' );
    if ( ! $t ) continue;
    $alt    = trim( get_post_meta( $img_id, '_wp_attachment_image_alt', true ) );
    $webp_t = preg_replace( '/\.(jpe?g|png)$/i', '.webp', $t[0] );
?>
<button class="mro-gt<?php echo $i === 0 ? ' is-a' : ''; ?>" data-idx="<?php echo (int)$i; ?>" type="button" aria-selected="<?php echo $i === 0 ? 'true' : 'false'; ?>"><img src="<?php echo esc_url( $webp_t ); ?>" width="<?php echo (int)$t[1]; ?>" height="<?php echo (int)$t[2]; ?>" alt="<?php echo esc_attr( $alt ); ?>" loading="lazy"></button>
<?php endforeach; ?>
</div>
<button class="mro-tarrow mro-tarrow--next" aria-label="Next">&#8250;</button>
</div>
<?php endif; ?>
</div>
