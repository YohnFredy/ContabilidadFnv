<div class="py-6 sm:py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-4 sm:space-y-6">
        {{-- Header --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-slate-900 dark:text-white">Dashboard de Inventario</h1>
                <p class="text-sm text-slate-500 dark:text-zinc-400">Resumen y métricas del sistema de inventario perpetuo</p>
            </div>
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 w-full sm:w-auto">
                <flux:input wire:model.live="dateFrom" type="date" class="text-sm" />
                <span class="text-slate-500 dark:text-zinc-500 hidden sm:block">a</span>
                <flux:input wire:model.live="dateTo" type="date" class="text-sm" />
            </div>
        </div>

        {{-- Main Stats Cards --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
            {{-- Total Inventory Value --}}
            <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 dark:from-zinc-900 dark:to-zinc-800 rounded-xl p-4 sm:p-6 text-white shadow-lg dark:shadow-none border border-transparent dark:border-zinc-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-emerald-100 dark:text-zinc-400 text-xs sm:text-sm font-medium">Valor Inventario</p>
                        <p class="text-xl sm:text-3xl font-bold mt-1">${{ number_format($this->totalInventoryValue, 0) }}</p>
                    </div>
                    <div class="bg-white/20 dark:bg-zinc-800 rounded-full p-2 sm:p-3 hidden sm:block">
                        <svg class="w-6 h-6 sm:w-8 sm:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="mt-2 sm:mt-4 flex items-center text-xs sm:text-sm text-emerald-100 dark:text-zinc-500">
                    <span class="hidden sm:inline">{{ number_format($this->totalStockUnits, 0) }} unidades en stock</span>
                    <span class="sm:hidden">{{ number_format($this->totalStockUnits, 0) }} uds.</span>
                </div>
            </div>

            {{-- Products Count --}}
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 dark:from-zinc-900 dark:to-zinc-800 rounded-xl p-4 sm:p-6 text-white shadow-lg dark:shadow-none border border-transparent dark:border-zinc-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 dark:text-zinc-400 text-xs sm:text-sm font-medium">Productos Activos</p>
                        <p class="text-xl sm:text-3xl font-bold mt-1">{{ $this->activeProducts }}</p>
                    </div>
                    <div class="bg-white/20 dark:bg-zinc-800 rounded-full p-2 sm:p-3 hidden sm:block">
                        <svg class="w-6 h-6 sm:w-8 sm:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                </div>
                <div class="mt-2 sm:mt-4 flex items-center text-xs sm:text-sm text-blue-100 dark:text-zinc-500">
                    <span class="hidden sm:inline">de {{ $this->totalProducts }} productos totales</span>
                    <span class="sm:hidden">de {{ $this->totalProducts }} total</span>
                </div>
            </div>

            {{-- Period Entries --}}
            <div class="bg-gradient-to-br from-violet-500 to-violet-600 dark:from-zinc-900 dark:to-zinc-800 rounded-xl p-6 text-white shadow-lg dark:shadow-none border border-transparent dark:border-zinc-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-violet-100 dark:text-zinc-400 text-sm font-medium">Entradas del Período</p>
                        <p class="text-3xl font-bold mt-1">${{ number_format($this->totalEntries, 0) }}</p>
                    </div>
                    <div class="bg-white/20 dark:bg-zinc-800 rounded-full p-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"/>
                        </svg>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-sm text-violet-100 dark:text-zinc-500">
                    <span>{{ $this->movementCount }} movimientos</span>
                </div>
            </div>

            {{-- Cost of Goods Sold --}}
            <div class="bg-gradient-to-br from-amber-500 to-orange-500 dark:from-zinc-900 dark:to-zinc-800 rounded-xl p-6 text-white shadow-lg dark:shadow-none border border-transparent dark:border-zinc-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-amber-100 dark:text-zinc-400 text-sm font-medium">Costo de Ventas</p>
                        <p class="text-3xl font-bold mt-1">${{ number_format($this->costOfGoodsSold, 0) }}</p>
                    </div>
                    <div class="bg-white/20 dark:bg-zinc-800 rounded-full p-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"/>
                        </svg>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-sm text-amber-100 dark:text-zinc-500">
                    <span>Salidas valuadas a CPP</span>
                </div>
            </div>
        </div>

        {{-- Content Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Low Stock Products --}}
            <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-700 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-zinc-200 dark:border-zinc-700 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="text-amber-500">⚠️</span>
                        <h3 class="font-semibold text-gray-900 dark:text-gray-100">Productos con Stock Bajo</h3>
                    </div>
                    <a href="{{ route('inventory.products') }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline" wire:navigate>
                        Ver todos →
                    </a>
                </div>
                <div class="divide-y divide-zinc-200 dark:divide-zinc-700">
                    @forelse ($this->lowStockProducts as $product)
                        <div class="px-6 py-3 flex items-center justify-between hover:bg-zinc-50 dark:hover:bg-zinc-800/50">
                            <div>
                                <p class="text-sm font-medium text-zinc-900 dark:text-zinc-100">{{ $product->name }}</p>
                                <p class="text-xs text-zinc-500 font-mono">{{ $product->code }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-mono font-semibold text-amber-600 dark:text-zinc-300">
                                    {{ number_format($product->current_stock, 2) }}
                                </p>
                                <p class="text-xs text-zinc-500">Mín: {{ number_format($product->min_stock, 2) }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-8 text-center text-zinc-500 dark:text-zinc-400">
                            <svg class="w-10 h-10 mx-auto mb-2 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-sm">Todos los productos tienen stock suficiente</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Recent Movements --}}
            <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-700 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-zinc-200 dark:border-zinc-700 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span>📋</span>
                        <h3 class="font-semibold text-gray-900 dark:text-gray-100">Movimientos Recientes</h3>
                    </div>
                    <a href="{{ route('inventory.movements') }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline" wire:navigate>
                        Ver todos →
                    </a>
                </div>
                <div class="divide-y divide-zinc-200 dark:divide-zinc-700">
                    @forelse ($this->recentMovements as $movement)
                        <div class="px-6 py-3 flex items-center justify-between hover:bg-zinc-50 dark:hover:bg-zinc-800/50">
                            <div class="flex items-center gap-3">
                                @php
                                    $typeColors = [
                                        'entrada' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400',
                                        'salida' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                                        'ajuste_positivo' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                                        'ajuste_negativo' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',
                                    ];
                                    $typeIcons = [
                                        'entrada' => '↑',
                                        'salida' => '↓',
                                        'ajuste_positivo' => '+',
                                        'ajuste_negativo' => '-',
                                    ];
                                @endphp
                                <span class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold {{ $typeColors[$movement->type] ?? 'bg-zinc-100' }}">
                                    {{ $typeIcons[$movement->type] ?? '?' }}
                                </span>
                                <div>
                                    <p class="text-sm font-medium text-zinc-900 dark:text-zinc-100">{{ $movement->product->name }}</p>
                                    <p class="text-xs text-zinc-500">{{ $movement->movement_date->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-mono font-medium {{ $movement->is_entry ? 'text-emerald-600 dark:text-white' : 'text-red-600 dark:text-zinc-400' }}">
                                    {{ $movement->is_entry ? '+' : '-' }}{{ number_format($movement->quantity, 2) }}
                                </p>
                                <p class="text-xs text-zinc-500 font-mono">${{ number_format($movement->total_cost, 2) }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-8 text-center text-zinc-500 dark:text-zinc-400">
                            <svg class="w-10 h-10 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <p class="text-sm">No hay movimientos registrados</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Top Products by Value --}}
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-700 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-zinc-200 dark:border-zinc-700">
                <div class="flex items-center gap-2">
                    <span>📊</span>
                    <h3 class="font-semibold text-gray-900 dark:text-gray-100">Productos con Mayor Valor en Inventario</h3>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                    <thead class="bg-zinc-50 dark:bg-zinc-800">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 uppercase dark:text-zinc-400">Producto</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-zinc-500 uppercase dark:text-zinc-400">Stock</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-zinc-500 uppercase dark:text-zinc-400">Costo Prom.</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-zinc-500 uppercase dark:text-zinc-400">Valor Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                        @forelse ($this->topProductsByValue as $product)
                            <tr>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-indigo-500 to-purple-500 dark:from-zinc-800 dark:to-zinc-700 flex items-center justify-center text-white font-bold text-sm">
                                            {{ substr($product->name, 0, 2) }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-zinc-900 dark:text-zinc-100">{{ $product->name }}</p>
                                            <p class="text-xs text-zinc-500 font-mono">{{ $product->code }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right font-mono text-sm text-zinc-900 dark:text-zinc-100">
                                    {{ number_format($product->current_stock, 2) }}
                                </td>
                                <td class="px-6 py-4 text-right font-mono text-sm text-zinc-900 dark:text-zinc-100">
                                    ${{ number_format($product->current_avg_cost, 2) }}
                                </td>
                                <td class="px-6 py-4 text-right font-mono text-sm font-semibold text-emerald-600 dark:text-white">
                                    ${{ number_format($product->inventory_value, 2) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-zinc-500">
                                    No hay productos con stock
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-700 shadow-sm p-6">
            <h3 class="font-semibold text-gray-900 dark:text-gray-100 mb-4">Acciones Rápidas</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <a href="{{ route('inventory.products') }}" wire:navigate
                    class="flex flex-col items-center p-4 rounded-lg border border-zinc-200 dark:border-zinc-700 hover:bg-zinc-50 dark:hover:bg-zinc-800 transition-colors">
                    <svg class="w-8 h-8 text-blue-500 dark:text-white mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    <span class="text-sm font-medium text-zinc-700 dark:text-zinc-300">Ver Productos</span>
                </a>
                <a href="{{ route('inventory.movements') }}" wire:navigate
                    class="flex flex-col items-center p-4 rounded-lg border border-zinc-200 dark:border-zinc-700 hover:bg-zinc-50 dark:hover:bg-zinc-800 transition-colors">
                    <svg class="w-8 h-8 text-emerald-500 dark:text-white mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                    </svg>
                    <span class="text-sm font-medium text-zinc-700 dark:text-zinc-300">Movimientos</span>
                </a>
                <a href="{{ route('inventory.movements') }}" wire:navigate
                    class="flex flex-col items-center p-4 rounded-lg border border-zinc-200 dark:border-zinc-700 hover:bg-zinc-50 dark:hover:bg-zinc-800 transition-colors">
                    <svg class="w-8 h-8 text-violet-500 dark:text-white mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="text-sm font-medium text-zinc-700 dark:text-zinc-300">Nueva Entrada</span>
                </a>
                <a href="{{ route('inventory.movements') }}" wire:navigate
                    class="flex flex-col items-center p-4 rounded-lg border border-zinc-200 dark:border-zinc-700 hover:bg-zinc-50 dark:hover:bg-zinc-800 transition-colors">
                    <svg class="w-8 h-8 text-amber-500 dark:text-white mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="text-sm font-medium text-zinc-700 dark:text-zinc-300">Nueva Salida</span>
                </a>
            </div>
        </div>
    </div>
</div>
