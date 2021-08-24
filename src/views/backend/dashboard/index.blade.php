@extends('chuckcms::backend.layouts.base')

@section('title')
	Order Form
@endsection

@section('breadcrumbs')
	<ol class="breadcrumb">
		<li class="breadcrumb-item active"><a href="{{ route('dashboard.module.booker.index') }}">Overzicht</a></li>
	</ol>
@endsection

@section('content')
<div class="container">
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
                                <td>{{$locations->find($appointment->location_id)->name}}</td>
                                <td>{{$services->find($appointment)->name}}</td>
                                <td>{{$appointment->price}}</td>
                                <td>{{$appointment->date}}</td>
                                <td>{{$appointment->time}}</td>
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