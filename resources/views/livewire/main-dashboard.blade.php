<div class="py-4 sm:py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-4 sm:space-y-6">
        
        {{-- Header with Period Selector --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 dark:from-white dark:to-white bg-clip-text text-transparent">
                    Panel de Control
                </h1>
                <p class="text-sm text-slate-500 dark:text-zinc-400 mt-1">
                    Vista general de tu sistema contable e inventario
                </p>
            </div>
            <div class="flex items-center gap-1 sm:gap-2 bg-white dark:bg-zinc-900 p-1 rounded-xl shadow-sm border border-slate-200 dark:border-zinc-800 w-full sm:w-auto justify-center">
                <button wire:click="$set('period', 'week')" 
                    class="px-3 sm:px-4 py-2 text-xs sm:text-sm font-medium rounded-lg transition-all {{ $period === 'week' ? 'bg-indigo-600 text-white shadow-lg dark:bg-zinc-700 dark:shadow-none' : 'text-slate-600 dark:text-zinc-400 hover:bg-slate-100 dark:hover:bg-zinc-800' }}">
                    Semana
                </button>
                <button wire:click="$set('period', 'month')" 
                    class="px-3 sm:px-4 py-2 text-xs sm:text-sm font-medium rounded-lg transition-all {{ $period === 'month' ? 'bg-indigo-600 text-white shadow-lg dark:bg-zinc-700 dark:shadow-none' : 'text-slate-600 dark:text-zinc-400 hover:bg-slate-100 dark:hover:bg-zinc-800' }}">
                    Mes
                </button>
                <button wire:click="$set('period', 'year')" 
                    class="px-3 sm:px-4 py-2 text-xs sm:text-sm font-medium rounded-lg transition-all {{ $period === 'year' ? 'bg-indigo-600 text-white shadow-lg dark:bg-zinc-700 dark:shadow-none' : 'text-slate-600 dark:text-zinc-400 hover:bg-slate-100 dark:hover:bg-zinc-800' }}">
                    Año
                </button>
            </div>
        </div>

        {{-- Financial Overview Cards --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
            {{-- Total Assets --}}
            <div class="relative overflow-hidden bg-gradient-to-br from-blue-500 to-blue-600 dark:from-zinc-900 dark:to-zinc-800 rounded-xl sm:rounded-2xl p-4 sm:p-6 text-white shadow-xl dark:shadow-none border border-transparent dark:border-zinc-700">
                <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-white/10 dark:bg-zinc-500/10 rounded-full blur-2xl"></div>
                <div class="relative">
                    <div class="flex items-center justify-between">
                        <span class="text-blue-100 dark:text-zinc-400 text-xs sm:text-sm font-medium">Total Activos</span>
                        <div class="p-1.5 sm:p-2 bg-white/20 dark:bg-zinc-800 rounded-lg hidden sm:block">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-xl sm:text-3xl font-bold mt-2 sm:mt-4">${{ number_format($this->totalAssets, 0, ',', '.') }}</p>
                    <p class="text-blue-200 dark:text-zinc-500 text-xs mt-1 sm:mt-2 hidden sm:block">Cuentas clase 1</p>
                </div>
            </div>

            {{-- Total Liabilities --}}
            <div class="relative overflow-hidden bg-gradient-to-br from-red-500 to-rose-600 dark:from-zinc-900 dark:to-zinc-800 rounded-xl sm:rounded-2xl p-4 sm:p-6 text-white shadow-xl dark:shadow-none border border-transparent dark:border-zinc-700">
                <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-white/10 dark:bg-zinc-500/10 rounded-full blur-2xl"></div>
                <div class="relative">
                    <div class="flex items-center justify-between">
                        <span class="text-red-100 dark:text-zinc-400 text-xs sm:text-sm font-medium">Total Pasivos</span>
                        <div class="p-1.5 sm:p-2 bg-white/20 dark:bg-zinc-800 rounded-lg hidden sm:block">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-xl sm:text-3xl font-bold mt-2 sm:mt-4">${{ number_format($this->totalLiabilities, 0, ',', '.') }}</p>
                    <p class="text-red-200 dark:text-zinc-500 text-xs mt-1 sm:mt-2 hidden sm:block">Cuentas clase 2</p>
                </div>
            </div>

            {{-- Total Equity --}}
            <div class="relative overflow-hidden bg-gradient-to-br from-emerald-500 to-teal-600 dark:from-zinc-900 dark:to-zinc-800 rounded-xl sm:rounded-2xl p-4 sm:p-6 text-white shadow-xl dark:shadow-none border border-transparent dark:border-zinc-700">
                <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-white/10 dark:bg-zinc-500/10 rounded-full blur-2xl"></div>
                <div class="relative">
                    <div class="flex items-center justify-between">
                        <span class="text-emerald-100 dark:text-zinc-400 text-xs sm:text-sm font-medium">Patrimonio</span>
                        <div class="p-1.5 sm:p-2 bg-white/20 dark:bg-zinc-800 rounded-lg hidden sm:block">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-xl sm:text-3xl font-bold mt-2 sm:mt-4">${{ number_format($this->totalEquity, 0, ',', '.') }}</p>
                    <p class="text-emerald-200 dark:text-zinc-500 text-xs mt-1 sm:mt-2 hidden sm:block">Cuentas clase 3</p>
                </div>
            </div>

            {{-- Net Income --}}
            <div class="relative overflow-hidden bg-gradient-to-br {{ $this->netIncome >= 0 ? 'from-violet-500 to-purple-600' : 'from-orange-500 to-amber-600' }} dark:from-zinc-900 dark:to-zinc-800 rounded-xl sm:rounded-2xl p-4 sm:p-6 text-white shadow-xl dark:shadow-none border border-transparent dark:border-zinc-700">
                <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-white/10 dark:bg-zinc-500/10 rounded-full blur-2xl"></div>
                <div class="relative">
                    <div class="flex items-center justify-between">
                        <span class="text-violet-100 dark:text-zinc-400 text-xs sm:text-sm font-medium">{{ $this->netIncome >= 0 ? 'Utilidad' : 'Pérdida' }}</span>
                        <div class="p-1.5 sm:p-2 bg-white/20 dark:bg-zinc-800 rounded-lg hidden sm:block">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-xl sm:text-3xl font-bold mt-2 sm:mt-4">${{ number_format(abs($this->netIncome), 0, ',', '.') }}</p>
                    <p class="text-violet-200 dark:text-zinc-500 text-xs mt-1 sm:mt-2 hidden sm:block">Ingresos - Gastos - Costos</p>
                </div>
            </div>
        </div>

        {{-- Inventory Overview --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
            {{-- Inventory Value --}}
            <div class="bg-white dark:bg-zinc-900 rounded-xl sm:rounded-2xl p-4 sm:p-6 border border-slate-200 dark:border-zinc-800 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2 sm:gap-4">
                    <div class="p-2 sm:p-3 bg-gradient-to-br from-cyan-400 to-cyan-600 dark:from-zinc-800 dark:to-zinc-700 rounded-lg sm:rounded-xl text-white">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-lg sm:text-2xl font-bold text-slate-900 dark:text-white">${{ number_format((float)($this->totalInventoryValue ?? 0), 0, ',', '.') }}</p>
                        <p class="text-xs sm:text-sm text-slate-500 dark:text-zinc-400">Inventario</p>
                    </div>
                </div>
            </div>

            {{-- Active Products --}}
            <div class="bg-white dark:bg-zinc-900 rounded-xl sm:rounded-2xl p-4 sm:p-6 border border-slate-200 dark:border-zinc-800 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2 sm:gap-4">
                    <div class="p-2 sm:p-3 bg-gradient-to-br from-indigo-400 to-indigo-600 dark:from-zinc-800 dark:to-zinc-700 rounded-lg sm:rounded-xl text-white">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-lg sm:text-2xl font-bold text-slate-900 dark:text-white">{{ $this->activeProducts }}</p>
                        <p class="text-xs sm:text-sm text-slate-500 dark:text-zinc-400">Productos</p>
                    </div>
                </div>
            </div>

            {{-- Period Movements --}}
            <div class="bg-white dark:bg-zinc-900 rounded-xl sm:rounded-2xl p-4 sm:p-6 border border-slate-200 dark:border-zinc-800 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2 sm:gap-4">
                    <div class="p-2 sm:p-3 bg-gradient-to-br from-amber-400 to-orange-500 dark:from-zinc-800 dark:to-zinc-700 rounded-lg sm:rounded-xl text-white">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-lg sm:text-2xl font-bold text-slate-900 dark:text-white">{{ $this->periodInventoryMovements }}</p>
                        <p class="text-xs sm:text-sm text-slate-500 dark:text-zinc-400">Movimientos</p>
                    </div>
                </div>
            </div>

            {{-- Low Stock Alert --}}
            <div class="bg-white dark:bg-zinc-900 rounded-xl sm:rounded-2xl p-4 sm:p-6 border {{ $this->lowStockCount > 0 ? 'border-red-300 dark:border-zinc-700' : 'border-slate-200 dark:border-zinc-800' }} shadow-sm hover:shadow-md transition-shadow">
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2 sm:gap-4">
                    <div class="p-2 sm:p-3 bg-gradient-to-br {{ $this->lowStockCount > 0 ? 'from-red-400 to-red-600 dark:from-zinc-700 dark:to-zinc-800' : 'from-green-400 to-green-600 dark:from-zinc-800 dark:to-zinc-700' }} rounded-lg sm:rounded-xl text-white">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-lg sm:text-2xl font-bold {{ $this->lowStockCount > 0 ? 'text-red-600 dark:text-white' : 'text-green-600 dark:text-zinc-400' }}">{{ $this->lowStockCount }}</p>
                        <p class="text-xs sm:text-sm text-slate-500 dark:text-zinc-500">Stock Bajo</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Main Content Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">
            {{-- Recent Diary Entries --}}
            <div class="lg:col-span-2 bg-white dark:bg-zinc-900 rounded-xl sm:rounded-2xl border border-slate-200 dark:border-zinc-800 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-zinc-200 dark:border-zinc-800">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-zinc-900 dark:text-white flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-500 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Últimos Asientos Contables
                        </h3>
                        <a href="{{ route('diary.index') }}" wire:navigate class="text-sm text-indigo-600 hover:text-indigo-800 dark:text-white dark:underline font-medium">
                            Ver todos →
                        </a>
                    </div>
                </div>
                <div class="divide-y divide-zinc-100 dark:divide-zinc-700">
                    @forelse($this->recentDiaryEntries as $entry)
                        <div class="p-4 hover:bg-zinc-50 dark:hover:bg-zinc-700/50 transition-colors">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-indigo-100 to-purple-100 dark:from-indigo-900/50 dark:to-purple-900/50 flex items-center justify-center">
                                        <span class="text-xs font-bold text-indigo-600 dark:text-indigo-400">{{ $entry->date->format('d') }}</span>
                                    </div>
                                    <div>
                                        <p class="font-medium text-zinc-900 dark:text-white text-sm">
                                            {{ $entry->nomenclature->code ?? 'N/A' }} - {{ Str::limit($entry->nomenclature->name ?? '', 30) }}
                                        </p>
                                        <p class="text-xs text-zinc-500 dark:text-zinc-400">
                                            {{ $entry->invoice_number ?? 'Sin referencia' }} • {{ $entry->date->format('M d, Y') }}
                                        </p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    @if($entry->debit > 0)
                                        <span class="text-blue-600 dark:text-white font-mono font-semibold">${{ number_format($entry->debit, 0, ',', '.') }}</span>
                                        <p class="text-xs text-zinc-400">Débito</p>
                                    @else
                                        <span class="text-green-600 dark:text-white font-mono font-semibold">${{ number_format($entry->credit, 0, ',', '.') }}</span>
                                        <p class="text-xs text-zinc-400">Crédito</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center text-zinc-500">
                            <p>No hay asientos contables registrados</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Quick Actions & Stats --}}
            <div class="space-y-6">
                {{-- Quick Actions --}}
                <div class="bg-white dark:bg-zinc-800 rounded-2xl border border-zinc-200 dark:border-zinc-700 shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-4">Acciones Rápidas</h3>
                    <div class="grid grid-cols-2 gap-3">
                        <a href="{{ route('diary.index') }}" wire:navigate
                            class="flex flex-col items-center p-4 rounded-xl bg-gradient-to-br from-indigo-50 to-purple-50 dark:from-zinc-900 dark:to-zinc-800 hover:from-indigo-100 hover:to-purple-100 dark:hover:bg-zinc-700 transition-all group border border-transparent dark:border-zinc-700">
                            <div class="p-3 bg-indigo-500 dark:bg-zinc-800 rounded-lg text-white mb-2 group-hover:scale-110 transition-transform">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                            </div>
                            <span class="text-xs font-medium text-zinc-700 dark:text-zinc-300 text-center">Nuevo Asiento</span>
                        </a>
                        <a href="{{ route('inventory.movements') }}" wire:navigate
                            class="flex flex-col items-center p-4 rounded-xl bg-gradient-to-br from-emerald-50 to-teal-50 dark:from-zinc-900 dark:to-zinc-800 hover:from-emerald-100 hover:to-teal-100 dark:hover:bg-zinc-700 transition-all group border border-transparent dark:border-zinc-700">
                            <div class="p-3 bg-emerald-500 dark:bg-zinc-800 rounded-lg text-white mb-2 group-hover:scale-110 transition-transform">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                                </svg>
                            </div>
                            <span class="text-xs font-medium text-zinc-700 dark:text-zinc-300 text-center">Movimiento</span>
                        </a>
                        <a href="{{ route('balance.index') }}" wire:navigate
                            class="flex flex-col items-center p-4 rounded-xl bg-gradient-to-br from-blue-50 to-cyan-50 dark:from-zinc-900 dark:to-zinc-800 hover:from-blue-100 hover:to-cyan-100 dark:hover:bg-zinc-700 transition-all group border border-transparent dark:border-zinc-700">
                            <div class="p-3 bg-blue-500 dark:bg-zinc-800 rounded-lg text-white mb-2 group-hover:scale-110 transition-transform">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                            </div>
                            <span class="text-xs font-medium text-zinc-700 dark:text-zinc-300 text-center">Balance</span>
                        </a>
                        <a href="{{ route('results.index') }}" wire:navigate
                            class="flex flex-col items-center p-4 rounded-xl bg-gradient-to-br from-amber-50 to-orange-50 dark:from-zinc-900 dark:to-zinc-800 hover:from-amber-100 hover:to-orange-100 dark:hover:bg-zinc-700 transition-all group border border-transparent dark:border-zinc-700">
                            <div class="p-3 bg-amber-500 dark:bg-zinc-800 rounded-lg text-white mb-2 group-hover:scale-110 transition-transform">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                </svg>
                            </div>
                            <span class="text-xs font-medium text-zinc-700 dark:text-zinc-300 text-center">Resultados</span>
                        </a>
                    </div>
                </div>

                {{-- Low Stock Alerts --}}
                @if($this->lowStockCount > 0)
                <div class="bg-gradient-to-br from-red-50 to-orange-50 dark:from-zinc-900 dark:to-zinc-800 rounded-2xl border border-red-200 dark:border-zinc-700 p-6">
                    <div class="flex items-center gap-2 mb-4">
                        <span class="flex h-3 w-3 relative">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 dark:bg-zinc-500 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500 dark:bg-zinc-400"></span>
                        </span>
                        <h3 class="font-semibold text-red-800 dark:text-white">Alertas de Stock Bajo</h3>
                    </div>
                    <div class="space-y-3">
                        @foreach($this->lowStockProducts as $product)
                            <div class="flex items-center justify-between p-3 bg-white/60 dark:bg-zinc-800/60 rounded-lg">
                                <div>
                                    <p class="font-medium text-zinc-900 dark:text-white text-sm">{{ $product->name }}</p>
                                    <p class="text-xs text-zinc-500">Mín: {{ number_format((float)($product->min_stock ?? 0), 0) }}</p>
                                </div>
                                <flux:badge color="red" class="dark:bg-zinc-700 dark:text-white dark:border-zinc-600">{{ number_format((float)($product->current_stock ?? 0), 0) }}</flux:badge>
                            </div>
                        @endforeach
                    </div>
                    <a href="{{ route('inventory.products') }}" wire:navigate class="block mt-4 text-center text-sm text-red-600 hover:text-red-800 dark:text-white dark:underline font-medium">
                        Ver todos los productos →
                    </a>
                </div>
                @endif
            </div>
        </div>

        {{-- Recent Inventory Movements --}}
        <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-slate-200 dark:border-zinc-800 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-zinc-200 dark:border-zinc-800">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-zinc-900 dark:text-white flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-500 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                        Últimos Movimientos de Inventario
                    </h3>
                    <a href="{{ route('inventory.movements') }}" wire:navigate class="text-sm text-emerald-600 hover:text-emerald-800 dark:text-white dark:underline font-medium">
                        Ver todos →
                    </a>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-zinc-50 dark:bg-zinc-700/50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase">Fecha</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase">Tipo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase">Producto</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase">Cantidad</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase">Costo Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100 dark:divide-zinc-700">
                        @forelse($this->recentMovements as $movement)
                            <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-700/50 transition-colors">
                                <td class="px-6 py-4 text-sm text-zinc-500 dark:text-zinc-400">
                                    {{ $movement->movement_date->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $typeColors = [
                                            'entrada' => 'green',
                                            'salida' => 'red',
                                            'ajuste_positivo' => 'blue',
                                            'ajuste_negativo' => 'amber',
                                        ];
                                    @endphp
                                    <flux:badge size="sm" color="{{ $typeColors[$movement->type] ?? 'zinc' }}" class="dark:bg-zinc-800 dark:text-white dark:border-zinc-700">
                                        {{ $movement->type_label }}
                                    </flux:badge>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="font-medium text-zinc-900 dark:text-white text-sm">{{ $movement->product->name }}</p>
                                    <p class="text-xs text-zinc-500 font-mono">{{ $movement->product->code }}</p>
                                </td>
                                <td class="px-6 py-4 text-right font-mono {{ $movement->is_entry ? 'text-emerald-600 dark:text-white' : 'text-red-600 dark:text-zinc-400' }}">
                                    {{ $movement->is_entry ? '+' : '-' }}{{ number_format((float)($movement->quantity ?? 0), 2) }}
                                </td>
                                <td class="px-6 py-4 text-right font-mono font-semibold text-zinc-900 dark:text-white">
                                    ${{ number_format((float)($movement->total_cost ?? 0), 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-zinc-500">
                                    No hay movimientos de inventario registrados
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Footer Stats --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-gradient-to-br from-zinc-100 to-zinc-200 dark:from-zinc-800 dark:to-zinc-900 rounded-xl p-4 text-center">
                <p class="text-2xl font-bold text-zinc-900 dark:text-white">{{ $this->accountCounts['nomenclatures'] }}</p>
                <p class="text-xs text-zinc-500 dark:text-zinc-400">Cuentas PUC</p>
            </div>
            <div class="bg-gradient-to-br from-zinc-100 to-zinc-200 dark:from-zinc-800 dark:to-zinc-900 rounded-xl p-4 text-center">
                <p class="text-2xl font-bold text-zinc-900 dark:text-white">{{ $this->accountCounts['diary_entries'] }}</p>
                <p class="text-xs text-zinc-500 dark:text-zinc-400">Asientos Contables</p>
            </div>
            <div class="bg-gradient-to-br from-zinc-100 to-zinc-200 dark:from-zinc-800 dark:to-zinc-900 rounded-xl p-4 text-center">
                <p class="text-2xl font-bold text-zinc-900 dark:text-white">{{ $this->totalProducts }}</p>
                <p class="text-xs text-zinc-500 dark:text-zinc-400">Productos</p>
            </div>
            <div class="bg-gradient-to-br from-zinc-100 to-zinc-200 dark:from-zinc-800 dark:to-zinc-900 rounded-xl p-4 text-center">
                <p class="text-2xl font-bold text-zinc-900 dark:text-white">${{ number_format((float)($this->periodCostOfSales ?? 0), 0, ',', '.') }}</p>
                <p class="text-xs text-zinc-500 dark:text-zinc-400">Costo de Ventas (Período)</p>
            </div>
        </div>

    </div>
</div>
