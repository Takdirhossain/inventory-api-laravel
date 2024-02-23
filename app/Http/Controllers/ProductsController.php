<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

use App\Models\Products;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
class ProductsController extends Controller
{
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
                $products = Products::where('date', 'like', '%' . $date . '%')
                ->orderBy('created_at', 'desc')
                ->get();

                return response()->json($products, Response::HTTP_OK);
            }


            $startDate = Carbon::now()->subDays(60)->startOfDay();
            $endDate = Carbon::now()->endOfDay();
            $allResult = Products::whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->get();

            return response()->json($allResult, Response::HTTP_OK);
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
            $object = (object) [
                'twelve_kg' => $twelb,
                'twentyfive_kg' => $twentyfive,
                'thirtythree_kg' => $thirtythree,
                'thirtyfive_kg' => $thirtyfive,
                'fourtyfive_kg' => $fourtyfive
              ];
            return response()->json($object, Response::HTTP_OK);
        } catch(\Exception $e){
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
        try{
            $product = Products::find($id);
            if (!$product) {
                return "No data found for the given ID";
            }
            $product->delete();
            return "Delete success";
        }
        catch(\Exception $e){
            \Log::error("Error deleting:" . $e->getMessage());
            return response()->json(['error' => 'Failed to Delete expense'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
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
    public function getUpdatedStock(){
        try{
            $stock = DB::select("
            SELECT
                COALESCE(SUM(p.twelve_kg), 0) AS total_stock_twelve_kg,
                COALESCE(SUM(p.twentyfive_kg), 0) AS total_stock_twentyfive_kg,
                COALESCE(SUM(p.thirtythree_kg), 0) AS total_stock_thirtythree_kg,
                COALESCE(SUM(p.thirtyfive_kg), 0) AS total_stock_thirtyfive_kg,
                COALESCE(SUM(p.fourtyfive_kg), 0) AS total_stock_fourtyfive_kg,
                COALESCE(SUM(sales.twelve_kg), 0) AS total_sales_twelve_kg,
                COALESCE(SUM(sales.twentyfive_kg), 0) AS total_sales_twentyfive_kg,
                COALESCE(SUM(sales.thirtythree_kg), 0) AS total_sales_thirtythree_kg,
                COALESCE(SUM(sales.thirtyfive_kg), 0) AS total_sales_thirtyfive_kg,
                COALESCE(SUM(sales.fourtyfive_kg), 0) AS total_sales_fourtyfive_kg,
                (COALESCE(SUM(p.twelve_kg), 0) - COALESCE(SUM(sales.twelve_kg), 0)) AS net_stock_twelve_kg,
                (COALESCE(SUM(p.twentyfive_kg), 0) - COALESCE(SUM(sales.twentyfive_kg), 0)) AS net_stock_twentyfive_kg,
                (COALESCE(SUM(p.thirtythree_kg), 0) - COALESCE(SUM(sales.thirtythree_kg), 0)) AS net_stock_thirtythree_kg,
                (COALESCE(SUM(p.thirtyfive_kg), 0) - COALESCE(SUM(sales.thirtyfive_kg), 0)) AS net_stock_thirtyfive_kg,
                (COALESCE(SUM(p.fourtyfive_kg), 0) - COALESCE(SUM(sales.fourtyfive_kg), 0)) AS net_stock_fourtyfive_kg,

                COALESCE(SUM(p.empty_twelve_kg), 0) AS total_stock_empty_twelve_kg,
                COALESCE(SUM(p.empty_twentyfive_kg), 0) AS total_stock_empty_twentyfive_kg,
                COALESCE(SUM(p.empty_thirtythree_kg), 0) AS total_stock_empty_thirtythree_kg,
                COALESCE(SUM(p.empty_thirtyfive_kg), 0) AS total_stock_empty_thirtyfive_kg,
                COALESCE(SUM(p.empty_fourtyfive_kg), 0) AS total_stock_empty_fourtyfive_kg,
                COALESCE(SUM(sales.empty_twelve_kg), 0) AS total_sales_empty_twelve_kg,
                COALESCE(SUM(sales.empty_twentyfive_kg), 0) AS total_sales_empty_twentyfive_kg,
                COALESCE(SUM(sales.empty_thirtythree_kg), 0) AS total_sales_empty_thirtythree_kg,
                COALESCE(SUM(sales.empty_thirtyfive_kg), 0) AS total_sales_empty_thirtyfive_kg,
                COALESCE(SUM(sales.empty_fourtyfive_kg), 0) AS empty_fourtyfive_kg,
                (COALESCE(SUM(sales.empty_twelve_kg), 0) - COALESCE(SUM(p.empty_twelve_kg), 0)) AS net_stock_empty_twelve_kg,
                (COALESCE(SUM(sales.empty_twentyfive_kg), 0) - COALESCE(SUM(p.empty_twentyfive_kg), 0)) AS net_stock_empty_twentyfive_kg,
                (COALESCE(SUM(sales.empty_thirtythree_kg), 0) - COALESCE(SUM(p.empty_thirtythree_kg), 0)) AS net_stock_empty_thirtythree_kg,
                (COALESCE(SUM(sales.empty_thirtyfive_kg), 0) - COALESCE(SUM(p.empty_thirtyfive_kg), 0)) AS net_stock_empty_thirtyfive_kg,
                (COALESCE(SUM(sales.empty_fourtyfive_kg), 0) - COALESCE(SUM(p.empty_fourtyfive_kg), 0)) AS net_stock_empty_fourtyfive_kg
            FROM
                (SELECT
                    SUM(twelve_kg) AS twelve_kg,
                    SUM(twentyfive_kg) AS twentyfive_kg,
                    SUM(thirtythree_kg) AS thirtythree_kg,
                    SUM(thirtyfive_kg) AS thirtyfive_kg,
                    SUM(fourtyfive_kg) AS fourtyfive_kg,
                    SUM(empty_twelve_kg) AS empty_twelve_kg,
                    SUM(empty_twentyfive_kg) AS empty_twentyfive_kg,
                    SUM(empty_thirtythree_kg) AS empty_thirtythree_kg,
                    SUM(empty_thirtyfive_kg) AS empty_thirtyfive_kg,
                    SUM(empty_fourtyfive_kg) AS empty_fourtyfive_kg
                FROM products) AS p,
                (SELECT
                    SUM(twelve_kg) AS twelve_kg,
                    SUM(twentyfive_kg) AS twentyfive_kg,
                    SUM(thirtythree_kg) AS thirtythree_kg,
                    SUM(thirtyfive_kg) AS thirtyfive_kg,
                    SUM(fourtyfive_kg) AS fourtyfive_kg,
                    SUM(empty_twelve_kg) AS empty_twelve_kg,
                    SUM(empty_twentyfive_kg) AS empty_twentyfive_kg,
                    SUM(empty_thirtythree_kg) AS empty_thirtythree_kg,
                    SUM(empty_thirtyfive_kg) AS empty_thirtyfive_kg,
                    SUM(empty_fourtyfive_kg) AS empty_fourtyfive_kg
                FROM sales) AS sales;
        ");
        $firstObject = $stock[0];
        return response()->json($firstObject, Response::HTTP_OK);
        }catch(\Exception $e){
            \Log::error('Error adding product: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
