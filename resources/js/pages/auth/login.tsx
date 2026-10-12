import Button from '@/components/button';
import FormField from '@/components/form-field';
import StatusMessage from '@/components/status-message';
import TextLink from '@/components/text-link';
import AuthLayout from '@/layouts/auth-layout';
import { dashboard } from '@/routes/admin';
import { store as loginStore } from '@/routes/login';
import { request as passwordRequest } from '@/routes/password';
import { login as passkeyLogin, loginOptions as passkeyLoginOptions } from '@/routes/passkey';
import { Form, router } from '@inertiajs/react';
import { usePasskeyVerify } from '@laravel/passkeys/react';

type LoginProps = {
    status: string | null;
};

export default function Login({ status }: LoginProps) {
    const passkey = usePasskeyVerify({
        routes: { options: passkeyLoginOptions.url(), submit: passkeyLogin.url() },
        onSuccess: (response) => router.visit(response.redirect ?? dashboard.url()),
    });

    return (
        <AuthLayout title="Accedi" description="Entra nel pannello per gestire i contenuti del sito.">
            <div className="mb-4">
                <StatusMessage message={status} />
            </div>

            <Form action={loginStore()} resetOnError={['password']} className="space-y-5">
                {({ errors, processing }) => (
                    <>
                        <FormField
                            id="email"
                            label="Email"
                            type="email"
                            autoComplete="email webauthn"
                            required
                            autoFocus
                            error={errors.email}
                        />
                        <FormField
                            id="password"
                            label="Password"
                            type="password"
                            autoComplete="current-password"
                            required
                            error={errors.password}
                        />

                        <div className="flex flex-wrap items-center justify-between gap-3">
                            <label className="inline-flex min-h-11 items-center gap-2 text-sm">
                                <input type="checkbox" name="remember" value="1" className="size-4" />
                                Ricordami su questo dispositivo
                            </label>
                            <TextLink href={passwordRequest()} className="text-sm">
                                Password dimenticata?
                            </TextLink>
                        </div>

                        <Button type="submit" disabled={processing} className="w-full">
                            {processing ? 'Accesso in corso…' : 'Accedi'}
                        </Button>
                    </>
                )}
            </Form>

            {passkey.isSupported && (
                <div className="mt-6 border-t border-slate-200 pt-6">
                    <Button variant="secondary" className="w-full" disabled={passkey.isLoading} onClick={() => passkey.verify()}>
                        {passkey.isLoading ? 'Attendo la passkey…' : 'Accedi con una passkey'}
                    </Button>
                    <div className="mt-3">
                        <StatusMessage message={passkey.error} tone="error" />
                    </div>
                </div>
            )}
        </AuthLayout>
    );
}
