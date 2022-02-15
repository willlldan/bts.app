<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function signup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'phone' => 'required|string|max:13',
            'country' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'postcode' => 'required|numeric',
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {


            $user = User::create([
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'country' => $request->country,
                'city' => $request->city,
                'postcode' => $request->postcode,
                'name' => $request->name,
                'address' => $request->address,
            ]);

            $token = $user->createToken('auth_token')->plainTextToken;

            $response = [
                "user" => $user,
                "token" => $token,
                "email" => $user->email,
                "username" => $user->username,
            ];

            return response()->json($response, Response::HTTP_CREATED);
        } catch (QueryException $e) {
            return response()->json([
                'message' => "Failed "
            ]);
        }
    }

    public function signin(Request $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()
                ->json(['message' => 'Unauthorized'], 401);
        }

        $user = User::where('email', $request['email'])->firstOrFail();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'email' => $user->email,
            'token' => $token,
            'username' => $user->username
        ], Response::HTTP_OK);
    }

    // public function signOut()
    // {
    //     auth()->user()->tokens()->delete();

    //     return [
    //         'message' => 'You have successfully logged out and the token was successfully deleted'
    //     ];
    // }
}
