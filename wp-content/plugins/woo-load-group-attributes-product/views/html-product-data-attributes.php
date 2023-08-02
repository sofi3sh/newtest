<style type="text/css">
    .show_if_variable {
        display: block !important;
    }
</style>
<div id="product_attributes" class="panel wc-metaboxes-wrapper">

    <p class="toolbar">
        <a href="#" class="close_all"><?php _e('Close all', 'woocommerce'); ?></a> <a href="#"
                                                                                      class="expand_all"><?php _e('Expand all', 'woocommerce'); ?></a>
    </p>

    <div class="product_attributes wc-metaboxes">

        <?php

        if ($attributes) {

            $position = -1;
            $i = -1;

            foreach ($attributes as $key => $value) {

                $attribute_taxonomy_name = $value["slug"];
                $attribute_name = str_replace("pa_", "", $attribute_taxonomy_name);

                $i++;

                $tax = WOOLGAP()->get_attribute_by_slug($attribute_name);

                $check_term_id = true;
                $post_terms = isset($value['values']) ? $value['values'] : 0;
                $is_visible = isset($value['visible']) ? $value['visible'] : 0;


                if ($attribute_taxonomy_name != $attribute_name) { ?>

                    <?php if (!taxonomy_exists($attribute_taxonomy_name)) {
                        continue;
                    } ?>
                    <?php
                    $attribute_label = $tax->attribute_label ? $tax->attribute_label : $tax->attribute_name;
                    ?>
                    <div class="woocommerce_attribute wc-metabox taxonomy <?php echo esc_html($attribute_taxonomy_name); ?> closed"
                         rel="<?php echo $position; ?>">
                        <h3>
                            <button type="button"
                                    class="remove_row button"><?php _e('Remove', 'woocommerce'); ?></button>
                            <div class="handlediv" title="<?php _e('Click to toggle', 'woocommerce'); ?>"></div>
                            <strong class="attribute_name"><?php echo esc_html($attribute_label); ?></strong>
                        </h3>
                        <table class="woocommerce_attribute_data wc-metabox-content">
                            <tbody>
                            <tr>
                                <td class="attribute_name">
                                    <label><?php _e('Name', 'woocommerce'); ?>:</label>
                                    <strong><?php echo esc_html($attribute_label); ?></strong>

                                    <input type="hidden" name="attribute_names[<?php echo $i; ?>]"
                                           value="<?php echo esc_attr($attribute_taxonomy_name); ?>"/>
                                    <input type="hidden" name="attribute_position[<?php echo $i; ?>]"
                                           class="attribute_position" value="<?php echo esc_attr($position); ?>"/>
                                    <input type="hidden" name="attribute_is_taxonomy[<?php echo $i; ?>]" value="1"/>
                                </td>
                                <td rowspan="3">
                                    <label><?php _e('Value(s)', 'woocommerce'); ?>:</label>
                                    <label>
                                        <?php if ('select' == $tax->attribute_type) : ?>
                                        <select multiple="multiple"
                                                data-placeholder="<?php _e('Select terms', 'woocommerce'); ?>"
                                                class="multiselect attribute_values wc-enhanced-select"
                                                name="attribute_values[<?php echo $i; ?>][]">
                                            <?php
                                            $all_terms = get_terms($attribute_taxonomy_name, 'orderby=name&hide_empty=0');
                                            if ($all_terms) {
                                                foreach ($all_terms as $term) {
                                                    if ($check_term_id) {
                                                        $has_term = in_array($term->term_id, $post_terms) ? 1 : 0;
                                                    } else {
                                                        $has_term = in_array($term->slug, $post_terms) ? 1 : 0;
                                                    }
                                                    echo '
                                            <option value="' . esc_attr($term->term_id) . '"
                                            ' . selected($has_term, 1, false) . '>' . $term->name . '</option>';
                                                }
                                            }
                                            ?>
                                        </select>
                                    </label>

                                    <button class="button plus select_all_attributes"><?php _e('Select all', 'woocommerce'); ?></button>
                                    <button class="button minus select_no_attributes"><?php _e('Select none', 'woocommerce'); ?></button>

                                    <button class="button fr plus add_new_attribute"
                                            data-attribute="<?php echo esc_html($attribute_taxonomy_name); ?>"><?php _e('Add new', 'woocommerce'); ?></button>

                                    <?php elseif ('text' == $tax->attribute_type) : ?>
                                    <label>
                                        <input type="text" name="attribute_values[<?php echo $i; ?>]" value="<?php

                                        if ($post_terms) {
                                            $values = array();

                                            foreach ($post_terms as $term)
                                                $values[] = $term;
                                            echo esc_attr(implode(' ' . WC_DELIMITER . ' ', $values));
                                        }

                                        ?>" placeholder="<?php _e('Pipe (|) separate terms', 'woocommerce'); ?>"/>
                                        <?php endif; ?>
                                    </label>

                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label><input type="checkbox" class="checkbox" <?php

                                        checked($tax->attribute_public, true);


                                        ?> name="attribute_visibility[<?php echo $i; ?>]"
                                                  value="1"/> <?php _e('Visible on the product page', 'woocommerce'); ?>
                                    </label>
                                </td>
                            </tr>

                            </tbody>
                        </table>
                    </div>

                <?php } else { ?>

                    <div class="woocommerce_attribute wc-metabox " rel="<?php echo $position; ?>">
                        <h3>
                            <button type="button"
                                    class="remove_row button"><?php _e('Remove', 'woocommerce'); ?></button>
                            <div class="handlediv" title="<?php _e('Click to toggle', 'woocommerce'); ?>"></div>
                            <strong class="attribute_name"><?php echo esc_html($attribute_name); ?></strong>
                        </h3>
                        <table class="woocommerce_attribute_data wc-metabox-content">
                            <tbody>
                            <tr>
                                <td class="attribute_name">
                                    <label><?php _e('Name', 'woocommerce'); ?>:</label>
                                    <label>
                                        <input type="text" class="attribute_name"
                                               name="attribute_names[<?php echo $i; ?>]"
                                               value="<?php echo esc_attr($attribute_name); ?>"/>
                                    </label>
                                    <input type="hidden" name="attribute_position[<?php echo $i; ?>]"
                                           class="attribute_position" value="<?php echo esc_attr($position); ?>"/>
                                    <input type="hidden" name="attribute_is_taxonomy[<?php echo $i; ?>]" value="0"/>
                                </td>
                                <td rowspan="3">
                                    <label><?php _e('Value(s)', 'woocommerce'); ?>:</label>
                                    <label>
<textarea name="attribute_values[<?php echo $i; ?>]" cols="5" rows="5"
          placeholder="<?php _e('Enter some text, or some attributes by pipe (|) separating values.', 'woocommerce'); ?>"><?php echo esc_textarea(implode('|', (array)$post_terms)); ?></textarea>
                                    </label>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label><input type="checkbox" class="checkbox" <?php checked($is_visible, 1); ?>
                                                  name="attribute_visibility[<?php echo $i; ?>]"
                                                  value="1"/> <?php _e('Visible on the product page', 'woocommerce'); ?>
                                    </label>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                <?php } ?>
            <?php } ?>
        <?php } ?>

    </div>

    <p class="toolbar">
        <button type="button" class="button button-primary add_attribute"><?php _e('Add', 'woocommerce'); ?></button>
        <label>
            <select name="attribute_taxonomy" class="attribute_taxonomy">
                <option value=""><?php _e('Custom product attribute', 'woocommerce'); ?></option>
                <?php
                if ($attribute_taxonomies) {

                    foreach ($attribute_taxonomies as $tax) {
                        $attribute_taxonomy_name = wc_attribute_taxonomy_name($tax->attribute_name);

                        $label = $tax->attribute_label ? $tax->attribute_label : $tax->attribute_name;
                        echo '<option value="' . esc_attr($attribute_taxonomy_name) . '">' . esc_html($label) . '</option>';
                    }
                }
                ?>
            </select>
        </label>
    </p>

</div>