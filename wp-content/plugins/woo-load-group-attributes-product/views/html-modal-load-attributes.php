<script type="text/template" id="tmpl-woolgap-modal-load-attributes">
	<div class="wc-backbone-modal">
		<div class="wc-backbone-modal-content">
			<section class="wc-backbone-modal-main" role="main">
				<header class="wc-backbone-modal-header">
					<h1><?php _e( 'Add Attributes', 'woolgap' ); ?>  |  {{{ data.title }}}</h1>
					<button class="modal-close modal-close-link dashicons dashicons-no-alt">
						<span class="screen-reader-text">Close modal panel</span>
					</button>
				</header>
				<article>
					{{{ data.html }}}
				</article>
				<footer>
					<div class="inner">
						<div style="display: inline-block;float: right;">							
							<span id="load_status">
						</div>

						<button id="btn_load_attributes" class="button button-primary button-large"><?php _e( 'Add', 'woolgap' ); ?></button>
					</div>
				</footer>
			</section>
        </div>
    </div>
	<div class="wc-backbone-modal-backdrop modal-close"></div>
</script>