<?php

namespace App\Http\Controllers;

use App\Exceptions\DomainError;
use App\Services\CreateUserService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Arr;

class PostUser extends Controller
{
    private CreateUserService $createUserService;

    public function __construct()
    {
        $this->createUserService = new CreateUserService();
    }

    public function __invoke(Request $request)
    {

        try {
            $request->validate([
                'name' => 'required|string',
                'email' => 'required|email',
                'cpf' => 'required|cpf',
                'date_of_birth' => 'required|date',
                'has_accepted_terms' => 'required|boolean',
            ]);

            $user = [
                'name' => $request->name,
                'email' => $request->email,
                'cpf' => $request->cpf,
                'date_of_birth' => $request->date_of_birth,
                'has_accepted_terms' => $request->has_accepted_terms,
            ];
            $this->createUserService->execute($user);
        } catch(DomainError $e) {
            return response()->json([
                'success' => false,
                'type' => $e->getType(),
                'message' => $e->getMessage()
            ], 400);
        } catch (ValidationException $e) {
            return response()->json([
                "success" => false,
                "type" => "erro_de_validacao",
                "message" => "Houve um erro de validação.",
                "errors" => Arr::flatten($e->errors())
            ], 422);
        }


        return response()->json($user, 201);
    }
}
