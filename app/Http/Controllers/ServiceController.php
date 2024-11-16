<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Service;
use Inertia\Inertia;

class ServiceController extends Controller
{
    public function index($id)
    {
        $services = Service::where('category_id', $id)->get(['id', 'title','image']);
        return Inertia::render('Category', [
            
            'services' => $services
            
        ]);
       
    }
    public function show($id)
{
    $service = Service::findOrFail($id);

    return Inertia::render('ServiceDetails', [
        'service' => $service,
    ]);
}
}
