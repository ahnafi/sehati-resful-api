<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        User::query()->delete();
    }
    public function testUserRegisterSuccess()
    {
        $this->post("/api/users/register", [
            "name" => "test",
            "email" => "text@example.com",
            "password" => "Password11",
            "address" => "jalan sehati jiwa"
        ])->assertStatus(201)
            ->assertJson([
                "user" => [
                    "name" => "test",
                    "email" => "text@example.com",
                    "address" => "jalan sehati jiwa",
                ],
                "token_type" => "Bearer",
            ])
            ->assertJsonStructure([
                "user" => [
                    "id",
                    "name",
                    "email",
                    "address",
                    "created_at",
                    "updated_at"
                ],
                "token_type",
                "token"
            ]);
    }

    public function testUserRegisterFailed()
    {
        $this->post("/api/users/register", [
            "name" => "te",
            "email" => "text",
            "password" => "asswor",
        ])
            ->assertStatus(400)
            ->assertJson([
                "errors" => [
                    "name" => ["The name field must be at least 3 characters."],
                    "email" => ["The email field must be a valid email address."],
                    "password" => [
                        'The password field must be at least 8 characters.',
                        'The password field must contain at least one uppercase and one lowercase letter.',
                        'The password field must contain at least one number.',
                    ]
                ]
            ]);
    } 

    public function testUserRegisterFailedEmailHasAlreadyUsed()
    {
        $this->testUserRegisterSuccess();
        $this->post("/api/users/register", [
            "name" => "test",
            "email" => "text@example.com",
            "password" => "Password11",
            "address" => "jalan sehati jiwa"
        ])->assertStatus(400)
            ->assertJson([
                "errors" => [
                    "email" => [
                        "The email has already been taken."
                    ]
                ]
            ]);
    }
}
