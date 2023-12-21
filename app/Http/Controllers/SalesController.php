<?php

namespace App\Http\Controllers;

use App\Models\Products;
use App\Models\Sales;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;

class SalesController extends Controller
{
    public function newSales(Request $request)
    {
        try {
            $newSales = new Sales();
            $newSales->customer_name = $request->customer_name;
            $newSales->customer_id = $request->customer_id;
            $newSales->twelve_kg = $request->twelve_kg;
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
            return response()->json(['message' => 'Product added successfully'], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            \Log::error('Error adding product: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function getSalesList(Request $request)
    {
        try {
            $date = $request->date;
            $name = $request->customer_name;
            $query = Sales::query();
            if ($date) {
                $salesSearchByDate = Sales::where('date', 'like', '%' . $date . '%')
                    ->orderBy('created_at', 'desc')
                    ->get();
                return response()->json($salesSearchByDate, Response::HTTP_OK);
            }
            if ($name) {
                $salesSearchByDate = Sales::where('customer_name', 'like', '%' . $name . '%')
                    ->orderBy('created_at', 'desc')
                    ->get();
                return response()->json($salesSearchByDate, Response::HTTP_OK);
            }
            $query->orderBy('date', 'desc');
            $sales = $query->get();

            return response()->json($sales, Response::HTTP_OK);
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
            $bangladeshDate = Carbon::now()->format('d-m-Y');
            $totalPrice = Sales::where('date',  $bangladeshDate)->sum('price');
            return response()->json($totalPrice, Response::HTTP_OK);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function monthsales(){
        try{
            $todayDate = Carbon::now()->format('d-m-Y');
            $startDate = Carbon::now()->format('01-m-Y');
            $thisMonths = Sales::whereBetween('date', [$startDate, $todayDate])
            ->sum('price');
            return response()->json($thisMonths, Response::HTTP_OK);
        }
        catch (\Exception $e) {
            return response()->json(['error'=> $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
