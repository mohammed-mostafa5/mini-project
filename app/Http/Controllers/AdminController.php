<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function createCategory()
    {
        $validated = request()->validate([
            'parent_id' => 'nullable|integer|exists:categories,id',
            'name'      => 'required|string|min:3|max:191'
        ]);

        $category = Category::create($validated);

        return response()->json(compact('category'));
    }
}
