@extends('chuckcms::backend.layouts.base')

@section('title')
Nieuwe e-mail voor status {{ $statusKey }}
@endsection

@section('content')
@php
$lang = \LaravelLocalization::getCurrentLocale();
@endphp
<div class="container min-height p-3">
    <div class="row">
        <div class="col-sm-12">
            <nav aria-label="breadcumb mt-3">
                <ol class="breadcrumb mt-3">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.module.booker.settings.index') }}">Instellingen</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.module.booker.settings.index.statuses.edit', ['status' => $statusKey]) }}" class="">Status {{ ucfirst($statusKey) }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Nieuwe Email</li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
          @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
          @endif
        </div>
    </div>
    <form action="{{ route('dashboard.module.booker.settings.index.statuses.email.save') }}" method="POST">
        <div class="row bg-light shadow-sm rounded p-3 mb-3 mx-1">
            <div class="col-sm-12 tab-pane fade show active" id="s_general">
                <div class="form-group row mb-3">
                    <div class="col-sm-12">
                        <label for="email_key">Primaire Sleutel *</label>
                        <input type="text" class="form-control" id="email_key" name="email_key" value="{{ old('email_key') }}" required>
                    </div>
                </div>
                <div class="form-group row mb-3">
                    <div class="col-sm-6">
                        <label for="email_to">E-mailadres geadresseerde *</label>
                        <input type="text" class="form-control" id="email_to" name="to" value="{{ old('to') }}" required>
                    </div>
                    <div class="col-sm-6">
                        <label for="email_to_name">Naam geadresseerde *</label>
                        <input type="text" class="form-control" id="email_to_name" name="to_name" value="{{ old('to_name') }}" required>
                    </div>
                </div>
                <div class="form-group row mb-3">
                    <div class="col-sm-6">
                        <label for="email_cc">CC (gescheiden door komma's)</label>
                        <input type="text" class="form-control" id="email_cc" name="cc" value="{{ old('cc') }}">
                    </div>
                    <div class="col-sm-6">
                        <label for="email_bcc">BCC (gescheiden door komma's)</label>
                        <input type="text" class="form-control" id="email_bcc" name="bcc" value="{{ old('bcc') }}">
                    </div>
                </div>
                <div class="form-group row mb-3">
                    <div class="col-sm-6">
                        <label for="email_template">Template</label>
                        <input type="text" class="form-control" id="email_template" name="template" value="{{ old('template') }}" required>
                    </div>
                    <div class="col-sm-3 pt-3">
                        <label class="sr-only" for="">Logo?</label>
                        <div class="w-100 d-block mb-lg-1"></div>
                        <input type="hidden" class="boolean_checkbox_input_hidden" value="0" name="logo" disabled>
                        <label for="email_logo">
                            <input type="checkbox" class="boolean_checkbox_input" id="email_logo" value="1" name="logo" checked /> Logo?
                        </label>
                    </div>
                    <div class="col-sm-3 pt-3">
                        <label class="sr-only" for="">Pakbon?</label>
                        <div class="w-100 d-block mb-lg-1"></div>
                        <input type="hidden" class="boolean_checkbox_input_hidden" value="0" name="send_delivery_note" disabled>
                        <label for="send_delivery_note">
                            <input type="checkbox" class="boolean_checkbox_input" id="send_delivery_note" value="1" name="send_delivery_note" checked /> Pakbon?
                        </label>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-6 col-sm-4 order-1">
                        <small>Slug *</small>
                    </div>
                    <div class="col-12 col-sm-4 order-3 order-sm-2">
                        <small>Verplicht? *</small>
                    </div>
                    <div class="col-6 col-sm-4 order-2 order-sm-3">
                        <small>Tekstvak?</small>
                    </div>
                    <div class="col-sm-12 order-4">
                        <hr class="mt-1 mb-0">
                    </div>
                </div>
                <div class="status_input_container _input_container" id="status_input_container">
                    <div class="form-group row required status_input_line _input_line d-none">
                        <div class="col-6 col-sm-4 order-1">
                            <label class="sr-only" for="status_slug_x">Slug *</label>
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                    <button class="btn btn-outline-danger remove_line_button" type="button"><i class="fa fa-trash"></i></button>
                                </div>
                                <input type="text" class="form-control form-control-sm status_slug_input" id="status_slug_x" name="status_slug[]" value="" required>
                            </div>
                        </div>
                        <div class="col-12 col-sm-4 order-3 order-sm-2">
                            <label class="sr-only" for="">Verplicht?</label>
                            <div class="w-100 d-block mb-lg-1"></div>
                            <input type="hidden" class="status_required_input_hidden" value="0" name="status_required[]">
                            <label for="status_required_x">
                                <input type="checkbox" class="status_required_input" id="status_required_x" value="1" name="status_required[]" /> Ja
                            </label>
                        </div>
                        <div class="col-6 col-sm-4 order-2 order-sm-3">
                            <label class="sr-only" for="">Tekstvak?</label>
                            <div class="w-100 d-block mb-lg-1"></div>
                            <input type="hidden" class="status_textarea_input_hidden" value="0" name="status_textarea[]">
                            <label for="status_textarea_x">
                                <input type="checkbox" class="status_textarea_input" id="status_textarea_x" value="1" name="status_textarea[]" /> Ja
                            </label>
                        </div>
                        <div class="col-sm-12 order-4">
                            <hr class="mb-0">
                        </div>
                    </div>
                </div>
                <div class="form-group row new_css_input_form py-3">
                    <div class="col-sm-3">
                        <label for="new_status_slug">Slug *</label>
                        <input type="text" class="form-control form-control-sm" id="new_status_slug">
                    </div>
                    <div class="col-sm-3">
                        <label for="">Verplicht?</label>
                        <div class="w-100 d-block mb-lg-1"></div>
                        <label for="new_status_required">
                            <input type="checkbox" id="new_status_required" value="1" /> Ja
                        </label>
                    </div>
                    <div class="col-sm-3">
                        <label for="">Tekstvak?</label>
                        <div class="w-100 d-block mb-lg-1"></div>
                        <label for="new_status_textarea">
                            <input type="checkbox" id="new_status_textarea" value="1" /> Ja
                        </label>
                    </div>
                    <div class="col-sm-3">
                        <button class="btn btn-outline-success mt-4 mt-md-2" type="button" id="new_status_button">Toevoegen</button>
                        <div class="w-100 d-block"></div>
                        <small class="d-none text-danger" id="new_status_error">Vul slug in</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 text-right">
                <input type="hidden" name="status_key" value="{{ $statusKey }}">
                <input type="hidden" name="_token" value="{{ Session::token() }}">
                <button class="btn btn-outline-success" type="submit">Opslaan</button>
            </div>
        </div>
    </form>
</div>
@endsection