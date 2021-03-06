<div class="modal fade stick-up disable-scroll" id="createGiftCardModal" tabindex="-1" role="dialog" aria-hidden="false">
<div class="modal-dialog modal-lg">
  <div class="modal-content-wrapper">
    <div class="modal-content">
      <div class="modal-header clearfix text-left">
        <h5 class="modal-title">Voeg een nieuwe <span class="semi-bold">cadeaubon</span> toe</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div>
          <p class="p-b-10">Vul de volgende velden aan om een nieuwe cadeaubon toe te voegen.</p>
          @if($errors->any())
            @foreach ($errors->all() as $error)
              <p class="text-danger">{{ $error }}</p>
            @endforeach
          @endif
        </div>
        <form role="form" method="POST" action="{{ route('dashboard.module.booker.gift_cards.save') }}">
            <div class="row" id="cmb_selectExistingCustomerWrapper">
              <div class="form-group mb-2 col-12">
                <label for="">Selecteer klant</label>
                <select name="customer" id="" class="select2 custom-select" required>
                  <option value="0" selected disabled>-- Nieuwe klant --</option>
                  @foreach($customers as $customer)
                  <option value="{{ $customer->id }}">{{ $customer->first_name }} {{ $customer->last_name }} ({{ $customer->email }})</option>
                  @endforeach
                </select>
              </div>
            </div>

            <div class="row">
              <div class="form-group mb-2 col-12">
                <label>Code *</label>
                <input class="form-control" type="text" name="code">
                <small>Laat veld leeg om een code te genereren.</small>
              </div>
            </div>

            <div class="row">
              <div class="form-group mb-2 col-12">
                <label>Prijs *</label>
                <input class="form-control" type="number" step="0.01" min="0" name="price" required>
              </div>
            </div>

            <div class="row">
              <div class="form-group mb-2 col-12">
                <label>Gewicht *</label>
                <input class="form-control" type="number" step="1" min="0" name="weight" required>
              </div>
            </div>
            
            <div class="form-group mb-1">
              <span class="d-block lead text-black font-weight-bold fw-bold">Betaling</span>
            </div>
            <div class="row cmb_confirmation_payment_section">
              <div class="form-group col-12 mb-0">
                <label class="mt-0 mb-0" for="cmb_paid">
                  <small>
                    <input type="hidden" name="paid" value="0">
                    <input type="checkbox" class="mr-2 me-2" name="paid" id="cmb_paid" value="1"> Cadeaubon is betaald.
                  </small>
                </label>
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