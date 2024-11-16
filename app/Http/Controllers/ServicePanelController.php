<?php

namespace App\Http\Controllers;


use App\Models\Category;
use App\Models\Service;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;


class ServicePanelController extends Controller
{
    public function index(): Response
    {
        // Fetch all services with their category relationship
        $services = Service::with('category')->get();
        
        // Fetch all categories
        $categories = Category::all();
    
        return Inertia::render('ServicesPanel', [
            'services' => $services,
            'categories' => $categories, // Pass categories to the view
        ]);
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
    
        // Add a key for the image if it's uploaded
        if ($request->hasFile('image')) {
            // Get the original filename
            $imageName = $request->file('image')->getClientOriginalName();
    
            // Move the file to the 'resources/assets/imgs' directory
            $request->file('image')->move(resource_path('assets/imgs'), $imageName);
    
            // Add the image name to the validated data
            $validated['image'] = $imageName;
        }
    
        // Create the service record
        Service::create($validated);
    
        return redirect()->route('services.panel')->with('success', 'Service added successfully.');
    }
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255', // Correct field name
            'description' => 'required|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
    
        $service = Service::findOrFail($id);
    
        if ($request->hasFile('image')) {
            $oldImagePath = public_path('assets/imgs/' . $service->image);
            if (file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }
            $imageName = $request->file('image')->getClientOriginalName();
            $request->file('image')->move(public_path('assets/imgs'), $imageName);
            $validated['image'] = $imageName;
        }
        
    
        $service->update($validated);
    
        return redirect()->route('dashboard')->with('success', 'Service updated successfully.');
    }
    

    
}
