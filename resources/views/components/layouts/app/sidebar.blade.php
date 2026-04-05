<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">


<head>
    @include('partials.head')
    <style>
        /* Custom Sidebar Hover Logic - ENHANCED */
        @media (min-width: 1024px) {
            .sidebar-compact {
                width: 4.5rem;
                /* Ajustado para centrar mejor los iconos */
                transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                overflow-x: hidden;
                overflow-y: auto;
                z-index: 50;
                /* Ensure it's above chat container */
                position: relative;
                white-space: nowrap;
                /* Evita que el texto baje y rompa el diseño */

                /* Hide scrollbar */
                -ms-overflow-style: none;
                /* IE and Edge */
                scrollbar-width: none;
                /* Firefox */
            }

            /* Hide scrollbar for Chrome, Safari and Opera */
            .sidebar-compact::-webkit-scrollbar {
                display: none;
            }

            .sidebar-compact:hover {
                width: 16rem;
                /* 256px */
            }

            .sidebar-compact .nav-text {
                opacity: 0;
                transition: opacity 0.2s ease;
                display: inline-block;
                vertical-align: middle;
            }

            .sidebar-compact:hover .nav-text {
                opacity: 1;
                transition-delay: 0.1s;
            }

            /* Custom group heading styling and truncation */
            .sidebar-compact:not(:hover) .custom-sidebar-heading {
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
                width: 3.2rem;
                /* Ancho fijo para mostrar aprox 4 letras (ej. INVE...) */
                padding-left: 0.5rem !important;
                /* Mínimo margen izquierdo para alinear con iconos */
                padding-right: 0 !important;
                display: block;
            }
        }
    </style>
</head>

<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-slate-50 dark:bg-zinc-900">
    <flux:sidebar sticky stashable class="sidebar-compact border-e border-slate-200 bg-white dark:border-stone-950 dark:bg-stone-900">
        <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

        <a href="{{ route('index') }}" class="me-5 flex items-center space-x-2 rtl:space-x-reverse" wire:navigate>
            <x-app-logo />
        </a>

        <flux:navlist variant="outline">
            <flux:navlist.item icon="home" :href="route('index')" :current="request()->routeIs('index')"
                wire:navigate><span class="nav-text">Index</span></flux:navlist.item>

            @can('ver contabilidad')
            <div class="px-3 pt-4 pb-2 text-xs font-semibold text-zinc-500 uppercase tracking-wider custom-sidebar-heading transition-opacity duration-200">
                {{ __('Platform') }}
            </div>
            <flux:navlist.group class="grid">
                @can('dashboard')
                <flux:navlist.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')"
                    wire:navigate><span class="nav-text">{{ __('Dashboard') }}</span></flux:navlist.item>
                @endcan
                <flux:navlist.item icon="book-open" :href="route('nomenclatures.index')"
                    :current="request()->routeIs('nomenclatures.index')" wire:navigate><span class="nav-text">{{ __('Nomenclatura') }}</span>
                </flux:navlist.item>
                <flux:navlist.item icon="clipboard-document-list" :href="route('accounting-rules.index')"
                    :current="request()->routeIs('accounting-rules.index')" wire:navigate><span class="nav-text">{{ __('Reglas Contables') }}</span>
                </flux:navlist.item>
                <flux:navlist.item icon="tag" :href="route('accounting-rule-categories.index')"
                    :current="request()->routeIs('accounting-rule-categories.index')" wire:navigate><span class="nav-text">{{ __('Categorías') }}</span>
                </flux:navlist.item>
                <flux:navlist.item icon="book-open" :href="route('diary.index')"
                    :current="request()->routeIs('diary.index')" wire:navigate><span class="nav-text">{{ __('Libro Diario') }}</span>
                </flux:navlist.item>
                <flux:navlist.item icon="calculator" :href="route('ledger.index')"
                    :current="request()->routeIs('ledger.index')" wire:navigate><span class="nav-text">{{ __('Libro Mayor') }}</span>
                </flux:navlist.item>
                <flux:navlist.item icon="chart-bar" :href="route('results.index')"
                    :current="request()->routeIs('results.index')" wire:navigate><span class="nav-text">{{ __('Resultados') }}</span>
                </flux:navlist.item>
                <flux:navlist.item icon="building-library" :href="route('balance.index')"
                    :current="request()->routeIs('balance.index')" wire:navigate><span class="nav-text">{{ __('Balance General') }}</span>
                </flux:navlist.item>
            </flux:navlist.group>
            @endcan

            @can('ver inventario')
            <div class="px-3 pt-4 pb-2 text-xs font-semibold text-zinc-500 uppercase tracking-wider custom-sidebar-heading transition-opacity duration-200">
                {{ __('Inventario') }}
            </div>
            <flux:navlist.group class="grid">
                <flux:navlist.item icon="chart-pie" :href="route('inventory.dashboard')"
                    :current="request()->routeIs('inventory.dashboard')" wire:navigate><span class="nav-text">{{ __('Dashboard') }}</span>
                </flux:navlist.item>
                <flux:navlist.item icon="cube" :href="route('inventory.products')"
                    :current="request()->routeIs('inventory.products')" wire:navigate><span class="nav-text">{{ __('Productos') }}</span>
                </flux:navlist.item>
                <flux:navlist.item icon="arrows-right-left" :href="route('inventory.movements')"
                    :current="request()->routeIs('inventory.movements')" wire:navigate><span class="nav-text">{{ __('Movimientos') }}</span>
                </flux:navlist.item>
            </flux:navlist.group>
            @endcan

            @can('gestionar usuarios')
            <div class="px-3 pt-4 pb-2 text-xs font-semibold text-zinc-500 uppercase tracking-wider custom-sidebar-heading transition-opacity duration-200">
                {{ __('Administración') }}
            </div>
            <flux:navlist.group class="grid">
                <flux:navlist.item icon="users" :href="route('users.index')"
                    :current="request()->routeIs('users.index')" wire:navigate><span class="nav-text">{{ __('Usuarios') }}</span>
                </flux:navlist.item>
                @role('admin')
                <flux:navlist.item icon="lock-closed" :href="route('roles.index')"
                    :current="request()->routeIs('roles.index')" wire:navigate><span class="nav-text">{{ __('Roles y Permisos') }}</span>
                </flux:navlist.item>
                @endrole
            </flux:navlist.group>
            @endcan
        </flux:navlist>

        <flux:spacer />

        <flux:navlist variant="outline">
            <flux:navlist.item icon="folder-git-2" href="https://github.com/laravel/livewire-starter-kit"
                target="_blank">
                <span class="nav-text">{{ __('Repository') }}</span>
            </flux:navlist.item>

            <flux:navlist.item icon="book-open-text" href="https://laravel.com/docs/starter-kits#livewire"
                target="_blank">
                <span class="nav-text">{{ __('Documentation') }}</span>
            </flux:navlist.item>
        </flux:navlist>

        <!-- Desktop User Menu -->
        <flux:dropdown class="hidden lg:block" position="bottom" align="start">
            <flux:profile :name="auth()->user()->name" :initials="auth()->user()->initials()"
                icon:trailing="chevrons-up-down" data-test="sidebar-menu-button" />

            <flux:menu class="w-[220px]">
                <flux:menu.radio.group>
                    <div class="p-0 text-sm font-normal">
                        <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                            <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                <span
                                    class="flex h-full w-full items-center justify-center rounded-lg bg-slate-200 text-slate-800 dark:bg-zinc-700 dark:text-white">
                                    {{ auth()->user()->initials() }}
                                </span>
                            </span>

                            <div class="grid flex-1 text-start text-sm leading-tight">
                                <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                            </div>
                        </div>
                    </div>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <flux:menu.radio.group>
                    <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>{{ __('Settings') }}
                    </flux:menu.item>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full"
                        data-test="logout-button">
                        {{ __('Log Out') }}
                    </flux:menu.item>
                </form>
            </flux:menu>
        </flux:dropdown>
    </flux:sidebar>

    <!-- Mobile User Menu -->
    <flux:header class="lg:hidden">
        <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

        <flux:spacer />

        <flux:dropdown position="top" align="end">
            <flux:profile :initials="auth()->user()->initials()" icon-trailing="chevron-down" />

            <flux:menu>
                <flux:menu.radio.group>
                    <div class="p-0 text-sm font-normal">
                        <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                            <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                <span
                                    class="flex h-full w-full items-center justify-center rounded-lg bg-slate-200 text-slate-800 dark:bg-zinc-700 dark:text-white">
                                    {{ auth()->user()->initials() }}
                                </span>
                            </span>

                            <div class="grid flex-1 text-start text-sm leading-tight">
                                <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                            </div>
                        </div>
                    </div>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <flux:menu.radio.group>
                    <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>{{ __('Settings') }}
                    </flux:menu.item>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full"
                        data-test="logout-button">
                        {{ __('Log Out') }}
                    </flux:menu.item>
                </form>
            </flux:menu>
        </flux:dropdown>
    </flux:header>

    {{ $slot }}

    <x-toast />

    @fluxScripts
</body>

</html>