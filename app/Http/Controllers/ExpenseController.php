<?php

namespace App\Http\Controllers;


use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ExpenseController extends Controller
{
    public function addExpense(Request $request)
    {
        try {
            $newExpense = new Expense();
            $newExpense->purpose = $request->purpose;
            $newExpense->amount = $request->amount;
            $newExpense->save();

            return response()->json(['message' => 'Expense added successfully'], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            \Log::error('Error adding expense: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to add expense'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function UpdateExpense(Request $request, $id)
    {
        try{
            $updateExpense = Expense::find($id);
            $updateExpense->purpose = $request->purpose;
            $updateExpense->amount = $request->amount;
            $updateExpense->save();
            return response()->json(['message' => 'Expense Update successfully'], Response::HTTP_CREATED);
        }catch (\Exception $e) {

            \Log::error('Error adding expense: ' . $e->getMessage());

            return response()->json(['error' => 'Failed to add expense'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function getexpense()
    {
        $expense = Expense::all();
        return $expense;
    }
    public function singleexpense($id)
    {
        $singleExpense = Expense::find($id);
        return $singleExpense;
    }

    public function deleteExpense($id)
    {
        $expense = Expense::find($id);
        if (!$expense) {
            return "No data found for the given ID";
        }
        $expense->delete();
        return "Delete success";
    }

}
