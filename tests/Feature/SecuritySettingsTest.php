<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Inertia\Testing\AssertableInertia as Assert;

test('the security page requires a recent password confirmation', function () {
    $this->actingAs(User::factory()->create())
        ->get(route('admin.security'))
        ->assertRedirect(route('password.confirm'));
});

test('the password confirmation page is rendered by Inertia', function () {
    $this->actingAs(User::factory()->create())
        ->get(route('password.confirm'))
        ->assertInertia(fn (Assert $page) => $page->component('auth/confirm-password'));
});

test('the security page shows the two factor state without leaking recovery codes', function () {
    $user = User::factory()->withTwoFactor()->create();

    $this->actingAs($user)
        ->withSession(['auth.password_confirmed_at' => time()])
        ->get(route('admin.security'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('settings/security')
            ->where('twoFactor.enabled', true)
            ->where('twoFactor.confirmed', true)
            ->where('twoFactor.setup', null)
            ->missing('recoveryCodes')
            ->has('passkeys', 0));
});

test('recovery codes are only sent when explicitly requested', function () {
    $user = User::factory()->withTwoFactor()->create();

    $this->actingAs($user)
        ->withSession(['auth.password_confirmed_at' => time()])
        ->get(route('admin.security'))
        ->assertInertia(fn (Assert $page) => $page
            ->missing('recoveryCodes')
            ->reloadOnly('recoveryCodes', fn (Assert $reload) => $reload
                ->where('recoveryCodes', ['codice-uno-1234', 'codice-due-5678'])));
});

test('a pending two factor setup exposes the QR code and setup key', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->withSession(['auth.password_confirmed_at' => time()])
        ->post(route('two-factor.enable'))
        ->assertSessionHas('status', 'two-factor-authentication-enabled');

    $this->get(route('admin.security'))
        ->assertInertia(fn (Assert $page) => $page
            ->where('twoFactor.enabled', true)
            ->where('twoFactor.confirmed', false)
            ->where('twoFactor.setup.setupKey', decrypt($user->fresh()->two_factor_secret))
            ->where('twoFactor.setup.qrCode', fn (string $qrCode) => str_starts_with($qrCode, 'data:image/svg+xml;base64,')));
});

test('a user can change the password', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->from(route('admin.security'))
        ->put(route('user-password.update'), [
            'current_password' => 'password',
            'password' => 'NuovaPassword2026',
            'password_confirmation' => 'NuovaPassword2026',
        ])
        ->assertSessionHasNoErrors();

    expect(Hash::check('NuovaPassword2026', $user->fresh()->password))->toBeTrue();
});

test('a weak new password is rejected', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->from(route('admin.security'))
        ->put(route('user-password.update'), [
            'current_password' => 'password',
            'password' => 'debole',
            'password_confirmation' => 'debole',
        ])
        ->assertSessionHasErrorsIn('updatePassword', 'password');

    expect(Hash::check('password', $user->fresh()->password))->toBeTrue();
});
