<div class="modal fade stick-up disable-scroll" id="createLocationModal" tabindex="-1" role="dialog" aria-hidden="false">
<div class="modal-dialog modal-lg">
  <div class="modal-content-wrapper">
    <div class="modal-content">
      <div class="modal-header clearfix text-left">
        <h5 class="modal-title">Maak een nieuwe <span class="semi-bold">locatie</span> aan</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div>
          <p class="p-b-10">Vul de volgende velden aan om de locatie aan te maken.</p>
          @if($errors->any())
            @foreach ($errors->all() as $error)
              <p class="text-danger">{{ $error }}</p>
            @endforeach
          @endif
        </div>
        <form role="form" method="POST" action="{{ route('dashboard.module.booker.locations.save') }}">
          <div class="form-group-attached">
            <div class="row">
              <div class="col-md-12">
                <div class="form-group form-group-default required">
                  <label>Naam *</label>
                  <input type="text" id="create_location_name" name="name" class="form-control" required>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-12">
                <div class="form-group form-group-default">
                  <label>Sluitingsdagen</label>
                  <select name="disabled_weekdays[]" id="create_location_disabled_weekdays" class="custom-control" multiple="multiple" >
                    <option value="1">Maandag</option>
                    <option value="2">Dinsdag</option>
                    <option value="3">Woensdag</option>
                    <option value="4">Donderdag</option>
                    <option value="5">Vrijdag</option>
                    <option value="6">Zaterdag</option>
                    <option value="0">Zondag</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-12">
                <label>Openingsuren *</label>
              </div>

              {{-- start of monday --}}
              <div class="col-sm-12 openingshoursRangeInputSection" data-day="1">
                <div>
                  <label><small>Maandag</small></label>
                  <span class="badge badge-secondary addOpeningshoursRangeInputRowBtn float-right" type="button" data-day="1">+</span>
                </div>
                <div class="row">
                  <div class="col-sm-12 openingshoursRangeInputWrapper" data-day="1">
                    <div class="row openingshoursRangeInputRow">
                      <div class="col-sm-6">
                        <div class="input-group">
                          <div class="input-group-prepend">
                            <button class="btn btn-outline-secondary removeOpeningshoursRangeInputRowBtn d-none" type="button">-</button>
                          </div>
                          <input type="time" class="form-control" name="start_time_monday[]" value="08:00" required>
                        </div>
                      </div>
                      <div class="col-sm-6">
                        <div class="form-group form-group-default">
                          <input type="time" class="form-control" name="end_time_monday[]" value="17:00" required>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              {{-- end of monday --}}

              {{-- start of tuesday --}}
              <div class="col-sm-12 openingshoursRangeInputSection" data-day="2">
                <div>
                  <label><small>Dinsdag</small></label>
                  <span class="badge badge-secondary addOpeningshoursRangeInputRowBtn float-right" type="button" data-day="2">+</span>
                </div>
                <div class="row">
                  <div class="col-sm-12 openingshoursRangeInputWrapper" data-day="2">
                    <div class="row openingshoursRangeInputRow">
                      <div class="col-sm-6">
                        <div class="input-group">
                          <div class="input-group-prepend">
                            <button class="btn btn-outline-secondary removeOpeningshoursRangeInputRowBtn d-none" type="button">-</button>
                          </div>
                          <input type="time" class="form-control" name="start_time_tuesday[]" value="08:00" required>
                        </div>
                      </div>
                      <div class="col-sm-6">
                        <div class="form-group form-group-default">
                          <input type="time" class="form-control" name="end_time_tuesday[]" value="17:00" required>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              {{-- end of tuesday --}}

              {{-- start of wednesday --}}
              <div class="col-sm-12 openingshoursRangeInputSection" data-day="3">
                <div>
                  <label><small>Woensdag</small></label>
                  <span class="badge badge-secondary addOpeningshoursRangeInputRowBtn float-right" type="button" data-day="3">+</span>
                </div>
                <div class="row">
                  <div class="col-sm-12 openingshoursRangeInputWrapper" data-day="3">
                    <div class="row openingshoursRangeInputRow">
                      <div class="col-sm-6">
                        <div class="input-group">
                          <div class="input-group-prepend">
                            <button class="btn btn-outline-secondary removeOpeningshoursRangeInputRowBtn d-none" type="button">-</button>
                          </div>
                          <input type="time" class="form-control" name="start_time_wednesday[]" value="08:00" required>
                        </div>
                      </div>
                      <div class="col-sm-6">
                        <div class="form-group form-group-default">
                          <input type="time" class="form-control" name="end_time_wednesday[]" value="17:00" required>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              {{-- end of wednesday --}}

              {{-- start of thursday --}}
              <div class="col-sm-12 openingshoursRangeInputSection" data-day="4">
                <div>
                  <label><small>Donderdag</small></label>
                  <span class="badge badge-secondary addOpeningshoursRangeInputRowBtn float-right" type="button" data-day="4">+</span>
                </div>
                <div class="row">
                  <div class="col-sm-12 openingshoursRangeInputWrapper" data-day="4">
                    <div class="row openingshoursRangeInputRow">
                      <div class="col-sm-6">
                        <div class="input-group">
                          <div class="input-group-prepend">
                            <button class="btn btn-outline-secondary removeOpeningshoursRangeInputRowBtn d-none" type="button">-</button>
                          </div>
                          <input type="time" class="form-control" name="start_time_thursday[]" value="08:00" required>
                        </div>
                      </div>
                      <div class="col-sm-6">
                        <div class="form-group form-group-default">
                          <input type="time" class="form-control" name="end_time_thursday[]" value="17:00" required>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              {{-- end of thursday --}}

              {{-- start of friday --}}
              <div class="col-sm-12 openingshoursRangeInputSection" data-day="5">
                <div>
                  <label><small>Vrijdag</small></label>
                  <span class="badge badge-secondary addOpeningshoursRangeInputRowBtn float-right" type="button" data-day="5">+</span>
                </div>
                <div class="row">
                  <div class="col-sm-12 openingshoursRangeInputWrapper" data-day="5">
                    <div class="row openingshoursRangeInputRow">
                      <div class="col-sm-6">
                        <div class="input-group">
                          <div class="input-group-prepend">
                            <button class="btn btn-outline-secondary removeOpeningshoursRangeInputRowBtn d-none" type="button">-</button>
                          </div>
                          <input type="time" class="form-control" name="start_time_friday[]" value="08:00" required>
                        </div>
                      </div>
                      <div class="col-sm-6">
                        <div class="form-group form-group-default">
                          <input type="time" class="form-control" name="end_time_friday[]" value="17:00" required>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              {{-- end of friday --}}

              {{-- start of saturday --}}
              <div class="col-sm-12 openingshoursRangeInputSection" data-day="6">
                <div>
                  <label><small>Zaterdag</small></label>
                  <span class="badge badge-secondary addOpeningshoursRangeInputRowBtn float-right" type="button" data-day="6">+</span>
                </div>
                <div class="row">
                  <div class="col-sm-12 openingshoursRangeInputWrapper" data-day="6">
                    <div class="row openingshoursRangeInputRow">
                      <div class="col-sm-6">
                        <div class="input-group">
                          <div class="input-group-prepend">
                            <button class="btn btn-outline-secondary removeOpeningshoursRangeInputRowBtn d-none" type="button">-</button>
                          </div>
                          <input type="time" class="form-control" name="start_time_saturday[]" value="08:00" required>
                        </div>
                      </div>
                      <div class="col-sm-6">
                        <div class="form-group form-group-default">
                          <input type="time" class="form-control" name="end_time_saturday[]" value="17:00" required>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              {{-- end of saturday --}}

              {{-- start of sunday --}}
              <div class="col-sm-12 openingshoursRangeInputSection" data-day="0">
                <div>
                  <label><small>Zondag</small></label>
                  <span class="badge badge-secondary addOpeningshoursRangeInputRowBtn float-right" type="button" data-day="0">+</span>
                </div>
                <div class="row">
                  <div class="col-sm-12 openingshoursRangeInputWrapper" data-day="0">
                    <div class="row openingshoursRangeInputRow">
                      <div class="col-sm-6">
                        <div class="input-group">
                          <div class="input-group-prepend">
                            <button class="btn btn-outline-secondary removeOpeningshoursRangeInputRowBtn d-none" type="button">-</button>
                          </div>
                          <input type="time" class="form-control" name="start_time_sunday[]" value="08:00" required>
                        </div>
                      </div>
                      <div class="col-sm-6">
                        <div class="form-group form-group-default">
                          <input type="time" class="form-control" name="end_time_sunday[]" value="17:00" required>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              {{-- end of sunday --}}
            </div>
            
            <div class="row">
              <div class="col-sm-12">
                <div class="form-group form-group-default">
                  <label>Uitgesloten Datums</label>
                  <input type="checkbox" name="disabled_dates_check" value="0">
                </div>
              </div>
              <div class="col-sm-12 disabledDatesInputSection d-none">
                <div>
                  <label><small>Datum - Hele dag? - Van - Tot</small></label>
                  <span class="badge badge-secondary addDisabledDatesInputRowBtn float-right" type="button">+</span>
                </div>
                <div class="row">
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
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-sm-12">
                <div class="form-group form-group-default">
                  <label>Biedt volgende diensten aan</label>
                  <select name="services[]" id="create_location_services" class="custom-control" multiple="multiple">
                    @foreach($services as $service)
                    <option value="{{ $service->id }}">{{ $service->name }}{{ $service->isFree() ? '' : ' ('.$service->formatted_price.')' }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-12">
                <div class="form-group form-group-default required">
                  <label>Max. gewicht</label>
                  <input type="number" min="0" steps="1" max="9999" id="create_location_max_weight" name="max_weight" class="form-control" value="1" required>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-12">
                <div class="form-group form-group-default required">
                  <label>Interval</label>
                  <input type="number" min="0" steps="1" max="1440" id="create_location_interval" name="interval" class="form-control" value="1" required>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-12">
                <div class="form-group form-group-default required">
                  <label>Volgorde</label>
                  <input type="number" min="0" steps="1" max="9999" id="create_location_order" name="order" class="form-control" value="{{ ($locations->count() + 1) }}" required>
                </div>
              </div>
            </div>
          </div>
        <div class="row">
          <div class="col-md-12 m-t-10 sm-m-t-10">
            <input type="hidden" name="create">
            <input type="hidden" name="_token" value="{{ Session::token() }}">
            <button type="button" class="btn btn-default m-t-5" data-dismiss="modal" aria-hidden="true">Annuleren</button>
            <button type="submit" class="btn btn-primary m-t-5 pull-right">Aanmaken</button>
          </div>
        </div>
        </form>
      </div>
    </div>
  </div>
  <!-- /.modal-content -->
</div>
</div>
<!-- /.modal-dialog