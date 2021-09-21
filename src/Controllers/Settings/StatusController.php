<?php

namespace Chuckbe\ChuckcmsModuleBooker\Controllers\Settings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Chuckbe\Chuckcms\Models\Module;
use Chuckbe\Chuckcms\Models\Template;
use ChuckSite;

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

        $booker = $this->module->where('slug', 'chuckcms-module-booker')->first();
        $json = $booker->json;

        $email_key_strings = implode(',', array_keys($json['admin']['settings']['appointment']['statuses'][$statusKey]['email']));


        $this->validate($request, [
            'status_key' => 'required',
            'email_key' => 'required|not_in:'.$email_key_strings,
            'to' => 'required',
            'to_name' => 'required',
            'cc' => 'nullable',
            'bcc' => 'nullable',
            'template' => 'required',
            'logo' => 'required|in:0,1',
            'send_delivery_note' => 'required|in:0,1',
            'status_slug' => 'required|array',
            'status_required' => 'required|array',
            'status_textarea' => 'required|array',
        ]);
        $langs = ChuckSite::getSupportedLocales();

        $json['admin']['settings']['appointment']['statuses'][$statusKey]['send_email'] = true;
        $json['admin']['settings']['appointment']['statuses'][$statusKey]['email'][$emailKey]['to'] = $request->get('to');
        $json['admin']['settings']['appointment']['statuses'][$statusKey]['email'][$emailKey]['to_name'] = $request->get('to_name');
        $json['admin']['settings']['appointment']['statuses']['email'][$emailKey]['cc'] = $request->get('cc');
        $json['admin']['settings']['appointment']['statuses'][$statusKey]['email'][$emailKey]['bcc'] = $request->get('bcc');
        $json['admin']['settings']['appointment']['statuses'][$statusKey]['email'][$emailKey]['template'] = $request->get('template');
        $json['admin']['settings']['appointment']['statuses'][$statusKey]['email'][$emailKey]['logo'] = $request->get('logo') == '1' ? true : false;
        $json['admin']['settings']['appointment']['statuses'][$statusKey]['email'][$emailKey]['send_delivery_note'] = $request->get('send_delivery_note') == '1' ? true : false;
        
        $loop = 0;

        foreach ($request->get('status_slug') as $slug) {
            $json['admin']['settings']['appointment']['statuses'][$statusKey]['email'][$emailKey]['data'][$slug]['type'] = $request->get('status_textarea')[$loop] == '1' ? 'textarea' : 'text';
            foreach ($langs as $langKey => $langValue) {
                $json['admin']['settings']['appointment']['statuses'][$statusKey]['email'][$emailKey]['data'][$slug]['value'][$langKey] = '';
            }
            $json['admin']['settings']['appointment']['statuses'][$statusKey]['email'][$emailKey]['data'][$slug]['required'] = $request->get('status_required')[$loop] == '1' ? true : false;
            $json['admin']['settings']['appointment']['statuses'][$statusKey]['email'][$emailKey]['data'][$slug]['validation'] = $request->get('status_required')[$loop] == '1' ? 'required' : 'nullable';
            $loop++;
        }

        $booker->json = $json;
        $booker->update();

        return redirect()->route('dashboard.module.booker.settings.index.statuses.edit', ['status' => $statusKey])->with('notification', 'Instellingen opgeslagen!');
    }

    public function emailDelete(Request $request)
    {
        $emailKey = $request->get('email_key');
        $statusKey = $request->get('status_key');


        $booker = $this->module->where('slug', 'chuckcms-module-booker')->first();
        $json = $booker->json;

        $email_key_strings = implode(',', array_keys($json['admin']['settings']['appointment']['statuses'][$statusKey]['email']));

        $this->validate($request, [
            'status_key' => 'required',
            'email_key' => 'required|in:'.$email_key_strings,
        ]);

        $email_object = $json['admin']['settings']['appointment']['statuses'][$statusKey]['email'];

        $object = [];

        foreach ($email_object as $key => $email) {
            if($key !== $emailKey) {
                $object[$key] = $json['settings']['order']['statuses'][$statusKey]['email'][$key];
            }
        }

        $json['settings']['order']['statuses'][$statusKey]['email'] = $object;

        if(count($object) == 0) {
            $json['settings']['order']['statuses'][$statusKey]['send_email'] = false;
        }
        $booker->json = $json;
        $booker->update();
        return response()->json(['status' => 'success']);
    }
}