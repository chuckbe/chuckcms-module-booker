<div class="cmb_datepicker_wrapper">
	<div class="splide">
		<div class="splide__track">
			<div class="splide__list">
				@php
				$startDate = new \DateTime('2020-06-01');
				$endDate = new \DateTime('2020-06-30');

				$interval = \DateInterval::createFromDateString('1 day');
				$period = new \DatePeriod($startDate, $interval, $endDate);
				@endphp

				@foreach($period as $date)
				<div class="splide__slide border border-dark rounded mr-2">
					<span class="d-block px-2 text-center">
						<small>{{ $date->format('D') }}</small>
						<h6 class="font-weight-bold mb-0">{{ $date->format('d') }}</h6>
						<small>{{ $date->format('m') }}</small>
					</span>
				</div>
				@endforeach
			</div>
		</div>
	</div>
</div>

<div class="cmb_datepicker_wrapper d-none">
	<div class="form-group">
		<span class="lead text-black font-weight-bold">Selecteer een datum</span>
		<div id="datepicker" class="d-flex justify-content-center" data-date="{{ date("d/m/Y") }}"></div>
	</div>
</div>