<?php

namespace App\Http\Controllers\Contacts;

use App\Http\Requests\Contacts\StoreRequest;
use Illuminate\Http\Response;


class StoreController extends BaseController
{
    public function __invoke(StoreRequest $request)
    {
        try {
            $contact = $this->service->store($request->validated());
            return response()->json($contact, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getCode() ?: 400);
        }
    }
}
