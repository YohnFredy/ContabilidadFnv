<x-layouts.app title="index">
    <div class="flex flex-col items-center justify-center min-h-[50vh] space-y-4">
        <h1 class="text-2xl font-bold text-slate-900 dark:text-white">{{ __('Bienvenido a Contabilidad FORNUVI') }}</h1>
        <p class="text-slate-600 dark:text-zinc-400">{{ __('Selecciona tu preferencia de apariencia:') }}</p>
        
        <flux:radio.group x-data x-init="$flux.appearance = $flux.appearance || 'dark'" variant="segmented" x-model="$flux.appearance">
            <flux:radio value="light" icon="sun">{{ __('Light') }}</flux:radio>
            <flux:radio value="dark" icon="moon">{{ __('Dark') }}</flux:radio>
            <flux:radio value="system" icon="computer-desktop">{{ __('System') }}</flux:radio>
        </flux:radio.group>
    </div>
</x-layouts.app>