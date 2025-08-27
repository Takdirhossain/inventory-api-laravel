<?php

namespace App\Http\Controllers;

use App\Models\Products;
use App\Models\Sales;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use App\Models\Company;

class SalesController extends Controller
{
    public function newSales(Request $request)
    {
        try {
            $company_id = Auth::user()->company_id;
            $company = Company::where('id', $company_id)->first();
            $newSales = new Sales();
            $newSales->customer_name = $request->customer_name;
            $newSales->customer_id = $request->customer_id;
            $newSales->twelve_kg = $request->twelve_kg;
            $newSales->is_due_bill = $request->is_due_bill;
            $newSales->twentyfive_kg = $request->twentyfive_kg;
            $newSales->thirtythree_kg = $request->thirtythree_kg;
            $newSales->thirtyfive_kg = $request->thirtyfive_kg;
            $newSales->fourtyfive_kg = $request->fourtyfive_kg;
            $newSales->others_kg = $request->others_kg;
            $newSales->empty_twelve_kg = $request->empty_twelve_kg;
            $newSales->empty_twentyfive_kg = $request->empty_twentyfive_kg;
            $newSales->empty_thirtythree_kg = $request->empty_thirtythree_kg;
            $newSales->empty_thirtyfive_kg = $request->empty_thirtyfive_kg;
            $newSales->empty_fourtyfive_kg = $request->empty_fourtyfive_kg;
            $newSales->empty_others_kg = $request->empty_others_kg;
            $newSales->date = $request->date;
            $newSales->price = $request->price;
            $newSales->pay = $request->pay;
            $newSales->due = $request->due;
            $newSales->save();

            $company->cash = $company->cash + $request->pay;
            $company->due = $company->due + $request->due;
            $company->save();
            return response()->json(['message' => 'Product added successfully'], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            \Log::error('Error adding product: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function updateSale (Request $request){
        try{
            $company_id = Auth::user()->company_id;
            $company = Company::where('id', $company_id)->first();
            $sale = Sales::where('company_id', $company_id)->find($request->id);
            $sale->customer_name = $request->customer_name;
            $sale->customer_id = $request->customer_id;
            $sale->twelve_kg = $request->twelve_kg;
            $sale->is_due_bill = $request->is_due_bill;
            $sale->twentyfive_kg = $request->twentyfive_kg;
            $sale->thirtythree_kg = $request->thirtythree_kg;
            $sale->thirtyfive_kg = $request->thirtyfive_kg;
            $sale->fourtyfive_kg = $request->fourtyfive_kg;
            $sale->others_kg = $request->others_kg;
            $sale->empty_twelve_kg = $request->empty_twelve_kg;
            $sale->empty_twentyfive_kg = $request->empty_twentyfive_kg;
            $sale->empty_thirtythree_kg = $request->empty_thirtythree_kg;
            $sale->empty_thirtyfive_kg = $request->empty_thirtyfive_kg;
            $sale->empty_fourtyfive_kg = $request->empty_fourtyfive_kg;
            $sale->empty_others_kg = $request->empty_others_kg;
            $sale->date = $request->date;
            $sale->price = $request->price;
            $sale->pay = $request->pay;
            $sale->due = $request->due;
            $sale->save();

            $company->cash =($company->cash - $sale->pay) + $request->pay;
            $company->due =($company->due - $sale->due) + $request->due;
            $company->save();
            return response()->json(['message' => 'Product updated successfully'], Response::HTTP_OK);
        }
        catch(\Exception $e){
            \Log::error("Error updating:" . $e->getMessage());
            return response()->json(['error' => 'Failed to Update expense'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function getSalesList(Request $request)
    {
        try {
            $date = $request->date;
            $name = $request->customer_name;
            $isSale = $request->isSale;
            $check;
            if($isSale==false){
                $check = true;
            } else{
                $check = false;
            }
            if ($date) {
                $salesSearchByDate = Sales::where('date', 'like', '%' . $date . '%')
                ->where('is_due_bill', $check)
                ->orderBy('created_at', 'desc')
                ->get();
                return response()->json($salesSearchByDate, Response::HTTP_OK);
            }
            if ($name) {
                $salesSearchByDate = Sales::where('customer_name', 'like', '%' . $name . '%')
                ->where('is_due_bill', $check)
                    ->orderBy('created_at', 'desc')
                    ->get();
                return response()->json($salesSearchByDate, Response::HTTP_OK);
            }
            $startDate = Carbon::now()->subDays(30)->startOfDay();
            $endDate = Carbon::now()->endOfDay();
            $allResult = Sales::whereBetween('created_at', [$startDate, $endDate])
            ->where('is_due_bill', $check)
            ->orderBy('created_at', 'desc')
            ->get();

            return response()->json($allResult, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    public function getCollectionList(Request $request)
    {
        try {
            $date = $request->date;
            $name = $request->customer_name;

            if ($date) {
                $salesSearchByDate = Sales::where('date', 'like', '%' . $date . '%')
                ->where('is_due_bill', false)
                ->orderBy('created_at', 'desc')
                ->get();
                return response()->json($salesSearchByDate, Response::HTTP_OK);
            }
            if ($name) {
                $salesSearchByDate = Sales::where('customer_name', 'like', '%' . $name . '%')
                ->where('is_due_bill', false)
                    ->orderBy('created_at', 'desc')
                    ->get();
                return response()->json($salesSearchByDate, Response::HTTP_OK);
            }
            $startDate = Carbon::now()->subDays(30)->startOfDay();
            $endDate = Carbon::now()->endOfDay();
            $allResult = Sales::whereBetween('created_at', [$startDate, $endDate])
            ->where('is_due_bill', true)
            ->orderBy('created_at', 'desc')
            ->get();

            return response()->json($allResult, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }



    public function getLastsale()
    {
        try {
            $sales = Sales::latest()->first();
            return response()->json($sales, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function getCompares(Request $request)
    {
        try {

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }
    public function getStock()
    {
        try {
            $twelve_kg = Products::sum('twelve_kg') - Sales::sum('twelve_kg');
            $twentyfive_kg = Products::sum('twentyfive_kg') - Sales::sum('twentyfive_kg');
            $thirtythree_kg = Products::sum('thirtythree_kg') - Sales::sum('thirtythree_kg');
            $thirtyfive_kg = Products::sum('thirtyfive_kg') - Sales::sum('thirtyfive_kg');
            $fourtyfive_kg = Products::sum('fourtyfive_kg') - Sales::sum('fourtyfive_kg');

            $twelve_kg_empty = Sales::sum('empty_twelve_kg') - Products::sum('empty_twelve_kg');
            $twentyfive_kg_empty = Sales::sum('empty_twentyfive_kg') - Products::sum('empty_twentyfive_kg');
            $thirtythree_kg_empty = Sales::sum('empty_thirtythree_kg') - Products::sum('empty_thirtythree_kg');
            $thirtyfive_kg_empty = Sales::sum('empty_thirtyfive_kg') - Products::sum('empty_thirtyfive_kg');
            $fourtyfive_kg_empty = Sales::sum('empty_fourtyfive_kg') - Products::sum('empty_fourtyfive_kg');

            $filledStock = (object) [
                'twelve_kg' => $twelve_kg,
                'twentyfive_kg' => $twentyfive_kg,
                'thirtythree_kg' => $thirtythree_kg,
                'thirtyfive_kg' => $thirtyfive_kg,
                'fourtyfive_kg' => $fourtyfive_kg,
                'empty_twelve_kg' => $twelve_kg_empty,
                'empty_twentyfive_kg' => $twentyfive_kg_empty,
                'empty_thirtythree_kg' => $thirtythree_kg_empty,
                'empty_thirtyfive_kg' => $thirtyfive_kg_empty,
                'empty_fourtyfive_kg' => $fourtyfive_kg_empty,
            ];


            return response()->json($filledStock, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function getTodaySales()
    {
        try {
            $bangladeshDate = Carbon::now()->setTimezone('Asia/Dhaka')->format('Y-m-d');
            $totalPrice = Sales::where('date',  $bangladeshDate)->sum('price');
            return response()->json($totalPrice, Response::HTTP_OK);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function cash()
    {
        try {
            $bangladeshDate = Carbon::now()->setTimezone('Asia/Dhaka')->format('Y-m-d');
            $totalPrice = Sales::where('date',  $bangladeshDate)->sum('pay');
            return response()->json($totalPrice, Response::HTTP_OK);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function monthsales(){
        try{
            $todayDate = Carbon::now()->setTimezone('Asia/Dhaka')->format('Y-m-d');
            $startDate = Carbon::now()->startOfMonth()->format('Y-m-01');
            $thisMonths = Sales::whereBetween('date', [$startDate, $todayDate])
            ->sum('price');
            return response()->json($thisMonths, Response::HTTP_OK);
        }
        catch (\Exception $e) {
            return response()->json(['error'=> $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function getRecent(){
        try{
            $recentSale = Sales::latest()->take(10)->get();
            return response()->json($recentSale, Response::HTTP_OK);
        }
        catch(\Exception $e){
            return response()->json(['error'=> $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function customerStates(Request $request)
    {
        try {
            $userId = Auth::id();
    
            $days = $request->input('days', 30);
            $startDate = now()->subDays($days);
    
            $totals = Sales::where('customer_id', $userId)
                ->where('created_at', '>=', $startDate)
                ->selectRaw('
                    COALESCE(SUM(price),0) as totalBuy,
                    COALESCE(SUM(pay),0) as totalPay,
                    COALESCE(SUM(due),0) as totalDue,
                    COALESCE(SUM(twelve_kg),0) as twelve,
                    COALESCE(SUM(twentyfive_kg),0) as twentyfive,
                    COALESCE(SUM(thirtythree_kg),0) as thirtythree,
                    COALESCE(SUM(thirtyfive_kg),0) as thirtyfive,
                    COALESCE(SUM(fourtyfive_kg),0) as fourtyfive,
                    COALESCE(SUM(others_kg),0) as others
                ')
                ->first();
    
            $recentActivity = Sales::where('customer_id', $userId)
                ->where('created_at', '>=', $startDate)
                ->orderBy('created_at', 'desc')
                ->paginate(10);
    
            return response()->json([
                'filterDays'     => $days,
                'totalBuy'       => $totals->totalBuy,
                'totalPay'       => $totals->totalPay,
                'totalDue'       => $totals->totalDue,
                'twelve'         => $totals->twelve,
                'twentyfive'     => $totals->twentyfive,
                'thirtythree'    => $totals->thirtythree,
                'thirtyfive'     => $totals->thirtyfive,
                'fourtyfive'     => $totals->fourtyfive,
                'others'         => $totals->others,
                'recentActivity' => $recentActivity,
            ], Response::HTTP_OK);
    
        } catch (\Exception $e) {
            \Log::error("Error getting customer states: " . $e->getMessage());
            return response()->json(['error' => 'Failed to get customer states'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    


}
