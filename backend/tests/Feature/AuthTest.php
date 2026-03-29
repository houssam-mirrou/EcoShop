<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);


it('permet à un utilisateur de s\'inscrire', function () {
    $response = $this->postJson('/api/register', [
        'name' => 'Utilisateur Test',
        'email' => 'test@ecoshop.com',
        'password' => 'password123',
    ]);

    $response->assertStatus(201)
             ->assertJsonStructure(['access_token', 'token_type', 'user']);

    $this->assertDatabaseHas('users', [
        'email' => 'test@ecoshop.com',
    ]);
});

it('permet à un utilisateur de se connecter', function () {
    $user = User::factory()->create([
        'password' => bcrypt('password123'),
    ]);

    $response = $this->postJson('/api/login', [
        'email' => $user->email,
        'password' => 'password123',
    ]);

    $response->assertStatus(200)
             ->assertJsonStructure(['access_token', 'token_type', 'user']);
});

it('rejette l\'accès au profil si non authentifié', function () {
    $response = $this->getJson('/api/profile');

    $response->assertStatus(401);
});

it('permet à un utilisateur authentifié de voir son profil', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user, 'sanctum')->getJson('/api/profile');

    $response->assertStatus(200)
             ->assertJsonFragment(['email' => $user->email]);
});

it('permet à un utilisateur de se déconnecter', function () {
    $user = User::factory()->create();
    $token = $user->createToken('auth_token')->plainTextToken;

    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $token,
    ])->postJson('/api/logout');

    $response->assertStatus(200)
             ->assertJson(['message' => 'Déconnexion réussie.']);

    $this->assertDatabaseCount('personal_access_tokens', 0);
});
