(function ($) {

    var easlMemberZone = window.easlMemberZone = {
        homePage: EASLMZSETTINGS.homeURL,
        url: EASLMZSETTINGS.ajaxURL,
        action: EASLMZSETTINGS.ajaxActionName,
        loaderHtml: EASLMZSETTINGS.loaderHtml,
        modulesLoadTrigger: false,
        methods: {
            "memberCard": 'get_member_card',
            "featuredMember": 'get_featured_member',
            "membershipForm": 'get_membership_form',
            "submitMemberShipForm": "update_member_profile",
            "submitNewMemberForm": "create_member_profile",
            "deleteMyAccount": "delete_current_member"
        },
        loadHtml: function ($el, response) {
            if (response.Status === 200) {
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
        clearFieldErrors: function ($context) {
            $('.mzms-field-wrap', $context).removeClass('easl-mz-field-has-error');
            $(".mzms-field-error-msg", $context).html('');
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
            $("#easl-mz-membership-form").on("submit", function (event) {
                event.preventDefault();
                _this.submitMemberShipForm($(this));
            });
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
                    $(".easl-mz-select2", $(this)).select2({
                        closeOnSelect: true
                    });
                    $(".easl-mz-date", $(this)).datepicker({
                        dateFormat: "yy-mm-dd",
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
                    closeOnSelect: false
                });
                $(".easl-mz-date", $el).datepicker({
                    dateFormat: "yy-mm-dd",
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
                $("#easl-mz-new-member-form").on("submit", function (event) {
                    event.preventDefault();
                    _this.submitNewMemberForm($(this));
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
                    _this.loadHtml($(this).closest(".easl-mz-new-member-form-inner"), response);
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
        },
        events: function () {
            $(".mz-forgot-password").on("click", function (event) {
                event.preventDefault();
                var $formWrap = $(this).closest(".easl-mz-login-form");
                if($formWrap.hasClass("mz-show-reset-form")){
                    $formWrap.removeClass("mz-show-reset-form");
                    $(this).html("Forgot your password?");
                }else{
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