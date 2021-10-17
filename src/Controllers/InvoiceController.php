<?php

namespace Chuckbe\ChuckcmsModuleBooker\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Chuckbe\ChuckcmsModuleBooker\Models\Invoice;
use Chuckbe\ChuckcmsModuleBooker\Chuck\InvoiceRepository;
use Chuckbe\ChuckcmsModuleBooker\Requests\StoreInvoiceRequest;

class InvoiceController extends Controller
{
    private $invoiceRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(InvoiceRepository $invoiceRepository)
    {
        $this->invoiceRepository = $invoiceRepository;
    }

    /**
     * Return the invoices overview page.
     *
     * @return Illuminate/View/View
     */
    public function index()
    {   
        $invoices = $this->invoiceRepository->get();

        return view('chuckcms-module-booker::backend.invoices.index', compact('invoices'));
    }

    /**
     * Return the invoices create page.
     *
     * @return Illuminate/View/View
     */
    public function create()
    {
        return view('chuckcms-module-booker::backend.invoices.create');
    }

    /**
     * Return the invoices detail page for given invoice.
     *
     * @param Invoice $invoice
     * 
     * @return Illuminate/View/View
     */
    public function detail(Invoice $invoice)
    {
        return view('chuckcms-module-booker::backend.invoices.detail', compact('invoice'));
    }

    /**
     * Return the invoices edit page for given invoice.
     *
     * @param Invoice $invoice
     * 
     * @return Illuminate/View/View
     */
    public function edit(Invoice $invoice)
    {
        return view('chuckcms-module-booker::backend.invoices.edit', compact('invoice'));
    }

    /**
     * Save a new invoice from the request.
     *
     * @param StoreInvoiceRequest $request
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function store(StoreInvoiceRequest $request)
    {
        $this->invoiceRepository->createOrUpdate($request);

        return redirect()->route('dashboard.module.booker.invoices.index');
    }

    /**
     * Delete the given invoice.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function delete(Request $request)
    {
        $this->validate(request(), [
            'invoice_id' => 'required',
        ]);

        $invoice = $this->invoiceRepository->find($request->get('invoice_id'));

        if (!$invoice) {
            return response()->json(['status' => 'error'], 404);
        }

        if ($this->invoiceRepository->delete($invoice)) {
            return response()->json(['status' => 'success'], 200);
        }

        return response()->json(['status' => 'error'], 404);
    }
}