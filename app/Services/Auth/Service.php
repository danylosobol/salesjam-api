<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class Service
{
  public function register($data)
  {
    return User::create([
      'name' => $data['name'],
      'email' => $data['email'],
      'password' => Hash::make($data['password']),
    ]);
  }

  public function login($data)
  {
    $user = User::query()->where('email', $data['email'])->first();
    if (empty($user) || !Hash::check($data['password'], $user->password)) {
      throw new \Exception('Invalid credentials', Response::HTTP_UNAUTHORIZED);
    }

    return $user;
  }

  public function send_reset_link($data)
  {
    $user = User::where('email', $data['email'])->first();
    if (!$user) {
      throw new \Exception('This email is not registered.', Response::HTTP_NOT_FOUND);
    }

    $token = Str::random(64);


    DB::table('password_reset_tokens')->updateOrInsert(
      ['email' => $user->email],
      [
        'token' => bcrypt($token),
        'created_at' => now(),
      ]
    );

    $url = $data['endpoint'] . '?secret=' . $token;

    try {
      Mail::raw(
        "We received a password reset request.\nClick the link below to reset your password:\n\n$url",
        function ($message) use ($user) {
          $message->to($user->email)
            ->subject('Reset Your Password');
        }
      );
      return true;

    } catch (\Exception $e) {
      throw new \Exception('Failed to send email.', Response::HTTP_INTERNAL_SERVER_ERROR);
    }
  }

  public function reset_password($data)
  {
    $row = DB::table('password_reset_tokens')->whereRaw('created_at >= ?', [now()->subHour()])
      ->get()
      ->filter(function ($item) use ($data) {
        return Hash::check($data['token'], $item->token);
      })
      ->first();

    if (!$row) {
      throw new \Exception('Invalid or expired secret token.', Response::HTTP_GONE);
    }

    $user = User::where('email', $row->email)->first();

    if (!$user) {
      throw new \Exception('User not found.', Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    $user->password = Hash::make($data['password']);
    $user->save();

    DB::table('password_reset_tokens')->where('email', $user->email)->delete();
    return true;
  }
}