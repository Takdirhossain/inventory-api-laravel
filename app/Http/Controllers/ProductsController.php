<?php

namespace App\Http\Controllers;

use App\Models\Products;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
class ProductsController extends Controller
{
    //
    public function addProduct(Request $request){
        try{
            $newProducts = new Products;
            $newProducts->twelve_kg = $request->twelve_kg;
            $newProducts->twentyfive_kg = $request->twentyfive_kg;
            $newProducts->thirtythree_kg = $request->thirtythree_kg;
            $newProducts->thirtyfive_kg = $request->thirtyfive_kg;
            $newProducts->fourtyfive_kg = $request->fourtyfive_kg;
            $newProducts->others_kg = $request->others_kg;
            $newProducts->empty_twelve_kg = $request->empty_twelve_kg;
            $newProducts->empty_twentyfive_kg = $request->empty_twentyfive_kg;
            $newProducts->empty_thirtythree_kg = $request->empty_thirtythree_kg;
            $newProducts->empty_thirtyfive_kg = $request->empty_thirtyfive_kg;
            $newProducts->empty_fourtyfive_kg = $request->empty_fourtyfive_kg;
            $newProducts->empty_others_kg = $request->empty_others_kg;
            $newProducts->price = $request->price;
            $newProducts->date = $request->date;
            $newProducts->save();
            return response()->json(['message' => 'Product added successfully'], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            \Log::error('Error adding product: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function getProducts(){
        try{
            $products = Products::all();
            return response()->json($products, Response::HTTP_OK);
        }catch(\Exception $e){
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function getStates(Request $request){

        try{
            $daysToSum = $request->input('days', 7); // Default to 7 days if not provided
            $endDate = Carbon::now();
            $startDate = $endDate->copy()->subDays($daysToSum);
            $total = array();
            $twelb = Products::sum("twelve_kg");
            $twentyfive = Products::sum("twentyfive_kg");
            $thirtythree = Products::sum("thirtythree_kg");
            $thirtyfive = Products::sum("thirtyfive_kg");
            $fourtyfive = Products::sum("fourtyfive_kg");
            array_push($total, (object)[
                'twelve_kg' => $twelb,
                'twentyfive_kg' => $twentyfive,
                'thirtythree_kg' => $thirtythree,
                'thirtyfive_kg' => $thirtyfive,
                'fourtyfive_kg' => $fourtyfive
            ]);
            return response()->json( $startDate, Response::HTTP_OK);
        }catch(\Exception $e){
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
