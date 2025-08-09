<?php

namespace App\Http\Controllers\Zoho;

use App\Services\Zoho\BaseService;

class BaseController extends BaseService
{
    public $service;
    public function __construct(BaseService $service)
    {
        $this->service = $service;
    }
}
