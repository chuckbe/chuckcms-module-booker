@extends('chuckcms::backend.layouts.base')

@section('title')
	Edit Location
@endsection

@section('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css">
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="{{ URL::to('vendor/laravel-filemanager/js/lfm.js') }}"></script>
<script>
$( document ).ready(function() { 
	
	$('select').select2({
	    theme: 'bootstrap4',
	    minimumResultsForSearch: -1
	});

	$('body').on('change', '#edit_location_disabled_weekdays', function (event) {
		$('.openingshoursRangeInputSection').removeClass('d-none');
		$('.openingshoursRangeInputSection')
								.find('input')
								.prop('required', true)
								.prop('disabled', false);

		let selectedValues = $(this).find(':selected');

		for (var i = selectedValues.length - 1; i >= 0; i--) {
			$('.openingshoursRangeInputSection[data-day="'+selectedValues[i].value+'"]').addClass('d-none');
			$('.openingshoursRangeInputSection[data-day="'+selectedValues[i].value+'"]')
								.find('input')
								.prop('required', false)
								.prop('disabled', true);
		};
	});

	$('body').on('click', '.addOpeningshoursRangeInputRowBtn', function (event) {
		event.preventDefault();

		let dayOfTheWeek = $(this).attr('data-day');
		
		firstRangeInputRow = $('.openingshoursRangeInputWrapper[data-day="'+dayOfTheWeek+'"]')
								.find('.openingshoursRangeInputRow')
								.first();

		firstRangeInputRow.clone().appendTo('.openingshoursRangeInputWrapper[data-day="'+dayOfTheWeek+'"]');

		$('.openingshoursRangeInputWrapper[data-day="'+dayOfTheWeek+'"]')
								.find('.removeOpeningshoursRangeInputRowBtn')
								.removeClass('d-none');
	});

	$('body').on('click', '.removeOpeningshoursRangeInputRowBtn', function (event) {
		event.preventDefault();

		rangeInputWrapper = $(this).parents('.openingshoursRangeInputWrapper');

		if (rangeInputWrapper.find('.openingshoursRangeInputRow').length == 1) return;

		$(this).parents('.openingshoursRangeInputRow').remove();

		if (rangeInputWrapper.find('.openingshoursRangeInputRow').length == 1) {
			rangeInputWrapper.find('.removeOpeningshoursRangeInputRowBtn')
								.addClass('d-none');
		}
	});



	$('body').on('change', 'input[name="disabled_dates_check"]', function (event) {
		event.preventDefault();

		if ($(this).is(':checked')) {
			$(this).val(1);
			$('.disabledDatesInputSection').removeClass('d-none');
			$('.disabledDatesInputSection')
								.find('input[type="date"]')
								.prop('disabled', false)
								.prop('required', true);
			$('.disabledDatesInputSection')
								.find('input[type="time"]')
								.prop('disabled', false)
								.prop('required', true);
		} else {
			$(this).val(0);
			$('.disabledDatesInputSection').addClass('d-none');
			$('.disabledDatesInputSection')
								.find('input[type="date"]')
								.prop('disabled', true)
								.prop('required', false);
			$('.disabledDatesInputSection')
								.find('input[type="time"]')
								.prop('disabled', false)
								.prop('required', true);
		}
	});

	$('body').on('click', '.addDisabledDatesInputRowBtn', function (event) {
		event.preventDefault();

		//let dayOfTheWeek = $(this).attr('data-day');
		
		firstRangeInputRow = $('.disabledDatesInputWrapper')
								.find('.disabledDatesInputRow')
								.first();

		firstRangeInputRow.clone().appendTo('.disabledDatesInputWrapper');

		$('.disabledDatesInputWrapper')
								.find('.removeDisabledDatesInputRowBtn')
								.removeClass('d-none');
	});

	$('body').on('click', '.removeDisabledDatesInputRowBtn', function (event) {
		event.preventDefault();

		rangeInputWrapper = $(this).parents('.disabledDatesInputWrapper');

		if (rangeInputWrapper.find('.disabledDatesInputRow').length == 1) return;

		$(this).parents('.disabledDatesInputRow').remove();

		if (rangeInputWrapper.find('.disabledDatesInputRow').length == 1) {
			rangeInputWrapper.find('.removeDisabledDatesInputRowBtn')
								.addClass('d-none');
		}
	});

	$('body').on('change', '.disabledDateFullDayCheckbox', function (event) {
		event.preventDefault();

		if ($(this).is(':checked')) {
			$(this).val(1);
			$(this).parents('.form-group').find('input[type="hidden"]').prop('disabled', true);
		} else {
			$(this).val(0);
			$(this).parents('.form-group').find('input[type="hidden"]').prop('disabled', false);
		}
	});


	init();
	function init () {
		//init media manager inputs 
		var domain = "{{ URL::to('dashboard/media')}}";
		$('.img_lfm_link').filemanager('image', {prefix: domain});
	}
});
</script>
@endsection

@section('content')
    <div class="container min-height p-3">
        <div class="row">
			<div class="col-sm-12">
				<nav aria-label="breadcrumb">
					<ol class="breadcrumb mt-3">
						<li class="breadcrumb-item active" aria-current="Locaties"><a href="{{ route('dashboard.module.booker.locations.index') }}">Locaties</a></li>
                        <li class="breadcrumb-item active" aria-current="Locaties">Bewerk locatie "{{ $location->name }}"</li>
					</ol>
				</nav>
			</div>
		</div>

		<form role="form" method="POST" action="{{ route('dashboard.module.booker.locations.update') }}">
        
            <div class="row bg-light shadow-sm rounded p-3 mb-3 mx-1">
                <div class="col-sm-12">
                    <div class="form-group-attached">
			            <div class="row">
			              <div class="col-md-12">
			                <div class="form-group form-group-default required">
			                  <label>Naam *</label>
			                  <input type="text" id="edit_location_name" name="name" class="form-control" value="{{ old('name', $location->name) }}" required>
			                </div>
			              </div>
			            </div>
			            <div class="row">
			              <div class="col-sm-12">
			                <div class="form-group form-group-default">
			                  <label>Sluitingsdagen</label>
			                  <select name="disabled_weekdays[]" id="edit_location_disabled_weekdays" class="form-control" multiple="multiple" >
			                  	@foreach(config('chuckcms-module-booker.locations.weekdays') as $weekdayInt => $weekdayName)
			                    <option value="{{ $weekdayInt }}" @if(is_array($location->disabled_weekdays) && in_array($weekdayInt, $location->disabled_weekdays)) selected @endif>{{ $weekdayName }}</option>
			                    @endforeach
			                  </select>
			                </div>
			              </div>
			            </div>
			            <div class="row">
			              <div class="col-sm-12">
			                <label>Openingsuren *</label>
			              </div>

			              @foreach(config('chuckcms-module-booker.locations.weekdays') as $weekdayKey => $weekdayName)
			              {{-- start of day --}}
			              <div class="col-sm-12 openingshoursRangeInputSection{{ $location->isDisabledOnWeekday($weekdayKey) ? ' d-none' : '' }}" data-day="{{ $weekdayKey }}">
			                <div>
			                  <label><small>{{ $weekdayName }}</small></label>
			                  <span class="badge badge-secondary addOpeningshoursRangeInputRowBtn float-right" type="button" data-day="{{ $weekdayKey }}">+</span>
			                </div>
			                <div class="row">
			                  <div class="col-sm-12 openingshoursRangeInputWrapper" data-day="{{ $weekdayKey }}">
			                  	@if($location->isDisabledOnWeekday($weekdayKey))
			                    <div class="row openingshoursRangeInputRow">
			                      <div class="col-sm-6">
			                        <div class="input-group">
			                          <div class="input-group-prepend">
			                            <button class="btn btn-outline-secondary removeOpeningshoursRangeInputRowBtn d-none" type="button">-</button>
			                          </div>
			                          <input type="time" class="form-control" name="start_time_{{ strtolower($weekdayName) }}[]" value="08:00" disabled>
			                        </div>
			                      </div>
			                      <div class="col-sm-6">
			                        <div class="form-group form-group-default">
			                          <input type="time" class="form-control" name="end_time_{{ strtolower($weekdayName) }}[]" value="17:00" disabled>
			                        </div>
			                      </div>
			                    </div>
			                    @else
			                    @foreach($location->getOpeningHoursSectionsForDay(strtolower($weekdayName)) as $opening_hours_section)
			                    <div class="row openingshoursRangeInputRow">
			                      <div class="col-sm-6">
			                        <div class="input-group">
			                          <div class="input-group-prepend">
			                            <button class="btn btn-outline-secondary removeOpeningshoursRangeInputRowBtn{{ count($location->getOpeningHoursSectionsForDay(strtolower($weekdayName))) == 1 ? ' d-none' : '' }}" type="button">-</button>
			                          </div>
			                          <input type="time" class="form-control" name="start_time_{{ strtolower($weekdayName) }}[]" value="{{ $opening_hours_section['start'] }}" required>
			                        </div>
			                      </div>
			                      <div class="col-sm-6">
			                        <div class="form-group form-group-default">
			                          <input type="time" class="form-control" name="end_time_{{ strtolower($weekdayName) }}[]" value="{{ $opening_hours_section['end'] }}" required>
			                        </div>
			                      </div>
			                    </div>
			                    @endforeach
			                    @endif
			                  </div>
			                </div>
			              </div>
			              {{-- end of day --}}
			              @endforeach
			            </div>
			            
			            <div class="row">
			              	<div class="col-sm-12">
				                <div class="form-group form-group-default">
				                  <label>Uitgesloten Datums</label>
				                  @if((is_array($location->disabled_dates) && count($location->disabled_dates) == 0) || (is_array($location->disabled_dates) && count($location->disabled_dates) > 0 && !is_array($location->disabled_dates[0])))
				                  <input type="checkbox" name="disabled_dates_check" value="0">
				                  @elseif(is_array($location->disabled_dates) && count($location->disabled_dates) > 0 && array_key_exists('date', $location->disabled_dates[0]))
				                  <input type="checkbox" name="disabled_dates_check" value="1" checked>
				                  @endif
				                </div>
			              	</div>
			              	<div class="col-sm-12 disabledDatesInputSection{{ (is_array($location->disabled_dates) && count($location->disabled_dates) == 0) || (is_array($location->disabled_dates) && count($location->disabled_dates) > 0 && !is_array($location->disabled_dates[0])) ? ' d-none' : '' }}">
				                <div>
				                  	<label><small>Datum - Hele dag? - Van - Tot</small></label>
				                  	<span class="badge badge-secondary addDisabledDatesInputRowBtn float-right" type="button">+</span>
				                </div>
				                <div class="row">
				                	@if(is_array($location->disabled_dates) && count($location->disabled_dates) > 0 && is_array($location->disabled_dates[0]))
				                	
				                  	<div class="col-sm-12 disabledDatesInputWrapper">
				                  		@foreach($location->disabled_dates as $disabled_date)
					                    <div class="row disabledDatesInputRow">
					                      	<div class="col-sm-4">
						                        <div class="input-group">
						                          <div class="input-group-prepend">
						                            <button class="btn btn-outline-secondary removeDisabledDatesInputRowBtn d-none" type="button">-</button>
						                          </div>
						                          <input type="date" class="form-control" name="disabled_date[]" value="{{ $disabled_date['date'] }}">
						                        </div>
					                      	</div>
					                      	<div class="col-sm-2">
						                        <div class="form-group form-group-default">
						                          <input type="hidden" name="disabled_date_full_day[]" value="0" @if($disabled_date['full_day']) disabled @endif>
						                          <label><input type="checkbox" class="form-control-sm disabledDateFullDayCheckbox d-inline-block mb-0" name="disabled_date_full_day[]" value="{{ $disabled_date['full_day'] ? 1 : 0 }}" @if($disabled_date['full_day']) checked @endif> Ja</label>
						                        </div>
					                      	</div>
					                      	<div class="col-sm-3">
						                        <div class="form-group form-group-default">
						                          <input type="time" class="form-control disabled_date_start_time" name="disabled_date_start_time[]" value="{{ $disabled_date['start'] }}">
						                        </div>
					                      	</div>
					                      	<div class="col-sm-3">
						                        <div class="form-group form-group-default">
						                          <input type="time" class="form-control disabled_date_end_time" name="disabled_date_end_time[]" value="{{ $disabled_date['end'] }}">
						                        </div>
					                      	</div>
					                    </div>
				                  		@endforeach
				                  	</div>
				                  	@elseif((is_array($location->disabled_dates) && count($location->disabled_dates) == 0) || is_array($location->disabled_dates) && count($location->disabled_dates) > 0 && !is_array($location->disabled_dates[0]))
				                  	<div class="col-sm-12 disabledDatesInputWrapper">
					                    <div class="row disabledDatesInputRow">
					                      	<div class="col-sm-4">
						                        <div class="input-group">
						                          <div class="input-group-prepend">
						                            <button class="btn btn-outline-secondary removeDisabledDatesInputRowBtn d-none" type="button">-</button>
						                          </div>
						                          <input type="date" class="form-control" name="disabled_date[]" disabled>
						                        </div>
					                      	</div>
					                      	<div class="col-sm-2">
						                        <div class="form-group form-group-default">
						                          <input type="hidden" name="disabled_date_full_day[]" value="0">
						                          <label><input type="checkbox" class="form-control-sm disabledDateFullDayCheckbox d-inline-block mb-0" name="disabled_date_full_day[]" value="0"> Ja</label>
						                        </div>
					                      	</div>
					                      	<div class="col-sm-3">
						                        <div class="form-group form-group-default">
						                          <input type="time" class="form-control disabled_date_start_time" name="disabled_date_start_time[]" value="08:00" disabled>
						                        </div>
					                      	</div>
					                      	<div class="col-sm-3">
						                        <div class="form-group form-group-default">
						                          <input type="time" class="form-control disabled_date_end_time" name="disabled_date_end_time[]" value="17:00" disabled>
						                        </div>
					                      	</div>
					                    </div>
				                  	</div>
				                  	@endif
				                </div>
			              	</div>
			            </div>

			            <div class="row">
			              <div class="col-sm-12">
			                <div class="form-group form-group-default">
			                  <label>Biedt volgende diensten aan</label>
			                  <select name="services[]" id="edit_location_services" class="form-control" multiple="multiple">
			                    @foreach($services as $service)
			                    <option value="{{ $service->id }}" @if($location->services->where('id', $service->id)->count() > 0) selected @endif>{{ $service->name }}{{ $service->isFree() ? '' : ' ('.$service->formatted_price.')' }}</option>
			                    @endforeach
			                  </select>
			                </div>
			              </div>
			            </div>

			            <div class="row">
			              <div class="col-md-12">
			                <div class="form-group form-group-default required">
			                  <label>Max. gewicht</label>
			                  <input type="number" min="0" steps="1" max="9999" id="edit_location_max_weight" name="max_weight" class="form-control" value="{{ old('max_weight', $location->max_weight) }}" required>
			                </div>
			              </div>
			            </div>

			            <div class="row">
			              <div class="col-md-12">
			                <div class="form-group form-group-default required">
			                  <label>Interval</label>
			                  <input type="number" min="0" steps="1" max="9999" id="edit_location_interval" name="interval" class="form-control" value="{{ old('interval', $location->interval) }}" required>
			                </div>
			              </div>
			            </div>

			            <div class="row">
			              <div class="col-md-12">
			                <div class="form-group form-group-default required">
			                  <label>Volgorde</label>
			                  <input type="number" min="0" steps="1" max="9999" id="edit_location_order" name="order" class="form-control" value="{{ old('order', $location->order) }}" required>
			                </div>
			              </div>
			            </div>
			        </div>
                </div>
            </div>
            @if ($errors->any())
		      <div class="row bg-light shadow-sm rounded p-3 mb-3 mx-1">
		        <div class="col">
		          <div class="my-3">
		            <div class="alert alert-danger">
		              <ul>
		                @foreach ($errors->all() as $error)
		                  <li>{{ $error }}</li>
		                @endforeach
		              </ul>
		            </div>
		          </div>
		        </div>
		      </div>
		    @endif
            <div class="row">
                <div class="col-sm-12">
                    <div class="my-3">
                        <p class="pull-right">
                            <input type="hidden" name="id" value="{{ $location->id }}">
                            <input type="hidden" name="_token" value="{{ Session::token() }}">
                            <button type="submit" name="update" class="btn btn-success btn-cons pull-right m-1" value="1">Opslaan</button>
                            <a href="{{ route('dashboard.module.booker.locations.index') }}" class="pull-right m-1"><button type="button" class="btn btn-info btn-cons">Annuleren</button></a>
                        </p>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection