@extends('chuckcms::backend.layouts.base')

@section('title')
	Appointments
@endsection

@section('css')
<meta name="csrf-token" content="{{ Session::token() }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.9.0/main.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" integrity="sha512-+4zCK9k+qNFUR5X+cKL9EIR+ZOhtIloNl9GIKS57V1MyNsYpYcUrUeQc9vNfzsWfV28IaLL3i96P9sdNyeRssA==" crossorigin="anonymous">
@include('chuckcms-module-booker::backend.appointments.booker.css')
<style>
    .fc-toolbar-chunk { font-size: 0.8em;}
</style>
@endsection

@section('content')
<div class="container min-height">
    <div class="row py-3">
        <div class="col-sm-12 text-right">
            <button class="btn btn-sm btn-outline-secondary" data-target="#createAppointmentModal" data-toggle="modal">+ Afspraak</button>
        </div>
    </div>
    <div class="row bg-light shadow-sm rounded p-3 mt-2 mb-5 mx-1">
        <div class="col-sm-12 my-3">
			<div id="calendar"></div>
        </div>
    </div>
</div>
@include('chuckcms-module-booker::backend.appointments._modal')
@include('chuckcms-module-booker::backend.appointments._create_modal')
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.9.0/main.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.9.0/locales-all.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var session_token = "{{ Session::token() }}";
        var get_appointment_modal_url = "{{ route('dashboard.module.booker.appointments.modal') }}";
        var cancel_appointment_url = "{{ route('dashboard.module.booker.appointments.cancel') }}";
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'timeGridDay',
            locale: 'nl',
            headerToolbar: {
              start: 'prev,next', 
              center: 'title',
              end: 'timeGridWeek,timeGridDay'
            },
            slotDuration: '00:15:00',
            slotMinTime: '07:00:00',
            slotMaxTime: '21:00:00',
            allDaySlot: false,
            navLinks: true,
            nowIndicator: true,
            events: {
                url: '/dashboard/booker/appointments/json',
                method: 'GET',
                // extraParams: {
                //     custom_param1: 'something',
                //     custom_param2: 'somethingelse'
                // },
                // success: function() {

                // },
                failure: function() {
                    alert('there was an error while fetching events!');
                },
                //color: 'yellow',   // a non-ajax option
                //textColor: 'black' // a non-ajax option
            },
            eventClick: function(info) {
                openSingleAppointmentModal(info);
            },
            eventDataTransform: function( eventData ) {
                //console.log('checkks', eventData);

                return {
                    id: eventData.id,
                    title: eventData.title,
                    start: eventData.start,
                    end: eventData.end,
                    title: getTitleForEvent(eventData),
                    extendedProps: {
                        time: eventData.time,
                        duration: eventData.duration,
                        status: eventData.status,
                        weight: eventData.weight,
                        price: eventData.price
                    },
                    backgroundColor: getBackgroundColorForStatus(eventData.status)
                }
            }
        });
        calendar.render();

        function getTitleForEvent(eventData) {
            if (eventData.status == "confirmed" && 'is_free_session' in eventData.json) {
                return eventData.title+' (Gratis sessie!)';
            }

            if (eventData.status == "confirmed" && 'subscription' in eventData.json) {
                return eventData.title+' (Abo!)';
            }

            if (eventData.status == "confirmed" && !eventData.has_invoice) {
                return eventData.title+' (Betaling nodig!)';
            }

            if (eventData.status == "awaiting" && !eventData.has_invoice) {
                return eventData.title+' (Betaling nodig!)';
            }

            return eventData.title;
        }

        function getBackgroundColorForStatus(status) {
            if (status == "new") {
                return "gray";
            }

            if (status == "awaiting") {
                return "blue";
            }

            if (status == "canceled") {
                return "red";
            }

            if (status == "error") {
                return "red";
            }

            if (status == "confirmed") {
                return "green";
            }

            if (status == "payment") {
                return "green";
            }
        }

        function openSingleAppointmentModal(info) {
            $('#appointmentDetailsModal').find('.modal-footer').remove();
            $('#appointmentDetailsModal').find('.modal-body').html("<h5><i class=\"fas fa-spinner fa-pulse\"></i> Data ophalen...</h5>");

            $('#appointmentDetailsModal').modal('show');

            return $.ajax({
                method: 'POST',
                url: get_appointment_modal_url,
                data: { 
                    id: info.event.id,
                    _token: session_token
                }
            }).done(function (data) {
                let html = data.html;
                $('#appointmentDetailsModal').find('.modal-body').remove();
                $('#appointmentDetailsModal').find('.modal-footer').remove();

                $(html).appendTo('#appointmentDetailsModal .modal-content');
            });
        }

        $('body').on('click', '#editAppointmentModalCancelAppBtn', function (event) {
            event.preventDefault();

            let event_id = $(this).data('event-id');

            var r = confirm("Bent u zeker dat u de afspraak wilt annuleren? Indien dit een betalende afspraak is dient U de terugbetaling momenteel nog manueel uit te voeren.");
            
            if (r == true) {

                $(this).html('<i class="fas fa-spinner fa-pulse"></i> Even geduld...');

                return $.ajax({
                    method: 'POST',
                    url: cancel_appointment_url,
                    data: { 
                        id: event_id,
                        _token: session_token
                    }
                }).done(function (data) {
                    if (data.status == 'success') {
                        $('#appointmentDetailsModal').modal('hide');
                        calendar.refetchEvents();
                    }
                });
            } 
        });
    });

$(document).ready(function (event) {
    var update_payment_url = "{{ route('dashboard.module.booker.appointments.payment') }}";
    var check_payment_url = "{{route('dashboard.module.booker.appointments.status')}}";
    var session_token = "{{ Session::token() }}";

    $('body').on('change', '#cmb_detail_is_free_session', function (event) {
        event.preventDefault();

        if ($(this).is(':checked')) {
            $('div.eAMP input[name="pay_later"]').prop('checked', false).prop('disabled', true);
            $('div.eAMP input[name="paid"]').prop('checked', false).prop('disabled', true);
            $('div.eAMP input[name="needs_payment"]').prop('checked', false).prop('disabled', true);
            $('div.eAMP input[name="qr_code"]').prop('checked', false).prop('disabled', true);
        } else {
            $('div.eAMP input[name="pay_later"]').prop('checked', false).prop('disabled', false);
            $('div.eAMP input[name="paid"]').prop('checked', false).prop('disabled', false);
            $('div.eAMP input[name="needs_payment"]').prop('checked', false).prop('disabled', false);
            $('div.eAMP input[name="qr_code"]').prop('checked', false).prop('disabled', false);
        }
    });

    $('body').on('change', '#cmb_detail_paid', function (event) {
        event.preventDefault();

        if ($(this).is(':checked')) {
            $('div.eAMP input[name="is_free_session"]').prop('checked', false).prop('disabled', true);
            $('div.eAMP input[name="pay_later"]').prop('checked', false).prop('disabled', true);
            $('div.eAMP input[name="needs_payment"]').prop('checked', false).prop('disabled', true);
            $('div.eAMP input[name="qr_code"]').prop('checked', false).prop('disabled', true);
        } else {
            $('div.eAMP input[name="is_free_session"]').prop('checked', false).prop('disabled', false);
            $('div.eAMP input[name="pay_later"]').prop('checked', false).prop('disabled', false);
            $('div.eAMP input[name="needs_payment"]').prop('checked', false).prop('disabled', false);
            $('div.eAMP input[name="qr_code"]').prop('checked', false).prop('disabled', false);
        }
    });

    $('body').on('change', '#cmb_detail_needs_payment', function (event) {
        event.preventDefault();

        if ($(this).is(':checked')) {
            $('div.eAMP input[name="pay_later"]').prop('checked', true).prop('disabled', true);
            $('div.eAMP input[name="paid"]').prop('checked', false).prop('disabled', true);
            $('div.eAMP input[name="is_free_session"]').prop('checked', false).prop('disabled', true);
            $('div.eAMP input[name="qr_code"]').prop('checked', false).prop('disabled', true);
        } else {
            $('div.eAMP input[name="pay_later"]').prop('checked', false).prop('disabled', false);
            $('div.eAMP input[name="paid"]').prop('checked', false).prop('disabled', false);
            $('div.eAMP input[name="is_free_session"]').prop('checked', false).prop('disabled', false);
            $('div.eAMP input[name="qr_code"]').prop('checked', false).prop('disabled', false);
        }
    });

    $('body').on('change', '#cmb_detail_qr_code', function (event) {
        event.preventDefault();

        if ($(this).is(':checked')) {
            $('div.eAMP input[name="pay_later"]').prop('checked', false).prop('disabled', true);
            $('div.eAMP input[name="paid"]').prop('checked', false).prop('disabled', true);
            $('div.eAMP input[name="is_free_session"]').prop('checked', false).prop('disabled', true);
            $('div.eAMP input[name="needs_payment"]').prop('checked', false).prop('disabled', true);
        } else {
            $('div.eAMP input[name="pay_later"]').prop('checked', false).prop('disabled', false);
            $('div.eAMP input[name="paid"]').prop('checked', false).prop('disabled', false);
            $('div.eAMP input[name="is_free_session"]').prop('checked', false).prop('disabled', false);
            $('div.eAMP input[name="needs_payment"]').prop('checked', false).prop('disabled', false);
        }
    });

    $('body').on('click', '#editAppointmentModalPaymentBtn', function (event) {
        event.preventDefault();

        $(this).prop('disabled', true);
        $(this).text('Even geduld...');

        $('.cmb_payment_detail_error_msg').text('');

        if (!$('div.eAMP input[name="is_free_session"]').is(':checked') 
            && !$('div.eAMP input[name="paid"]').is(':checked') 
            && !$('div.eAMP input[name="needs_payment"]').is(':checked')
            && !$('div.eAMP input[name="qr_code"]').is(':checked')) {
            $('.cmb_payment_detail_error_msg').text('Gelieve een betaalmethode aan te duiden.');

            $(this).prop('disabled', false);
            $(this).text('Update betalingstatus');
            return false;
        }

        let appointment_id = $('div.eAMP input[name="appointment_payment_id"]').val();

        let is_free_session = $('div.eAMP input[name="is_free_session"]').is(':checked') ? 1 : 0;
        let paid = $('div.eAMP input[name="paid"]').is(':checked') ? 1 : 0;
        let qr_code = $('div.eAMP input[name="qr_code"]').is(':checked') ? 1 : 0;

        return $.ajax({
            method: 'POST',
            url: update_payment_url,
            data: { 
                appointment_id: appointment_id,
                is_free_session: is_free_session,
                paid: paid,
                qr_code: qr_code,
                _token: session_token
            }
        }).done(function (data) {
            if (data.status == 'qr_code') {
                let qr_code_image = data.qr;

                $('div.eAMP').addClass('d-none');
                $('.cmb_detail_qr_code').removeClass('d-none');
                $('.cmb_detail_qr_code').find('img').prop('src', qr_code_image);

                checkDetailPaymentStatus(data.appointment_id);
            }

            if (data.status == 'success' || data.status == 'paid') {
                window.location = window.location;
            }
        });
    });

    function checkDetailPaymentStatus(appointment_id) {
        $.ajax({
            method: 'POST',
            url: check_payment_url,
            data: { 
                id: appointment_id,
                _token: session_token
            }
        }).done(function (data) {
            if (data.status == 'paid') {
                $('.cmb_detail_qr_code').find('.cmb_detail_qr_code_status').html('<i class="fas fa-check"></i> Betaling ontvangen!');

                sleep(2500).then(() => {
                    window.location = window.location;
                });
            } else {
                setTimeout(checkDetailPaymentStatus(appointment_id), 12000);
            }
        });
    }

    function sleep (time) {
        return new Promise((resolve) => setTimeout(resolve, time));
    }
});
</script>

@include('chuckcms-module-booker::backend.appointments.booker.scripts')
@endsection