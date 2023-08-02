<?php
/**
 * Woo Extra Product Options - Field Editor
 *
 * @author    ThemeHiGH
 * @category  Admin
 */

if(!defined('ABSPATH')){ exit; }

if(!class_exists('THWEPOF_Admin_Settings_Pro')):
class THWEPOF_Admin_Settings_Pro extends THWEPOF_Admin_Settings {
	protected static $_instance = null;

	private $section_form = null;
	private $field_form = null;

	private $field_props = array();

	public function __construct() {
		parent::__construct('pro');
		$this->page_id = 'pro';

		// $this->section_form = new THWEPOF_Admin_Form_Section();
		// $this->field_form = new THWEPOF_Admin_Form_Field();
		// $this->field_props = $this->field_form->get_field_form_props();

		// add_filter( 'woocommerce_attribute_label', array($this, 'woo_attribute_label'), 10, 2 );
		
		// //add_filter('thwepof_load_products', array($this, 'load_products'));
		// add_filter('thwepof_load_products_cat', array($this, 'load_products_cat'));
	}

	public static function instance() {
		if(is_null(self::$_instance)){
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	
	public function render_page(){
		$this->render_tabs();
		$this->render_content();
	}

	private function render_content(){
		?>
		<div class="th-wrap-pro">
			<div class="th-nice-box">
			    <h2>Key Features of Extra Product Options for WooCommerce</h2>
			    <p>The premium version of <b>Extra Product Options for WooCommerce plugin</b> offers a wide variety of advanced features that will help you to create the finest product pages in your store.</p>
			    <ul class="feature-list star-list">
			        <li>27 extra product fields</li>
			        <li>Display fields conditionally</li>
			        <li>Display sections conditionally</li>
			        <li>Custom validations</li>
			        <li>Confirm validations</li>
			        <li>Popular Themes Compatibility</li>
			        <li>Compatibility with other plugins</li>
			        <li>WPML Compatibility</li>
			        <li>Easily Duplicate and Rearrange fields & Sections</li>
			        <li>Manage field display in cart page, checkout page and order details pages</li>
			        <li>Easily Customise, Disable or delete created fields and sections</li>
			        <li>Developer friendly with custom hooks</li>
			        <li>Create your own custom classes for styling the fields</li>
			        <li>Advanced Pricing Options</li>
			        <li>Option to add Extra price as Flat Fee</li>
			        <li>Option for displaying the Price Table in product page</li>
			        <li>Date & Time Picker Fields</li>
			        <li>Upload File Fields(Single file upload/ Mutliple file upload)</li>
			        <li>Image group & Color palette field type with multiselection property</li>
			    </ul>
			    <p>
			    	<a class="button big-button" target="_blank" href="https://www.themehigh.com/product/woocommerce-extra-product-options/?utm_source=free&utm_medium=premium_tab&utm_campaign=wepo_upgrade_link">Upgrade to Premium Version</a>
			    	<a class="button big-button" target="_blank" href="https://flydemos.com/wepo/?utm_source=free&utm_medium=banner&utm_campaign=wepo_trydemo" style="margin-left: 20px">Try Demo</a>
				</p>
			</div>
			<div class="th-flexbox">
			    <div class="th-flexbox-child th-nice-box">
			        <h2>Available Field types</h2>
			        <p>Following are the custom product field types available in the Extra Product Options plugin.</p>
			        <ul class="feature-list">
			            <li>Text</li>
			            <li>Hidden</li>
			            <li>Password</li>
			            <li>Telephone</li>
			            <li>Email</li>
			            <li>URL</li>
			            <li>Slider/Range</li>
			            <li>Number</li>
			            <li>Textarea</li>
			            <li>Select</li>
			            <li>Multi Select</li>
			            <li>Radio</li>
			            <li>Checkbox</li>
			            <li>Checkbox Group</li>
			            <li>Date picker</li>
			            <li>Date & Time Range Picker</li>
			            <li>Time picker</li>
			            <li>File Upload</li>
			            <li>Color picker</li>
			            <li>Color palette</li>
			            <li>Image group</li>
			            <li>Heading</li>
			            <li>Label</li>
			            <li>HTML</li>
			            <li>Separator</li>
			            <li>Switch</li>
			            <li>Product Group</li>
			        </ul>
			    </div>
			    <div class="th-flexbox-child th-nice-box">
			        <h2>Advanced Pricing Options</h2>
			        <p>Modify the existing product prices by choosing from 5 flexible pricing methods provided by Extra Product options plugin.</p>
			        <ul class="feature-list">
			            <li>
			            	Fixed Pricing:
			            	<p>A fixed amount will be added to the total price.</p>
			            </li>
			            <li>
			            	Custom Pricing:
			            	<p>A value entered by the user will be added to the total price. Use Case example: This option helps to receive donations.</p>
			            </li>
			            <li>
			            	Percentage of Product Pricing:
			            	<p>Percentage of product price is added to the total price</p>
			            </li>
			            <li>
			            	Dynamic Pricing:
			            	<p>You can set a price for ‘n’ number of units. This value will be added to the product price</p>
			            </li>
			            <li>
			            	Dynamic(Exclude base price):
			            	<p>Same as Dynamic pricing, but instead of adding the Extra price, it replaces the product price.</p>
			            </li>
			            <li>
			            	Character Count:
			            	<p>You can add an extra amount to the product price based on the number of characters the shopper provide.</p>
			            </li>
			            <li>
			            	Custom Formula:
			            	<p>An additional price can be charged to the product price based on the custom formula you set.</p>
			            </li> 
			        </ul>
			        <p>Note: You can use -ve price value (eg:-20) for applying the discount to product price.</p>
			    </div>
			</div>
			<div class="th-flexbox">
			    <div class="th-flexbox-child th-nice-box">
			        <h2>Display Rules for Fields and Sections</h2>
			        <p>You will be able to display fields and sections conditionally in your WooCommerce product page using the display rules feature.</p>
			        <p>Available set of conditions are:</p>
			        <ul class="feature-list">
			        	<li>Conditions based on products</li>
						<li>Conditions based on Categories</li>
						<li>Conditions based on Tags</li>
						<li>Conditions based on User Role</li>
						<li>Conditions based on Product variation</li>
						<li>Conditions based on Product Qty</li>
						<li>Conditions based on Other fields value</li>
			        </ul>
			    </div>
			    <div class="th-flexbox-child th-nice-box">
			        <h2>Advanced Styling for fields & Sections</h2>
			        <p>Extra Product Options for Woocommerce plugins lets you style your fields and sections in a number of ways. Let's have a look at it:</p>
			        <ul class="feature-list">
			        	<li>You can define title type as header tags, paragraph, span, division or label</li>
						<li>Change the colour of title and subtitle using an easy color picker</li>
						<li>You can set the title position to left of the field or above the field</li>
						<li>Inherit the store or theme styles using CSS classes</li>	            
			        </ul>
			    </div>
			</div>
		</div>
		<?php
	}

}
endif;