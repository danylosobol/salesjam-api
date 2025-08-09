<?php

namespace App\Http\Controllers\Contacts;

use App\Http\Requests\Contacts\IndexRequest;
use Illuminate\Http\Response;


class IndexController extends BaseController
{

    public function __invoke(IndexRequest $request)
    {
        try {
            $contacts = $this->service->index($request->validated());
            return response()->json($contacts, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getCode() ?: 400);
        }
    }
}
