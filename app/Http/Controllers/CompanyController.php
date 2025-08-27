<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CompanyController extends Controller
{
    public function addCompany(Request $request){
        try {
            DB::beginTransaction();
            $company = new Company;
            $company->name = $request->name;
            $company->location = $request->location;
            $company->phone = $request->phone;
            $company->email = $request->email;
            $company->save();

            $user = new User;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->company_id = $company->id;
            $user->role = 1;
            $user->password = bcrypt($request->password);
            $user->save();


            DB::commit();
            return response()->json(['message' => 'Company added successfully'], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            \Log::error('Error adding company: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
