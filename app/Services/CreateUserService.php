<?php

namespace App\Services;

use App\Repositories\UserRepository;
use App\Exceptions\EmailIsAlreadyUsed;
use App\Exceptions\CpfIsAlreadyUsed;
use App\Exceptions\CpfIsInvalid;
use App\Exceptions\AgeIsInvalid;
use App\Exceptions\TermsNotAccepted;

class CreateUserService
{
    private VerifyCpfReceitaService $verifyCpfService;
    private DateService $dateService;
    private UserRepository $userRepository;
    private MailerService $mailerService;

    public function __construct()
    {
        $this->verifyCpfService = new VerifyCpfReceitaService();
        $this->dateService = new DateService();
        $this->userRepository = new UserRepository();
        $this->mailerService = new MailerService();
    }

    public function execute(array $user)
    {
        if ($this->userRepository->verifyIfEmailAlreadyExists($user['email'])) {
            throw new EmailIsAlreadyUsed('email_ja_utilizado', 'O e-mail informado pelo usuário já existe na base de dados.');
        }

        if ($this->userRepository->verifyIfCpfAlreadyExists($user['cpf'])) {
            throw new CpfIsAlreadyUsed('cpf_ja_utilizado', 'O cpf informado pelo usuário já existe na base de dados.');
        }

        if ($this->verifyCpfService->execute($user['cpf'])) {
            throw new CpfIsInvalid('cpf_invalido_na_receita', 'O CPF informado pelo usuário está inválido na Receita Federal.');
            // return abort(400, 'CPF inválido na receita federal');
        }

        $isAgeValid = $this->dateService->verifyAgeBetween($user['date_of_birth'], 18, 90);

        if (! $isAgeValid) {
            throw new AgeIsInvalid('idade_invalida', 'Para se cadastrar deve ter entre 18 e 90 anos.');
            // return abort(400, "Idade inválida");
        }

        if (! $user['has_accepted_terms']) {
            throw new TermsNotAccepted('termos_nao_aceitos', 'Para se cadastrar você deve aceitar os termos.');
            // return abort(400, 'Para se cadastrar você deve aceitar os termos');
        }

        $user = $this->userRepository->save($user);

        $this->mailerService->confirmationMail($user);

        return $user;
    }
}
