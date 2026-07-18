<?php
/**
 * Plugin Name: MROCIOA Yoast Default OG Image
 * Description: Ensures Yoast outputs a default Open Graph image when a page has none.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_filter(
	'wpseo_add_opengraph_additional_images',
	function ( $images ) {
		if ( ! is_object( $images ) || ! method_exists( $images, 'has_images' ) || ! method_exists( $images, 'add_image_by_id' ) ) {
			return $images;
		}

		if ( ! $images->has_images() ) {
			$images->add_image_by_id( 10071 );
		}

		return $images;
	}
);
