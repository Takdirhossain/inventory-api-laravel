<?php

namespace App\Http\Controllers;

use App\Models\Products;
use App\Models\Sales;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use App\Models\Company;
use App\Models\Customers;

class SalesController extends Controller
{
    public function newSales(Request $request)
    {
        try {
            foreach ($request->customer as $customer) {
    
                $newSales = new Sales();
                $newSales->customer_name = $customer['label'];
                $newSales->customer_id = $customer['value'];
    
                $newSales->twelve_kg = $request->twelve_kg ?? 0;
                $newSales->twentyfive_kg = $request->twentyfive_kg ?? 0;
                $newSales->thirtythree_kg = $request->thirtythree_kg ?? 0;
                $newSales->thirtyfive_kg = $request->thirtyfive_kg ?? 0;
                $newSales->fourtyfive_kg = $request->fourtyfive_kg ?? 0;
                $newSales->others_kg = $request->others_kg ?? 0;
    
                $newSales->empty_twelve_kg = $request->empty_twelve_kg ?? 0;
                $newSales->empty_twentyfive_kg = $request->empty_twentyfive_kg ?? 0;
                $newSales->empty_thirtythree_kg = $request->empty_thirtythree_kg ?? 0;
                $newSales->empty_thirtyfive_kg = $request->empty_thirtyfive_kg ?? 0;
                $newSales->empty_fourtyfive_kg = $request->empty_fourtyfive_kg ?? 0;
                $newSales->empty_others_kg = $request->empty_others_kg ?? 0;
    
                $newSales->date = $request->date;
                $newSales->is_due_bill = $request->is_due_bill ?? 0;
                $newSales->price = $request->price ?? 0;
                $newSales->pay = $request->pay ?? 0;
                $newSales->due = $request->due ?? 0;
    
                // Calculation (empty হলে 0 ধরা হচ্ছে)
                $newSales->price_12_kg = ((int) $request->price_12_kg ?? 0) * ((int) $request->twelve_kg ?? 0);
                $newSales->price_25_kg = ((int) $request->price_25_kg ?? 0) * ((int) $request->twentyfive_kg ?? 0);
                $newSales->price_33_kg = ((int) $request->price_33_kg ?? 0) * ((int) $request->thirtythree_kg ?? 0);
                $newSales->price_35_kg = ((int) $request->price_35_kg ?? 0) * ((int) $request->thirtyfive_kg ?? 0);
                $newSales->price_45_kg = ((int) $request->price_45_kg ?? 0) * ((int) $request->fourtyfive_kg ?? 0);
    
                $newSales->save();
            }
    
            return response()->json(['message' => 'Sales added successfully'], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            \Log::error('Error adding sales: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    
    public function updateSale(Request $request)
    {
        try {
            $sale = Sales::find($request->id);
            if (isset($request->customer[0])) {
                $sale->customer_name = $request->customer[0]['label'];
                $sale->customer_id = $request->customer[0]['value'];
            } else {
                // fallback if not provided
                $sale->customer_name = null;
                $sale->customer_id = null;
            }
            $sale->twelve_kg = $request->twelve_kg ?? 0;
            $sale->twentyfive_kg = $request->twentyfive_kg ?? 0;
            $sale->thirtythree_kg = $request->thirtythree_kg ?? 0;
            $sale->thirtyfive_kg = $request->thirtyfive_kg ?? 0;
            $sale->fourtyfive_kg = $request->fourtyfive_kg ?? 0;
            $sale->others_kg = $request->others_kg ?? 0;

            $sale->empty_twelve_kg = $request->empty_twelve_kg ?? 0;
            $sale->empty_twentyfive_kg = $request->empty_twentyfive_kg ?? 0;
            $sale->empty_thirtythree_kg = $request->empty_thirtythree_kg ?? 0;
            $sale->empty_thirtyfive_kg = $request->empty_thirtyfive_kg ?? 0;
            $sale->empty_fourtyfive_kg = $request->empty_fourtyfive_kg ?? 0;
            $sale->empty_others_kg = $request->empty_others_kg ?? 0;

            $sale->date = $request->date;
            $sale->is_due_bill = $request->is_due_bill ?? 0;
            $sale->price = $request->price ?? 0;
            $sale->pay = $request->pay ?? 0;
            $sale->due = $request->due ?? 0;

            // Calculation (empty হলে 0 ধরা হচ্ছে)
            $sale->price_12_kg = ((int) $request->price_12_kg ?? 0) * ((int) $request->twelve_kg ?? 0);
            $sale->price_25_kg = ((int) $request->price_25_kg ?? 0) * ((int) $request->twentyfive_kg ?? 0);
            $sale->price_33_kg = ((int) $request->price_33_kg ?? 0) * ((int) $request->thirtythree_kg ?? 0);
            $sale->price_35_kg = ((int) $request->price_35_kg ?? 0) * ((int) $request->thirtyfive_kg ?? 0);
            $sale->price_45_kg = ((int) $request->price_45_kg ?? 0) * ((int) $request->fourtyfive_kg ?? 0);

            $sale->save();


            return response()->json(['message' => 'Product updated successfully'], Response::HTTP_OK);
        } catch (\Exception $e) {
            \Log::error("Error updating:" . $e->getMessage());
            return response()->json(['error' => 'Failed to Update expense' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function getSalesList(Request $request)
    {
        try {
            $perPage = $request->get('per_page', 10); // default 10
            $date = $request->get('date');
            $startDate = $request->get('start_date');
            $endDate = $request->get('end_date');
            $name = $request->get('customer_name');
            $isSale = filter_var($request->get('isSale', true), FILTER_VALIDATE_BOOLEAN);

            $isDueBill = $isSale ? false : true;

            $query = Sales::with('customer') 
                ->where('is_due_bill', $isDueBill);

            if ($date) {
                $query->whereDate('date', $date);
            }

            if ($startDate && $endDate) {
                $query->whereBetween('date', [$startDate, $endDate]);
            }

            if ($name) {
                $query->whereHas('customer', function ($q) use ($name) {
                    $q->where('name', 'like', '%' . $name . '%');
                });
            }

            $sales = $query->orderBy('created_at', 'desc')
                ->paginate($perPage);

            return response()->json([
                'status' => true,
                'data' => $sales,
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    public function getCollectionList(Request $request)
    {
        try {
            $search = $request->get('search'); // search input
            $perPage = $request->get('per_page', 20);
    
            $query = Sales::query()
                ->where('is_due_bill', true)
                ->join('customers', 'sales.customer_id', '=', 'customers.id')
                ->select('sales.*', 'customers.name as customer_name') // ধরে নিলাম customers টেবিলে কলাম "name" আছে
                ->orderBy('sales.created_at', 'desc');
    
            if (!empty($search)) {
                $query->where(function ($q) use ($search) {
                    $q->where('customers.name', 'like', "%{$search}%")
                      ->orWhere('sales.date', 'like', "%{$search}%");
                });
            }
    
            $allResult = $query->with('customer')->paginate($perPage);
    
            return response()->json([
                'status' => true,
                'data' => $allResult,
            ], Response::HTTP_OK);
    
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
            $totalPrice = Sales::where('date', $bangladeshDate)->sum('price');
            return response()->json($totalPrice, Response::HTTP_OK);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function cash()
    {
        try {
            $bangladeshDate = Carbon::now()->setTimezone('Asia/Dhaka')->format('Y-m-d');
            $totalPrice = Sales::where('date', $bangladeshDate)->sum('pay');
            return response()->json($totalPrice, Response::HTTP_OK);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function monthsales()
    {
        try {
            $todayDate = Carbon::now()->setTimezone('Asia/Dhaka')->format('Y-m-d');
            $startDate = Carbon::now()->startOfMonth()->format('Y-m-01');
            $thisMonths = Sales::whereBetween('date', [$startDate, $todayDate])
                ->sum('price');
            return response()->json($thisMonths, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function getRecent()
    {
        try {
            $recentSale = Sales::latest()->take(10)->get();
            return response()->json($recentSale, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
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
                'filterDays' => $days,
                'totalBuy' => $totals->totalBuy,
                'totalPay' => $totals->totalPay,
                'totalDue' => $totals->totalDue,
                'twelve' => $totals->twelve,
                'twentyfive' => $totals->twentyfive,
                'thirtythree' => $totals->thirtythree,
                'thirtyfive' => $totals->thirtyfive,
                'fourtyfive' => $totals->fourtyfive,
                'others' => $totals->others,
                'recentActivity' => $recentActivity,
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            \Log::error("Error getting customer states: " . $e->getMessage());
            return response()->json(['error' => 'Failed to get customer states'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function details(Request $request, $id)
    {
        try {
            $sales = Sales::where('customer_id', $id)
                ->selectRaw('SUM(CAST(price AS UNSIGNED)) as totalBuy, SUM(CAST(pay AS UNSIGNED)) as totalPay')
                ->first();
    
            $totalBuy = $sales->totalBuy ?? 0;
            $totalPay = $sales->totalPay ?? 0;
            $totalDue = $totalBuy - $totalPay;
    
            return response()->json([
                'totalBuy' => $totalBuy,
                'totalPay' => $totalPay,
                'totalDue' => $totalDue,
            ], Response::HTTP_OK);
    
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function collection(Request $request)
    {
        try {
            // Find customer
            $customer = Customers::find($request->customer);
            if (!$customer) {
                return response()->json(['error' => 'Customer not found'], Response::HTTP_NOT_FOUND);
            }
    
            // Check if this is an update
            if ($request->id) {
                $sale = Sales::find($request->id);
                if (!$sale) {
                    return response()->json(['error' => 'Sale not found'], Response::HTTP_NOT_FOUND);
                }
    
                // Update existing sale
                $sale->customer_id = $request->customer;
                $sale->customer_name = $customer->name;
                $sale->pay = $request->pay;
                $sale->date = $request->date;
                $sale->is_due_bill = 1;
                $sale->save();
    
                return response()->json(['message' => 'Collection updated successfully'], Response::HTTP_OK);
            }
    
            // Create new sale
            $newCollection = new Sales();
            $newCollection->customer_id = $request->customer;
            $newCollection->customer_name = $customer->name;
            $newCollection->pay = $request->pay;
            $newCollection->date = $request->date;
            $newCollection->is_due_bill = 1;
            $newCollection->save();
    
            return response()->json(['message' => 'Collection added successfully'], Response::HTTP_OK);
    
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    


}
