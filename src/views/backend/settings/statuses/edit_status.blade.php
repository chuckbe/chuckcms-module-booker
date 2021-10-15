@extends('chuckcms::backend.layouts.base')

@section('title')
Bewerk status
@endsection

@section('content')
    <div class="container min-height p-3">
        <div class="row">
            <div class="col-sm-12">
                <nav aria-label="breadcumb mt-3">
                    <ol class="breadcrumb mt-3">
                        <li class="breadcrumb-item active" aria-current="instellingen">Bewerk instellingen</li>
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
            <div class="col-sm-12">
                <div class="my-3">
                    <ul class="nav nav-tabs justify-content-start" id="pageTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="s_general-tab" data-toggle="tab" href="#s_general" role="tab" aria-controls="s_general" aria-selected="true">Algemeen</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="s_emails-tab" data-toggle="tab" href="#s_emails" role="tab" aria-controls="s_emails" aria-selected="false">E-mails</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <form action="{{ route('dashboard.module.booker.settings.index.statuses.update') }}" method="POST">
            <div class="row tab-content bg-light shadow-sm rounded p-3 mb-3 mx-1" id="pageTabContent">
                <div class="col-sm-12 tab-pane fade show active" id="s_general" role="tabpanel" aria-labelledby="s_general-tab">
                    <div class="form-group row required">
                        <div class="col-sm-4">
                            <label class="sr-only" for="">Voorschot betaald?</label>
                            <div class="w-100 d-block mb-lg-1"></div>
                            <input type="hidden" class="boolean_checkbox_input_hidden" value="0" name="is_deposit_paid" @if($status['deposit_paid'] == true) disabled @endif>
                            <label for="is_deposit_paid">
                                <input type="checkbox" class="boolean_checkbox_input" id="is_deposit_paid" value="{{ $status['deposit_paid'] == true ? 1 : 0 }}" name="is_deposit_paid" @if($status['deposit_paid'] == true) checked @endif /> Voorschot betaald?
                            </label>
                        </div>
                        <div class="col-sm-4">
                            <label class="sr-only" for="">Betaald?</label>
                            <div class="w-100 d-block mb-lg-1"></div>
                            <input type="hidden" class="boolean_checkbox_input_hidden" value="0" name="is_paid" @if($status['paid'] == true) disabled @endif>
                            <label for="is_paid">
                                <input type="checkbox" class="boolean_checkbox_input" id="is_paid" value="{{ $status['paid'] == true ? 1 : 0 }}" name="is_paid" @if($status['paid'] == true) checked @endif /> Betaald?
                            </label>
                        </div>
                        <div class="col-sm-4">
                            <label class="sr-only" for="">Factuur?</label>
                            <div class="w-100 d-block mb-lg-1"></div>
                            <input type="hidden" class="boolean_checkbox_input_hidden" value="0" name="has_invoice" @if($status['invoice'] == true) disabled @endif>
                            <label for="has_invoice">
                                <input type="checkbox" class="boolean_checkbox_input" id="has_invoice" value="{{ $status['invoice'] == true ? 1 : 0 }}" name="has_invoice" @if($status['invoice'] == true) checked @endif /> Factuur?
                            </label>
                        </div>
                    </div>
                    <ul class="nav nav-tabs justify-content-start mb-1"  role="tablist">
                        @foreach(ChuckSite::getSupportedLocales() as $langKey => $langValue)
                            <li class="nav-item" role="presentation">
                            <a href="#" class="nav-link{{ $loop->iteration == 1 ? ' active' : '' }}" @if($loop->iteration == 1) class="active" @endif data-toggle="tab" data-target="#tab_product_{{ $langKey }}"><span>{{ $langValue['name'] }} ({{ $langValue['native'] }})</span></a>
                            </li>
                        @endforeach
                    </ul>
                    <div class="tab-content">
                        @foreach(ChuckSite::getSupportedLocales() as $langKey => $langValue)
                        <div class="tab-pane fade show @if($loop->iteration == 1) active @endif" id="tab_product_{{ $langKey }}">
                                <div class="form-group form-group-default required">
                                <label>Naam</label>
                                <input type="text" class="form-control" placeholder="Naam" name="display_name[{{ $langKey }}]" value="{{ $status['display_name'][$langKey] }}" required>
                                </div>

                                <div class="form-group form-group-default required">
                                <label>Verkorte Naam</label>
                                <input type="text" class="form-control" placeholder="Verkorte Naam" name="short[{{ $langKey }}]" value="{{ $status['short'][$langKey] }}" required>
                                </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="col-sm-12 tab-pane fade" id="s_emails" role="tabpanel" aria-labelledby="s_emails-tab">
                    <div class="row">
                        <div class="col-sm-12 text-right">
                            <a href="{{ route('dashboard.module.booker.settings.index.statuses.email.new', ['status' => $statusKey]) }}" class="btn btn-sm btn-outline-success">Nieuwe E-mail</a>
                        </div>
                    </div>
                    <input type="hidden" name="status_key" value="{{ $statusKey }}">
                    @if($status['send_email'])
                        <input type="hidden" value="1" name="_has_email">
                        @foreach($status['email'] as $emailKey => $email)
                            <div class="row mb-3 status_email_row_{{ $emailKey }}">
                                <div class="col-sm-12">
                                    <h4 class="text-capitalize"><a href="#" class="btn btn-sm btn-outline-danger delete_email" data-email-key="{{ $emailKey }}" data-status-key="{{ $statusKey }}"><i class="fa fa-trash"></i></a> {{ $emailKey }}</h4>
                                    <input type="hidden" name="email_key[]" value="{{ $emailKey }}">
                                    <hr>
                                </div>
                            </div>
                            <div class="form-group row mb-3 status_email_row_{{ $emailKey }}">
                                <div class="col-sm-6">
                                    <label for="email_to">E-mailadres geadresseerde *</label>
                                    <input type="text" class="form-control" id="email_to" name="to[{{ $emailKey }}]" value="{{ $email['to'] }}" required>
                                </div>
                                <div class="col-sm-6">
                                    <label for="email_to_name">Naam geadresseerde *</label>
                                    <input type="text" class="form-control" id="email_to_name" name="to_name[{{ $emailKey }}]" value="{{ $email['to_name'] }}" required>
                                </div>
                            </div>
                            <div class="form-group row mb-3 status_email_row_{{ $emailKey }}">
                                <div class="col-sm-6">
                                    <label for="email_cc">CC (gescheiden door komma's)</label>
                                    <input type="text" class="form-control" id="email_cc" name="cc[{{ $emailKey }}]" value="{{ $email['cc'] ?? '' }}">
                                </div>
                                <div class="col-sm-6">
                                    <label for="email_bcc">BCC (gescheiden door komma's)</label>
                                    <input type="text" class="form-control" id="email_bcc" name="bcc[{{ $emailKey }}]" value="{{ $email['bcc'] ?? '' }}">
                                </div>
                            </div>
                            <div class="form-group row mb-3 status_email_row_{{ $emailKey }}">
                                <div class="col-sm-6">
                                    <label for="email_template">Template</label>
                                    <input type="text" class="form-control" id="email_template" name="template[{{ $emailKey }}]" value="{{ $email['template'] }}">
                                </div>
                                <div class="col-sm-3 pt-3">
                                    <label class="sr-only" for="">Logo?</label>
                                    <div class="w-100 d-block mb-lg-1"></div>
                                    <input type="hidden" class="boolean_checkbox_input_hidden" value="0" name="logo[{{ $emailKey }}]" @if($email['logo'] == true) disabled @endif>
                                    <label for="email_logo">
                                        <input type="checkbox" class="boolean_checkbox_input" id="email_logo" value="{{ $email['logo'] == true ? 1 : 0 }}" name="logo[{{ $emailKey }}]" @if($email['logo'] == true) checked @endif /> Logo?
                                    </label>
                                </div>
                                <div class="col-sm-3 pt-3">
                                    <label class="sr-only" for="">iCal bestand toevoegen?</label>
                                    <div class="w-100 d-block mb-lg-1"></div>
                                    <input type="hidden" class="boolean_checkbox_input_hidden" value="0" name="ics[{{ $emailKey }}]" @if(array_key_exists('ics', $email) && $email['ics'] == true) disabled @endif>
                                    <label for="ics{{ $emailKey }}">
                                        <input type="checkbox" class="boolean_checkbox_input" id="ics{{ $emailKey }}" value="{{ array_key_exists('ics', $email) && $email['ics'] == true ? 1 : 0 }}" name="ics[{{ $emailKey }}]" @if(array_key_exists('ics', $email) && $email['ics'] == true) checked @endif /> iCal bestand toevoegen?
                                    </label>
                                </div>
                            </div>
                            <ul class="nav nav-tabs justify-content-start mb-1 status_email_row_{{ $emailKey }}"  role="tablist">
                                @foreach(ChuckSite::getSupportedLocales() as $langKey => $langValue)
                                    <li class="nav-item" role="presentation">
                                        <a href="#" class="nav-link{{ $loop->iteration == 1 ? ' active' : '' }}" @if($loop->iteration == 1) class="active" @endif data-toggle="tab" data-target="#tab_email_data_{{ $emailKey }}_{{ $langKey }}"><span>{{ $langValue['name'] }} ({{ $langValue['native'] }})</span></a>
                                    </li>
                                @endforeach
                            </ul>
                            <div class="tab-content status_email_row_{{ $emailKey }}">
                                 @foreach(ChuckSite::getSupportedLocales() as $langKey => $langValue)
                                    <div class="tab-pane fade show @if($loop->iteration == 1) active @endif" id="tab_email_data_{{ $emailKey }}_{{ $langKey }}">
                                        <div class="form-group row my-3">
                                            @foreach($email['data'] as $dataKey => $data)
                                                <div class="col-sm-12 mb-3">
                                                    <label for="email_data_{{ $dataKey }}" class="text-capitalize">{{ $dataKey }}</label>
                                                    @if($data['type'] == 'text')
                                                    <input type="text" class="form-control" id="email_data_{{ $dataKey }}" name="email[{{ $emailKey }}][data][{{ $dataKey }}][{{ $langKey }}]" value="{{ $data['value'][$langKey] }}" @if($data['required']) required @endif>
                                                    @elseif($data['type'] == 'textarea')
                                                    <textarea name="email[{{ $emailKey }}][data][{{ $dataKey }}][{{ $langKey }}]" id="email_data_{{ $dataKey }}" rows="7" class="form-control" @if($data['required']) required @endif>{{ $data['value'][$langKey] }}</textarea>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                 @endforeach
                            </div>
                        @endforeach
                        @else
                        <input type="hidden" value="0" name="_has_email">
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 text-right">
                    <input type="hidden" name="_token" value="{{ Session::token() }}">
                    <button class="btn btn-outline-success" type="submit">Opslaan</button>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
<script src="{{ URL::to('vendor/laravel-filemanager/js/lfm.js') }}"></script>
<script>
var a_token = "{{ Session::token() }}";
var remove_email_from_status_url = "{{ route('dashboard.module.booker.settings.index.statuses.email.delete') }}";
$( document ).ready(function() {
    $('body').on('change', '.boolean_checkbox_input', function() {
        if($(this).is(':checked')) {
            $(this).val(1);
            $(this).parent('label').siblings('input').prop('disabled', true);
        } else {
            $(this).val(0);
            $(this).parent('label').siblings('input').prop('disabled', false);
        }
    });
    $('body').on('click', '.delete_email', function(event) {
        event.preventDefault();
        r = confirm("Are you sure you want to delete this email?");
        if (r == true) {
            status_key = $(this).attr('data-status-key');
            email_key = $(this).attr('data-email-key');
            $.ajax({
                method: 'POST',
                url: remove_email_from_status_url,
                data: { 
                    status_key: status_key,
                    email_key: email_key, 
                    _token: a_token
                }
            }).done(function(data) {
                if(data.status == 'success') {
                    $('.status_email_row_'+email_key).remove();
                    if($('.delete_email').length == 0){
                        $('input[name=_has_email]').val('0');
                    }
                }
            });
        } 
        return;
    });
    init();
    function init () {
        //init media manager inputs 
        var domain = "{{ URL::to('dashboard/media')}}";
        $('#lfm').filemanager('image', {prefix: domain});
    }
});
</script>
@if (session('notification'))
    @include('chuckcms::backend.includes.notification')
@endif
@endsection