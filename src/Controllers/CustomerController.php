<?php

namespace Chuckbe\ChuckcmsModuleBooker\Controllers;

use Auth;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Chuckbe\Chuckcms\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Chuckbe\Chuckcms\Models\Template;
use Chuckbe\Chuckcms\Chuck\UserRepository;
use Chuckbe\ChuckcmsModuleBooker\Models\Customer;
use Chuckbe\ChuckcmsModuleBooker\Chuck\CustomerRepository;
use Chuckbe\ChuckcmsModuleBooker\Requests\StoreCustomerRequest;
use Illuminate\Support\Facades\Password;

class CustomerController extends Controller
{
    private $customerRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(CustomerRepository $customerRepository, UserRepository $userRepository)
    {
        $this->customerRepository = $customerRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * Return the customers overview page.
     *
     * @return Illuminate\View\View
     */
    public function index()
    {
        $customers = $this->customerRepository->get();

        return view('chuckcms-module-booker::backend.customers.index', compact('customers'));
    }

    /**
     * Return the customers create page.
     *
     * @return Illuminate\View\View
     */
    public function create()
    {
        return view('chuckcms-module-booker::backend.customers.create');
    }

    /**
     * Return the customers detail page for given location.
     *
     * @param Customer $customer
     * 
     * @return Illuminate\View\View
     */
    public function detail(Customer $customer)
    {
        return view('chuckcms-module-booker::backend.customers.detail', compact('customer'));
    }

    /**
     * Return the customers edit page for given location.
     *
     * @param Customer $customer
     * 
     * @return Illuminate/View/View
     */
    public function edit(Customer $customer)
    {
        return view('chuckcms-module-booker::backend.customers.edit', compact('location'));
    }

    /**
     * Save a new customer from the request.
     *
     * @param StoreCustomerRequest $request
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function store(StoreCustomerRequest $request)
    {
        if(is_null($request->make_account)){
            $this->customerRepository->makeGuestFromRequest($request);
        }else{
            $this->customerRepository->makeFromRequest($request);
        }
        // $this->customerRepository->createOrUpdate($request);

        return redirect()->route('dashboard.module.booker.customers.index');
    }

    /**
     * Update email on a customer from the request.
     *
     * @param Request $request
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function email(Request $request)
    {
        $customer = $this->customerRepository->updateEmail($request);

        return redirect()->route('dashboard.module.booker.customers.detail', ['customer' => $customer->id]);
    }

    /**
     * Update address/company on a customer from the request.
     *
     * @param Request $request
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function address(Request $request)
    {
        $customer = $this->customerRepository->updateAddress($request);

        if(! is_null($request->customer_company_name)){
            $customer = $this->customerRepository->updateCompany($request);
        }

        return redirect()->route('dashboard.module.booker.customers.detail', ['customer' => $customer->id]);
    }

    /**
     * Delete the given customer.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function delete(Request $request)
    {
        $this->validate(request(), [
            'customer_id' => 'required',
        ]);

        $customer = $this->customerRepository->find($request->get('customer_id'));

        if (!$customer) {
            return response()->json(['status' => 'error'], 404);
        }

        if ($customer->is_deletable && $this->customerRepository->delete($customer)) {
            return response()->json(['status' => 'success'], 200);
        }

        return response()->json(['status' => 'error'], 404);
    }

    /**
     * Activate the user/customer per token.
     *
     * @param $user_token
     * 
     * @return mixed
     **/
    public function activate($user_token)
    {
        if (Auth::check()) {
            return redirect()->to('/');
        }

        $user = User::where('token', $user_token)->first();

        $templateHintpath = config('chuckcms-module-booker.auth.template.hintpath');
        $template = Template::where('active', 1)->where('hintpath', $templateHintpath)->first();
        $blade = $templateHintpath . '::templates.' . $template->slug .'.'. config('chuckcms-module-booker.auth.template.activation_blade');

        if (is_null($user)) {
            return abort(404);
        }

        if (view()->exists($blade)) {
            $activated = (bool)$user->active;
            
            if (!$user->active && strlen((string)$user->password) > 0) {
                $user->token = $this->userRepository->createToken();
                $user->active = 1;
                $user->update();

                $activated = true;
            }

            return view($blade, compact('template', 'user', 'activated'));
        }

        echo 'Something went wrong, please contact the webmaster.';
        return false;
    }

    /**
     * Activate the user/customer per token.
     *
     * @param Request $request
     * 
     * @return bool
     **/
    public function activateAccount(Request $request)
    {
        $this->validate($request, [
            'user_token' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::where('token', $request->user_token)->where('active', 0)->first();
        $user->password = bcrypt($request->password);
        $user->token = $this->userRepository->createToken();
        $user->active = 1;
        $user->update();

        $templateHintpath = config('chuckcms-module-booker.auth.template.hintpath');
        $template = Template::where('active', 1)->where('hintpath', $templateHintpath)->first();
        $blade = $templateHintpath . '::templates.' . $template->slug .'.'. config('chuckcms-module-booker.auth.template.activation_blade');

        if (view()->exists($blade)) {
            $activated = true;
            return view($blade, compact('template', 'user', 'activated'));
        }

        echo 'Something went wrong, please contact the webmaster.';
        return false;
    }

    /**
     * Reactivate the given customer.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function reactivate(Request $request)
    {
        $this->validate(request(), [
            'customer_id' => 'required',
        ]);

        $customer = $this->customerRepository->find($request->get('customer_id'));
        $user = $customer->user;

        $user->password = '';
        $user->token = $this->userRepository->createToken();
        $user->active = 0;

        $user->save();
        $user->refresh();

        $this->customerRepository->sendActivationEmailForCustomer($customer, $user);
        
        return response()->json(['status' => 'success'], 200);    
    }

    public function passwordResetLink(Request $request)
    {
        $this->validate(request(), [
            'reset_text' => 'string|in:RESET',
            'customer_id' => 'required'
        ]);

        $customer = $this->customerRepository->find($request->get('customer_id'));
        $user = $customer->user;

        $user->password = Hash::make(Str::random(30));
        $user->token = $this->userRepository->createToken();
        $user->active = 1;

        $user->save();
        $user->refresh();

        $token = $this->broker()->createToken($user);

        return redirect()->back()->with([
            'status_reset_link' => 'success', 
            'reset_link' => url()->to(route('password.reset', ['token' => $token, 'email' => $user->email], false))
        ]);
    }

    /**
     * Get the broker to be used during password reset.
     *
     * @return \Illuminate\Contracts\Auth\PasswordBroker
     */
    public function broker()
    {
        return Password::broker();
    }
}