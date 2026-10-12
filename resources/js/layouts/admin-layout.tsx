import StatusMessage from '@/components/status-message';
import { statusMessage } from '@/lib/status-messages';
import { logout } from '@/routes';
import { dashboard, security } from '@/routes/admin';
import type { SharedProps } from '@/types';
import { Head, Link, usePage } from '@inertiajs/react';
import type { ReactNode } from 'react';

type AdminLayoutProps = {
    title: string;
    children: ReactNode;
};

const navigationItems = [
    { label: 'Bacheca', route: dashboard },
    { label: 'Sicurezza', route: security },
];

export default function AdminLayout({ title, children }: AdminLayoutProps) {
    const { auth, flash } = usePage<SharedProps>().props;
    const { url: currentUrl } = usePage();

    return (
        <>
            <Head title={title} />
            <a
                href="#contenuto"
                className="sr-only rounded-md bg-white px-4 py-2 font-semibold focus:not-sr-only focus:fixed focus:top-3 focus:left-3 focus:z-50"
            >
                Vai al contenuto
            </a>

            <div className="min-h-screen bg-slate-50 font-sans text-inchiostro">
                <header className="bg-inchiostro text-white">
                    <div className="mx-auto flex max-w-6xl flex-wrap items-center justify-between gap-4 px-4 py-3">
                        <Link href={dashboard()} className="text-lg font-bold">
                            Scarabocchio <span className="font-normal text-carta/80">· Pannello</span>
                        </Link>

                        <div className="flex items-center gap-3 text-sm">
                            <span className="hidden text-carta/80 sm:inline">{auth.user?.name}</span>
                            <Link
                                href={logout()}
                                as="button"
                                className="min-h-11 rounded-md border border-white/30 px-3 hover:bg-white/10"
                            >
                                Esci
                            </Link>
                        </div>
                    </div>

                    <nav aria-label="Menu del pannello" className="mx-auto max-w-6xl px-4">
                        <ul className="flex gap-1 overflow-x-auto">
                            {navigationItems.map((item) => {
                                const href = item.route.url();
                                const isCurrent = currentUrl === href || currentUrl.startsWith(`${href}?`);

                                return (
                                    <li key={href}>
                                        <Link
                                            href={href}
                                            aria-current={isCurrent ? 'page' : undefined}
                                            className="inline-flex min-h-11 items-center rounded-t-md px-4 text-sm font-medium text-carta/80 hover:text-white aria-[current=page]:bg-slate-50 aria-[current=page]:text-inchiostro"
                                        >
                                            {item.label}
                                        </Link>
                                    </li>
                                );
                            })}
                        </ul>
                    </nav>
                </header>

                <main id="contenuto" className="mx-auto max-w-6xl px-4 py-8">
                    <h1 className="text-2xl font-bold">{title}</h1>
                    <div className="mt-4">
                        <StatusMessage message={statusMessage(flash.status)} />
                    </div>
                    <div className="mt-6">{children}</div>
                </main>
            </div>
        </>
    );
}
