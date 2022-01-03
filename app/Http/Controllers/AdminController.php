<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Payment;
use App\Models\Subcategory;
use App\Models\Transaction;
use Illuminate\Http\Request;

class AdminController extends Controller
{

    // Create transactions categories & subcategories by define ['parent_id']
    public function createCategory()
    {
        $validated = request()->validate([
            'name'      => 'required|string|min:3|max:191'
        ]);

        $category = Category::create($validated);

        return response()->json(compact('category'));
    }
    public function createSubcategory()
    {
        $validated = request()->validate([
            'category_id' => 'nullable|integer|exists:categories,id',
            'name'      => 'required|string|min:3|max:191'
        ]);

        $subcategory = Subcategory::create($validated);

        return response()->json(compact('subcategory'));
    }

    // get categories and it's subcategories to use this data in select category dropdown menu
    public function getCategories()
    {
        $categories = Category::with('subcategories')->get();

        return response()->json(compact('categories'));
    }

    public function createTransaction()
    {
        $validated = request()->validate([
            'category_id'           => 'required|integer|exists:categories,id',
            'subcategory_id'        => 'required|integer|exists:subcategories,id',
            'payer_id'              => 'required|integer|exists:users,id',
            'amount'                => 'required|integer',
            'due_on'                => 'required',
            'vat'                   => 'required|integer',
            'is_vat_inclusive'      => 'required|in:0,1',
        ]);

        $transaction = Transaction::create($validated);

        return response()->json(compact('transaction'));
    }

    public function createPayment()
    {
        $validated = request()->validate([
            'transaction_id'    => 'required|integer|exists:transactions,id',
            'amount'            => 'required|integer',
            'paid_on'           => 'required',
            'details'           => 'nullable|string|max:191',
        ]);

        $payment = Payment::create($validated);

        return response()->json(compact('payment'));
    }


}
