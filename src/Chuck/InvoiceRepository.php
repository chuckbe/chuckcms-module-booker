<?php

namespace Chuckbe\ChuckcmsModuleBooker\Chuck;

use Chuckbe\ChuckcmsModuleBooker\Models\Appointment;
use Chuckbe\ChuckcmsModuleBooker\Models\Subscription;
use Chuckbe\ChuckcmsModuleBooker\Chuck\AppointmentRepository;
use Chuckbe\ChuckcmsModuleBooker\Chuck\SubscriptionRepository;

class InvoiceRepository
{
    private $service;

    public function __construct(AppointmentRepository $appointmentRepository, SubscriptionRepository $subscriptionRepository)
    {
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
        $appointments = $this->appointmentRepository->getInvoices();
        $subscriptions = $this->subscriptionRepository->getInvoices();

        $invoices = [];
        return $invoices;
    }
}