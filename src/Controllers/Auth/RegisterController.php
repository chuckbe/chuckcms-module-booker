<?php

namespace Chuckbe\ChuckcmsModuleBooker\Controllers\Auth;

use Chuckbe\ChuckcmsModuleBooker\Models\Customer;

use Chuckbe\Chuckcms\Models\User;
use Chuckbe\Chuckcms\Models\Template;

use Chuckbe\Chuckcms\Chuck\UserRepository;

use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class RegisterController extends BaseController
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/mijn-account';

    protected function redirectTo() { 
        return '/mijn-account';
    }

    /**
     * User Repository.
     *
     * @var string
     */
    private $userRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
        $this->middleware('guest');
    }

    public function showRegistrationForm()
    {
        $templateHintpath = config('chuckcms-module-order-form.auth.template.hintpath');
        $template = Template::where('active', 1)->where('hintpath', $templateHintpath)->first();
        $blade = $templateHintpath.'::templates.'.$templateHintpath.'.'.config('chuckcms-module-order-form.auth.template.registration_blade');
        
        if (view()->exists($blade)) {
            return view($blade, compact('template'));
        }

        return view('chuckcms::auth.register');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'name' => $data['surname'].' '.$data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'token' => $this->userRepository->createToken(),
            'active' => 1,
        ]);
        $user->assignRole('customer');

        $customer = new Customer;
        $customer->user_id = $user->id;
        $customer->surname = $data['surname'];
        $customer->name = $data['name'];
        $customer->email = $data['email'];
        $dob = false;
        if( array_key_exists('day', $data) && !is_null($data['day']) && !is_null($data['month']) && !is_null($data['year']) ) {
            $customer->dob = $data['year'].'-'.$data['month'].'-'.$data['day'];
            $dob = true;
        } else {
            $customer->dob = null;
        }
        $customer->tel = null;
        $json = [];
        $json['loyalty_points'] = 0;
        if( array_key_exists('promo_acceptance', $data) ) {
            $json['promo'] = true;
        } else {
            $json['promo'] = false;
        }
        $json['ean'] = $this->generateEanForCustomer();

        $customer->json = $json;
        $customer->save();

        return $user;
    }

    public function generateEanForCustomer()
    {
        $uid = rand(100000000000, 999999999999);
        $uids = Customer::where('json->ean', $uid)->get();
        if (count($uids) > 0) {
            $this->generateEanForCustomer();
        } else {
            return $uid;
        }
    }
}
