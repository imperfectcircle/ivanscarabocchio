<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Laravel\Passkeys\Passkey;

class SecuritySettingsController extends Controller
{
    /**
     * Show the password, two-factor authentication and passkey settings of the current user.
     */
    public function show(Request $request): Response
    {
        /** @var User $user */
        $user = $request->user();

        $isTwoFactorEnabled = $user->two_factor_secret !== null;
        $isTwoFactorConfirmed = $user->two_factor_confirmed_at !== null;

        return Inertia::render('settings/security', [
            'twoFactor' => [
                'enabled' => $isTwoFactorEnabled,
                'confirmed' => $isTwoFactorConfirmed,
                'setup' => $isTwoFactorEnabled && ! $isTwoFactorConfirmed ? [
                    'qrCode' => 'data:image/svg+xml;base64,'.base64_encode($user->twoFactorQrCodeSvg()),
                    'setupKey' => decrypt($user->two_factor_secret),
                ] : null,
            ],
            'recoveryCodes' => Inertia::optional(
                fn (): array => $isTwoFactorConfirmed ? $user->recoveryCodes() : []
            ),
            'passkeys' => $user->passkeys()
                ->latest()
                ->get()
                ->map(fn (Passkey $passkey): array => [
                    'id' => $passkey->id,
                    'name' => $passkey->name,
                    'createdAt' => $passkey->created_at?->toIso8601String(),
                    'lastUsedAt' => $passkey->last_used_at?->toIso8601String(),
                ]),
        ]);
    }
}
