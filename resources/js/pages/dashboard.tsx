import TextLink from '@/components/text-link';
import AdminLayout from '@/layouts/admin-layout';
import { security } from '@/routes/admin';
import type { SharedProps } from '@/types';
import { usePage } from '@inertiajs/react';

export default function Dashboard() {
    const { auth } = usePage<SharedProps>().props;

    return (
        <AdminLayout title="Bacheca">
            <section aria-labelledby="benvenuto" className="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 id="benvenuto" className="text-lg font-semibold">
                    Ciao {auth.user?.name}!
                </h2>
                <p className="mt-2 text-slate-600">
                    Qui troverai la gestione di libri, racconti, articoli, eventi e newsletter. Le sezioni arriveranno a breve.
                </p>
                <p className="mt-4 text-sm">
                    Intanto puoi proteggere il tuo account dalla pagina <TextLink href={security()}>Sicurezza</TextLink>.
                </p>
            </section>
        </AdminLayout>
    );
}
