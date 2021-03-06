<?php

namespace Tests\Unit;

use App\Http\Requests\StoreShipmentRequest;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class ShipmentValidateRequestTest extends TestCase
{
    protected array $jsonRequest;
    protected array $rules;

    protected function setUp(): void
    {
        parent::setUp();
        $this->jsonRequest = [
            "recipient_address" => [
                "street_name" => "STREET_NAME",
                "street_number" => 19,
                "country_code" => "NL",
                "first_name" => "Firstname",
                "last_name" => "Lastname",
                "phone" => "+1231231231"
            ],
            "number_of_items" => 4,
            "service_code" => "express"
        ];

        $storeShipmentRequest = new StoreShipmentRequest();
        $this->rules = $storeShipmentRequest->rules();
    }

    /**
     * Validates the jsonRequest as the jsonRequest is expected/specified and asserts that validation passes.
     *
     * @return void
     */
    public function test_acceptsCorrectJsonrequest()
    {
        $validator = Validator::make($this->jsonRequest, $this->rules);
        $this->assertTrue($validator->passes());
    }

    /**
     * Tests whether the validation fails if any of the entries on the request and the recipient_address on it.
     * Loops over keys in jsonRequest and its recipient address and removes one array entry and checks if validation
     * indeed failed.
     *
     * @return void
     */
    public function test_testThatAllFieldsOnJsonrequestAreRequired()
    {
        $requestCollection = collect($this->jsonRequest);

        $keys = $requestCollection->keys();
        $keys->each(function($key) use ($requestCollection) {
            $pulled = $requestCollection->pull($key);
            $validator = Validator::make($requestCollection->toArray(), $this->rules);
            $requestCollection[$key] = $pulled;

            $this->assertFalse($validator->passes());
        });


        $addressCollection = collect($requestCollection['recipient_address']);
        $addressKeys = $requestCollection->collapse()->keys();
        $addressKeys->each(function($key) use ($requestCollection, $addressCollection) {
            $pulled = $addressCollection->pull($key);
            $requestCollection['recipient_address'] = $addressCollection->toArray();
            $validator = Validator::make($requestCollection->toArray(), $this->rules);
            $addressCollection[$key] = $pulled;

            $this->assertFalse($validator->passes());
        });
    }

    /**
     * assigns a valid country is accepted and asserts that the validation passes.
     *
     * @return void
     */
    public function test_AcceptsValidCountryCodes()
    {
        $validCountryCode = 'NL';

        $this->jsonRequest['recipient_address']['country_code'] = $validCountryCode;
        $validator = Validator::make($this->jsonRequest, $this->rules);

        $this->assertTrue($validator->passes());
    }

    /**
     * assigns a invalid country is accepted and asserts that the validation does not pass
     *
     * @return void
     */
    public function test_RejectsInvalidCountryCodes()
    {
        $invalidCountryCode = 'AA';

        $this->jsonRequest['recipient_address']['country_code'] = $invalidCountryCode;
        $validator = Validator::make($this->jsonRequest, $this->rules);

        $this->assertFalse($validator->passes());
    }

    /**
     * Assigns a positive number of items to the json request and asserts the validation passes
     *
     * @return void
     */
    public function test_acceptsPositiveNumberOfItems()
    {
        $this->jsonRequest['number_of_items'] = 1;
        $validator = Validator::make($this->jsonRequest, $this->rules);

        $this->assertTrue($validator->passes());
    }

    /**
     * Assigns a negative number of items to the json request and asserts the validation does not pass
     *
     * @return void
     */
    public function test_rejectsNegativeNumberOfItems()
    {
        $this->jsonRequest['number_of_items'] = -1;
        $validator = Validator::make($this->jsonRequest, $this->rules);

        $this->assertFalse($validator->passes());
    }

    /**
     * Assigns a zero number of items to the json request and asserts the validation does not pass
     *
     * @return void
     */
    public function test_rejectsZeroNumberOfItems()
    {
        $this->jsonRequest['number_of_items'] = 0;
        $validator = Validator::make($this->jsonRequest, $this->rules);

        $this->assertFalse($validator->passes());
    }

    /**
     * Assigns express to service to the json request and asserts the validation passes
     *
     * @return void
     */
    public function test_acceptsExpressAsService()
    {
        $this->jsonRequest['service_code'] = 'express';
        $validator = Validator::make($this->jsonRequest, $this->rules);
        $this->assertTrue($validator->passes());
    }

    /**
     * Assigns economy to service to the json request and asserts the validation passes
     *
     * @return void
     */
    public function test_acceptsEconomyAsService()
    {
        $this->jsonRequest['service_code'] = 'economy';
        $validator = Validator::make($this->jsonRequest, $this->rules);
        $this->assertTrue($validator->passes());
    }


    /**
     * Assigns a random string  to service to the json request and asserts the validation does not pass
     *
     * @return void
     */
    public function test_rejectAnotherStringAsService()
    {
        $this->jsonRequest['service_code'] = 'some random string';
        $validator = Validator::make($this->jsonRequest, $this->rules);
        $this->assertFalse($validator->passes());
    }

}
