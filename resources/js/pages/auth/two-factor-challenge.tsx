import Button from '@/components/button';
import FormField from '@/components/form-field';
import AuthLayout from '@/layouts/auth-layout';
import { store as twoFactorLoginStore } from '@/routes/two-factor/login';
import { Form } from '@inertiajs/react';
import { useState } from 'react';

export default function TwoFactorChallenge() {
    const [isUsingRecoveryCode, setIsUsingRecoveryCode] = useState(false);

    return (
        <AuthLayout
            title="Verifica in due passaggi"
            description={
                isUsingRecoveryCode
                    ? 'Inserisci uno dei codici di recupero che hai salvato.'
                    : "Inserisci il codice a 6 cifre mostrato dall'app di autenticazione."
            }
        >
            <Form action={twoFactorLoginStore()} resetOnError className="space-y-5">
                {({ errors, processing }) => (
                    <>
                        {isUsingRecoveryCode ? (
                            <FormField
                                key="recovery_code"
                                id="recovery_code"
                                label="Codice di recupero"
                                autoComplete="one-time-code"
                                required
                                autoFocus
                                error={errors.recovery_code}
                            />
                        ) : (
                            <FormField
                                key="code"
                                id="code"
                                label="Codice"
                                inputMode="numeric"
                                pattern="[0-9]*"
                                maxLength={6}
                                autoComplete="one-time-code"
                                required
                                autoFocus
                                error={errors.code}
                            />
                        )}

                        <Button type="submit" disabled={processing} className="w-full">
                            Verifica
                        </Button>
                    </>
                )}
            </Form>

            <button
                type="button"
                onClick={() => setIsUsingRecoveryCode((current) => !current)}
                className="mt-4 min-h-11 text-sm font-medium text-biro underline underline-offset-4"
            >
                {isUsingRecoveryCode ? "Usa il codice dell'app" : 'Usa un codice di recupero'}
            </button>
        </AuthLayout>
    );
}
