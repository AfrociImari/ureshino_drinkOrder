<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Inertia\Response;
use Inertia\Inertia;
use App\Models\CheckIn;

class CheckInController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Retrieve all drink menus with their categories
        $checkins = CheckIn::with('room')->get();

        return response()->json($checkins);
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
        CheckIn::create($request->all());

        return redirect(route('checkinList', absolute: false));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
        CheckIn::where('checkin_id', $id)->update([
            'room_id' => $request->room_id,//
            'date' => $request->date,//
            'timing' => $request->timing,//
            'type' => $request->type,//
            'qr_code' => $request->qr_code,//
        ]);
    }

    /**
     * Update the qrCode value to corresponding checkinId
     */
    public function updateQrCode(Request $request, string $id)
    {
        $checkin = CheckIn::where('checkin_id', $id)->first();

        if (!$checkin) {
            return response()->json(['message' => 'Check-in not found'], 404);
        }
        
        $checkin->qr_code = $request->qrCode;
        $checkin->save();
    }

    
    /**
     * Update order_stop for asking last order
     */
    public function orderStop(Request $request, string $id)
    {
        $checkin = CheckIn::where('checkin_id', $id)->first();

        if (!$checkin) {
            return response()->json(['message' => 'Check-in not found'], 404);
        }
        
        $checkin->order_stop = $request->orderStop;
        $checkin->save();

        return response()->json([
            'message' => 'Order stopped successfully',
            'checkin' => $checkin,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
