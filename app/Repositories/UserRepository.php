<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository implements UserRepositoryInterface
{
    public function getAllUsers()
    {
        return User::all();
    }

    public function save(array $user): User
    {
        return User::create($user);
    }

    public function verifyIfEmailAlreadyExists(string $email): bool
    {
        if (User::where('email', $email)->count() === 0){
            return false;
        };
        return true;
    }

    public function verifyIfCpfAlreadyExists(string $cpf): bool
    {
        if (User::where('cpf', $cpf)->count() === 0){
            return false;
        };
        return true;;
    }
}
