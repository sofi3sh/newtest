<?php
/**
 * Premium Tab
 *
 * @package YITH WooCommerce Badge Management
 */

?>
<style>
	.section {
		margin-left  : -20px;
		margin-right : -20px;
		font-family  : "Raleway", san-serif;
	}

	.section h1 {
		text-align     : center;
		text-transform : uppercase;
		color          : #808a97;
		font-size      : 35px;
		font-weight    : 700;
		line-height    : normal;
		display        : inline-block;
		width          : 100%;
		margin         : 50px 0 0;
	}

	.section:nth-child(odd) {
		background-color : #f9f9f9;
	}

	.section:nth-child(even), .section:first-child {
		background-color : #fff;
	}

	.section .section-title img {
		display        : table-cell;
		vertical-align : middle;
		width          : auto;
		margin-right   : 15px;
	}

	.section h2,
	.section h3 {
		display        : inline-block;
		vertical-align : middle;
		padding        : 0;
		font-size      : 24px;
		font-weight    : 700;
		color          : #808a97;
		text-transform : uppercase;
		border         : none;
		background     : transparent;
	}

	.section .section-title h2 {
		display        : table-cell;
		vertical-align : middle;
		line-height    : 25px;
	}

	.section-title {
		display : table;
	}

	.section h3 {
		font-size     : 14px;
		line-height   : 28px;
		margin-bottom : 0;
		display       : block;
	}

	.section p {
		font-size : 13px;
		margin    : 25px 0;
	}

	.section ul li {
		margin-bottom : 4px;
	}

	.landing-container {
		max-width    : 750px;
		margin-left  : auto;
		margin-right : auto;
		padding      : 50px 0 30px;
	}

	.landing-container:after {
		display : block;
		clear   : both;
		content : '';
	}

	.landing-container .col-1,
	.landing-container .col-2 {
		float      : left;
		box-sizing : border-box;
		padding    : 0 15px;
	}

	.landing-container .col-1 img {
		width : 100%;
	}

	.landing-container .col-1 {
		width : 55%;
	}

	.landing-container .col-2 {
		width : 45%;
	}

	.premium-cta {
		background-color : #808a97;
		color            : #fff;
		border-radius    : 6px;
		padding          : 20px 15px;
	}

	.premium-cta:after {
		content : '';
		display : block;
		clear   : both;
	}

	.premium-cta p {
		margin      : 7px 0;
		font-size   : 14px;
		font-weight : 500;
		display     : inline-block;
		width       : 60%;
	}

	.premium-cta a.button {
		border-radius : 6px;
		height        : 60px;
		float         : right;
		background    : #ff643f url( <?php echo esc_url_raw( YITH_WCBM_ASSETS_URL . '/images/upgrade.png' ); ?> ) no-repeat 13px 13px;
		border-color  : #ff643f;
		box-shadow    : none;
		outline       : none;
		color         : #fff;
		position      : relative;
		padding       : 9px 50px 9px 70px;
	}

	.premium-cta a.button:hover,
	.premium-cta a.button:active,
	.premium-cta a.button:focus {
		color        : #fff;
		background   : #971d00 url( <?php echo esc_url_raw( YITH_WCBM_ASSETS_URL . '/images/upgrade.png' ); ?> ) no-repeat 13px 13px;
		border-color : #971d00;
		box-shadow   : none;
		outline      : none;
	}

	.premium-cta a.button:focus {
		top : 1px;
	}

	.premium-cta a.button span {
		line-height : 13px;
	}

	.premium-cta a.button .highlight {
		display     : block;
		font-size   : 20px;
		font-weight : 700;
		line-height : 20px;
	}

	.premium-cta .highlight {
		text-transform : uppercase;
		background     : none;
		font-weight    : 800;
		color          : #fff;
	}

	@media (max-width : 768px) {
		.section {
			margin : 0
		}

		.premium-cta p {
			width : 100%;
		}

		.premium-cta {
			text-align : center;
		}

		.premium-cta a.button {
			float : none;
		}
	}

	@media (max-width : 480px) {
		.wrap {
			margin-right : 0;
		}

		.section {
			margin : 0;
		}

		.landing-container .col-1,
		.landing-container .col-2 {
			width   : 100%;
			padding : 0 15px;
		}

		.section-odd .col-1 {
			float        : left;
			margin-right : -100%;
		}

		.section-odd .col-2 {
			float      : right;
			margin-top : 65%;
		}
	}

	@media (max-width : 320px) {
		.premium-cta a.button {
			padding : 9px 20px 9px 70px;
		}

		.section .section-title img {
			display : none;
		}
	}
</style>
<div class="yith-plugin-fw-panel-custom-tab-container landing">
	<div class="section section-cta section-odd">
		<div class="landing-container">
			<div class="premium-cta">
				<p>
					Upgrade to the <span class="highlight">premium version</span>
					of <span class="highlight">YITH WooCommerce Badge Management</span> to benefit from all features!
				</p>
				<a href="<?php echo esc_url_raw( $this->get_premium_landing_uri() ); ?>" target="_blank" class="premium-cta-button button btn">
					<span class="highlight">UPGRADE</span>
					<span>to the premium version</span>
				</a>
			</div>
		</div>
	</div>
	<div class="section section-even clear" style="background: #fff url( <?php echo esc_url_raw( YITH_WCBM_ASSETS_URL . '/images/01-bg.png' ); ?> ) no-repeat 85% 75%;">
		<h1>Premium Features</h1>
		<div class="landing-container">
			<div class="col-1">
				<img src="<?php echo esc_url_raw( YITH_WCBM_ASSETS_URL . '/images/01.png' ); ?>" alt="Automatic Badges"/>
			</div>
			<div class="col-2">
				<div class="section-title">
					<img src="<?php echo esc_url_raw( YITH_WCBM_ASSETS_URL . '/images/01-icon.png' ); ?>" alt="icon"/>
					<h2>Assign badges automatically</h2>
				</div>
				<p>
					A badge for <b>recent product</b>, another one for on <b>sale products</b> and another one for <b>featured</b> ones. Go
					to plugin panel and enjoy automatic attribution feature of badges: as soon as the product is set as
					belonging to one the categories above mentioned, it will be automatically associated to the relevant
					badge, without you having to do that manually.
				</p>
			</div>
		</div>
	</div>
	<div class="section section-odd clear" style="background: #f9f9f9 url( <?php echo esc_url_raw( YITH_WCBM_ASSETS_URL . '/images/02-bg.png' ); ?> ) no-repeat 15% 100%">
		<div class="landing-container">
			<div class="col-2">
				<div class="section-title">
					<img src="<?php echo esc_url_raw( YITH_WCBM_ASSETS_URL . '/images/02-icon.png' ); ?>" alt="icon 02"/>
					<h2>Category badges</h2>
				</div>
				<p>
					A badge for each product category. You might feel the need to create a badge for each product category: <b>you can with the premium version of the plugin</b>.<br>
					A very effective strategy if you want to highlight specific products or if you want to give a nice touch to your store.
				</p>
			</div>
			<div class="col-1">
				<img src="<?php echo esc_url_raw( YITH_WCBM_ASSETS_URL . '/images/02.png' ); ?>" alt="category badges"/>
			</div>
		</div>
	</div>
	<div class="section section-even clear" style="background: #fff url( <?php echo esc_url_raw( YITH_WCBM_ASSETS_URL . '/images/03-bg.png' ); ?> ) no-repeat 85% 100%">
		<div class="landing-container">
			<div class="col-1">
				<img src="<?php echo esc_url_raw( YITH_WCBM_ASSETS_URL . '/images/03.png' ); ?>" alt="Advanced badges"/>
			</div>
			<div class="col-2">
				<div class="section-title">
					<img src="<?php echo esc_url_raw( YITH_WCBM_ASSETS_URL . '/images/03-icon.png' ); ?>" alt="icon 03"/>
					<h2>Advanced badges</h2>
				</div>
				<p>
					The plugin gives you the possibility to create countless badges entirely conceived for you, but (and
					this is wonderful news), it allows you to use a series of <b>advanced badges</b>, ready for you, and easy
					to adapt to the style of your shop.
				</p>
			</div>
		</div>
	</div>
	<div class="section section-odd clear" style="background: #f9f9f9 url( <?php echo esc_url_raw( YITH_WCBM_ASSETS_URL . '/images/04-bg.png' ); ?> ) no-repeat #f9f9f9 15% 100%">
		<div class="landing-container">
			<div class="col-2">
				<div class="section-title">
					<img src="<?php echo esc_url_raw( YITH_WCBM_ASSETS_URL . '/images/04-icon.png' ); ?>" alt="icon 04"/>
					<h2>CSS BADGE</h2>
				</div>
				<p>
					Discover the new section <b>“CSS Badge”</b>, where you can find a selection of badges entirely design as
					CSS and that you can customise in text and color. They are perfect to give a <b>special touch to your
						products</b> and to create ad hoc badges for your store.
				</p>
			</div>
			<div class="col-1">
				<img src="<?php echo esc_url_raw( YITH_WCBM_ASSETS_URL . '/images/04.png' ); ?>" alt="css badge"/>
			</div>
		</div>
	</div>
	<div class="section section-even clear" style="background: #fff url(<?php echo esc_url_raw( YITH_WCBM_ASSETS_URL . '/images/05-bg.png' ); ?>) no-repeat 85% 100%">
		<div class="landing-container">
			<div class="col-1">
				<img src="<?php echo esc_url_raw( YITH_WCBM_ASSETS_URL . '/images/05.png' ); ?>" alt="Style options"/>
			</div>
			<div class="col-2">
				<div class="section-title">
					<img src="<?php echo esc_url_raw( YITH_WCBM_ASSETS_URL . '/images/05-icon.png' ); ?>" alt="icon 05"/>
					<h2>Additional style options</h2>
				</div>
				<p>
					Attention to detail is important and if you want to create the perfect badge for you, you can
					exploit additional style options in the option panel, where <b>you can set font size, values for
						padding and border radius and opacity of your badge</b>.
				</p>
			</div>
		</div>
	</div>
	<div class="section section-odd clear" style="background: #f9f9f9 url( <?php echo esc_url_raw( YITH_WCBM_ASSETS_URL . '/images/07-bg.png' ); ?> ) no-repeat 15% 100%">
		<div class="landing-container">
			<div class="col-2">
				<div class="section-title">
					<img src="<?php echo esc_url_raw( YITH_WCBM_ASSETS_URL . '/images/07-icon.png' ); ?>" alt="icon 07"/>
					<h2>Even more badges</h2>
				</div>
				<p>
					A collection of <b>ready badges</b>, which enlarges more and more and which is entirely at your disposal. A series of badges carefully designed and using the best colours for an e-commerce store.
					But, do not stop here! If you have a badge that meets your needs better, upload it through the dedicated button and apply it to the product it has been conceived for.
				</p>
			</div>
			<div class="col-1">
				<img src="<?php echo esc_url_raw( YITH_WCBM_ASSETS_URL . '/images/07.png' ); ?>" alt="Image badges"/>
			</div>
		</div>
	</div>
	<div class="section section-even clear" style="background: #fff url(<?php echo esc_url_raw( YITH_WCBM_ASSETS_URL . '/images/06-bg.png' ); ?>) no-repeat 85% 100%">
		<div class="landing-container">
			<div class="col-1">
				<img src="<?php echo esc_url_raw( YITH_WCBM_ASSETS_URL . '/images/06.png' ); ?>" alt="positioning system"/>
			</div>
			<div class="col-2">
				<div class="section-title">
					<img src="<?php echo esc_url_raw( YITH_WCBM_ASSETS_URL . '/images/06-icon.png' ); ?>" alt="icon 06"/>
					<h2>Positioning system</h2>
				</div>
				<p>
					Move your badge with "Drag & Drop" method and see where it appears, by using it in the preview box.
					Set the <b>anchor point</b> for your badge, in the context of the product image, and specify position
					values for the four borders of the box (top, right, bottom, left).
				</p>
			</div>
		</div>
	</div>
	<div class="section section-odd clear" style="background: #f9f9f9 url(<?php echo esc_url_raw( YITH_WCBM_ASSETS_URL . '/images/08-bg.png' ); ?>) no-repeat 15% 100%">
		<div class="landing-container">
			<div class="col-2">
				<div class="section-title">
					<img src="<?php echo esc_url_raw( YITH_WCBM_ASSETS_URL . '/images/08-icon.png' ); ?>" alt="icon 08"/>
					<h2>Hide the badge on the single product</h2>
				</div>
				<p>
					A specific option that allows you to <b>hide the badge in product detail page</b>, while its behaviour will not be changed in other pages in the shop.
				</p>
			</div>
			<div class="col-1">
				<img src="<?php echo esc_url_raw( YITH_WCBM_ASSETS_URL . '/images/08.png' ); ?>" alt="hide badge"/>
			</div>
		</div>
	</div>
	<div class="section section-even clear" style="background: #fff url(<?php echo esc_url_raw( YITH_WCBM_ASSETS_URL . '/images/09-bg.png' ); ?>) no-repeat 85% 100%">
		<div class="landing-container">
			<div class="col-1">
				<img src="<?php echo esc_url_raw( YITH_WCBM_ASSETS_URL . '/images/09.png' ); ?>" alt="schedule badge"/>
			</div>
			<div class="col-2">
				<div class="section-title">
					<img src="<?php echo esc_url_raw( YITH_WCBM_ASSETS_URL . '/images/09-icon.png' ); ?>" alt="icon 09"/>
					<h2>Schedule your badge</h2>
				</div>
				<p>
					Show your badges automatically setting the <b>starting date</b> and the <b>ending date</b> in which you want to display them in the featured images of your products. In this way, you won't have to edit always your product settings.
				</p>
			</div>
		</div>
	</div>
	<div class="section section-odd clear" style="background: #f9f9f9 url( <?php echo esc_url_raw( YITH_WCBM_ASSETS_URL . '/images/10-bg.png' ); ?> ) no-repeat 15% 100%">
		<div class="landing-container">
			<div class="col-2">
				<div class="section-title">
					<img src="<?php echo esc_url_raw( YITH_WCBM_ASSETS_URL . '/images/10-icon.png' ); ?>" alt="icon 10"/>
					<h2>OUT OF STOCK PRODUCTS</h2>
				</div>
				<p>
					This premium version allows you to apply a badge for all <b>unavailable products</b> in the shop. When a product is out of stock, an automatic badge will be displayed.
				</p>
			</div>
			<div class="col-1">
				<img src="<?php echo esc_url_raw( YITH_WCBM_ASSETS_URL . '/images/10.png' ); ?>" alt="out of stock products"/>
			</div>
		</div>
	</div>
	<div class="section section-even clear" style="background: #fff url( <?php echo esc_url_raw( YITH_WCBM_ASSETS_URL . '/images/11-bg.png' ); ?>) no-repeat 85% 100%">
		<div class="landing-container">
			<div class="col-1">
				<img src="<?php echo esc_url_raw( YITH_WCBM_ASSETS_URL . '/images/11.png' ); ?>" alt="badge for multiple products"/>
			</div>
			<div class="col-2">
				<div class="section-title">
					<img src="<?php echo esc_url_raw( YITH_WCBM_ASSETS_URL . '/images/11-icon.png' ); ?>" alt="icon 11"/>
					<h2>SAME BADGE FOR MULTIPLE PRODUCTS</h2>
				</div>
				<p>
					In WooCommerce product page you can simultaneously apply a badge to more products. An <b>easier</b> and <b>faster</b> way to use your badges!
				</p>
			</div>
		</div>
	</div>
	<div class="section section-odd clear" style="background: #f9f9f9 url( <?php echo esc_url_raw( YITH_WCBM_ASSETS_URL . '/images/12-bg.png' ); ?> ) no-repeat15% 100%">
		<div class="landing-container">
			<div class="col-2">
				<div class="section-title">
					<img src="<?php echo esc_url_raw( YITH_WCBM_ASSETS_URL . '/images/12-icon.png' ); ?>" alt="icon 12"/>
					<h2>WOOCOMMERCE "ON SALE" BADGE</h2>
				</div>
				<p>
					If there are other badges configurated with <b>YITH WooCommerce Badge Management</b> in a product, you can choose to hide or display WooCommerce "On sale" badge in that same product
				</p>
			</div>
			<div class="col-1">
				<img src="<?php echo esc_url_raw( YITH_WCBM_ASSETS_URL . '/images/12.png' ); ?>" alt="out of stock products"/>
			</div>
		</div>
	</div>
	<div class="section section-even clear" style="background: #fff url(<?php echo esc_url_raw( YITH_WCBM_ASSETS_URL . '/images/13-bg.png' ); ?>) no-repeat 85% 100%">
		<div class="landing-container">
			<div class="col-1">
				<img src="<?php echo esc_url_raw( YITH_WCBM_ASSETS_URL . '/images/13.png' ); ?>" alt="badge for multiple products"/>
			</div>
			<div class="col-2">
				<div class="section-title">
					<img src="<?php echo esc_url_raw( YITH_WCBM_ASSETS_URL . '/images/13-icon.png' ); ?>" alt="icon 11"/>
					<h2>SHIPPING CLASSES</h2>
				</div>
				<p>
					Do you use WooCommerce shipping classes for the products of your shop? Badge Management thought about you!<br>
					Select the badge you want to use for any configured shipping class and you will have a further reference on your product images.
				</p>
			</div>
		</div>
	</div>
	<div class="section section-odd clear" style="background: #f9f9f9 url(<?php echo esc_url_raw( YITH_WCBM_ASSETS_URL . '/images/14-bg.png' ); ?>) no-repeat 15% 100%">
		<div class="landing-container">
			<div class="col-2">
				<div class="section-title">
					<img src="<?php echo esc_url_raw( YITH_WCBM_ASSETS_URL . '/images/14-icon.png' ); ?>" alt="icon 14"/>
					<h2>100% WPML Compatible</h2>
				</div>
				<p>
					This plugin is now 100% WPML compatible. Every single badge can be translated into any language available in your website,
					preventing your website from displaying a <b>badge in a different language</b> from the one chosen by the user.<br>
					It’s a quick and easy feature you simply cannot do without, if you decided to make your website multilingual by using WPML.
				</p>
			</div>
			<div class="col-1">
				<img src="<?php echo esc_url_raw( YITH_WCBM_ASSETS_URL . '/images/14.png' ); ?>" alt="out of stock products"/>
			</div>
		</div>
	</div>
	<div class="section section-cta section-odd">
		<div class="landing-container">
			<div class="premium-cta">
				<p>
					Upgrade to the <span class="highlight">premium version</span>
					of <span class="highlight">YITH WooCommerce Badge Management</span> to benefit from all features!
				</p>
				<a href="<?php echo esc_url_raw( $this->get_premium_landing_uri() ); ?>" target="_blank" class="premium-cta-button button btn">
					<span class="highlight">UPGRADE</span>
					<span>to the premium version</span>
				</a>
			</div>
		</div>
	</div>
</div>
