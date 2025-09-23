<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

use App\Models\Products;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\Company;
use Log;
class ProductsController extends Controller
{
    
    public function addProduct(Request $request)
    {
        // Validation rules
        $validated = $request->validate([
            'twelve_kg' => 'nullable|numeric',
            'twentyfive_kg' => 'nullable|numeric',
            'thirtythree_kg' => 'nullable|numeric',
            'thirtyfive_kg' => 'nullable|numeric',
            'fourtyfive_kg' => 'nullable|numeric',
            'others_kg' => 'nullable|numeric',
            'empty_twelve_kg' => 'nullable|numeric',
            'empty_twentyfive_kg' => 'nullable|numeric',
            'empty_thirtythree_kg' => 'nullable|numeric',
            'empty_thirtyfive_kg' => 'nullable|numeric',
            'empty_fourtyfive_kg' => 'nullable|numeric',
            'empty_others_kg' => 'nullable|numeric',
            'price' => 'required|numeric',
            'date' => 'required|date',
            'is_package' => 'nullable|boolean',
        ]);
    
        try {
            $product = Products::create($validated);
    
            return response()->json([
                'status' => true,
                'message' => 'Product added successfully',
                'data' => $product
            ], Response::HTTP_CREATED);
    
        } catch (\Exception $e) {
            Log::error('Error adding product: ' . $e->getMessage());
    
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function singleProduct($id){
        try {
            $product = Products::find($id);
            return response()->json([
                'status' => true,
                'data'   => $product,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Something went wrong.',
                'error'   => $e->getMessage(), // useful for debugging
            ], 500);
        }
    }
    
    public function getProducts(Request $request)
    {
        try {
            $date = $request->input('params.search');
            $perPage = $request->input('per_page', 15); 
    
            $query = Products::query();
    
            if ($date) {
                $query->where('date', 'like', '%' . $date . '%');
            } else {
                // Default last 60 days
                $startDate = Carbon::now()->subDays(60)->startOfDay();
                $endDate   = Carbon::now()->endOfDay();
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }
    
            $products = $query->orderBy('created_at', 'desc')
                              ->paginate($perPage);
    
            return response()->json([
                'status' => true,
                'data'   => $products,
            ], 200);
    
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Something went wrong.',
                'error'   => $e->getMessage(), // useful for debugging
            ], 500);
        }
    }
    

    public function getStates(Request $request){
        try{
            $company_id = Auth::user()->company_id;
            $daysToSum = $request->input('days', $request);
            $endDate = Carbon::now();
            $startDate = $endDate->copy()->subDays($daysToSum);
            $total = array();

            $twelb = Products::whereBetween('created_at', [$startDate, $endDate])->where('company_id', $company_id)->sum("twelve_kg");
            $twentyfive = Products::whereBetween('created_at', [$startDate, $endDate])->where('company_id', $company_id)->sum("twentyfive_kg");
            $thirtythree = Products::whereBetween('created_at', [$startDate, $endDate])->where('company_id', $company_id)->sum("thirtythree_kg");
            $thirtyfive = Products::whereBetween('created_at', [$startDate, $endDate])->where('company_id', $company_id)->sum("thirtyfive_kg");
            $fourtyfive = Products::whereBetween('created_at', [$startDate, $endDate])->where('company_id', $company_id)->sum("fourtyfive_kg");

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

    public function updateProduct(Request $request, $id)
    {
        // Validation rules (same as create, but nullable for update)
        $validated = $request->validate([
            'twelve_kg' => 'nullable|numeric',
            'twentyfive_kg' => 'nullable|numeric',
            'thirtythree_kg' => 'nullable|numeric',
            'thirtyfive_kg' => 'nullable|numeric',
            'fourtyfive_kg' => 'nullable|numeric',
            'others_kg' => 'nullable|numeric',
            'empty_twelve_kg' => 'nullable|numeric',
            'empty_twentyfive_kg' => 'nullable|numeric',
            'empty_thirtythree_kg' => 'nullable|numeric',
            'empty_thirtyfive_kg' => 'nullable|numeric',
            'empty_fourtyfive_kg' => 'nullable|numeric',
            'empty_others_kg' => 'nullable|numeric',
            'price' => 'nullable|numeric',
            'date' => 'nullable|date',
            'is_package' => 'nullable|boolean',
        ]);
    
        try {
            $product = Products::find($id);
    
            if (!$product) {
                return response()->json([
                    'status' => false,
                    'message' => 'Product not found'
                ], Response::HTTP_NOT_FOUND);
            }
    
            // Keep old price for later comparison
            $oldPrice = $product->price;
    
            // Update product fields
            $product->fill($validated);
            $product->save();
    
            return response()->json([
                'status' => true,
                'message' => 'Product updated successfully',
                'data' => $product
            ], Response::HTTP_OK);
    
        } catch (\Exception $e) {
            \Log::error('Error updating product: ' . $e->getMessage());
    
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function deleteProduct($id){
        try{
            $product = Products::where('company_id', Auth::user()->company_id)->find($id);
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
            $lastRecord = Products::where('company_id', Auth::user()->company_id)->latest()->first();
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
