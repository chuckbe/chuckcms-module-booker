<?php

namespace Chuckbe\ChuckcmsModuleBooker\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Chuckbe\ChuckcmsModuleBooker\Chuck\ServiceRepository;
use Chuckbe\ChuckcmsModuleBooker\Requests\StoreServiceRequest;

class ServiceController extends Controller
{
    private $serviceRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ServiceRepository $serviceRepository)
    {
        $this->serviceRepository = $serviceRepository;
    }

    /**
     * Return the services overview page.
     *
     * @return Illuminate/View/View
     */
    public function index()
    {   
        $services = $this->serviceRepository->get();

        return view('chuckcms-module-booker::backend.services.index', compact('services'));
    }

    /**
     * Return the services create page.
     *
     * @return Illuminate/View/View
     */
    public function create()
    {
        return view('chuckcms-module-booker::backend.services.create');
    }

    /**
     * Return the services detail page for given service.
     *
     * @param Service $service
     * 
     * @return Illuminate/View/View
     */
    public function detail(Service $service)
    {
        return view('chuckcms-module-booker::backend.services.detail', compact('service'));
    }

    /**
     * Return the services edit page for given service.
     *
     * @param Service $service
     * 
     * @return Illuminate/View/View
     */
    public function edit(Service $service)
    {
        return view('chuckcms-module-booker::backend.services.edit', compact('service'));
    }

    /**
     * Save a new service from the request.
     *
     * @param StoreServiceRequest $request
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function store(StoreServiceRequest $request)
    {
        $this->serviceRepository->createOrUpdate($request);

        return redirect()->route('dashboard.module.booker.services.index');
    }

    /**
     * Delete the given service.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function delete(Request $request)
    {
        $this->validate(request(), [
            'service_id' => 'required',
        ]);

        $service = $this->serviceRepository->find($request->get('service_id'));

        if (!$service) {
            return response()->json(['status' => 'error'], 404);
        }

        if ($this->serviceRepository->delete($service)) {
            return response()->json(['status' => 'success'], 200);
        }

        return response()->json(['status' => 'error'], 404);
    }
}