@extends('chuckcms::backend.layouts.base')

@section('title')
	Appointments
@endsection

@section('css')
<meta name="csrf-token" content="{{ Session::token() }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.9.0/main.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" integrity="sha512-+4zCK9k+qNFUR5X+cKL9EIR+ZOhtIloNl9GIKS57V1MyNsYpYcUrUeQc9vNfzsWfV28IaLL3i96P9sdNyeRssA==" crossorigin="anonymous">
@include('chuckcms-module-booker::backend.appointments.booker.css')
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
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'timeGridDay',
            locale: 'nl',
            headerToolbar: {
              start: 'prev,next', 
              center: 'title',
              end: 'dayGridMonth,timeGridWeek,timeGridDay'
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
                console.log('clickk :: ', info.event);
                
                let options = { year: 'numeric', month: 'long', day: 'numeric' };
                let date = new Date(info.event.start);
                date = date.toLocaleDateString('nl-BE', options);

                time = info.event.extendedProps.time;
                duration = info.event.extendedProps.duration+" minuten";
                price = (Math.round((Number(info.event.extendedProps.price) + Number.EPSILON) * 100) / 100) + " EUR";

                $('#appointmentDetailsModal .cmb_confirmation_date_text').text(date);
                $('#appointmentDetailsModal .cmb_confirmation_time_text').text(time);
                $('#appointmentDetailsModal .cmb_confirmation_duration_text').text(duration);
                $('#appointmentDetailsModal .cmb_confirmation_price_text').text(price);


                $('#appointmentDetailsModal').modal('show');
            },
            eventDataTransform: function( eventData ) {
                //console.log('checkks', eventData);

                return {
                    id: eventData.id,
                    title: eventData.title,
                    start: eventData.start,
                    end: eventData.end,
                    title: eventData.title,
                    extendedProps: {
                        time: eventData.time,
                        duration: eventData.duration,
                        status: eventData.status,
                        weight: eventData.weight,
                        price: eventData.price,
                    },
                    backgroundColor: getBackgroundColorForStatus(eventData.status)
                }
            }
        });
        calendar.render();

        function getBackgroundColorForStatus(status) {
            if (status == "new") {
                return "gray";
            }

            if (status == "awaiting") {
                return "blue";
            }

            if (status == "canceled") {
                return "dark gray";
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
    });
</script>

@include('chuckcms-module-booker::backend.appointments.booker.scripts')
@endsection