(function ($) {
    var mzModal = window.mzModal = {
        $el: null,
        $backdrop: null,
        $content: null,
        $closeButton: null,
        transitionEventName: "transitionEnd webkitTransitionEnd transitionend oTransitionEnd msTransitionEnd",
        show: function (html, name) {
            var _this = this;
            if (_this.$el.hasClass("easl-mz-modal-transitioning")) {
                return;
            }
            html && _this.$content.html(html);
            _this.$el.addClass("easl-mz-modal-transitioning").attr("data-mzname", name);
            _this.$el[0].style.opacity = "0";
            _this.$el[0].style.display = "block";
            _this.$el[0].innerWidth;
            _this.$el.one(_this.transitionEventName, function () {
                _this.$el.addClass("easl-mz-modal-shown").removeClass("easl-mz-modal-transitioning").removeClass("easl-mz-modal-hidden");
                _this.$el[0].style.opacity = "";
                _this.$el[0].style.display = "";
            });
            requestAnimationFrame(function () {
                _this.$el[0].style.opacity = "1";
            })
        },
        hide: function () {
            var _this = this;
            if (_this.$el.hasClass("easl-mz-modal-transitioning")) {
                return;
            }
            _this.$el.addClass("easl-mz-modal-transitioning").removeClass("easl-mz-modal-shown");
            _this.$el[0].innerWidth;
            _this.$el.one(_this.transitionEventName, function () {
                _this.$el.addClass("easl-mz-modal-hidden").removeClass("easl-mz-modal-transitioning");
                _this.$el[0].style.opacity = "";
                _this.$el.trigger("mz.modal.hidden");
                name && _this.$el.trigger("mz.modal.hidden." + name);
            });
            requestAnimationFrame(function () {
                _this.$el[0].style.opacity = "0";
            })
        },
        events: function () {
            var _this = this;
            _this.$closeButton.on("click", function (event) {
                event.preventDefault();
                _this.hide();
            });
            _this.$backdrop.on("click", function (event) {
                event.preventDefault();
                _this.hide();
            });
        },
        init: function () {
            if (!this.$el) {
                this.$el = $('<div id="easl-mz-modal-wrap"></div>');
                this.$el.addClass("easl-mz-modal-hidden");
                this.$el.append('<div class="easl-mz-modal-backdrop"></div><div class="easl-mz-modal"><div class="easl-mz-modal-content"></div><div class="easl-mz-modal-buttons"><a class="easl-mz-modal-close" href="#">OK</a></div></div>');
                $("body").append(this.$el);
                this.$backdrop = $(".easl-mz-modal-backdrop", this.$el);
                this.$content = $(".easl-mz-modal-content", this.$el);
                this.$closeButton = $(".easl-mz-modal-close", this.$el);
                this.events();
            }
            return this;
        },

    };
    var mzMap = window.mzMap = {
        Storage: {
            apiKey: EASLMZSETTINGS.mapAPIkey,
            mapData: {},
            map: {},
            styledMap: {}
        },
        style: function () {
            return [
                {
                    "elementType": "geometry",
                    "stylers": [
                        {
                            "color": "#eaeaea"
                        }
                    ]
                },
                {
                    "elementType": "labels",
                    "stylers": [
                        {
                            "visibility": "off"
                        }
                    ]
                },
                {
                    "featureType": "administrative",
                    "stylers": [
                        {
                            "visibility": "off"
                        }
                    ]
                },
                {
                    "featureType": "administrative",
                    "elementType": "geometry",
                    "stylers": [
                        {
                            "visibility": "off"
                        }
                    ]
                },
                {
                    "featureType": "administrative.land_parcel",
                    "stylers": [
                        {
                            "visibility": "off"
                        }
                    ]
                },
                {
                    "featureType": "administrative.neighborhood",
                    "stylers": [
                        {
                            "visibility": "off"
                        }
                    ]
                },
                {
                    "featureType": "landscape",
                    "stylers": [
                        {
                            "visibility": "off"
                        }
                    ]
                },
                {
                    "featureType": "poi",
                    "stylers": [
                        {
                            "visibility": "off"
                        }
                    ]
                },
                {
                    "featureType": "road",
                    "stylers": [
                        {
                            "visibility": "off"
                        }
                    ]
                },
                {
                    "featureType": "road",
                    "elementType": "labels.icon",
                    "stylers": [
                        {
                            "visibility": "off"
                        }
                    ]
                },
                {
                    "featureType": "road.arterial",
                    "stylers": [
                        {
                            "visibility": "off"
                        }
                    ]
                },
                {
                    "featureType": "road.highway",
                    "elementType": "labels",
                    "stylers": [
                        {
                            "visibility": "off"
                        }
                    ]
                },
                {
                    "featureType": "road.local",
                    "stylers": [
                        {
                            "visibility": "off"
                        }
                    ]
                },
                {
                    "featureType": "transit",
                    "stylers": [
                        {
                            "visibility": "off"
                        }
                    ]
                },
                {
                    "featureType": "water",
                    "stylers": [
                        {
                            "color": "#fff"
                        }
                    ]
                }
            ]
        },
        hasAPI: function () {
            return this.Storage.apiKey;
        },
        maybeInit: function () {
            if (this.hasAPI()) {
                this.init();
            }
        },
        init: function () {
            if (typeof google === "undefined" || typeof google.maps === "undefined") {
                var tag = document.createElement('script');
                tag.async = true;
                tag.defer = true;
                tag.src = "https://maps.googleapis.com/maps/api/js?key=" + this.Storage.apiKey + "&callback=mzInitMaps";
                var firstScriptTag = document.getElementsByTagName('script')[0];
                firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
            } else {
                this.initMaps();
            }
        },
        initMaps: function () {
            var styledMapType = new google.maps.StyledMapType(this.style());
            var map = new google.maps.Map(document.getElementById('easl-mz-stat-map'), {
                center: {lat: 43.371, lng: 9.282},
                zoom: 1.4,
                mapTypeControlOptions: {
                    mapTypeIds: ['roadmap', 'satellite', 'hybrid', 'terrain',
                        'mz_top_country_map']
                },
                zoomControl: false,
                mapTypeControl: false,
                scaleControl: false,
                streetViewControl: false,
                rotateControl: false,
                fullscreenControl: false
            });
            map.mapTypes.set('mz_top_country_map', styledMapType);
            map.setMapTypeId('mz_top_country_map');
        }
    };
    window.mzInitMaps = function () {
        mzMap.initMaps();
    };
    var easlMemberZone = window.easlMemberZone = {
        homePage: EASLMZSETTINGS.homeURL,
        url: EASLMZSETTINGS.ajaxURL,
        action: EASLMZSETTINGS.ajaxActionName,
        loaderHtml: EASLMZSETTINGS.loaderHtml,
        messages: EASLMZSETTINGS.messages,
        Fees: EASLMZSETTINGS.membershipFees,
        modulesLoadTrigger: false,
        memberDirectoryRequest: false,
        methods: {
            "resetPassword": 'reset_member_password',
            "changePassword": 'change_member_password',
            "memberCard": 'get_member_card',
            "featuredMember": 'get_featured_member',
            "membershipForm": 'get_membership_form',
            "membershipBanner": 'get_membership_banner',
            "newMembershipForm": 'get_new_membership_form',
            "submitMemberShipForm": "update_member_profile",
            "submitNewMemberForm": "create_member_profile",
            "deleteMyAccount": "delete_current_member",
            "getMemberDirectory": "get_members_list",
            "getMemberDetails": "get_member_details",
            "getMembersMembership": "get_members_memberships",
            "getMembershipNotes": "get_memberships_notes"
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
                var $resetButton = $(this);
                if (response.Status === 200) {
                    mzModal.init();
                    mzModal.$el.one("mz.modal.hidden.email.reset.ok", function () {
                        $resetButton
                            .closest(".easl-mz-login-form-wrapper")
                            .removeClass("mz-show-reset-form mz-reset-pass-processing")
                            .find(".mz-forgot-password")
                            .html("Forgot your password?");
                    });
                    mzModal.show('<div class="mz-modal-password-reset">Your password has been reset, <br>please check your email</div>', 'email.reset.ok');
                } else {
                    mzModal.init();
                    mzModal.$el.one("mz.modal.hidden.email.reset.notfound", function () {
                        $resetButton
                            .closest(".easl-mz-login-form-wrapper")
                            .removeClass("mz-show-reset-form mz-reset-pass-processing")
                            .find(".mz-forgot-password")
                            .html("Forgot your password?");
                    });
                    mzModal.show('<div class="mz-modal-password-reset">We could not find your email</div>', 'email.reset.notfound');
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
        loadMemberZonePanel: function (html) {
            var $panel = $("#mz-panel-outer-wrapper");
            if ($panel.length === 0) {
                $("body").append('<div id="mz-panel-outer-wrapper"></div>');
                $panel = $("#mz-panel-outer-wrapper");
                $panel.html(html);
                $("body").on("click", ".mz-member-panel-button", function (event) {
                    event.preventDefault();
                    console.log('OK');
                    if ($panel.hasClass("mz-show-panel")) {
                        $panel.removeClass("mz-show-panel");
                    } else {
                        $panel.addClass("mz-show-panel");
                    }
                });
                $(".mz-panel-close").on("click", function (event) {
                    event.preventDefault();
                    $panel.removeClass("mz-show-panel");
                });
            }
        },
        getMemberCard: function () {
            var _this = this;
            var $el = $(".easl-mz-membercard");
            if ($el.length) {
                $el.on("mz_loaded:" + this.methods.memberCard, function (event, response, method) {
                    if (response.Status === 200) {
                        !_this.modulesLoadTrigger && _this.loadModules();
                        $el.html(response.Html);
                        $el.removeClass("easl-mz-loading");
                        if (response.Panel) {
                            _this.loadMemberZonePanel(response.Panel);
                        }
                    } else if (response.Status === 401) {
                        // TODO-maybe reload
                    }
                    if (response.Status === 200) {
                        !_this.modulesLoadTrigger && _this.loadModules();
                        _this.loadHtml($(this), response);
                    } else {
                        mzModal.init();
                        mzModal.$el.one("mz.modal.hidden.session.expired", function () {
                            window.location.href = _this.homePage;
                        });
                        mzModal.show('<div class="mz-modal-password-reset">Your session expired</div>', 'session.expired');
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
                    $(".mzms-fields-privacy-tooltip", $fieldWrap).html("Show to public");
                } else {
                    $fieldWrap.removeClass("mzms-privacy-enabled");
                    $(".mzms-fields-privacy-tooltip", $fieldWrap).html("Hide from public")
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
                        mzModal.init();
                        mzModal.$el.one("mz.modal.hidden.password.changed.ok", function () {
                            $el.removeClass("mz-show-password-change-form");
                        });
                        mzModal.show('<div class="mz-modal-password-changed">Your password has been changed successfully!</div>', 'password.changed.ok');
                    }
                    if (response.Status === 400) {
                        // TODO - Replace with a modal
                        for (var fieldName in response.Errors) {
                            _this.showFieldError(fieldName, response.Errors[fieldName], $wrap);
                        }
                    }
                    if (response.Status === 405) {
                        mzModal.init();
                        mzModal.$el.one("mz.modal.hidden.password.changed.notok", function () {
                            // May be refresh
                        });
                        mzModal.show('<div class="mz-modal-password-changed">Failed! Refresh the page and try again!</div>', 'password.changed.notok');
                    }
                    if (response.Status === 401) {
                        mzModal.init();
                        mzModal.$el.one("mz.modal.hidden.password.changed.unauthorized", function () {
                            // May be refresh
                        });
                        mzModal.show('<div class="mz-modal-unauthorized">Unauthorized! Refresh the page</div>', 'password.changed.unauthorized');
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
                    mzModal.init();
                    mzModal.$el.one("mz.modal.hidden.account.delete.ok", function () {
                        window.location.href = _this.homePage;
                    });
                    mzModal.show('<div class="mz-modal-password-changed">Your account deleted successfully!</div>', 'account.delete.ok');
                }
                if (response.Status === 400) {
                    mzModal.init();
                    mzModal.$el.one("mz.modal.hidden.account.delete.notok", function () {

                    });
                    mzModal.show('<div class="mz-modal-password-changed">Could not delete account! Refresh the page and try again.</div>', 'account.delete.notok');
                }
                if (response.Status === 401) {
                    mzModal.init();
                    mzModal.$el.one("mz.modal.hidden.account.delete.unauthorized", function () {

                    });
                    mzModal.show('<div class="mz-modal-unauthorized">Unauthorized! Refresh the page.</div>', 'delete.account.unauthorized');
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
                    mzModal.init();
                    mzModal.$el.one("mz.modal.hidden.account.update.ok", function () {
                        _this.getMembershipForm();
                    });
                    mzModal.show('<div class="mz-modal-password-changed">Your profile updated successfully!</div>', 'account.update.ok');
                }
                if (response.Status === 400) {
                    mzModal.init();
                    mzModal.$el.one("mz.modal.hidden.account.update.error", function () {
                        //
                    });
                    mzModal.show('<div class="mz-modal-password-changed">Please fix the errors with highlighted fields!</div>', 'account.update.error');
                    for (var fieldName in response.Errors) {
                        _this.showFieldError(fieldName, response.Errors[fieldName], $form);
                    }
                }
                if (response.Status === 401) {
                    mzModal.init();
                    mzModal.$el.one("mz.modal.hidden.account.update.unauthorized", function () {
                        //may be refresh here
                    });
                    mzModal.show('<div class="mz-modal-password-changed">Unauthorized! Refresh the page.</div>', 'account.update.unauthorized');
                }
            });
            _this.request(this.methods.submitMemberShipForm, $form, $form.serialize());
        },
        getMembershipForm: function () {
            var _this = this;
            var $el = $(".easl-mz-membership-inner");
            if ($el.length) {
                $(".mz-expiring-message-wrap").addClass('mz-banner-loading');
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
                    if ((response.Status === 200) && response.Data && response.Data.banner) {
                        _this.loadBanner(response.Data.banner);
                    }
                    _this.membershipFormEvents($el);
                });
                this.request(this.methods.membershipForm, $el);
            }
        },
        loadBanner: function (msg) {
            var $wrap = $(".mz-expiring-message-wrap");

            if (msg) {
                $wrap.find('.mz-expiring-message').html(msg);
                $wrap.addClass('mz-banner-loaded easl-active').removeClass('mz-banner-loading');
                $(".mz-expiring-message-close", $wrap).one("click", function (event) {
                    event.preventDefault();
                    $wrap.removeClass("easl-active");
                });
            }
        },
        getMembershipBanner: function () {
            var _this = this;
            var $el = $(".mz-expiring-message-wrap");
            if ($el.length && !($el.hasClass('mz-banner-loaded') || $el.hasClass('mz-banner-loading'))) {
                $el.on("mz_loaded:" + this.methods.membershipBanner, function (event, response, method) {
                    if ((response.Status === 200) && response.Html) {
                        _this.loadBanner(response.Html);
                    }
                });
                this.request(this.methods.membershipBanner, $el);
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
                    mzModal.init();
                    mzModal.$el.one("mz.modal.hidden.account.create.unauthorized", function () {
                        //may be refresh here
                    });
                    mzModal.show('<div class="mz-modal-unauthorized">Unauthorized! Refresh the page.</div>', 'account.create.unauthorized');
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
                $(".mz-expiring-message-wrap").addClass('mz-banner-loading');
                $el.on("mz_loaded:" + this.methods.newMembershipForm, function (event, response, method) {
                    _this.loadHtml($(this), response);
                    $("body").trigger("mz_reload_custom_fields");
                    $(".easl-mz-select2", $(this)).select2({
                        closeOnSelect: true,
                        allowClear: true
                    });
                    _this.customFileInput($el);
                    _this.newMembershipFormEvents($el);
                    if ((response.Status === 200) && response.Data && response.Data.banner) {
                        _this.loadBanner(response.Data.banner);
                    }
                });
                this.request(this.methods.newMembershipForm, $el, {
                    'renew': $el.data('paymenttype'),
                    'messages': _this.messages
                });
            }
        },
        getMemberDirectory: function ($el) {
            var _this = this;
            var search = $("#mz-md-filter-search", $el).val();
            var country = $("#mz-md-filter-country", $el).val();
            var speciality = $("#mz-md-filter-spec", $el).val();
            var letter = $("#mz-md-filter-letter", $el).val();
            var page = $("#mz-md-filter-page", $el).val();

            if (typeof _this.memberDirectoryRequest === 'object') {
                _this.memberDirectoryRequest.abort();
            }
            $el.addClass("mz-md-loading");
            _this.memberDirectoryRequest = _this.request(this.methods.getMemberDirectory, $el, {
                "search": search,
                "country": country,
                "speciality": speciality,
                "letter": letter,
                "page_offset": page
            });
        },
        initMemberDirectory: function () {
            var _this = this;
            var $el = $(".easl-mz-directory-inner");
            if ($el.length) {
                $el.on("mz_loaded:" + this.methods.getMemberDirectory, function (event, response, method) {
                    if (response.Status === 200) {
                        $(".easl-mz-members-direcoty-content-inner", $el).html(response.Html);
                    } else if (response.Status === 404) {
                        $(".easl-mz-members-direcoty-content-inner", $el).html("No members found!");
                    } else if (response.Status === 401) {
                        // TODO-maybe reload
                    }
                    $el.removeClass("mz-md-loading");
                });
                $el.on("click", ".easl-mz-clear-filters", function (event) {
                    event.preventDefault();
                    $("#mz-md-filter-search", $el).val("");
                    $("#mz-md-filter-letter", $el).val("");
                    $("#mz-md-filter-page", $el).val("");
                    $(".easl-mz-select2", $el).val(null).trigger('change');
                    _this.getMemberDirectory($el);
                });
                $el.on("keyup", "#mz-md-filter-search", function (event) {
                    if (event.which === 13) {
                        $("#mz-md-filter-page", $el).val("");
                        _this.getMemberDirectory($el);
                    }
                });
                $el.on("click", "#mz-search-trigger", function (event) {
                    event.preventDefault();
                    $("#mz-md-filter-page", $el).val("");
                    _this.getMemberDirectory($el);
                });
                $el.on("click", ".easl-mz-letter-filter a", function (event) {
                    event.preventDefault();
                    $("#mz-md-filter-page", $el).val("");
                    if (!$(this).hasClass("mz-active")) {
                        $("#mz-md-filter-letter", $el).val($(this).data('value'));
                        $(this).siblings("mz-active").removeClass("mz-active");
                        $(this).addClass("mz-active");
                    } else {
                        $("#mz-md-filter-letter", $el).val("");
                        $(this).removeClass("mz-active");
                    }
                    _this.getMemberDirectory($el);
                });
                $("#mz-md-filter-country", $el).on("change.select2", function (event) {
                    $("#mz-md-filter-page", $el).val("");
                    _this.getMemberDirectory($el);
                });
                $("#mz-md-filter-spec", $el).on("change.select2", function (event) {
                    $("#mz-md-filter-page", $el).val("");
                    _this.getMemberDirectory($el);
                });
                $el.on("click", ".easl-mz-pagination-list a", function (event) {
                    event.preventDefault();
                    $("#mz-md-filter-page", $el).val($(this).text());
                    _this.getMemberDirectory($el);
                });
                _this.getMemberDirectory($el);
            }
        },
        getMemberDocuments: function () {
            var _this = this;
            var $el = $(".easl-mz-mydocs-inner");
            var memberships = [];
            if ($el.length) {
                var $table = $(".mzmd-docs-table", $el);
                $el.on("mz_loaded:" + this.methods.getMembershipNotes, function (event, response, method) {
                    if (response.Status === 200) {
                        $table.append(response.Html);
                    } else if (response.Status === 404) {

                    } else if (response.Status === 401) {
                        // TODO-maybe reload
                    }
                    if (memberships.length > 0) {
                        _this.request(_this.methods.getMembershipNotes, $el, {
                            'membership': memberships.shift()
                        });
                    } else {
                        $el.removeClass("mz-docs-loading");
                    }
                });
                $el.on("mz_loaded:" + this.methods.getMembersMembership, function (event, response, method) {
                    if (response.Status === 200) {
                        memberships = response.Data;
                        if (memberships.length) {
                            _this.request(_this.methods.getMembershipNotes, $el, {
                                'membership': memberships.shift()
                            });
                        }
                    } else if (response.Status === 404) {
                        $table.html("You don't have any documents.");
                    } else if (response.Status === 401) {
                        // TODO-maybe reload
                    }
                });
                this.request(this.methods.getMembersMembership, $el);
            }
        },
        request: function (method, $el, reqData) {
            reqData = reqData || {};
            return $.ajax({
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
        getMemberDetailsCon: function () {
            var $con = $(".easl-mz-container-inner .easl-mz-member-profile-wrap");
            if ($con.length === 0) {
                $con = $('<div class="easl-mz-member-profile-wrap"><div class="easl-mz-member-profile-con"><div class="easl-mz-back-link-wrap"><a class="easl-mz-back-link mz-member-profile-back" href="#">Back</a></div><div class="easl-easl-mz-member-profile"></div></div>' + this.loaderHtml + '</div>');
                $(".easl-mz-container-inner").append($con);
            }
            return $con;
        },
        events: function () {
            var _this = this;
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

            $("body").on("click", ".mz-member-details-trigger", function (event) {
                var $con = _this.getMemberDetailsCon();
                var $conWrap = $(".easl-mz-container-inner");
                var id = $(this).data("memberid");
                event.preventDefault();
                $con.find(".easl-easl-mz-member-profile").html("");
                $con.addClass("mz-mpd-loading");
                $conWrap.addClass("easl-mz-mp-show-details").data('easlscrollpos', document.documentElement.scrollTop);
                $('html, body').animate({
                    scrollTop: $conWrap.offset().top - $('#site-header').height() - 100
                }, 275);
                $con.one("mz_loaded:" + _this.methods.getMemberDetails, function (event, response, method) {

                    if (response.Status === 200) {
                        $con.find(".easl-easl-mz-member-profile").html(response.Html);
                    } else if (response.Status === 404) {
                        $(".easl-mz-members-direcoty-content-inner", $el).html("Member not found!");
                    } else if (response.Status === 401) {
                        // TODO-maybe reload
                    }
                    $con.removeClass("mz-mpd-loading");
                });
                _this.request(_this.methods.getMemberDetails, $con, {
                    'member_id': id
                });
            });
            $("body").on("click", ".mz-member-profile-back", function (event) {
                var $conWrap = $(".easl-mz-container-inner");
                event.preventDefault();
                $conWrap.removeClass("easl-mz-mp-show-details");
                var scrollPosition = $conWrap.data('easlscrollpos') || false;
                if (scrollPosition) {
                    $('html, body').animate({
                        scrollTop: scrollPosition
                    }, 275);
                }
                $conWrap.data('easlscrollpos', false);
            });
        },
        loadModules: function () {
            this.modulesLoadTrigger = true;
            this.getFeaturedMembers();
            this.getMembershipForm();
            this.getMemberDocuments();
            this.getNewMembershipForm();
            this.getMembershipBanner();
            this.initMemberDirectory();
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
        if (typeof $.fn.select2 !== "undefined") {
            $(".easl-mz-select2").length && $(".easl-mz-select2").select2({
                closeOnSelect: true,
                allowClear: true
            });
        }

        easlMemberZone.init();
    });

})(jQuery);