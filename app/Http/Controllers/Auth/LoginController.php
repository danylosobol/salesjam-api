<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\Response;

class LoginController extends BaseController
{
  public function __invoke(LoginRequest $request)
  {
    try {
      $user = $this->service->login($request->validated());
      return response()->json(['user' => new UserResource($user), 'token' => $user->createToken('api-token', ['*'], now()->addMinutes($request->boolean('remember_me', false) ? 43200 : 1440))->plainTextToken], Response::HTTP_OK);
    } catch (\Exception $e) {
      return response()->json([
        'message' => $e->getMessage()
      ], $e->getCode() ?: 400);
    }
  }
}