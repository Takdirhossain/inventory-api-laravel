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





    public function getCustomerWithSum(Request $request){
        $name = $request->input('name');
        $customers = Customers::with(['sales' => function ($query) {
            $query->orderByDesc('created_at');
        }]);
        if ($name) {
            $customers->where('name', 'like', '%' . $name . '%');
        }

        $customers = $customers->get();

        $customersWithSum = $customers->map(function ($customer) {
            $totalBuy = $customer->sales->sum('price');
            $totalPay = $customer->sales->sum('pay');

            $customer['buy'] = $totalBuy;
            $customer['pay'] = $totalPay;
            $customer['due'] = $totalBuy - $totalPay;

            return $customer;
        });

        if ($customersWithSum->isEmpty()) {
            return response()->json(['error' => 'No data found'], Response::HTTP_NOT_FOUND);
        }


        $totalDue = $customersWithSum->sum('due');
        $result = array([
            'customers' => $customersWithSum,
            'total_due' => $totalDue,
        ]);

        return   $result;
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
