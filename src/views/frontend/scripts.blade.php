<script src="{{ asset('module-booker/scripts/splide.min.js') }}"></script>
<script src="{{ asset('module-booker/scripts/mailcheck.min.js') }}"></script>
<script src="{{ asset('module-booker/scripts/intlTelInput-jquery.min.js') }}"></script>


<script type="text/javascript">
$(document).ready(function (event) {
    var session_token = "{{Session::token()}}";
    var get_available_dates_url = "{{route('module.booker.get_available_dates')}}";
    var make_appointment_url = "{{route('module.booker.book')}}";
    var make_login_url = "{{route('login.post')}}";
    var auth_check = ("{{ Auth::check() }}" == 1) ? true : false;

@if(Auth::check() && Auth::user()->hasRole('customer'))
@php
$customer = \Chuckbe\ChuckcmsModuleBooker\Models\Customer::where('user_id', Auth::user()->id)->first();
@endphp
    var auth_available_weight = parseInt("{{ $customer->getAvailableWeight() }}");
    var auth_available_weight_not_on_days = ("{{ $customer->getDatesWhenAvailableWeightNotAvailable() }}").split(',');
@else
    var auth_available_weight = 0;
@endif
    
    var datePickerSlider = new Splide( '#splide', {
        perPage: 7,
        perMove: 6,
        // padding: {
        //     right: '1.5rem',
        //     left : '1.5rem',
        // },
        rewind : false,
        pagination: false,
        //isNavigation: true,
        focus: 'center',
        // height: '6rem',
        // autoWidth: true,
    }).mount();

    datePickerSlider.on( 'scroll', function () {
        console.log('are we scrolling ?');
    });

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


    $('body').on('change', 'form.cmb_booker_app input[name="cmb_services"]', function (event) {
        hideDatepicker();

        if ($('input[name="cmb_services"]:checked').length == 0) {
            enableAllServices();
            return;
        } else {
            disableServicesOnWeight();
        }

        showDatepicker();
    });

    $('body').on('click', '.datepicker_item:not(.disabled)', function (event) {
        $('.datepicker_item').removeClass('is_selected');
        $(this).addClass('is_selected');
        refreshTimeslots();
        datePickerSlider.go($(this).data('index'));
    });

    $('body').on('click', '.cmb_datepicker_timeslot span', function (event) {
        $('.cmb_datepicker_timeslot').find('span').removeClass('is_selected');
        $(this).addClass('is_selected');

        $('.cmb_location_wrapper').addClass('d-none');
        $('.cmb_services_wrapper').addClass('d-none');
        hideDatepicker();
        showConfirmation();
    });

    $('body').on('click', '.cmb_datepicker_timeslot_show_more_btn', function (event) {
        event.preventDefault();

        let wrapper = $(this).parents('.cmb_datepicker_timeslots_wrapper');
        show = 0;
        wrapper.find('.cmb_datepicker_timeslot.d-none').each(function () {
            if (show > 5) {
                return;
            }
            $(this).removeClass('d-none');
            show++;
        });

        if (wrapper.find('.cmb_datepicker_timeslot.d-none').length == 0) {
            $('.cmb_datepicker_timeslot_show_more_section').addClass('d-none');
        }
    });

    $('body').on('click', '.cmb_back_to_datepicker_btn', function (event) {
        event.preventDefault();

        hideConfirmation();
        $('.cmb_confirmation_error_msg').text('');
        $('.cmb_services_wrapper').removeClass('d-none');
        $('.cmb_datepicker_wrapper').removeClass('d-none');
    });

    $('body').on('click', '.cmb_open_login_modal', function (event) {
        event.preventDefault();

        $('#cmb_login_modal').modal('show');
    });

    $('body').on('click', '#cmb_login_modal_confirm_btn', function (event) {
        event.preventDefault();

        $('[data-cmb-login-errors]').html('');

        $(this).prop('disabled', true);
        $(this).text('Even geduld...');

        if (validateLoginForm()) {
            makeLogin().catch(function (error) {
                $('#cmb_login_modal_confirm_btn').prop('disabled', false);
                $('#cmb_login_modal_confirm_btn').text('Aanmelden');

                console.log('error :: ',error.responseJSON);

                if ('email' in error.responseJSON.errors) {
                    let emailError = error.responseJSON.errors.email;
                    $('<p class="text-danger">'+emailError+'</p>')
                        .appendTo('[data-cmb-login-errors]');
                }

                if ('password' in error.responseJSON.errors) {
                    let pwdError = error.responseJSON.errors.password;
                    $('<p class="text-danger">'+pwdError+'</p>')
                        .appendTo('[data-cmb-login-errors]');
                }
            }).done(function (data) {
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

        $('form.cmb_booker_app input[name="general_conditions"]').prop('checked', true);
        $('#generalConditionsModal').modal('hide');
    });

    $('body').on('click', '.cmb_medical_declaration_modal_btn', function (event) {
        event.preventDefault();

        $('form.cmb_booker_app input[name="medical_declaration"]').prop('checked', true);
        $('#medicalDeclarationModal').modal('hide');
    });

    $('body').on('change', 'input[name="create_customer"]', function (event) {
        event.preventDefault();

        $('form.cmb_booker_app .cmb_create_customer_password_wrapper').addClass('d-none');

        if ($(this).is(':checked')) {
            $('form.cmb_booker_app .cmb_create_customer_password_wrapper').removeClass('d-none');
        }
    });

    $('body').on('click', '#cmb_confirmation_booker_btn', function (event) {
        event.preventDefault();

        $(this).prop('disabled', true);
        $(this).text('Even geduld...');

        if (validateForm()) {
            makeAppointment().done(function (data) {
                handleResponseFromMakeAppointment(data);
            });
        } else {
            $(this).prop('disabled', false);
            $(this).text('Bevestigen');
        }
    });


    function showDatepicker() {
        showLoadingMessage();

        //Get available dates for service/location(s)
        getAvailableDates().done(function (response) {
            if (response.status == "success") {
                hideLoadingMessage();

                removeAllDates();

                addNewDatesFromAvailability(response.availability);

                refreshTimeslots();

                $('.cmb_datepicker_wrapper').removeClass('d-none');
            } 

            if (response.status == "error") {
                hideLoadingMessage();

                showLoadingErrorMessage();

                setTimeout(function(){ 
                    hideLoadingErrorMessage(); 
                }, 3000);
            }
        });
    }

    function hideDatepicker() {
        hideLoadingMessage();
        hideLoadingErrorMessage();
        $('.cmb_datepicker_wrapper').addClass('d-none');
    }

    function enableAllServices() {
        $('input[name="cmb_services"]').prop('disabled', false);
    }

    function disableServicesOnWeight() {
        enableAllServices();

        let max_weight = getSelectedLocationMaxWeight();
        let selected_weight = getSelectedServicesWeight();

        let remainder_weight = max_weight - selected_weight;

        $('input[name="cmb_services"]:not(:checked)').each(function (item) {
            let service_weight = parseInt($(this).attr('data-weight'));

            if (service_weight > remainder_weight) {
                $(this).prop('disabled', true);
            }
        });
    }

    function getAvailableDates() {
        let location = getSelectedLocationId();
        let services = getSelectedServicesIds();

        return $.ajax({
            method: 'POST',
            url: get_available_dates_url,
            data: { 
                location: location, 
                services: services, 
                _token: session_token
            }
        });
    }

    function refreshTimeslots() {
        let timeslots = JSON.parse($('.datepicker_item.is_selected:first').attr('data-timeslots'));
        $('.cmb_datepicker_timeslot_show_more_section').addClass('d-none');

        $('.cmb_datepicker_timeslot:not(:first)').remove();
        $('.cmb_datepicker_timeslot').find('span').removeClass('is_selected');

        for (var i = 0; i < timeslots.length; i++) {
            if (i !== 0) {
                $('.cmb_datepicker_timeslot:first')
                            .clone()
                            .appendTo('.cmb_datepicker_timeslot_section:first');
            }
            
            $('.cmb_datepicker_timeslot:last').prop('data-slot-start', timeslots[i].start);
            $('.cmb_datepicker_timeslot:last').prop('data-slot-end', timeslots[i].end);
            $('.cmb_datepicker_timeslot:last')
                    .find('p')
                    .text(timeslots[i].start);//+' - '+timeslots[i].end

            if (i > 5) {
                $('.cmb_datepicker_timeslot:last').addClass('d-none');
            }
        };

        if (timeslots.length > 6) {
            $('.cmb_datepicker_timeslot_show_more_section').removeClass('d-none');
        }
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

    function addNewDatesFromAvailability(availability) {
        dates = Object.entries(availability);
        firstSelected = false;

        for (var a = 0; a < dates.length; a++) {
            slideHtml = '';
            
            if (!firstSelected && dates[a][1].status == 'available') {
                slideHtml = '<div class="splide__slide datepicker_item border rounded mr-2 me-2 is_selected '+(dates[a][1].status !== 'available' ? ' disabled' : '' )+'" data-index="'+a+'" data-date="'+dates[a][1].month+'/'+dates[a][1].day+'/'+dates[a][1].year+'" data-timeslots=\''+JSON.stringify(dates[a][1].timeslots)+'\'>';
                firstSelected = true;
            } else {
                slideHtml = '<div class="splide__slide datepicker_item border rounded mr-2 me-2 '+(dates[a][1].status !== 'available' ? ' disabled' : '' )+'" data-index="'+a+'" data-date="'+dates[a][1].month+'/'+dates[a][1].day+'/'+dates[a][1].year+'" data-timeslots=\''+JSON.stringify(dates[a][1].timeslots)+'\'>';
            }

            slideHtml += '<span class="d-block px-2 text-center">';
            slideHtml += '<small class="day">'+getShortWeekday(dates[a][1].short_weekday)+'</small>';
            slideHtml += '<h6 class="font-weight-bold mb-0 number">'+dates[a][1].day+'</h6>';
            slideHtml += '<small class="month">'+getShortMonth(dates[a][1].short_month)+'</small>';
            slideHtml += '</span>';
            slideHtml += '</div>';

            datePickerSlider.add(slideHtml);
        };
    }

    function getShortWeekday(weekday) {
        if (weekday == "Mon") { return "Maa"; }
        if (weekday == "Tue") { return "Din"; }
        if (weekday == "Wen") { return "Woe"; }
        if (weekday == "Thu") { return "Don"; }
        if (weekday == "Fri") { return "Vri"; }
        if (weekday == "Sat") { return "Zat"; }
        if (weekday == "Sun") { return "Zon"; }

        return weekday;
    }

    function getShortMonth(month) {
        if (month == "Jan") { return "Jan"; }
        if (month == "Feb") { return "Feb"; }
        if (month == "Mar") { return "Maa"; }
        if (month == "Apr") { return "Apr"; }
        if (month == "May") { return "Mei"; }
        if (month == "Jun") { return "Jun"; }
        if (month == "Jul") { return "Jul"; }
        if (month == "Aug") { return "Aug"; }
        if (month == "Sep") { return "Sep"; }
        if (month == "Oct") { return "Okt"; }
        if (month == "Nov") { return "Nov"; }
        if (month == "Dec") { return "Dec"; }

        return month;
    }

    function removeAllDates() {
        if (datePickerSlider.length > 0) {
            sliderLength = datePickerSlider.length;
            
            for (var dps = 0; dps < sliderLength; dps++) {
                datePickerSlider.remove(datePickerSlider.length - 1);
            };
        }
    }

    function showConfirmation() {
        fillConfirmation();
        $('.cmb_confirmation_wrapper').removeClass('d-none');
    }

    function fillConfirmation() {
        let options = { year: 'numeric', month: 'long', day: 'numeric' };
        let date = new Date(getSelectedDate());
        date = date.toLocaleDateString('nl-BE', options);
        $('.cmb_confirmation_date_text').text(date);

        let timeslot = getSelectedTimeslot();
        $('.cmb_confirmation_time_text').text(timeslot);

        fillConfirmationServices();

        $('.cmb_confirmation_duration_text').text(getSelectedServicesDuration()+' minuten');
        
        if ( isSubscriptionValidForSelection() ) {
            $('.cmb_confirmation_price_text').html('<span style="text-decoration: line-through;">'+getSelectedServicesPrice()+' EUR</span> (Actief Abonnement)');
        } else {
            $('.cmb_confirmation_price_text').text(getSelectedServicesPrice()+' EUR');
        }
    }

    function isSubscriptionValidForSelection() {
        let date = new Date(getSelectedDate());
        date = date.getFullYear()+'-'+(parseInt(date.getMonth()) + 1)+'-'+(String(date.getDate()).padStart(2, '0'));

        return (auth_check && auth_available_weight >= getSelectedServicesWeight() && !auth_available_weight_not_on_days.includes(date)) || (auth_check && auth_available_weight == -1 && !auth_available_weight_not_on_days.includes(date));
    }

    function fillConfirmationServices() {
        let services = getSelectedServices();

        $('.cmb_confirmation_overview_services:not(:first)').remove();

        for (var s = 0; s < services.length; s++) {
            if (s > 0) {
                $('.cmb_confirmation_overview_services:first')
                        .clone()
                        .appendTo('.cmb_confirmation_overview_section');
            }

            fillConfirmationService(services[s]);
        };
    }

    function fillConfirmationService(service) {
        iteration = $('.cmb_confirmation_overview_services').length;

        $('.cmb_confirmation_overview_services:last')
            .find('.cmb_confirmation_overview_services_name_text')
            .html(service.name+' <small>('+service.duration+' min)</small>');

        $('.cmb_confirmation_overview_services:last')
            .find('.cmb_confirmation_overview_services_dd_btn')
            .attr('data-target', '#cmb_overview_service_description'+iteration)
            .attr('aria-controls', 'cmb_overview_service_description'+iteration);

        $('.cmb_confirmation_overview_services:last')
            .find('.cmb_confirmation_overview_services_dd_text')
            .prop('id', 'cmb_overview_service_description'+iteration);

        $('.cmb_confirmation_overview_services:last')
            .find('.cmb_confirmation_overview_services_dd_text p.description')
            .text(service.description);
    }

    function hideConfirmation() {
        $('.cmb_confirmation_wrapper').addClass('d-none');
    }

    function getSelectedDate() {
        return $('.datepicker_item.is_selected:first').attr('data-date');
    }

    function getSelectedTimeslot() {
        let start = $('.cmb_datepicker_timeslot span.is_selected').first().parents('.cmb_datepicker_timeslot').prop('data-slot-start');

        return start;
    }

    function getSelectedServices() {
        let array = [];
        
        $('input[name=cmb_services]:checked:not(:disabled)').each(function () {
            array.push({
                id: $(this).val(),
                name: $(this).attr('data-name'),
                duration: $(this).attr('data-duration'),
                weight: parseInt($(this).attr('data-weight')),
                price: $(this).attr('data-price'),
                description: $(this).attr('data-description')
            });
        });

        return array;
    }

    function getSelectedServicesPrice() {
        let services = getSelectedServices();
        let price = 0;

        for (var s = 0; s < services.length; s++) {
            price = (price + parseFloat(services[s].price));
        };

        return Math.round((price + Number.EPSILON) * 100) / 100;
    }

    function getSelectedServicesDuration() {
        let services = getSelectedServices();
        let duration = 0;

        for (var s = 0; s < services.length; s++) {
            duration = (duration + parseInt(services[s].duration));
        };

        return duration;
    }

    function getSelectedServicesWeight() {
        let services = getSelectedServices();
        let weight = 0;

        for (var s = 0; s < services.length; s++) {
            weight = (weight + parseInt(services[s].weight));
        };

        return weight;
    }

    function validateForm() {
        $('.cmb_confirmation_error_msg').text('');

        let first_name = $('form.cmb_booker_app input[name="first_name"]').val();
        let last_name = $('form.cmb_booker_app input[name="last_name"]').val();
        let email = $('form.cmb_booker_app input[name="email"]').val();
        let tel = $('form.cmb_booker_app input[name="tel"]').intlTelInput("getNumber");

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

        if (!$('form.cmb_booker_app input[name="tel"]').intlTelInput("isValidNumber")) {
            $('.cmb_confirmation_error_msg').text('Gelieve een correct telefoonnummer in te vullen.');
            return false;
        }

        if ($('form.cmb_booker_app input[name="create_customer"]').is(':checked')) {
            let pwd = $('form.cmb_booker_app input[name="password"]').val();
            let pwd_check = $('form.cmb_booker_app input[name="password_confirmation"]').val();

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

        let general_conditions = $('form.cmb_booker_app input[name="general_conditions"]')
                                        .is(':checked');
        let medical_declaration = $('form.cmb_booker_app input[name="medical_declaration"]')
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

    function makeAppointment() {
        let date = getSelectedDate();
        let time = getSelectedTimeslot();
        let location = getSelectedLocationId();
        let services = getSelectedServicesIds();
        let duration = getSelectedServicesDuration();
        let customer = null;
        let create_customer = $('form.cmb_booker_app input[name="create_customer"]').is(':checked') ? 1 : 0;

        let pwd = $('form.cmb_booker_app input[name="password"]').val();
        let pwd_check = $('form.cmb_booker_app input[name="password_confirmation"]').val();

        let promo_check = 0;
        
        if ($('form.cmb_booker_app input[name="promo"]').length) {
            let promo_check = $('form.cmb_booker_app input[name="promo"]').is(':checked') ? 1 : 0;
        } 

        if (auth_check) {
            customer = $('form.cmb_booker_app input[name="customer_id"]').val();
            create_customer = 0;
        }

        //console.log('check for the customer :: ',customer, auth_check, "{{ Auth::check() }}");

        let first_name = $('form.cmb_booker_app input[name="first_name"]').val();
        let last_name = $('form.cmb_booker_app input[name="last_name"]').val();
        let email = $('form.cmb_booker_app input[name="email"]').val();
        let tel = $('form.cmb_booker_app input[name="tel"]').intlTelInput("getNumber");

        return $.ajax({
            method: 'POST',
            url: make_appointment_url,
            data: { 
                date: date,
                time: time,
                location: location,
                services: services, 
                duration: duration,
                customer: customer,
                create_customer: create_customer,
                password: pwd,
                password_confirmation: pwd_check,
                first_name: first_name,
                last_name: last_name,
                email: email,
                tel: tel,
                promo: promo_check,
                _token: session_token
            }
        });
    }

    function handleResponseFromMakeAppointment(data) {
        if (data.status == 'success') {
            window.location = data.redirect;
        }

        if (data.status == 'user_exists' || data.status == 'customer_exists') {
            $('.cmb_confirmation_error_msg').text('Er bestaat al een gebruiker met dit e-mailadres, gelieve aan te melden om een afspraak vast te leggen.');

            $('#cmb_confirmation_booker_btn').prop('disabled', false);
            $('#cmb_confirmation_booker_btn').text('Bevestigen');
        }

        if (data.status == 'booked_already') {
            $('.cmb_confirmation_error_msg').text('Helaas is het geselecteerde moment niet meer beschikbaar. Gelieve terug te gaan en een andere moment te selecteren.');

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

            $('form.cmb_booker_app input[name="first_name"]').val(data.customer.first_name).prop('disabled', true);
            $('form.cmb_booker_app input[name="last_name"]').val(data.customer.last_name).prop('disabled', true);
            $('form.cmb_booker_app input[name="email"]').val(data.customer.email).prop('disabled', true);
            $('form.cmb_booker_app input[name="tel"]').val(data.customer.tel).prop('disabled', false);

            $('form.cmb_booker_app input[name="general_conditions"]').prop('checked', true).prop('disabled', true);
            $('form.cmb_booker_app input[name="medical_declaration"]').prop('checked', true).prop('disabled', true);

            $('form.cmb_booker_app input[name="customer_id"]').val(data.customer.id);

            $('form.cmb_booker_app label[for="cmb_create_customer"]').remove();

            $('form.cmb_booker_app .cmb_email_correction').addClass('d-none');
            $('form.cmb_booker_app .cmb_email_suggestion').addClass('d-none');

            $('form.cmb_booker_app .cmb_open_login_modal').hide();

            $('#cmb_login_modal').modal('hide');

            auth_check = true;
            auth_available_weight = parseInt(data.available_weight);
            auth_available_weight_not_on_days = data.available_weight_not_on_days.split(',');
            fillConfirmation();
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
        let pwd = $('form.cmb_booker_app #cmb_customer_password').val();
        let pwd_check = $('form.cmb_booker_app #password-confirm').val();

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