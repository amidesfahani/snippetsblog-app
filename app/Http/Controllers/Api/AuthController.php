<?php

namespace App\Http\Controllers\Api;

use App\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController  extends Controller
{
	public function register(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'username' => 'required|string|unique:users',
			'password' => 'required|string|min:6',
		]);

		if ($validator->fails()) {

			Log::warning('Register validation failed', [
				'errore' => $validator->errors(),
			]);

			return response()->json([
				'errors' => $validator->errors()
			], 422);
		}

		try {
			$user = User::create([
				'username' => $request->username,
				'password' => Hash::make($request->password),
			]);

			$token = JWTAuth::fromUser($user);

			return response()->json([
				'user' => UserResource::make($user),
				'token' => $token,
			], 201);
		} catch (\Throwable $e) {
			Log::error('Registration failed', [
				'error' => $e->getMessage(),
				'trace' => $e->getTraceAsString()
			]);

			return response()->json(['message' => 'Server Error'], 500);
		}
	}

	public function login(Request $request)
	{
		$credentials = $request->only('username', 'password');

		if (! $token = JWTAuth::attempt($credentials)) {
			return response()->json(['error' => 'Unauthorized'], 401);
		}

		return response()->json([
			'token' => $token,
		]);
	}

	public function me()
	{
		return response()->json(UserResource::make(auth()->user()));
	}

	public function logout()
	{
		auth()->logout();

		return response()->json(['message' => 'Successfully logged out']);
	}

	public function refresh()
	{
		/** @var Illuminate\Auth\AuthManager */
		$auth = auth();

		return response()->json([
			'token' => $auth->refresh(),
		]);
	}
}
