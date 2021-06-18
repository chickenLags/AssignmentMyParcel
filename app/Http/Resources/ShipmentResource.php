<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ShipmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "recipient_address" => new ShipmentAddressResource($this->recipientAddress),
            "number_of_items" => $this->number_of_items,
            "service_code" => $this->service_code,
            'tracking_code' => $this->tracking_code,
            'deliver_at' => $this->deliver_at,
        ];
    }
}
