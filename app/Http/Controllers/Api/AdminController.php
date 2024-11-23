<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Http\Requests\RegisterAdminRequest;
use App\Http\Requests\LoginAdminRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AdminController extends Controller
{
    public function registerAdmin(RegisterAdminRequest $request)
    {
        $request->validated();

        $adminData = [
            'username' => $request->username,
            'phoneNumber' => $request->phoneNumber,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ];

        $admin = Admin::create($adminData);
        $token = $admin->createToken('auth_token')->plainTextToken;

        return response([
            'admin'=>$admin,
            'token'=> $token,
        ],200);
    }

    public function checkEmailAvailabilityAdmin(Request $request)
    {
        // Validate request data
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            throw ValidationException::withMessages($validator->errors()->toArray());
        }

        // Check if email already exists
        $admin = Admin::where('email', $request->email)->first();

        // Return response based on email availability
        if ($admin) {
            return response()->json([
                'status' => false,
                'message' => 'Email sudah terdaftar',
            ]);
        } else {
            return response()->json([
                'status' => true,
                'message' => 'Email tersedia',
            ]);
        }
    }

    public function loginAdmin(LoginAdminRequest $request)
    {
        $validatedData = $request->validated();

        $admin = Admin::where('email', $validatedData['email'])->first();

        if (!$admin || !Hash::check($validatedData['password'], $admin->password)) {
            return response()->json([
                'message' => 'Email atau password anda salah'
            ], 400);
        }

        if ($admin->is_Active != 1) {
            return response()->json([
                'message' => 'Akun anda tidak aktif'
            ], 400);
        }

        $token = $admin->createToken('auth_token')->plainTextToken;

        return response()->json([
            'admin' => $admin,
            'token' => $token,
            'is_Active' => $admin->is_Active,
        ], 200);
    }
}
