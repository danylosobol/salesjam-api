<?php

namespace App\Http\Controllers\Auth;

use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class MeController extends BaseController
{
  public function __invoke(Request $request)
  {
    try {
      return response()->json(new UserResource($request->user()), Response::HTTP_OK);
    } catch (\Exception $e) {
      return response()->json([
        'message' => $e->getMessage()
      ], $e->getCode() ?: 400);
    }
  }
}