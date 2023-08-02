<?php
/**
 * Premium tab
 *
 * @author  Corrado Porzio <corradoporzio@gmail.com>
 * @package YITH\ProductAddOns
 * @version 2.0.0
 */

?>
<style>
	.landing{
		margin-right: 15px;
		border: 1px solid #d8d8d8;
		border-top: 0;
	}
	.section{
		font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol";
		background: #fafafa;
	}
	.section h1{
		text-align: center;
		text-transform: uppercase;
		color: #445674;
		font-size: 35px;
		font-weight: 700;
		line-height: normal;
		display: inline-block;
		width: 100%;
		margin: 50px 0 0;
	}
	.section .section-title h2{
		vertical-align: middle;
		padding: 0;
		line-height: normal;
		font-size: 24px;
		font-weight: 700;
		color: #445674;
		text-transform: uppercase;
		background: none;
		border: none;
		text-align: center;
	}
	.section p{
		margin: 15px 0;
		font-size: 19px;
		line-height: 32px;
		font-weight: 300;
		text-align: center;
	}
	.section ul li{
		margin-bottom: 4px;
	}
	.section.section-cta{
		background: #fff;
	}
	.cta-container,
	.landing-container{
		display: flex;
		max-width: 1200px;
		margin-left: auto;
		margin-right: auto;
		padding: 30px 0;
		align-items: center;
	}
	.landing-container-wide{
		flex-direction: column;
	}
	.cta-container{
		display: block;
		max-width: 860px;
	}
	.landing-container:after{
		display: block;
		clear: both;
		content: '';
	}
	.landing-container .col-1,
	.landing-container .col-2{
		float: left;
		box-sizing: border-box;
		padding: 0 15px;
	}
	.landing-container .col-1{
		width: 58.33333333%;
	}
	.landing-container .col-2{
		width: 41.66666667%;
	}
	.landing-container .col-1 img,
	.landing-container .col-2 img,
	.landing-container .col-wide img{
		max-width: 100%;
	}
	.premium-cta{
		color: #4b4b4b;
		border-radius: 10px;
		padding: 30px 25px;
		display: flex;
		align-items: center;
		justify-content: space-between;
		width: 100%;
		box-sizing: border-box;
	}
	.premium-cta:after{
		content: '';
		display: block;
		clear: both;
	}
	.premium-cta p{
		margin: 10px 0;
		line-height: 1.5em;
		display: inline-block;
		text-align: left;
	}
	.premium-cta a.button{
		border-radius: 25px;
		float: right;
		background: #e09004;
		box-shadow: none;
		outline: none;
		color: #fff;
		position: relative;
		padding: 10px 50px 8px;
		text-align: center;
		text-transform: uppercase;
		font-weight: 600;
		font-size: 20px;
		line-height: normal;
		border: none;
	}
	.premium-cta a.button:hover,
	.premium-cta a.button:active,
	.wp-core-ui .yith-plugin-ui .premium-cta a.button:focus{
		color: #fff;
		background: #d28704;
		box-shadow: none;
		outline: none;
	}
	.premium-cta .highlight{
		text-transform: uppercase;
		background: none;
		font-weight: 500;
	}

	@media (max-width: 991px){
		.landing-container{
			display: block;
			padding: 50px 0 30px;
		}

		.landing-container .col-1,
		.landing-container .col-2{
			float: none;
			width: 100%;
		}

		.premium-cta{
			display: block;
			text-align: center;
		}

		.premium-cta p{
			text-align: center;
			display: block;
			margin-bottom: 30px;
		}
		.premium-cta a.button{
			float: none;
			display: inline-block;
		}
	}
</style>
<div class="landing">

	<div class="section section-cta section-odd">
		<div class="cta-container">
			<div class="premium-cta">
				<p><?php echo sprintf( esc_html__( 'Upgrade to the %1$spremium version%2$s%3$sof %1$sYITH WooCommerce Product Add-ons & Extra Options%2$s to benefit from all features!', 'yith-woocommerce-product-add-ons' ), '<span class="highlight">', '</span>', '<br/>' ); // phpcs:ignore WordPress.WP.I18n.MissingTranslatorsComment ?></p>
				<a href="<?php echo esc_attr( $this->get_premium_landing_uri() ); ?>" target="_blank" class="premium-cta-button button btn"><?php echo esc_html__( 'Upgrade', 'yith-woocommerce-product-add-ons' ); ?></a>
			</div>
		</div>
	</div>

	<div class="section section-even clear" style="background: #fff;">
		<h1>Premium Features</h1>
		<div class="landing-container">
			<div class="col-1">
				<img src="<?php echo esc_attr( YITH_WAPO_ASSETS_URL ); ?>/img/premium/001.jpg" />
			</div>
			<div class="col-2">
				<div class="section-title">
					<h2><?php echo esc_html__( 'The ultimate tool to add options and extra services (free or paid) to your products and offer them to your users', 'yith-woocommerce-product-add-ons' ); ?></h2>
				</div>
				<p>
					<?php
					echo esc_html__(
						'WooCommerce allows selling any type of product and lets users choose simple variations, like size and color.
                    Yet, this is not enough. If you sell your products online, you most likely need more advanced
                    options. For example, if you sell rings or jewelry, you might need to let your customers add a
                    custom text to be engraved in a piece of jewelry or choose carats for a gemstone, the size, the
                    color for gold.
                    If you sell T-shirts, cups, and other custom items, you certainly need a field to let your customers
                    upload files, like their photos, during the checkout process. Or if you run an e-commerce site for
                    tech products, you might want to offer warranty and assistance services for an extra cost or
                    allow users to select a large number of options like RAM, processor, screen size, weight, etc.
                    Some options might have a cost higher than others or require a dependency and show only
                    after a specific selection has been made by the user. All these scenarios need a versatile and
                    powerful tool to help you add an unlimited number of options, of any kind, to a product page.
                    With <b>YITH WooCommerce Product Add-ons</b>, you can create several blocks of options to
                    insert in your products: after creating the block, you can add the options you need by choosing
                    elements through an amazing library (input fields, text areas, select dropdowns, checkboxes,
                    images, labels, radio buttons, upload fields, date pickers, etc.). For each option, you can set an
                    additional cost, which will be added to the standard product price, and, <b>in a few minutes, you\'ll
                    be ready to sell complex or customizable products and offer any kind of optional service</b>
                    to your customers.',
						'yith-woocommerce-product-add-ons'
					);
					?>
				</p>
			</div>
		</div>
	</div>

	<div class="section section-odd clear">
		<div class="landing-container">
			<div class="col-2">
				<div class="section-title">
					<h2><?php echo esc_html__( 'Create unlimited blocks of options to show on specific products or product categories', 'yith-woocommerce-product-add-ons' ); ?></h2>
				</div>
				<p>
					<?php
					echo esc_html__(
						'Create a block of options for all products in a certain category, another block for a specific
                    product, the third block for another different product, and so on. The plugin allows creating and
                    configuring an unlimited number of blocks, each of them with an unlimited number of options.
                    The key idea behind it is, as usual, flexibility.',
						'yith-woocommerce-product-add-ons'
					);
					?>
				</p>
			</div>
			<div class="col-1">
				<img src="<?php echo esc_attr( YITH_WAPO_ASSETS_URL ); ?>/img/premium/002.jpg" />
			</div>
		</div>
	</div>

	<div class="section section-even clear">
		<div class="landing-container">
			<div class="col-1">
				<img src="<?php echo esc_attr( YITH_WAPO_ASSETS_URL ); ?>/img/premium/003.jpg" />
			</div>
			<div class="col-2">
				<div class="section-title">
					<h2><?php echo esc_html__( 'Populate every block by choosing one or more options among the ones available in the library', 'yith-woocommerce-product-add-ons' ); ?></h2>
				</div>
				<p>
					<?php
					echo esc_html__(
						'Pick the options you want to add to the block from the options library provided. To present your
                    product options, you can use checkboxes, select dropdowns, file upload fields, input fields, color
                    swatches, textual labels or labels with images, date pickers, and much more.',
						'yith-woocommerce-product-add-ons'
					);
					?>
				</p>
			</div>
		</div>
	</div>

	<div class="section section-odd clear">
		<div class="landing-container">
			<div class="col-2">
				<div class="section-title">
					<h2><?php echo esc_html__( 'Promote products as additional options', 'yith-woocommerce-product-add-ons' ); ?></h2>
				</div>
				<p>
					<?php
					echo esc_html__(
						'Since version 2.0, you can select a product as an option: this way, on the product detail page,
                    you can suggest one or more products or related services, side options, and push the user to
                    add them all in bulk to the cart in just one click.',
						'yith-woocommerce-product-add-ons'
					);
					?>
				</p>
			</div>
			<div class="col-1">
				<img src="<?php echo esc_attr( YITH_WAPO_ASSETS_URL ); ?>/img/premium/004.jpg" />
			</div>
		</div>
	</div>

	<div class="section section-even clear">
		<div class="landing-container">
			<div class="col-1">
				<img src="<?php echo esc_attr( YITH_WAPO_ASSETS_URL ); ?>/img/premium/005.jpg" />
			</div>
			<div class="col-2">
				<div class="section-title">
					<h2><?php echo esc_html__( 'Customize every option to show it the way you like it', 'yith-woocommerce-product-add-ons' ); ?></h2>
				</div>
				<p>
					<?php
					echo esc_html__(
						'Once the option has been added to the block, you can customize the way it appears on the
                    product page: set which title and/or a description to show; add a tooltip and image to easily
                    identify the option (you can also choose to whether this image will replace the default product
                    image when it is selected by the user); you can also set the option as “required” and decide
                    whether to show it as selected by default on the page.',
						'yith-woocommerce-product-add-ons'
					);
					?>
				</p>
			</div>
		</div>
	</div>

	<div class="section section-odd clear">
		<div class="landing-container">
			<div class="col-2">
				<div class="section-title">
					<h2><?php echo esc_html__( 'Set the price (regular and on-sale one) for every option or set a percentage value that will add or detract from the final product price', 'yith-woocommerce-product-add-ons' ); ?></h2>
				</div>
				<p>
					<?php
					echo esc_html__(
						'For every single option you can choose whether it can be selected by the user for free, if it will
                    add an extra cost to the base price (a fixed or percentage surcharge on the product price) or if,
                    on the other hand, selecting it will apply a discount on the product price. Additionally, in the
                    block options, you can choose whether to offer the first options chosen by the user for free (e.g.
                    you sell a pizza and the first three ingredients selected by the user are included in the pizza
                    price, but from the forth one on, every additional topping will come at an extra cost).',
						'yith-woocommerce-product-add-ons'
					);
					?>
				</p>
			</div>
			<div class="col-1">
				<img src="<?php echo esc_attr( YITH_WAPO_ASSETS_URL ); ?>/img/premium/006.jpg" />
			</div>
		</div>
	</div>

	<div class="section section-even clear">
		<div class="landing-container">
			<div class="col-1">
				<img src="<?php echo esc_attr( YITH_WAPO_ASSETS_URL ); ?>/img/premium/007.jpg" />
			</div>
			<div class="col-2">
				<div class="section-title">
					<h2><?php echo esc_html__( 'Choose whether to show the options in a vertical line or in a grid', 'yith-woocommerce-product-add-ons' ); ?></h2>
				</div>
				<p>
					<?php
					echo esc_html__(
						'Choose whether to show the options in a vertical layout, so one below the other, or show them
                    in a horizontal grid. In the latter case, you can set the number of columns into which the options
                    will be divided.',
						'yith-woocommerce-product-add-ons'
					);
					?>
				</p>
			</div>
		</div>
	</div>

	<div class="section section-odd clear">
		<div class="landing-container">
			<div class="col-2">
				<div class="section-title">
					<h2><?php echo esc_html__( 'Create dependencies rules (conditional logic) to show or hide the options based on the user’s selection', 'yith-woocommerce-product-add-ons' ); ?></h2>
				</div>
				<p>
					<?php
					echo esc_html__(
						'The dependency rules make the plugin even more powerful and show or hide specific options
                    based on what the user selects. For example, you can show the express shipping option only to
                    users who select the region where this delivery option is available; or show the file upload field
                    only to users who have checked the option to customize the product.',
						'yith-woocommerce-product-add-ons'
					);
					?>
				</p>
			</div>
			<div class="col-1">
				<img src="<?php echo esc_attr( YITH_WAPO_ASSETS_URL ); ?>/img/premium/008.jpg" />
			</div>
		</div>
	</div>

	<div class="section section-even clear">
		<div class="landing-container">
			<div class="col-1">
				<img src="<?php echo esc_attr( YITH_WAPO_ASSETS_URL ); ?>/img/premium/009.jpg" />
			</div>
			<div class="col-2">
				<div class="section-title">
					<h2><?php echo esc_html__( 'Choose whether the user can only pick one of the options available or select multiple ones', 'yith-woocommerce-product-add-ons' ); ?></h2>
				</div>
				<p>
					<?php
					echo esc_html__(
						'For every block of options, you can choose whether the user can make multiple selections (e.g.,
                    select more colors from the color swatches) or if only one of the available options can be
                    chosen.',
						'yith-woocommerce-product-add-ons'
					);
					?>
				</p>
			</div>
		</div>
	</div>

	<div class="section section-odd clear">
		<div class="landing-container">
			<div class="col-2">
				<div class="section-title">
					<h2><?php echo esc_html__( 'Set the options as mandatory and define the minimum and maximum number of options that the user can select and add to the cart', 'yith-woocommerce-product-add-ons' ); ?></h2>
				</div>
				<p>
					<?php
					echo esc_html__(
						'You can set specific quantity rules for the number of options the user has to choose. This is both
                    possible for a minimum number of selected options and the maximum number of options. For
                    example, customizing a garden shed requires at least two options but no more than six before
                    the product is added to the cart.',
						'yith-woocommerce-product-add-ons'
					);
					?>
				</p>
			</div>
			<div class="col-1">
				<img src="<?php echo esc_attr( YITH_WAPO_ASSETS_URL ); ?>/img/premium/010.jpg" />
			</div>
		</div>
	</div>

	<div class="section section-even clear">
		<div class="landing-container">
			<div class="col-1">
				<img src="<?php echo esc_attr( YITH_WAPO_ASSETS_URL ); ?>/img/premium/011.jpg" />
			</div>
			<div class="col-2">
				<div class="section-title">
					<h2><?php echo esc_html__( 'Choose whether the options will be visible to all users, only to registered ones, or to specific user roles only', 'yith-woocommerce-product-add-ons' ); ?></h2>
				</div>
				<p>
					<?php
					echo esc_html__(
						'For every block of options, you can set some visibility rules and decide whether the options will
                    be visible to all users (also guests) or if they will be available only for registered users or specific
                    user roles only.',
						'yith-woocommerce-product-add-ons'
					);
					?>
				</p>
			</div>
		</div>
	</div>

	<div class="section section-odd clear">
		<div class="landing-container">
			<div class="col-2">
				<div class="section-title">
					<h2><?php echo esc_html__( 'Choose where on the product page the options will be displayed', 'yith-woocommerce-product-add-ons' ); ?></h2>
				</div>
				<p>
					<?php
					echo esc_html__(
						'Choose where on the page the block with options will show up (before or after the “Add to cart”
                    button), and customize the “Select options” button in WooCommerce loops (i.e., the product
                    shortcodes, the Shop page, the category pages, and so on.)',
						'yith-woocommerce-product-add-ons'
					);
					?>
				</p>
			</div>
			<div class="col-1">
				<img src="<?php echo esc_attr( YITH_WAPO_ASSETS_URL ); ?>/img/premium/012.jpg" />
			</div>
		</div>
	</div>

	<div class="section section-even clear">
		<div class="landing-container">
			<div class="col-1">
				<img src="<?php echo esc_attr( YITH_WAPO_ASSETS_URL ); ?>/img/premium/013.jpg" />
			</div>
			<div class="col-2">
				<div class="section-title">
					<h2><?php echo esc_html__( 'Hide the “Add to cart” button until the user selects all options', 'yith-woocommerce-product-add-ons' ); ?></h2>
				</div>
				<p>
					<?php
					echo esc_html__(
						'Enhance the options by hiding the “Add to cart” button until the user has selected every required
                    option. Only after the user has made a selection for all the available options, the button will
                    show up, and the user is able to add the product to the cart.',
						'yith-woocommerce-product-add-ons'
					);
					?>
				</p>
			</div>
		</div>
	</div>

	<div class="section section-odd clear">
		<div class="landing-container">
			<div class="col-2">
				<div class="section-title">
					<h2><?php echo esc_html__( 'Choose whether to show the total price, including options on the product page', 'yith-woocommerce-product-add-ons' ); ?></h2>
				</div>
				<p>
					<?php
					echo esc_html__(
						'Do you want to offer transparency about product prices and prevent unwanted higher prices in
                    the cart for your users? You can, by simply enabling the option that allows showing the total
                    product price inclusive of all the options selected by the user on the product page.',
						'yith-woocommerce-product-add-ons'
					);
					?>
				</p>
			</div>
			<div class="col-1">
				<img src="<?php echo esc_attr( YITH_WAPO_ASSETS_URL ); ?>/img/premium/014.jpg" />
			</div>
		</div>
	</div>

	<div class="section section-even clear">
		<div class="landing-container">
			<div class="col-1">
				<img src="<?php echo esc_attr( YITH_WAPO_ASSETS_URL ); ?>/img/premium/015.jpg" />
			</div>
			<div class="col-2">
				<div class="section-title">
					<h2><?php echo esc_html__( 'Configure the permissions for file upload fields (accepted file formats, maximum file size, etc.) and enable the option to get the uploaded files as order attachments', 'yith-woocommerce-product-add-ons' ); ?></h2>
				</div>
				<p>
					<?php
					echo esc_html__(
						'If you choose the Upload fields to let your user upload files to the product (like documents,
                    images, graphics, etc.), you can take advantage of the built-in options to set permissions about
                    which file formats are allowed and the maximum size. Also, for better and flawless
                    management, enable the option to send the uploaded files as attachments of the Order
                    confirmation emails and choose the folder where all the files will be automatically stored.',
						'yith-woocommerce-product-add-ons'
					);
					?>
				</p>
			</div>
		</div>
	</div>

	<div class="section section-odd clear">
		<div class="landing-container">
			<div class="col-2">
				<div class="section-title">
					<h2><?php echo esc_html__( 'Choose whether to show the selected option in the Cart and in the order-related emails', 'yith-woocommerce-product-add-ons' ); ?></h2>
				</div>
				<p>
					<?php
					echo esc_html__(
						'Choose whether to show only the basic product information or also all the options selected by
                    the user. Similarly, you can show or hide the selected options on all the order-related emails.',
						'yith-woocommerce-product-add-ons'
					);
					?>
				</p>
			</div>
			<div class="col-1">
				<img src="<?php echo esc_attr( YITH_WAPO_ASSETS_URL ); ?>/img/premium/016.jpg" />
			</div>
		</div>
	</div>

	<div class="section section-even clear">
		<div class="landing-container">
			<div class="col-1">
				<img src="<?php echo esc_attr( YITH_WAPO_ASSETS_URL ); ?>/img/premium/017.jpg" />
			</div>
			<div class="col-2">
				<div class="section-title">
					<h2><?php echo esc_html__( 'Customize the style and colors of the options block', 'yith-woocommerce-product-add-ons' ); ?></h2>
				</div>
				<p>
					<?php
					echo esc_html__(
						'In the plugin you will find many options to customize the option blocks. You can use either the
                    theme style (for checkboxes, radio buttons, select dropdowns etc) or the plugin style; you can
                    edit colors (block background color, highlight color for the selected option etc), show tooltips and
                    set their color and position, set whether to show the options in a toggle button (open or closed
                    by default), and many more styling options.',
						'yith-woocommerce-product-add-ons'
					);
					?>
				</p>
			</div>
		</div>
	</div>

	<div class="section section-cta section-odd">
		<div class="cta-container">
			<div class="premium-cta">
				<p><?php echo sprintf( esc_html__( 'Upgrade to the %1$spremium version%2$s%3$sof %1$sYITH WooCommerce Product Add-ons & Extra Options%2$s to benefit from all features!', 'yith-woocommerce-product-add-ons' ), '<span class="highlight">', '</span>', '<br/>' ); // phpcs:ignore WordPress.WP.I18n.MissingTranslatorsComment ?></p>
				<a href="<?php echo esc_attr( $this->get_premium_landing_uri() ); ?>" target="_blank" class="premium-cta-button button btn"><?php echo esc_html__( 'Upgrade', 'yith-woocommerce-product-add-ons' ); ?></a>
			</div>
		</div>
	</div>

</div>
