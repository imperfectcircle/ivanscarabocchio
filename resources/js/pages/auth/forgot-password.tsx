import Button from '@/components/button';
import FormField from '@/components/form-field';
import StatusMessage from '@/components/status-message';
import TextLink from '@/components/text-link';
import AuthLayout from '@/layouts/auth-layout';
import { login } from '@/routes';
import { email as passwordEmail } from '@/routes/password';
import { Form } from '@inertiajs/react';

type ForgotPasswordProps = {
    status: string | null;
};

export default function ForgotPassword({ status }: ForgotPasswordProps) {
    return (
        <AuthLayout title="Password dimenticata" description="Ti invieremo via email un link per impostarne una nuova.">
            <div className="mb-4">
                <StatusMessage message={status} />
            </div>

            <Form action={passwordEmail()} className="space-y-5">
                {({ errors, processing }) => (
                    <>
                        <FormField id="email" label="Email" type="email" autoComplete="email" required autoFocus error={errors.email} />
                        <Button type="submit" disabled={processing} className="w-full">
                            Invia il link
                        </Button>
                    </>
                )}
            </Form>

            <p className="mt-6 text-sm">
                <TextLink href={login()}>Torna all'accesso</TextLink>
            </p>
        </AuthLayout>
    );
}
