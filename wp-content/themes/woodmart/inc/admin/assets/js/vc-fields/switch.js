(function ($) {

    var $panel = $('#vc_ui-panel-edit-element');

    $panel.on('vcPanel.shown', function () {

        $('.xts-switcher-btn').on('click', function () {
            var $this = $(this);
            var value = '';

            if ($this.hasClass('xts-active')) {
                value = $this.data('off');
                $this.removeClass('xts-active');
            } else {
                value = $this.data('on');
                $this.addClass('xts-active');
            }

            $this.find('.switch-field-value').val(value).trigger('change');
        });
    });

})(jQuery);
