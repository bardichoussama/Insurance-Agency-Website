<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;


class DashboardController extends Controller
{
    public function index(): Response
    {
        // Check if the user is authenticated

        // Fetch categories
        $categories = Category::select('id', 'name', 'image')->get();

        return Inertia::render('Dashboard', [
            'categories' => $categories,
        ]);


        // return redirect()->route('login'); // Redirect if not logged in
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpg,jpeg,png,gif,svg|max:2048',
        ]);

        $data = $request->only('name');

        if ($request->hasFile('image')) {
            // Get the original filename
            $imageName = $request->file('image')->getClientOriginalName();

            // Save image to 'resources/assets/imgs/' directory
            $request->file('image')->move(resource_path(path: 'assets/imgs'), $imageName);

            // Store the filename in the database
            $data['image'] = $imageName;
        }

        Category::create($data);

        return redirect()->route('dashboard');
    }
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|max:1024',
        ]);

        $data = $request->only('name', 'image');

        if ($request->hasFile('image')) {
            // Delete the old image if it exists
            if ($category->image && file_exists(public_path($category->image))) {
                unlink(public_path($category->image));
            }

            $imagePath = $request->file('image')->storeAs('imgs', $request->file('image')->getClientOriginalName(), 'assets');
            $data['image'] = '/assets/imgs/' . $request->file('image')->getClientOriginalName();
        }

        $category->update($data);

        return redirect()->route('dashboard');
    }
    public function destroy($id)
    {
        // Find the category or fail if it doesn't exist
        $category = Category::findOrFail($id);
    
        // Delete the associated image file if it exists
        if ($category->image && file_exists(public_path("resources/assets/imgs/{$category->image}"))) {
            unlink(public_path("resources/assets/imgs/{$category->image}"));
        }
    
        // Delete the category record from the database
        $category->delete();
    
        // Redirect to the dashboard with a success message
        return redirect()->route('dashboard')->with('success', 'Category deleted successfully.');
    }
    
}
