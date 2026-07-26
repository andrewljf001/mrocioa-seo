<?php
/**
 * Template Name: MROCIOA Label Tool
 *
 * A full-width WordPress shell for the isolated label design application.
 */

$label_canonical = home_url( '/label-printing-tool/' );

add_filter( 'wpseo_canonical', '__return_false' );
add_action(
	'wp_head',
	static function () use ( $label_canonical ) {
		echo '<link rel="canonical" href="' . esc_url( $label_canonical ) . '">' . "\n";
	},
	1
);

get_header();

$app_path          = WP_PLUGIN_DIR . '/labeltool/app/index.html';
$app_url           = plugins_url( 'labeltool/app/index.html' );
$decode_url        = '';
$label_access      = false;
$label_access_note = '';
$public_access     = false;
$uploads           = wp_upload_dir();

if ( ! function_exists( 'labeltool_token' ) || ! defined( 'LABELTOOL_VER' ) ) {
	$label_access_note = 'The Label Tool plugin is not active. Ask the site administrator to enable it.';
} else {
	$public_access = function_exists( 'labeltool_public_access' ) && labeltool_public_access();
}

if ( '' === $label_access_note && ! $public_access && ! is_user_logged_in() ) {
	$label_access_note = 'Sign in to open the label editor and its protected PDF decoding service.';
} elseif ( '' === $label_access_note && ! $public_access && ! current_user_can( labeltool_cap() ) ) {
	$label_access_note = 'Your account does not have permission to use the label editor.';
} elseif ( '' === $label_access_note && ! file_exists( $app_path ) ) {
	$label_access_note = 'The label editor application file is missing.';
} elseif ( '' === $label_access_note ) {
	$label_access = true;
	$decode_url   = rest_url( 'labeltool/v1/svc/' . labeltool_token() );
	$app_args     = array(
		'svc'         => $decode_url,
		'tpl_api'     => rest_url( 'labeltool/v1/templates' ),
		'tpl_session' => admin_url( 'admin-ajax.php?action=labeltool_template_session' ),
		'tpl_share'   => $label_canonical,
		'v'           => LABELTOOL_VER,
		'ver'         => (string) filemtime( $app_path ),
	);
	if ( isset( $_GET['template'] ) && ctype_digit( (string) $_GET['template'] ) ) {
		$app_args['template'] = (string) absint( $_GET['template'] );
	}
	$app_url      = add_query_arg(
		$app_args,
		$app_url
	);
}

$product_slides = array(
	array(
		'name'   => 'S5 Pro',
		'detail' => '8K HDMI 2.1 Switch',
		'url'    => home_url( '/product/8k-hdmi-switch-5-port-earc/' ),
		'image'  => trailingslashit( $uploads['baseurl'] ) . '2025/08/mrocioa-8k-hdmi-2.1-switch-521arc-148x148.webp',
	),
	array(
		'name'   => 'Thunderbolt 5',
		'detail' => '120Gbps · 240W Cable',
		'url'    => home_url( '/product/thunderbolt-5-cable-3ft-120gbps-16k-8k/' ),
		'image'  => trailingslashit( $uploads['baseurl'] ) . '2026/03/1-148x148.webp',
	),
	array(
		'name'   => 'DP16',
		'detail' => 'DisplayPort 2.1 · 54Gbps',
		'url'    => home_url( '/product/16k-displayport-cable-display-port-cable-6-6ft-54gbps-dp-cable/' ),
		'image'  => trailingslashit( $uploads['baseurl'] ) . '2026/03/displayport-2.1-cable-148x148.webp',
	),
	array(
		'name'   => 'HC10',
		'detail' => '8K HDMI 2.1 Cable',
		'url'    => home_url( '/product/8k-hdmi-cable-10-feet/' ),
		'image'  => trailingslashit( $uploads['baseurl'] ) . '2025/08/8K-HDMI-CABLE-2.1-4k-120hz-main-148x148.webp',
	),
);

?>
<style id="mro-serial-tool-page-css">
	body.page-template-page-label-tool {
		background: #060a0e !important;
	}
	body.page-template-page-label-tool .wd-page-title,
	body.page-template-page-label-tool .page-title-bar,
	body.page-template-page-label-tool .page-header,
	body.page-template-page-label-tool .woocommerce-breadcrumb {
		display: none !important;
	}
	body.page-template-page-label-tool .site-footer {
		margin-top: 0 !important;
	}
	body.page-template-page-label-tool .wd-page-content,
	body.page-template-page-label-tool #main-content {
		width: 100% !important;
		max-width: 100% !important;
		margin: 0 !important;
		padding: 0 !important;
	}
	body.page-template-page-label-tool #main-content {
		display: block !important;
	}
	.mro-serial-page,
	.mro-serial-page * {
		box-sizing: border-box;
	}
	.mro-serial-page {
		--mro-zero-cyan: #37d5f2;
		--mro-zero-violet: #7b6cf6;
		--mro-zero-grad: linear-gradient(90deg, var(--mro-zero-cyan), var(--mro-zero-violet));
		width: 100%;
		min-height: 720px;
		background: #060a0e;
		color: #e8f4f8;
		font-family: Inter, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
	}
	.mro-serial-page [hidden] {
		display: none !important;
	}
	.mro-serial-intro {
		position: relative;
		z-index: 2;
		padding: 18px clamp(18px, 3vw, 44px);
		border-bottom: 0;
		background:
			radial-gradient(420px 180px at 76% 0%, rgba(55, 213, 242, 0.13), transparent 72%),
			radial-gradient(360px 180px at 100% 0%, rgba(123, 108, 246, 0.08), transparent 74%),
			linear-gradient(135deg, #0a0a0f, #07141a);
	}
	.mro-serial-intro::after {
		position: absolute;
		inset: auto 0 0;
		height: 1px;
		background: var(--mro-zero-grad);
		content: "";
		opacity: 0.46;
	}
	.mro-serial-intro-in {
		display: flex;
		align-items: center;
		justify-content: space-between;
		gap: 28px;
		max-width: 1440px;
		margin: 0 auto;
	}
	.mro-serial-copy {
		flex: 1 1 520px;
		min-width: 0;
	}
	.mro-serial-eyebrow {
		display: block;
		margin-bottom: 4px;
		color: #37d5f2;
		font-size: 10px;
		font-weight: 700;
		letter-spacing: 0.16em;
		text-transform: uppercase;
	}
	.mro-serial-copy h1 {
		display: inline-block;
		width: fit-content;
		max-width: 100%;
		margin: 0 0 4px;
		background: var(--mro-zero-grad);
		background-clip: text;
		color: transparent;
		font-size: clamp(24px, 2.3vw, 34px);
		font-weight: 650;
		line-height: 1.12;
		letter-spacing: -0.025em;
		-webkit-background-clip: text;
		-webkit-text-fill-color: transparent;
	}
	.mro-serial-copy p {
		max-width: 620px;
		margin: 0;
		color: #e0e9ed;
		font-size: 13px;
		line-height: 1.55;
	}
	.mro-serial-value-tags {
		display: flex;
		align-items: center;
		gap: 7px;
		margin-top: 9px;
		flex-wrap: wrap;
	}
	.mro-serial-value-tag {
		display: inline-flex;
		min-height: 22px;
		align-items: center;
		padding: 3px 8px;
		border: 1px solid rgba(55, 213, 242, 0.34);
		border-radius: 999px;
		background: linear-gradient(90deg, rgba(55, 213, 242, 0.1), rgba(123, 108, 246, 0.07));
		color: #f2fbff;
		font-size: 9px;
		font-weight: 750;
		line-height: 1;
		letter-spacing: 0.11em;
		text-transform: uppercase;
		white-space: nowrap;
	}
	.mro-serial-value-tag::before {
		width: 5px;
		height: 5px;
		border-radius: 50%;
		background: #37d5f2;
		box-shadow: 0 0 8px rgba(55, 213, 242, 0.72);
		content: "";
	}
	.mro-serial-products {
		position: relative;
		width: clamp(250px, 23vw, 320px);
		height: 72px;
		flex: 0 1 320px;
		min-width: 250px;
	}
	.mro-serial-product-slide {
		position: absolute;
		inset: 0;
		display: grid;
		grid-template-columns: 64px minmax(0, 1fr) 20px;
		align-items: center;
		gap: 10px;
		padding: 5px 10px 5px 5px;
		border: 1px solid rgba(55, 213, 242, 0.34);
		border-radius: 5px;
		background: rgba(55, 213, 242, 0.07);
		opacity: 0;
		pointer-events: none;
		transform: translateX(8px);
		transition: opacity 0.3s ease, transform 0.3s ease, border-color 0.18s ease, background 0.18s ease;
		text-decoration: none !important;
	}
	.mro-serial-product-slide.is-active {
		opacity: 1;
		pointer-events: auto;
		transform: translateX(0);
	}
	.mro-serial-product-slide:hover,
	.mro-serial-product-slide:focus-visible {
		border-color: rgba(55, 213, 242, 0.58);
		background: rgba(55, 213, 242, 0.09);
	}
	.mro-serial-product-image {
		display: block;
		width: 62px;
		height: 62px;
		border-radius: 3px;
		background: #ffffff;
		object-fit: contain;
	}
	.mro-serial-product-copy {
		min-width: 0;
	}
	.mro-serial-product-kicker {
		display: block;
		margin-bottom: 2px;
		color: #cbd8de;
		font-size: 8px;
		font-weight: 700;
		letter-spacing: 0.13em;
		text-transform: uppercase;
	}
	.mro-serial-product-name {
		display: block;
		overflow: hidden;
		color: #ffffff;
		font-size: 12px;
		font-weight: 700;
		line-height: 1.25;
		text-overflow: ellipsis;
		white-space: nowrap;
	}
	.mro-serial-product-detail {
		display: block;
		overflow: hidden;
		margin-top: 2px;
		color: #e1e9ed;
		font-size: 10px;
		line-height: 1.25;
		text-overflow: ellipsis;
		white-space: nowrap;
	}
	.mro-serial-product-arrow {
		background: var(--mro-zero-grad);
		background-clip: text;
		color: transparent;
		font-size: 16px;
		font-weight: 700;
		text-align: center;
		-webkit-background-clip: text;
		-webkit-text-fill-color: transparent;
	}
	.mro-serial-actions {
		display: flex;
		flex: 0 0 auto;
		align-items: center;
		justify-content: flex-end;
		gap: 10px;
	}
	.mro-serial-support,
	.mro-serial-fullscreen {
		display: inline-flex;
		min-height: 34px;
		align-items: center;
		justify-content: center;
		border-radius: 4px;
		font-size: 11px;
		font-weight: 700;
		line-height: 1.2;
		letter-spacing: 0.035em;
	}
	.mro-serial-support {
		padding: 8px 12px;
		border: 1px solid rgba(0, 255, 156, 0.32);
		background: rgba(0, 255, 156, 0.08);
		color: #65ffc0;
	}
	.mro-serial-support.is-unsupported {
		border-color: rgba(255, 179, 0, 0.42);
		background: rgba(255, 179, 0, 0.1);
		color: #ffc241;
	}
	.mro-serial-fullscreen {
		appearance: none;
		min-height: 44px;
		padding: 7px 14px 7px 16px;
		border: 1px solid rgba(126, 233, 255, 0.9);
		background: linear-gradient(100deg, #37d5f2 0%, #7b6cf6 100%);
		box-shadow: 0 8px 24px rgba(55, 213, 242, 0.2), inset 0 1px 0 rgba(255, 255, 255, 0.32);
		color: #061018 !important;
		cursor: pointer;
		font-family: inherit;
		text-decoration: none !important;
		transition: filter 0.18s ease, transform 0.18s ease, box-shadow 0.18s ease;
	}
	.mro-serial-fullscreen:hover,
	.mro-serial-fullscreen:focus-visible {
		border-color: #ffffff;
		box-shadow: 0 10px 30px rgba(55, 213, 242, 0.32), inset 0 1px 0 rgba(255, 255, 255, 0.42);
		color: #061018 !important;
		filter: brightness(1.08);
		transform: translateY(-1px);
	}
	.mro-serial-cta-copy {
		font-size: 12px;
		font-weight: 800;
		letter-spacing: -0.01em;
		white-space: nowrap;
	}
	.mro-serial-cta-action {
		margin-left: 12px;
		padding-left: 12px;
		border-left: 1px solid rgba(6, 16, 24, 0.3);
		font-size: 9px;
		font-weight: 850;
		letter-spacing: 0.08em;
		text-transform: uppercase;
		white-space: nowrap;
	}
	.mro-serial-mobile-note {
		display: none;
		padding: 10px 16px;
		border-bottom: 1px solid rgba(255, 179, 0, 0.28);
		background: #171407;
		color: #ffd267;
		font-size: 12px;
		line-height: 1.5;
		text-align: center;
	}
	.mro-serial-stage {
		position: relative;
		width: 100%;
		overflow: auto;
		background: #060a0e;
		-webkit-overflow-scrolling: touch;
	}
	body.mro-serial-focus-mode {
		overflow: hidden !important;
	}
	.mro-serial-stage:fullscreen,
	.mro-serial-stage:-webkit-full-screen,
	.mro-serial-stage.is-focus-mode {
		width: 100vw;
		height: 100vh;
		height: 100dvh;
		max-width: none;
		overflow: hidden;
		background: #060a0e;
	}
	.mro-serial-stage.is-focus-mode {
		position: fixed;
		z-index: 2147483000;
		inset: 0;
	}
	.mro-serial-frame {
		display: block;
		width: 100%;
		height: 720px;
		border: 0;
		background: #060a0e;
	}
	.mro-serial-stage:fullscreen .mro-serial-frame,
	.mro-serial-stage:-webkit-full-screen .mro-serial-frame,
	.mro-serial-stage.is-focus-mode .mro-serial-frame {
		width: 100% !important;
		min-width: 0 !important;
		height: 100% !important;
	}
	.mro-serial-noscript {
		margin: 0;
		padding: 24px;
		background: #18110c;
		color: #ffd2bd;
		font-size: 14px;
		text-align: center;
	}
	.mro-serial-browser-gate {
		display: flex;
		min-height: 560px;
		align-items: center;
		justify-content: center;
		padding: 48px 20px;
		border-bottom: 1px solid #16222c;
		background:
			radial-gradient(520px 260px at 50% 25%, rgba(0, 229, 255, 0.09), transparent 72%),
			#060a0e;
		text-align: center;
	}
	.mro-serial-browser-card {
		width: min(100%, 620px);
		padding: clamp(28px, 5vw, 48px);
		border: 1px solid #1e2e3a;
		border-radius: 8px;
		background: rgba(10, 16, 22, 0.95);
		box-shadow: 0 18px 60px rgba(0, 0, 0, 0.36);
	}
	.mro-serial-browser-icon {
		display: inline-flex;
		width: 54px;
		height: 54px;
		align-items: center;
		justify-content: center;
		margin-bottom: 18px;
		border: 1px solid rgba(0, 229, 255, 0.4);
		border-radius: 50%;
		background: rgba(0, 229, 255, 0.08);
		color: #00e5ff;
		font: 700 20px/1 "SFMono-Regular", Consolas, monospace;
	}
	.mro-serial-browser-card h2 {
		margin: 0 0 10px;
		color: #ffffff;
		font-size: clamp(22px, 3vw, 30px);
		line-height: 1.2;
	}
	.mro-serial-browser-card p {
		margin: 0 auto 22px;
		color: #e0e9ed;
		font-size: 14px;
		line-height: 1.65;
	}
	.mro-serial-browser-links {
		display: flex;
		justify-content: center;
		gap: 10px;
		flex-wrap: wrap;
	}
	.mro-serial-browser-links a {
		display: inline-flex;
		min-height: 40px;
		align-items: center;
		justify-content: center;
		padding: 9px 18px;
		border: 1px solid rgba(55, 213, 242, 0.45);
		border-radius: 4px;
		background: rgba(55, 213, 242, 0.08);
		color: #69e8ff !important;
		font-size: 12px;
		font-weight: 700;
		text-decoration: none !important;
	}
	.mro-serial-browser-links a:hover,
	.mro-serial-browser-links a:focus-visible {
		border-color: #37d5f2;
		background: rgba(55, 213, 242, 0.15);
		color: #ffffff !important;
	}
	@media (max-width: 1399px) and (min-width: 900px) {
		.mro-serial-intro-in {
			flex-wrap: wrap;
		}
		.mro-serial-actions {
			width: 100%;
			justify-content: flex-end;
		}
	}
	@media (max-width: 899px) {
		body.page-template-page-label-tool .wd-toolbar {
			display: none !important;
		}
		.mro-serial-intro {
			padding: 16px 18px;
		}
		.mro-serial-intro-in {
			align-items: flex-start;
			flex-direction: column;
			gap: 12px;
		}
		.mro-serial-copy {
			flex: 0 1 auto;
		}
		.mro-serial-products {
			width: 100%;
			max-width: none;
			flex-basis: auto;
			min-width: 0;
		}
		.mro-serial-actions {
			width: 100%;
			justify-content: flex-start;
			flex-wrap: wrap;
		}
		.mro-serial-fullscreen {
			width: 100%;
			justify-content: space-between;
		}
		.mro-serial-cta-copy {
			white-space: normal;
			text-align: left;
		}
		.mro-serial-mobile-note {
			display: block;
		}
		.mro-serial-frame {
			width: 1180px;
			min-width: 1180px;
		}
		.mro-serial-page.is-viewer .mro-serial-frame {
			width: 100%;
			min-width: 0;
		}
	}
</style>

<main id="primary" class="mro-serial-page">
	<section class="mro-serial-intro" aria-labelledby="mro-serial-title">
		<div class="mro-serial-intro-in">
			<div class="mro-serial-copy">
				<span class="mro-serial-eyebrow">MROCIOA Lab Tools</span>
				<h1 id="mro-serial-title">Label Design &amp; Printing Tool</h1>
				<p>Create production labels, rebuild editable artwork from PDF, generate serial data and print directly to your workstation&rsquo;s label printer.</p>
				<div class="mro-serial-value-tags" aria-label="Product availability">
					<span class="mro-serial-value-tag">Free forever</span>
					<span class="mro-serial-value-tag">Continuously updated</span>
				</div>
			</div>
			<div class="mro-serial-products" id="mro-serial-products" aria-label="Featured MROCIOA products">
				<?php foreach ( $product_slides as $index => $product ) : ?>
					<a
						class="mro-serial-product-slide<?php echo 0 === $index ? ' is-active' : ''; ?>"
						href="<?php echo esc_url( $product['url'] ); ?>"
						aria-hidden="<?php echo 0 === $index ? 'false' : 'true'; ?>"
						tabindex="<?php echo 0 === $index ? '0' : '-1'; ?>"
					>
						<img class="mro-serial-product-image" src="<?php echo esc_url( $product['image'] ); ?>" alt="<?php echo esc_attr( 'MROCIOA ' . $product['name'] ); ?>" width="62" height="62" decoding="async">
						<span class="mro-serial-product-copy">
							<span class="mro-serial-product-kicker">Featured hardware</span>
							<strong class="mro-serial-product-name"><?php echo esc_html( $product['name'] ); ?></strong>
							<span class="mro-serial-product-detail"><?php echo esc_html( $product['detail'] ); ?></span>
						</span>
						<span class="mro-serial-product-arrow" aria-hidden="true">&#8594;</span>
					</a>
				<?php endforeach; ?>
			</div>
			<div class="mro-serial-actions">
				<span
					class="mro-serial-support<?php echo $label_access ? '' : ' is-unsupported'; ?>"
					id="mro-serial-support"
					data-decode-base="<?php echo esc_attr( $decode_url ); ?>"
				><?php echo $label_access ? 'Checking services&hellip;' : 'Sign-in required'; ?></span>
				<?php if ( $label_access ) : ?>
					<button class="mro-serial-fullscreen" type="button" aria-controls="mro-serial-stage" aria-pressed="false">
						<span class="mro-serial-cta-copy">Print perfect labels, anytime, anywhere.</span>
						<span class="mro-serial-cta-action">Start designing&nbsp; &#x26F6;</span>
					</button>
				<?php endif; ?>
			</div>
		</div>
	</section>

	<p class="mro-serial-mobile-note">Desktop Chrome or Microsoft Edge is recommended for local printer access. On smaller screens, swipe sideways inside the tool to reach every panel.</p>

	<?php if ( $label_access ) : ?>
		<section class="mro-serial-stage" id="mro-serial-stage" aria-label="MROCIOA label design and printing application">
			<iframe
				class="mro-serial-frame"
				id="mro-serial-frame"
				src="<?php echo esc_url( $app_url ); ?>"
				title="MROCIOA Label Design and Printing Tool"
				allow="serial; usb; fullscreen; clipboard-write"
				allowfullscreen
				loading="eager"
				referrerpolicy="same-origin"
			></iframe>
		</section>
	<?php else : ?>
		<section class="mro-serial-browser-gate" aria-labelledby="mro-label-access-title">
			<div class="mro-serial-browser-card">
				<span class="mro-serial-browser-icon" aria-hidden="true">&gt;_</span>
				<h2 id="mro-label-access-title">Label Tool access</h2>
				<p><?php echo esc_html( $label_access_note ); ?></p>
				<?php if ( ! $public_access && ! is_user_logged_in() ) : ?>
					<div class="mro-serial-browser-links">
						<a href="<?php echo esc_url( wp_login_url( get_permalink() ) ); ?>">Sign in to continue&nbsp; &#8594;</a>
					</div>
				<?php endif; ?>
			</div>
		</section>
	<?php endif; ?>

	<noscript><p class="mro-serial-noscript">JavaScript is required to run the Label Design &amp; Printing Tool.</p></noscript>
</main>

<script data-cfasync="false" id="mro-serial-tool-page-js">
(function () {
	'use strict';

	var frame = document.getElementById('mro-serial-frame');
	var support = document.getElementById('mro-serial-support');
	var stage = document.getElementById('mro-serial-stage');
	var fullScreen = document.querySelector('.mro-serial-fullscreen');
	var productRotator = document.getElementById('mro-serial-products');

	function getFullscreenElement() {
		return document.fullscreenElement || document.webkitFullscreenElement || null;
	}

	function isStageFullscreen() {
		return getFullscreenElement() === stage;
	}

	function isFocusMode() {
		return !!(stage && stage.classList.contains('is-focus-mode'));
	}

	function updateFullscreenState() {
		var active = isStageFullscreen() || isFocusMode();
		if (fullScreen) fullScreen.setAttribute('aria-pressed', active ? 'true' : 'false');
		if (frame && active) frame.style.height = '100%';
		if (frame && !active) {
			frame.style.height = '';
			window.requestAnimationFrame(sizeFrame);
		}
	}

	function enterFocusMode() {
		if (!stage) return;
		stage.classList.add('is-focus-mode');
		document.body.classList.add('mro-serial-focus-mode');
		updateFullscreenState();
	}

	function leaveFocusMode() {
		if (!stage) return;
		stage.classList.remove('is-focus-mode');
		document.body.classList.remove('mro-serial-focus-mode');
		updateFullscreenState();
	}

	function enterFullScreenMode() {
		if (!stage || isStageFullscreen() || isFocusMode()) return;
		var request = stage.requestFullscreen || stage.webkitRequestFullscreen;
		if (!request) {
			enterFocusMode();
			return;
		}
		try {
			var result = request.call(stage, { navigationUI: 'hide' });
			if (result && typeof result.catch === 'function') result.catch(enterFocusMode);
		} catch (error) {
			enterFocusMode();
		}
	}

	function leaveFullScreenMode() {
		if (isFocusMode()) {
			leaveFocusMode();
			return;
		}
		if (!isStageFullscreen()) return;
		var exit = document.exitFullscreen || document.webkitExitFullscreen;
		if (!exit) return;
		try {
			var result = exit.call(document);
			if (result && typeof result.catch === 'function') result.catch(function () {});
		} catch (error) {}
	}

	function toggleFullScreenMode() {
		if (isStageFullscreen() || isFocusMode()) {
			leaveFullScreenMode();
			return;
		}
		enterFullScreenMode();
	}

	function handleFullscreenEscape(event) {
		if (event.key === 'Escape' && isFocusMode()) leaveFocusMode();
	}

	if (frame) {
		frame.addEventListener('load', function () {
			try {
				frame.contentDocument.addEventListener('keydown', handleFullscreenEscape);
			} catch (error) {}
		});
	}
	if (fullScreen) fullScreen.addEventListener('click', toggleFullScreenMode);
	document.addEventListener('fullscreenchange', updateFullscreenState);
	document.addEventListener('webkitfullscreenchange', updateFullscreenState);
	document.addEventListener('keydown', handleFullscreenEscape);

	if (productRotator) {
		var productSlides = Array.prototype.slice.call(productRotator.querySelectorAll('.mro-serial-product-slide'));
		var productIndex = 0;
		var productTimer = null;
		var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

		function showProduct(index) {
			productSlides.forEach(function (slide, slideIndex) {
				var active = slideIndex === index;
				slide.classList.toggle('is-active', active);
				slide.setAttribute('aria-hidden', active ? 'false' : 'true');
				slide.setAttribute('tabindex', active ? '0' : '-1');
			});
		}

		function startProductRotation() {
			if (reduceMotion || productSlides.length < 2 || productTimer) return;
			productTimer = window.setInterval(function () {
				productIndex = (productIndex + 1) % productSlides.length;
				showProduct(productIndex);
			}, 3800);
		}

		function stopProductRotation() {
			if (!productTimer) return;
			window.clearInterval(productTimer);
			productTimer = null;
		}

		productRotator.addEventListener('mouseenter', stopProductRotation);
		productRotator.addEventListener('mouseleave', startProductRotation);
		productRotator.addEventListener('focusin', stopProductRotation);
		productRotator.addEventListener('focusout', startProductRotation);
		showProduct(productIndex);
		startProductRotation();
	}

	if (support && support.dataset.decodeBase) {
		var decodeStatus = support.dataset.decodeBase.replace(/\/$/, '') + '/status';
		Promise.allSettled([
			fetch(decodeStatus, { credentials: 'same-origin', cache: 'no-store' }).then(function (response) {
				if (!response.ok) throw new Error('decode');
				return response.json();
			}),
			fetch('http://localhost:8631/status', { cache: 'no-store' }).then(function (response) {
				if (!response.ok) throw new Error('print');
				return response.json();
			})
		]).then(function (results) {
			var decodeReady = results[0].status === 'fulfilled' && results[0].value && results[0].value.ok;
			var printReady = results[1].status === 'fulfilled' && results[1].value && results[1].value.ok;
			if (decodeReady && printReady) {
				support.textContent = results[1].value.connected ? 'Decode + printer ready' : 'Decode ready · printer disconnected';
			} else if (decodeReady) {
				support.textContent = 'Editor ready · print service offline';
			} else {
				support.textContent = 'PDF service unavailable';
			}
			support.classList.toggle('is-unsupported', !decodeReady);
		});
	}

	function sizeFrame() {
		if (!frame) return;
		if (isStageFullscreen() || isFocusMode()) {
			frame.style.height = '100%';
			return;
		}
		var compact = window.matchMedia('(max-width: 899px)').matches;
		var minimum = compact ? 820 : 720;
		var top = frame.getBoundingClientRect().top;
		var available = window.innerHeight - Math.max(0, top);
		frame.style.height = Math.max(minimum, available) + 'px';
	}

	window.addEventListener('resize', sizeFrame, { passive: true });
	window.addEventListener('orientationchange', sizeFrame, { passive: true });
	window.addEventListener('load', sizeFrame, { once: true });
	sizeFrame();
}());
</script>

<script type="application/ld+json">
{
	"@context": "https://schema.org",
	"@type": "WebApplication",
	"name": "MROCIOA Label Design & Printing Tool",
	"applicationCategory": "BusinessApplication",
	"operatingSystem": "Windows, macOS, Linux, ChromeOS",
	"browserRequirements": "A modern desktop browser; Chrome or Microsoft Edge is recommended for local printer access",
	"isAccessibleForFree": true,
	"description": "A browser-based industrial label editor with PDF reconstruction, barcode tools, serial data and local USB printing."
}
</script>
<?php
get_footer();
