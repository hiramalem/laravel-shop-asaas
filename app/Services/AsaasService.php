<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class AsaasService
{
    protected string $baseUrl;
    protected string $apiKey;

    public function __construct()
    {
        $this->baseUrl = config('services.asaas.url');
        $this->apiKey = config('services.asaas.key');
    }

    protected function client()
    {
        return Http::baseUrl($this->baseUrl)
            ->withHeaders([
                'access_token' => $this->apiKey,
                'Content-Type' => 'application/json',
                'User-Agent' => config('app.name', 'Laravel'),
            ]);
    }

    /**
     * Busca um cliente pelo CPF/CNPJ; se não existir, cria um novo.
     */
    public function findOrCreateCustomer(array $data): array
    {
        $existing = $this->client()->get('/customers', [
            'cpfCnpj' => $data['cpf_cnpj'],
        ]);

        if ($existing->successful() && count($existing->json('data', [])) > 0) {
            return $existing->json('data')[0];
        }

        $response = $this->client()->post('/customers', [
            'name' => $data['name'],
            'email' => $data['email'],
            'cpfCnpj' => $data['cpf_cnpj'],
        ]);

        if ($response->failed()) {
            Log::error('Falha ao criar cliente no ASAAS', ['response' => $response->json()]);
            throw new RuntimeException('Não foi possível criar o cliente no ASAAS.');
        }

        return $response->json();
    }

    /**
     * Cria uma cobrança com forma de pagamento indefinida
     * (o cliente escolhe Pix, boleto ou cartão na página do ASAAS).
     */
    public function createPayment(string $customerId, float $value, string $description): array
    {
        $response = $this->client()->post('/payments', [
            'customer' => $customerId,
            'billingType' => 'UNDEFINED',
            'value' => $value,
            'dueDate' => now()->addDays(3)->format('Y-m-d'),
            'description' => $description,
        ]);

        if ($response->failed()) {
            Log::error('Falha ao criar cobrança no ASAAS', ['response' => $response->json()]);
            throw new RuntimeException('Não foi possível criar a cobrança no ASAAS.');
        }

        return $response->json();
    }
}