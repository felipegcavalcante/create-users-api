<?php

namespace App\Repositories;

interface UserRepositoryInterface
{
    public function getAllUsers();
    public function createUser(array $user);
    public function verifyIfEmailAlreadyExists(string $email);
    public function verifyIfCpfAlreadyExists(string $cpf);
}
