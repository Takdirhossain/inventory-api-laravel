<?php

namespace App\Http\Controllers;


use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
class ExpenseController extends Controller
{
    public function addExpense(Request $request) {
        try {
            $newExpense = new Expense();
            $newExpense->company_id = Auth::user()->company_id;
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
            $updateExpense = Expense::where('company_id', Auth::user()->company_id)->find($id);

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

            return response()->json(['error' => 'Failed to update expense'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function getexpense()
    {
        $expense = Expense::where('company_id', Auth::user()->company_id)->all();
        return $expense;
    }
    public function singleexpense($id)
    {
        $singleExpense = Expense::where('company_id', Auth::user()->company_id)->find($id);
        return $singleExpense;
    }

    public function deleteExpense($id)
    {
        try{
            $expense = Expense::where('company_id', Auth::user()->company_id)->find($id);
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
    public function getStates(Request $request)
    {
        try {
            $companyId = Auth::user()->company_id;
    
            // Duration handle
            $days = $request->input('days', 30); // default 30 days
            $startDate = now()->subDays($days);
    
            // Expense summary
            $expenseSummary = Expense::where('company_id', $companyId)
                ->whereDate('date', '>=', $startDate)
                ->selectRaw('
                    COALESCE(SUM(amount),0) as totalExpense,
                    COUNT(*) as totalExpenseItems
                ')
                ->first();
    
            // Product purchase summary
            $productSummary = Product::where('company_id', $companyId)
                ->whereDate('date', '>=', $startDate)
                ->selectRaw('
                     COALESCE(SUM(price),0) as totalBuyCost,
                    COUNT(*) as totalProductsBought
                ')
                ->first();
    
            // Combine both (Expense + Product) into one list using UNION
            $expenses = Expense::where('company_id', $companyId)
                ->whereDate('date', '>=', $startDate)
                ->selectRaw('id, date, amount as cost, purpose');
    
            $products = Product::where('company_id', $companyId)
                ->whereDate('date', '>=', $startDate)
                ->selectRaw('id, date, price as cost');
    
            // Merge queries with union
            $combined = $expenses->unionAll($products)
                ->orderBy('date', 'desc')
                ->get();
    
            // Manual pagination
            $page = $request->get('page', 1);
            $perPage = $request->get('per_page', 10);
            $paginated = new \Illuminate\Pagination\LengthAwarePaginator(
                $combined->forPage($page, $perPage),
                $combined->count(),
                $perPage,
                $page,
                ['path' => $request->url(), 'query' => $request->query()]
            );
    
            return response()->json([
                'filterDays'      => $days,
                'expenseSummary'  => $expenseSummary,
                'productSummary'  => $productSummary,
                'grandTotal'      => $expenseSummary->totalExpense + $productSummary->totalBuyCost,
                'combinedList'    => $paginated
            ], Response::HTTP_OK);
    
        } catch (\Exception $e) {
            \Log::error("Error getting states: " . $e->getMessage());
            return response()->json(['error' => 'Failed to get expense states'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    
}
