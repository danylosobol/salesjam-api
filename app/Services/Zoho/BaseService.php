<?php

namespace App\Services\Zoho;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class BaseService
{
  protected ?string $api_domain = '';
  protected ?string $access_token = null;
  protected ?string $account_url = '';

  public function __construct()
  {
    $this->api_domain = config('services.zoho.api_domain');
    $this->account_url = config('services.zoho.account_url');
    $this->access_token = null;
  }

  protected function get_access_token()
  {
    $this->access_token = Cache::get('zoho_access_token');

    if (!$this->access_token) {
      $this->access_token = $this->refresh_access_token();
    }

    return $this->access_token;
  }

  protected function get_refresh_token(): ?string
  {
    return Cache::get('zoho_refresh_token');
  }

  protected function get_module_url($module)
  {
    return "{$this->api_domain}/crm/v8/{$module}";
  }

  protected function get_record_url($module, $id)
  {
    return "{$this->api_domain}/crm/v8/{$module}/{$id}";
  }

  protected function request($method, $url, $data = [])
  {
    if (!$this->access_token) {
      $this->refresh_access_token();
    }
    $response = Http::withHeaders([
          'Authorization' => 'Zoho-oauthtoken ' . $this->get_access_token(),
        ])->{$method}($url, $data);

    if ($response->status() === 401) {
      $this->access_token = $this->refresh_access_token();

      $response = Http::withHeaders([
            'Authorization' => 'Zoho-oauthtoken ' . $this->access_token,
          ])->{$method}($url, $data);
    }

    if (!$response->successful()) {
      throw new \Exception('Zoho API error.', Response::HTTP_BAD_REQUEST);
    }

    return $response->json();
  }

  protected function refresh_access_token()
  {
    $refresh_token = $this->get_refresh_token();
    if (!$refresh_token) {
      throw new \Exception('Refresh token not available. Run generate_tokens() first.', Response::HTTP_BAD_REQUEST);
    }

    $response = Http::asForm()->post("{$this->account_url}/oauth/v2/token", [
      'refresh_token' => $refresh_token,
      'client_id' => config('services.zoho.client_id'),
      'client_secret' => config('services.zoho.client_secret'),
      'grant_type' => 'refresh_token',
    ]);

    \Log::info($response->json());

    if (!$response->successful()) {
      throw new \Exception($response->json()['error_description'] ?: 'Failed to refresh Zoho token.', Response::HTTP_BAD_REQUEST);
    }

    $new_token = $response->json()['access_token'];

    Cache::put('zoho_access_token', $new_token, now()->addMinutes(60));

    return $new_token;
  }

  public function redirect_to_zoho()
  {
    $clientId = config('services.zoho.client_id');
    $scope = 'ZohoCRM.modules.ALL';
    $responseType = 'code';
    $accessType = 'offline';

    $url = "{$this->account_url}/oauth/v2/auth?" . http_build_query([
      'scope' => $scope,
      'client_id' => $clientId,
      'response_type' => $responseType,
      'access_type' => $accessType,
      'redirect_uri' => config('services.zoho.redirect_uri'),
      'prompt' => 'consent',
    ]);

    return redirect($url);
  }

  public function generate_tokens($code)
  {
    $res = Http::asForm()->post("{$this->account_url}/oauth/v2/token", [
      'grant_type' => 'authorization_code',
      'code' => $code,
      'client_id' => config('services.zoho.client_id'),
      'client_secret' => config('services.zoho.client_secret'),
      'redirect_uri' => config('services.zoho.redirect_uri'),
    ]);

    if (!$res->successful()) {
      throw new \Exception('Tokens weren\'t created.', Response::HTTP_BAD_REQUEST);
    }

    $data = $res->json();

    Cache::put('zoho_access_token', $data['access_token'], now()->addMinutes(60));
    Cache::put('zoho_refresh_token', $data['refresh_token'], now()->addDays(365));

    return true;
  }
}

