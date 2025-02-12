<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    public function testUserRegister()
    {
        $this->post("/api/users/register", [
            "name" => "test",
            "email" => "text@example.com",
            "password" => "Password11",
            "address" => "jalan sehati jiwa"
        ])->assertStatus(201);
    }
}
