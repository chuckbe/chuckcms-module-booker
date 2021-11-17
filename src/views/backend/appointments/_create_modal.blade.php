<div class="modal fade stick-up disable-scroll" id="createAppointmentModal" tabindex="-1" role="dialog" aria-hidden="false">
<div class="modal-dialog modal-lg">
    <div class="modal-content-wrapper">
        <div class="modal-content">
            <div class="modal-header clearfix text-left pb-2">
                <h5 class="modal-title">Maak een nieuwe <span class="semi-bold">afspraak</span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body pt-2">
                @include('chuckcms-module-booker::backend.appointments.booker.form', ['locations' => $locations, 'services' => $services])
            </div>
        </div>
    </div>
    <!-- /.modal-content -->
</div>
</div>