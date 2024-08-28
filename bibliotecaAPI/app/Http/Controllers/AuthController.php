<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;



class AuthController extends Controller
{
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return $this->Response(
                message: 'Falha ao logar!',
                data: false,
                status: 401
            );
        }

        return $this->Response(
            message: 'Logado com sucesso!',
            data: [
                'token' => $token,
            ],
            status: 200
        );
    }

    public function me()
    {
        return response()->json(auth()->user());
    }

    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'logged out']);
    }

}
