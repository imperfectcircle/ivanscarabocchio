import { Link, type InertiaLinkProps } from '@inertiajs/react';

export default function TextLink({ className = '', ...props }: InertiaLinkProps) {
    return (
        <Link
            className={`font-medium text-biro underline underline-offset-4 hover:text-inchiostro ${className}`}
            {...props}
        />
    );
}
