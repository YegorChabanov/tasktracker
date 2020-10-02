<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthLoginRequest;
use App\Http\Requests\AuthRegistrationRequest;
use App\Http\Resources\UserResource;
use App\User;

class AuthController extends Controller
{
    public function register(AuthRegistrationRequest $request)
    {
        $data = $request->all();
        $data['password'] = bcrypt($data['password']);
        $user = User::create($data);

        $accessToken = $user->createToken('authToken')->accessToken;

        return response()->json([
            'user' =>  new UserResource($user),
            'access_token' => $accessToken
        ]);
    }

    public function login(AuthLoginRequest $request)
    {
        if (!auth()->attempt($request->all())) {
            return response()->json(['message' => 'Invalid credentials']);
        }

        $accessToken = auth()->user()->createToken('authToken')->accessToken;

        return response()->json([
            'user' => new UserResource(auth()->user()),
            'access_token' => $accessToken
        ]);
    }
}
