<?php

use App\Models\User;
use function Pest\Laravel\be;
use function Pest\Laravel\postJson;

describe('It test auth works', function () {

    it('return validation error if no email given', function () {
        $response = postJson(route('auth.login'), []);
        $response->assertUnprocessable();
        $response->assertJsonValidationErrorFor('email');
    });

    it('return validation error if no password given', function () {
        $response = postJson(route('auth.login'), []);
        $response->assertUnprocessable();
        $response->assertJsonValidationErrorFor('password');
    });

    it('return forbidden if already authenticated', function () {

        $user = User::factory()->create();

        be($user);
        $response = postJson(route('auth.login'), []);
        $response->assertForbidden();
    });

    it('return validation error if email doesnt exist', function () {

        $user = User::factory()->make();
        $response = postJson(route('auth.login'), [
            'email' => $user->email,
        ]);
        $response->assertUnprocessable();
        $response->assertJsonValidationErrorFor('email');
    });

    it('dont return validation error if email exist', function () {

        $user = User::factory()->create();
        $response = postJson(route('auth.login'), [
            'email' => $user->email,
            'password' => 'password',
        ]);
        $response->assertSuccessful();
    });

    it('return 401 if password is invalid', function () {

        $user = User::factory()->create();
        $response = postJson(route('auth.login'), [
            'email' => $user->email,
            'password' => 'fake',
        ]);
        $response->assertUnauthorized();

    });

    it('return 200 plus token if creds are good', function () {

        $user = User::factory()->create();
        $response = postJson(route('auth.login'), [
            'email' => $user->email,
            'password' => 'password',
        ]);
        $response->assertSuccessful();
        $response->assertJsonStructure([
            'token',
        ]);

    });

});
