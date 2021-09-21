<?php

namespace Chuckbe\ChuckcmsModuleBooker\Controllers\Settings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Chuckbe\Chuckcms\Models\Module;
use Chuckbe\Chuckcms\Models\Template;


class CustomerController extends Controller
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
        $json['admin']['settings']['customer']['is_tel_required'] = $request->get('is_tel_required') == '1' ? true : false;
        $json['admin']['settings']['customer']['title'] = $request->get('title');
        $booker->json = $json;
        $booker->update();
        return redirect()->route('dashboard.module.booker.settings.index.customer')->with('notification', 'Instellingen opgeslagen!');
    }
}