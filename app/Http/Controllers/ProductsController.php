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
    public function getProducts(Request $request){
        try {
            $date = $request->input('date');
            if ($date) {
                $products = Products::where('date', 'like', '%' . $date . '%')->get();

                return response()->json($products, Response::HTTP_OK);
            }
            $products = Products::all();
            return response()->json($products, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getStates(Request $request){

        try{
            $daysToSum = $request->input('days', $request);
            $endDate = Carbon::now();
            $startDate = $endDate->copy()->subDays($daysToSum);
            $total = array();

            $twelb = Products::whereBetween('created_at', [$startDate, $endDate])->sum("twelve_kg");
            $twentyfive = Products::whereBetween('created_at', [$startDate, $endDate])->sum("twentyfive_kg");
            $thirtythree = Products::whereBetween('created_at', [$startDate, $endDate])->sum("thirtythree_kg");
            $thirtyfive = Products::whereBetween('created_at', [$startDate, $endDate])->sum("thirtyfive_kg");
            $fourtyfive = Products::whereBetween('created_at', [$startDate, $endDate])->sum("fourtyfive_kg");
            array_push($total, (object)[
                'twelve_kg' => $twelb,
                'twentyfive_kg' => $twentyfive,
                'thirtythree_kg' => $thirtythree,
                'thirtyfive_kg' => $thirtyfive,
                'fourtyfive_kg' => $fourtyfive
            ]);
            return response()->json( $total, Response::HTTP_OK);
        }catch(\Exception $e){
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function updateProduct(Request $request, $id){
        try{
            $updatedProduct = Products::find($id);
            if($updatedProduct){
                $updatedProduct->twelve_kg = $request->has("twelve_kg") ? $request->input("twelve_kg") : $updatedProduct->twelve_kg;
                $updatedProduct->twentyfive_kg = $request->has("twentyfive_kg") ? $request->input('twentyfive_kg') : $updatedProduct->twentyfive_kg;
                $updatedProduct->thirtythree_kg = $request->has("thirtythree_kg") ? $request->input('thirtythree_kg') : $updatedProduct->thirtythree_kg;
                $updatedProduct->thirtyfive_kg = $request->has("thirtyfive_kg") ? $request->input('thirtyfive_kg') : $updatedProduct->thirtyfive_kg;
                $updatedProduct->fourtyfive_kg = $request->has("fourtyfive_kg") ? $request->input('fourtyfive_kg') : $updatedProduct->fourtyfive_kg;
                $updatedProduct->others_kg = $request->has("others_kg") ? $request->input('others_kg') : $updatedProduct->others_kg;
                $updatedProduct->empty_twelve_kg = $request->has("empty_twelve_kg") ? $request->input('empty_twelve_kg') : $updatedProduct->empty_twelve_kg;
                $updatedProduct->empty_twentyfive_kg = $request->has("empty_twentyfive_kg") ? $request->input('empty_twentyfive_kg') : $updatedProduct->empty_twentyfive_kg;
                $updatedProduct->empty_thirtythree_kg = $request->has("empty_thirtythree_kg") ? $request->input('empty_thirtythree_kg') : $updatedProduct->empty_thirtythree_kg;
                $updatedProduct->empty_thirtyfive_kg = $request->has("empty_thirtyfive_kg") ? $request->input('empty_thirtyfive_kg') : $updatedProduct->empty_thirtyfive_kg;
                $updatedProduct->empty_fourtyfive_kg = $request->has("empty_fourtyfive_kg") ? $request->input('empty_fourtyfive_kg') : $updatedProduct->empty_fourtyfive_kg;
                $updatedProduct->empty_others_kg = $request->has("empty_others_kg") ? $request->input('empty_others_kg') : $updatedProduct->empty_others_kg;
                $updatedProduct->price = $request->has("price") ? $request->input('price') : $updatedProduct->price;
                $updatedProduct->date = $request->has("date") ? $request->input('date') : $updatedProduct->date;
                $updatedProduct->save();
                return response()->json(['message' => 'Product updated successfully'], Response::HTTP_OK);
            } else {
                return response()->json(['error' => 'Expense not found'], Response::HTTP_NOT_FOUND);
            }

           
        } catch (\Exception $e) {
            \Log::error('Error adding product: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function deleteProduct($id){

    }

    public function getLastStock(){
        try{
            $lastRecord = Products::latest()->first();
            return response()->json( $lastRecord, Response::HTTP_OK);
        }catch (\Exception $e) {
            \Log::error('Error adding product: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}
