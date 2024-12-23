<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{   
    // public function __construct()
    // {
    //     // Terapkan middleware 'check.user' untuk semua metode kecuali register, login, dan checkEmailAvailability
    //     $this->middleware('check.user')->except(['register', 'login', 'checkEmailAvailability']);
    // }

    public function register(RegisterUserRequest $request)
    {
        $request->validated();

        // Periksa apakah email sudah terdaftar
        $existingUser = User::where('email', $request->email)->first();
        if ($existingUser) {
            return response()->json([
                'status' => false,
                'message' => 'Email sudah terdaftar',
            ], 400);
        }

        $userData = [
            'username' => $request->username,
            'phoneNumber' => $request->phoneNumber,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'gender' => $request->gender,
            
        ];

        $user = User::create($userData);
        $token = $user->createToken('auth_token')->plainTextToken;

        return response([
            'user'=>$user,
            'token'=> $token,
        ],200);
    }

    public function checkEmailAvailability(Request $request)
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
        $user = User::where('email', $request->email)->first();

        // Return response based on email availability
        if ($user) {
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
    
    public function login(LoginUserRequest $request)
    {
        $validatedData = $request->validated();

        $user = User::where('email', $validatedData['email'])->first();

        if (!$user || !Hash::check($validatedData['password'], $user->password)) {
            return response()->json([
                'message' => 'Email atau password anda salah'
            ], 400);
        }

        if ($user->is_Active != 1) {
            return response()->json([
                'message' => 'Akun anda tidak aktif'
            ], 400);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
            'is_Active' => $user->is_Active,
        ], 200);
    }


     public function profile(Request $request)
     {
         $user = $request->user();
 
         return response()->json([
            'status' => true,
            'message' => 'Profil pengguna',
            'user' => $user,
         ]);
     }
 
     // Logout API
     public function logout(Request $request)
     {
         $request->user()->currentAccessToken()->delete();
 
         return response()->json([
            'status' => true,
            'message' => 'Logout berhasil',
         ]);
     }
 
     public function user(Request $request)
     {
        return response()->json($request->user());
     }

}

