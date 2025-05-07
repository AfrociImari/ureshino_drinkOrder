<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Inertia\Response;
use App\Models\CourseMenu;

class CourseMenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return CourseMenu::all();
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
        try {
            // Log request data for debugging
            \Log::info($request->all());
    
            // Validate request
            $request->validate([
                'course_type' => 'required|string',
                'course_name' => 'required|string'
            ]);
    
            // Create a new course
            $course = CourseMenu::create($request->all());
    
            return response()->json(['message' => 'Course created successfully', 'course' => $course], 201);
        } catch (\Exception $e) {
            \Log::error('Error storing drink order: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
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
        //
        CourseMenu::where('id', $id)->update([
            'course_type' => $request->course_type,
            'course_name' => $request->course_name
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        CourseMenu::where('id', $id)->delete();
    }
}
