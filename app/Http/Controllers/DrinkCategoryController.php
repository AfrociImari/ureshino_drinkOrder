<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Inertia\Response;
use App\Models\DrinkCategory;

class DrinkCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return DrinkCategory::all();
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
        DrinkCategory::create($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(string $category_name)
    {
        $category = DrinkCategory::where('product_name', $category_name)->get();

        if ($category->isEmpty()) {
            return response()->json(['message' => 'No category found'], 404);
        }

    return response()->json($category);
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
        DrinkCategory::where('category_id', $id)->update([
            'category_name' => $request->category_name,//
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        DrinkCategory::where('category_id', $id)->delete();
    }
}
