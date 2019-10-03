(function ($) {

    var easlMemberZone = window.easlMemberZone = {
        homePage: EASLMZSETTINGS.homeURL,
        url: EASLMZSETTINGS.ajaxURL,
        action: EASLMZSETTINGS.ajaxActionName,
        loaderHtml: EASLMZSETTINGS.loaderHtml,
        messages: EASLMZSETTINGS.messages,
        Fees: EASLMZSETTINGS.membershipFees,
        modulesLoadTrigger: false,
        methods: {
            "resetPassword": 'reset_member_password',
            "changePassword": 'change_member_password',
            "memberCard": 'get_member_card',
            "featuredMember": 'get_featured_member',
            "membershipForm": 'get_membership_form',
            "newMembershipForm": 'get_new_membership_form',
            "submitMemberShipForm": "update_member_profile",
            "submitNewMemberForm": "create_member_profile",
            "deleteMyAccount": "delete_current_member"
        },
        loadHtml: function ($el, response) {
            if (response.Status === 200 || response.Status === 201) {
                $el.html(response.Html);
                $el.removeClass("easl-mz-loading");
            } else if (response.Status === 401) {
                // TODO-maybe reload
            }

        },
        showModuleLoading: function () {
            $(".easl-mz-crm-view").html(this.loaderHtml);
        },
        showFieldError: function (fieldName, errorMsg, $context) {
            $context = $context || $('body');
            var $field = $('#mzf_' + fieldName, $context), $fieldWrap = $field.closest(".mzms-field-wrap");
            $fieldWrap.addClass("easl-mz-field-has-error");
            !$(".mzms-field-error-msg", $fieldWrap).length && $fieldWrap.append('<p class="mzms-field-error-msg"></p>');
            $(".mzms-field-error-msg", $fieldWrap).html(errorMsg)
        },
        clearSingleFieldError: function (fieldName, $context) {
            var $field = $('#mzf_' + fieldName, $context),
                $fieldWrap = $('#mzf_' + fieldName, $context).closest(".mzms-field-wrap");
            $fieldWrap.removeClass('easl-mz-field-has-error');
            $(".mzms-field-error-msg", $fieldWrap).html('');
        },
        clearFieldErrors: function ($context) {
            $('.mzms-field-wrap', $context).removeClass('easl-mz-field-has-error');
            $(".mzms-field-error-msg", $context).html('');
        },
        customFileInput: function ($el) {
            $('.mzms-field-file-wrap input', $el).each(function () {
                var $input = $(this);
                var $label = $(this).closest(".mzms-field-file-wrap");
                var fileName = '';

                $input.on('change', function (e) {
                    if (this.files && this.files.length > 1) {
                        fileName = (this.getAttribute('data-multiple-caption') || '').replace('{count}', this.files.length);
                    } else if (e.target.value) {
                        fileName = e.target.value.split('\\').pop();
                    }
                    if (fileName) {
                        $label.addClass('mzfs-file-selected').find('.mzms-field-file-label').html(fileName);
                    } else {
                        $label.removeClass('mzfs-file-selected').find('.mzms-field-file-label').html('');
                    }
                });

                // Firefox bug fix
                $input
                    .on('focus', function () {
                        $input.addClass('has-focus');
                    })
                    .on('blur', function () {
                        $input.removeClass('has-focus');
                    });
            });
        },
        resetPassword: function () {
            var _this = this;
            $(".mz-reset-pass-button").on("mz_loaded:" + this.methods.resetPassword, function (event, response, method) {
                if (response.Status === 200) {
                    alert("Password has been reset! Please check your email.");
                    $(this)
                        .closest(".easl-mz-login-form-wrapper")
                        .removeClass("mz-show-reset-form mz-reset-pass-processing")
                        .find(".mz-forgot-password")
                        .html("Forgot your password?");
                }else{
                    alert("We could not find your email.");
                    $(this)
                        .closest(".easl-mz-login-form-wrapper")
                        .removeClass("mz-show-reset-form mz-reset-pass-processing")
                        .find(".mz-forgot-password")
                        .html("Forgot your password?");
                }

            });
            $(".mz-reset-pass-button").on("click", function (event) {
                event.preventDefault();
                $el = $(this);
                var email = $el.closest(".easl-mz-login-form-wrapper").addClass("mz-reset-pass-processing").find(".mz-reset-pass-email").val();
                if (email) {
                    _this.request(_this.methods.resetPassword, $el, {"email": email});
                }

            });
        },
        getMemberCard: function () {
            var _this = this;
            var $el = $(".easl-mz-membercard");
            if ($el.length) {
                $el.on("mz_loaded:" + this.methods.memberCard, function (event, response, method) {
                    if (response.Status === 200) {
                        !_this.modulesLoadTrigger && _this.loadModules();
                        _this.loadHtml($(this), response);
                    } else {
                        /**
                         * @todo replace with a modal
                         */
                        alert("Your session expired!");
                        window.location.href = _this.homePage;
                    }
                });
                this.request(this.methods.memberCard, $el);
            }
        },
        getFeaturedMembers: function () {
            var _this = this;
            var $el = $(".easl-mz-featured-members-slider");
            if ($el.length) {
                $el.on("mz_loaded:" + this.methods.featuredMember, function (event, response, method) {
                    _this.loadHtml($(this), response);
                    ('function' === typeof (window['vcexCarousels'])) && window.vcexCarousels($(this));
                });
                this.request(this.methods.featuredMember, $el);
            }
        },
        membershipFormEvents: function ($el) {
            var _this = this;
            var $jobFunction = $("#mzf_dotb_job_function", $el);
            var $jobFunctionOther = $("#mzms-fields-con-dotb_job_function_other", $el);
            var $speciality = $("#mzf_dotb_easl_specialty", $el);
            var $specialityOther = $("#mzms-fields-con-dotb_easl_specialty_other", $el);
            var $publicField = $("#mzms_dotb_public_profile", $el);
            var $publicProfileFields = $("#mzf_dotb_public_profile_fields", $el);

            if ($jobFunction.val() === "other") {
                $jobFunctionOther.removeClass("easl-mz-hide");
            } else {
                $jobFunctionOther.addClass("easl-mz-hide");
            }
            $jobFunction.on("change", function () {
                if ($(this).val() === "other") {
                    $jobFunctionOther.removeClass("easl-mz-hide");
                } else {
                    $jobFunctionOther.addClass("easl-mz-hide");
                }
            });

            if ($speciality.val() === "other") {
                $specialityOther.removeClass("easl-mz-hide");
            } else {
                $specialityOther.addClass("easl-mz-hide");
            }
            $speciality.on("change", function () {
                if ($(this).val() && (-1 !== $(this).val().indexOf("other"))) {
                    $specialityOther.removeClass("easl-mz-hide");
                } else {
                    $specialityOther.addClass("easl-mz-hide");
                }
            });
            $("#mzms-delete-account", $el).on("click", function (event) {
                event.preventDefault();
                _this.deleteMyAccount($("#easl-mz-membership-form", $el));
            });

            // Change Picture form events

            _this.customFileInput($el);

            $(".mzms-change-image", $el).on("click", function (event) {
                event.preventDefault();
                $el.addClass("mz-show-picture-change-form");
            });
            $(".mzms-change-picture-cancel", $el).on("click", function (event) {
                event.preventDefault();
                $el.removeClass("mz-show-picture-change-form");
            });
            $(".mzms-fields-privacy-icon", $el).on("click", function (event) {
                var $fieldWrap = $(this).closest(".mzms-field-wrap");
                event.preventDefault();
                if (!$fieldWrap.hasClass("mzms-privacy-enabled")) {
                    $fieldWrap.addClass("mzms-privacy-enabled");
                } else {
                    $fieldWrap.removeClass("mzms-privacy-enabled");
                    !$publicField.prop("checked") && $publicField.prop("checked", true).closest("label").addClass("easl-active");
                }
                $publicProfileFields.val(_this.getMemberProfilePublicFieldsValue());
            });
            $publicField.on("click", function (event) {
                if (!$(this).prop("checked")) {
                    $(".mzms-field-has-privacy", $el).addClass("mzms-privacy-enabled");
                    $publicProfileFields.val('');
                } else {
                    $(".mzms-field-has-privacy", $el).removeClass("mzms-privacy-enabled");
                    $publicProfileFields.val(_this.getMemberProfilePublicFieldsValue());
                }
            });

            // Change password form events
            $(".mzms-change-password", $el).on("click", function (event) {
                event.preventDefault();
                $el.addClass("mz-show-password-change-form");
            });
            $(".mzms-change-password-cancel", $el).on("click", function (event) {
                event.preventDefault();
                $el.removeClass("mz-show-password-change-form");
            });
            $(".mzms-change-password-submit", $el).on("click", function (event) {
                event.preventDefault();
                _this.changePassword($el);
            });
            $("#easl-mz-membership-form").on("submit", function (event) {
                event.preventDefault();
                _this.submitMemberShipForm($(this));
            });
        },
        getMemberProfilePublicFieldsValue: function ($el) {
            var field_names = [];
            $(".mzms-field-has-privacy").not(".mzms-privacy-enabled").find(":input").each(function () {
                $(this).attr('name') && field_names.push($(this).attr('name'));
            });
            return field_names.join(',');
        },
        changePassword: function ($el) {
            var _this = this;
            var $wrap = $el.find(".easl-mz-password-change-wrap");
            var error = false;
            var data = {
                "old_password": $("#mzf_old_password", $wrap).val(),
                "new_password": $("#mzf_new_password", $wrap).val(),
                "new_password2": $("#mzf_new_password2", $wrap).val()
            };

            _this.clearFieldErrors($wrap);

            if (!data.old_password) {
                _this.showFieldError('old_password', "Mandatory field", $wrap);
                error = true;
            } else {
                _this.clearSingleFieldError('old_password', $wrap);
            }
            if (!data.new_password) {
                _this.showFieldError('new_password', "Mandatory field", $wrap);
                error = true;
            } else {
                _this.clearSingleFieldError('new_password', $wrap);
            }
            if (!data.new_password2) {
                _this.showFieldError('new_password2', "Mandatory field", $wrap);
                error = true;
            } else {
                _this.clearSingleFieldError('new_password2', $wrap);
            }
            if (!error) {
                if (data.new_password2 !== data.new_password) {
                    _this.showFieldError('new_password2', "Must be same as password.", $wrap);
                    error = true;
                } else {
                    _this.clearSingleFieldError('new_password2', $wrap);
                }
            }
            if (!error) {
                $el.addClass("easl-mz-modal-processing");
                $el.one("mz_loaded:" + this.methods.changePassword, function (event, response, method) {
                    $el.removeClass("easl-mz-modal-processing");
                    if (response.Status === 200) {
                        // TODO - Replace with a modal
                        alert("Your password has been changed successfully!");
                        $el.removeClass("mz-show-password-change-form");
                    }
                    if (response.Status === 400) {
                        // TODO - Replace with a modal
                        for (var fieldName in response.Errors) {
                            _this.showFieldError(fieldName, response.Errors[fieldName], $wrap);
                        }
                    }
                    if (response.Status === 405) {
                        // TODO - Replace with a modal
                        alert("Failed! Refresh the page and try again.");
                    }
                    if (response.Status === 401) {
                        // TODO - Replace with a modal
                        alert("Unauthorized! Refresh the page.");
                    }
                });
                this.request(this.methods.changePassword, $el, data);
            }
        },
        deleteMyAccount: function ($form) {
            var _this = this;
            $form.closest(".wpb_easl_mz_membership").addClass("easl-mz-form-processing").append('<div class="easl-mz-membership-loader">' + this.loaderHtml + '</div>');
            $("html, body").stop().animate({
                "scrollTop": 0
            }, 600);
            $form.one("mz_loaded:" + this.methods.deleteMyAccount, function (event, response, method) {
                $form.closest(".wpb_easl_mz_membership").removeClass("easl-mz-form-processing").find(".easl-mz-membership-loader").remove();
                if (response.Status === 200) {
                    // TODO - Replace with a modal
                    alert("Your account deleted successfully!");
                    window.location.href = _this.homePage;
                }
                if (response.Status === 400) {
                    // TODO - Replace with a modal
                    alert("Could not delete account! Refresh the page and try again.");
                }
                if (response.Status === 401) {
                    // TODO - Replace with a modal
                    alert("Unauthorized! Refresh the page.");
                }
            });
            this.request(this.methods.deleteMyAccount, $form, {"id": $form.find('#mzf_id').val()});
        },
        submitMemberShipForm: function ($form) {
            _this = this;
            this.clearFieldErrors($form);
            $form.closest(".wpb_easl_mz_membership").addClass("easl-mz-form-processing").append('<div class="easl-mz-membership-loader">' + this.loaderHtml + '</div>');
            $("html, body").stop().animate({
                "scrollTop": 0
            }, 600);
            $form.one("mz_loaded:" + this.methods.submitMemberShipForm, function (event, response, method) {
                $form.closest(".wpb_easl_mz_membership").removeClass("easl-mz-form-processing").find(".easl-mz-membership-loader").remove();
                if (response.Status === 200) {
                    // TODO - Replace with a modal
                    alert("Your profile updated successfully!");
                    _this.getMembershipForm();
                }
                if (response.Status === 400) {
                    // TODO - Replace with a modal
                    for (var fieldName in response.Errors) {
                        _this.showFieldError(fieldName, response.Errors[fieldName], $form);
                    }
                }
                if (response.Status === 401) {
                    // TODO - Replace with a modal
                    alert("Unauthorized! Refresh the page.");
                }
            });
            _this.request(this.methods.submitMemberShipForm, $form, $form.serialize());
        },
        getMembershipForm: function () {
            var _this = this;
            var $el = $(".easl-mz-membership-inner");
            if ($el.length) {
                $el.on("mz_loaded:" + this.methods.membershipForm, function (event, response, method) {
                    _this.loadHtml($(this), response);
                    $("body").trigger("mz_reload_custom_fields");
                    $(".easl-mz-select2", $(this)).select2({
                        closeOnSelect: true,
                        allowClear: true
                    });
                    $("#mzf_birthdate_fz", $(this)).datepicker({
                        dateFormat: "dd.mm.yy",
                        altFormat: "yy-mm-dd",
                        altField: "#mzf_birthdate",
                        changeMonth: true,
                        changeYear: true,
                        yearRange: "1900:-00",
                        maxDate: "-0D"
                    });
                    _this.membershipFormEvents($el);
                });
                this.request(this.methods.membershipForm, $el);
            }
        },
        initNewMemberForm: function () {
            var _this = this;
            var $el = $(".wpb_easl_mz_new_member_form");
            if ($el.length) {
                $(".easl-mz-select2", $el).select2({
                    closeOnSelect: true,
                    allowClear: true
                });
                $(".easl-mz-date", $el).datepicker({
                    dateFormat: "dd.mm.yy",
                    altFormat: "yy-mm-dd",
                    altField: "#mzf_birthdate",
                    changeMonth: true,
                    changeYear: true,
                    yearRange: "1900:-00",
                    maxDate: "-0D"
                });
                var $jobFunction = $("#mzf_dotb_job_function", $el);
                var $jobFunctionOther = $("#mzms-fields-con-dotb_job_function_other", $el);
                var $speciality = $("#mzf_dotb_easl_specialty", $el);
                var $specialityOther = $("#mzms-fields-con-dotb_easl_specialty_other", $el);

                if ($jobFunction.val() === "other") {
                    $jobFunctionOther.removeClass("easl-mz-hide");
                } else {
                    $jobFunctionOther.addClass("easl-mz-hide");
                }
                $jobFunction.on("change", function () {
                    if ($(this).val() === "other") {
                        $jobFunctionOther.removeClass("easl-mz-hide");
                    } else {
                        $jobFunctionOther.addClass("easl-mz-hide");
                    }
                });

                if ($speciality.val() === "other") {
                    $specialityOther.removeClass("easl-mz-hide");
                } else {
                    $specialityOther.addClass("easl-mz-hide");
                }
                $speciality.on("change", function () {
                    if ($(this).val() && (-1 !== $(this).val().indexOf("other"))) {
                        $specialityOther.removeClass("easl-mz-hide");
                    } else {
                        $specialityOther.addClass("easl-mz-hide");
                    }
                });
                var $termsCondition = $("#mzf_terms_condition", $el);
                $("#easl-mz-new-member-form").on("submit", function (event) {
                    event.preventDefault();
                    if (!$termsCondition.prop("checked")) {
                        _this.showFieldError("terms_condition", "You must agree to our terms and conditions.", $el);
                        event.preventDefault();
                    } else {
                        _this.clearSingleFieldError("terms_condition", $el);
                        _this.submitNewMemberForm($(this));
                    }
                });
            }
        },
        submitNewMemberForm: function ($form) {
            _this = this;
            this.clearFieldErrors($form);
            $form.closest(".wpb_easl_mz_new_member_form").addClass("easl-mz-form-processing");
            $("html, body").stop().animate({
                "scrollTop": 0
            }, 600);
            $form.one("mz_loaded:" + this.methods.submitNewMemberForm, function (event, response, method) {
                $form.closest(".wpb_easl_mz_new_member_form").removeClass("easl-mz-form-processing");
                if (response.Status === 200) {
                    window.location.href = response.Html;
                }
                if (response.Status === 201) {
                    _this.loadHtml($form.closest(".easl-mz-new-member-form-inner"), response);
                }
                if (response.Status === 400) {
                    for (var fieldName in response.Errors) {
                        _this.showFieldError(fieldName, response.Errors[fieldName], $form);
                    }
                }
                if (response.Status === 401) {
                    // TODO - Replace with a modal
                    alert("Unauthorized! Refresh the page.");
                }
            });
            _this.request(this.methods.submitNewMemberForm, $form, $form.serialize());
        },
        newMembershipFormEvents: function ($el) {
            var _this = this;
            // Membership Category Form events
            var requireProof = ["trainee_jhep", "trainee", "nurse_jhep", "nurse", "allied_pro_jhep", "allied_pro"];
            var jhef = ["regular_jhep", "corresponding_jhep", "trainee_jhep", "nurse_jhep", "patient_jhep", "emeritus_jhep", "allied_pro_jhep"];
            var $mzf_membership_category = $("#mzf_membership_category", $el);
            var $mzf_billing_mode = $("#mzf_billing_mode", $el);
            var $mzf_jhephardcopy_recipient = $("#mzf_jhephardcopy_recipient", $el);
            var $mzf_supporting_docs = $("#mzf_supporting_docs", $el);

            if (-1 !== requireProof.indexOf($mzf_membership_category.val())) {
                $("#mzms-support-docs-wrap").addClass("easl-active");
            } else {
                $("#mzms-support-docs-wrap").removeClass("easl-active")
            }
            if (-1 !== jhef.indexOf($mzf_membership_category.val())) {
                $("#mzf_jhephardcopy_recipient_wrapper").addClass("easl-active");
            } else {
                $("#mzf_jhephardcopy_recipient_wrapper").removeClass("easl-active").val("c1");
                $("#mz-membership-jhe-pother-address-wrap").removeClass("easl-active");
            }

            $mzf_membership_category.on("change", function (event) {
                var cat = $mzf_membership_category.val();
                if (-1 !== requireProof.indexOf(cat)) {
                    $("#mzms-support-docs-wrap").addClass("easl-active");
                } else {
                    $("#mzms-support-docs-wrap").removeClass("easl-active")
                }
                if (-1 !== jhef.indexOf(cat)) {
                    $("#mzf_jhephardcopy_recipient_wrapper").addClass("easl-active");
                } else {
                    $("#mzf_jhephardcopy_recipient_wrapper").removeClass("easl-active").val("c1");
                    $("#mz-membership-jhe-pother-address-wrap").removeClass("easl-active");
                }

            });
            $mzf_billing_mode.on("change", function (event) {
                if ('other' === $mzf_billing_mode.val()) {
                    $("#mz-membership-other-address-wrap").addClass("easl-active");
                } else {
                    $("#mz-membership-other-address-wrap").removeClass("easl-active")
                }
            });
            $mzf_jhephardcopy_recipient.on("change", function (event) {
                if ('other' === $mzf_jhephardcopy_recipient.val()) {
                    $("#mz-membership-jhe-pother-address-wrap").addClass("easl-active");
                } else {
                    $("#mz-membership-jhe-pother-address-wrap").removeClass("easl-active");
                }
            });
            var $termsCondition = $("#mzf_terms_condition", $el);
            $("form", $el).on("submit", function (event) {
                var hasError = false;
                if (!$termsCondition.prop("checked")) {
                    hasError = true;
                    _this.showFieldError("terms_condition", "You must agree to our terms and conditions.", $el);
                } else {
                    _this.clearSingleFieldError("terms_condition", $el);
                }
                if ((-1 !== requireProof.indexOf($mzf_membership_category.val())) && !$mzf_supporting_docs.val()) {
                    hasError = true;
                    _this.showFieldError("supporting_docs", "Please upload  supporting document.", $el);
                } else {
                    _this.clearSingleFieldError("supporting_docs", $el);
                }

                if (hasError) {
                    event.preventDefault();
                }
            });
        },
        getNewMembershipForm: function () {
            var _this = this;
            var $el = $(".easl-mz-new-membership-form");
            if ($el.length) {
                $el.on("mz_loaded:" + this.methods.newMembershipForm, function (event, response, method) {
                    _this.loadHtml($(this), response);
                    $("body").trigger("mz_reload_custom_fields");
                    $(".easl-mz-select2", $(this)).select2({
                        closeOnSelect: true,
                        allowClear: true
                    });
                    _this.customFileInput($el);
                    _this.newMembershipFormEvents($el);
                });
                this.request(this.methods.newMembershipForm, $el, {
                    'renew': $el.data('paymenttype'),
                    'messages': _this.messages
                });
            }
        },
        request: function (method, $el, reqData) {
            reqData = reqData || {};
            $.ajax({
                url: this.url,
                method: "POST",
                data: {
                    action: this.action,
                    method: method,
                    request_data: reqData
                },
                success: $.proxy(function (response) {
                    response && response.Status && $el.trigger("mz_loaded:" + method, [response, method]);
                }, this),
                dataType: "json"
            });
        },
        loadModules: function () {
            this.modulesLoadTrigger = true;
            this.getFeaturedMembers();
            this.getMembershipForm();
            this.getNewMembershipForm();
        },
        events: function () {
            $(".mz-forgot-password").on("click", function (event) {
                event.preventDefault();
                var $formWrap = $(this).closest(".easl-mz-login-form-wrapper");
                if ($formWrap.hasClass("mz-show-reset-form")) {
                    $formWrap.removeClass("mz-show-reset-form");
                    $(this).html("Forgot your password?");
                } else {
                    $formWrap.addClass("mz-show-reset-form");
                    $(this).html("Login");
                }

            });
        },
        init: function () {
            this.events();
            this.showModuleLoading();
            this.getMemberCard();
            this.initNewMemberForm();
            this.resetPassword();
        }
    };

    $(document).ready(function () {
        $(".easl-mz-header-login-button").on("click", function (event) {
            event.preventDefault();
            $(".easl-mz-login-form").toggleClass("easl-active");
        });

        $(".md-item-name a").on("click", function (event) {
            var $conWrap = $(this).closest(".wpb_easl_mz_directory");
            event.preventDefault();
            $conWrap.addClass("easl-mz-mp-show-details").data('easlscrollpos', document.documentElement.scrollTop);

            $('html, body').animate({
                scrollTop: $conWrap.offset().top - $('#site-header').height() - 100
            }, 275);
        });
        $(".easl-mz-back-link").on("click", function (event) {
            var $conWrap = $(this).closest(".wpb_easl_mz_directory");
            event.preventDefault();
            $conWrap.removeClass("easl-mz-mp-show-details");
            var scrollPosition = $conWrap.data('easlscrollpos') || false;
            if (scrollPosition) {
                $('html, body').animate({
                    scrollTop: scrollPosition
                }, 275);
            }
            $mscContentWrap.data('easlscrollpos', false);
        });


        easlMemberZone.init();
    });

})(jQuery);