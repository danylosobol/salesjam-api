<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\Auth\ResetPasswordRequest;
use Illuminate\Http\Response;

class ResetPasswordController extends BaseController
{
  public function __invoke(ResetPasswordRequest $request)
  {
    try {
      $result = $this->service->reset_password($request->validated());
      return response()->json(['status' => $result], Response::HTTP_OK);
    } catch (\Exception $e) {
      return response()->json([
        'message' => $e->getMessage()
      ], $e->getCode() ?: 400);
    }
  }
}
