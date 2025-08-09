<?php

namespace App\Http\Controllers\Contacts;

use App\Http\Controllers\Controller;
use App\Services\Zoho\ContactsService;

class BaseController extends Controller
{
    public $service;
    public function __construct(ContactsService $service)
    {
        $this->service = $service;
    }
}
