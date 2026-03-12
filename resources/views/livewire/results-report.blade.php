<div class="p-4 sm:p-6">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-slate-900 dark:text-white">Estado de Resultados</h1>
            <p class="text-sm text-slate-500 dark:text-zinc-500">Informe de Pérdidas y Ganancias</p>
        </div>

        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 w-full sm:w-auto">
            <flux:input type="date" wire:model.live="start_date" label="Desde" />
            <flux:input type="date" wire:model.live="end_date" label="Hasta" />
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-2 sm:px-0">
    <div
        class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-700 shadow-sm rounded-lg overflow-hidden">
        <div class="p-4 sm:p-8 space-y-6 sm:space-y-8 font-mono text-sm">

            <!-- INGRESOS -->
            <div>
                <h3 class="font-bold text-slate-900 dark:text-white mb-2 uppercase tracking-wide text-xs sm:text-sm">INGRESOS</h3>
                @if(count($this->results['income_details']) > 0)
                    <div class="space-y-1 mb-4">
                        @foreach($this->results['income_details'] as $detail)
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center p-3 hover:bg-slate-50 dark:hover:bg-zinc-800/50 rounded-lg transition-colors group">
                                <div class="flex items-center gap-4">
                                    <span class="font-mono font-bold text-slate-700 dark:text-zinc-400 w-16">{{ $detail['code'] }}</span>
                                    <span class="text-slate-900 dark:text-white font-medium">{{ $detail['name'] }}</span>
                                </div>
                                <div class="flex items-center gap-4 mt-2 sm:mt-0 w-full sm:w-auto justify-between sm:justify-end">
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-zinc-100 text-zinc-600 dark:bg-zinc-800 dark:text-zinc-400 border border-zinc-200 dark:border-zinc-700">
                                        Ingresos
                                    </span>
                                    {{-- Optional: Show individual amount if desired, otherwise just list them --}}
                                    {{-- Based on user image, it doesn't explicitly show amount on the row, but usually it's helpful. 
                                         However, the user said "mostrar el resultado" (show result) and "estos son los ingresos" (these are the incomes).
                                         The image shows Code, Name, "Ingresos" badge. It does NOT clearly show the right side 
                                         where the amount usually is (crop). I will assume showing amount is standard and good. --}}
                                    <span class="font-mono text-slate-900 dark:text-white">{{ number_format($detail['amount'], 2) }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4 text-slate-500 dark:text-zinc-500 italic">No hay ingresos registrados en este periodo.</div>
                @endif
                {{-- Total Section --}}
                <div class="flex justify-between items-center py-4 border-t border-slate-200 dark:border-zinc-800 mt-2">
                    <span class="font-bold text-slate-900 dark:text-white">Total Ingresos</span>
                    <span class="font-bold text-slate-900 dark:text-white">{{ number_format($this->results['income'], 2) }}</span>
                </div>

            <!-- COSTOS -->
            <div>
                <h3 class="font-bold text-slate-900 dark:text-white mb-2 uppercase tracking-wide text-xs sm:text-sm">MENOS: COSTO DE
                    VENTAS</h3>
                <div class="flex justify-between items-center py-2 border-b border-slate-100 dark:border-zinc-800">
                    <span class="text-slate-600 dark:text-zinc-400 text-xs sm:text-sm">(6) Costo de Ventas</span>
                    <span
                        class="text-slate-900 dark:text-white">{{ number_format($this->results['costs'], 2) }}</span>
                </div>
                <div class="flex justify-between items-center py-2 mt-2">
                    <span class="font-bold text-slate-900 dark:text-white">UTILIDAD BRUTA</span>
                    <div class="text-right">
                        <span
                            class="font-bold text-slate-900 dark:text-white block">{{ number_format($this->results['gross_profit'], 2) }}</span>
                        @if($this->results['income'] > 0)
                            <span
                                class="text-xs text-slate-500 dark:text-zinc-500">{{ number_format($this->results['gross_margin'], 2) }}%</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- GASTOS -->
            <div>
                <h3 class="font-bold text-slate-900 dark:text-white mb-2 uppercase tracking-wide text-xs sm:text-sm">MENOS: GASTOS
                    OPERACIONALES</h3>
                <div class="flex justify-between items-center py-2 border-b border-slate-100 dark:border-zinc-800">
                    <span class="text-slate-600 dark:text-zinc-400 text-xs sm:text-sm">(5) Gastos Administrativos y de Ventas</span>
                    <span
                        class="text-slate-900 dark:text-white">{{ number_format($this->results['expenses'], 2) }}</span>
                </div>
                <div
                    class="flex flex-col sm:flex-row justify-between items-start sm:items-center py-4 mt-2 border-t-2 border-slate-200 dark:border-zinc-700 gap-2">
                    <span class="font-bold text-base sm:text-lg text-slate-900 dark:text-white">RESULTADO DEL EJERCICIO
                        (Utilidad/Pérdida)</span>
                    <div class="text-right">
                        <span
                            class="font-bold text-lg {{ $this->results['net_result'] >= 0 ? 'text-green-600 dark:text-white' : 'text-red-600 dark:text-zinc-400' }}">
                            {{ number_format((float)($this->results['net_result'] ?? 0), 2) }}
                        </span>
                        @if($this->results['income'] > 0)
                            <span
                                class="block text-xs text-slate-500 dark:text-zinc-500">{{ number_format($this->results['net_margin'], 2) }}%</span>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
    </div>
</div>