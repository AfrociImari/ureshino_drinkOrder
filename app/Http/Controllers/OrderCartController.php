<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Inertia\Response;
use App\Models\OrderCart;


class OrderCartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return OrderCart::all();
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
        OrderCart::create($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(string $checkin_id)
    {
        $orderCart = OrderCart::where('checkin_id', $checkin_id)->get();

        // if ($orderCart->isEmpty()) {
        //     return response()->json(['message' => 'No orderCart found'], 404);
        // }

    return response()->json($orderCart);
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
        $order = OrderCart::findOrFail($id);

        $order->quantity = $request->input('quantity');
        $order->total_amount = $request->input('total_amount');
        $order->save();

        return response()->json(['message' => 'Order cart updated successfully.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        OrderCart::where('id', $id)->delete();
    }
}
