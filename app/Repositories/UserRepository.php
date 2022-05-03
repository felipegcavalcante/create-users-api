<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository implements UserRepositoryInterface
{
    public function getAllUsers()
    {
        return User::all();
    }

    public function createUser(array $user)
    {
        return User::create($user);
    }

    public function verifyIfEmailAlreadyExists(string $email)
    {
        if(User::where('email', $email)->count() == 0){
            return false;
        };
        return true;
    }

    public function verifyIfCpfAlreadyExists(string $cpf)
    {
        if (User::where('cpf', $cpf)->count() == 0){
            return false;
        };
        return true;;
    }
}
