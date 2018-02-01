(function ($) {
    $(function () {
        $('.seo-product-settings').insertAfter($('.s-product-form.main .field-group:first'));


        var seo_name = $('input[name="seo[name]"]');

        $.shop.changeListener($('input[name="product[name]"]'), function(name_input) {
            seo_name.attr('placeholder', name_input.val());
        });
    });
})(jQuery);