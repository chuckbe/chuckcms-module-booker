<?php

namespace Chuckbe\ChuckcmsModuleBooker\Controllers\Settings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Chuckbe\Chuckcms\Models\Module;
use Chuckbe\Chuckcms\Models\Template;

class StatusController extends Controller
{
    private $module;
    private $template;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Module $module, Template $template)
    {
        $this->module = $module;
        $this->template = $template;
    }

    public function edit($status)
    {
        $booker = $this->module->where('slug', 'chuckcms-module-booker')->first();
        $statusKey = $status;
        $json = $booker->json;
        $status = $json['admin']['settings']['appointment']['statuses'][$statusKey];
        
        return view('chuckcms-module-booker::backend.settings.statuses.edit_status', compact('status', 'statusKey'));

        // return view('chuckcms-module-ecommerce::backend.settings.orders.edit_status', ['module' => $ecommerce, 'templates' => $templates, 'status' => $status, 'statusKey' => $statusKey]);
    }

    public function email($status)
    {
        $booker = $this->module->where('slug', 'chuckcms-module-booker')->first();
        $statusKey = $status;
        $json = $booker->json;
        $status = $json['admin']['settings']['appointment']['statuses'][$statusKey];

        return view('chuckcms-module-booker::backend.settings.statuses.new_status_email', compact('status', 'statusKey'));
    }
    public function emailSave(Request $request)
    {
        $emailKey = $request->get('email_key');
        $statusKey = $request->get('status_key');
    }
}