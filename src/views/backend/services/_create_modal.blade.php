<div class="modal fade stick-up disable-scroll" id="createServiceModal" tabindex="-1" role="dialog" aria-hidden="false">
<div class="modal-dialog modal-lg">
  <div class="modal-content-wrapper">
    <div class="modal-content">
      <div class="modal-header clearfix text-left">
        <h5 class="modal-title">Maak een nieuwe <span class="semi-bold">dienst</span> aan</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div>
          <p class="p-b-10">Vul de volgende velden aan om de dienst aan te maken.</p>
          @if($errors->any())
            @foreach ($errors->all() as $error)
              <p class="text-danger">{{ $error }}</p>
            @endforeach
          @endif
        </div>
        <form role="form" method="POST" action="{{ route('dashboard.module.booker.services.save') }}">
          <div class="form-group-attached">
            <div class="row">
              <div class="col-md-12">
                <div class="form-group form-group-default required">
                  <label>Naam *</label>
                  <input type="text" id="create_service_name" name="name" class="form-control" required>
                </div>
              </div>
            </div>
            
            <div class="row">
              <div class="col-md-12">
                <div class="form-group form-group-default required">
                  <label>Duurtijd *</label>
                  <input type="number" min="1" max="540" steps="1" id="create_service_duration" name="duration" class="form-control" required>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-12">
                <div class="form-group form-group-default required">
                  <label>Prijs *</label>
                  <input type="number" min="0.00" steps="0.01" id="create_service_price" name="price" class="form-control" required>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-12">
                <div class="form-group form-group-default required">
                  <label>Voorschot *</label>
                  <input type="number" min="0.00" steps="0.01" id="create_service_deposit" name="deposit" class="form-control" required>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-12">
                <div class="form-group form-group-default required">
                  <label>Gewicht</label>
                  <input type="number" min="0" steps="1" max="9999" id="create_service_weight" name="weight" class="form-control" value="1" required>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-12">
                <div class="form-group form-group-default required">
                  <label>Volgorde</label>
                  <input type="number" min="0" steps="1" max="9999" id="create_service_order" name="order" class="form-control" value="{{ ($services->count() + 1) }}" required>
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