<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use LVR\CountryCode\Two;

class StoreShipmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "recipient_address" => ['required'],
            "recipient_address.street_name" => ['required'],
            "recipient_address.street_number" => ['required', 'integer', 'min:1'],
            "recipient_address.country_code" => ['required', new Two],
            "recipient_address.first_name" => ['required'],
            "recipient_address.last_name" => ['required'],
            "recipient_address.phone" => ['required'],
            "number_of_items" => ['required', 'integer', 'min:1'],
            "service_code" => ['required', Rule::in(['express', 'economy'])],
        ];
    }
}
