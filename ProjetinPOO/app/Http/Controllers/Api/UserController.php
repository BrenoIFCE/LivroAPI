<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\VerificationController;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function __construct(
        private VerificationController $verificationController
    )
    { }

    //Retorna usuarios recuperados
    public function index(): JsonResponse
    {
        $users = User::all();
        return $this->Response(
            message: 'Usuários listados com sucesso!',
            data: $users,
            status: 200
        );
    }

    public function store(UserRequest $request) : JsonResponse
    {
        DB::beginTransaction();

        try {
            $user = User::create([
                'name' =>$request->name,
                'email' => $request->email,
                'password' => $request->password,
                'verification_token' => Str::uuid()
            ]);

            $this->verificationController->sendVerificationEmail($user);

            DB::commit();

            return $this->Response(
                message: 'Usuários cadastrado com sucesso!',
                data: true,
                status: 201
            );

        }catch (Exception $e){
            dd($e->getMessage());
            DB::rollBack();

            return $this->Response(
                message: 'Usuários não cadastrado!',
                data: false,
                status: 500
            );
        }
    }

    public function update(UserRequest $request, User $user) : JsonResponse
    {

        DB::beginTransaction();

        try {

            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
            ]);

            DB::commit();

            return $this->Response(
                message: 'Usuários editado com sucesso!',
                data: true,
                status: 200
            );
        } catch (Exception $e){
            DB::rollBack();
            return $this->Response(
                message: 'Usuários não cadastrado!',
                data: false,
                status: 500
            );
        }
    }

    public function destroy(User $user) : JsonResponse
    {
        try{

            $user->delete();

            return $this->Response(
                message: 'Usuários apagado com sucesso!',
                data: true,
                status: 200
            );
        }catch(Exception $e){
            return $this->Response(
                message: 'Usuários não apagado!',
                data: false,
                status: 500
            );
        }
    }

}
