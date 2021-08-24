<?php

namespace Chuckbe\ChuckcmsModuleBooker\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Chuckbe\Chuckcms\Models\FormEntry;
use ChuckSite;

class BookerController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    public function index()
    {   
        return view('chuckcms-module-booker::backend.dashboard.index');
    }
}