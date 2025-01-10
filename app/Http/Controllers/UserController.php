<?php

namespace App\Http\Controllers;

use http\Env\Response;
use Illuminate\Http\Request;
USE Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $v = Validator::make($request->all(), [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|string|in:manager,admin,user',
            'img' => 'nullable|image',
        ]);

        if($v->fails())
        {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $v ->errors(),
            ], 422);
        }

        $avatarPatch = null;

        if($request->hasFile('avatar'))
        {
            $avatar = $request->File('img');
            $avatarPatch = Str::uuid() . '.' . $avatar->getClientOriginalExtension();
            $avatar->move(public_path('avatar'), $avatarPatch);
        }

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'avatar' => $avatarPatch,
        ]);

        return response()->json([
            "message" => "Successfully registered!",
            "data" => [
                'id' => $user->id,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'role' => $request->role,
                'avatar' => $avatarPatch,
            ],
        ], 200);
    }

    public function login(Request $request)
    {
        $v = Validator::make($request->all(), [
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($v->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $v->errors(),
            ], 422);
        }

        $credentials = $request->only('email', 'password');

        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return response()->json(["message" => "Authentication failed"], 401);
        }

        $bearerToken = Str::random();
        $user->bearerToken = $bearerToken;
        $user->save();

        return response()->json([
            "message" => "Successfully logged in!",
            "data" => [
                "profile" => [
                    "id" => 1,
                    "first_name" => $user->first_name,
                    "last_name" => $user->last_name,
                    "email" => $user->email,
                    "role" => $user->role,
                    "avatar" => $user->avatar,
                ],
                "credentials" => [
                    "token" => $bearerToken,
                ],
            ],
        ], 200);
    }
}
