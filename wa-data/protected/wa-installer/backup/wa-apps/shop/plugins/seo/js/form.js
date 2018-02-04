(function ($) {
	function initForm(context)
	{
		if (typeof CodeMirror != 'undefined')
		{
			context.find(".text-area_syntax-highlight_html").each(function () {
				var _this = $(this);

				var cm = CodeMirror.fromTextArea(this, {
					mode: "text/html",
					tabMode: "indent",
					height: "dynamic",
					lineWrapping: true
				});

				_this.on('syntax-highlight.reset', function () {
					cm.setValue(_this.val());
				});

				_this.on('syntax-highlight.refresh', function () {
					cm.refresh();
				});
			});

			context.find(".text-area_syntax-highlight_css").each(function () {
				var _this = $(this);

				var cm = CodeMirror.fromTextArea(this, {
					mode: "text/css",
					tabMode: "indent",
					height: "dynamic",
					lineWrapping: true
				});

				_this.on('syntax-highlight.reset', function () {
					cm.setValue(_this.val());
				});

				_this.on('syntax-highlight.refresh', function () {
					cm.refresh();
				});
			});

			context.find(".text-area_syntax-highlight_plain").each(function () {
				var _this = $(this);
				if (_this.next('.CodeMirror').length) return true;

				var cm = CodeMirror.fromTextArea(this, {
					mode: "text/plain",
					tabMode: "indent",
					height: "dynamic",
					lineWrapping: true
				});

				_this.on('syntax-highlight.reset', function () {
					cm.setValue(_this.val());
				});

				_this.on('syntax-highlight.refresh', function () {
					cm.refresh();
				});
			});
		}

		if (typeof $().redactor != 'undefined')
		{
			context.find('.text-area_wysiwyg').each(function () {
				var _this = $(this);
				if (_this.prev('.redactor-editor').length) return true;

				_this.redactor();
			});
		}

		/* Form Ajax */

		context.on('submit', '.form_ajax', function ()
		{
			var _this = $(this);

			if (_this.data('timer'))
			{
				clearTimeout(_this.data('timer'));
				_this.data('timer', false);
			}

			function changeStatus(status, timer)
			{
				_this.find('.submit-box__status .form-status').addClass('form-status_hidden');
				_this.find('.submit-box__status .form-status_' + status).removeClass('form-status_hidden');
				timer = timer ? timer : false;

				if (timer)
				{
					_this.data('timer', setTimeout(function () {
						_this.find('.submit-box__status .form-status').addClass('form-status_hidden');
					}, timer));
				}
			}

			changeStatus('loading');
			var datatype = _this.data('datatype');
			datatype = datatype ? datatype : 'html';

			$.ajax({
				url: _this.attr('action'),
				method: _this.attr('method'),
				data: _this.serialize(),
				dataType: datatype,
				success: function (response)
				{
					changeStatus('success', 1000);
					console.log(_this);
					_this.trigger('ajax.success', [response]);

					return false;
				},
				error: function ()
				{
					changeStatus('error');
					_this.trigger('ajax.error');

					return false;
				}
			});

			return false;
		});

		initRadioBox(context);
		initCheckBox(context);
		initOptionSet(context);
		initSelectBox(context);
		initFieldGroup(context);
		initSelectTabs(context);
	}

	function initSelectBox(context)
	{
		context.on('change', '.select-box__input', function () {
			var _this = $(this);
			var select_box = _this.closest('.select-box');
			var disabled = _this.attr('disabled') ? true : false;
			var value = _this.val();
			var box_value = select_box.data('value');

			select_box.data('disabled', disabled);

			if (value !== box_value)
			{
				select_box.data('value', value);
				select_box.trigger('change');
			}

			return false;
		});

		context.on('change', '.select-box', function (e) {
			var _this = $(this);

			var input = _this.find('.select-box__input');
			var disabled = _this.data('disabled');
			var value = _this.data('value');
			var input_value = input.val();

			if (disabled)
			{
				input.attr('disabled', 'disabled');
			}
			else
			{
				input.removeAttr('disabled');
			}

			var options = input.find('option');
			options.removeAttr('selected');
			options.filter('option[value="'+value+'"]').attr('selected', 'selected');

			if (value !== input_value)
			{
				input.trigger('change');
			}
		});

		context.on('change', '.select-box_ajax', function (e) {
			var _this = $(this);
			var value = _this.data('value');

			if (_this.data('timer'))
			{
				clearTimeout(_this.data('timer'));
				_this.data('timer', false);
			}

			function changeStatus(status, timer)
			{
				_this.find('.select-box__status .form-status').addClass('form-status_hidden');
				_this.find('.select-box__status .form-status_' + status).removeClass('form-status_hidden');
				timer = timer ? timer : false;

				if (timer)
				{
					_this.data('timer', setTimeout(function () {
						_this.find('.select-box__status .form-status').addClass('form-status_hidden');
					}, timer));
				}
			}

			changeStatus('loading');

			$.ajax({
				url: _this.data('action'),
				method: _this.data('method'),
				data: { value: _this.data('value') },
				dataType: _this.data('datatype'),
				success: function (result) {
					changeStatus('success', 1000);
					_this.trigger('ajax.success', [result]);
				},
				error: function () {
					changeStatus('error');
				}
			});

			return false;
		});

		context.find('.select-box').each(function () {
			var _this = $(this);
			var input = _this.find('.select-box__input');
			var disabled = input.attr('disabled') ? true : false;
			var value = input.val();
			_this.data('disabled', disabled);
			_this.data('value', value);
		});
	}

	function initRadioBox(context)
	{
		context.on('change', '.radio-box__input', function () {
			var _this = $(this);
			var checked = _this.attr('checked') ? true : false;
			var disabled = _this.attr('disabled') ? true : false;
			var radio_box = _this.closest('.radio-box');
			var box_checked = radio_box.data('checked');
			radio_box.data('disabled', disabled);

			if (checked !== box_checked)
			{
				radio_box.data('checked', checked);
				radio_box.trigger('change');
			}

			if (checked)
			{
				var item = radio_box.closest('.radio-box-set__item');
				var radio_box_set = item.closest('.radio-box-set');
				var items = radio_box_set.find('.radio-box-set__item');
				var inputs = items.find('.radio-box__input').not(_this);
				inputs.trigger('change');
			}

			return false;
		});

		context.on('change', '.radio-box', function (e) {
			var _this = $(this);

			var input = _this.find('.radio-box__input');
			var disabled = _this.data('disabled');
			var checked = _this.data('checked');
			var input_checked = input.attr('checked') ? true : false;

			if (disabled)
			{
				input.attr('disabled', 'disabled');
			}
			else
			{
				input.removeAttr('disabled');
			}

			if (checked)
			{
				input.attr('checked', 'checked');
			}
			else
			{
				input.removeAttr('checked');
			}

			if (checked !== input_checked)
			{
				input.trigger('change');
			}
		});

		context.find('.radio-box').each(function () {
			var _this = $(this);
			var input = _this.find('.radio-box__input');
			var disabled = input.attr('disabled') ? true : false;
			var checked = input.attr('checked') ? true : false;
			_this.data('disabled', disabled);
			_this.data('checked', checked);
		});
	}

	function initCheckBox(context)
	{
		context.on('change', '.check-box__input', function () {
			var _this = $(this);
			var checked = _this.attr('checked') ? true : false;
			var disabled = _this.attr('disabled') ? true : false;
			var check_box = _this.closest('.check-box');
			var box_checked = check_box.data('checked');
			check_box.data('disabled', disabled);

			if (checked !== box_checked)
			{
				check_box.data('checked', checked);
				check_box.trigger('change');
			}

			return false;
		});

		context.on('change', '.check-box', function () {
			var _this = $(this);

			var input = _this.find('.check-box__input');
			var disabled = _this.data('disabled');
			var checked = _this.data('checked');
			var input_checked = input.attr('checked') ? true : false;

			if (disabled)
			{
				input.attr('disabled', 'disabled');
			}
			else
			{
				input.removeAttr('disabled');
			}

			if (checked)
			{
				input.attr('checked', 'checked');
			}
			else
			{
				input.removeAttr('checked');
			}

			if (checked !== input_checked)
			{
				input.trigger('change');
			}
		});

		context.find('.check-box').each(function () {
			var _this = $(this);
			var input = _this.find('.check-box__input');
			var disabled = input.attr('disabled') ? true : false;
			var checked = input.attr('checked') ? true : false;
			_this.data('disabled', disabled);
			_this.data('checked', checked);
		});
	}

	function initOptionSet(context)
	{
		context.on('change', '.option-set__trigger', function () {
			var _this = $(this);
			var checked = _this.data('checked');
			var option_set = _this.closest('.option-set');

			if (checked)
			{
				option_set.find('.option-set__item').data('disabled', false);
				option_set.find('.option-set__item').trigger('change');
				var triggers = option_set.find('.option-set__trigger').not(_this);
				triggers.trigger('change');
			}
			else
			{
				option_set.find('.option-set__item').data('disabled', true);
				option_set.find('.option-set__item').trigger('change');
			}
		});

		context.find('.option-set').each(function () {
			var _this = $(this);
			var trigger = _this.find('.option-set__trigger');
			trigger.trigger('change');
		});
	}

	function initFieldGroup(context)
	{
		context.on('change', '.field-group__trigger', function () {
			var _this = $(this);
			var checked = _this.data('checked');
			var field_group = _this.closest('.field-group_extensible');

			if (checked)
			{
				field_group.addClass('field-group_expanded');
			}
			else
			{
				field_group.removeClass('field-group_expanded');
			}
		});

		context.find('.field-group_extensible').each(function () {
			var _this = $(this);
			var trigger = _this.find('.field-group__trigger');
			trigger.trigger('change');
		});
	}

	function initSelectTabs(context)
	{
		context.on('change', '.select-tabs__select', function () {
			var _this = $(this);
			var value = _this.data('value');
			var select_tabs = _this.closest('.select-tabs');

			var tabs = select_tabs.find('.select-tabs__tab');
			tabs.removeClass('select-tabs__tab_open');
			tabs.filter('.select-tabs__tab_value_' + value).addClass('select-tabs__tab_open');
		});

		context.find('.select-tabs').each(function () {
			var _this = $(this);
			var select = _this.find('.select-tabs__select');
			select.trigger('change');
		});
	}

	$(function () {
		var html = $('html');

		initForm(html);
	});
})(jQuery);