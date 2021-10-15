<?php

namespace Chuckbe\ChuckcmsModuleBooker\Controllers\Settings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Chuckbe\Chuckcms\Models\Module;
use Chuckbe\Chuckcms\Models\Template;


class AppointmentController extends Controller
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

    public function update(Request $request)
    {
        $booker = $this->module->where('slug', 'chuckcms-module-booker')->first();
        $json = $booker->json;
        $json['settings']['appointment']['can_guest_checkout'] = $request->get('can_guest_checkout') == '1' ? true : false;
        $json['settings']['appointment']['title'] = $request->get('title');
        $booker->json = $json;
        $booker->update();
        return redirect()->route('dashboard.module.booker.settings.index')->with('notification', 'Instellingen opgeslagen!');
    }
}