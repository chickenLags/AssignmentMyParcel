<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ConsignmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return (
            [
                "send_to" => new ConsignmentAddressResource($this->recipientAddress),
                "quantity" => $this->number_of_items,
                "service" => $this->service_code
            ]
        );
    }
}
