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
        $response = $this->post('/shipments');
        $response->assertStatus(200);
    }

//    public function test_
}
