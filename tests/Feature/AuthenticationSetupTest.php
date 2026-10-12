<?php

use Illuminate\Support\Facades\Route;

test('public registration is not available', function () {
    expect(Route::has('register'))->toBeFalse();

    $this->get('/register')->assertNotFound();
    $this->post('/register', [
        'name' => 'Intruso',
        'email' => 'intruso@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ])->assertNotFound();
});

test('two factor authentication and passkeys routes are registered', function () {
    expect(Route::has('two-factor.enable'))->toBeTrue()
        ->and(Route::has('passkey.login'))->toBeTrue();
});
