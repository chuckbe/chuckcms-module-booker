<?php

namespace Chuckbe\ChuckcmsModuleBooker\Commands;

use Chuckbe\ChuckcmsModuleBooker\Chuck\SubscriptionRepository;
use Illuminate\Console\Command;

class ExpireSubscriptions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'chuckcms-module-booker:expire-subscriptions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command expires the subscriptions that needs to be expired.';

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
        $this->subscriptionRepository->expireSubscriptions();
    }
    
}
