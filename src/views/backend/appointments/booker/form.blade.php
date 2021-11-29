<div class="container" style="min-height:450px">
    <div class="row">
        <div class="col-sm-12">
            <form class="cmb_booker_app" action="">
                @include('chuckcms-module-booker::backend.appointments.booker.includes._steps')
                <div class="cmb_qr_code d-none">
                	<div class="row">
                		<div class="col-sm-12 text-center">
                			<img src="" width="180" height="180" class="cmb_qr_code_img mx-auto m-5">
                		</div>
                		<div class="col-sm-12 text-center">
                			<span class="badge badge-light cmb_qr_code_status">
                				<i class="fas fa-spinner fa-pulse"></i> Status checken...
                			</span>
                		</div>
                	</div>
                </div>
            </form>
        </div>
    </div>
    @include('chuckcms-module-booker::frontend.includes._modal_general_conditions')
    @include('chuckcms-module-booker::frontend.includes._modal_medical_declaration')
</div>