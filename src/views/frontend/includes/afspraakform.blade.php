<form class="afspraakform" id="afspraak-form">
{{-- < action="{{ route('dashboard.module.booker.formhandle') }}" method="POST"> --}}
    @csrf
    <div class="step1">
        <input type="hidden" id="datepicker_date_hidden">
        <div class="form-group">
            <label class="text-white" for="soortafspraak">Soort afspraak </label>
            <select id="soortafspraak" class="soortafspraak w-100" multiple="multiple" name="afspraak" aria-describedby="soortafspraak"></select>
            <span class="error" id="soortafspraak">This field is required</span>
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
    </div>
    <div class="step2">
        <div class="form-group">
            <label class="text-white" for="location">Kies Locatie</label>
            <select id="location" class="location w-100" name="location" aria-describedby="location"></select>
            <span class="error" id="location">This field is required</span>
        </div>
        <div class="form-group pt-3">
            <label class="text-white" for="location">Kies tijslot</label>
            <select id="timeslot" class="timesmot w-100" name="timeslot" aria-describedby="timeslot"></select>
            <span class="error" id="timeslot">This field is required</span>
        </div>
    </div>
    <div class="step3">
        <div class="form-group">
            <label class="text-white" for="name">Uw Naam</label>
            <input id="name" type="text" class="form-control" name="name" aria-describedby="naam">
            <span class="error" id="naam">This field is required</span>
        </div>
        <div class="form-group pt-3">
            <label class="text-white" for="email">Uw Email</label>
            <input id="email" type="email" class="form-control" name="email" aria-describedby="e-mail">
            <span class="error" id="e-mail">This field is required</span>
        </div>
        <div class="form-group pt-3">
            <label class="text-white" for="tel">Uw Telephone</label>
            <input id="tel" type="text" class="form-control" pattern="^(?:0|\(?\+32\)?\s?|0032\s?)[1-79](?:[\.\-\s]?\d\d){4}$" name="tel" aria-describedby="phone">
            <span class="error" id="phone">This field is required</span>
        </div>
    </div>
    <div class="d-flex w-100">
        <button type="submit" class="btn btn-light ml-auto">Submit</button>
        {{-- <button class="btn btn-light ml-auto step1btn">Next</button>
        <button class="btn btn-light ml-auto step2btn d-none">Next</button>
        <button class="btn btn-light ml-auto step3btn d-none">Next</button> --}}
        {{-- <button class="btn btn-light ml-auto">Boek Afspraak</button> --}}
    </div>
</form>