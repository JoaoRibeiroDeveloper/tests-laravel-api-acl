<?php

use App\Models\User;

use function Pest\Laravel\postJson;

test('should auth the User', function () {
    $user = User::factory()->create();
    $data = [
        'email' => $user->email,
        'password' => 'password',
        'device_name' => 'e2e_test',
    ];
    postJson(route('auth.login'), $data)
        ->assertOk()
        ->assertJsonStructure(['token']);
});

test('should fail auth - with wrong password', function () {
    $user = User::factory()->create();
    $data = [
        'email' => $user->email,
        'password' => 'wrong-password',
        'device_name' => 'e2e_test',
    ];
    postJson(route('auth.login'), $data)->assertStatus(422);
});

test('should fail auth - with wrong email', function () {
    $data = [
        'email' => 'fake@email.com',
        'password' => 'password',
        'device_name' => 'e2e_test',
    ];
    postJson(route('auth.login'), $data)->assertStatus(422);
});

describe('Validations', function () {
    it('should require email', function () {
        $data = [
            'password' => 'password',
            'device_name' => 'e2e_test',
        ];
        postJson(route('auth.login'), $data)
        ->assertJsonValidationErrors([
            'email' => trans('validation.required', ['attribute' => 'email'])
        ])
        ->assertStatus(422);
    });
    it('should require password', function () {
        $data = [
            'email' => 'fake@email.com',
            'device_name' => 'e2e_test',
        ];
        postJson(route('auth.login'), $data)
            ->assertJsonValidationErrors([
                'password' => trans('validation.required', ['attribute' => 'password'])
            ])
            ->assertStatus(422);
    });
    it('should require device name', function () {
        $data = [
            'email' => 'fake@email.com',
            'password' => 'password',
        ];
        postJson(route('auth.login'), $data)
            ->assertJsonValidationErrors([
                'device_name' => trans('validation.required', ['attribute' => 'device name'])
            ])
            ->assertStatus(422);
    });
});
