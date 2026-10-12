/**
 * Italian labels for the status keys flashed by Fortify and the passkeys package.
 */
const statusMessages: Record<string, string> = {
    'password-updated': 'Password aggiornata.',
    'two-factor-authentication-enabled': 'Completa la configurazione inquadrando il QR code.',
    'two-factor-authentication-confirmed': 'Verifica in due passaggi attivata.',
    'two-factor-authentication-disabled': 'Verifica in due passaggi disattivata.',
    'recovery-codes-generated': 'Nuovi codici di recupero generati.',
    'passkey-deleted': 'Passkey eliminata.',
};

export function statusMessage(status: string | null | undefined): string | null {
    if (!status) {
        return null;
    }

    return statusMessages[status] ?? status;
}
