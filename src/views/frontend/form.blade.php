<div class="row mx-0 py-5">
    <div class="col-6 bg-blue">
        <div class="pt-5 mx-auto" style="width: 450px">
            <div class="date-data">
                <span class="text-muted font-weight-bold" id="day"></span><br>
                <span class="lead text-white font-weight-bold" id="date"></span>
            </div>
            <div class="pt-3">
                @include('chuckcms-module-booker::frontend.includes.afspraakform')
            </div>
        </div>
    </div>
    <div class="col-6">
        @include('chuckcms-module-booker::frontend.includes.datepicker')
    </div>
</div>