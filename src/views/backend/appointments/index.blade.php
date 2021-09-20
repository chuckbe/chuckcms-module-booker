@extends('chuckcms::backend.layouts.base')

@section('title')
	Appointments
@endsection

@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.9.0/main.min.css">
@endsection

@section('content')
<div class="container min-height">
    <div class="row bg-light shadow-sm rounded p-3 my-5 mx-1">
        <div class="col-sm-12 my-3">
			<div id="calendar"></div>
        </div>
    </div>
</div>
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
        slotDuration: '00:30:00',
        slotMinTime: '07:00:00',
        slotMaxTime: '21:00:00',
        allDaySlot: false,
        navLinks: true
    });
    calendar.render();
});
</script>
@endsection