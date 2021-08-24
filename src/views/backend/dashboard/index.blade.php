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
{{dd($locations)}}
</div>
@endsection

@section('scripts')

@endsection