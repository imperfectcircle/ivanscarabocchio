import { Head } from '@inertiajs/react';
import type { ReactNode } from 'react';

type AuthLayoutProps = {
    title: string;
    description?: string;
    children: ReactNode;
};

export default function AuthLayout({ title, description, children }: AuthLayoutProps) {
    return (
        <>
            <Head title={title} />
            <main className="flex min-h-screen items-center justify-center bg-carta px-4 py-12 font-sans">
                <div className="w-full max-w-md rounded-xl border border-slate-200 bg-white p-8 shadow-sm">
                    <p className="text-sm font-semibold tracking-wide text-pennarello uppercase">Ivan Scarabocchio · Pannello</p>
                    <h1 className="mt-2 text-2xl font-bold text-inchiostro">{title}</h1>
                    {description && <p className="mt-2 text-sm text-slate-600">{description}</p>}
                    <div className="mt-6">{children}</div>
                </div>
            </main>
        </>
    );
}
