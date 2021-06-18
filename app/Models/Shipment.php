<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'number_of_items',
        'service_code',
        'recipient_address',
        'tracking_code',
        'deliver_at'
    ];

    public function recipientAddress() {
        return $this->hasOne(Address::class);
    }
}
