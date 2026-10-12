import type { InputHTMLAttributes, ReactNode } from 'react';

type FormFieldProps = InputHTMLAttributes<HTMLInputElement> & {
    id: string;
    label: string;
    error?: string;
    hint?: ReactNode;
};

export default function FormField({ id, label, error, hint, className = '', ...inputProps }: FormFieldProps) {
    const hintId = hint ? `${id}-hint` : undefined;
    const errorId = error ? `${id}-error` : undefined;
    const describedBy = [hintId, errorId].filter(Boolean).join(' ') || undefined;

    return (
        <div className={className}>
            <label htmlFor={id} className="block text-sm font-medium text-inchiostro">
                {label}
            </label>
            {hint && (
                <p id={hintId} className="mt-1 text-sm text-slate-600">
                    {hint}
                </p>
            )}
            <input
                id={id}
                name={id}
                aria-invalid={error ? true : undefined}
                aria-describedby={describedBy}
                className="mt-1 block min-h-11 w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-inchiostro shadow-sm aria-invalid:border-pennarello"
                {...inputProps}
            />
            {error && (
                <p id={errorId} className="mt-1 text-sm text-pennarello">
                    {error}
                </p>
            )}
        </div>
    );
}
