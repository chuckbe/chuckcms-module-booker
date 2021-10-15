<?php

namespace Chuckbe\ChuckcmsModuleBooker\Controllers\Settings;

use App\Http\Requests;
use Illuminate\Http\Request;
use Chuckbe\Chuckcms\Models\Module;
use App\Http\Controllers\Controller;

class GeneralController extends Controller
{
    private $module;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Module $module)
    {
        $this->module = $module;
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'general.supported_currencies' => 'required|array',
            'general.featured_currency' => 'required',

            'general.decimals' => 'required',
            'general.decimals_separator' => 'required',
            'general.thousands_separator' => 'required',
            
            'general.conditions' => 'required',
            'general.medical_declaration' => 'required'
        ]);

        $booker = $this->module->where('slug', 'chuckcms-module-booker')->first();
        
        $json = $booker->json;
        $json['settings']['general']['conditions'] = $request->get('general')['conditions'];
        $json['settings']['general']['medical_declaration'] = $request->get('general')['medical_declaration'];

        $booker->json = $json;
        $booker->update();

        return redirect()->route('dashboard.module.booker.settings.index')->with('notification', 'Instellingen opgeslagen!');
    }

}
