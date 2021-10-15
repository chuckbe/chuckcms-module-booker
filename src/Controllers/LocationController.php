<?php

namespace Chuckbe\ChuckcmsModuleBooker\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Chuckbe\ChuckcmsModuleBooker\Models\Location;
use Chuckbe\ChuckcmsModuleBooker\Chuck\ServiceRepository;
use Chuckbe\ChuckcmsModuleBooker\Chuck\LocationRepository;
use Chuckbe\ChuckcmsModuleBooker\Requests\StoreLocationRequest;

class LocationController extends Controller
{
    private $locationRepository;
    private $serviceRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        LocationRepository $locationRepository,
        ServiceRepository $serviceRepository)
    {
        $this->locationRepository = $locationRepository;
        $this->serviceRepository = $serviceRepository;
    }

    /**
     * Return the locations overview page.
     *
     * @return Illuminate\View\View
     */
    public function index()
    {
        $locations = $this->locationRepository->get();
        $services = $this->serviceRepository->get();

        return view('chuckcms-module-booker::backend.locations.index', compact('locations', 'services'));
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
        $services = $this->serviceRepository->get();

        return view('chuckcms-module-booker::backend.locations.edit', compact('location', 'services'));
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
}