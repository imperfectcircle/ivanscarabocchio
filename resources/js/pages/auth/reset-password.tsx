import Button from '@/components/button';
import FormField from '@/components/form-field';
import AuthLayout from '@/layouts/auth-layout';
import { update as passwordUpdate } from '@/routes/password';
import { Form } from '@inertiajs/react';

type ResetPasswordProps = {
    email: string;
    token: string;
};

export default function ResetPassword({ email, token }: ResetPasswordProps) {
    return (
        <AuthLayout title="Nuova password" description="Scegli una nuova password per il tuo account.">
            <Form
                action={passwordUpdate()}
                transform={(data) => ({ ...data, token, email })}
                resetOnSuccess={['password', 'password_confirmation']}
                className="space-y-5"
            >
                {({ errors, processing }) => (
                    <>
                        <FormField id="email" label="Email" type="email" value={email} readOnly error={errors.email} />
                        <FormField
                            id="password"
                            label="Nuova password"
                            type="password"
                            autoComplete="new-password"
                            hint="Almeno 12 caratteri, con maiuscole, minuscole e numeri."
                            required
                            autoFocus
                            error={errors.password}
                        />
                        <FormField
                            id="password_confirmation"
                            label="Conferma la nuova password"
                            type="password"
                            autoComplete="new-password"
                            required
                            error={errors.password_confirmation}
                        />
                        <Button type="submit" disabled={processing} className="w-full">
                            Salva la nuova password
                        </Button>
                    </>
                )}
            </Form>
        </AuthLayout>
    );
}
