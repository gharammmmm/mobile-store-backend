<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // POST /api/register
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|min:3|max:100|unique:users,username',
            'email'    => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            $firstError = $validator->errors()->first();

            // نحاكي نفس رسائل الـ PHP القديمة
            if ($validator->errors()->has('username') || $validator->errors()->has('email')) {
                $isDuplicate = User::where('email', $request->email)
                    ->orWhere('username', $request->username)
                    ->exists();

                if ($isDuplicate) {
                    return response()->json(['error' => 'Email or username already exists'], 409);
                }
            }

            return response()->json(['error' => $firstError], 400);
        }

        $user = User::create([
            'username' => $request->username,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $request->session()->put('user_id',  $user->id);
        $request->session()->put('username', $user->username);
        $request->session()->put('email',    $user->email);

        return response()->json([
            'success' => true,
            'message' => 'Account created successfully',
            'user'    => [
                'id'       => $user->id,
                'username' => $user->username,
                'email'    => $user->email,
            ],
        ], 201);
    }

    // POST /api/login
    public function login(Request $request)
    {
        $email    = trim($request->input('email', ''));
        $password = trim($request->input('password', ''));

        if (!$email || !$password) {
            return response()->json(['error' => 'Email and password are required'], 400);
        }

        $user = User::where('email', $email)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            return response()->json(['error' => 'Invalid email or password'], 401);
        }

        $request->session()->put('user_id',  $user->id);
        $request->session()->put('username', $user->username);
        $request->session()->put('email',    $user->email);

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'user'    => [
                'id'       => $user->id,
                'username' => $user->username,
                'email'    => $user->email,
            ],
        ]);
    }

    // POST /api/logout
    public function logout(Request $request)
    {
        $request->session()->flush();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully',
        ]);
    }

    // GET /api/auth-check
    public function check(Request $request)
    {
        if ($request->session()->has('user_id')) {
            return response()->json([
                'success'       => true,
                'authenticated' => true,
                'user'          => [
                    'id'       => $request->session()->get('user_id'),
                    'username' => $request->session()->get('username'),
                    'email'    => $request->session()->get('email'),
                ],
            ]);
        }

        return response()->json(['success' => true, 'authenticated' => false]);
    }
}
