<?php

namespace Chuckbe\ChuckcmsModuleBooker\Controllers\Settings;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Chuckbe\Chuckcms\Models\Module;

class IntegrationsController extends Controller
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
            'integrations.mollie.key' => 'required',
            'integrations.mollie.methods' => 'required|array'
        ]);

        $booker = $this->module->where('slug', 'chuckcms-module-booker')->first();
        
        $json = $booker->json;
        $json['settings']['integrations']['mollie']['key'] = $request->get('integrations')['mollie']['key'];
        $json['settings']['integrations']['mollie']['methods'] = $request->get('integrations')['mollie']['methods'];

        $booker->json = $json;
        $booker->update();

        return redirect()->route('dashboard.module.booker.settings.index.integrations')->with('notification', 'Instellingen opgeslagen!');
    }

}