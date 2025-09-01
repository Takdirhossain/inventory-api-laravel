<?php

namespace App\Http\Controllers;

use App\Models\Customers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CustomersController extends Controller
{
    public function addCustomer(Request $request)
    {
        try {
            $customer = new Customers;
            $customer->name = $request->name;
            $customer->save();
            return response()->json(['message' => 'Customer added successfully'], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            \Log::error('Error adding expense: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function getCustomer(){
        $customer = Customers::all();
        if(sizeof($customer) == 0){
            return response()->json(['error' => 'No data found'], Response::HTTP_NOT_FOUND);
        }
        return $customer;
    }





    public function getCustomerWithSum(Request $request)
    {
        $params  = $request->input('params', []);
        $page    = $params['page']     ?? $request->input('page', 1);
        $perPage = $params['per_page'] ?? $request->input('per_page', 10);
        $name    = $params['search']   ?? $request->input('name', '');
    
        $customers = Customers::with(['sales' => function ($query) {
            $query->orderByDesc('created_at');
        }]);
    
        if ($name) {
            $customers->where('name', 'like', '%' . $name . '%');
        }
    
        // এখানে paginate করলাম
        $customers = $customers->paginate($perPage, ['*'], 'page', $page);
    
        // এখন প্রতিটা কাস্টমারকে map করলাম
        $customersTransformed = $customers->getCollection()->map(function ($customer) {
            $totalBuy = $customer->sales->sum('price');
            $totalPay = $customer->sales->sum('pay');
            $totalDue = $customer->sales->sum('due');
    
            $purchaseCylinders = [
                'twelve_kg'      => $customer->sales->sum(fn($s) => (int)$s->twelve_kg),
                'twentyfive_kg'  => $customer->sales->sum(fn($s) => (int)$s->twentyfive_kg),
                'thirtythree_kg' => $customer->sales->sum(fn($s) => (int)$s->thirtythree_kg),
                'thirtyfive_kg'  => $customer->sales->sum(fn($s) => (int)$s->thirtyfive_kg),
                'fourtyfive_kg'  => $customer->sales->sum(fn($s) => (int)$s->fourtyfive_kg),
                'others_kg'      => $customer->sales->sum(fn($s) => (int)$s->others_kg),
            ];
    
            $emptyCylinders = [
                'empty_twelve_kg'      => $customer->sales->sum(fn($s) => (int)$s->empty_twelve_kg),
                'empty_twentyfive_kg'  => $customer->sales->sum(fn($s) => (int)$s->empty_twentyfive_kg),
                'empty_thirtythree_kg' => $customer->sales->sum(fn($s) => (int)$s->empty_thirtythree_kg),
                'empty_thirtyfive_kg'  => $customer->sales->sum(fn($s) => (int)$s->empty_thirtyfive_kg),
                'empty_fourtyfive_kg'  => $customer->sales->sum(fn($s) => (int)$s->empty_fourtyfive_kg),
                'empty_others_kg'      => $customer->sales->sum(fn($s) => (int)$s->empty_others_kg),
            ];
    
            return [
                'id'                => $customer->id,
                'name'              => $customer->name,
                'total_purchase'    => $totalBuy,
                'total_paid'        => $totalPay,
                'total_due'         => $totalDue,
                'purchase_cylinders'=> $purchaseCylinders,
                'empty_cylinders'   => $emptyCylinders,
            ];
        });
    
        // Pagination এর collection override করে দিলাম
        $customers->setCollection($customersTransformed);
    
        // Grand totals সব পেইজ মিলিয়ে বের করতে চাইলে আলাদা query চালাতে হবে
        $allCustomers = Customers::with('sales');
        if ($name) {
            $allCustomers->where('name', 'like', '%' . $name . '%');
        }
        $allData = $allCustomers->get();
    
        $customersWithSumAll = $allData->map(function ($customer) {
            return [
                'total_purchase' => $customer->sales->sum('price'),
                'total_paid'     => $customer->sales->sum('pay'),
                'total_due'      => $customer->sales->sum('due'),
                'purchase_cylinders' => [
                    'twelve_kg'      => $customer->sales->sum(fn($s) => (int)$s->twelve_kg),
                    'twentyfive_kg'  => $customer->sales->sum(fn($s) => (int)$s->twentyfive_kg),
                    'thirtythree_kg' => $customer->sales->sum(fn($s) => (int)$s->thirtythree_kg),
                    'thirtyfive_kg'  => $customer->sales->sum(fn($s) => (int)$s->thirtyfive_kg),
                    'fourtyfive_kg'  => $customer->sales->sum(fn($s) => (int)$s->fourtyfive_kg),
                    'others_kg'      => $customer->sales->sum(fn($s) => (int)$s->others_kg),
                ],
                'empty_cylinders' => [
                    'empty_twelve_kg'      => $customer->sales->sum(fn($s) => (int)$s->empty_twelve_kg),
                    'empty_twentyfive_kg'  => $customer->sales->sum(fn($s) => (int)$s->empty_twentyfive_kg),
                    'empty_thirtythree_kg' => $customer->sales->sum(fn($s) => (int)$s->empty_thirtythree_kg),
                    'empty_thirtyfive_kg'  => $customer->sales->sum(fn($s) => (int)$s->empty_thirtyfive_kg),
                    'empty_fourtyfive_kg'  => $customer->sales->sum(fn($s) => (int)$s->empty_fourtyfive_kg),
                    'empty_others_kg'      => $customer->sales->sum(fn($s) => (int)$s->empty_others_kg),
                ],
            ];
        });
    
        $grandTotals = [
            'purchase' => $customersWithSumAll->sum('total_purchase'),
            'paid'     => $customersWithSumAll->sum('total_paid'),
            'due'      => $customersWithSumAll->sum('total_due'),
            'purchase_cylinders' => [
                'twelve_kg'      => $customersWithSumAll->sum(fn($c) => $c['purchase_cylinders']['twelve_kg']),
                'twentyfive_kg'  => $customersWithSumAll->sum(fn($c) => $c['purchase_cylinders']['twentyfive_kg']),
                'thirtythree_kg' => $customersWithSumAll->sum(fn($c) => $c['purchase_cylinders']['thirtythree_kg']),
                'thirtyfive_kg'  => $customersWithSumAll->sum(fn($c) => $c['purchase_cylinders']['thirtyfive_kg']),
                'fourtyfive_kg'  => $customersWithSumAll->sum(fn($c) => $c['purchase_cylinders']['fourtyfive_kg']),
                'others_kg'      => $customersWithSumAll->sum(fn($c) => $c['purchase_cylinders']['others_kg']),
            ],
            'empty_cylinders' => [
                'empty_twelve_kg'      => $customersWithSumAll->sum(fn($c) => $c['empty_cylinders']['empty_twelve_kg']),
                'empty_twentyfive_kg'  => $customersWithSumAll->sum(fn($c) => $c['empty_cylinders']['empty_twentyfive_kg']),
                'empty_thirtythree_kg' => $customersWithSumAll->sum(fn($c) => $c['empty_cylinders']['empty_thirtythree_kg']),
                'empty_thirtyfive_kg'  => $customersWithSumAll->sum(fn($c) => $c['empty_cylinders']['empty_thirtyfive_kg']),
                'empty_fourtyfive_kg'  => $customersWithSumAll->sum(fn($c) => $c['empty_cylinders']['empty_fourtyfive_kg']),
                'empty_others_kg'      => $customersWithSumAll->sum(fn($c) => $c['empty_cylinders']['empty_others_kg']),
            ],
        ];
    
        return response()->json([
            'status' => 'success',
            'data'   => [
                'customers'    => $customers,   // এখানে pagination data (links, meta সহ)
                'grand_totals' => $grandTotals, // সব কাস্টমারের টোটাল
            ]
        ], 200);
    }



    public function lastCustomers( Request $request){
        $limit = $request->input('limit');
            try {
                if($limit){
                    $recentCustomer = Customers::latest()->take(5)->get();
                    return response()->json($recentCustomer, Response::HTTP_OK);
                }
                $customer = Customers::all();
                return $customer;
            } catch (\Exception $e) {
                \Log::error('Error getting last customer: ' . $e->getMessage());
                return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
            }


    }


    public function updateCustomer( Request $request, $id){

        try {
            $customer = Customers::find($id);
            $customer->name = $request->name;
            $customer->save();
            return response()->json(['message' => 'Customer updated successfully'], Response::HTTP_OK);
        } catch (\Exception $e) {
            \Log::error('Error updating expense: '. $e->getMessage());
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function deleteCustomer($id){
        try{
            $customer = Customers::find($id);
            if (!$customer) {
                return "No data found for the given ID";
            }
            $customer->delete();
            return "Delete success";
        }
        catch(\Exception $e){
            \Log::error("Error deleting:". $e->getMessage());
            return response()->json(['error' => 'Failed to Delete customer'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
