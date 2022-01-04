<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Payment;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Resources\PaymentResource;
use App\Http\Resources\TransactionResource;

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
            'due_on'                => 'required|date',
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
            'paid_on'           => 'required|date',
            'payment_method'    => 'required|in:1,2',
            'details'           => 'nullable|string|max:191',
        ]);

        $payment = Payment::create($validated);

        $transaction = Transaction::find(request('transaction_id'));

        if ($transaction->due_on >= now() && $transaction->payments->sum('amount') < $transaction->amount) {
            $transaction->update(['status' => Transaction::STATUS_OUTSTANDING]);
        }

        if ($transaction->due_on >= now() && $transaction->payments->sum('amount') == $transaction->amount) {
            $transaction->update(['status' => Transaction::STATUS_PAID]);
        }


        return response()->json(compact('payment'));
    }

    public function viewTransaction(Transaction $transaction)
    {
        return new TransactionResource($transaction->load('category', 'subcategory', 'payer'));
    }

    public function viewPayment(Payment $payment)
    {
        return new PaymentResource($payment);
    }

    public function generateReport()
    {
        request()->validate([
            'starting_date' => 'required|date',
            'ending_date'   => 'required|date'
        ]);

        // Calculate Period
        $from = Carbon::parse(request('starting_date'));
        $to = Carbon::parse(request('ending_date'));

        $diff = $to->diffInMonths($from);
        $period = $diff >= 12 ? (round($diff / 12)) . ' Years' : $diff . ' Months';



        // Calculate Paid Amount
        $paidTransactions = Transaction::where('status', Transaction::STATUS_PAID)
                                    ->whereBetween('due_on', [request()->input('starting_date'), request()->input('ending_date')])
                                    ->get();

        $paidAmount = 0;
        foreach ($paidTransactions as $transaction) {
            $paidAmount += $transaction->payments->sum('amount');
        }

        // Calculate Outstanding Amount
        $outstandingTransactions = Transaction::where('status', Transaction::STATUS_OUTSTANDING)
                                    ->whereBetween('due_on', [request()->input('starting_date'), request()->input('ending_date')])
                                    ->get();

        $outstandingAmount = 0;
        foreach ($outstandingTransactions as $transaction) {
            $outstandingAmount += $transaction->payments->sum('amount');
        }

        // Calculate overdue Amount
        $overdueTransactions = Transaction::where('status', Transaction::STATUS_OVERDUE)
                                    ->whereBetween('due_on', [request()->input('starting_date'), request()->input('ending_date')])
                                    ->get();

        $overdueAmount = 0;
        foreach ($overdueTransactions as $transaction) {
            $overdueAmount += $transaction->payments->sum('amount');
        }

        return response()->json([
            'period' => $period,
            'paid_amount' => $paidAmount,
            'outstanding_amount' => $outstandingAmount,
            'overdue_amount' => $overdueAmount
        ]);
    }
}
