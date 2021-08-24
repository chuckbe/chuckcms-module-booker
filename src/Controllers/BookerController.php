<?php

namespace Chuckbe\ChuckcmsModuleBooker\Controllers;
use Chuckbe\ChuckcmsModuleBooker\Chuck\LocationRepository;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Chuckbe\ChuckcmsModuleBooker\Models\Location;
use ChuckSite;

class BookerController extends Controller
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

    public function index()
    {   
        $locations = $this->locationRepository->get();
        return view('chuckcms-module-booker::backend.dashboard.index', compact('locations'));
    }
}