<?php

namespace App\Http\Controllers;

use App\Models\Category;

// class CategoryController extends Controller
// {
//     public function index()
//     {
//         $categories = Category::all();
//         return view('categories.index', compact('categories'));
//     }
// }

class CategoryController extends Controller {
    public function index() {
        $categories = Category::all();
        return view('welcome', compact('categories'));
    }

    public function show(Category $category) {
        $products = $category->products;
        return view('categories.products', compact('category','products'));
    }
}

