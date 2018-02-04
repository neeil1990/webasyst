(function ($) {
    var seoSettings = {
        storefronts_diff: [],
        openTransfer: function(storefront) {
            var html = $('.transfer-storefront-template').contents().clone();
            html.find('.transfer-storefront__storefront').text(storefront);
            html.find('.transfer-storefront__input').val(storefront);

            var controls = $('.transfer-controls-template').contents().clone();
            controls.find('.transfer-storefront-controls__delete-storefront').on('click', function () {
                seoSettings.deleteStorefront(this);
            });
            controls.find('.transfer-storefront-controls__do-transfer-storefront').on('click', function () {
                seoSettings.transferStorefront(this);
            });

            html.waDialog({
                'width': '600px',
                'height': '115px',
                'buttons': controls,
                'onSubmit': function () {
                    return false;
                }
            });
        },
        openNextTransfer: function () {
            if (seoSettings.storefronts_diff.length > 0) {
                seoSettings.openTransfer(seoSettings.storefronts_diff.shift());
            } else {
                window.location.reload();
            }
        },
        deleteStorefront: function(button) {
            var form = $(button).parents('form');
            var dialog = form.parents('.dialog-window');
            var data = form.serialize();
            data = data + '&transfer=delete';

            $.post('?plugin=seo&module=settings', data, function () {
                dialog.trigger('close');
                seoSettings.openNextTransfer();
            });
            return false;
        },
        transferStorefront: function(button) {
            var form = $(button).parents('form');
            var dialog = form.parents('.dialog-window');
            var data = form.serialize();
            data = data + '&transfer=transfer';

            $.post('?plugin=seo&module=settings', data, function () {
                dialog.trigger('close');
                seoSettings.openNextTransfer();
            });
            return false;
        },
        openHelp: function ()
        {
            $('.help-block-template').contents().clone().waDialog({
                'width': '600px',
                'height': '500px',
                'buttons': $('.help-controls-template').contents().clone(),
                onSubmit: function (d)
                {
                    d.trigger('close');
                    return false;
                }
            });
        },
        addNewField: function ()
        {
            var html = $('.new-field-template').contents().clone();
            $('.seo-settings__settings-fields').append(html);
            seoSettings.initDeleteField(html);
        },
        removeField: function (field)
        {
            field.remove();
        },
        initDeleteField: function (context)
        {
            context.find('.additional-fields__remove-field').on('click', function () {
                //if (confirm('Вы действительно хотите удалить это поле?'))
                //{
                var field = $(this).closest('.field-box');
                seoSettings.removeField(field);
                //}
            });
        }
    };

    $(function () {
        $('.seo-settings__help-button').on('click', function () {
            seoSettings.openHelp();
        });

        $('.additional-fields__add-field').on('click', function () {
            seoSettings.addNewField();
        });

        seoSettings.initDeleteField($('html'));

        var $storefronts_diff = $('.seo-settings__storefronts-diff');

        if ($storefronts_diff.length)
        {
            var json = JSON.parse($storefronts_diff.val());

            for (var key in json)
                if (json.hasOwnProperty(key))
                {
                    seoSettings.storefronts_diff.push(json[key]);
                }

            if (seoSettings.storefronts_diff.length > 0)
            {
                seoSettings.openNextTransfer();
            }
        }

        $('#seo_settings_form').on('ajax.success', function (e, html_response) {
            var _this = $(this);
            var fields1 = _this.find('.seo-settings__settings-fields').html();
            var fields2 = $('.seo-settings__settings-fields', html_response).html();

            if (fields1 != fields2)
            {
                window.location.reload();
            }

            return false;
        });
    });
})(jQuery);