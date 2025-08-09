<?php

namespace App\Http\Controllers\Zoho;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthorizeController extends BaseController
{
    public function __invoke(Request $request)
    {
        return $this->service->redirect_to_zoho();
    }
}
