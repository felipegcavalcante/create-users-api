<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostsUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_user_with_invalid_data_should_not_work(): void
    {
        $user = array_merge($this->validPayload(), [
            'email' => "invalid_email",
            'has_accepted_terms' => "true",
        ]);

        $response = $this->postJson('api/users', $user);

        $expected = [
            "message" => "The email must be a valid email address. (and 1 more error)",
            "errors" => [
                "email" => [
                    0 => "The email must be a valid email address."
                ],
                "has_accepted_terms" => [
                    0 => "The has accepted terms field must be true or false."
                ]
            ]
        ];
        $response->assertStatus(422);
        $response->assertExactJson($expected);
    }

    public function test_create_user_when_email_already_exists_shoud_not_work(): void
    {
        $this->seed();

        $user = array_merge($this->validPayload(), [
            'email' => "felipesantistacavalcante@gmail.com",
        ]);

        $response = $this->postJson('api/users', $user);

        $expected = [
            "success" => false,
            "type" => "email_ja_utilizado",
            "message" => "O e-mail informado pelo usuário já existe na base de dados."
        ];

        $response->assertStatus(400);
        $response->assertJsonFragment($expected);
    }

    public function test_create_user_when_cpf_already_exists_shoud_not_work(): void
    {
        $this->seed();

        $user = array_merge($this->validPayload(), [
            'cpf' => "484.607.328-94",
        ]);

        $response = $this->postJson('api/users', $user);

        $expected = [
            "success" => false,
            "type" => "cpf_ja_utilizado",
            "message" => "O cpf informado pelo usuário já existe na base de dados."
        ];

        $response->assertStatus(400);
        $response->assertJsonFragment($expected);
    }

    public function test_create_user_when_user_does_not_accepted_terms_should_not_work(): void
    {
        $user = array_merge($this->validPayload(), [
            'has_accepted_terms' => false,
        ]);

        $response = $this->postJson('api/users', $user);

        $expected = [
            "success" => false,
            "type" => "termos_nao_aceitos",
            "message" => "Para se cadastrar você deve aceitar os termos."
        ];

        $response->assertStatus(400);
        $response->assertJsonFragment($expected);
    }

    public function test_create_user_with_invalid_date_of_birth_should_not_work(): void
    {
        $user = array_merge($this->validPayload(), [
            'date_of_birth' => "2005-09-06",
        ]);

        $response = $this->postJson('api/users', $user);

        $expected = [
            "success" => false,
            "type" => "idade_invalida",
            "message" => "Para se cadastrar deve ter entre 18 e 90 anos."
        ];

        $response->assertStatus(400);
        $response->assertJson($expected);
    }

    public function test_create_user_with_valid_data_should_work(): void
    {
        $payload = $this->validPayload();

        $response = $this->postJson('api/users', $payload);

        $response->assertStatus(201);
        $response->assertJsonFragment($payload);
    }

    private function validPayload(): array
    {
        return [
            'name' => "Jonh Doe",
            'email' => "jonh.doe@example.com",
            'cpf' => "126.237.918-05",
            'date_of_birth' => "2003-09-06",
            'has_accepted_terms' => true,
        ];
    }
}
