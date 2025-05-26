import { PlaceholderPattern } from '@/components/ui/placeholder-pattern';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';
import { useEffect, useState } from 'react';

const breadcrumbs: BreadcrumbItem[] = [
{
        title: 'Dashboard',
        href: '/dashboard',
    },
];

export default function Dashboard() {
    const [devoluciones, setDevoluciones] = useState<{ pendientes: number; recientes_hoy: number } | null>(null);
    const [loading, setLoading] = useState(true);
    useEffect(() => {
        fetch('/api/devoluciones/resumen-dashboard')
            .then(res => res.json())
            .then(data => {
                setDevoluciones(data);
                setLoading(false);
            })
            .catch(() => setLoading(false));
    }, []);
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Dashboard" />
            <div className="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
                <div className="grid auto-rows-min gap-4 md:grid-cols-3">
                    <div className="border-sidebar-border/70 dark:border-sidebar-border relative aspect-video overflow-hidden rounded-xl border flex flex-col items-center justify-center">
                        <h2 className="text-lg font-semibold mb-2">Devoluciones Pendientes</h2>
                        {loading ? (
                            <span className="text-gray-400">Cargando...</span>
                        ) : (
                            <span className="text-4xl font-bold text-yellow-500">{devoluciones?.pendientes ?? 0}</span>
                        )}
                        <span className="text-xs text-gray-500 mt-2">Total pendientes de revisión</span>
                    </div>
                    <div className="border-sidebar-border/70 dark:border-sidebar-border relative aspect-video overflow-hidden rounded-xl border flex flex-col items-center justify-center">
                        <h2 className="text-lg font-semibold mb-2">Devoluciones Hoy</h2>
                        {loading ? (
                            <span className="text-gray-400">Cargando...</span>
                        ) : (
                            <span className="text-4xl font-bold text-blue-500">{devoluciones?.recientes_hoy ?? 0}</span>
                        )}
                        <span className="text-xs text-gray-500 mt-2">Solicitadas en el día</span>
                    </div>
                    <div className="border-sidebar-border/70 dark:border-sidebar-border relative aspect-video overflow-hidden rounded-xl border">
                        <PlaceholderPattern className="absolute inset-0 size-full stroke-neutral-900/20 dark:stroke-neutral-100/20" />
                    </div>
                </div>
                <div className="border-sidebar-border/70 dark:border-sidebar-border relative min-h-[100vh] flex-1 overflow-hidden rounded-xl border md:min-h-min">
                    <PlaceholderPattern className="absolute inset-0 size-full stroke-neutral-900/20 dark:stroke-neutral-100/20" />
                </div>
            </div>
        </AppLayout>
    );
}
