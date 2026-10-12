type StatusMessageProps = {
    message: string | null;
    tone?: 'success' | 'error';
};

/**
 * Announces feedback to screen readers through a polite live region.
 */
export default function StatusMessage({ message, tone = 'success' }: StatusMessageProps) {
    const toneClasses = tone === 'success' ? 'border-green-300 bg-green-50 text-green-900' : 'border-red-300 bg-red-50 text-red-900';

    return (
        <div role={tone === 'error' ? 'alert' : 'status'} aria-live={tone === 'error' ? 'assertive' : 'polite'}>
            {message && <p className={`rounded-md border px-4 py-3 text-sm ${toneClasses}`}>{message}</p>}
        </div>
    );
}
