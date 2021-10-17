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
                            <th scope="col">Factuur?</th>
                            <th scope="col" style="min-width:170px">Acties</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($settings['subscription']['statuses'] as $statusKey => $status)
                            <tr class="order_status_line" data-key="{{ $statusKey }}">
                                <td>{{ $status['display_name'][ChuckSite::getFeaturedLocale()] }} <span class="badge badge-secondary badge-pill">{{ $status['short'][ChuckSite::getFeaturedLocale()] }}</span></td>
                                <td><span class="badge badge-{{ $status['send_email'] ? 'success' : 'danger' }} badge-pill">{{ $status['send_email'] ? '✓' : '✕' }}</span></td>
                                <td><span class="badge badge-{{ $status['paid'] ? 'success' : 'danger' }} badge-pill">{{ $status['paid'] ? '✓' : '✕' }}</span></td>
                                <td><span class="badge badge-{{ $status['invoice'] ? 'success' : 'danger' }} badge-pill">{{ $status['invoice'] ? '✓' : '✕' }}</span></td>
                                <td>
                                    @can('edit forms')
                                    <a href="{{ route('dashboard.module.booker.settings.index.subscriptions.statuses.edit', ['status' => $statusKey]) }}" class="btn btn-sm btn-outline-secondary rounded d-inline-block">
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
    
