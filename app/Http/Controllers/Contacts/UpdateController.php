<?php

namespace App\Http\Controllers\Contacts;

use App\Http\Requests\Contacts\UpdateRequest;
use Illuminate\Http\Response;


class UpdateController extends BaseController
{
    public function __invoke(UpdateRequest $request, $id)
    {
        try {
            $contact = $this->service->update($id, $request->validated());
            return response()->json($contact, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getCode() ?: 400);
        }
    }
}
