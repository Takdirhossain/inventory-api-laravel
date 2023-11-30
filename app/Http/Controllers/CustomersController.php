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
