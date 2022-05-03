<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Carbon\Carbon;
use App\Mail\NovaTarefaMail;
use App\Mail\MailNotify;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewUserMail;

class UserService
{
    public function __construct(array $user)
    {
        return $this->validate($user);
    }

    public function validate(array $user)
    {
        $userRepository = new UserRepository();

        if ($userRepository->verifyIfEmailAlreadyExists($user['email'])) {
            return abort(400, 'Email já existente');
        }

        if ($userRepository->verifyIfCpfAlreadyExists($user['cpf'])) {
            return abort(400, 'CPF já existente');
        }

        if(! $this->verifyCpfReceita($user['cpf'])){
            return abort(400, 'CPF inválido na receita federal');
        }

        if (!$user['termos_aceitos']) {
            return abort(400, 'Para se cadastrar você deve aceitar os termos');
        }

        $this->verifyIdade($user['data_nascimento']);

        $this->sendEmail($user);



    }

    public function verifyCpfReceita(string $cpf)
    {
        return (bool) mt_rand(0, 1);
    }

    public function verifyIdade($data_nascimento)
    {
        $today = Carbon::today();
        $idade = Carbon::createFromFormat('Y-m-d', $data_nascimento);
        if (($today->floatDiffInYears($idade)) < 17.995) {
            return abort(400, 'Para se cadastrar você deve ser maior de 18 anos');
        }
        if (($today->floatDiffInYears($idade)) > 89.9974) {
            return abort(400, 'Para se cadastrar você deve ser menor de 90 anos');
        }
    }

    public function sendEmail($user)
    {
        Mail::to($user['email'])->send(new NewUserMail($user['name']));
    }
}
