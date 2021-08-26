@extends('chuckcms::backend.layouts.base')

@section('title')
	Booking Module
@endsection

@section('breadcrumbs')
	<ol class="breadcrumb">
		<li class="breadcrumb-item active"><a href="{{ route('dashboard.module.booker.appointments') }}">Appointments</a></li>
	</ol>
@endsection

@section('content')
<div class="container min-height">
    <div class="row bg-light shadow-sm rounded p-3 my-5 mx-1">
        <div class="col-sm-12 my-3">
			<div class="table-responsive">
                <table class="table" data-datatable style="width:100%">
                    <thead>
						<tr>
							<th scope="col">#</th>
							<th scope="col">Location</th>
                            <th scope="col">Service</th>
                            <th scope="col">Price</th>
							<th scope="col">Date</th>
							<th scope="col">Time</th>
							<th scope="col">Status</th>
							<th scope="col">is_cancelled</th>
						</tr>
					</thead>
                    <tbody>
                        @foreach($appointments as $appointment)
                            <tr>
                                <td>{{$appointment->id}}</td>
                                <td>{{$appointment->location->name}}</td>
                                <td>
                                    @foreach ($appointment->services as $service)
                                        @if($loop->last){{$service->name}}
                                        @else{{$service->name.", "}}
                                        @endif
                                    @endforeach
                                </td>
                                <td>€ {{number_format($appointment->price, 2, ',', '.')}}</td>
                                <td style="min-width: 90px">{{date( "d M,y", strtotime($appointment->date))}}</td>
                                <td style="min-width: 90px">{{date("h:i a", strtotime($appointment->time))}}</td>
                                <td>{{$appointment->status}}</td>
                                <td>{{$appointment->is_cancelled}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')

@endsection