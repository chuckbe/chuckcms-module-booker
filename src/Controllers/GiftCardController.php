<?php

namespace Chuckbe\ChuckcmsModuleBooker\Controllers;

use PDF;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Chuckbe\ChuckcmsModuleBooker\Models\GiftCard;
use Chuckbe\ChuckcmsModuleBooker\Chuck\CustomerRepository;
use Chuckbe\ChuckcmsModuleBooker\Chuck\GiftCardRepository;
use Chuckbe\ChuckcmsModuleBooker\Requests\StoreGiftCardRequest;

class GiftCardController extends Controller
{
    private $giftCardRepository;
    private $customerRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        GiftCardRepository $giftCardRepository, 
        CustomerRepository $customerRepository)
    {
        $this->giftCardRepository = $giftCardRepository;
        $this->customerRepository = $customerRepository;
    }

    /**
     * Return the subscriptions overview page.
     *
     * @return Illuminate\View\View
     */
    public function index()
    {   
        $customers = $this->customerRepository->get();
        $giftCards = $this->giftCardRepository->get();

        return view('chuckcms-module-booker::backend.gift_cards.index', compact('customers', 'giftCards'));
    }

    /**
     * Save a new gift card from the request.
     *
     * @param StoreSubscriptionRequest $request
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function store(StoreGiftCardRequest $request)
    {
        $customer = $this->customerRepository->find($request->customer);

        if ($customer == false || $customer == null) {
            return response()->json(['status' => 'error'], 200);
        }

        $giftCard = $this->giftCardRepository->makeFromRequest($request, $customer);

        if ($giftCard == false || $giftCard == null) {
            return response()->json(['status' => 'error'], 200);
        }

        return redirect()->route('dashboard.module.booker.gift_cards.index');
    }

    /**
     * Return the gift card invoice.
     *
     * @param GiftCard $giftCard
     * 
     * @return Illuminate\View\View
     */
    public function invoice(GiftCard $giftCard)
    {
        return $this->giftCardRepository->downloadInvoice($giftCard);
    }

    /**
     * Return the gift card invoice.
     *
     * @param GiftCard $giftCard
     * 
     * @return Illuminate\View\View
     */
    public function creditNote(GiftCard $giftCard)
    {
        return $this->giftCardRepository->downloadCreditNote($giftCard);
    }
}