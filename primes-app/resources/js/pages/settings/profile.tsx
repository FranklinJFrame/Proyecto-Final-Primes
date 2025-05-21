import { type BreadcrumbItem, type SharedData } from '@/types';
import { Transition } from '@headlessui/react';
import { Head, Link, useForm, usePage } from '@inertiajs/react';
import { FormEventHandler, useEffect, useState } from 'react';

import DeleteUser from '@/components/delete-user';
import HeadingSmall from '@/components/heading-small';
import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/app-layout';
import SettingsLayout from '@/layouts/settings/layout';

// Importar estilos cyber
import './cyber-glitch.css';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Mi Cuenta',
        href: '/settings/profile',
    },
];

type ProfileForm = {
    name: string;
    email: string;
}

export default function Profile({ mustVerifyEmail, status }: { mustVerifyEmail: boolean; status?: string }) {
    const { auth, ultimosPedidos, direccion } = usePage<any>().props;
    const [currentTime, setCurrentTime] = useState(new Date());
    
    // Actualizar la hora cada segundo para el efecto cyber
    useEffect(() => {
        const timer = setInterval(() => {
            setCurrentTime(new Date());
        }, 1000);
        return () => clearInterval(timer);
    }, []);

    const { data, setData, patch, errors, processing, recentlySuccessful } = useForm<Required<ProfileForm>>({
        name: auth.user.name,
        email: auth.user.email,
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();

        patch(route('profile.update'), {
            preserveScroll: true,
        });
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Mi Cuenta | Primes" />

            <div className="cyber-bg min-h-screen pt-6 pb-16">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
                    {/* Fondo con efecto cyber */}
                    <div className="absolute inset-0 cyber-grid opacity-20"></div>
                    
                    {/* Cabecera de bienvenida */}
                    <div className="relative z-10 text-center mb-12">
                        <h1 className="text-4xl font-extrabold text-white mb-2 cyber-glitch-text">
                            <span className="bg-clip-text text-transparent bg-gradient-to-r from-blue-500 via-purple-500 to-red-500">
                                Centro de Control
                            </span>
                        </h1>
                        <p className="text-lg text-gray-300 animate-pulse-slow">
                            Bienvenido de vuelta, <span className="font-bold neon-text">{auth.user.name}</span>
                        </p>
                        <div className="mt-2 cyber-tag inline-block">
                            {currentTime.toLocaleTimeString('es-DO')}
                        </div>
                    </div>

                    {/* Grid principal */}
                    <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        {/* Panel izquierdo: Datos personales */}
                        <div className="cyber-panel rounded-xl p-6 border border-blue-500/30 backdrop-blur-sm hover:border-blue-500 transition-all duration-300">
                            <h2 className="text-xl font-bold mb-6 text-blue-400 cyber-glitch-text">Datos Personales</h2>
                            
                            <form onSubmit={submit} className="space-y-6">
                                <div className="space-y-4">
                                    <div>
                                        <Label htmlFor="name" className="text-gray-300">Nombre</Label>
                                        <Input
                                            id="name"
                                            className="mt-1 block w-full bg-gray-800/50 border-blue-500/30 text-white"
                                            value={data.name}
                                            onChange={(e) => setData('name', e.target.value)}
                                            required
                                            autoComplete="name"
                                        />
                                        <InputError className="mt-1" message={errors.name} />
                                    </div>

                                    <div>
                                        <Label htmlFor="email" className="text-gray-300">Email</Label>
                                        <Input
                                            id="email"
                                            type="email"
                                            className="mt-1 block w-full bg-gray-800/50 border-blue-500/30 text-white"
                                            value={data.email}
                                            onChange={(e) => setData('email', e.target.value)}
                                            required
                                            autoComplete="username"
                                        />
                                        <InputError className="mt-1" message={errors.email} />
                                    </div>

                                    {/* Información de cuenta */}
                                    <div className="pt-4 border-t border-blue-500/20">
                                        <div className="flex justify-between text-sm mb-2">
                                            <span className="text-gray-400">ID de Usuario:</span>
                                            <span className="text-white font-mono">#00{auth.user.id}</span>
                                        </div>
                                        <div className="flex justify-between text-sm mb-2">
                                            <span className="text-gray-400">Estado:</span>
                                            <span className="text-green-400">Activo</span>
                                        </div>
                                        <div className="flex justify-between text-sm">
                                            <span className="text-gray-400">Miembro desde:</span>
                                            <span className="text-white">{new Date(auth.user.created_at).toLocaleDateString('es-DO')}</span>
                                        </div>
                                    </div>
                                </div>

                                <div className="flex items-center gap-4">
                                    <Button 
                                        disabled={processing}
                                        className="bg-blue-600 hover:bg-blue-700 text-white"
                                    >
                                        Guardar Cambios
                                    </Button>

                                    <Transition
                                        show={recentlySuccessful}
                                        enter="transition ease-in-out"
                                        enterFrom="opacity-0"
                                        leave="transition ease-in-out"
                                        leaveTo="opacity-0"
                                    >
                                        <p className="text-sm text-green-500">Guardado</p>
                                    </Transition>
                                </div>
                            </form>
                        </div>

                        {/* Panel central: Últimos pedidos */}
                        <div className="cyber-panel rounded-xl p-6 border border-purple-500/30 backdrop-blur-sm hover:border-purple-500 transition-all duration-300">
                            <h2 className="text-xl font-bold mb-6 text-purple-400 cyber-glitch-text">Últimos Pedidos</h2>
                            
                            {Array.isArray(ultimosPedidos) && ultimosPedidos.length > 0 ? (
                                <div className="space-y-4">
                                    {ultimosPedidos.map((pedido: any) => (
                                        <div key={pedido.id} className="bg-gray-800/50 rounded-lg p-4 border border-purple-500/20 hover:border-purple-500/50 transition-all duration-300 transform hover:-translate-y-1">
                                            <div className="flex justify-between items-center mb-2">
                                                <span className="font-bold text-white">Pedido #{pedido.id}</span>
                                                <span className="cyber-tag bg-purple-900/50 border-purple-500/30">
                                                    {new Date(pedido.created_at).toLocaleDateString('es-DO')}
                                                </span>
                                            </div>
                                            <div className="flex justify-between items-center">
                                                <span className="text-gray-300">Total:</span>
                                                <span className="text-lg font-bold text-purple-400">
                                                    RD$ {Number(pedido.total_general).toLocaleString('es-DO', {minimumFractionDigits: 2})}
                                                </span>
                                            </div>
                                            <div className="mt-3 text-right">
                                                <Link 
                                                    href={`/mis-pedidos/${pedido.id}`} 
                                                    className="text-sm text-purple-400 hover:text-purple-300 underline"
                                                >
                                                    Ver detalles →
                                                </Link>
                                            </div>
                                        </div>
                                    ))}

                                    <div className="mt-4 text-center">
                                        <Link 
                                            href="/mis-pedidos" 
                                            className="cyber-button px-4 py-2 rounded-md text-white inline-block"
                                        >
                                            Ver historial completo
                                        </Link>
                                    </div>
                                </div>
                            ) : (
                                <div className="bg-gray-800/50 rounded-lg p-6 text-center border border-purple-500/20">
                                    <p className="text-gray-400 mb-4">No tienes pedidos recientes.</p>
                                    <Link 
                                        href="/productos" 
                                        className="cyber-button px-4 py-2 rounded-md text-white inline-block"
                                    >
                                        Explorar productos
                                    </Link>
                                </div>
                            )}
                        </div>

                        {/* Panel derecho: Dirección y acciones */}
                        <div className="space-y-6">
                            {/* Dirección principal */}
                            <div className="cyber-panel rounded-xl p-6 border border-red-500/30 backdrop-blur-sm hover:border-red-500 transition-all duration-300">
                                <h2 className="text-xl font-bold mb-6 text-red-400 cyber-glitch-text">Dirección Principal</h2>
                                
                                {direccion ? (
                                    <div className="space-y-3">
                                        <div className="bg-gray-800/50 rounded-lg p-4 border border-red-500/20">
                                            <div className="mb-2">
                                                <span className="text-gray-400 block text-sm">Dirección:</span>
                                                <span className="text-white">{direccion.direccion_calle}</span>
                                            </div>
                                            <div className="mb-2">
                                                <span className="text-gray-400 block text-sm">Ciudad:</span>
                                                <span className="text-white">{direccion.ciudad}</span>
                                            </div>
                                            <div className="mb-2">
                                                <span className="text-gray-400 block text-sm">Estado:</span>
                                                <span className="text-white">{direccion.estado}</span>
                                            </div>
                                            <div>
                                                <span className="text-gray-400 block text-sm">Código Postal:</span>
                                                <span className="text-white">{direccion.codigo_postal}</span>
                                            </div>
                                        </div>
                                        
                                        <div className="text-center">
                                            <Link 
                                                href="/mis-direcciones" 
                                                className="text-sm text-red-400 hover:text-red-300 underline"
                                            >
                                                Gestionar direcciones
                                            </Link>
                                        </div>
                                    </div>
                                ) : (
                                    <div className="bg-gray-800/50 rounded-lg p-6 text-center border border-red-500/20">
                                        <p className="text-gray-400 mb-4">No tienes una dirección principal registrada.</p>
                                        <Link 
                                            href="/mis-direcciones" 
                                            className="cyber-button px-4 py-2 rounded-md text-white inline-block"
                                        >
                                            Añadir dirección
                                        </Link>
                                    </div>
                                )}
                            </div>

                            {/* Acciones rápidas */}
                            <div className="cyber-panel rounded-xl p-6 border border-green-500/30 backdrop-blur-sm hover:border-green-500 transition-all duration-300">
                                <h2 className="text-xl font-bold mb-6 text-green-400 cyber-glitch-text">Acciones Rápidas</h2>
                                
                                <div className="grid grid-cols-1 gap-3">
                                    <Link 
                                        href="/carrito" 
                                        className="cyber-button px-4 py-3 rounded-md text-white flex items-center justify-between"
                                    >
                                        <span>Mi Carrito</span>
                                        <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                    </Link>
                                    
                                    <Link 
                                        href="/productos?oferta=1" 
                                        className="cyber-button px-4 py-3 rounded-md text-white flex items-center justify-between"
                                    >
                                        <span>Ofertas Especiales</span>
                                        <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" />
                                        </svg>
                                    </Link>
                                    
                                    <Link 
                                        href="/logout" 
                                        method="post" 
                                        as="button"
                                        className="cyber-button px-4 py-3 rounded-md text-white flex items-center justify-between"
                                    >
                                        <span>Cerrar Sesión</span>
                                        <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                        </svg>
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>

                    {/* Sección de eliminación de cuenta */}
                    <div className="mt-12 cyber-panel rounded-xl p-6 border border-red-500/30 backdrop-blur-sm">
                        <DeleteUser />
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}