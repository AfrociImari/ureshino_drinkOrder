<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Inertia\Response;
use App\Models\DrinkMenu;
use App\Models\CheckIn;
use Inertia\Inertia;
use Illuminate\Support\Facades\Crypt;

class DrinkMenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       // Retrieve all drink menus with their categories
        $drinks = DrinkMenu::with('category')->get();

        return response()->json($drinks);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        DrinkMenu::create($request->all());

        return redirect(route('drinks', absolute: false));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $menu_id)
    {
         // Try to find the drink menu item with category
   // $drink = DrinkMenu::find($menu_id);
    $drink = DrinkMenu::with('category')->findOrFail($menu_id);

    // Check if it exists
    if (!$drink) {
        return response()->json(['error' => 'Drink menu not found'], 404);
    }

    return response()->json($drink, 200);
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
        DrinkMenu::where('menu_id', $id)->update([
            'product_name' => $request->product_name,//
            'category_name' => $request->category_name,//
            'unitprice' => $request->unitprice,//
            'image' => $request->image,//
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        DrinkMenu::where('menu_id', $id)->delete();
    }

    public function generateEncryptedMobileUrl(Request $request)
{
    $request->validate([
        'checkin_id' => 'required|integer'
    ]);
    $checkin_id = $request->input('checkin_id');

    $encryptedCheckinId = Crypt::encryptString($checkin_id);

    $url = url("/drinkMenu/mobile/{$encryptedCheckinId}");

    return response()->json($url);
}

    //Render the menu page on Mobile Device
    public function gotoMobileMenu($encrypted_checkin_id)
{
    try {
        $checkin_id = Crypt::decryptString($encrypted_checkin_id);
    } catch (\Exception $e) {
        return redirect()->route('qrCodeError'); // Redirect if decryption fails
    }

    $checkin = CheckIn::with('room')->findOrFail($checkin_id);

    if (empty($checkin->qr_code)) {
        return redirect()->route('qrCodeError');
    }

    return Inertia::render('MobileApp/MobileReceipt', [
        'checkin' => $checkin
    ]);
}

    //Select variant and unitprice according to product_name
    public function getVariantsByProductName($product_name)
    {
        // Assuming you have a DrinkMenu model with variants and prices
        $variants = DrinkMenu::where('product_name', $product_name)
                            ->select('variant', 'unitprice')
                            ->get();
        
        return response()->json($variants);
    }
}
