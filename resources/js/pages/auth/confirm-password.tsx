import Button from '@/components/button';
import FormField from '@/components/form-field';
import AuthLayout from '@/layouts/auth-layout';
import { store as confirmPasswordStore } from '@/routes/password/confirm';
import { Form } from '@inertiajs/react';

export default function ConfirmPassword() {
    return (
        <AuthLayout title="Conferma la password" description="Questa è un'area protetta: inserisci di nuovo la password per continuare.">
            <Form action={confirmPasswordStore()} resetOnError className="space-y-5">
                {({ errors, processing }) => (
                    <>
                        <FormField
                            id="password"
                            label="Password"
                            type="password"
                            autoComplete="current-password"
                            required
                            autoFocus
                            error={errors.password}
                        />
                        <Button type="submit" disabled={processing} className="w-full">
                            Conferma
                        </Button>
                    </>
                )}
            </Form>
        </AuthLayout>
    );
}
