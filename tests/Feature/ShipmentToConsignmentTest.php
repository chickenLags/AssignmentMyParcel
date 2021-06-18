<?php

namespace Tests\Feature;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use Tests\TestCase;

class ShipmentToConsignmentTest extends TestCase
{
    protected array $jsonRequest;
    protected array $expectedJson;
    protected array $container = [];

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

        $this->expectedJson = $this->jsonRequest;
        $this->expectedJson['tracking_code'] = "forwarded value from the remote api";
        $this->expectedJson['deliver_at'] = "2021-01-30";

        $this->bindGuzzleMock();
    }

    protected function bindGuzzleMock(): void
    {
        app()->bind(Client::class, function () {
            $mock = new MockHandler([
                new Response(200, [], json_encode([
                    'tracking_code' => 'forwarded value from the remote api',
                    'deliver_at' => '2021-01-30'
                ]))
            ]);
            $handler = HandlerStack::create($mock);

            $history = Middleware::history($this->container);
            $handler->push($history);

            return new Client(['handler' => $handler]);
        });
    }

    /**
     * Feature test of Shipping to Consignment.
     * asserts a response 200 response and that the expected json is returned.
     *
     * @return void
     */
    public function test_shipmentsFeatureTest()
    {
        $this->json('POST', '/api/shipments', $this->jsonRequest)
            ->assertStatus(200)
            ->assertJson($this->expectedJson);
    }

    /**
     * Tests that shipping endpoint uses the customValidator.
     * asserts a response 422 response when no json/body is sent.
     *
     * @return void
     */
    public function test_shipmentsFeatureUsesStoreshipmentrequestTest()
    {
        $this->json('POST', '/api/shipments')
            ->assertStatus(422);
    }

    /**
     * Tests that Guzzle Client has indeed made one call.
     * In the ShipmentController@store there is exactly one call made with Guzzle. This test asserts that.
     *
     * @return void
     */
    public function test_ReachedOutToRemoteServerUnitTest() {
        $this->json('POST', '/api/shipments', $this->jsonRequest);
        $this->assertTrue(count($this->container) === 1, 'Guzzle client/ foreign server was not called in the test.');
    }

    /**
     * Tests that Guzzle reached out to the correct endpoint.
     * Asserts that the route foreign-server.com/consignment was called.
     *
     * @return void
     */
    public function test_CorrectRemoteCarrierHostAndPathUnitTest(){
        $this->json('POST', '/api/shipments', $this->jsonRequest);
        $guzzleUri = $this->container[0]['request']->getUri();

        $this->assertTrue($guzzleUri->getPath() === '/consignment', 'Guzzle client is set to the wrong path.');
        $this->assertTrue($guzzleUri->getHost() === 'foreign-server.com', 'Guzzle client is set to the wrong host.');
    }
}
