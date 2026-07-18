<?php
/**
 * Plugin Name: MROCIOA Noindex And Sitemap Rules
 * Description: Keeps low-value utility, duplicate, and thin archive URLs out of search indexes and Yoast sitemaps, with small frontend SEO cleanup rules.
 * Version: 2026.07.06.1
 */

if (!defined('ABSPATH')) {
	exit;
}

function mrocioa_noindex_post_slug_rules(): array {
	return [
		'page' => [
			'cart',
			'checkout',
			'compare',
			'my-account',
			'track-order',
			'wishlist',
		],
		'product' => [
			'4k-60hz-hdmi-switch-3-in-1-out',
		],
		'woodmart_slide' => [
			'accessories-slider',
		],
	];
}

function mrocioa_noindex_term_slug_rules(): array {
	return [
		'category' => [
			'news',
			'technical-support',
			'uncategorized',
		],
		'post_tag' => [
			'av-accessory-supply-chain',
			'hdmi-accessory-price-increase',
			'hdmi-switch-buying-guide',
			'mlcc-price-spike',
			'resistor-price-increase',
		],
		'product_brand' => [
			'mrocioa',
		],
		'product_tag' => [
			'3-in-1-out-hdmi-switch',
			'4k-120hz-hdmi-switch',
			'4k-hdmi-switch',
			'8k-hdmi-cable',
			'8k-hdmi-switch',
			'dolby-vision-hdmi-switch',
			'hdmi-cord',
			'hdmi-splitter-3-in-1-out',
			'hdmi-splitter-5-in-1-out',
			'hdmi-switch-4k-120hz',
			'hdmi-switch-for-nintendo-switch',
			'hdmi-switch-for-xbox-series-x',
			'hdmi-switch-with-remote',
			'hdmi-switcher',
			'hdmi-switcher-4-in-1-out',
		],
	];
}

function mrocioa_noindex_whole_taxonomies(): array {
	return [
		'category',
		'post_tag',
		'product_brand',
		'product_tag',
	];
}

function mrocioa_noindex_whole_post_types(): array {
	return [
		'woodmart_slide',
	];
}

function mrocioa_noindex_sitemap_files(): array {
	return [
		'author-sitemap.xml',
		'category-sitemap.xml',
		'post_tag-sitemap.xml',
		'product_brand-sitemap.xml',
		'product_tag-sitemap.xml',
		'woodmart_slider-sitemap.xml',
	];
}

function mrocioa_noindex_blocked_sitemap_types(): array {
	return [
		'author',
		'category',
		'post_tag',
		'product_brand',
		'product_tag',
		'woodmart_slider',
	];
}

function mrocioa_noindex_post_ids(): array {
	$ids = [];

	foreach (mrocioa_noindex_post_slug_rules() as $post_type => $slugs) {
		foreach ($slugs as $slug) {
			$post = get_page_by_path($slug, OBJECT, $post_type);
			if ($post instanceof WP_Post) {
				$ids[] = (int) $post->ID;
			}
		}
	}

	return array_values(array_unique(array_filter($ids)));
}

function mrocioa_noindex_is_target_singular(): bool {
	if (!is_singular()) {
		return false;
	}

	$post = get_queried_object();
	if (!$post instanceof WP_Post) {
		return false;
	}

	$rules = mrocioa_noindex_post_slug_rules();
	$post_type = get_post_type($post);

	return isset($rules[$post_type]) && in_array($post->post_name, $rules[$post_type], true);
}

function mrocioa_noindex_is_target_term(): bool {
	if (!is_category() && !is_tag() && !is_tax()) {
		return false;
	}

	$term = get_queried_object();
	if (!$term instanceof WP_Term) {
		return false;
	}

	$rules = mrocioa_noindex_term_slug_rules();

	if (in_array($term->taxonomy, mrocioa_noindex_whole_taxonomies(), true)) {
		return true;
	}

	return isset($rules[$term->taxonomy]) && in_array($term->slug, $rules[$term->taxonomy], true);
}

function mrocioa_noindex_should_apply(): bool {
	return mrocioa_noindex_is_target_singular()
		|| mrocioa_noindex_is_target_term()
		|| is_author();
}

function mrocioa_seo_sitemap_image_replacements(): array {
	return [
		'https://mrocioa.com/ps5-xbox-troube/' => 'https://mrocioa.com/wp-content/uploads/2026/05/PS5-XBOX-TROUBE.webp',
	];
}

function mrocioa_seo_normalize_url_for_match(string $url): string {
	$url = strtok($url, '?#') ?: $url;

	return trailingslashit($url);
}

function mrocioa_seo_replace_sitemap_image_url(string $url): string {
	$normalized = mrocioa_seo_normalize_url_for_match($url);

	foreach (mrocioa_seo_sitemap_image_replacements() as $from => $to) {
		if ($normalized === mrocioa_seo_normalize_url_for_match($from)) {
			return $to;
		}
	}

	return $url;
}

function mrocioa_seo_meta_overrides(): array {
	return [
		'product' => [
			'4k-60hz-hdmi-switch-5-in-1-out' => [
				'title' => '4K 60Hz HDMI Switch 5 In 1 Out with ARC | MROCIOA',
				'description' => '5-in-1 4K 60Hz HDMI switch with ARC and remote for PS5, Xbox, Apple TV and streaming devices. Switch inputs cleanly without cable swapping.',
			],
			'16k-displayport-cable-display-port-cable-6-6ft-54gbps-dp-cable' => [
				'title' => '16K DisplayPort Cable 6.6ft 54Gbps DP Cable | MROCIOA',
				'description' => '16K DisplayPort 2.1 cable with 54Gbps bandwidth for gaming monitors, GPUs and workstations. Supports high refresh rates and stable video output.',
			],
			'thunderbolt-5-cable-3ft-120gbps-16k-8k' => [
				'title' => 'Thunderbolt 5 Cable 3.3ft 120Gbps 240W | MROCIOA',
				'description' => 'Thunderbolt 5 cable for 120Gbps data, 240W charging, 16K/8K displays and fast laptop docks. Built for USB-C workstations and creators.',
			],
		],
		'post' => [
			'ps5-and-xbox-series-x-black-screen-on-hdmi-switch-fix-it-once-and-for-all' => [
				'description' => 'Fix PS5 and Xbox Series X black screen issues on HDMI switches with EDID, cable, port and eARC setup checks for stable 4K 120Hz gaming.',
			],
			'hdmi-2-2-ultra96-home-theater-upgrade-guide' => [
				'description' => 'Learn what HDMI 2.2 and Ultra96 mean for home theater buyers, when to upgrade and how current HDMI switches, cables, TVs and soundbars fit.',
			],
		],
	];
}

function mrocioa_seo_current_meta_override(): array {
	$post = get_queried_object();
	if (!$post instanceof WP_Post) {
		return [];
	}

	$overrides = mrocioa_seo_meta_overrides();
	$post_type = get_post_type($post);
	$slug = (string) $post->post_name;

	return $overrides[$post_type][$slug] ?? [];
}

add_filter('wpseo_title', function ($title) {
	$override = mrocioa_seo_current_meta_override();

	return isset($override['title']) ? $override['title'] : $title;
}, 999);

add_filter('wpseo_opengraph_title', function ($title) {
	$override = mrocioa_seo_current_meta_override();

	return isset($override['title']) ? $override['title'] : $title;
}, 999);

add_filter('wpseo_twitter_title', function ($title) {
	$override = mrocioa_seo_current_meta_override();

	return isset($override['title']) ? $override['title'] : $title;
}, 999);

add_filter('pre_get_document_title', function ($title) {
	$override = mrocioa_seo_current_meta_override();

	return isset($override['title']) ? $override['title'] : $title;
}, 999);

add_filter('wpseo_metadesc', function ($description) {
	$override = mrocioa_seo_current_meta_override();

	return isset($override['description']) ? $override['description'] : $description;
}, 999);

add_filter('wpseo_opengraph_desc', function ($description) {
	$override = mrocioa_seo_current_meta_override();

	return isset($override['description']) ? $override['description'] : $description;
}, 999);

add_filter('wpseo_twitter_description', function ($description) {
	$override = mrocioa_seo_current_meta_override();

	return isset($override['description']) ? $override['description'] : $description;
}, 999);

add_filter('wpseo_robots', function ($robots) {
	if (!mrocioa_noindex_should_apply()) {
		return $robots;
	}

	return 'noindex, nofollow';
}, 999);

add_filter('wp_robots', function (array $robots): array {
	if (!mrocioa_noindex_should_apply()) {
		return $robots;
	}

	unset($robots['index'], $robots['follow']);
	$robots['noindex'] = true;
	$robots['nofollow'] = true;

	return $robots;
}, 999);

add_action('send_headers', function (): void {
	if (!mrocioa_noindex_should_apply()) {
		return;
	}

	header('X-Robots-Tag: noindex, nofollow', true);
}, 20);

add_filter('wpseo_exclude_from_sitemap_by_post_ids', function ($excluded_ids): array {
	$excluded_ids = is_array($excluded_ids) ? $excluded_ids : [];

	return array_values(array_unique(array_merge($excluded_ids, mrocioa_noindex_post_ids())));
});

add_filter('wpseo_sitemap_exclude_post_type', function ($excluded, $post_type) {
	if (in_array($post_type, mrocioa_noindex_whole_post_types(), true)) {
		return true;
	}

	return $excluded;
}, 10, 2);

add_filter('wpseo_sitemap_exclude_taxonomy', function ($excluded, $taxonomy) {
	if (in_array($taxonomy, mrocioa_noindex_whole_taxonomies(), true)) {
		return true;
	}

	return $excluded;
}, 10, 2);

add_filter('wpseo_sitemap_entry', function ($url, $type, $object) {
	if ($object instanceof WP_User || $type === 'author' || $type === 'user') {
		return false;
	}

	if ($object instanceof WP_Term || (is_object($object) && isset($object->term_id, $object->taxonomy, $object->slug))) {
		$taxonomy = (string) $object->taxonomy;
		$slug = (string) $object->slug;
		$rules = mrocioa_noindex_term_slug_rules();
		if (isset($rules[$taxonomy]) && in_array($slug, $rules[$taxonomy], true)) {
			return false;
		}
	}

	if ($object instanceof WP_Post || (is_object($object) && isset($object->ID, $object->post_name, $object->post_type))) {
		$rules = mrocioa_noindex_post_slug_rules();
		$post_type = $object instanceof WP_Post ? get_post_type($object) : (string) $object->post_type;
		$post_name = (string) $object->post_name;
		if (isset($rules[$post_type]) && in_array($post_name, $rules[$post_type], true)) {
			return false;
		}
	}

	return $url;
}, 10, 3);

add_filter('wpseo_sitemap_post_type_archive_link', function ($archive_url, $post_type) {
	if ($post_type === 'product') {
		return false;
	}

	return $archive_url;
}, 999, 2);

add_filter('wpseo_xml_sitemap_img_src', function ($src) {
	return mrocioa_seo_replace_sitemap_image_url((string) $src);
}, 999);

add_filter('wpseo_sitemap_urlimages', function ($images): array {
	if (!is_array($images)) {
		return [];
	}

	foreach ($images as $key => $image) {
		if (is_array($image) && isset($image['src'])) {
			$images[$key]['src'] = mrocioa_seo_replace_sitemap_image_url((string) $image['src']);
		}
	}

	return $images;
}, 999);

add_filter('wpseo_sitemap_url', function ($output): string {
	foreach (mrocioa_seo_sitemap_image_replacements() as $from => $to) {
		$output = str_replace(esc_url($from), esc_url($to), $output);
		$output = str_replace(esc_url(untrailingslashit($from)), esc_url($to), $output);
	}

	return $output;
}, 999);

add_filter('wpseo_sitemap_index_links', function (array $links): array {
	$blocked_files = mrocioa_noindex_sitemap_files();

	return array_values(array_filter($links, function ($link) use ($blocked_files): bool {
		if (!is_array($link) || empty($link['loc'])) {
			return true;
		}

		$path = parse_url((string) $link['loc'], PHP_URL_PATH);
		$file = $path ? basename($path) : basename((string) $link['loc']);

		return !in_array($file, $blocked_files, true);
	}));
}, 999);

add_filter('wpseo_build_sitemap_post_type', function ($type) {
	if (in_array($type, mrocioa_noindex_blocked_sitemap_types(), true)) {
		return '__mrocioa_blocked_sitemap__';
	}

	return $type;
}, 999);

add_filter('wpseo_sitemap_index', function ($sitemap_index) {
	foreach (mrocioa_noindex_sitemap_files() as $file) {
		$pattern = '#\s*<sitemap>\s*<loc>https?://[^<]+/' . preg_quote($file, '#') . '</loc>.*?</sitemap>#s';
		$sitemap_index = preg_replace($pattern, '', $sitemap_index);
	}

	return $sitemap_index;
}, 999);

remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'wp_shortlink_wp_head');
remove_action('template_redirect', 'wp_shortlink_header', 11);

add_filter('xmlrpc_enabled', '__return_false');
add_filter('pings_open', '__return_false', 20);

add_filter('bloginfo_url', function ($output, $show) {
	if ($show === 'pingback_url') {
		return '';
	}

	return $output;
}, 10, 2);

add_action('send_headers', function (): void {
	if (!defined('REST_REQUEST') || !REST_REQUEST) {
		return;
	}

	mrocioa_noindex_send_rest_nocache_headers();
}, 0);

add_action('send_headers', function (): void {
	if (headers_sent()) {
		return;
	}

	if (is_ssl()) {
		header('Strict-Transport-Security: max-age=31536000', true);
	}

	header('X-Content-Type-Options: nosniff', true);
	header('X-Frame-Options: SAMEORIGIN', true);
	header("Content-Security-Policy: frame-ancestors 'self'; base-uri 'self'", true);
	header('Permissions-Policy: accelerometer=(), camera=(), geolocation=(), gyroscope=(), magnetometer=(), microphone=(), usb=(), interest-cohort=()', true);
}, 1);

function mrocioa_noindex_send_rest_nocache_headers(): void {
	if (headers_sent()) {
		return;
	}

	header_remove('Cache-Control');
	header('Cache-Control: no-cache, no-store, must-revalidate, max-age=0', true);
	header('Pragma: no-cache', true);
	header('Expires: Wed, 11 Jan 1984 05:00:00 GMT', true);
}

add_filter('rest_pre_serve_request', function ($served) {
	mrocioa_noindex_send_rest_nocache_headers();

	return $served;
}, 0);

add_filter('wp_get_attachment_image_attributes', function (array $attr): array {
	if (isset($attr['srcset']) && trim((string) $attr['srcset']) === '') {
		unset($attr['srcset']);
	}

	return $attr;
}, 999);

function mrocioa_noindex_should_cleanup_frontend_html(): bool {
	if (is_admin() || wp_doing_ajax() || wp_doing_cron()) {
		return false;
	}

	if ((defined('REST_REQUEST') && REST_REQUEST) || (defined('XMLRPC_REQUEST') && XMLRPC_REQUEST)) {
		return false;
	}

	$method = isset($_SERVER['REQUEST_METHOD']) ? strtoupper((string) $_SERVER['REQUEST_METHOD']) : 'GET';

	return in_array($method, ['GET', 'HEAD'], true);
}

function mrocioa_noindex_remove_first_entry_title_h1(string $html): string {
	return preg_replace(
		'/<h1\b([^>]*\bclass=(["\'])(?=[^"\']*\bentry-title\b)(?=[^"\']*\btitle\b)[^"\']*\2[^>]*)>(.*?)<\/h1>/is',
		'',
		$html,
		1
	) ?? $html;
}

function mrocioa_noindex_demote_h1s_after_first(string $html): string {
	$seen = 0;

	return preg_replace_callback(
		'/<h1\b([^>]*)>(.*?)<\/h1>/is',
		function (array $matches) use (&$seen): string {
			$seen++;
			if ($seen === 1) {
				return $matches[0];
			}

			return '<h2' . $matches[1] . '>' . $matches[2] . '</h2>';
		},
		$html
	) ?? $html;
}

function mrocioa_noindex_ensure_hidden_h1(string $html, string $title): string {
	if (preg_match('/<h1\b/i', $html)) {
		return $html;
	}

	$h1 = '<h1 class="screen-reader-text mro-audit-h1">' . esc_html($title) . '</h1>';
	$updated = preg_replace('/(<main\b[^>]*>)/i', '$1' . $h1, $html, 1);
	if (is_string($updated) && $updated !== $html) {
		return $updated;
	}

	return preg_replace('/(<body\b[^>]*>)/i', '$1' . $h1, $html, 1) ?? $html;
}

function mrocioa_seo_replace_first_h1(string $html, string $heading): string {
	return preg_replace(
		'/<h1\b([^>]*)>.*?<\/h1>/is',
		'<h1$1>' . esc_html($heading) . '</h1>',
		$html,
		1
	) ?? $html;
}

function mrocioa_seo_count_headings(string $html, string $tag): int {
	$count = preg_match_all('/<' . preg_quote($tag, '/') . '\b/i', $html);

	return is_int($count) ? $count : 0;
}

function mrocioa_seo_add_supporting_h2(string $html, string $heading, int $minimum_h2_count = 1): string {
	if (mrocioa_seo_count_headings($html, 'h2') >= $minimum_h2_count) {
		return $html;
	}

	if (strpos($html, $heading) !== false) {
		return $html;
	}

	$h2 = '<h2 class="screen-reader-text mro-seo-supporting-heading">' . esc_html($heading) . '</h2>';

	return preg_replace('/(<\/h1>)/i', '$1' . $h2, $html, 1) ?? $html;
}

function mrocioa_seo_product_supporting_h2(): string {
	if (function_exists('has_term') && has_term('av-switches', 'product_cat')) {
		return 'HDMI switch features, eARC audio and 4K 120Hz device compatibility';
	}

	if (function_exists('has_term') && has_term('av-cables', 'product_cat')) {
		return 'AV cable bandwidth, display support and device compatibility';
	}

	if (function_exists('has_term') && has_term('usb-cables', 'product_cat')) {
		return 'USB-C cable and adapter specs for laptops, monitors and workstations';
	}

	return 'Product specifications, compatibility and setup details';
}

function mrocioa_seo_product_category_headings(): array {
	return [
		'av-switches' => [
			'h1' => 'MROCIOA HDMI 2.1 AV Switches for 8K and 4K 120Hz',
			'h2' => 'Compare HDMI switch inputs, eARC audio and gaming display support',
		],
		'av-cables' => [
			'h1' => 'MROCIOA 8K HDMI and DisplayPort AV Cables',
			'h2' => 'Choose AV cables by bandwidth, refresh rate and display setup',
		],
		'usb-cables' => [
			'h1' => 'MROCIOA USB-C Cables and Display Adapters',
			'h2' => 'Connect USB-C laptops to HDMI, DisplayPort and high-resolution displays',
		],
	];
}

function mrocioa_noindex_cleanup_frontend_html(string $html): string {
	if ($html === '') {
		return $html;
	}

	$html = preg_replace(
		'/<meta\s+name=(["\'])viewport\1\s+content=(["\'])(.*?)\2\s*\/?>/i',
		'<meta name="viewport" content="width=device-width, initial-scale=1.0">',
		$html,
		1
	);

	$html = preg_replace('/<link\b[^>]*\brel=(["\']?)pingback\1[^>]*>/i', '', $html);
	$html = preg_replace('/\s+srcset(?=\s+sizes=)/i', '', $html);
	$html = preg_replace('/href=(["\'])https?:\/\/mrocioa\.com\/cdn-cgi\/l\/email-protection\1/i', 'href="mailto:support@mrocioa.com"', $html);
	$html = preg_replace('/href=(["\'])\/cdn-cgi\/l\/email-protection\1/i', 'href="mailto:support@mrocioa.com"', $html);
	$html = preg_replace('/(href=["\'][^"\']*\?shop_view=grid)(?:&amp;|&)(["\'])/i', '$1$2', $html);
	$fallback_alt = 'MROCIOA HDMI switch and AV accessory image';
	$html = preg_replace('/<img\b(?![^>]*\balt=)([^>]*)>/i', '<img alt="' . esc_attr($fallback_alt) . '"$1>', $html);
	$html = preg_replace('/(<img\b[^>]*\balt=)(["\'])\s*\2/i', '$1$2' . esc_attr($fallback_alt) . '$2', $html);

	if (is_front_page() || is_page('s5pro-landing')) {
		$html = mrocioa_noindex_remove_first_entry_title_h1($html);
	}

	if (is_front_page()) {
		$html = mrocioa_seo_replace_first_h1($html, 'MROCIOA HDMI Switches, 8K Cables and AV Accessories');
		$html = mrocioa_seo_add_supporting_h2($html, 'Shop HDMI 2.1 switches, 8K HDMI cables and USB-C AV accessories', 2);
	}

	if (function_exists('is_shop') && is_shop()) {
		$html = mrocioa_seo_replace_first_h1($html, 'Shop HDMI Switches, 8K Cables and AV Accessories');
		$html = mrocioa_seo_add_supporting_h2($html, 'Browse HDMI 2.1 switches, 8K cables, USB-C adapters and AV accessories', 2);
	}

	if (function_exists('is_product_category') && is_product_category()) {
		$term = get_queried_object();
		$slug = $term instanceof WP_Term ? (string) $term->slug : '';
		$headings = mrocioa_seo_product_category_headings();
		if (isset($headings[$slug])) {
			$html = mrocioa_seo_replace_first_h1($html, $headings[$slug]['h1']);
			$html = mrocioa_seo_add_supporting_h2($html, $headings[$slug]['h2'], 2);
		}
	}

	if (function_exists('is_product') && is_product()) {
		$html = mrocioa_seo_add_supporting_h2($html, mrocioa_seo_product_supporting_h2(), 1);
	}

	if (is_page('s5pro-landing')) {
		$html = mrocioa_seo_add_supporting_h2($html, 'S5 Pro HDMI 2.1 switch setup for PS5, Xbox, Apple TV and eARC soundbars', 2);
	}

	if (
		is_singular('post')
		|| is_page([
			'shipping',
			'shipping-policy',
			'shipping-information',
			'delivery-information',
			'privacy-policy',
			'refund_returns',
			'refund-returns',
			'refund-returns-policy',
			'returns-policy',
			'refund-policy',
			'refund-and-returns-policy',
			's5pro-landing',
		])
	) {
		$html = mrocioa_noindex_demote_h1s_after_first($html);
	}

	if (is_page('home-accessories')) {
		$html = mrocioa_noindex_ensure_hidden_h1($html, 'Home Accessories');
	}

	return $html;
}

add_action('template_redirect', function (): void {
	if (!mrocioa_noindex_should_cleanup_frontend_html()) {
		return;
	}

	ob_start('mrocioa_noindex_cleanup_frontend_html');
}, 0);

add_action('wp_head', function (): void {
	if (is_admin() || is_feed() || is_robots() || (function_exists('wp_is_json_request') && wp_is_json_request())) {
		return;
	}
	?>
<style id="mrocioa-layout-hotfix">
html,
body {
	max-width: 100%;
	overflow-x: clip;
}
.woocommerce-shop .wd-cat-image,
.woocommerce-shop .category-image,
.woocommerce-shop .wd-cat-image img,
.woocommerce-shop .category-image img {
	box-sizing: border-box;
	display: block;
	height: auto;
	max-width: 100% !important;
	width: 100% !important;
}
.woocommerce-shop .wd-carousel-container,
.woocommerce-shop .wd-carousel-wrap,
.woocommerce-shop .wd-products-element,
.woocommerce-shop .wd-cats {
	max-width: 100%;
	overflow: hidden;
}
.mro-cta-band,
.mro-blog-cta {
	box-sizing: border-box;
	width: 100vw !important;
	max-width: 100vw !important;
	margin-left: calc(50% - 50vw) !important;
	margin-right: calc(50% - 50vw) !important;
	overflow: hidden;
}
.mro-cta-band *,
.mro-blog-cta * {
	box-sizing: border-box;
	max-width: 100%;
}
	.mroA-wrap {
		--mro-midnight: #080b14;
		--mro-panel: #111827;
		--mro-cyan: #37D5F2;
		--mro-indigo: #7B6CF6;
		--mro-soft-blue: #4ea6ff;
	}
	.mroA-wrap .mroA-hero.mroA-sec {
		padding-bottom: clamp(22px, 2.6vw, 40px) !important;
	}
	.mroA-wrap .mroA-stats {
		padding: clamp(26px, 3vw, 42px) 24px clamp(28px, 3.2vw, 46px) !important;
		background: #11141d !important;
		border-top: 1px solid rgba(78, 166, 255, .16) !important;
	}
	.mroA-wrap .mroA-stats .mroA-in {
		display: grid !important;
		grid-template-columns: repeat(4, minmax(0, 1fr)) !important;
		gap: 18px 28px !important;
	}
	.mroA-wrap .mroA-stat {
		display: flex !important;
		flex-direction: column !important;
		align-items: center !important;
		gap: 8px !important;
		text-align: center !important;
		font-size: 14px !important;
		line-height: 1.35 !important;
		color: #9099aa !important;
	}
	.mroA-wrap .mroA-stat .mroA-dot {
		display: none !important;
	}
	.mroA-wrap .mroA-stat b {
		order: -1;
		display: block !important;
		color: var(--mro-soft-blue) !important;
		font-size: clamp(24px, 2.35vw, 36px) !important;
		line-height: 1 !important;
		font-weight: 700 !important;
		letter-spacing: 0 !important;
	}
	@media (max-width: 767px) {
		.mroA-wrap .mroA-stats .mroA-in {
			grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
		}
	}
	.mroA-wrap .mroA-eyebrow {
		gap: 10px !important;
		width: fit-content;
	max-width: 100%;
	padding: 9px 15px !important;
	border: 1px solid rgba(55, 213, 242, .38) !important;
	border-radius: 999px !important;
	background: rgba(6, 20, 33, .74) !important;
	color: var(--mro-cyan) !important;
	letter-spacing: .16em !important;
}
.mroA-wrap .mroA-eyebrow i {
	display: block !important;
	width: 8px !important;
	height: 8px !important;
	border-radius: 999px !important;
	background: var(--mro-cyan) !important;
	box-shadow: 0 0 0 5px rgba(55, 213, 242, .10), 0 0 18px rgba(55, 213, 242, .65);
	flex: 0 0 auto;
}
.mroA-wrap .mroA-btn--ink {
	background: linear-gradient(95deg, var(--mro-indigo), var(--mro-cyan)) !important;
	color: #fff !important;
	box-shadow: 0 18px 44px rgba(123, 108, 246, .34), 0 8px 28px rgba(55, 213, 242, .20) !important;
}
.mroA-wrap .mroA-btn--ink:hover {
	color: #fff !important;
	box-shadow: 0 20px 50px rgba(123, 108, 246, .42), 0 10px 32px rgba(55, 213, 242, .28) !important;
}
.mroA-wrap .mroA-btn--ghost {
	background: rgba(255, 255, 255, .035) !important;
	border-color: rgba(55, 213, 242, .25) !important;
	color: #e6edf7 !important;
}
.mroA-wrap .mroA-chip {
	background: rgba(9, 13, 23, .86) !important;
	border-color: rgba(43, 53, 72, .95) !important;
	color: #9aa4b5 !important;
	box-shadow: inset 0 1px 0 rgba(255,255,255,.035);
	}
		.mroA-wrap .mroA-chip b {
			color: #60d6ff !important;
			-webkit-text-fill-color: #60d6ff !important;
			text-shadow: -8px 0 18px rgba(123, 108, 246, .18), 8px 0 18px rgba(55, 213, 242, .22);
		}
.mroA-wrap .mroA-cat {
	border-color: #e3e8f1 !important;
	background: #fff !important;
	box-shadow: 0 16px 36px rgba(11, 14, 20, .055) !important;
}
.mroA-wrap .mroA-cat:before {
	height: 4px !important;
	opacity: 1 !important;
	background: linear-gradient(90deg, var(--mro-indigo), var(--mro-cyan)) !important;
}
.mroA-wrap .mroA-cat:nth-child(2):before {
	background: linear-gradient(90deg, #5f7cff, #9b7bff) !important;
}
.mroA-wrap .mroA-cat:nth-child(3):before {
	background: linear-gradient(90deg, var(--mro-cyan), #56a9ff) !important;
}
.mroA-wrap .mroA-cat .mroA-arrow {
	right: 24px !important;
	width: 34px;
	height: 34px;
	display: inline-flex;
	align-items: center;
	justify-content: center;
	border-radius: 999px;
	background: #f0f6ff;
	color: var(--mro-indigo) !important;
}
.mroA-wrap .mroA-cat:hover .mroA-arrow {
	background: linear-gradient(135deg, var(--mro-indigo), var(--mro-cyan));
	color: #fff !important;
}
.mroA-wrap .mroA-ic,
.mro-val .ic {
	background: linear-gradient(135deg, rgba(55, 213, 242, .12), rgba(123, 108, 246, .10)) !important;
	color: var(--mro-indigo) !important;
	box-shadow: inset 0 0 0 1px rgba(123, 108, 246, .08);
}
body.term-av-switches .wd-products,
body.term-av-cables .wd-products,
body.term-usb-cables .wd-products,
body.woocommerce-shop .wd-products {
	margin-bottom: clamp(36px, 5vw, 64px) !important;
}
body.term-av-switches .mro-vals-wrap,
body.term-av-cables .mro-vals-wrap,
body.term-usb-cables .mro-vals-wrap,
body.woocommerce-shop .mro-vals-wrap {
	margin-top: clamp(28px, 4vw, 52px) !important;
}
@media (max-width: 520px) {
	body.single-product.postid-10244 iframe.vxf {
		height: clamp(1020px, calc(322vw - 15px), 1570px) !important;
	}
}
body.woocommerce-checkout form.woocommerce-checkout > .woocommerce-NoticeGroup,
body.woocommerce-checkout form.woocommerce-checkout > .woocommerce-NoticeGroup-checkout {
	grid-column: 1 / -1 !important;
	width: 100% !important;
	max-width: 100% !important;
	box-sizing: border-box !important;
	margin: 0 !important;
}
body.woocommerce-checkout form.woocommerce-checkout > .woocommerce-NoticeGroup .woocommerce-error,
body.woocommerce-checkout form.woocommerce-checkout > .woocommerce-NoticeGroup-checkout .woocommerce-error {
	width: 100% !important;
	max-width: 100% !important;
	box-sizing: border-box !important;
	margin: 0 0 16px !important;
}
body.woocommerce-checkout.mro-alt-payment-selected .form-row.place-order > button#place_order {
	display: none !important;
}
body .cky-consent-container .cky-consent-bar {
	position: relative;
	overflow: hidden;
	background: linear-gradient(145deg, #0b0e14 0%, #111827 100%) !important;
	border: 1px solid rgba(55, 213, 242, .22) !important;
	border-radius: 18px !important;
	box-shadow: 0 24px 80px rgba(3, 8, 16, .48) !important;
	color: #dfe7f3 !important;
	font-family: Inter, Arial, sans-serif !important;
}
body .cky-consent-container {
	left: 50% !important;
	right: auto !important;
	bottom: 18px !important;
	width: min(940px, calc(100vw - 32px)) !important;
	max-width: min(940px, calc(100vw - 32px)) !important;
	transform: translateX(-50%) !important;
	padding: 0 !important;
}
body .cky-consent-container .cky-consent-bar {
	width: 100% !important;
	max-width: 100% !important;
	margin: 0 auto !important;
}
body .cky-consent-container .cky-consent-bar:before {
	content: "";
	position: absolute;
	inset: 0 0 auto 0;
	height: 3px;
	background: linear-gradient(90deg, #7B6CF6, #37D5F2);
}
body .cky-notice .cky-title,
body .cky-preference-header .cky-preference-title {
	color: #fff !important;
	font-family: "Space Grotesk", Inter, Arial, sans-serif !important;
	font-weight: 600 !important;
}
body .cky-notice-des,
body .cky-notice-des *,
body .cky-preference-content-wrapper,
body .cky-preference-content-wrapper *,
body .cky-accordion-header-des,
body .cky-accordion-header-des * {
	color: #aeb8c7 !important;
}
body .cky-notice-des a.cky-policy,
body .cky-preference-content-wrapper a,
body .cky-btn-close {
	color: #37D5F2 !important;
}
body .cky-btn {
	border-radius: 10px !important;
	font-family: Inter, Arial, sans-serif !important;
	font-weight: 600 !important;
	text-shadow: none !important;
	box-shadow: none !important;
}
body .cky-btn-accept,
body .cky-prefrence-btn-wrapper .cky-btn-accept {
	background: linear-gradient(95deg, #7B6CF6, #37D5F2) !important;
	border-color: transparent !important;
	color: #fff !important;
	box-shadow: 0 10px 26px rgba(123, 108, 246, .28) !important;
}
body .cky-btn-reject,
body .cky-btn-customize,
body .cky-btn-preferences,
body .cky-prefrence-btn-wrapper .cky-btn {
	background: rgba(255,255,255,.035) !important;
	border-color: rgba(55, 213, 242, .34) !important;
	color: #dfe7f3 !important;
}
body .cky-preference-center,
body .cky-preference,
body .cky-preference-header,
body .cky-footer-wrapper,
body .cky-prefrence-btn-wrapper {
	background: #0b0e14 !important;
	border-color: rgba(55, 213, 242, .18) !important;
	color: #dfe7f3 !important;
}
@media (max-width: 767px) {
	.single-post .wd-toolbar,
	.page-id-9500 .wd-toolbar {
		display: none !important;
	}
	.cky-consent-container .cky-consent-bar {
		padding: 12px 14px !important;
	}
	body .cky-consent-container {
		bottom: 10px !important;
		width: calc(100vw - 20px) !important;
		max-width: calc(100vw - 20px) !important;
	}
	.cky-notice .cky-title {
		font-size: 15px !important;
		line-height: 18px !important;
		margin-bottom: 6px !important;
	}
	.cky-notice-group {
		display: block !important;
	}
	.cky-notice-des {
		font-size: 12px !important;
		line-height: 17px !important;
		max-height: 54px;
		overflow: auto;
	}
	.cky-notice-btn-wrapper {
		gap: 8px !important;
		margin-top: 8px !important;
	}
	.cky-btn {
		font-size: 12px !important;
		line-height: 16px !important;
		min-height: 0 !important;
		padding: 8px 10px !important;
	}
		body.page-id-11 .mro-cta,
		body.page-id-9506 .mro-cta {
			padding: 56px 24px 64px !important;
		}
		body.page-id-11 .mro-cta .mro-btn,
		body.page-id-9506 .mro-cta .mro-btn {
			display: flex !important;
			align-items: center;
			justify-content: center;
			width: min(100%, 260px);
			min-height: 56px;
			margin: 0 auto !important;
		}
		body.page-id-11 .mro-cta .mro-btn + .mro-btn,
		body.page-id-9506 .mro-cta .mro-btn + .mro-btn {
			margin-top: 14px !important;
		}
}
</style>
		<?php
	}, 40);

add_action('wp_footer', function (): void {
	if (is_admin() || !function_exists('is_checkout') || !is_checkout()) {
		return;
	}
	?>
<script id="mrocioa-checkout-payment-buttons">
(function () {
	var cardGateway = 'ppcp-credit-card-gateway';

	function updatePlaceOrderState() {
		var selected = document.querySelector('input[name="payment_method"]:checked');
		var isCard = selected && selected.value === cardGateway;

		document.body.classList.toggle('mro-card-payment-selected', !!isCard);
		document.body.classList.toggle('mro-alt-payment-selected', !!selected && !isCard);
	}

	function scheduleUpdate() {
		updatePlaceOrderState();
		window.setTimeout(updatePlaceOrderState, 150);
		window.setTimeout(updatePlaceOrderState, 600);
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', scheduleUpdate);
	} else {
		scheduleUpdate();
	}

	document.addEventListener('change', function (event) {
		if (event.target && event.target.name === 'payment_method') {
			scheduleUpdate();
		}
	});

	if (window.jQuery) {
		window.jQuery(function ($) {
			$(document.body).on('updated_checkout payment_method_selected', scheduleUpdate);
			scheduleUpdate();
		});
	}
}());
</script>
	<?php
}, 30);

add_action('send_headers', function (): void {
	if (!mrocioa_noindex_should_cleanup_frontend_html() || headers_sent()) {
		return;
	}

	if (is_feed() || is_robots() || (function_exists('wp_is_json_request') && wp_is_json_request())) {
		return;
	}

	if (
		(function_exists('is_cart') && is_cart())
		|| (function_exists('is_checkout') && is_checkout())
		|| (function_exists('is_account_page') && is_account_page())
		|| is_page(['cart', 'checkout', 'my-account', 'wishlist', 'compare', 'track-order'])
	) {
		header_remove('Cache-Control');
		nocache_headers();
		header('Cache-Control: no-cache, no-store, must-revalidate, max-age=0, private', true);
		header('X-Robots-Tag: noindex, nofollow', true);
		return;
	}

	header_remove('Cache-Control');
	header('Cache-Control: public, max-age=300, s-maxage=31536000, stale-while-revalidate=86400', true);
}, 999);

add_filter('wp_headers', function (array $headers): array {
	if (
		(function_exists('is_cart') && is_cart())
		|| (function_exists('is_checkout') && is_checkout())
		|| (function_exists('is_account_page') && is_account_page())
		|| is_page(['cart', 'checkout', 'my-account', 'wishlist', 'compare', 'track-order'])
	) {
		$headers['Cache-Control'] = 'no-cache, no-store, must-revalidate, max-age=0, private';
		$headers['Pragma'] = 'no-cache';
		$headers['Expires'] = 'Wed, 11 Jan 1984 05:00:00 GMT';
		$headers['X-Robots-Tag'] = 'noindex, nofollow';
	}

	return $headers;
}, 9999);

add_action('pre_get_posts', function (WP_Query $query): void {
	if (is_admin() || !$query->is_main_query() || !$query->is_search()) {
		return;
	}

	$excluded = mrocioa_noindex_post_ids();
	if (!$excluded) {
		return;
	}

	$current = $query->get('post__not_in');
	$current = is_array($current) ? $current : [];
	$query->set('post__not_in', array_values(array_unique(array_merge($current, $excluded))));
});
