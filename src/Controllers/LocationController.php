<?php

namespace Chuckbe\ChuckcmsModuleBooker\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Chuckbe\ChuckcmsModuleBooker\Chuck\LocationRepository;
use Chuckbe\ChuckcmsModuleBooker\Requests\StoreLocationRequest;
use Chuckbe\ChuckcmsModuleBooker\Models\Location;

class LocationController extends Controller
{
    private $locationRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(LocationRepository $locationRepository)
    {
        $this->locationRepository = $locationRepository;
    }

    /**
     * Return the locations overview page.
     *
     * @return Illuminate\View\View
     */
    public function index()
    {   
        $locations = $this->locationRepository->get();

        return view('chuckcms-module-booker::backend.locations.index', compact('locations'));
    }

    /**
     * Return the locations create page.
     *
     * @return Illuminate\View\View
     */
    public function create()
    {
        return view('chuckcms-module-booker::backend.locations.create');
    }

    /**
     * Return the locations detail page for given location.
     *
     * @param Location $location
     * 
     * @return Illuminate\View\View
     */
    public function detail(Location $location)
    {
        return view('chuckcms-module-booker::backend.locations.detail', compact('location'));
    }

    /**
     * Return the locations edit page for given location.
     *
     * @param Location $location
     * 
     * @return Illuminate/View/View
     */
    public function edit(Location $location)
    {
        return view('chuckcms-module-booker::backend.locations.edit', compact('location'));
    }

    /**
     * Save a new location from the request.
     *
     * @param StoreLocationRequest $request
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function store(StoreLocationRequest $request)
    {
        $this->locationRepository->createOrUpdate($request);

        return redirect()->route('dashboard.module.booker.locations.index');
    }

    /**
     * Delete the given location.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function delete(Request $request)
    {
        $this->validate(request(), [
            'location_id' => 'required',
        ]);

        $location = $this->locationRepository->find($request->get('location_id'));

        if (!$location) {
            return response()->json(['status' => 'error'], 404);
        }

        if ($this->locationRepository->delete($location)) {
            return response()->json(['status' => 'success'], 200);
        }

        return response()->json(['status' => 'error'], 404);
    }
    // public function locations()
    // {   
    //     $appointments = $this->appointmentRepository->get();
    //     $locations = $this->locationRepository->get();
    //     $services = $this->serviceRepository->get();
    //     return view('chuckcms-module-booker::backend.locations.index', compact('appointments', 'locations', 'services'));
    // }

    // public function editLocation($location_id)
    // {
    //     $location = $this->location->getById($location_id);
    //     return view('chuckcms-module-booker::backend.locations.edit', compact('location'));
    // }

    // public function saveLocation(Request $request)
    // {
    //     $location = $this->location->getById($request->get('location_id'));
    //     $json = [];
    //     $arr = json_decode( $request->get('location_opening_hours'), true );
    //     foreach($arr as $key=>$value) {
    //         switch ($key) {
    //             case "0":
    //               $json["opening-hours"]["monday"] = $value;
    //               break;
    //             case "1":
    //             $json["opening-hours"]["tuesday"] = $value;
    //             break;
    //             case "2":
    //                 $json["opening-hours"]["wednesday"] = $value;
    //                 break;
    //             case "3":
    //                 $json["opening-hours"]["thursday"] = $value;
    //                 break;
    //             case "4":
    //                 $json["opening-hours"]["friday"] = $value;
    //                 break;
    //             case "5":
    //                 $json["opening-hours"]["saturday"] = $value;
    //                 break;
    //             case "6":
    //                 $json["opening-hours"]["sunday"] = $value;
    //                 break;
    //             default:
    //         }
    //     }
    //     $location->name = $request->get('location_name');
    //     $location->lat = $request->get('location_lat');
    //     $location->long = $request->get('location_long');
    //     $location->google_calendar_id = $request->get('location_gid');
    //     $location->json = $json;
    //     $location->save();
    //     return redirect()->route('dashboard.module.booker.locations');
    // }

    // public function createLocation(Request $request)
    // {
    //     $this->location->create([
    //         'name' => $request->get('location_name'),
    //         'lat' => $request->get('location_lat'),
    //         'long' => $request->get('location_long'),
    //         'google_calendar_id' => $request->get('location_gid')
    //     ]);
    //     return redirect()->back();
    // }
    
    
}