<?php

use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use function Pest\Laravel\{postJson};

beforeEach(function () {
    $this->endpoint = '/api/v1/register';
});

it('can register', function () {
    $attributes = [
        'name' => 'John Doe',
        'email' => 'johndoe@email.com',
        'password' => 'azerty',
        'password_confirmation' => 'azerty',
    ];

    $response = postJson($this->endpoint, $attributes);

    $response->assertCreated()
        ->assertJson(
            fn (AssertableJson $json) =>
            $json->hasAll(['message', 'data.token'])
        );
});

it('can validate name is required', function () {
    $attributes = [
        'name' => '',
        'email' => 'johndoe@email.com',
        'password' => 'azerty',
        'confirm_password' => 'azerty',
    ];

    $response = postJson($this->endpoint, $attributes);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors('name');
});

it('can validate email is required', function () {
    $attributes = [
        'name' => 'John Doe',
        'email' => '',
        'password' => 'azerty',
        'confirm_password' => 'azerty',
    ];

    $response = postJson($this->endpoint, $attributes);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors('email');
});

it('can validate email is valid email', function () {
    $attributes = [
        'name' => 'John Doe',
        'email' => 'johndoeemail.com',
        'password' => 'azerty',
        'confirm_password' => 'azerty',
    ];

    $response = postJson($this->endpoint, $attributes);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors('email');
});

it('can validate email is duplicated', function () {
    User::factory()->create([
        'email' => 'johndoe@email.com',
    ]);
    $attributes = [
        'name' => 'John Doe',
        'email' => 'johndoe@email.com',
        'password' => 'azerty',
        'confirm_password' => 'azerty',
    ];

    $response = postJson($this->endpoint, $attributes);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors('email');
});

it('can validate password is required', function () {
    $attributes = [
        'name' => 'John Doe',
        'email' => 'johndoe@email.com',
        'password' => '',
        'confirm_password' => 'azerty',
    ];

    $response = postJson($this->endpoint, $attributes);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors('password');
});

it('can validate password and confirm_password is matched', function () {
    $attributes = [
        'name' => 'John Doe',
        'email' => 'johndoe@email.com',
        'password' => 'ytreza',
        'password_confirmation' => 'azerty',
    ];

    $response = postJson($this->endpoint, $attributes);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors('password');
});
