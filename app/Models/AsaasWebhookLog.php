<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AsaasWebhookLog extends Model
{
    protected $fillable = [
        'payload',
    ];

    protected $casts = [
        'payload' => 'array',
    ];
}
