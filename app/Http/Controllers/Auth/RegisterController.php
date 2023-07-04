<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function __invoke(
        RegisterRequest $request
    ): JsonResponse {
        $attributes = $request->only(['name', 'email', 'password']);
        $attributes['password'] = Hash::make($attributes['password']);

        $user = User::create($attributes);

        return response()->created(
            data: [
                'token' => $user->createToken('API_TOKEN')->plainTextToken,
            ],
        );
    }
}
