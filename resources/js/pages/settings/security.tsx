import Button from '@/components/button';
import FormField from '@/components/form-field';
import StatusMessage from '@/components/status-message';
import AdminLayout from '@/layouts/admin-layout';
import { destroy as passkeyDestroy, registrationOptions as passkeyRegistrationOptions, store as passkeyStore } from '@/routes/passkey';
import { confirm as twoFactorConfirm, disable as twoFactorDisable, enable as twoFactorEnable, regenerateRecoveryCodes } from '@/routes/two-factor';
import { update as userPasswordUpdate } from '@/routes/user-password';
import { Form, router } from '@inertiajs/react';
import { usePasskeyRegister } from '@laravel/passkeys/react';
import { useState, type FormEvent, type ReactNode } from 'react';

type TwoFactorState = {
    enabled: boolean;
    confirmed: boolean;
    setup: {
        qrCode: string;
        setupKey: string;
    } | null;
};

type PasskeySummary = {
    id: number;
    name: string;
    createdAt: string | null;
    lastUsedAt: string | null;
};

type SecurityProps = {
    twoFactor: TwoFactorState;
    recoveryCodes?: string[];
    passkeys: PasskeySummary[];
};

const dateFormatter = new Intl.DateTimeFormat('it-IT', { dateStyle: 'medium' });

function formatDate(isoDate: string | null): string {
    return isoDate ? dateFormatter.format(new Date(isoDate)) : 'mai';
}

function SettingsCard({ id, title, description, children }: { id: string; title: string; description: string; children: ReactNode }) {
    return (
        <section aria-labelledby={id} className="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <h2 id={id} className="text-lg font-semibold">
                {title}
            </h2>
            <p className="mt-1 text-sm text-slate-600">{description}</p>
            <div className="mt-6">{children}</div>
        </section>
    );
}

function PasswordSection() {
    return (
        <SettingsCard id="password" title="Password" description="Usa una password lunga e diversa da quelle degli altri siti.">
            <Form
                action={userPasswordUpdate()}
                errorBag="updatePassword"
                resetOnSuccess
                resetOnError
                options={{ preserveScroll: true }}
                className="max-w-md space-y-5"
            >
                {({ errors, processing }) => (
                    <>
                        <FormField
                            id="current_password"
                            label="Password attuale"
                            type="password"
                            autoComplete="current-password"
                            required
                            error={errors.current_password}
                        />
                        <FormField
                            id="password"
                            label="Nuova password"
                            type="password"
                            autoComplete="new-password"
                            hint="Almeno 12 caratteri, con maiuscole, minuscole e numeri."
                            required
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
                        <Button type="submit" disabled={processing}>
                            Aggiorna la password
                        </Button>
                    </>
                )}
            </Form>
        </SettingsCard>
    );
}

function TwoFactorSection({ twoFactor, recoveryCodes }: { twoFactor: TwoFactorState; recoveryCodes?: string[] }) {
    const [isConfirmingDisable, setIsConfirmingDisable] = useState(false);
    const visitOptions = { preserveScroll: true };

    const showRecoveryCodes = () => router.reload({ only: ['recoveryCodes'] });

    const regenerateCodes = () =>
        router.post(regenerateRecoveryCodes.url(), {}, { ...visitOptions, onSuccess: () => showRecoveryCodes() });

    const disableTwoFactor = () =>
        router.delete(twoFactorDisable.url(), { ...visitOptions, onFinish: () => setIsConfirmingDisable(false) });

    return (
        <SettingsCard
            id="due-passaggi"
            title="Verifica in due passaggi"
            description="Oltre alla password, all'accesso viene chiesto un codice generato da un'app (es. Google Authenticator, 1Password)."
        >
            {!twoFactor.enabled && (
                <Button onClick={() => router.post(twoFactorEnable.url(), {}, visitOptions)}>Attiva la verifica in due passaggi</Button>
            )}

            {twoFactor.setup && (
                <div className="space-y-5">
                    <p className="text-sm">Inquadra il QR code con l'app di autenticazione, poi inserisci il codice che ti mostra.</p>
                    <img
                        src={twoFactor.setup.qrCode}
                        alt="QR code per configurare l'app di autenticazione"
                        className="size-48 rounded-md border border-slate-200 bg-white p-2"
                    />
                    <p className="text-sm">
                        Non riesci a inquadrarlo? Inserisci questa chiave a mano:{' '}
                        <code className="rounded bg-slate-100 px-2 py-1 font-mono break-all">{twoFactor.setup.setupKey}</code>
                    </p>

                    <Form action={twoFactorConfirm()} errorBag="confirmTwoFactorAuthentication" options={visitOptions} className="max-w-xs space-y-4">
                        {({ errors, processing }) => (
                            <>
                                <FormField
                                    id="code"
                                    label="Codice di verifica"
                                    inputMode="numeric"
                                    pattern="[0-9]*"
                                    maxLength={6}
                                    autoComplete="one-time-code"
                                    required
                                    error={errors.code}
                                />
                                <div className="flex flex-wrap gap-3">
                                    <Button type="submit" disabled={processing}>
                                        Conferma
                                    </Button>
                                    <Button variant="secondary" onClick={disableTwoFactor}>
                                        Annulla
                                    </Button>
                                </div>
                            </>
                        )}
                    </Form>
                </div>
            )}

            {twoFactor.confirmed && (
                <div className="space-y-5">
                    <p className="inline-flex rounded-full bg-green-100 px-3 py-1 text-sm font-medium text-green-900">Attiva</p>

                    <div>
                        <h3 className="font-semibold">Codici di recupero</h3>
                        <p className="mt-1 text-sm text-slate-600">
                            Servono se perdi il telefono. Conservali in un posto sicuro: ognuno si può usare una sola volta.
                        </p>

                        {recoveryCodes && recoveryCodes.length > 0 ? (
                            <ul className="mt-3 grid max-w-md grid-cols-1 gap-1 rounded-md bg-slate-100 p-4 font-mono text-sm sm:grid-cols-2">
                                {recoveryCodes.map((code) => (
                                    <li key={code}>{code}</li>
                                ))}
                            </ul>
                        ) : null}

                        <div className="mt-3 flex flex-wrap gap-3">
                            {!recoveryCodes?.length && (
                                <Button variant="secondary" onClick={showRecoveryCodes}>
                                    Mostra i codici
                                </Button>
                            )}
                            <Button variant="secondary" onClick={regenerateCodes}>
                                Genera nuovi codici
                            </Button>
                        </div>
                    </div>

                    {isConfirmingDisable ? (
                        <div role="group" aria-label="Conferma disattivazione" className="rounded-md border border-red-200 bg-red-50 p-4">
                            <p className="text-sm text-red-900">Vuoi davvero disattivare la verifica in due passaggi? L'account sarà meno protetto.</p>
                            <div className="mt-3 flex flex-wrap gap-3">
                                <Button variant="danger" onClick={disableTwoFactor}>
                                    Sì, disattiva
                                </Button>
                                <Button variant="secondary" onClick={() => setIsConfirmingDisable(false)} autoFocus>
                                    Annulla
                                </Button>
                            </div>
                        </div>
                    ) : (
                        <Button variant="danger" onClick={() => setIsConfirmingDisable(true)}>
                            Disattiva
                        </Button>
                    )}
                </div>
            )}
        </SettingsCard>
    );
}

function PasskeysSection({ passkeys }: { passkeys: PasskeySummary[] }) {
    const [passkeyName, setPasskeyName] = useState('');
    const [pendingDeletionId, setPendingDeletionId] = useState<number | null>(null);

    const passkeyRegistration = usePasskeyRegister({
        routes: { options: passkeyRegistrationOptions.url(), submit: passkeyStore.url() },
        onSuccess: () => {
            setPasskeyName('');
            router.reload({ only: ['passkeys'] });
        },
    });

    const registerPasskey = (event: FormEvent<HTMLFormElement>) => {
        event.preventDefault();
        passkeyRegistration.register(passkeyName.trim());
    };

    const deletePasskey = (passkeyId: number) =>
        router.delete(passkeyDestroy.url(passkeyId), {
            preserveScroll: true,
            onFinish: () => setPendingDeletionId(null),
        });

    return (
        <SettingsCard
            id="passkey"
            title="Passkey"
            description="Accedi senza password usando l'impronta, il volto o il PIN del tuo dispositivo."
        >
            {passkeys.length > 0 ? (
                <ul className="divide-y divide-slate-200 rounded-md border border-slate-200">
                    {passkeys.map((passkey) => (
                        <li key={passkey.id} className="flex flex-wrap items-center justify-between gap-3 p-4">
                            <div>
                                <p className="font-medium">{passkey.name}</p>
                                <p className="text-sm text-slate-600">
                                    Aggiunta il {formatDate(passkey.createdAt)} · ultimo uso: {formatDate(passkey.lastUsedAt)}
                                </p>
                            </div>
                            {pendingDeletionId === passkey.id ? (
                                <div className="flex flex-wrap gap-2">
                                    <Button variant="danger" onClick={() => deletePasskey(passkey.id)}>
                                        Conferma eliminazione
                                    </Button>
                                    <Button variant="secondary" onClick={() => setPendingDeletionId(null)}>
                                        Annulla
                                    </Button>
                                </div>
                            ) : (
                                <Button variant="secondary" onClick={() => setPendingDeletionId(passkey.id)}>
                                    Elimina <span className="sr-only">{passkey.name}</span>
                                </Button>
                            )}
                        </li>
                    ))}
                </ul>
            ) : (
                <p className="text-sm text-slate-600">Nessuna passkey registrata.</p>
            )}

            {passkeyRegistration.isSupported ? (
                <form onSubmit={registerPasskey} className="mt-6 flex max-w-md flex-col gap-3 sm:flex-row sm:items-end">
                    <FormField
                        id="passkey_name"
                        label="Nome del dispositivo"
                        placeholder="es. Portatile di casa"
                        value={passkeyName}
                        onChange={(event) => setPasskeyName(event.target.value)}
                        maxLength={100}
                        required
                        className="flex-1"
                    />
                    <Button type="submit" disabled={passkeyRegistration.isLoading || passkeyName.trim() === ''}>
                        {passkeyRegistration.isLoading ? 'Attendo…' : 'Aggiungi passkey'}
                    </Button>
                </form>
            ) : (
                <p className="mt-6 text-sm text-slate-600">Questo browser non supporta le passkey.</p>
            )}

            <div className="mt-3">
                <StatusMessage message={passkeyRegistration.error} tone="error" />
            </div>
        </SettingsCard>
    );
}

export default function Security({ twoFactor, recoveryCodes, passkeys }: SecurityProps) {
    return (
        <AdminLayout title="Sicurezza">
            <div className="space-y-8">
                <PasswordSection />
                <TwoFactorSection twoFactor={twoFactor} recoveryCodes={recoveryCodes} />
                <PasskeysSection passkeys={passkeys} />
            </div>
        </AdminLayout>
    );
}
