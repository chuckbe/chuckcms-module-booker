<?php

namespace Chuckbe\ChuckcmsModuleBooker\Controllers;
use Chuckbe\ChuckcmsModuleBooker\Chuck\AppointmentRepository;
use Chuckbe\ChuckcmsModuleBooker\Chuck\LocationRepository;
use Chuckbe\ChuckcmsModuleBooker\Chuck\ServiceRepository;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Chuckbe\ChuckcmsModuleBooker\Models\Appointment;
use Chuckbe\ChuckcmsModuleBooker\Models\Location;
use ChuckSite;

class BookerController extends Controller
{
    private $appointmentRepository;
    private $location;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Location $location, AppointmentRepository $appointmentRepository, LocationRepository $locationRepository, ServiceRepository $serviceRepository)
    {
        $this->location = $location;
        $this->appointmentRepository = $appointmentRepository;
        $this->locationRepository = $locationRepository;
        $this->serviceRepository = $serviceRepository;
    }

    public function appointments()
    {   
        $appointments = $this->appointmentRepository->get();
        return view('chuckcms-module-booker::backend.appointments.index', compact('appointments'));
    }

    public function getAppointmentDetail(Request $request)
    {
        $appointment = $this->appointmentRepository->getById($request->appointment);
        return view('chuckcms-module-booker::backend.appointments.detail', compact('appointment'));
    }

    public function locations()
    {   
        $appointments = $this->appointmentRepository->get();
        $locations = $this->locationRepository->get();
        $services = $this->serviceRepository->get();
        return view('chuckcms-module-booker::backend.locations.index', compact('appointments', 'locations', 'services'));
    }

    public function editLocation($location_id)
    {
        $location = $this->location->getById($location_id);
        return view('chuckcms-module-booker::backend.locations.edit', compact('location'));
    }

    public function saveLocation(Request $request)
    {
        $location = $this->location->getById($request->get('location_id'));
        $json = [];
        $arr = json_decode( $request->get('location_opening_hours'), true );
        foreach($arr as $key=>$value) {
            switch ($key) {
                case "0":
                  $json["opening-hours"]["monday"] = $value;
                  break;
                case "1":
                $json["opening-hours"]["tuesday"] = $value;
                break;
                case "2":
                    $json["opening-hours"]["wednesday"] = $value;
                    break;
                case "3":
                    $json["opening-hours"]["thursday"] = $value;
                    break;
                case "4":
                    $json["opening-hours"]["friday"] = $value;
                    break;
                case "5":
                    $json["opening-hours"]["saturday"] = $value;
                    break;
                case "6":
                    $json["opening-hours"]["sunday"] = $value;
                    break;
                default:
            }
        }
        $location->name = $request->get('location_name');
        $location->lat = $request->get('location_lat');
        $location->long = $request->get('location_long');
        $location->google_calendar_id = $request->get('location_gid');
        $location->json = $json;
        $location->save();
        return redirect()->route('dashboard.module.booker.locations');
    }

    public function createLocation(Request $request)
    {
        $this->location->create([
            'name' => $request->get('location_name'),
            'lat' => $request->get('location_lat'),
            'long' => $request->get('location_long'),
            'google_calendar_id' => $request->get('location_gid')
        ]);
        return redirect()->back();
    }
    
    public function deleteLocation(Request $request)
    {
        $this->validate(request(), [
            'location_id' => 'required',
        ]);
        $status = $this->location->deleteById($request->get('location_id'));
        return $status;
    }

    public function getServices()
    {
        $services = $this->serviceRepository->get();
        return response()->json([
            'services' => $services
        ]);
    }

    public function getLocations()
    {
        $locations = $this->locationRepository->get();
        return response()->json([
            'locations' => $locations
        ]);
    }
    public function formHandle(Request $request){
        dd($request);
    }
}