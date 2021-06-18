<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreShipmentRequest;
use App\Http\Resources\ConsignmentResource;
use App\Http\Resources\ShipmentResource;
use App\Models\Address;
use App\Models\Shipment;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class ShipmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreShipmentRequest $request, Client $client)
    {
        $data = $request->validated();
        $shipment = new Shipment($data);
        $shipment->recipientAddress = new Address($data['recipient_address']);

        $consignmentResource = new ConsignmentResource($shipment);
        $response = $client->request('POST', 'http://foreign-server.com/consignment', ['query' => $consignmentResource->toArray($request)] );
        $contents = json_decode($response->getBody()->getContents());

        $shipment->tracking_code = $contents->tracking_code;
        $shipment->deliver_at = $contents->deliver_at;
        return response()->json(new ShipmentResource($shipment));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Shipment  $shipment
     * @return \Illuminate\Http\Response
     */
    public function show(Shipment $shipment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Shipment  $shipment
     * @return \Illuminate\Http\Response
     */
    public function edit(Shipment $shipment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Shipment  $shipment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Shipment $shipment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Shipment  $shipment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Shipment $shipment)
    {
        //
    }
}
