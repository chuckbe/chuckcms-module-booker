<form action="{{ route('dashboard.module.booker.settings.index.integrations.update') }}" method="POST">
    <div class="row">
        <div class="col-sm-12">
            <h6><b>mollie</b></h6>
        </div>
        <div class="col-lg-12">
            <div class="form-group form-group-default ">
                <label>mollie API key</label>
                <input type="text" class="form-control" placeholder="eg test_XXXXXXXXXXXXXXXXXXXX" name="integrations[mollie][key]" value="{{ array_key_exists('integrations', $settings) ? $settings['integrations']['mollie']['key'] : '' }}">
            </div>
            <div class="form-group form-group-default required ">
                <label>Actieve betaalmethodes</label>
                <select class="form-control" data-init-plugin="select2" name="integrations[mollie][methods][]" data-minimum-results-for-search="5" multiple required>
                    @foreach(config('chuckcms-module-booker.integrations.mollie.methods') as $methodKey => $method)
                    <option value="{{ $methodKey }}" {{ array_key_exists('integrations', $settings) ? in_array($methodKey, $settings['integrations']['mollie']['methods']) ? 'selected' : '' : '' }}>{{ $method['display_name'] }}</option>
                    @endforeach
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