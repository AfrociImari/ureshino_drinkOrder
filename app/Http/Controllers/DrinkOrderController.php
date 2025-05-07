<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Inertia\Response;
use App\Models\DrinkOrder;


class DrinkOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       // Retrieve all order with their checkin
       $orders = DrinkOrder::with('checkin.room')->get();

       return response()->json($orders);
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
       // DrinkOrder::create($request->all());
        try {
            // Log request data for debugging
            \Log::info($request->all());
    
            // Validate request
            $request->validate([
                'checkin_id' => 'required|int',
                'product_name' => 'required|string',
                'quantity' => 'required|string',
                'amount' => 'required|string',
            ]);
    
            // Create a new order
            $order = DrinkOrder::create($request->all());
    
            return response()->json(['message' => 'Order placed successfully', 'order' => $order], 201);
        } catch (\Exception $e) {
            \Log::error('Error storing drink order: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $checkin_id)
    {
        $order = DrinkOrder::where('checkin_id', $checkin_id)->get();

    return response()->json($order);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        DrinkOrder::where('order_id', $id)->update([
            'checkin_id' => $request->checkin_id,
            'product_name' => $request->product_name,
            'quantity' => $request->quantity,
            'amount' => $request->amount,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        DrinkOrder::where('id', $id)->delete();
    }
}
