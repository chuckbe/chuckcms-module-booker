<div class="row mx-0 py-5">
    <div class="col-6 bg-blue">
        <div class="pt-5 mx-auto" style="width: 450px">
            <div class="date-data">
                <span class="text-muted font-weight-bold" id="day"></span><br>
                <span class="lead text-white font-weight-bold" id="date"></span>
            </div>
            <div class="pt-3">
                <form class="afspraakform">
                    @csrf
                    <div class="form-group">
                        <label class="text-white" for="soortafspraak">Soort afspraak </label>
                        <select id="soortafspraak" class="soortafspraak w-100"></select>
                    </div>
                    <div class="form-group pt-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="checkmedicalquestionnaire">
                            <label class="form-check-label text-white" for="checkmedicalquestionnaire">
                                Ik ga akkoord met de <strong>medische vragenlijst</strong>
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="checkterms">
                            <label class="form-check-label text-white" for="checkterms">
                                Ik ga akkoord met de <strong>algemene voorwaarden</strong>
                            </label>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-6">
        <div id="datepicker" class="d-flex justify-content-center" data-date="{{ date("d/m/Y") }}"></div>
        <input type="hidden" id="my_hidden_input">
    </div>
</div>