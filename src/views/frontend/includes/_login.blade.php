<div class="modal fade stick-up disable-scroll" id="cmb_login_modal" tabindex="-1" role="dialog" aria-hidden="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header clearfix text-left text-end">
                <h5 class="modal-title">Meld je <span class="semi-bold">hier</span> aan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div data-cmb-login-errors>
                    @if($errors->any())
                    @foreach ($errors->all() as $error)
                    <p data-cmb-login-error class="text-danger">{{ $error }}</p>
                    @endforeach
                    @endif
                </div>
                <form class="cmb_login_form" role="form">
                    <div class="form-group-attached">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group mb-3 form-group-default required">
                                    <label class="sr-only">E-mailadres *</label>
                                    <input type="email" name="email" placeholder="E-mailadres *" class="form-control" autocomplete="username" required>
                                    <small class="text-danger error-msg error-email"></small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group mb-3 form-group-default required">
                                    <label class="sr-only">Wachtwoord *</label>
                                    <input type="password" name="password" placeholder="Wachtwoord *" class="form-control" autocomplete="current-password" required>
                                    <small class="text-danger error-msg error-password"></small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 m-t-10 sm-m-t-10">
                            <input type="hidden" name="create">
                            <input type="hidden" name="_token" value="{{ Session::token() }}">
                            <button type="button" class="btn btn-default" data-dismiss="modal" aria-hidden="true">Annuleren</button>
                            <button type="button" class="btn btn-dark float-right float-end" id="cmb_login_modal_confirm_btn">Aanmelden</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>