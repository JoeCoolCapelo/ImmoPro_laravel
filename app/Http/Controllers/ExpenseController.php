<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Bien;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = Expense::with('bien.owner')->latest()->paginate(10);
        $biens = Bien::all();
        return view('expenses.index', compact('expenses', 'biens'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'bien_id' => 'required|exists:biens,id',
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'date_expense' => 'required|date',
            'description' => 'nullable|string',
        ]);

        Expense::create($request->all());

        return redirect()->back()->with('success', 'Dépense enregistrée avec succès.');
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();
        return redirect()->back()->with('success', 'Dépense supprimée.');
    }
}
