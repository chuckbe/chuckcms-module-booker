<form action="{{ route('dashboard.module.booker.settings.index.appointment.update') }}" method="POST">
    <div class="row column-seperation">
        <div class="col-lg-12">
            <div class="form-group form-group-default required ">
                <label>Can guest checkout</label>
                <select class="full-width select2 form-control" data-init-plugin="select2" name="can_guest_checkout">
                    <option value="1" @if($settings['appointment']['can_guest_checkout'] == true) selected @endif>Ja</option>
                    <option value="0" @if($settings['appointment']['can_guest_checkout']  !== true) selected @endif>Nee</option>
                </select>
            </div>
            <div class="form-group form-group-default required ">
                <label>Title</label>
                <input type="text" class="form-control" placeholder="{{$settings['appointment']['title']}}" name="title" value="{{$settings['appointment']['title']}}" required>
            </div>
            <div class="form-group form-group-default required ">
                <div class="table-responsive">
                    <table class="table" style="width:100%">
                        <thead>
                            <tr>
                                <th scope="col">Titel</th>
                                <th scope="col">E-mail?</th>
                                <th scope="col">Betaald?</th>
                                <th scope="col">Geleverd?</th>
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
                                    <td><span class="badge badge-{{ $status['delivery'] ? 'success' : 'danger' }} badge-pill">{{ $status['delivery'] ? '✓' : '✕' }}</span></td>
                                    <td><span class="badge badge-{{ $status['invoice'] ? 'success' : 'danger' }} badge-pill">{{ $status['invoice'] ? '✓' : '✕' }}</span></td>
                                    <td>
                                        @can('edit forms')
                                        <a href="#" class="btn btn-sm btn-outline-secondary rounded d-inline-block">
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
    <div class="row mt-4">
        <div class="col-sm-12 text-right">
            <input type="hidden" name="_token" value="{{ Session::token() }}">
            <button class="btn btn-outline-success" type="submit">Opslaan</button>
        </div>
    </div>
</form>