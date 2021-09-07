<div class="modal fade stick-up disable-scroll" id="newLocationModal" tabindex="-1" role="dialog" aria-hidden="false">
    <div class="modal-dialog">
        <div class="modal-content-wrapper">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Nieuwe <span class="semi-bold">locatie</span> toevoegen</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body bg-white">
                    <p>Volgende informatie hebben we nodig om een nieuwe locatie toe te voegen.</p>
                    <form id="add-new-location" action="{{ route('dashboard.module.booker.createlocation') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="locationname">Name</label>
                            <input type="text" class="form-control" id="locationname" name="location_name" aria-describedby="locationname" placeholder="Enter location name">
                        </div>
                        <div class="form-group">
                            <div class="form-row">
                                    <div class="col">
                                        <label for="locationLatitude">Latitude</label>
                                        <input type="text" class="form-control" id="locationLatitude" name="location_lat" aria-describedby="locationLatitude" placeholder="Enter location Latitude">
                                    </div>
                                    <div class="col">
                                        <label for="locationLongitute">Longitute</label>
                                        <input type="text" class="form-control" id="locationLongitute" name="location_long" aria-describedby="locationLongitute" placeholder="Enter location Longitute">
                                    </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="gid">Google Calender ID</label>
                            <input type="text" class="form-control" id="gid" aria-describedby="gid" name="location_gid" placeholder="Enter Google Calender ID">
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>