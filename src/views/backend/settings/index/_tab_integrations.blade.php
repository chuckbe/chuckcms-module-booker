<form action="{{ route('dashboard.module.booker.settings.index.customer.update') }}" method="POST">
    <div class="row">
        <div class="col-sm-12">
            <h6><b>mollie</b></h6>
        </div>
        <div class="col-lg-12">
            <div class="form-group form-group-default ">
                <label>mollie API key</label>
                <input type="text" class="form-control" placeholder="eg test_XXXXXXXXXXXXXXXXXXXX" name="integrations[mollie][key]" value="{{ array_key_exists('integrations', $settings) ? $settings['integrations']['mollie']['key'] : '' }}">
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