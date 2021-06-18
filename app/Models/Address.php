<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        "street_name",
        "street_number",
        "country_code",
        "first_name",
        "last_name",
        "phone",
    ];

    public function getAddress() {
        return "$this->street_name $this->street_number";
    }

    public function getFullName() {
        return "$this->first_name $this->last_name";
    }
}
