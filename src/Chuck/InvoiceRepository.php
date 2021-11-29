<?php

namespace Chuckbe\ChuckcmsModuleBooker\Chuck;

use Chuckbe\ChuckcmsModuleBooker\Models\Appointment;
use Chuckbe\ChuckcmsModuleBooker\Models\Subscription;
use Chuckbe\ChuckcmsModuleBooker\Chuck\GiftCardRepository;
use Chuckbe\ChuckcmsModuleBooker\Chuck\AppointmentRepository;
use Chuckbe\ChuckcmsModuleBooker\Chuck\SubscriptionRepository;

class InvoiceRepository
{
    private $service;

    public function __construct(
        GiftCardRepository $giftCardRepository, 
        AppointmentRepository $appointmentRepository, 
        SubscriptionRepository $subscriptionRepository)
    {
        $this->giftCardRepository = $giftCardRepository;
        $this->appointmentRepository = $appointmentRepository;
        $this->subscriptionRepository = $subscriptionRepository;
    }

    /**
     * Get all the invoices
     *
     * @return Illuminate\Database\Eloquent\Collection
     **/
    public function get()
    {
        $giftCards = $this->giftCardRepository->getInvoices();
        $appointments = $this->appointmentRepository->getInvoices();
        $subscriptions = $this->subscriptionRepository->getInvoices();

        $invoices = collect();

        foreach ($appointments as $appointment) {
            $invoices->push((object)['id' => $appointment->id, 'name' => 'Factuur #'.$appointment->json['invoice_number'], 'number' => $appointment->json['invoice_number'], 'type' => 'appointment', 'object' => $appointment]);
        }

        foreach ($subscriptions as $subscription) {
            $invoices->push((object)['id' => $subscription->id, 'name' => 'Factuur #'.$subscription->json['invoice_number'], 'number' => $subscription->json['invoice_number'], 'type' => 'subscription', 'object' => $subscription]);
        }

        foreach ($giftCards as $giftCard) {
            $invoices->push((object)['id' => $giftCard->id, 'name' => 'Factuur #'.$giftCard->json['invoice_number'], 'number' => $giftCard->json['invoice_number'], 'type' => 'gift_card', 'object' => $giftCard]);
        }

        return $invoices->sortByDesc('number');
    }
}