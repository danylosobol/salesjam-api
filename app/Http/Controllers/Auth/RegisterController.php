<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Response;

class RegisterController extends BaseController
{
  public function __invoke(RegisterRequest $request, User $user)
  {
    try {
      $user = $this->service->register($request->validated());
      return response()->json(new UserResource($user), Response::HTTP_CREATED);
    } catch (\Exception $e) {
      return response()->json([
        'message' => $e->getMessage()
      ], $e->getCode() ?: 400);
    }
  }
}