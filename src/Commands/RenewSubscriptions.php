<?php

namespace Chuckbe\ChuckcmsModuleBooker\Commands;

use ChuckSite;
use Chuckbe\ChuckcmsModuleBooker\Chuck\SubscriptionRepository;
use Illuminate\Console\Command;

class RenewSubscriptions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'chuckcms-module-booker:renew-subscriptions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command renews the subscriptions active in the ChuckCMS Booker Module.';

    /**
     * The module repository implementation.
     *
     * @var ModuleRepository
     */
    protected $subscriptionRepository;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(SubscriptionRepository $subscriptionRepository)
    {
        parent::__construct();

        $this->subscriptionRepository = $subscriptionRepository;
    }
    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $subscriptions = $this->subscriptionRepository->getActiveSubscriptionsToRenew();

        foreach ($subscriptions as $subscription) {
            //deactivate current one
            $subscription->is_active = false;
            $subscription->will_renew = false;
            $subscription->update();

            //make a new one with correct information and new expiration date (this will also create direct debit charge)
            $this->subscriptionRepository->makeFromPrevious($subscription);
        }
    }
    
}
