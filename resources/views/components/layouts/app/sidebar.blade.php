<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-slate-50 dark:bg-zinc-900">
    <flux:sidebar sticky stashable class="border-e border-slate-200 bg-white dark:border-zinc-700 dark:bg-zinc-900">
        <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

        <a href="{{ route('index') }}" class="me-5 flex items-center space-x-2 rtl:space-x-reverse" wire:navigate>
            <x-app-logo />
        </a>

        <flux:navlist variant="outline">
            <flux:navlist.item icon="home" :href="route('index')" :current="request()->routeIs('index')"
                wire:navigate>Index</flux:navlist.item>

            @can('ver contabilidad')
                <flux:navlist.group :heading="__('Platform')" class="grid">
                    @can('dashboard')
                        <flux:navlist.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')"
                            wire:navigate>{{ __('Dashboard') }}</flux:navlist.item>
                    @endcan
                    <flux:navlist.item icon="book-open" :href="route('nomenclatures.index')"
                        :current="request()->routeIs('nomenclatures.index')" wire:navigate>{{ __('Nomenclatura') }}
                    </flux:navlist.item>
                    <flux:navlist.item icon="clipboard-document-list" :href="route('accounting-rules.index')"
                        :current="request()->routeIs('accounting-rules.index')" wire:navigate>{{ __('Reglas Contables') }}
                    </flux:navlist.item>
                    <flux:navlist.item icon="book-open" :href="route('diary.index')"
                        :current="request()->routeIs('diary.index')" wire:navigate>{{ __('Libro Diario') }}
                    </flux:navlist.item>
                    <flux:navlist.item icon="calculator" :href="route('ledger.index')"
                        :current="request()->routeIs('ledger.index')" wire:navigate>{{ __('Libro Mayor') }}
                    </flux:navlist.item>
                    <flux:navlist.item icon="chart-bar" :href="route('results.index')"
                        :current="request()->routeIs('results.index')" wire:navigate>{{ __('Resultados') }}
                    </flux:navlist.item>
                    <flux:navlist.item icon="building-library" :href="route('balance.index')"
                        :current="request()->routeIs('balance.index')" wire:navigate>{{ __('Balance General') }}
                    </flux:navlist.item>
                </flux:navlist.group>
            @endcan

            @can('ver inventario')
                <flux:navlist.group :heading="__('Inventario')" class="grid">
                    <flux:navlist.item icon="chart-pie" :href="route('inventory.dashboard')"
                        :current="request()->routeIs('inventory.dashboard')" wire:navigate>{{ __('Dashboard') }}
                    </flux:navlist.item>
                    <flux:navlist.item icon="cube" :href="route('inventory.products')"
                        :current="request()->routeIs('inventory.products')" wire:navigate>{{ __('Productos') }}
                    </flux:navlist.item>
                    <flux:navlist.item icon="arrows-right-left" :href="route('inventory.movements')"
                        :current="request()->routeIs('inventory.movements')" wire:navigate>{{ __('Movimientos') }}
                    </flux:navlist.item>
                </flux:navlist.group>
            @endcan

            @can('gestionar usuarios')
                <flux:navlist.group :heading="__('Administración')" class="grid">
                    <flux:navlist.item icon="users" :href="route('users.index')"
                        :current="request()->routeIs('users.index')" wire:navigate>{{ __('Usuarios') }}
                    </flux:navlist.item>
                    @role('admin')
                        <flux:navlist.item icon="lock-closed" :href="route('roles.index')"
                            :current="request()->routeIs('roles.index')" wire:navigate>{{ __('Roles y Permisos') }}
                        </flux:navlist.item>
                    @endrole
                </flux:navlist.group>
            @endcan
        </flux:navlist>

        <flux:spacer />

        <flux:navlist variant="outline">
            <flux:navlist.item icon="folder-git-2" href="https://github.com/laravel/livewire-starter-kit"
                target="_blank">
                {{ __('Repository') }}
            </flux:navlist.item>

            <flux:navlist.item icon="book-open-text" href="https://laravel.com/docs/starter-kits#livewire"
                target="_blank">
                {{ __('Documentation') }}
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
