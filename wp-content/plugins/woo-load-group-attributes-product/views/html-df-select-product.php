<p class="toolbar">
    <button type="button" class="button button-primary load_attributes_content" style="float: right;margin: 0 0 0 6px;"><?php _e('Load', 'woolgap'); ?></button>
    <label for="attributes_content"></label>
	<select id="attributes_content" name="attributes_content" style="float: right;margin: 0 0 0 6px;">
        <option value=""><?php _e('Default Attribute', 'woolgap'); ?></option>
        <?php
        foreach ($myposts as $post) {
            setup_postdata($post);
		?>

            <option value="<?php echo $post->ID; ?>"> <?php echo $post->post_title; ?> </option>

            <?php
        }
        wp_reset_postdata();
        ?>
    </select>
</p>
<script>
	$( ".button.add_attribute" ).after( '<button type="button" class="button add_group_attribute" style="margin: 0 10px;"><?php _e('Add Group', 'woocommerce'); ?></button>' );
</script>