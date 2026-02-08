<?php

namespace App\Modules\Order\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Modules\Order\Http\Resources\CourierSlaResource;
use App\Modules\Order\Models\Shipment;
use App\Modules\Order\Services\ShipmentService;
use Illuminate\Http\Request;

class ShipmentController extends Controller
{
    public function __construct(
        private ShipmentService $shipmentService) {
    }
    /**
     * Display a listing of the resource.
     */
    public function indexCourierSla()
    {
        $courierSlas = $this->shipmentService->getCourier();
        return ApiResponse::successResponse(CourierSlaResource::collection($courierSlas),"Berhasil mengambil courier Service Level Agreement");
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Shipment $shipment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Shipment $shipment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Shipment $shipment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Shipment $shipment)
    {
        //
    }
}
