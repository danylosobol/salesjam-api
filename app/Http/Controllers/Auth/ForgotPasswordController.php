<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Auth\BaseController;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use Illuminate\Http\Response;

class ForgotPasswordController extends BaseController
{
  public function __invoke(ForgotPasswordRequest $request)
  {
    try {
      $result = $this->service->send_reset_link($request->validated());
      return response()->json(['status' => $result], Response::HTTP_OK);
    } catch (\Exception $e) {
      return response()->json([
        'message' => $e->getMessage()
      ], $e->getCode() ?: 400);
    }
  }
}