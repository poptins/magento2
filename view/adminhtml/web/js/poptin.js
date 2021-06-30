/*Poptin Popup functions*/
function openPoptinModal(popupid) {

    var body = document.body;
    body.classList.add("modal-open");
    document.getElementById(popupid).style.display = "block";

    var element, name, arr;
    element = document.getElementById(popupid);
    name = "in";
    arr = element.className.split(" ");
    if (arr.indexOf(name) == -1) {
        element.className += " " + name;
    }
}
// Close the Modal
function closePoptinModal(popupid) {
    var body = document.body;
    body.classList.remove("modal-open");
    document.getElementById(popupid).style.display = "none";

    var element = document.getElementById(popupid);
    element.className = element.className.replace(/\bin\b/g, "");
}

define([
    'jquery'
], function($) {
    "use strict";

    return function(config) {
        jQuery(document).ready(function($) {
            jQuery(".where-is-my-id-inside-lb").bind('click', function(e) {
                closePoptinModal('oopsiewrongid');
                openPoptinModal('whereIsMyId');
            });

            function show_loader() {
                jQuery(".poptin-overlay").fadeIn(500);
            }

            function hide_loader() {
                jQuery(".poptin-overlay").fadeOut(500);
            }

            jQuery(".pp_signup_btn").on('click', function(e) {
                e.preventDefault();
                var email = jQuery("#email").val();
                if (!isEmail(email)) {
                    e.preventDefault();
                    openPoptinModal("oopsiewrongemailid");
                    return false;
                } else {
                    jQuery("#registration_form").submit();
                }
            });

            jQuery('.goto_dashboard_button_pp_updatable').click(function() {
                link = jQuery(this);
                href = link.attr('href');
                setTimeout(function() {
                    link.attr('href', href.replace('&after_registration=wordpress', ''));
                }, 1000);
            });

            jQuery(document).on('click', '.deactivate-poptin-confirm-yes', function() {
                window.location.href = config.poptinDeactivatePluginUrl;

            });

            jQuery(".pplogout").click(function(e) {
                e.preventDefault();
                openPoptinModal("makingsure");
            });

            jQuery(".ppLogin").click(function(e) {
                e.preventDefault();
                jQuery(".popotinLogin").fadeIn('slow');
                jQuery(".popotinRegister").hide();
            });

            jQuery(".ppRegister").click(function(e) {
                e.preventDefault();
                jQuery(".popotinRegister").fadeIn('slow');
                jQuery(".popotinLogin").hide();
            });

            jQuery('.poptin_submit_button').on('click', function(e) {
                e.preventDefault();
                var id = jQuery('.ppFormLogin input[type="text"]').val();
                if (id.length != 13) {
                    e.preventDefault();
                    openPoptinModal("oopsiewrongid");

                    return false;
                } else {
                    jQuery("#map_poptin_id_form").submit();

                }
            });

            jQuery(".parrot").hover(
                function() {
                    jQuery(this).attr("src", config.parrotGifUrl);
                },
                function() {
                    jQuery(this).attr("src", config.parrotPngUrl);
                });


            jQuery('.poptin-help-tooltip').on('click', function(e) {
                e.preventDefault();
                if (jQuery(".poptin-help-form").is(":hidden")) {
                    jQuery(".poptin-help-form").show();
                } else {
                    jQuery(".poptin-help-form").hide();
                }
            });


            jQuery('#poptin-help-form').on('submit', function(e) {
                var user_email = jQuery("#user_email").val();
                var textarea_text = jQuery("#textarea_text").val();
                var poptin_chat_key = jQuery("#poptin_chat_key").val();
                var poptin_web_address = jQuery("#poptin_web_address").val();
                var error = [];
                var error_msg = "";
                if (user_email == "") {
                    error.push('1');
                    error_msg += '<p class="fm_error">Please Enter Your Email</p>';
                } else if (!isEmail(user_email)) {
                    error.push('1');
                    error_msg += '<p class="fm_error">Please Enter Valid Email Address</p>';
                }
                if (textarea_text == "") {
                    error.push('1');
                    error_msg += '<p class="fm_error">Please Write Your Message</p>';
                }
                if (error.length > 0) {
                    jQuery("#poptin_err_msg").html(error_msg);
                    return false;
                } else {
                    jQuery("#poptin_err_msg").html("");
                    var url = config.poptinAjaxUrl;
                    var posdata = {
                        "user_email": user_email,
                        "textarea_text": textarea_text,
                        "form_key": poptin_chat_key,
                        "web_address": poptin_web_address
                    };
                    jQuery.ajax({
                        type: "POST",
                        url: url,
                        dataType: 'JSON',
                        data: posdata,
                        success: function(d) {
                            if (d.result == 'success') {
                                jQuery("#poptin_success_msg").html('<p>' + d.msg + '</p>');
                                jQuery('#poptin-help-form')[0].reset();
                            } else {
                                jQuery("#poptin_err_msg").html('<p class="fm_error">' + d.msg + '</p>');
                            }
                        }
                    });
                }
                return false;
            });

            jQuery('.poptin-form-elem input:radio').click(function() {
                if (jQuery('.poptin-form-elem input:radio:checked').length > 0) {
                    jQuery(".plation-item").removeClass("opt--selected");
                    jQuery(this).parent(".plation-item").addClass("opt-selected");
                    var datacode = jQuery(this).attr("data-code");
                    jQuery("#poptin-pixelcode").val(datacode);
                }
            });

        });


        function isEmail(email) {
            var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
            return regex.test(email);
        }

    }
});