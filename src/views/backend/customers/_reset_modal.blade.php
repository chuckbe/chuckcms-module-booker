<div class="modal fade stick-up disable-scroll" id="resetCustomerPasswordModal" tabindex="-1" role="dialog" aria-hidden="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content-wrapper">
            <div class="modal-content">
                <div class="modal-header clearfix text-left">
                    <h5 class="modal-title">Reset het <span class="semi-bold">wachtwoord</span> van de klant</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Dit genereert een random wachtwoord, activeert het account van de klant en laat een link zien die je aan de klant kan bezorgen. Hiermee kan de klant zijn wachtwoord opnieuw instellen naar eigen keuze.</p>
                    <div>
                        @if($errors->any())
                        @foreach ($errors->all() as $error)
                        <p class="text-danger">{{ $error }}</p>
                        @endforeach
                        @endif
                    </div>
                    <form role="form" method="POST" action="{{ route('dashboard.module.booker.customers.get_password_reset_link') }}">
                        <div class="form-group-attached">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group form-group-default required">
                                        <label>Bevestig door 'RESET' te typen *</label>
                                        <input type="text" name="reset_text" class="form-control" required>
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