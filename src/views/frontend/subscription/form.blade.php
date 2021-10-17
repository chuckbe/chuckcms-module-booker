<div class="container" style="min-height:450px">
    <div class="row">
        <div class="col-sm-8 offset-sm-2">
            <form class="cmb_booker_subscription_app" action="">
                @include('chuckcms-module-booker::frontend.subscription.includes._steps')
            </form>
            @guest
            @include('chuckcms-module-booker::frontend.includes._login')
            @endguest
        </div>
    </div>
    @include('chuckcms-module-booker::frontend.includes._modal_general_conditions')
    @include('chuckcms-module-booker::frontend.includes._modal_medical_declaration')
</div>