<?php

namespace Tests\Feature;

use Tests\TestCase;

class FirebaseMiddlewareTest extends TestCase
{
    public function test_protected_api_requires_a_firebase_bearer_token(): void
    {
        $this->getJson('/api/v1/courses')
            ->assertUnauthorized()
            ->assertJson([
                'success' => false,
                'message' => 'Firebase ID token tidak ditemukan.',
            ]);
    }
}
