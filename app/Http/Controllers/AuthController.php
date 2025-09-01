<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function login(Request $request){
        try {
           $user = User::where('email', $request->email)
            ->orWhere('login_id', $request->email)
            ->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json(['error' => 'Invalid credentials'], Response::HTTP_UNAUTHORIZED);
            }
            $token = $user->createToken('auth-token')->plainTextToken;
            return response()->json(['token' => $token, 'data' => $user], Response::HTTP_OK);     
            
        } catch (\Exception $e) {
            \Log::error('Error logging in: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
