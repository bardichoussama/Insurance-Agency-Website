<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Route;


class CategoryController extends Controller
{
    public function index(): Response
    {
        $categories = Category::select('id', 'name', 'image')->get();

        return Inertia::render('Welcome', [
            'categories' => $categories,
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
         ]);
    }
    
}

