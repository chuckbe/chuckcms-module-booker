<?php

namespace Chuckbe\ChuckcmsModuleBooker\Chuck;

use Chuckbe\ChuckcmsModuleBooker\Requests\StoreGiftCardRequest;
use Chuckbe\ChuckcmsModuleBooker\Chuck\CustomerRepository;
use Chuckbe\ChuckcmsModuleBooker\Models\GiftCard;
use Chuckbe\ChuckcmsModuleBooker\Models\Customer;
use Illuminate\Http\Request;
use ChuckModuleBooker;
use ChuckSite;
use Mollie;
use Mail;
use PDF;
use Str;

class GiftCardRepository
{
    private $giftCard;

    public function __construct(
        GiftCard $giftCard, 
        CustomerRepository $customerRepository)
    {
        $this->giftCard = $giftCard;
        $this->customerRepository = $customerRepository;
    }

    /**
     * Get all the gift cards
     *
     * @return Illuminate\Database\Eloquent\Collection
     **/
    public function get()
    {
        return $this->giftCard->get();
    }

    /**
     * Get all the gift cards with invoices
     *
     * @return Illuminate\Database\Eloquent\Collection
     **/
    public function getInvoices()
    {
        return $this->giftCard->where('has_invoice', true)->get();
    }

    /**
     * Find the gift cards for the given id(s).
     *
     * @param string|array $id
     * 
     * @return mixed
     **/
    public function find($id)
    {
        if (!is_array($id)) {
            return $this->giftCard->where('id', $id)->first();
        }
        
        return $this->giftCard->whereIn('id', $id)->get();
    }

    /**
     * Make a gift card based on the request
     *
     * @param Illuminate\Http\Request $request
     * @param Customer $customer
     * 
     * @return mixed
     **/
    public function makeFromRequest(Request $request, $customer)
    {
        $giftCard = $this->create($request, $customer);

        return $giftCard;
    }

    /**
     * Create a new gift card.
     *
     * @param Illuminate\Http\Request $request
     * @param Customer $customer
     * 
     * @return Chuckbe\ChuckcmsModuleBooker\Models\GiftCard
     **/
    public function create(Request $request, $customer)
    {
        $json = [];
        
        if (array_key_exists('address', $customer->json)) {
            $json['address'] = $customer->json['address'];
        }

        if (array_key_exists('company', $customer->json)) {
            $json['company'] = $customer->json['company'];
        }
        
        $giftCard = $this->giftCard->create([
            'customer_id' => $customer->id,
            'code' => $this->generateCode($request),
            'weight' => $request->get('weight'),
            'price' => $request->get('price'),
            'is_paid' => $request->get('paid') == 1 ? true : false,
            'json' => $json
        ]);

        $giftCard->refresh();

        if ($giftCard->is_paid) {
            $json = $giftCard->json;
            $json['invoice_number'] = $this->generateInvoiceNumber();
            $giftCard->json = $json;
            $giftCard->has_invoice = true;

            $giftCard->update();
        }

        return $giftCard;
    }

    /**
     * Delete the given gift card.
     *
     * @param GiftCard $giftCard
     * 
     * @return bool
     **/
    public function delete(GiftCard $giftCard)
    {
        return $giftCard->delete();
    }

    private function generateCode(Request $request)
    {
        if ($request->has('code') && !is_null($request->get('code'))) {
            return $request->get('code');
        }

        return strtoupper(Str::random(8));
    }

    private function generatePDF(GiftCard $giftCard)
    {
        $pdf = PDF::loadView('chuckcms-module-booker::pdf.gift_card_invoice', compact('giftCard'));
        return $pdf->output();
    }

    public function downloadInvoice(GiftCard $giftCard)
    {
        $pdf = PDF::loadView('chuckcms-module-booker::pdf.gift_card_invoice', compact('giftCard'));
        return $pdf->download($giftCard->invoiceFileName);
    }

    private function generateInvoiceNumber()
    {
        $invoice_number = ChuckModuleBooker::getSetting('invoice.number') + 1;
        ChuckModuleBooker::setSetting('invoice.number', $invoice_number);
        return $invoice_number;
    }

    private function generateCreditNotePDF(GiftCard $giftCard)
    {
        $pdf = PDF::loadView('chuckcms-module-booker::pdf.gift_card_credit_note', compact('giftCard'));
        return $pdf->output();
    }

    public function downloadCreditNote(GiftCard $giftCard)
    {
        $pdf = PDF::loadView('chuckcms-module-booker::pdf.gift_card_credit_note', compact('giftCard'));
        return $pdf->download($giftCard->creditNoteFileName);
    }

    private function generateCreditNoteNumber()
    {
        $credit_note_number = ChuckModuleBooker::getSetting('credit_note.number') + 1;
        ChuckModuleBooker::setSetting('credit_note.number', $credit_note_number);
        return $credit_note_number;
    }
}