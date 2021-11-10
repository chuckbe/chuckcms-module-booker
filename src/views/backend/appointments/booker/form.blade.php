<div class="container" style="min-height:450px">
    <div class="row">
        <div class="col-sm-12">
            <form class="cmb_booker_app" action="">
                @include('chuckcms-module-booker::backend.appointments.booker.includes._steps')
            </form>
        </div>
    </div>
    @include('chuckcms-module-booker::frontend.includes._modal_general_conditions')
    @include('chuckcms-module-booker::frontend.includes._modal_medical_declaration')
</div>