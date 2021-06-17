<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ShipmentToConsignmentTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_routeReachable()
    {
        $jsonRequest = [
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

        $response = $this->json('POST', '/shipments', $jsonRequest);

        $response->assertStatus(200);

        $jsonResponse = $jsonRequest;

        $jsonResponse['tracking_code'] = "forwarded value from the remote api";
        $jsonResponse['deliver_at'] = "2021-01-30";
        $response->assertJson($jsonResponse);




    }

//    public function test_
}
