<?php

namespace Chuckbe\ChuckcmsModuleBooker\Chuck;

use Chuckbe\ChuckcmsModuleBooker\Requests\StoreSubscriptionRequest;
use Chuckbe\ChuckcmsModuleBooker\Chuck\SubscriptionPlanRepository;
use Chuckbe\ChuckcmsModuleBooker\Chuck\CustomerRepository;
use Chuckbe\ChuckcmsModuleBooker\Models\Subscription;
use Chuckbe\ChuckcmsModuleBooker\Models\Customer;
use Chuckbe\ChuckcmsModuleBooker\Models\Payment;
use Illuminate\Http\Request;
use ChuckModuleBooker;
use ChuckSite;
use Mollie;
use Mail;
use PDF;

class SubscriptionRepository
{
    private $subscription;

    public function __construct(
        Payment $payment, 
        Subscription $subscription, 
        CustomerRepository $customerRepository, 
        SubscriptionPlanRepository $subscriptionPlanRepository)
    {
        $this->payment = $payment;
        $this->subscription = $subscription;
        $this->customerRepository = $customerRepository;
        $this->subscriptionPlanRepository = $subscriptionPlanRepository;
    }

    /**
     * Get all the subscriptions
     *
     * @return Illuminate\Database\Eloquent\Collection
     **/
    public function get()
    {
        return $this->subscription->get();
    }

    /**
     * Find the subscription for the given id(s).
     *
     * @param string|array $id
     * 
     * @return mixed
     **/
    public function find($id)
    {
        if (!is_array($id)) {
            return $this->subscription->where('id', $id)->first();
        }
        
        return $this->subscription->whereIn('id', $id)->get();
    }

    /**
     * Make a subscription based on the request
     *
     * @param Illuminate\Http\Request $request
     * @param $customer
     * 
     * @return mixed
     **/
    public function makeFromRequest(Request $request, $customer)
    {
        $subscription = $this->create($request, $customer);

        if ($subscription->price > 0) {
            $payment = $this->makePayment($subscription, $customer);
        } 

        return $subscription;
    }

    /**
     * Make a new payment for the subscription
     *
     * @param Chuckbe\ChuckcmsModuleBooker\Models\Subscription $subscription
     * 
     * @return mixed
     **/
    public function makePayment(Subscription $subscription, Customer $customer)
    {
        config(['mollie.key' => ChuckSite::module('chuckcms-module-booker')->getSetting('integrations.mollie.key')]);

        if (!$customer->hasMollieId()) {
            $customer = $this->customerRepository->createMollieId($customer);
        }

        $mollie = Mollie::api()->payments()->create([
            'amount' => [
                'currency' => 'EUR',
                'value' => number_format( ( (float)$subscription->price ), 2, '.', ''), 
            ],
            'customerId'    => $customer->json['mollie_id'],
            'sequenceType'  => 'first',
            'method'        => 'bancontact',
            'description'   => ChuckSite::getSite('name') . ' Abo #' . $subscription->id,
            'redirectUrl'   => route('module.booker.checkout.subscription.followup', ['subscription' => $subscription->id]),
            'webhookUrl'    => 'https://chuckcms.com/', //route('module.ecommerce.mollie_webhook'),
            'metadata'      => array(
                    'price'                 => number_format( ( (float)$subscription->price ), 2, '.', ''),
                    'subscription_id'       => $subscription->id,
                    'sequence'              => 'first'
                )
        ]);

        $payment = $this->payment->create(['subscription_id' => $subscription->id, 'external_id' => $mollie->id, 'type' => 'first', 'status' => 'awaiting', 'amount' => $subscription->price, 'log' => array(), 'json' => array()]);

        $mollie = Mollie::api()->payments()->get($mollie->id);

        return $mollie;
    }









    /**
     * Create a new subscription.
     *
     * @param Illuminate\Http\Request $request
     * @param Customer $customer
     * 
     * @return Chuckbe\ChuckcmsModuleBooker\Models\Subscription
     **/
    public function create(Request $request, $customer)
    {
        $plan = $this->subscriptionPlanRepository->find($request->subscription_plan);

        $subscription = $this->subscription->create([
            'subscription_plan_id' => $plan->id,
            'customer_id' => $customer->id,
            'type' => $plan->type,
            'weight' => $plan->weight,
            'price' => $plan->price,
            'expires_at' => $this->getExpiresAt($plan),
            'will_renew' => $plan->type !== 'one-off',
            'json' => array()
        ]);

        return $subscription;
    }

    private function getExpiresAt($plan)
    {
        if ($plan->type == 'one-off') {
            return now()->addMonths($plan->months_valid)->addDays($plan->days_valid);
        } 

        if ($plan->type == 'weekly') {
            return now()->addDays(7);
        } 

        if ($plan->type == 'monthly') {
            return now()->addMonths(1);
        } 

        if ($plan->type == 'quarterly') {
            return now()->addMonths(3);
        } 

        if ($plan->type == 'yearly') {
            return now()->addYears(1);
        } 
    }

    /**
     * Create a new subscription.
     *
     * @param Illuminate\Http\Request $request
     * 
     * @return Chuckbe\ChuckcmsModuleBooker\Models\subscription
     **/
    public function update(Request $request)
    {
        $subscription = $this->subscription->where('id', $request->get('id'))->first();

        $subscription = $subscription->update([
            'name' => $request->get('name'),
            'duration' => (int)$request->get('duration'),
            'price' => $request->get('price'),
            'deposit' => $request->get('deposit'),
            'order' => (int)$request->get('order'),
            'weight' => (int)$request->get('weight'),
            'json' => array()
        ]);

        return $subscription;
    }

    /**
     * Delete the given subscription.
     *
     * @param Subscription $subscription
     * 
     * @return bool
     **/
    public function delete(Subscription $subscription)
    {
        return $subscription->delete();
    }

    // public function updateStatus(Subscription $subscription, $status)
    // {
    //     $json = is_null($subscription->json) ? [] : $subscription->json; 
    //     if ($status == 'payment' || $status == 'paid') {
    //         $subscription->is_active = true;

    //         $json['invoice_number'] = $this->generateInvoiceNumber();
    //         $subscription->has_invoice = true;
            
    //         $subscription->json = $json;

    //         $subscription->save();

    //         $pdf = null;
    //         if ($subscription->has_invoice) {
    //             $pdf = $this->generatePDF($subscription);
    //         }
            
    //         $this->sendMail($subscription, $status);
    //     }

    //     if ($status == 'failed') {
    //         //if customer has an active mandate create a new payment and try again
            


    //         $subscription->is_active = false;
    //         $subscription->save();
    //     }

    //     if ($status == 'canceled') {
    //         //send email to try again?
    //         $subscription->is_active = false;
    //         $subscription->save();
    //     }

    //     if ($status == 'expired') {
    //         //send email to try again?
    //         $subscription->is_active = false;
    //         $subscription->save();
    //     }
    // }


    public function updateStatus(Subscription $subscription, $status)
    {
        if ($status == 'paid') {
            $status = 'payment';
        }

        $status_object = ChuckModuleBooker::getSetting('subscription.statuses.'.$status);
        //$subscription->status = $status;
        $json = is_null($subscription->json) ? [] : $subscription->json; 

        if ($status == 'payment') {
            $subscription->is_active = true;
        }

        if ($status == 'failed' || $status == 'canceled' || $status == 'expired') {
            $subscription->is_active = false;
        }
        
        if($status_object['invoice'] && !array_key_exists('invoice_number', $json)) {
            $json['invoice_number'] = $this->generateInvoiceNumber();
            $subscription->json = $json;
            $subscription->has_invoice == true;
        }

        $subscription->update();

        if($status_object['send_email']) {
            if($status_object['invoice'] && $subscription->has_invoice) {
                $pdf = $this->generatePDF($subscription);
            } else {
                $pdf = null;
            }

            foreach($status_object['email'] as $emailKey => $email) {
                $this->sendMail($subscription, $status_object, $emailKey, $email, $pdf);
                sleep(1);
            }
        }
    }


    private function sendMail(Subscription $subscription, array $status, string $emailKey, array $email, $pdf = null)
    {
        $invoice = $subscription->has_invoice;
        $template = $email['template'];
        $to = $this->replaceEmailVariables($subscription, is_null($email['to']) ? '' : $email['to']);
        $to_name = $this->replaceEmailVariables($subscription, is_null($email['to_name']) ? '' : $email['to_name']);
        $cc = $this->replaceEmailVariables($subscription, is_null($email['cc']) ? '' : $email['cc']);
        $bcc = $this->replaceEmailVariables($subscription, is_null($email['bcc']) ? '' : $email['bcc']);

        $data = $this->prepareEmailData($subscription, $email);

        Mail::send($template, ['subscription' => $subscription, 'email' => $email, 'data' => $data], function ($m) use ($subscription, $status, $email, $to, $to_name, $cc, $bcc, $data, $invoice, $pdf) {
            $m->from(config('chuckcms-module-booker.emails.from_email'), config('chuckcms-module-booker.emails.from_name'));
            
            $m->to($to, $to_name)->subject($data['subject']);

            if( $cc !== false && $cc !== null && $cc !== ''){
                $m->cc($cc);
            }

            if( $bcc !== false && $bcc !== null && $bcc !== ''){
                $m->bcc($bcc);
            }

            if ($invoice) {
                $m->attachData($pdf, $subscription->invoiceFileName, ['mime' => 'application/pdf']);
            }
        });    
    }

    private function prepareEmailData(Subscription $subscription, array $email)
    {
        $data = [];
        $locale = app()->getLocale();

        foreach($email['data'] as $emailDataKey => $emailData) {
            $data[$emailDataKey] = $this->replaceEmailVariables($subscription, $emailData['value'][$locale]);
        }

        return $data;
    }

    private function replaceEmailVariables(Subscription $subscription, string $value)
    {
        $foundVariables = $this->getRawVariables($value, '[%', '%]');
        if (count($foundVariables) > 0) {
            foreach ($foundVariables as $foundVariable) {
                // if (strpos($foundVariable, 'APPOINTMENT_NUMBER') !== false) {
                //     $value = str_replace('[%APPOINTMENT_NUMBER%]', $order->json['APPOINTMENT_number'], $value);
                // }
                // if (strpos($foundVariable, 'APPOINTMENT_SUBTOTAL') !== false) {
                //     $value = str_replace('[%APPOINTMENT_SUBTOTAL%]', ChuckEcommerce::formatPrice($order->subtotal), $value);
                // }
                // if (strpos($foundVariable, 'APPOINTMENT_SUBTOTAL_TAX') !== false) {
                //     $value = str_replace('[%APPOINTMENT_SUBTOTAL_TAX%]', ChuckEcommerce::formatPrice($order->subtotal_tax), $value);
                // }
                // if (strpos($foundVariable, 'APPOINTMENT_SHIPPING') !== false) {
                //     $value = str_replace('[%APPOINTMENT_SHIPPING%]', ChuckEcommerce::formatPrice($order->shipping), $value);
                // }
                // if (strpos($foundVariable, 'APPOINTMENT_SHIPPING_TAX') !== false) {
                //     $value = str_replace('[%APPOINTMENT_SHIPPING_TAX%]', ChuckEcommerce::formatPrice($order->shipping_tax), $value);
                // }
                // if (strpos($foundVariable, 'APPOINTMENT_SHIPPING_TOTAL') !== false) {
                //     $value = str_replace('[%APPOINTMENT_SHIPPING_TOTAL%]', $order->shipping > 0 ? ChuckEcommerce::formatPrice($order->shipping + $order->shipping_tax) : 'gratis', $value);
                // }
                // if (strpos($foundVariable, 'APPOINTMENT_TOTAL') !== false) {
                //     $value = str_replace('[%APPOINTMENT_TOTAL%]', ChuckEcommerce::formatPrice($order->total), $value);
                // }
                // if (strpos($foundVariable, 'APPOINTMENT_TOTAL_TAX') !== false) {
                //     $value = str_replace('[%APPOINTMENT_TOTAL_TAX%]', ChuckEcommerce::formatPrice($order->total_tax), $value);
                // }
                // if (strpos($foundVariable, 'APPOINTMENT_FINAL') !== false) {
                //     $value = str_replace('[%APPOINTMENT_FINAL%]', ChuckEcommerce::formatPrice($order->final), $value);
                // }
                // if (strpos($foundVariable, 'APPOINTMENT_PAYMENT_METHOD') !== false) {
                //     $value = str_replace('[%APPOINTMENT_PAYMENT_METHOD%]', $order->json['payment_method'], $value);
                // }
                


                if (strpos($foundVariable, 'SUBSCRIPTION_FIRST_NAME') !== false) {
                    $value = str_replace('[%SUBSCRIPTION_FIRST_NAME%]', $subscription->customer->first_name, $value);
                }
                if (strpos($foundVariable, 'SUBSCRIPTION_LAST_NAME') !== false) {
                    $value = str_replace('[%SUBSCRIPTION_LAST_NAME%]', $subscription->customer->last_name, $value);
                }
                if (strpos($foundVariable, 'APPOINTMENT_EMAIL') !== false) {
                    $value = str_replace('[%APPOINTMENT_EMAIL%]', $subscription->customer->email, $value);
                }
                if (strpos($foundVariable, 'SUBSCRIPTION_EMAIL') !== false) {
                    $value = str_replace('[%SUBSCRIPTION_EMAIL%]', $subscription->customer->email, $value);
                }
                if (strpos($foundVariable, 'SUBSCRIPTION_TELEPHONE') !== false) {
                    $value = str_replace('[%SUBSCRIPTION_TELEPHONE%]', !is_null($subscription->customer->tel) ? $subscription->customer->tel : '', $value);
                }



                // if (strpos($foundVariable, 'APPOINTMENT_COMPANY') !== false) {
                //     $value = str_replace('[%APPOINTMENT_COMPANY%]', !is_null($order->json['company']['name']) ? $order->json['company']['name'] : '', $value);
                // }
                // if (strpos($foundVariable, 'APPOINTMENT_COMPANY_VAT') !== false) {
                //     $value = str_replace('[%APPOINTMENT_COMPANY_VAT%]', !is_null($order->json['company']['name']) ? $order->json['company']['vat'] : '', $value);
                // }
                // if (strpos($foundVariable, 'APPOINTMENT_BILLING_STREET') !== false) {
                //     $value = str_replace('[%APPOINTMENT_BILLING_STREET%]', $order->json['address']['billing']['street'], $value);
                // }
                // if (strpos($foundVariable, 'APPOINTMENT_BILLING_HOUSENUMBER') !== false) {
                //     $value = str_replace('[%APPOINTMENT_BILLING_HOUSENUMBER%]', $order->json['address']['billing']['housenumber'], $value);
                // }
                // if (strpos($foundVariable, 'APPOINTMENT_BILLING_POSTALCODE') !== false) {
                //     $value = str_replace('[%APPOINTMENT_BILLING_POSTALCODE%]', $order->json['address']['billing']['postalcode'], $value);
                // }
                // if (strpos($foundVariable, 'APPOINTMENT_BILLING_CITY') !== false) {
                //     $value = str_replace('[%APPOINTMENT_BILLING_CITY%]', $order->json['address']['billing']['city'], $value);
                // }
                // if (strpos($foundVariable, 'APPOINTMENT_BILLING_COUNTRY') !== false) {
                //     $value = str_replace('[%APPOINTMENT_BILLING_COUNTRY%]', config('chuckcms-module-ecommerce.countries_data.'.$order->json['address']['billing']['country'].'.native'), $value);
                // }



                if (strpos($foundVariable, 'SUBSCRIPTION_SUBSCRIPTION_PLAN') !== false) {
                    $value = str_replace('[%SUBSCRIPTION_SUBSCRIPTION_PLAN%]', $this->formatSubscription($subscription), $value);
                }
            }
        }
        return $value;
    }

    private function formatSubscription(Subscription $subscription) {
        $string = '';
        
        if ($subscription->subscription_plan->type == 'one-off') {
            $string .= '<p>1x "'.$subscription->subscription_plan->name.'" - Eenmalig ('.ChuckModuleBooker::formatPrice($subscription->price).')</p><hr>';
        } elseif ($subscription->subscription_plan->type == 'weekly') {
            $string .= '<p>1x "'.$subscription->subscription_plan->name.'" - Wekelijks ('.ChuckModuleBooker::formatPrice($subscription->price).')</p><hr>';
        } elseif ($subscription->subscription_plan->type == 'monthly') {
            $string .= '<p>1x "'.$subscription->subscription_plan->name.'" - Maandelijks ('.ChuckModuleBooker::formatPrice($subscription->price).')</p><hr>';
        } elseif ($subscription->subscription_plan->type == 'quarterly') {
            $string .= '<p>1x "'.$subscription->subscription_plan->name.'" - Driemaandelijks ('.ChuckModuleBooker::formatPrice($subscription->price).')</p><hr>';
        } elseif ($subscription->subscription_plan->type == 'yearly') {
            $string .= '<p>1x "'.$subscription->subscription_plan->name.'" - Jaarlijks ('.ChuckModuleBooker::formatPrice($subscription->price).')</p><hr>';
        }
        

        return $string;
    }

    private function getRawVariables($str, $startDelimiter, $endDelimiter) 
    {
        $contents = array();
        $startDelimiterLength = strlen($startDelimiter);
        $endDelimiterLength = strlen($endDelimiter);
        $startFrom = 0;
        while (false !== ($contentStart = strpos($str, $startDelimiter, $startFrom))) {
            $contentStart += $startDelimiterLength;
            $contentEnd = strpos($str, $endDelimiter, $contentStart);
            if (false === $contentEnd) {
                break;
            }
            $contents[] = substr($str, $contentStart, $contentEnd - $contentStart);
            $startFrom = $contentEnd + $endDelimiterLength;
        }

        return $contents;
    }

    private function generatePDF(Subscription $subscription)
    {
        $pdf = PDF::loadView('chuckcms-module-booker::pdf.subscription_invoice', compact('subscription'));
        return $pdf->output();
    }

    public function downloadInvoice(Subscription $subscription)
    {
        $pdf = PDF::loadView('chuckcms-module-booker::pdf.subscription_invoice', compact('subscription'));
        return $pdf->download($subscription->invoiceFileName);
    }

    private function generateInvoiceNumber()
    {
        $invoice_number = ChuckModuleBooker::getSetting('invoice.number') + 1;
        ChuckModuleBooker::setSetting('invoice.number', $invoice_number);
        return $invoice_number;
    }
}