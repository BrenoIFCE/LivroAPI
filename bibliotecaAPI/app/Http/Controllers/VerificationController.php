<?php

namespace App\Http\Controllers;

use App\Mail\EmailVerificationUserMail;
use App\Models\User;
use http\Env\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\DB;

class VerificationController extends Controller
{
    public function verifyEmail(int $id, string $token) : JsonResponse
    {
        $user = DB::table('users')->where('id', $id)->first();

        if ($user) {
            DB::table('users')
                ->where('verification_token', $token)
                ->update(['email_verified_at' => now()]);
            return response()->json(['message' => 'E-mail verificado com sucesso!'],  200);
        }else{
            return response()->json(['message' => 'Token inválido ou expirado!'], 400);
        }
    }
    public function sendVerificationEmail(User $user) : JsonResponse
    {
        if (!$user || $user->hasVerifiedEmail()) {
            return response()->json(['message' => 'E-mail já verificado'], 400);
        }

        Mail::to($user->email)->send(new EmailVerificationUserMail($user));

        return response()->json(['message' => 'E-mail de verificação enviado!'], 200);
    }
}
