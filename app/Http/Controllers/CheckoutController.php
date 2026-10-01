<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\AsaasService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function __construct(protected AsaasService $asaas)
    {
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'cpf_cnpj' => ['required', 'string', 'max:20'],
        ]);

        $product = Product::query()->findOrFail($validated['product_id']);

        $customer = $this->asaas->findOrCreateCustomer([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'cpf_cnpj' => preg_replace('/\D/', '', $validated['cpf_cnpj']),
        ]);

        $payment = $this->asaas->createPayment(
            customerId: $customer['id'],
            value: (float) $product->price,
            description: $product->name,
        );

        return redirect()->away($payment['invoiceUrl']);
    }
}