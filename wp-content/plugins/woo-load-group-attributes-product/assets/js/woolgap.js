jQuery(function ($) {

    $(document.body).on('click', 'button#btn_load_attributes', function () {

        const $wrapper_model = $(this).closest("section");
        let size = $('.product_attributes .woocommerce_attribute').length;
        const attributes_size = $("input[name='load_attribute_names[]']:checked").length;
        const attributes = $('#woocommerce-product-data .product_attributes');
        let si = 1;
        const attributes_content_id = $('#attributes_content').val();

        $wrapper_model.block({message: null, overlayCSS: {background: '#fff', opacity: 0.6}});

        $("input[name='load_attribute_names[]']:checked").each(function () {

            const slug = $(this).val();
            const at_size = $('.woocommerce_attribute.' + slug).length;
            if (!at_size) {

                const data = {
                    action: 'woolgap_add_attribute',
                    taxonomy: slug,
                    i: size++,
                    df: attributes_content_id,
                    security: woolgap_admin_ajax.add_attribute_nonce
                };

                $.post(woocommerce_admin_meta_boxes.ajax_url, data, function (response) {
                    attributes.append(response);
 
                    $(document.body).trigger('wc-enhanced-select-init');

                    attribute_row_indexes();

                    $(document.body).trigger('woocommerce_added_attribute');

                    $('#load_status').html(si + ' / ' + attributes_size);

                    if (si++ >= attributes_size) {

                        $wrapper_model.unblock();
                        $(".modal-close").trigger("click");
                    }
                });

            } else {

                $('.woocommerce_attribute.' + slug).show();

                $('#load_status').html(si + ' / ' + attributes_size);

                if (si++ >= attributes_size) {
                    $wrapper_model.unblock();
                    $(".modal-close").trigger("click");
                }
            }
        });
    });

    $(document.body).on('click', '.load_attributes_content', function (event) {

        event.preventDefault();

        const attributes_content_id = $('#attributes_content').val();

        if ("" != attributes_content_id) {

            const $wrapper = $(this).closest('#product_attributes');

            $wrapper.block({message: null, overlayCSS: {background: '#fff', opacity: 0.6}});

            const data = {action: 'get_load_attribute_content', attributes_content_id: attributes_content_id};
            $.post(woocommerce_admin.ajax_url, data, function (response) {

                $(this).WCBackboneModal({
                    template: 'woolgap-modal-load-attributes',
                    variable: response.data
                });

                $wrapper.unblock();
            });
        } else {
            alert("Select Item");
        }
    });

    $(document.body).on('click', 'button.add_group_attribute', function (event) {

        event.preventDefault();

        let html = ' <form action="" method="post"> ';
        html += ' <table id="form_load_attributes"> ';
        html += ' <tbody> <tr> <input type="checkbox" id="checkAll" checked="checked"> <label for="checkAll"> <b> Check All </b> </label> <td> </td> </tr> <tr> <td> ';

        const attribute_taxonomy = $('.toolbar .attribute_taxonomy');
        attribute_taxonomy.find('option[value*="pa_"]').each(function () {

            const _option = $(this);

            if (_option.is(':disabled')) {

                html += '<span><input type="checkbox" class="checkbox" checked="checked" disabled> <lable for="lc_' + _option.val() + '">' + _option.html() + '</label></span>';
            } else {

                html += '<span><input type="checkbox" id="lc_' + _option.val() + '" class="checkbox" checked="checked" name="load_attribute_names[]" value="' + _option.val() + '"> <lable for="lc_' + _option.val() + '">' + _option.html() + '</label></span>';
            }

        });

        html += ' </td> </tr> </tbody>';
        html += ' </table>';

        const $wrapper = $(this).closest('#product_attributes');
        $wrapper.block({message: null, overlayCSS: {background: '#fff', opacity: 0.6}});

        const data = {title: "Load Attributes", html: html};

        $(this).WCBackboneModal({
            template: 'woolgap-modal-load-attributes',
            variable: data
        });

        $wrapper.unblock();

    });

    $('.attribute_taxonomy').on('focus', function () {
        $(this).find('option').each(function () {
            const _option = $(this);
            if (_option.val() !== '') {
                if ($('.woocommerce_attribute.' + _option.val()).is(':visible')) {
                    _option.attr("disabled", "disabled");
                } else {
                    _option.removeAttr("disabled");
                }
            }
        });
    });


    $('#publish').click(function () {

        $('input.woolgap_attribute_visible').remove();

        $('.product_attributes .woocommerce_attribute').each(function (index, item) {

            if ($(item).is(':visible')) {

                const name = $(item).find('input[name^="attribute_names"]').attr('name');
                const res = name.match(/[0-9]+/);

                if (res != null) {
                    $('form#post').prepend('<input type="hidden" name="woolgap_attribute_visible[' + res[0] + ']" class="woolgap_attribute_visible" value="1" />');
                }
            }
        });
    });

    function attribute_row_indexes() {
        $('.product_attributes .woocommerce_attribute').each(function (index, el) {

            $('.attribute_position', el).val(parseInt($(el).index('.product_attributes .woocommerce_attribute'), 10));
        });
    }

    $(document.body).on('click', 'input#checkAll', function () {

        $("input[name='load_attribute_names[]']:checkbox").prop('checked', $(this).prop("checked"));
    });

});
