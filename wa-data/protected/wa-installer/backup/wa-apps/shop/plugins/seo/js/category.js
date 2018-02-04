(function ($) {
    $(function () {
        var d = $('#s-product-list-settings-dialog');

        var seo_name = $('input[name="seo[name]"]', d);

        $.shop.changeListener($('input[name="name"]'), function(name_input) {
            seo_name.attr('placeholder', name_input.val());
        });

        var f_description = $('.field.description:first').closest('.field-group');
        $('#category-additional-description').insertAfter(f_description);
    });
})(jQuery);