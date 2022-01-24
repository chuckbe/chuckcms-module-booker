<div class="modal fade stick-up disable-scroll" id="editCustomerEmailModal" tabindex="-1" role="dialog" aria-hidden="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content-wrapper">
            <div class="modal-content">
                <div class="modal-header clearfix text-left">
                    <h5 class="modal-title">Wijzig het <span class="semi-bold">e-mailadres</span> van de klant</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div>
                        @if($errors->any())
                        @foreach ($errors->all() as $error)
                        <p class="text-danger">{{ $error }}</p>
                        @endforeach
                        @endif
                    </div>
                    <form role="form" method="POST" action="{{ route('dashboard.module.booker.customers.update_email') }}">
                        <div class="form-group-attached">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group form-group-default required">
                                        <label>Nieuw e-mailadres *</label>
                                        <input type="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" id="create_customer_email" name="email" class="form-control" value="{{ $customer->email }}" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 m-t-10 sm-m-t-10">
                                <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                                <input type="hidden" name="_token" value="{{ Session::token() }}">
                                <button type="button" class="btn btn-default m-t-5" data-dismiss="modal" aria-hidden="true">Annuleren</button>
                                <button type="submit" class="btn btn-primary m-t-5 pull-right">Wijzigen</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
</div>