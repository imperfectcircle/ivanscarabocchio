<?php

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Notification;
use Inertia\Testing\AssertableInertia as Assert;
use PragmaRX\Google2FA\Google2FA;

test('the login page is served by Inertia under /admin/login', function () {
    expect(route('login', absolute: false))->toBe('/admin/login');

    $this->get(route('login'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page->component('auth/login'));
});

test('guests are redirected from the panel to the login page', function () {
    $this->get(route('admin.dashboard'))->assertRedirect(route('login'));
    $this->get(route('admin.security'))->assertRedirect(route('login'));
});

test('a user can log in with valid credentials', function () {
    $user = User::factory()->create();

    $this->post(route('login.store'), [
        'email' => $user->email,
        'password' => 'password',
    ])->assertRedirect('/admin');

    $this->assertAuthenticatedAs($user);
});

test('a user cannot log in with a wrong password', function () {
    $user = User::factory()->create();

    $this->from(route('login'))
        ->post(route('login.store'), [
            'email' => $user->email,
            'password' => 'password-sbagliata',
        ])
        ->assertRedirect(route('login'))
        ->assertSessionHasErrors('email');

    $this->assertGuest();
});

test('a user with two factor authentication must provide a valid code', function () {
    $user = User::factory()->withTwoFactor()->create();

    $this->post(route('login.store'), [
        'email' => $user->email,
        'password' => 'password',
    ])->assertRedirect(route('two-factor.login'));

    $this->assertGuest();

    $this->get(route('two-factor.login'))
        ->assertInertia(fn (Assert $page) => $page->component('auth/two-factor-challenge'));

    $this->post(route('two-factor.login.store'), ['code' => '000000'])
        ->assertSessionHasErrors('code');

    $this->assertGuest();

    $validCode = app(Google2FA::class)->getCurrentOtp(decrypt($user->two_factor_secret));

    $this->post(route('two-factor.login.store'), ['code' => $validCode])
        ->assertRedirect('/admin');

    $this->assertAuthenticatedAs($user);
});

test('authenticated users are sent to the panel instead of the login page', function () {
    $this->actingAs(User::factory()->create())
        ->get(route('login'))
        ->assertRedirect('/admin');
});

test('the dashboard is shown to authenticated users', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('admin.dashboard'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('dashboard')
            ->where('auth.user.email', $user->email)
            ->missing('auth.user.password')
            ->missing('auth.user.two_factor_secret'));
});

test('logging out returns to the login page', function () {
    $this->actingAs(User::factory()->create())
        ->post(route('logout'))
        ->assertRedirect('/admin/login');

    $this->assertGuest();
});

test('a password reset link can be requested and points to the panel', function () {
    Notification::fake();
    $user = User::factory()->create();

    $this->get(route('password.request'))
        ->assertInertia(fn (Assert $page) => $page->component('auth/forgot-password'));

    $this->post(route('password.email'), ['email' => $user->email])->assertSessionHasNoErrors();

    Notification::assertSentTo($user, ResetPassword::class, function (ResetPassword $notification) use ($user) {
        $resetUrl = $notification->toMail($user)->actionUrl;

        return str_contains($resetUrl, '/admin/reset-password/');
    });
});

test('the reset password page receives the token and email', function () {
    $this->get(route('password.reset', ['token' => 'token-di-prova', 'email' => 'ivan@example.com']))
        ->assertInertia(fn (Assert $page) => $page
            ->component('auth/reset-password')
            ->where('token', 'token-di-prova')
            ->where('email', 'ivan@example.com'));
});

test('public pages do not link to the panel login', function () {
    $this->get(route('home'))
        ->assertOk()
        ->assertDontSee('/admin', false);
});
