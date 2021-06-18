<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ConsignmentAddressResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return ([
            "address" => $this->getAddress(),
            "country" => $this->country_code,
            "recipient" => $this->getFullName(),
            "phone" => $this->phone
        ]);
    }
}
