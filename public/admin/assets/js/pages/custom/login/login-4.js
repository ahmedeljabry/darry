"use strict";

// Class Definition
var KTLogin = (function () {
    var _buttonSpinnerClasses = "spinner spinner-right spinner-white pr-15";

    var _handleFormSignin = function () {
        var form = KTUtil.getById("kt_login_singin_form");
        var formSubmitUrl = KTUtil.attr(form, "action");
        var formSubmitButton = KTUtil.getById(
            "kt_login_singin_form_submit_button"
        );

        if (!form) {
            return;
        }

        FormValidation.formValidation(form, {
            fields: {
                email: {
                    validators: {
                        notEmpty: {
                            message: "البريد الإلكتروني مطلوب",
                        },
                        emailAddress: {
                            message: "صيغة البريد الإلكتروني غير صحيحة",
                        },
                    },
                },
                password: {
                    validators: {
                        notEmpty: {
                            message: "كلمة المرور مطلوبة",
                        },
                    },
                },
            },
            plugins: {
                trigger: new FormValidation.plugins.Trigger(),
                submitButton: new FormValidation.plugins.SubmitButton(),
                //defaultSubmit: new FormValidation.plugins.DefaultSubmit(), // Uncomment this line to enable normal button submit after form validation
                bootstrap: new FormValidation.plugins.Bootstrap({
                    //	eleInvalidClass: '', // Repace with uncomment to hide bootstrap validation icons
                    //	eleValidClass: '',   // Repace with uncomment to hide bootstrap validation icons
                }),
            },
        })
            .on("core.form.valid", function () {
                // Show loading state on button
                KTUtil.btnWait(
                    formSubmitButton,
                    _buttonSpinnerClasses,
                    "من فضلك انتظر"
                );
                // Submit via AJAX to get JSON response
                var fd = new FormData(form);
                fetch(formSubmitUrl, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: fd
                })
                .then(function(res){
                    return res.json().then(function(data){ return { ok: res.ok, data: data }; });
                })
                .then(function(result){
                    KTUtil.btnRelease(formSubmitButton);
                    if (result.ok && result.data && result.data.status === 'success') {
                        var redirect = result.data.redirect || '/admin';
                        window.location.href = redirect;
                    } else {
                        var msg = (result.data && (result.data.message || result.data.error)) || 'حدث خطأ، حاول مرة أخرى.';
                        Swal.fire({
                            text: msg,
                            icon: 'error',
                            buttonsStyling: false,
                            confirmButtonText: 'حسنًا',
                            customClass: { confirmButton: 'btn font-weight-bold btn-light-primary' }
                        }).then(function(){ KTUtil.scrollTop(); });
                    }
                })
                .catch(function(){
                    KTUtil.btnRelease(formSubmitButton);
                    Swal.fire({
                        text: 'تعذر الاتصال بالخادم. حاول لاحقًا.',
                        icon: 'error',
                        buttonsStyling: false,
                        confirmButtonText: 'حسنًا',
                        customClass: { confirmButton: 'btn font-weight-bold btn-light-primary' }
                    });
                });
            })
            .on("core.form.invalid", function () {
                Swal.fire({
                    text: "عذرًا، هناك أخطاء في الإدخال. حاول مرة أخرى.",
                    icon: "error",
                    buttonsStyling: false,
                    confirmButtonText: "حسنًا",
                    customClass: {
                        confirmButton: "btn font-weight-bold btn-light-primary",
                    },
                }).then(function () {
                    KTUtil.scrollTop();
                });
            });
    };

    var _handleFormForgot = function () {
        var form = KTUtil.getById("kt_login_forgot_form");
        var formSubmitUrl = KTUtil.attr(form, "action");
        var formSubmitButton = KTUtil.getById(
            "kt_login_forgot_form_submit_button"
        );

        if (!form) {
            return;
        }

        FormValidation.formValidation(form, {
            fields: {
                email: {
                    validators: {
                        notEmpty: {
                            message: "Email is required",
                        },
                        emailAddress: {
                            message: "The value is not a valid email address",
                        },
                    },
                },
            },
            plugins: {
                trigger: new FormValidation.plugins.Trigger(),
                submitButton: new FormValidation.plugins.SubmitButton(),
                //defaultSubmit: new FormValidation.plugins.DefaultSubmit(), // Uncomment this line to enable normal button submit after form validation
                bootstrap: new FormValidation.plugins.Bootstrap({
                    //	eleInvalidClass: '', // Repace with uncomment to hide bootstrap validation icons
                    //	eleValidClass: '',   // Repace with uncomment to hide bootstrap validation icons
                }),
            },
        })
            .on("core.form.valid", function () {
                // Show loading state on button
                KTUtil.btnWait(
                    formSubmitButton,
                    _buttonSpinnerClasses,
                    "من فضلك انتظر"
                );

                // Simulate Ajax request
                setTimeout(function () {
                    KTUtil.btnRelease(formSubmitButton);
                }, 2000);
            })
            .on("core.form.invalid", function () {
                Swal.fire({
                    text: "Sorry, looks like there are some errors detected, please try again.",
                    icon: "error",
                    buttonsStyling: false,
                    confirmButtonText: "Ok, got it!",
                    customClass: {
                        confirmButton: "btn font-weight-bold btn-light-primary",
                    },
                }).then(function () {
                    KTUtil.scrollTop();
                });
            });
    };

    var _handleFormSignup = function () {
        // Base elements
        var wizardEl = KTUtil.getById("kt_login");
        var form = KTUtil.getById("kt_login_signup_form");
        var wizardObj;
        var validations = [];

        if (!form) {
            return;
        }

        // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
        // Step 1
        validations.push(
            FormValidation.formValidation(form, {
                fields: {
                    fname: {
                        validators: {
                            notEmpty: {
                                message: "First name is required",
                            },
                        },
                    },
                    lname: {
                        validators: {
                            notEmpty: {
                                message: "Last Name is required",
                            },
                        },
                    },
                    phone: {
                        validators: {
                            notEmpty: {
                                message: "Phone is required",
                            },
                        },
                    },
                    email: {
                        validators: {
                            notEmpty: {
                                message: "Email is required",
                            },
                            emailAddress: {
                                message:
                                    "The value is not a valid email address",
                            },
                        },
                    },
                },
                plugins: {
                    trigger: new FormValidation.plugins.Trigger(),
                    // Bootstrap Framework Integration
                    bootstrap: new FormValidation.plugins.Bootstrap({
                        //eleInvalidClass: '',
                        eleValidClass: "",
                    }),
                },
            })
        );

        // Step 2
        validations.push(
            FormValidation.formValidation(form, {
                fields: {
                    address1: {
                        validators: {
                            notEmpty: {
                                message: "Address is required",
                            },
                        },
                    },
                    postcode: {
                        validators: {
                            notEmpty: {
                                message: "Postcode is required",
                            },
                        },
                    },
                    city: {
                        validators: {
                            notEmpty: {
                                message: "City is required",
                            },
                        },
                    },
                    state: {
                        validators: {
                            notEmpty: {
                                message: "State is required",
                            },
                        },
                    },
                    country: {
                        validators: {
                            notEmpty: {
                                message: "Country is required",
                            },
                        },
                    },
                },
                plugins: {
                    trigger: new FormValidation.plugins.Trigger(),
                    // Bootstrap Framework Integration
                    bootstrap: new FormValidation.plugins.Bootstrap({
                        //eleInvalidClass: '',
                        eleValidClass: "",
                    }),
                },
            })
        );

        // Initialize form wizard
        wizardObj = new KTWizard(wizardEl, {
            startStep: 1, // initial active step number
            clickableSteps: false, // allow step clicking
        });

        // Validation before going to next page
        wizardObj.on("change", function (wizard) {
            if (wizard.getStep() > wizard.getNewStep()) {
                return; // Skip if stepped back
            }

            // Validate form before change wizard step
            var validator = validations[wizard.getStep() - 1]; // get validator for currnt step

            if (validator) {
                validator.validate().then(function (status) {
                    if (status == "Valid") {
                        wizard.goTo(wizard.getNewStep());

                        KTUtil.scrollTop();
                    } else {
                        Swal.fire({
                            text: "Sorry, looks like there are some errors detected, please try again.",
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn font-weight-bold btn-light",
                            },
                        }).then(function () {
                            KTUtil.scrollTop();
                        });
                    }
                });
            }

            return false; // Do not change wizard step, further action will be handled by he validator
        });

        // Change event
        wizardObj.on("changed", function (wizard) {
            KTUtil.scrollTop();
        });

        // Submit event
        wizardObj.on("submit", function (wizard) {
            Swal.fire({
                text: "All is good! Please confirm the form submission.",
                icon: "success",
                showCancelButton: true,
                buttonsStyling: false,
                confirmButtonText: "Yes, submit!",
                cancelButtonText: "No, cancel",
                customClass: {
                    confirmButton: "btn font-weight-bold btn-primary",
                    cancelButton: "btn font-weight-bold btn-default",
                },
            }).then(function (result) {
                if (result.value) {
                    form.submit(); // Submit form
                } else if (result.dismiss === "cancel") {
                    Swal.fire({
                        text: "Your form has not been submitted!.",
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn font-weight-bold btn-primary",
                        },
                    });
                }
            });
        });
    };

    // Public Functions
    return {
        init: function () {
            _handleFormSignin();
            _handleFormForgot();
            _handleFormSignup();
        },
    };
})();

// Class Initialization
jQuery(document).ready(function () {
    KTLogin.init();
});
