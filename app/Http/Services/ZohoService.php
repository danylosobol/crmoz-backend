<?php

namespace App\Http\Services;

use App\Enums\Setting\Key;
use App\Models\Setting;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;

class ZohoService
{
  protected ?string $api_domain = "";
  protected ?string $access_token = '';
  protected ?string $account_url = '';

  public function __construct()
  {
    $this->api_domain = Setting::get(Key::API_DOMAIN->value);
    $this->access_token = Setting::get(Key::ACCESS_TOKEN->value);
    $this->account_url = Setting::get(Key::ACCOUNT_URL->value);
  }
  protected function get_access_token()
  {
    if (!$this->access_token) {
      $this->access_token = $this->refresh_access_token();
    }

    return $this->access_token;
  }

  protected function get_module_url($module)
  {
    return "{$this->api_domain}/crm/v8/{$module}/upsert";
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
      $response = Http::withHeaders([
            'Authorization' => 'Zoho-oauthtoken ' . $this->get_access_token(),
          ])->{$method}($url, $data);
    }

    if (!$response->successful()) {
      abort(Response::HTTP_BAD_REQUEST, 'Zoho API error.');
    }

    return $response->json();
  }

  protected function refresh_access_token()
  {
    $res = Http::asForm()->post("{$this->account_url}/oauth/v2/token", [
      'refresh_token' => Setting::get(Key::REFRESH_TOKEN->value),
      'client_id' => Setting::get(Key::CLIENT_ID->value),
      'client_secret' => Setting::get(Key::CLIENT_SECRET->value),
      'grant_type' => 'refresh_token',
    ]);

    if (!$res->successful()) {
      abort(Response::HTTP_BAD_REQUEST, 'Failed to refresh Zoho token.');
    }

    $new_token = $res->json()['access_token'];
    Setting::updateOrCreate(['key' => 'access_token'], ['value' => $new_token]);
    return $new_token;
  }

  public function create_account($data)
  {
    $url_base = $this->get_module_url('Accounts');
    $payload = [
      'data' => [
        [
          'Account_Name' => $data['account_name'],
          'Phone' => $data['account_phone'],
          'Website' => $data['account_website']
        ]
      ]
    ];
    try {
      return $this->request('post', $url_base, $payload)['data'][0] ?? [];
    } catch (\Exception $e) {
      abort(Response::HTTP_BAD_REQUEST, $e->getMessage());
    }
  }

  public function create_deal($data)
  {
    $url_base = $this->get_module_url('Deals');

    $payload = [
      'data' => [
        [
          'Deal_Name' => $data['deal_name'],
          'Stage' => $data['deal_stage'],
        ]
      ]
    ];
    try {
      return $this->request(
        'post',
        $url_base,
        $payload
      )['data'][0] ?? [];
    } catch (\Exception $e) {
      abort(Response::HTTP_BAD_REQUEST, $e->getMessage());
    }
  }

  public function generate_tokens($code)
  {
    $res = Http::asForm()->post("{$this->account_url}/oauth/v2/token", [
      'grant_type' => 'authorization_code',
      'code' => $code,
      'client_id' => Setting::get(Key::CLIENT_ID->value),
      'client_secret' => Setting::get(Key::CLIENT_SECRET->value),
      'redirect_uri' => Setting::get(Key::REDIRECT_URI->value),
    ]);


    if (!$res->successful()) {
      abort(Response::HTTP_BAD_REQUEST, 'Tokens weren\'t created.');
    }

    $data = $res->json();

    Setting::updateOrCreate(['key' => 'access_token'], ['value' => $data['access_token']]);
    Setting::updateOrCreate(['key' => 'refresh_token'], ['value' => $data['refresh_token']]);
    return !!$data;
  }
}