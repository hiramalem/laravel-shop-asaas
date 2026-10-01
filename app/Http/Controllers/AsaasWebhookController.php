<?php

namespace App\Http\Controllers;

use App\Models\AsaasWebhookLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AsaasWebhookController extends Controller
{
    public function handle(Request $request): JsonResponse
    {
        Log::info('Webhook ASAAS recebido', $request->all());
        
        // query() retirado
        
        AsaasWebhookLog::create([
            'payload' => $request->all(),
        ]);

        return response()->json(['received' => true]);
    }
}