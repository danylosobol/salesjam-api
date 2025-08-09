<?php

namespace App\Services\Zoho;

use App\Services\Zoho\BaseService;


class ContactsService extends BaseService
{
  public function store(array $data): array
  {
    $payload = [
      'data' => [
        [
          'First_Name' => $data['first_name'] ?? null,
          'Last_Name' => $data['last_name'] ?? null,
          'Email' => $data['email'] ?? null,
          'Phone' => $data['phone'] ?? null,
        ]
      ]
    ];
    return $this->request('POST', $this->get_module_url('Contacts'), $payload)['data'][0] ?? [];
  }

  public function show(string $id): array
  {
    return $this->request('GET', $this->get_record_url('Contacts', $id), data: [])['data'][0] ?? [];
  }

  public function update(string $id, array $data): array
  {
    $payload = [
      'data' => [
        [
          'First_Name' => $data['first_name'] ?? null,
          'Last_Name' => $data['last_name'] ?? null,
          'Email' => $data['email'] ?? null,
          'Phone' => $data['phone'] ?? null,
        ]
      ]
    ];

    return $this->request('PUT', $this->get_record_url('Contacts', $id), $payload)['data'][0] ?? [];
  }

  public function destroy(string $id): bool
  {
    $response = $this->request('DELETE', $this->get_record_url('Contacts', $id), []);
    return isset($response['data'][0]['code']) && $response['data'][0]['code'] === 'SUCCESS';
  }

  public function index(array $params = []): array
  {
    $payload = [
      'fields' => 'First_Name,Last_Name,Email',
      ...$params
    ];

    $response = $this->request('GET', $this->get_module_url('Contacts'), $payload);

    return [
      'data' => $response['data'] ?? [],
      'meta' => $response['info'] ?? [],
    ];
  }
}