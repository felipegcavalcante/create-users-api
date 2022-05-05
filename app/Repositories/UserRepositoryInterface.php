<?php

namespace App\Repositories;

use App\Models\User;

interface UserRepositoryInterface
{
    public function getAllUsers();
    public function save(array $user): User;
    public function verifyIfEmailAlreadyExists(string $email): bool;
    public function verifyIfCpfAlreadyExists(string $cpf): bool;
}
