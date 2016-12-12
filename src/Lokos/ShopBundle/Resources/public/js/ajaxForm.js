/**
 * JS logic for ajax form validation
 *
 * @category   EUC
 * @package    Mindk
 * @subpackage EucBundle
 * @author     Maxim Lyatsky <mlyatsky@mindk.com>
 * @copyright  2011-2012 mindk (http://mindk.com). All rights reserved.
 * @license    http://mindk.com Commercial
 * @link       http://mindk.com
 */

AjaxForm = {
    /**
     * class settings
     */
    options: {
        formErrorTemplateId: "ajaxFormErrorTemplate",
        fieldErrorTemplateId: "ajaxFieldErrorTemplate",
        responseErrorMsg: "Something went wrong: '{message}'",
        validateOnSubmit: true,
        validateOnChange: true,
        beforeSubmitAction: null,
        beforeShowErrorAction: null,
        afterShowErrorAction: null,
        validateErrorAction: null,
        validateSuccessAction: null,
        wrapperClass: "uk-form-row",
        errorClass: "has-error",
        successClass: "success",
        initClass:"ajax-form-init",
        validatedClass:"ajax-form-validated"
    },

    /**
     * Initialization of form
     *
     * @param form    form element
     * @param options object with configuration options
     *
     * @returns {*}
     */
    init: function (form, options) {
        var self = this;
        if (options) {
            this.setOptions(options);
        }
        this.form = form;
        if (this.options.validateOnSubmit) {
            $(this.form).not('.' + this.options.initClass).submit(function () {
                self.validateForm(self.form);
                return false;
            }).addClass(this.options.initClass);
        }
        if (this.options.validateOnChange) {
            $(this.form).find('input,textarea').not('[type=hidden],[type=checkbox],[type=radio]').not('.' + this.options.initClass).blur(
                function () {
                    self.validateField($(this));
                }
            ).addClass(this.options.initClass).attr('data-wrapper', this.options.wrapperClass);
            $(this.form).find('input[type=hidden],input[type=checkbox],input[type=radio],select').not('.' + this.options.initClass).change(
                function () {
                    self.validateField($(this));
                }
            ).addClass(this.options.initClass).attr('data-wrapper', this.options.wrapperClass);
        }
        return this;
    },

    /**
     * Sets configuration
     *
     * @param options object with configuration options
     */
    setOptions: function (options) {
        $.extend(this.options, options);
    },

    /**
     * Show/hide errors for all fields, if need, else submit form
     *
     * @param form     form element
     * @param ajaxForm ajaxForm object
     * @param data   object with validation response
     */
    validateForm: function (form, ajaxForm, data) {
        var self = ajaxForm || this;
        if (!data) {
            this.validate(form, this.validateForm);
            return;
        }
        $.each(data.errors, function (key, value) {
            self.showError(self, value, $("#" + key));
        });
        if (!data.hasErrors) {
            if (typeof self.options.beforeSubmitAction == "function") {
                if (!self.options.beforeSubmitAction(self.form, data)) {
                    return;
                }
            }
            $(self.form).submit(function(){ return true; });
            self.form.submit();
        }
    },

    /**
     * Show/hide error for validated field, if need
     *
     * @param field    field element that need validate
     * @param ajaxForm ajaxForm instance
     * @param errors   object with validation response
     */
    validateField: function (field, ajaxForm, errors) {
        var self = ajaxForm || this;
        if (!errors) {
            if(field.attr('type') && field.attr('type').toLowerCase() == 'password') {
                var first = field.parents('form').find('input[type="password"]:first'),
                    last  = field.parents('form').find('input[type="password"]:last');
                if(first.attr('id') == last.attr('id')) {
                    this.validate(field, this.validateField);
                }
                else if ((field.attr('id') == last.attr('id')) || (last.val() != '')) {
                    this.validate(first, this.validateField);
                }
            }
            else {
                this.validate(field, this.validateField);
            }
            return;
        }
        $.each(errors.errors, function (key, value) {
            if (key == field.attr('id') || key == field.attr('data-id')) {
                self.showError(self, value, $('#' + key));
            }
        });
    },

    /**
     * Show/hide error for element
     *
     * @param ajaxForm ajaxForm instance
     * @param messages array with validation errors
     * @param element  DOM element as jQuery object
     */
    showError: function (ajaxForm, messages, element) {
        if (typeof ajaxForm.options.beforeShowErrorAction == "function") {
            if (!ajaxForm.options.beforeShowErrorAction(messages, element)) {
                return;
            }
        }
        if (element && (element.attr("type") == "file")) {
            return;
        }
        var error;
        if (element && element.length) {
            var wrapper_class = element.attr('data-wrapper');
            if (!wrapper_class) {
                wrapper_class = $('[data-id=' + element.attr('id') + ']').attr('data-wrapper');
            }
            if (messages.length) {
                element.parents('.' + wrapper_class)
                    .first()
                    .addClass(ajaxForm.options.errorClass)
                    .addClass(ajaxForm.options.validatedClass)
                    .removeClass(ajaxForm.options.successClass);
                element.addClass(ajaxForm.options.errorClass)
                    .removeClass(ajaxForm.options.successClass);
                element.parent().find(".ajax-error").remove();
                error = $("#" + ajaxForm.options.fieldErrorTemplateId).html().replace(/\{message\}/, messages.join('<br/>'));
                error = $(error).addClass("ajax-error");
                error.appendTo(element.parent());
            } else {
                element.parents("." + wrapper_class)
                    .first()
                    .not('.' + ajaxForm.options.validatedClass)
                    .removeClass(ajaxForm.options.errorClass)
                    .addClass(ajaxForm.options.successClass)
                    .find(".ajax-error")
                    .remove();
                element.removeClass(ajaxForm.options.errorClass)
                    .addClass(ajaxForm.options.successClass);
            }
        } else {
            if (messages.length) {
                $(ajaxForm.form).children(".ajax-error").remove();
                error = $("#" + ajaxForm.options.formErrorTemplateId).html().replace(/\{message\}/, messages.join('<br/>'));
                error = $(error).addClass("ajax-error");
                error.insertBefore($(ajaxForm.form).children(":first"));
            } else {
                $(ajaxForm.form).children(".ajax-error").remove();
            }
        }

        if (typeof ajaxForm.options.afterShowErrorAction == "function") {
            if (!ajaxForm.options.afterShowErrorAction(messages, element, error)) {
                return;
            }
        }

    },

    /**
     * Do validate action
     *
     * @param owner    element for callback function
     * @param callback the function that executes when response
     */
    validate: function (owner, callback) {
        var self = this;
        $('.' + self.options.validatedClass).removeClass(self.options.validatedClass);
        $.ajax({
            url: self.form.action,
            type: self.form.method,
            data: $(self.form).serialize(),
            dataType: "json",
            success: function (response) {
                if (!response.hasErrors) {
                    if (typeof self.options.validateSuccessAction == "function") {
                        self.options.validateSuccessAction(owner, self.form);
                    }
                } else {
                    if (typeof self.options.validateErrorAction == "function") {
                        self.options.validateErrorAction(self.form, response.errors);
                    }
                }
                if (typeof callback == "function") {
                    callback(owner, self, response);
                }
            },
            error: function (data, status, text) {
                self.showError(self, [self.options.responseErrorMsg.replace(/\{message\}/, text)]);
            }
        });
    }
};