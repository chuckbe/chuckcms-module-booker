<div class="modal fade stick-up disable-scroll" id="cancelSubscriptionModal" tabindex="-1" role="dialog" aria-hidden="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content-wrapper">
            <div class="modal-content">
                <div class="modal-header clearfix text-left">
                    <h5 class="modal-title">Ben je zeker dat je dit <span class="semi-bold">abonnement</span> wil annuleren?</h5>
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
                    <form role="form" method="POST" action="{{ route('dashboard.module.booker.subscriptions.cancel') }}">

                        <div class="form-group mb-1">
                            <span class="d-block lead text-black font-weight-bold fw-bold">Credit nota</span>
                        </div>
                        <div class="row">
                            <div class="form-group mb-2 col-12">
                                <label>Te crediteren bedrag *</label>
                                <input class="form-control" type="number" step="0.01" min="0" name="credit" required>
                                <small>Credit nota wordt alleen gemaakt als er een factuur beschikbaar is.</small>
                            </div>
                        </div>
                    
                        <div class="form-group mb-1">
                            <span class="d-block lead text-black font-weight-bold fw-bold">E-mail</span>
                        </div>
                        <div class="row cmb_confirmation_payment_section">
                            <div class="form-group col-12 mb-0">
                                <label class="mt-0 mb-0" for="cmb_needs_cancellation_email">
                                    <small>
                                        <input type="hidden" name="email" value="0">
                                        <input type="checkbox" class="mr-2 me-2" name="email" value="1" id="cmb_needs_cancellation_email"> Verstuur annulering e-mail.
                                    </small>
                                </label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 m-t-10 sm-m-t-10">
                                <input type="hidden" name="subscription_id" value="">
                                <input type="hidden" name="_token" value="{{ Session::token() }}">
                                <button type="submit" class="btn btn-danger m-t-5 pull-right">Abonnement annuleren</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
</div>