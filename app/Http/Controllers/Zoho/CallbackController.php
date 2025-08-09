<?php

namespace App\Http\Controllers\Zoho;

use Illuminate\Http\Request;


class CallbackController extends BaseController
{
    public function __invoke(Request $request)
    {
        $code = $request->get('code');
        if (!$code) {
            return response()->json(['error' => 'Missing "code" in query parameters.'], 400);
        }

        try {
            $this->service->generate_tokens($code);
            return response()->json(['message' => 'Zoho tokens generated successfully.']);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getCode() ?: 400);
        }
    }
}
