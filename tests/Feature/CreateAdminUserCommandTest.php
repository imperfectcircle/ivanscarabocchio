<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

/**
 * @param  array{name?: string, email?: string, password?: string, password_confirmation?: string}  $answers
 */
function runCreateAdminUser(array $answers = []): mixed
{
    $answers = [
        'name' => 'Ivan Scarabocchio',
        'email' => 'ivan@example.com',
        'password' => 'PasswordSicura2026',
        'password_confirmation' => 'PasswordSicura2026',
        ...$answers,
    ];

    return test()->artisan('admin:create-user')
        ->expectsQuestion('Nome', $answers['name'])
        ->expectsQuestion('Email', $answers['email'])
        ->expectsQuestion('Password', $answers['password'])
        ->expectsQuestion('Conferma password', $answers['password_confirmation']);
}

test('the command creates a panel user with a hashed password', function () {
    runCreateAdminUser()->assertSuccessful();

    $user = User::firstWhere('email', 'ivan@example.com');

    expect($user)->not->toBeNull()
        ->and($user->name)->toBe('Ivan Scarabocchio')
        ->and($user->email_verified_at)->not->toBeNull()
        ->and($user->password)->not->toBe('PasswordSicura2026')
        ->and(Hash::check('PasswordSicura2026', $user->password))->toBeTrue();
});

test('the command rejects a weak password', function () {
    runCreateAdminUser(['password' => 'debole', 'password_confirmation' => 'debole'])->assertFailed();

    expect(User::count())->toBe(0);
});

test('the command rejects a password confirmation that does not match', function () {
    runCreateAdminUser(['password_confirmation' => 'AltraPassword2026'])->assertFailed();

    expect(User::count())->toBe(0);
});

test('the command rejects an email that is already in use', function () {
    User::factory()->create(['email' => 'ivan@example.com']);

    runCreateAdminUser()->assertFailed();

    expect(User::count())->toBe(1);
});
