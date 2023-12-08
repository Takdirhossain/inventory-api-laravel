<?php

namespace App\Http\Controllers;


use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ExpenseController extends Controller
{
    public function addExpense(Request $request) {
        try {
            $newExpense = new Expense();
            $newExpense->purpose = $request->purpose;
            $newExpense->amount = $request->amount;
            $newExpense->date = $request->date;
            $newExpense->save();

            return response()->json(['message' => 'Expense added successfully'], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            \Log::error('Error adding expense: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to add expense'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function UpdateExpense(Request $request, $id){
        try{
            $updateExpense = Expense::find($id);

            if ($updateExpense) {
                $updateExpense->purpose = $request->has('purpose') ? $request->input('purpose') : $updateExpense->purpose;
                $updateExpense->amount = $request->has('amount') ? $request->input('amount') : $updateExpense->amount;
                $updateExpense->save();

                return response()->json(['message' => 'Expense updated successfully'], Response::HTTP_OK);
            } else {
                return response()->json(['error' => 'Expense not found'], Response::HTTP_NOT_FOUND);
            }
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
        try{
            $expense = Expense::find($id);
            if (!$expense) {
                return "No data found for the given ID";
            }
            $expense->delete();
            return "Delete success";
        }
        catch(\Exception $e){
            \Log::error("Error deleting:" . $e->getMessage());
            return response()->json(['error' => 'Failed to Delete expense'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }

}
