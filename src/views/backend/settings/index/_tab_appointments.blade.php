<div class="row">
    <div class="col-lg-12">
        <div class="form-group form-group-default required ">
            <div class="table-responsive">
                <table class="table" style="width:100%">
                    <thead>
                        <tr>
                            <th scope="col">Titel</th>
                            <th scope="col">E-mail?</th>
                            <th scope="col">Betaald?</th>
                            <th scope="col">Voorschot?</th>
                            <th scope="col">Factuur?</th>
                            <th scope="col" style="min-width:170px">Acties</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($settings['appointment']['statuses'] as $statusKey => $status)
                            <tr class="order_status_line" data-key="{{ $statusKey }}">
                                <td>{{ $status['display_name'][ChuckSite::getFeaturedLocale()] }} <span class="badge badge-secondary badge-pill">{{ $status['short'][ChuckSite::getFeaturedLocale()] }}</span></td>
                                <td><span class="badge badge-{{ $status['send_email'] ? 'success' : 'danger' }} badge-pill">{{ $status['send_email'] ? '✓' : '✕' }}</span></td>
                                <td><span class="badge badge-{{ $status['paid'] ? 'success' : 'danger' }} badge-pill">{{ $status['paid'] ? '✓' : '✕' }}</span></td>
                                <td><span class="badge badge-{{ $status['deposit_paid'] ? 'success' : 'danger' }} badge-pill">{{ $status['deposit_paid'] ? '✓' : '✕' }}</span></td>
                                <td><span class="badge badge-{{ $status['invoice'] ? 'success' : 'danger' }} badge-pill">{{ $status['invoice'] ? '✓' : '✕' }}</span></td>
                                <td>
                                    @can('edit forms')
                                    <a href="{{ route('dashboard.module.booker.settings.index.statuses.edit', ['status' => $statusKey]) }}" class="btn btn-sm btn-outline-secondary rounded d-inline-block">
                                        <i class="fa fa-pen"></i> edit 
                                    </a>
                                    @endcan
                                    <a href="#" class="btn btn-sm btn-outline-danger rounded d-inline-block form_delete" data-id="0">
                                        <i class="fa fa-trash"></i> delete 
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<form action="{{ route('dashboard.module.booker.settings.index.appointments.update') }}" method="POST">
    <div class="row column-seperation">
        <div class="col-lg-12">
            <div class="form-group form-group-default required ">
                <label>Kan er als gast worden geboekt? <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" title="Zonder dat de klant een account moet maken."></i></label>
                <select class="full-width select2 form-control" data-init-plugin="select2" name="can_guest_checkout">
                    <option value="1" @if($settings['appointment']['can_guest_checkout'] == true) selected @endif>Ja</option>
                    <option value="0" @if($settings['appointment']['can_guest_checkout']  !== true) selected @endif>Nee</option>
                </select>
            </div>

            <div class="form-group form-group-default required ">
                <label>Gratis eerste sessie voor nieuwe klanten? <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" title="De klant krijgt zijn eerste afspraak een gratis sessie. Ter validatie wordt er gekeken naar het e-mailadres."></i></label>
                <select class="full-width select2 form-control" data-init-plugin="select2" name="can_guest_checkout">
                    <option value="1" @if(array_key_exists('free_session', $settings['appointment']) && $settings['appointment']['free_session'] == true) selected @endif>Ja</option>
                    <option value="0" @if((array_key_exists('free_session', $settings['appointment']) && $settings['appointment']['free_session'] !== true) || !array_key_exists('free_session', $settings['appointment'])) selected @endif>Nee</option>
                </select>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-sm-12 text-right">
            <input type="hidden" name="_token" value="{{ Session::token() }}">
            <button class="btn btn-outline-success" type="submit">Opslaan</button>
        </div>
    </div>
</form>
    
