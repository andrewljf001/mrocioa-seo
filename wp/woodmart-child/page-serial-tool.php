<?php
/**
 * Template Name: MROCIOA Serial Tool
 *
 * A full-width WordPress shell for the isolated Web Serial application.
 */

$serial_canonical = home_url( '/web-serial-debugger/' );

add_filter( 'wpseo_canonical', '__return_false' );
add_action(
	'wp_head',
	static function () use ( $serial_canonical ) {
		echo '<link rel="canonical" href="' . esc_url( $serial_canonical ) . '">' . "\n";
	},
	1
);

get_header();

$app_path = get_stylesheet_directory() . '/assets/serial-tool/mrocioa-serial-tool.html';
$app_url  = get_stylesheet_directory_uri() . '/assets/serial-tool/mrocioa-serial-tool.html';
$uploads  = wp_upload_dir();

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

if ( file_exists( $app_path ) ) {
	$app_url = add_query_arg( 'ver', (string) filemtime( $app_path ), $app_url );
}

$collaboration_url = apply_filters(
	'mrocioa_serial_collaboration_url',
	defined( 'MROCIOA_SERIAL_COLLAB_URL' ) ? (string) MROCIOA_SERIAL_COLLAB_URL : home_url( '/serial-collab' )
);
if ( '' !== $collaboration_url ) {
	$app_url = add_query_arg( 'collab', $collaboration_url, $app_url );
}
?>
<style id="mro-serial-tool-page-css">
	body.page-template-page-serial-tool {
		background: #060a0e !important;
	}
	body.page-template-page-serial-tool .wd-page-title,
	body.page-template-page-serial-tool .page-title-bar,
	body.page-template-page-serial-tool .page-header,
	body.page-template-page-serial-tool .woocommerce-breadcrumb {
		display: none !important;
	}
	body.page-template-page-serial-tool .site-footer {
		margin-top: 0 !important;
	}
	body.page-template-page-serial-tool .wd-page-content,
	body.page-template-page-serial-tool #main-content {
		width: 100% !important;
		max-width: 100% !important;
		margin: 0 !important;
		padding: 0 !important;
	}
	body.page-template-page-serial-tool #main-content {
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
		padding: 8px 15px;
		border: 1px solid rgba(55, 213, 242, 0.72);
		background: rgba(55, 213, 242, 0.12);
		color: #ffffff !important;
		cursor: pointer;
		font-family: inherit;
		text-decoration: none !important;
		transition: background 0.18s ease, border-color 0.18s ease;
	}
	.mro-serial-fullscreen:hover,
	.mro-serial-fullscreen:focus-visible {
		border-color: #37d5f2;
		background: rgba(55, 213, 242, 0.15);
		color: #ffffff !important;
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
	.mro-serial-exit-fullscreen {
		position: absolute;
		z-index: 3;
		top: 8px;
		left: 50%;
		display: none;
		min-height: 30px;
		align-items: center;
		justify-content: center;
		padding: 6px 12px;
		border: 1px solid rgba(55, 213, 242, 0.58);
		border-radius: 4px;
		background: rgba(6, 10, 14, 0.84);
		box-shadow: 0 8px 24px rgba(0, 0, 0, 0.36);
		color: #ffffff;
		cursor: pointer;
		font: 700 11px/1.2 Inter, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
		letter-spacing: 0.035em;
		opacity: 0.78;
		transform: translateX(-50%);
		transition: opacity 0.18s ease, border-color 0.18s ease, background 0.18s ease;
	}
	.mro-serial-stage:fullscreen .mro-serial-exit-fullscreen,
	.mro-serial-stage:-webkit-full-screen .mro-serial-exit-fullscreen,
	.mro-serial-stage.is-focus-mode .mro-serial-exit-fullscreen {
		display: inline-flex;
	}
	.mro-serial-exit-fullscreen:hover,
	.mro-serial-exit-fullscreen:focus-visible {
		border-color: #37d5f2;
		background: rgba(6, 10, 14, 0.96);
		opacity: 1;
	}
	.mro-serial-exit-fullscreen span {
		margin-left: 5px;
		color: #cbdde4;
		font-weight: 600;
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
	@media (max-width: 899px) {
		body.page-template-page-serial-tool .wd-toolbar {
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
		.mro-serial-mobile-note {
			display: block;
		}
		.mro-serial-frame {
			width: 1180px;
			min-width: 1180px;
		}
	}
</style>

<main id="primary" class="mro-serial-page">
	<section class="mro-serial-intro" aria-labelledby="mro-serial-title">
		<div class="mro-serial-intro-in">
			<div class="mro-serial-copy">
				<span class="mro-serial-eyebrow">MROCIOA Lab Tools</span>
				<h1 id="mro-serial-title">Web Serial Debugger</h1>
				<p>Connect, monitor, decode and export serial data directly in your browser. No installation, no upload &mdash; your device data stays on this computer.</p>
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
				<span class="mro-serial-support" id="mro-serial-support">Checking Web Serial&hellip;</span>
				<button class="mro-serial-fullscreen" type="button" aria-controls="mro-serial-stage" aria-pressed="false" hidden>Enter full screen&nbsp; &#x26F6;</button>
			</div>
		</div>
	</section>

	<p class="mro-serial-mobile-note">Desktop Chrome or Microsoft Edge is recommended. On smaller screens, swipe sideways inside the tool to reach every panel.</p>

	<section class="mro-serial-browser-gate" id="mro-serial-browser-gate" hidden aria-labelledby="mro-serial-browser-title">
		<div class="mro-serial-browser-card">
			<span class="mro-serial-browser-icon" aria-hidden="true">&gt;_</span>
			<h2 id="mro-serial-browser-title">Open this tool in Chrome or Microsoft Edge</h2>
			<p>The Web Serial API is not available in your current browser. Reopen this page in the desktop version of Google Chrome or Microsoft Edge to connect a serial device.</p>
			<div class="mro-serial-browser-links">
				<a href="https://www.google.com/chrome/" target="_blank" rel="noopener">Get Google Chrome&nbsp; &#8599;</a>
				<a href="https://www.microsoft.com/edge/download" target="_blank" rel="noopener">Get Microsoft Edge&nbsp; &#8599;</a>
			</div>
		</div>
	</section>

	<section class="mro-serial-stage" id="mro-serial-stage" aria-label="MROCIOA Web Serial application" hidden>
		<button class="mro-serial-exit-fullscreen" id="mro-serial-exit-fullscreen" type="button" aria-label="Exit full screen" hidden>Exit full screen <span aria-hidden="true">&middot; Esc</span></button>
		<iframe
			class="mro-serial-frame"
			id="mro-serial-frame"
			data-src="<?php echo esc_url( $app_url ); ?>"
			title="MROCIOA Web Serial Debugger"
			allow="serial; screen-wake-lock"
			loading="eager"
			referrerpolicy="same-origin"
		></iframe>
	</section>

	<noscript><p class="mro-serial-noscript">JavaScript is required to run the Web Serial Debugger.</p></noscript>
</main>

<script data-cfasync="false" id="mro-serial-tool-page-js">
(function () {
	'use strict';

	var frame = document.getElementById('mro-serial-frame');
	var support = document.getElementById('mro-serial-support');
	var stage = document.getElementById('mro-serial-stage');
	var browserGate = document.getElementById('mro-serial-browser-gate');
	var fullScreen = document.querySelector('.mro-serial-fullscreen');
	var exitFullScreen = document.getElementById('mro-serial-exit-fullscreen');
	var productRotator = document.getElementById('mro-serial-products');
	var ua = navigator.userAgent || '';
	var brands = navigator.userAgentData && navigator.userAgentData.brands
		? navigator.userAgentData.brands.map(function (item) { return item.brand; }).join(' ')
		: '';
	var isEdge = /Edg\//.test(ua) || /Microsoft Edge/.test(brands);
	var isChrome = !isEdge && !/OPR\//.test(ua) && (/Chrome\//.test(ua) || /Google Chrome/.test(brands));
	var supported = (isChrome || isEdge) && 'serial' in navigator && window.isSecureContext;

	try {
		if (window.localStorage && window.localStorage.getItem('mrocioa.site-default-lang') !== 'en-v1') {
			var savedLanguage = window.localStorage.getItem('mrocioa.lang');
			if (savedLanguage === null || savedLanguage === 'bi') {
				window.localStorage.setItem('mrocioa.lang', 'en');
			}
			window.localStorage.setItem('mrocioa.site-default-lang', 'en-v1');
		}
	} catch (error) {}

	function applySiteThemeToTool() {
		if (!frame) return;
		try {
			var appDocument = frame.contentDocument;
			if (!appDocument || !appDocument.head || appDocument.getElementById('mrocioa-site-theme')) return;
			var themeStyle = appDocument.createElement('style');
			themeStyle.id = 'mrocioa-site-theme';
			themeStyle.textContent = ':root{' +
				'--bg-0:#060a0e;--bg-1:#0a1016;--bg-2:#0e161e;--bg-3:#131d27;' +
				'--panel-0:#0a1016;--panel-1:#0e161e;--panel-2:#131d27;' +
				'--border-0:#203543;--border-1:#315064;--border-2:#41677d;--border-accent:rgba(55,213,242,.76);' +
				'--fg-0:#ffffff;--fg-1:#ffffff;--fg-2:#f2f9fc;--fg-3:#cbdde4;' +
				'--accent-0:#37d5f2;--accent-1:#9c90ff;--accent-2:#69b6ff;' +
				'--accent-dim:rgba(55,213,242,.15);--accent-dim-2:rgba(55,213,242,.07);' +
				'--accent-glow:0 0 8px rgba(55,213,242,.45),0 0 20px rgba(123,108,246,.18);' +
				'--tx-0:#9c90ff;--sel-bg:rgba(55,213,242,.28);' +
				'--fw-regular:500;--fw-medium:600;--fw-semibold:700;' +
			'}' +
			'body{font-weight:500}' +
			'span,label{font-weight:500!important}' +
			'button{font-weight:600!important}' +
			'input,select,textarea{font-weight:500!important}' +
			'header{border-bottom:1px solid transparent!important;background:linear-gradient(var(--bg-1),var(--bg-1)) padding-box,linear-gradient(90deg,#37d5f2,#7b6cf6) border-box!important}' +
			'div[style*="background: var(--surface-card)"][style*="border: 1px solid var(--border-1)"]{border:1px solid transparent!important;background:linear-gradient(var(--surface-card),var(--surface-card)) padding-box,linear-gradient(135deg,rgba(55,213,242,.52),rgba(123,108,246,.38)) border-box!important}' +
			'div[style*="border-bottom: 1px solid var(--border-0)"]{border-bottom:0!important;background:linear-gradient(90deg,rgba(55,213,242,.62),rgba(123,108,246,.48)) left bottom/100% 1px no-repeat!important}' +
			'button[style*="background: var(--accent-dim)"][style*="border: 1px solid var(--border-accent)"]{border-color:rgba(129,155,255,.9)!important;background:linear-gradient(100deg,rgba(55,213,242,.34),rgba(123,108,246,.34))!important;color:#fff!important;box-shadow:0 0 10px rgba(55,213,242,.26),0 0 20px rgba(123,108,246,.16)!important}' +
			'span[style*="background: var(--accent-dim)"][style*="border: 1px solid var(--border-accent)"],div[style*="background: var(--accent-dim)"][style*="border: 1px solid var(--border-accent)"]{border-color:rgba(129,155,255,.82)!important;background:linear-gradient(100deg,rgba(55,213,242,.24),rgba(123,108,246,.22))!important;color:#fff!important}';
			appDocument.head.appendChild(themeStyle);
		} catch (error) {}
	}

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
		if (exitFullScreen) exitFullScreen.hidden = !active;
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
		if (exitFullScreen) {
			exitFullScreen.setAttribute('aria-label', 'Exit focus mode');
			exitFullScreen.innerHTML = 'Exit focus mode <span aria-hidden="true">&middot; Esc</span>';
		}
		updateFullscreenState();
	}

	function leaveFocusMode() {
		if (!stage) return;
		stage.classList.remove('is-focus-mode');
		document.body.classList.remove('mro-serial-focus-mode');
		if (exitFullScreen) {
			exitFullScreen.setAttribute('aria-label', 'Exit full screen');
			exitFullScreen.innerHTML = 'Exit full screen <span aria-hidden="true">&middot; Esc</span>';
		}
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

	function handleFullscreenEscape(event) {
		if (event.key === 'Escape' && isFocusMode()) leaveFocusMode();
	}

	if (frame) {
		frame.addEventListener('load', function () {
			applySiteThemeToTool();
			try {
				frame.contentDocument.addEventListener('keydown', handleFullscreenEscape);
			} catch (error) {}
		});
	}
	if (fullScreen) fullScreen.addEventListener('click', enterFullScreenMode);
	if (exitFullScreen) exitFullScreen.addEventListener('click', leaveFullScreenMode);
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

	if (support) {
		if (supported) {
			support.textContent = 'Web Serial ready';
		} else {
			support.textContent = 'Chrome / Edge required';
			support.classList.add('is-unsupported');
		}
	}

	if (supported) {
		if (stage) stage.hidden = false;
		if (browserGate) browserGate.hidden = true;
		if (fullScreen) fullScreen.hidden = false;
		if (frame && frame.dataset.src) frame.src = frame.dataset.src;
	} else {
		if (stage) stage.hidden = true;
		if (browserGate) browserGate.hidden = false;
		if (fullScreen) fullScreen.hidden = true;
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
	"name": "MROCIOA Web Serial Debugger",
	"applicationCategory": "DeveloperApplication",
	"operatingSystem": "Windows, macOS, Linux, ChromeOS",
	"browserRequirements": "Requires desktop Google Chrome or Microsoft Edge with Web Serial API support",
	"isAccessibleForFree": true,
	"description": "A free browser-based serial port monitor with multi-port sessions, live charts, protocol decoding, automation and data export."
}
</script>
<?php
get_footer();
