<?php

namespace Chuckbe\ChuckcmsModuleBooker\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Chuckbe\ChuckcmsModuleBooker\Models\Customer;
use Chuckbe\ChuckcmsModuleBooker\Chuck\CustomerRepository;
use Chuckbe\ChuckcmsModuleBooker\Requests\StoreCustomerRequest;

class CustomerController extends Controller
{
    private $customerRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(CustomerRepository $customerRepository)
    {
        $this->customerRepository = $customerRepository;
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
        return view('chuckcms-module-booker::backend.customers.detail', compact('location'));
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
     * Save a new location from the request.
     *
     * @param StoreCustomerRequest $request
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function store(StoreCustomerRequest $request)
    {
        $this->customerRepository->createOrUpdate($request);

        return redirect()->route('dashboard.module.booker.customers.index');
    }

    /**
     * Delete the given location.
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

        if ($this->customerRepository->delete($customer)) {
            return response()->json(['status' => 'success'], 200);
        }

        return response()->json(['status' => 'error'], 404);
    }
}