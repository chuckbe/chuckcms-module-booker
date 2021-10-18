<div class="cmb_datepicker_wrapper d-none">
	<div class="form-group mb-2">
		<span class="lead text-black font-weight-bold fw-bold">Selecteer een datum</span>
	</div>
	<div class="splide" id="splide">
		<div class="splide__track">
			<div class="splide__list">
				@php
				$startDate = new \DateTime(now()->toDateString());
				$endDate = new \DateTime(now()->addMonths(1)->toDateString());

				$interval = \DateInterval::createFromDateString('1 day');
				$period = new \DatePeriod($startDate, $interval, $endDate);
				@endphp

				@foreach($period as $date)
				<div class="splide__slide datepicker_item border rounded mr-2 me-2" data-index="{{ $loop->index }}">
					<span class="d-block px-2 text-center">
						<small class="day">{{ $date->format('D') }}</small>
						<h6 class="font-weight-bold fw-bold mb-0 number">{{ $date->format('d') }}</h6>
						<small class="month">{{ $date->format('M') }}</small>
					</span>
				</div>
				@endforeach
			</div>
		</div>
	</div>

	<div class="cmb_datepicker_timeslots_wrapper">
		<div class="form-group my-2">
			<span class="lead text-black font-weight-bold fw-bold">Selecteer het gewenste uur</span>
		</div>
		<div class="row cmb_datepicker_timeslot_section">
			<div class="col-sm-6 col-md-4 cmb_datepicker_timeslot mb-2">
				<span class="d-block border rounded px-2 text-center">
					<p class="font-size-small m-auto py-2">08:00 - 08:15</p>
				</span>
			</div>
		</div>
		<div class="row cmb_datepicker_timeslot_show_more_section d-none">
			<div class="col-sm-12 text-center">
				<button class="btn btn-sm btn-dark cmb_datepicker_timeslot_show_more_btn">Meer tonen</button>
			</div>
		</div>
	</div>
</div>