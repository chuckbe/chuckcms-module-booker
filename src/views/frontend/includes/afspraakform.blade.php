<form class="afspraakform">
    @csrf
    <div class="form-group">
        <label class="text-white" for="soortafspraak">Soort afspraak </label>
        <select id="soortafspraak" class="soortafspraak w-100"></select>
    </div>
    <div class="form-group pt-3">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" value="" id="checkmedicalquestionnaire" required>
            <label class="form-check-label text-white" for="checkmedicalquestionnaire">
                Ik ga akkoord met de <strong>medische vragenlijst</strong>
            </label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" value="" id="checkterms" required>
            <label class="form-check-label text-white" for="checkterms">
                Ik ga akkoord met de <strong>algemene voorwaarden</strong>
            </label>
        </div>
    </div>
    <div class="d-flex w-100">
        <button type="submit" class="btn btn-light ml-auto">Boek Afspraak</button>
    </div>
</form>