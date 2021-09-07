<?php

namespace Chuckbe\ChuckcmsModuleBooker\Commands;

use Chuckbe\Chuckcms\Chuck\ModuleRepository;
use Illuminate\Console\Command;

class InstallModuleBooker extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'chuckcms-module-booker:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command installs the ChuckCMS Booker Module .';

    /**
     * The module repository implementation.
     *
     * @var ModuleRepository
     */
    protected $moduleRepository;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(ModuleRepository $moduleRepository)
    {
        parent::__construct();

        $this->moduleRepository = $moduleRepository;
    }
    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $name = 'ChuckCMS Booker Module';
        $slug = 'chuckcms-module-booker';
        $hintpath = 'chuckcms-module-booker';
        $path = 'chuckbe/chuckcms-module-booker';
        $type = 'module';
        $version = '0.0.1';
        $author = 'Karel Brijs (karel@chuck.be)';

        $json = [];
        $json['admin']['show_in_menu'] = true;


        $json['admin']['menu'] = array(
            'name' => 'Booker',
            'icon' => "calender",
            'route' => '#',
            'has_submenu' => true,
            'submenu' => array(
                'a' => array(
                    'name' => 'Afspraken',
                    'icon' => true,
                    'icon_data' => 'calendar',
                    'route' => 'dashboard.module.booker.appointments',
                    'has_submenu' => false,
                    'submenu' => null
                ),
                'b' => array(
                    'name' => 'Locaties',
                    'icon' => true,
                    'icon_data' => 'map-marked-alt',
                    'route' => 'dashboard.module.booker.locations',
                    'has_submenu' => false,
                    'submenu' => null
                ),
                'c' => array(
                    'name' => 'Diensten',
                    'icon' => true,
                    'icon_data' => 'map-marked-alt',
                    'route' => 'dashboard.module.booker.services.index',
                    'has_submenu' => false,
                    'submenu' => null
                ),
            )
        );

        // create the module
        $module = $this->moduleRepository->createFromArray([
            'name' => $name,
            'slug' => $slug,
            'hintpath' => $hintpath,
            'path' => $path,
            'type' => $type,
            'version' => $version,
            'author' => $author,
            'json' => $json
        ]);


        $this->info('.         .');
        $this->info('..         ..');
        $this->info('...         ...');
        $this->info('.... AWESOME ....');
        $this->info('...         ...');
        $this->info('..         ..');
        $this->info('.         .');
        $this->info('.         .');
        $this->info('..         ..');
        $this->info('...         ...');
        $this->info('....   JOB   ....');
        $this->info('...         ...');
        $this->info('..         ..');
        $this->info('.         .');
        $this->info(' ');
        $this->info('Module installed: ChuckCMS Booker Module');
        $this->info(' ');
    }


}
