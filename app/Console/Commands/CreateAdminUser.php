<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

use function Laravel\Prompts\password;
use function Laravel\Prompts\text;

#[Signature('admin:create-user')]
#[Description('Crea un account per il pannello di amministrazione (la password viene chiesta in modo nascosto)')]
class CreateAdminUser extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $input = [
            'name' => text(label: 'Nome', required: true),
            'email' => text(label: 'Email', required: true),
            'password' => password(label: 'Password', required: true),
            'password_confirmation' => password(label: 'Conferma password', required: true),
        ];

        $validator = Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)],
            'password' => ['required', 'string', Password::default(), 'confirmed'],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->components->error($error);
            }

            return self::FAILURE;
        }

        $user = User::create($validator->safe()->only(['name', 'email', 'password']));
        $user->forceFill(['email_verified_at' => now()])->save();

        $this->components->info("Account creato per {$user->email}. Accesso: ".route('login'));

        return self::SUCCESS;
    }
}
