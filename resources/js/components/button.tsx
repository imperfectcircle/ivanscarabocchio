import type { ButtonHTMLAttributes } from 'react';

type ButtonVariant = 'primary' | 'secondary' | 'danger';

type ButtonProps = ButtonHTMLAttributes<HTMLButtonElement> & {
    variant?: ButtonVariant;
};

const variantClasses: Record<ButtonVariant, string> = {
    primary: 'bg-inchiostro text-white hover:bg-biro',
    secondary: 'border border-slate-300 bg-white text-inchiostro hover:bg-slate-50',
    danger: 'bg-pennarello text-white hover:bg-red-800',
};

export default function Button({ variant = 'primary', type = 'button', className = '', ...props }: ButtonProps) {
    return (
        <button
            type={type}
            className={`inline-flex min-h-11 items-center justify-center gap-2 rounded-md px-4 py-2 text-sm font-semibold transition-colors disabled:cursor-not-allowed disabled:opacity-60 ${variantClasses[variant]} ${className}`}
            {...props}
        />
    );
}
