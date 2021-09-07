@extends('chuckcms::backend.layouts.base')

@section('title')
	Edit Location
@endsection

@section('breadcrumbs')
	<ol class="breadcrumb">
		<li class="breadcrumb-item"><a href="{{ route('dashboard.module.booker.locations.index') }}">Locaties</a></li>
        <li class="breadcrumb-item active" aria-current="Locaties">Bewerk locatie "{{ $location->name }}"</li>
	</ol>
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
        <form id="locationUpdateForm" action="{{ route('dashboard.module.booker.services.save') }}" method="POST">
            <div class="row bg-light shadow-sm rounded p-3 mb-3 mx-1">
                <div class="col-sm-12">
                    <div class="my-3">
                        <div class="form-group form-group-default required">
                            <label>Name</label>
                            <input type="text" class="form-control" id="locationname" name="location_name" aria-describedby="locationname" value="{{ $location->name }}" placeholder="Enter location name">
                        </div>
                        <div class="form-group form-group-default required">
                            <div class="form-row">
                                <div class="col">
                                    <label for="locationLatitude">Latitude</label>
                                    <input type="text" class="form-control" id="locationLatitude" name="location_lat" aria-describedby="locationLatitude" value="{{ $location->lat }}" placeholder="Enter location Latitude">
                                </div>
                                <div class="col">
                                    <label for="locationLongitute">Longitute</label>
                                    <input type="text" class="form-control" id="locationLongitute" name="location_long" aria-describedby="locationLongitute" value="{{ $location->long }}" placeholder="Enter location Longitute">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="gid">Google Calender ID</label>
                            <input type="text" class="form-control" id="gid" aria-describedby="gid" name="location_gid" value="{{ $location->google_calendar_id }}" placeholder="Enter Google Calender ID">
                        </div>
                        <div class="form-group">
                            <label for="gid">Opening hours</label>
                            <div id="businessHoursContainer" class="pt-4"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="my-3">
                        <p class="pull-right">
                            <input type="hidden" id="location_opening_hours" name="location_opening_hours" value="">
                            <input type="hidden" name="location_id" value="{{ $location->id }}">
                            <input type="hidden" name="_token" value="{{ Session::token() }}">
                            <button type="submit" class="btn btn-success btn-cons pull-right m-1" value="1">Opslaan</button>
                            <a href="{{ route('dashboard.module.booker.locations.index') }}" class="pull-right m-1"><button type="button" class="btn btn-info btn-cons">Annuleren</button></a>
                        </p>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('css')
<style>
    #businessHoursContainer .clean {
        clear: both
    }

    #businessHoursContainer .dayContainer {
        float: left;
        line-height: 20px;
        margin-right: 8px;
        width: 65px;
        font-size: 11px;
        font-weight: 700
    }

    #businessHoursContainer .colorBox {
        cursor: pointer;
        height: 45px;
        border: 2px solid #888;
        -webkit-border-radius: 4px;
        -moz-border-radius: 4px;
        border-radius: 4px
    }

    #businessHoursContainer .colorBox.WorkingDayState {
        border: 2px solid #4E8059;
        background-color: #8ade8f
    }

    #businessHoursContainer .colorBox.RestDayState {
        border: 2px solid #7a1c44;
        background-color: #de5962
    }

    #businessHoursContainer .operationTime .mini-time {
        width: 40px;
        padding: 3px;
        font-size: 12px;
        font-weight: 400
    }

    #businessHoursContainer .dayContainer .add-on {
        padding: 4px 2px
    }

    #businessHoursContainer .colorBoxLabel {
        clear: both;
        font-size: 12px;
        font-weight: 700
    }

    #businessHoursContainer .invisible {
        visibility: hidden
    }

    #businessHoursContainer .operationTime {
        margin-top: 5px
    }
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-timepicker/1.13.18/jquery.timepicker.css" />
@endsection

@section('scripts')
@include('chuckcms-module-booker::backend.locations.businesshours')
<script>
    // {{json_encode($location->json['opening-hours']) }}
    // $("#businessHoursContainer").businessHours({
    //     operationTime: @json($location->json['opening-hours']),
    // });
    // $('body').on('click', '#locationUpdateForm .btn-success[type=submit]' , function(event){
    //     event.preventDefault();
    //     let arr = ($("#businessHoursContainer").businessHours()).serialize();
    //     $('#location_opening_hours').val(JSON.stringify(arr));
    //     $("#locationUpdateForm").submit();
    // });

</script>
@endsection