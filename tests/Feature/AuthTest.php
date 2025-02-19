<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
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
                "data" => [
                    "user" => [
                        "name" => "test",
                        "email" => "text@example.com",
                        "address" => "jalan sehati jiwa",
                    ],
                    "token_type" => "Bearer",
                ]
            ])
            ->assertJsonStructure([
                "data" => [
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
                ],
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

    public function testUserLoginSuccess()
    {
        $this->testUserRegisterSuccess();

        $this->post("/api/users/login", [
            "email" => "text@example.com",
            "password" => "Password11",
        ])->assertStatus(200)
            ->assertJsonStructure([
                "data" => [
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
                ],
            ]);
    }

    public function testUserLoginFailed()
    {
        $this->post("/api/users/login", [
            "email" => "text@example.com",
            "password" => "Password11",
        ])->assertStatus(401)
            ->assertJson([
                "errors" => [
                    "message" => ["Unauthorized"]
                ]
            ]);
    }

    public function testUserLoginFailedEmailAndPasswordRequired()
    {
        $this->post("/api/users/login", [
            // "email" => "text@example.com",
            // "password" => "Password11",
        ])->assertStatus(400)
            ->assertJson([
                "errors" => [
                    "email" => [
                        "The email field is required."
                    ],
                    "password" => [
                        "The password field is required."
                    ]
                ]
            ]);
    }

    public function testUserLogout()
    {
        $user = User::factory()->create();
        $token = $user->createToken("auth_token")->plainTextToken;

        $this->post("/api/users/logout", [], ["Authorization" => "Bearer $token"])
            ->assertStatus(200)
            ->assertJson([
                "data" => true
            ]);
    }

    public function testUserLogoutUnauthorized()
    {
        $this->post("/api/users/logout", [], ["Accept" => "application/json"])
            ->assertStatus(401)
            ->assertJson([
                "errors" => [
                    "message" => ["Unauthorized"]
                ]
            ]);
    }

    public function testUserForgotPasswordSuccess()
    {
        $user = User::factory()->create();

        $this->post("/api/forgot-password", ["email" => $user->email], ["Accept" => "application/json"])
            ->assertStatus(200)
            ->assertJson(["data" => [
                "message" => ["We have emailed your password reset link."]
            ]]);
    }

    public function testUserForgotPasswordEmailNotFound()
    {
        $user = User::factory()->create();

        $this->post("/api/forgot-password", ["email" => "gajelas@gmail.com"], ["Accept" => "application/json"])
            ->assertStatus(422)
            ->assertJson(["errors" => [
                "email" => ["We can't find a user with that email address."]
            ]]);
    }

    public function testUserForgotPasswordEmailFieldIsRequired()
    {
        $user = User::factory()->create();

        $this->post("/api/forgot-password", [], ["Accept" => "application/json"])
            ->assertStatus(400)
            ->assertJson(["errors" => [
                "email" => ["The email field is required."]
            ]]);
    }

    public function testUserResetPasswordSuccess()
    {
        $user = User::factory()->create([
            "name" => "test",
            "email" => "test@example.com"
        ]);

        $token = Password::createToken($user);
        Log::info($token);

        $this->post("/api/reset-password", [
            "token" => $token,
            "email" => "test@example.com",
            "password" => "cobalagi123@TT",
            "password_confirmation" => "cobalagi123@TT"
        ])->assertStatus(200)
            ->assertJson([
                "data" => [
                    "message" => ["Your password has been reset."]
                ]
            ]);
    }

    public function testUserResetPasswordFieldRequired()
    {
        $user = User::factory()->create([
            "name" => "test",
            "email" => "test@example.com"
        ]);

        $token = Password::createToken($user);
        Log::info($token);

        $this->post("/api/reset-password", [
//            "token" => $token,
//            "email" => "test@example.com",
//            "password" => "cobalagi123@TT",
//            "password_confirmation" => "cobalagi123@TT"
        ])->assertStatus(400)
            ->assertJson([
                "errors" => [
                    "token" => [
                        "The token field is required."
                    ],
                    "email" => [
                        "The email field is required."
                    ],
                    "password" => [
                        "The password field is required."
                    ]
                ]
            ]);
    }

    public function testUserResetPasswordTokenNotValid()
    {
        $user = User::factory()->create([
            "name" => "test",
            "email" => "test@example.com"
        ]);

        $token = Password::createToken($user);
        Log::info($token);

        $this->post("/api/reset-password", [
            "token" => "token",
            "email" => "test@example.com",
            "password" => "cobalagi123@TT",
            "password_confirmation" => "cobalagi123@TT"
        ])->assertStatus(422)
            ->assertJson([
                "errors" => [
                    "message" => [
                        "This password reset token is invalid."
                    ],
                ]
            ]);
    }

    public function testUserResetPasswordNotConfirm()
    {
        $user = User::factory()->create([
            "name" => "test",
            "email" => "test@example.com"
        ]);

        $token = Password::createToken($user);
        Log::info($token);

        $this->post("/api/reset-password", [
            "token" => "token",
            "email" => "test@example.com",
            "password" => "cobalagi123@TT",
//            "password_confirmation" => "cobalagi123@TT"
        ])->assertStatus(400)
            ->assertJson([
                "errors" => [
                    "password" => [
                        "The password field confirmation does not match."
                    ]
                ]
            ]);
    }

    public function testUserResetPasswordUserEmailNotFound()
    {
        $user = User::factory()->create([
            "name" => "test",
            "email" => "test@example.com"
        ]);

        $token = Password::createToken($user);
        Log::info($token);

        $this->post("/api/reset-password", [
            "token" => "token",
            "email" => "test1111@example.com",
            "password" => "cobalagi123@TT",
            "password_confirmation" => "cobalagi123@TT"
        ])->assertStatus(422)
            ->assertJson([
                "errors" => [
                    "message" => [
                        "We can't find a user with that email address."
                    ]
                ]
            ]);
    }

}
