<?php

namespace Chuckbe\ChuckcmsModuleBooker\Chuck\Accessors;

use Chuckbe\ChuckcmsModuleBooker\Chuck\BookerFormRepository;
use Exception;
use Illuminate\Support\Facades\Schema;

use App\Http\Requests;

class ChuckModuleBooker
{
    private $bookerFormRepository;

    public function __construct(BookerFormRepository $bookerFormRepository) 
    {
        $this->bookerFormRepository = $bookerFormRepository;
    }

    public function renderStyles()
    {
        return $this->bookerFormRepository->styles();
    }


    public function renderForm()
    {
        return $this->bookerFormRepository->render();
    }

    public function renderScripts()
    {
        return $this->bookerFormRepository->scripts();
    }


}