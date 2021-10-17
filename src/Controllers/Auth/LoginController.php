<?php

namespace Chuckbe\ChuckcmsModuleBooker\Controllers\Auth;

use Auth;
use App\Http\Controllers\Controller;
use Chuckbe\Chuckcms\Models\Template;
use Chuckbe\ChuckcmsModuleBooker\Models\Customer;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected function redirectTo() { 
        return '/' . Auth::user()->roles()->first()->redirect;
    }

    public function showLoginForm()
    {
        $templateHintpath = config('chuckcms-module-booker.auth.template.hintpath');
        $template = Template::where('active', 1)->where('hintpath', $templateHintpath)->first();
        $blade = $templateHintpath . '::templates.' . $template->slug .'.'. config('chuckcms-module-booker.auth.template.login_blade');

        if (view()->exists($blade)) {
            return view($blade, compact('template'));
        }

        return view('chuckcms::auth.login');
    }

    protected function validateLogin(\Illuminate\Http\Request $request)
    {
        $this->validate($request, [
            $this->username() => 'required|exists:users,' . $this->username() . ',active,1',
            'password' => 'required',
        ], [
            $this->username() . '.exists' => 'The selected email is invalid or the account is not active.'
        ]);
    }

    /**
     * Send the response after the user was authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    protected function sendLoginResponse(\Illuminate\Http\Request $request)
    {
        $request->session()->regenerate();

        $this->clearLoginAttempts($request);

        return $this->authenticated($request, $this->guard()->user())
                ?: redirect()->intended($this->redirectPath());
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(\Illuminate\Http\Request $request, $user)
    {
        if ($request->ajax()){

            $customer = Customer::where('user_id', $user->id)->first();

            return response()->json([
                'auth' => auth()->check(),
                'user' => $user,
                'customer' => $customer,
                'available_weight' => $customer->getAvailableWeight(),
                'available_weight_not_on_days' => $customer->getDatesWhenAvailableWeightNotAvailable(),
                'intended' => $this->redirectPath(),
                'token' => csrf_token()
            ]);

        }
    }
}
