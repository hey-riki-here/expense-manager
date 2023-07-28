<?php

namespace App\Http\Controllers;

use App\Models\ExpenseCategory;
use Illuminate\Http\Request;

class ExpenseCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $expenseCategories = ExpenseCategory::all(['id','display_name', 'description', 'created_at']);
        return response()->json($expenseCategories);
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
        $expenseCategory = ExpenseCategory::create($request->post());
        return response()->json([
            'message' => 'Expense category created successfully!',
            'expenseCategory' => $expenseCategory,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(ExpenseCategory $expenseCategory)
    {
        return response()->json($expenseCategory);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ExpenseCategory $expenseCategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ExpenseCategory $expenseCategory)
    {
        $expenseCategory->fill($request->post())->save();
        return response()->json([
            'message' => 'Expense category updated successfully!',
            'expenseCategory' => $expenseCategory,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ExpenseCategory $expenseCategory)
    {
        $expenseCategory->delete();
        return response()->json([
            'message' => 'Expense category deleted successfully!',
        ]);
    }
}
