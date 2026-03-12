<div class="p-4 sm:p-6">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4 print:hidden">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-slate-900 dark:text-white">Balance General</h1>
        </div>

        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 w-full sm:w-auto">
            <flux:input type="date" wire:model.live="start_date" label="Desde" />
            <flux:input type="date" wire:model.live="end_date" label="Hasta" />
        </div>
    </div>

    <div
        class="bg-white dark:bg-zinc-900 shadow-sm rounded-lg overflow-hidden border border-slate-200 dark:border-zinc-700 p-4 sm:p-8 max-w-6xl mx-auto font-sans text-sm">

        <!-- Header Date Range -->
        <div
            class="bg-slate-100 dark:bg-zinc-800 py-2 px-4 text-center font-bold text-sm sm:text-lg uppercase mb-6 sm:mb-8 text-slate-900 dark:text-white rounded">
            {{ \Carbon\Carbon::parse($start_date)->format('d/m/Y') }} HASTA EL
            {{ \Carbon\Carbon::parse($end_date)->format('d/m/Y') }}
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12">

            <!-- LEFT COLUMN: ACTIVOS -->
            <div class="space-y-6 sm:space-y-8">
                <!-- Header -->
                <h2 class="font-bold text-base sm:text-lg text-slate-900 dark:text-white uppercase mb-2">Activos</h2>

                <!-- Activos Corrientes -->
                <div>
                    <h3 class="font-bold text-slate-900 dark:text-white text-sm sm:text-base mb-2">Activos Corrientes</h3>
                    <div class="space-y-1">
                        @foreach ($this->reportData['assets_current'] as $item)
                            <div class="flex justify-between">
                                <span class="text-slate-700 dark:text-zinc-300 text-xs sm:text-sm">{{ $item['name'] }}</span>
                                <span class="text-slate-900 dark:text-white font-medium">$
                                    {{ number_format($item['value'], 2) }}</span>
                            </div>
                        @endforeach
                    </div>
                    <div class="flex justify-between mt-2 pt-2 border-t border-slate-200 dark:border-zinc-700 font-bold">
                        <span class="text-slate-900 dark:text-white text-xs sm:text-sm">Total activos Corrientes</span>
                        <span class="text-slate-900 dark:text-white">$
                            {{ number_format($this->reportData['total_assets_current'], 2) }}</span>
                    </div>
                </div>

                <!-- Activos no Corrientes -->
                <div>
                    <h3 class="font-bold text-slate-900 dark:text-white text-sm sm:text-base mb-2">Activos no Corrientes</h3>
                    <div class="space-y-1">
                        @foreach ($this->reportData['assets_non_current'] as $item)
                            <div class="flex justify-between">
                                <span class="text-slate-700 dark:text-zinc-300 text-xs sm:text-sm">{{ $item['name'] }}</span>
                                <span class="text-slate-900 dark:text-white font-medium">$
                                    {{ number_format($item['value'], 2) }}</span>
                            </div>
                        @endforeach
                    </div>
                    <div class="flex justify-between mt-2 pt-2 border-t border-slate-200 dark:border-zinc-700 font-bold">
                        <span class="text-slate-900 dark:text-white text-xs sm:text-sm">Total activos no Corrientes</span>
                        <span class="text-slate-900 dark:text-white">$
                            {{ number_format($this->reportData['total_assets_non_current'], 2) }}</span>
                    </div>
                </div>

                <!-- Total Activos -->
                <div class="flex justify-between mt-6 sm:mt-8 pt-4 font-bold text-base sm:text-lg border-t-2 border-slate-300 dark:border-zinc-600">
                    <span class="text-slate-900 dark:text-white">Total Activos</span>
                    <span class="text-slate-900 dark:text-white">$
                        {{ number_format($this->reportData['total_assets'], 2) }}</span>
                </div>

            </div>

            <!-- RIGHT COLUMN: PASIVOS Y PATRIMONIO -->
            <div class="space-y-6 sm:space-y-8">
                <!-- Header -->
                <h2 class="font-bold text-base sm:text-lg text-slate-900 dark:text-white uppercase mb-2">Pasivos y Patrimonio</h2>

                <!-- Pasivos Corrientes -->
                <div>
                    <h3 class="font-bold text-slate-900 dark:text-white text-sm sm:text-base mb-2">Pasivos Corrientes</h3>
                    <div class="space-y-1">
                        @foreach ($this->reportData['liabilities_current'] as $item)
                            <div class="flex justify-between">
                                <span class="text-slate-700 dark:text-zinc-300 text-xs sm:text-sm">{{ $item['name'] }}</span>
                                <span class="text-slate-900 dark:text-white font-medium">$
                                    {{ number_format($item['value'], 2) }}</span>
                            </div>
                        @endforeach
                    </div>
                    <div class="flex justify-between mt-2 pt-2 border-t border-slate-200 dark:border-zinc-700 font-bold">
                        <span class="text-slate-900 dark:text-white text-xs sm:text-sm">Total Pasivos</span>
                        <span class="text-slate-900 dark:text-white">$
                            {{ number_format($this->reportData['total_liabilities'], 2) }}</span>
                    </div>
                </div>

                <!-- Patrimonio -->
                <div class="mt-6 sm:mt-8"> <!-- Spacing to align visually similar to image -->
                    <h3 class="font-bold text-slate-900 dark:text-white text-sm sm:text-base mb-2">Patrimonio</h3>
                    <div class="space-y-1">
                        @foreach ($this->reportData['equity'] as $item)
                            <div class="flex justify-between">
                                <span class="text-slate-700 dark:text-zinc-300 text-xs sm:text-sm">{{ $item['name'] }}</span>
                                <span class="text-slate-900 dark:text-white font-medium">$
                                    {{ number_format($item['value'], 2) }}</span>
                            </div>
                        @endforeach

                        <!-- Net Result -->
                        <div class="flex justify-between">
                            <span class="text-slate-700 dark:text-zinc-300 text-xs sm:text-sm">Resultado del ejercicio</span>
                            <span class="text-slate-900 dark:text-white font-medium">$
                                {{ number_format($this->reportData['net_result'], 2) }}</span>
                        </div>
                    </div>

                    <div class="flex justify-between mt-2 pt-2 border-t border-slate-200 dark:border-zinc-700 font-bold">
                        <span class="text-slate-900 dark:text-white text-xs sm:text-sm">Total Patrimonio</span>
                        <span class="text-slate-900 dark:text-white">$
                            {{ number_format($this->reportData['total_equity'], 2) }}</span>
                    </div>
                </div>

                <!-- Total Pasivo y Patrimonio -->
                <div class="flex justify-between mt-6 sm:mt-8 pt-4 font-bold text-base sm:text-lg border-t-2 border-slate-300 dark:border-zinc-600">
                    <span class="text-slate-900 dark:text-white">Total Pasivo y Patrimonio</span>
                    <span class="text-slate-900 dark:text-white">$
                        {{ number_format($this->reportData['total_equity_and_liabilities'], 2) }}</span>
                </div>
            </div>
        </div>
    </div>
</div>