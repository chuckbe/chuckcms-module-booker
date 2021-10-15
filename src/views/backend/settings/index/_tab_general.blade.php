<form action="{{ route('dashboard.module.booker.settings.index.general.update') }}" method="POST">
    <div class="row">
        <div class="col-lg-12">
            <div class="form-group">
                <label>Actieve Valuta</label>
                <select class="form-control" name="general[supported_currencies][]" multiple>
                @foreach(config('chuckcms-module-booker.currencies') as $currency => $currencyName)
                  <option value="{{$currency}}" @if( in_array($currency, $settings['general']['supported_currencies']) ) selected @endif>{{ $currencyName }}</option>
                @endforeach
                </select>
            </div>

            <div class="form-group">
              <label>Hoofdvaluta</label>
              <select class="form-control" name="general[featured_currency]">
                @foreach($settings['general']['supported_currencies'] as $currency)
                  <option value="{{$currency}}" @if( $currency == $settings['general']['featured_currency'] ) selected @endif>{{ config('chuckcms-module-booker.currencies')[$currency] }}</option>
                @endforeach
              </select>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group form-group-default required ">
              <label>Aantal decimalen *</label>
              <input type="number" min="0" max="6" step="1" class="form-control" placeholder="Aantal decimalen" name="general[decimals]" value="{{ array_key_exists('general', $settings) ? $settings['general']['decimals'] : 2 }}" onkeyup="this.value=this.value.replace(/[^\d]/,'')" required>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group form-group-default required ">
              <label>Decimalen *</label>
              <input type="text" class="form-control" placeholder="Scheidingsteken" name="general[decimals_separator]" value="{{ array_key_exists('general', $settings) ? $settings['general']['decimals_separator'] : ',' }}" required>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group form-group-default required ">
              <label>Duizendtallen *</label>
              <input type="text" class="form-control" placeholder="Scheidingsteken" name="general[thousands_separator]" value="{{ array_key_exists('general', $settings) ? $settings['general']['thousands_separator'] : '.' }}" required>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group form-group-default required">
              <label>Algemene voorwaarden</label>
              <textarea class="form-control" name="general[conditions]" cols="30" rows="10">{{ array_key_exists('general', $settings) ? $settings['general']['conditions'] : '.' }}</textarea>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group form-group-default required">
              <label>Medische verklaring</label>
              <textarea class="form-control" name="general[medical_declaration]" cols="30" rows="10">{{ array_key_exists('general', $settings) ? $settings['general']['medical_declaration'] : '.' }}</textarea>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 text-right">
            <input type="hidden" name="_token" value="{{ Session::token() }}">
            <button class="btn btn-outline-success" type="submit">Opslaan</button>
        </div>
    </div>
</form>