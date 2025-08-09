<?php

namespace App\Http\Controllers\Contacts;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ShowController extends BaseController
{
    public function __invoke(Request $request, $id)
    {
        try {
            $contact = $this->service->show($id);
            return response()->json($contact, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getCode() ?: 400);
        }
    }
}
