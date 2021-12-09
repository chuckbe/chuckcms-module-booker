<script src="{{ asset('module-booker/scripts/splide.min.js') }}"></script>
<script src="{{ asset('module-booker/scripts/mailcheck.min.js') }}"></script>
<script src="{{ asset('module-booker/scripts/intlTelInput-jquery.min.js') }}"></script>

<script type="text/javascript">
$(document).ready(function (event) {
    var session_token = "{{Session::token()}}";
    var get_available_dates_url = "{{route('module.booker.get_available_dates')}}";
    var make_subscription_url = "{{route('module.booker.subscribe')}}";
    var make_login_url = "{{route('login.post')}}";
    var auth_check = ("{{ Auth::check() }}" == 1) ? true : false;
    

    $('input[type="tel"]').intlTelInput({
        initialCountry: "be",
        onlyCountries: ["be", "lu", "nl"],
        utilsScript: "{{ asset('module-booker/scripts/intlTelInput-utils.js') }}"
    });

    Mailcheck.defaultDomains.push('chuck.be', 'live.com', 'live.be', 'outlook.com', 'outlook.be', 'msn.be', 'hotmail.be', 'hotmail.com', 'gmail.com', 'aol.com') // extend existing domains
    Mailcheck.defaultSecondLevelDomains.push('msn', 'live', 'gmail', 'outlook', 'hotmail', 'yahoo', 'aol', 'gmx') // extend existing SLDs
    Mailcheck.defaultTopLevelDomains.push("be", "nl", "fr", "co.uk", "de", "com", "net", "org") // extend existing TLDs

    $('input[type="email"]').on('blur', function() {
        let emailInput = $(this);

        var emailRegex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        if (!emailRegex.test(emailInput.val())) {
            emailInput.siblings('.cmb_email_correction').first().removeClass('d-none');
            return;
        } else {
            emailInput.siblings('.cmb_email_correction').first().addClass('d-none');
        }

        if (emailInput.val().includes('@') &&  emailInput.val().split('@')[1].includes('.')) {
            $(this).mailcheck({
                suggested: function(element, suggestion) {
                    emailInput.siblings('.cmb_email_suggestion').first().removeClass('d-none');
                    emailInput.siblings('.cmb_email_suggestion').first().find('.cmb_email_suggestion_link').text(suggestion.full);
                },
                empty: function(element) {
                  emailInput.siblings('.cmb_email_suggestion').first().addClass('d-none');
                }
            });
        } else {
            emailInput.siblings('.cmb_email_suggestion').first().addClass('d-none');
        }
    });

    $('body').on('click', '.cmb_email_suggestion_link', function (event) {
        event.preventDefault();

        let emailSuggestion = $(this).text();
        let emailInput = $(this).parent().siblings('input').first().val(emailSuggestion);

        $(this).parent().addClass('d-none');
        emailInput.trigger('blur');
    });

    $('body').on('keyup', '#cmb_customer_password', function (event) {
        indicatePasswordStrength();
    });

    $('body').on('keyup', '#password-confirm', function (event) {
        indicatePasswordStrength();
    });


    $('body').on('change', 'form.cmb_booker_subscription_app input[name="cmb_subscription_plan"]', function (event) {
        hideConfirmation();

        if ($('input[name="cmb_subscription_plan"]:checked').length == 0) {
            enableAllPlans();
            return;
        } else {
            disablePlansButSelected();
        }

        showConfirmation();
    });

    $('body').on('click', '.cmb_open_login_modal', function (event) {
        event.preventDefault();

        $('#cmb_login_modal').modal('show');
    });

    $('body').on('click', '#cmb_login_modal_confirm_btn', function (event) {
        event.preventDefault();

        $(this).prop('disabled', true);
        $(this).text('Even geduld...');

        if (validateLoginForm()) {
            makeLogin().done(function (data) {
                handleLoginResponse(data);
            });
        }
    });

    $('body').on('click', '.cmb_show_general_conditions_btn', function (event) {
        event.preventDefault();

        $('#generalConditionsModal').modal('show');
    });

    $('body').on('click', '.cmb_show_medical_declaration_btn', function (event) {
        event.preventDefault();

        $('#medicalDeclarationModal').modal('show');
    });

    $('body').on('click', '.cmb_general_conditions_modal_btn', function (event) {
        event.preventDefault();

        $('form.cmb_booker_subscription_app input[name="general_conditions"]').prop('checked', true);
        $('#generalConditionsModal').modal('hide');
    });

    $('body').on('click', '.cmb_medical_declaration_modal_btn', function (event) {
        event.preventDefault();

        $('form.cmb_booker_subscription_app input[name="medical_declaration"]').prop('checked', true);
        $('#medicalDeclarationModal').modal('hide');
    });

    $('body').on('click', 'form.cmb_booker_subscription_app #cmb_confirmation_booker_btn', function (event) {
        event.preventDefault();

        $(this).prop('disabled', true);
        $(this).text('Even geduld...');

        if (validateForm()) {
            makeSubscription().done(function (data) {
                handleResponseFromMakeSubscription(data);
            });
        } else {
            $(this).prop('disabled', false);
            $(this).text('Betalen & Bevestigen');
        }
    });


    

    function enableAllPlans() {
        $('input[name="cmb_subscription_plan"]').prop('disabled', false);
    }

    function disablePlansButSelected() {
        $('input[name="cmb_subscription_plan"]:not(:checked)').each(function (item) {
            $(this).prop('disabled', true);    
        });
    }

    

    function getSelectedLocationId() {
        return $('input[name=cmb_location]').val();
    }

    function getSelectedLocationMaxWeight() {
        return parseInt($('input[name=cmb_location]').attr('data-max-weight'));
    }

    function getSelectedServicesIds() {
        let array = [];
        $('input[name=cmb_services]:checked:not(:disabled)').each(function () {
            array.push($(this).val());
        });

        return array;
    }

    function showLoadingMessage() {
        $('.cmb_services_loading_message').removeClass('d-none');
    }

    function hideLoadingMessage() {
        $('.cmb_services_loading_message').addClass('d-none');
    }

    function showLoadingErrorMessage() {
        $('.cmb_services_loading_error_message').removeClass('d-none');
    }

    function hideLoadingErrorMessage() {
        $('.cmb_services_loading_error_message').addClass('d-none');
    }

    function showConfirmation() {
        fillConfirmation();
        $('.cmb_confirmation_wrapper').removeClass('d-none');
    }

    function fillConfirmation() {
        let sub_plan = getSelectedSubscriptionPlan();
        let plan_name = sub_plan.name;
        $('.cmb_confirmation_sub_plan_text').text(plan_name);

        $('.cmb_confirmation_type_text').text(getSubPlanTypeReadable(sub_plan.type));
        $('.cmb_confirmation_price_text').text(getSubPlanPriceReadable(parseFloat(sub_plan.price))+' EUR');

        if (sub_plan.type !== 'one-off') {
            $('.cmb_confirmation_recurring_text').removeClass('d-none');
        }
    }

    function hideConfirmation() {
        $('.cmb_confirmation_wrapper').addClass('d-none');
    }

    function getSelectedSubscriptionPlan() {
        let subscriptionEl = $('input[name=cmb_subscription_plan]:checked');
        
        return {
            id: subscriptionEl.val(),
            name: subscriptionEl.attr('data-name'),
            type: subscriptionEl.attr('data-type'),
            weight: parseInt(subscriptionEl.attr('data-weight')),
            price: subscriptionEl.attr('data-price'),
            description: subscriptionEl.attr('data-description')
        };
    }

    function getSubPlanTypeReadable(type) {
        if (type == 'one-off') {
            return 'Eenmalig';
        }

        if (type == 'weekly') {
            return 'Wekelijks';
        }

        if (type == 'monthly') {
            return 'Maandelijks';
        }

        if (type == 'quarterly') {
            return 'Driemaandelijks';
        }

        if (type == 'yearly') {
            return 'Jaarlijks';
        }

        return '';
    }

    function getSubPlanPriceReadable(price) {
        return Math.round((price + Number.EPSILON) * 100) / 100;
    }

    function validateForm() {
        $('.cmb_confirmation_error_msg').text('');

        let first_name = $('form.cmb_booker_subscription_app input[name="first_name"]').val();
        let last_name = $('form.cmb_booker_subscription_app input[name="last_name"]').val();
        let email = $('form.cmb_booker_subscription_app input[name="email"]').val();
        let tel = $('form.cmb_booker_subscription_app input[name="tel"]').intlTelInput("getNumber");

        if (first_name.length == 0) {
            $('.cmb_confirmation_error_msg').text('Gelieve een voornaam in te vullen.');
            return false;
        }

        if (last_name.length == 0) {
            $('.cmb_confirmation_error_msg').text('Gelieve een achternaam in te vullen.');
            return false;
        }

        let emailRegex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        if (!emailRegex.test(email)) {
            $('.cmb_confirmation_error_msg').text('Gelieve een correct e-mailadres in te vullen.');
            return false;
        }

        if (!$('form.cmb_booker_subscription_app input[name="tel"]').intlTelInput("isValidNumber")) {
            $('.cmb_confirmation_error_msg').text('Gelieve een correct telefoonnummer in te vullen.');
            return false;
        }

        if ($('form.cmb_booker_subscription_app input[name="create_customer"]').is(':checked')) {
            let pwd = $('form.cmb_booker_subscription_app input[name="password"]').val();
            let pwd_check = $('form.cmb_booker_subscription_app input[name="password_confirmation"]').val();

            if (pwd.length == 0) {
                $('.cmb_confirmation_error_msg').text('Gelieve een wachtwoord in te vullen.');
                return false;
            }

            if (pwd_check.length == 0) {
                $('.cmb_confirmation_error_msg').text('Gelieve uw wachtwoord opnieuw in te vullen.');
                return false;
            }

            if (pwd_check != pwd) {
                $('.cmb_confirmation_error_msg').text('De ingevulde wachtwoorden komen niet overeen.');
                return false;
            }

            if (pwd.length < 8) {
                $('.cmb_confirmation_error_msg').text('Uw wachtwoord moet minstens 8 tekens bevatten.');
                return false;
            }
            
            let spec_chars = /[ `!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?~]/;

            if (!spec_chars.test(pwd)) {
                $('.cmb_confirmation_error_msg').text('Uw wachtwoord moet minstens 1 speciaal teken bevatten.');
                return false;
            }

            if (!new RegExp("^(?=.*\\d).+$").test(pwd)) {
                $('.cmb_confirmation_error_msg').text('Uw wachtwoord moet cijfers bevatten.');
                return false;
            }

            if (!new RegExp("^(?=.*[a-z]).+$").test(pwd)) {
                $('.cmb_confirmation_error_msg').text('Uw wachtwoord moet kleine letters bevatten.');
                return false;
            }

            if (!new RegExp("^(?=.*[A-Z]).+$").test(pwd)) {
                $('.cmb_confirmation_error_msg').text('Uw wachtwoord moet hoofdletters bevatten.');
                return false;
            }
        }

        let general_conditions = $('form.cmb_booker_subscription_app input[name="general_conditions"]')
                                        .is(':checked');
        let medical_declaration = $('form.cmb_booker_subscription_app input[name="medical_declaration"]')
                                        .is(':checked');
        
        if (!general_conditions) {
            $('.cmb_confirmation_error_msg').text('Gelieve de algemene voorwaarden te aanvaarden.');
            return false;
        }

        if (!medical_declaration) {
            $('.cmb_confirmation_error_msg').text('Gelieve akkoord te gaan met de medische verklaring.');
            return false;
        }

        return true;
    }

    function makeSubscription() {
        let subscription_plan = getSelectedSubscriptionPlan().id;
        let customer = null;
        let create_customer = $('form.cmb_booker_subscription_app input[name="create_customer"]').is(':checked') ? 1 : 0;

        let pwd = $('form.cmb_booker_subscription_app input[name="password"]').val();
        let pwd_check = $('form.cmb_booker_subscription_app input[name="password_confirmation"]').val();

        if (auth_check) {
            customer = $('form.cmb_booker_subscription_app input[name="customer_id"]').val();
            create_customer = 0;
        }

        let first_name = $('form.cmb_booker_subscription_app input[name="first_name"]').val();
        let last_name = $('form.cmb_booker_subscription_app input[name="last_name"]').val();
        let email = $('form.cmb_booker_subscription_app input[name="email"]').val();
        let tel = $('form.cmb_booker_subscription_app input[name="tel"]').intlTelInput("getNumber");

        return $.ajax({
            method: 'POST',
            url: make_subscription_url,
            data: { 
                subscription_plan: subscription_plan, 
                customer: customer,
                create_customer: create_customer,
                password: pwd,
                password_confirmation: pwd_check,
                first_name: first_name,
                last_name: last_name,
                email: email,
                tel: tel,
                _token: session_token
            }
        });
    }

    function handleResponseFromMakeSubscription(data) {
        if (data.status == 'success') {
            window.location = data.redirect;
        }

        if (data.status == 'user_exists' || data.status == 'customer_exists') {
            $('.cmb_confirmation_error_msg').text('Er bestaat al een gebruiker met dit e-mailadres, gelieve aan te melden om een abonnement vast te leggen.');

            $('#cmb_confirmation_booker_btn').prop('disabled', false);
            $('#cmb_confirmation_booker_btn').text('Bevestigen');
        }

        if (data.status == 'error') {
            $('.cmb_confirmation_error_msg').text('Er is iets misgegaan. Probeer het later opnieuw. Als het probleem blijft bestaan gelieve contact op te nemen.');

            $('#cmb_confirmation_booker_btn').prop('disabled', false);
            $('#cmb_confirmation_booker_btn').text('Bevestigen');

        }
    }

    function validateLoginForm() {
        $('form.cmb_login_form small.error-msg').text('');

        let email = $('form.cmb_login_form input[name="email"]').val();
        let pwd = $('form.cmb_login_form input[name="password"]').val();

        if (!isValidEmail(email)) {
            $('form.cmb_login_form small.error-msg.error-email').text('Het opgegeven e-mailadres is ongeldig...');
            return false;
        }

        if (!isValidPwd(pwd)) {
            $('form.cmb_login_form small.error-msg.error-password').text('Het opgegeven wachtwoord is ongeldig...');
            return false;
        }

        return true;
    }

    function makeLogin() {
        let email = $('form.cmb_login_form input[name="email"]').val();
        let pwd = $('form.cmb_login_form input[name="password"]').val();

        return $.ajax({
            method: 'POST',
            url: make_login_url,
            data: { 
                email: email,
                password: pwd,
                _token: session_token
            }
        });
    }

    async function handleLoginResponse(data) {
        console.log('make login response data :: ', data);

        if (data.auth == true) {
            $('#cmb_login_modal_confirm_btn').text('U bent aangemeld...');

            await sleep(2000);

            $('form.cmb_login_form input[name="email"]').val('');
            $('form.cmb_login_form input[name="password"]').val('');
            $('form.cmb_login_form small.error-msg').text('');

            $('.cmb_confirmation_error_msg').text('');

            $('form.cmb_booker_subscription_app input[name="first_name"]').val(data.customer.first_name).prop('disabled', true);
            $('form.cmb_booker_subscription_app input[name="last_name"]').val(data.customer.last_name).prop('disabled', true);
            $('form.cmb_booker_subscription_app input[name="email"]').val(data.customer.email).prop('disabled', true);
            $('form.cmb_booker_subscription_app input[name="tel"]').val(data.customer.tel).prop('disabled', false);

            $('form.cmb_booker_subscription_app input[name="general_conditions"]').prop('checked', true).prop('disabled', true);
            $('form.cmb_booker_subscription_app input[name="medical_declaration"]').prop('checked', true).prop('disabled', true);

            $('form.cmb_booker_subscription_app input[name="customer_id"]').val(data.customer.id);

            $('form.cmb_booker_subscription_app label[for="cmb_create_customer"]').remove();

            $('form.cmb_booker_subscription_app .cmb_open_login_modal').hide();

            $('#cmb_login_modal').modal('hide');

            auth_check = true;
            session_token = data.token
        }

        if (data.auth == false) {
            
        }
    }

    function isValidEmail(email) {
        if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(email)) {
            return true;
        }
        
        return false;
    }

    function isValidPwd(pwd) {
        if (pwd.length > 5) {
            return true;
        }

        return false;
    }

    function sleep(ms) {
        return new Promise(resolve => setTimeout(resolve, ms));
    }

    function indicatePasswordStrength() {
        $('.cmb_create_customer_password_confirmation_check div')
            .first()
            .removeClass('bg-success')
            .removeClass('bg-danger');

        $('.cmb_create_customer_password_strenth div')
            .removeClass('bg-success')
            .removeClass('bg-warning');

        let checks = 0;
        let pwd = $('form.cmb_booker_subscription_app #cmb_customer_password').val();
        let pwd_check = $('form.cmb_booker_subscription_app #password-confirm').val();

        if (pwd.length > 8) {
            checks++;
        }
        
        let spec_chars = /[ `!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?~]/;

        if (spec_chars.test(pwd)) {
            checks++;
        }

        if (new RegExp("^(?=.*\\d).+$").test(pwd)) {
            checks++;
        }

        if (new RegExp("^(?=.*[A-Z]).+$").test(pwd)) {
            checks++;
        }

        if (new RegExp("^(?=.*[a-z]).+$").test(pwd)) {
            checks++;
        }

        for (var i = 0; i < checks; i++) {
            if (i == 3) {
                $('.cmb_create_customer_password_strenth div').eq(i).addClass('bg-warning');
            } else if (i == 4) {
                $('.cmb_create_customer_password_strenth div').eq((i-1)).removeClass('bg-warning').addClass('bg-success');
            } else {
                $('.cmb_create_customer_password_strenth div').eq(i).addClass('bg-success');
            }
        };

        if (pwd.length > 0 && pwd_check.length > 0 && pwd != pwd_check) {
            $('.cmb_create_customer_password_confirmation_check div').first().addClass('bg-danger');
        }

        if (pwd.length > 0 && pwd_check.length > 0 && pwd == pwd_check) {
            $('.cmb_create_customer_password_confirmation_check div').first().addClass('bg-success');
        }
    }
});
</script>