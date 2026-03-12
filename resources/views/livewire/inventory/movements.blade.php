<div class="py-6 sm:py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        {{-- Header --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-slate-900 dark:text-white">Movimientos de Inventario</h1>
                <p class="text-sm text-slate-500 dark:text-zinc-400">Registro de entradas, salidas y ajustes</p>
            </div>
            <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
                @can('registrar ventas')
                    <flux:button wire:click="openCalculator" icon="calculator" variant="ghost" class="w-full sm:w-auto">Registrar Venta</flux:button>
                @endcan
                @can('crear movimientos de inventario')
                    <flux:button wire:click="create" icon="plus" variant="primary" class="w-full sm:w-auto">Nuevo Movimiento</flux:button>
                @endcan
            </div>
        </div>

        {{-- Filters --}}
        <div class="bg-white dark:bg-zinc-900 rounded-lg border border-slate-200 dark:border-zinc-700 p-4">
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 sm:gap-4">
                <flux:input wire:model.live="search" icon="magnifying-glass" placeholder="Buscar..." class="col-span-2 sm:col-span-1" />
                
                <select wire:model.live="productFilter"
                    class="w-full rounded-lg border-0 py-2 px-3 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm dark:bg-zinc-800 dark:text-white dark:ring-zinc-600">
                    <option value="">Todos los productos</option>
                    @foreach ($products as $prod)
                        <option value="{{ $prod->id }}">{{ $prod->code }} - {{ $prod->name }}</option>
                    @endforeach
                </select>

                <select wire:model.live="typeFilter"
                    class="w-full rounded-lg border-0 py-2 px-3 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm dark:bg-zinc-800 dark:text-white dark:ring-zinc-600">
                    <option value="">Todos los tipos</option>
                    @foreach ($types as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                    @endforeach
                </select>

                <flux:input wire:model.live="dateFrom" type="date" label="" />
                <flux:input wire:model.live="dateTo" type="date" label="" />
            </div>
        </div>

        {{-- Movements Table --}}
        <div class="overflow-hidden bg-white dark:bg-zinc-900 shadow-sm rounded-lg border border-slate-200 dark:border-zinc-700">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 dark:divide-zinc-700">
                    <thead class="bg-slate-50 dark:bg-zinc-800">
                        <tr>
                            <th scope="col" class="px-3 sm:px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider dark:text-zinc-400">
                                Fecha</th>
                            <th scope="col" class="px-3 sm:px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider dark:text-zinc-400">
                                Tipo</th>
                            <th scope="col" class="px-3 sm:px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider dark:text-zinc-400">
                                Producto</th>
                            <th scope="col" class="px-3 sm:px-4 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider dark:text-zinc-400">
                                Cant.</th>
                            <th scope="col" class="px-3 sm:px-4 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider dark:text-zinc-400 hidden sm:table-cell">
                                Costo Unit.</th>
                            <th scope="col" class="px-3 sm:px-4 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider dark:text-zinc-400 hidden md:table-cell">
                                Costo Total</th>
                            <th scope="col" class="px-3 sm:px-4 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider dark:text-zinc-400 hidden lg:table-cell">
                                Stock Result.</th>
                            <th scope="col" class="px-3 sm:px-4 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider dark:text-zinc-400 hidden xl:table-cell">
                                CPP Result.</th>
                            <th scope="col" class="px-3 sm:px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider dark:text-zinc-400 hidden lg:table-cell">
                                Referencia</th>
                            <th scope="col" class="px-3 sm:px-4 py-3 text-center text-xs font-medium text-slate-500 uppercase tracking-wider dark:text-zinc-400">
                                Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-200 dark:bg-zinc-900 dark:divide-zinc-700">
                        @forelse ($movements as $movement)
                            <tr wire:key="{{ $movement->id }}">
                                <td class="px-3 sm:px-4 py-3 whitespace-nowrap text-sm text-slate-600 dark:text-zinc-400">
                                    <div>{{ $movement->movement_date->format('d/m/Y') }}</div>
                                    <div class="text-xs">{{ $movement->movement_date->format('H:i') }}</div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
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
                                <td class="px-3 sm:px-4 py-3 whitespace-nowrap">
                                    <div class="text-sm font-medium text-slate-900 dark:text-white">{{ $movement->product->name }}</div>
                                    <div class="text-xs text-slate-500 dark:text-zinc-500 font-mono">{{ $movement->product->code }}</div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-right font-mono {{ $movement->is_entry ? 'text-emerald-600 dark:text-white' : 'text-red-600 dark:text-zinc-400' }}">
                                    {{ $movement->is_entry ? '+' : '-' }}{{ number_format($movement->quantity, 2) }}
                                </td>
                                <td class="px-3 sm:px-4 py-3 whitespace-nowrap text-sm text-right font-mono text-slate-600 dark:text-zinc-400 hidden sm:table-cell">
                                    ${{ number_format($movement->unit_cost, 4) }}
                                </td>
                                <td class="px-3 sm:px-4 py-3 whitespace-nowrap text-sm text-right font-mono font-medium {{ $movement->is_entry ? 'text-emerald-600 dark:text-white' : 'text-rose-600 dark:text-zinc-400' }} hidden md:table-cell">
                                    ${{ number_format($movement->total_cost, 2) }}
                                </td>
                                <td class="px-3 sm:px-4 py-3 whitespace-nowrap text-sm text-right font-mono text-slate-900 dark:text-white hidden lg:table-cell">
                                    {{ number_format($movement->stock_after, 2) }}
                                </td>
                                <td class="px-3 sm:px-4 py-3 whitespace-nowrap text-sm text-right font-mono text-slate-900 dark:text-white hidden xl:table-cell">
                                    ${{ number_format($movement->avg_cost_after, 4) }}
                                </td>
                                <td class="px-3 sm:px-4 py-3 whitespace-nowrap text-sm text-slate-500 dark:text-zinc-500 hidden lg:table-cell">
                                    {{ $movement->reference ?? '—' }}
                                    @if($movement->notes)
                                        <span title="{{ $movement->notes }}" class="cursor-help text-xs">💬</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-center">
                                    <div class="flex justify-center gap-1">
                                        @can('editar movimientos de inventario')
                                            <flux:button wire:click="edit({{ $movement->id }})"
                                                wire:confirm="⚠️ ADVERTENCIA IMPORTANTE\n\nEditar este movimiento afectará:\n• El stock y costo promedio del producto\n• Los cálculos contables relacionados\n\nSi este movimiento generó asientos en el Libro Diario, deberá rectificarlos manualmente.\n\n¿Desea continuar con la edición?"
                                                icon="pencil-square" size="sm" variant="ghost" title="Editar" />
                                        @endcan
                                        @can('eliminar movimientos de inventario')
                                            <flux:button wire:click="delete({{ $movement->id }})"
                                                wire:confirm="⚠️ ADVERTENCIA IMPORTANTE\n\nEliminar este movimiento afectará:\n• El stock y costo promedio del producto\n• Todos los movimientos posteriores serán recalculados\n\nSi este movimiento generó asientos en el Libro Diario (ej: ventas), deberá eliminar o rectificar esos asientos manualmente.\n\n¿Está seguro de que desea eliminar este movimiento?"
                                                icon="trash" size="sm" variant="danger" title="Eliminar" />
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="px-6 py-12 text-center bg-slate-50/50 dark:bg-zinc-800/50">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-12 h-12 text-slate-300 dark:text-zinc-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                        </svg>
                                        <p class="text-slate-500 dark:text-zinc-400 text-sm">No hay movimientos registrados</p>
                                        @can('crear movimientos de inventario')
                                        <flux:button wire:click="create" size="sm" variant="ghost" class="mt-2">
                                            Registrar primer movimiento
                                        </flux:button>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination --}}
        {{ $movements->links() }}

        {{-- Movement Modal (Create/Edit) --}}
        <flux:modal wire:model="showModal" class="w-full sm:max-w-[500px]">
            <div class="space-y-6">
                <div>
                    <h2 class="text-lg font-semibold text-slate-900 dark:text-white">
                        {{ $editMode ? 'Editar Movimiento' : 'Nuevo Movimiento' }}
                    </h2>
                    <p class="mt-1 text-sm text-slate-500 dark:text-zinc-400">
                        {{ $editMode ? 'Modifica los datos del movimiento. El stock y CPP se recalcularán.' : 'Registra una entrada, salida o ajuste de inventario' }}
                    </p>
                </div>

                @if($editMode)
                    <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                        <div class="flex items-start gap-3">
                            <span class="text-red-500 text-xl">⚠️</span>
                            <div class="text-sm text-red-700 dark:text-red-300">
                                <p class="font-semibold mb-2">ADVERTENCIA - Impacto Contable</p>
                                <ul class="space-y-1 text-xs">
                                    <li>• El stock y costo promedio (CPP) se recalcularán automáticamente</li>
                                    <li>• Todos los movimientos posteriores a este también serán afectados</li>
                                    <li>• <strong>Si este movimiento generó asientos en el Libro Diario</strong> (ej: registros de venta), deberá rectificarlos manualmente</li>
                                </ul>
                                <p class="mt-2 text-xs font-medium">Asegúrese de revisar y ajustar los asientos contables relacionados después de guardar.</p>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="grid grid-cols-2 gap-4">
                    {{-- Shared Fields --}}
                    <div class="col-span-2 sm:col-span-1">
                        <flux:label>Tipo de Movimiento *</flux:label>
                        <select wire:model.live="type" @if($editMode) disabled @endif
                            class="w-full rounded-lg border-0 py-2 px-3 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm dark:bg-zinc-800 dark:text-white dark:ring-zinc-600 disabled:opacity-50">
                            @foreach ($types as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>

                    </div>

                    <div class="col-span-2 sm:col-span-1">
                        <flux:input wire:model="movement_date" label="Fecha *" type="datetime-local" />
                        @error('movement_date') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="col-span-2">
                        <flux:input wire:model="reference" label="Referencia" placeholder="Factura, orden, etc." />
                    </div>

                    {{-- Multi-item Header for Entrada --}}
                    @if($type === 'entrada' && !$editMode)
                        <div class="col-span-2 border-t border-slate-200 dark:border-zinc-700 pt-4 mt-2">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="font-semibold text-slate-900 dark:text-white">Productos a Ingresar</h3>
                                <flux:button wire:click="addEntryItem" icon="plus" size="sm" variant="ghost">Agregar Producto</flux:button>
                            </div>

                            <div class="space-y-4">
                                @foreach($entryItems as $index => $item)
                                    <div class="p-4 bg-slate-50 dark:bg-zinc-800/50 rounded-xl border border-slate-200 dark:border-zinc-700 space-y-4 relative" wire:key="entry-item-{{ $index }}">
                                        @if(count($entryItems) > 1)
                                            <button wire:click="removeEntryItem({{ $index }})" class="absolute top-2 right-2 text-slate-400 hover:text-rose-500 transition-colors">
                                                <flux:icon.x-mark size="sm" />
                                            </button>
                                        @endif

                                        <div class="grid grid-cols-1 gap-4">
                                            <div>
                                                <flux:label>Producto *</flux:label>
                                                <select wire:model.live="entryItems.{{ $index }}.product_id"
                                                    class="w-full rounded-lg border-0 py-2 px-3 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm dark:bg-zinc-800 dark:text-white dark:ring-zinc-600">
                                                    <option value="">Seleccionar...</option>
                                                    @foreach ($products as $prod)
                                                        <option value="{{ $prod->id }}">{{ $prod->code }} - {{ $prod->name }}</option>
                                                    @endforeach
                                                </select>
                                                @error("entryItems.{$index}.product_id") <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                                            </div>

                                            <div class="grid grid-cols-2 gap-4">
                                                <div>
                                                    <flux:input wire:model.live="entryItems.{{ $index }}.quantity" label="Cantidad *" type="number" step="0.0001" min="0.0001" placeholder="0" />
                                                    @error("entryItems.{$index}.quantity") <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                                                </div>
                                                <div>
                                                    <flux:input wire:model.live="entryItems.{{ $index }}.unit_cost" label="Costo Unitario *" type="number" step="0.01" min="0" placeholder="0.00" />
                                                    @error("entryItems.{$index}.unit_cost") <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                                                </div>
                                            </div>

                                            @if(!empty($item['quantity']) && !empty($item['unit_cost']))
                                                <div class="flex justify-end text-sm text-slate-500 dark:text-zinc-400 font-mono">
                                                    Subtotal: ${{ number_format((float)$item['quantity'] * (float)$item['unit_cost'], 2) }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            {{-- Total Display --}}
                            <div class="mt-6 p-4 bg-emerald-50 dark:bg-zinc-800 rounded-xl border border-emerald-100 dark:border-zinc-700 flex justify-between items-center">
                                <span class="font-semibold text-emerald-800 dark:text-zinc-300 uppercase text-xs tracking-wider">Total Factura</span>
                                <span class="text-xl font-bold text-emerald-700 dark:text-white font-mono underline decoration-emerald-200">
                                    ${{ number_format($this->entryTotal, 2) }}
                                </span>
                            </div>
                        </div>
                    @else
                        {{-- Single Item (Edit or other types) --}}
                        <div class="col-span-2 space-y-4">
                            <div>
                                <flux:label>Producto *</flux:label>
                                <select wire:model.live="product_id"
                                    class="w-full rounded-lg border-0 py-2 px-3 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm dark:bg-zinc-800 dark:text-white dark:ring-zinc-600">
                                    <option value="">Seleccionar producto...</option>
                                    @foreach ($products as $prod)
                                        <option value="{{ $prod->id }}">{{ $prod->code }} - {{ $prod->name }}</option>
                                    @endforeach
                                </select>
                                @error('product_id') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                                
                                @if($this->selectedProduct)
                                    <div class="mt-2 p-2 bg-slate-100 dark:bg-zinc-800 rounded text-xs">
                                        <span class="text-slate-600 dark:text-zinc-400">Stock actual:</span>
                                        <span class="font-mono font-medium text-slate-900 dark:text-white">{{ number_format($this->selectedProduct->current_stock, 2) }}</span>
                                        <span class="mx-2 text-slate-300 dark:text-zinc-600">|</span>
                                        <span class="text-slate-600 dark:text-zinc-400">CPP:</span>
                                        <span class="font-mono font-medium text-slate-900 dark:text-white">${{ number_format($this->selectedProduct->current_avg_cost, 4) }}</span>
                                    </div>
                                @endif
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <flux:input wire:model="quantity" label="Cantidad *" type="number" step="0.0001" min="0.0001" placeholder="0" />
                                    @error('quantity') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    @if(in_array($type, ['entrada', 'ajuste_positivo']))
                                        <flux:input wire:model="unit_cost" label="Costo Unitario *" type="number" step="0.000001" min="0" placeholder="0.00" />
                                        @error('unit_cost') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                                    @else
                                        <div>
                                            <flux:label>Costo Unitario</flux:label>
                                            <div class="py-2 px-3 rounded-lg bg-slate-100 dark:bg-zinc-800 text-sm font-mono text-slate-700 dark:text-zinc-300">
                                                ${{ $this->selectedProduct ? number_format($this->selectedProduct->current_avg_cost, 4) : '0.0000' }}
                                                <span class="text-xs text-slate-500 dark:text-zinc-500 ml-1">(CPP automático)</span>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="col-span-2">
                        <flux:label>Notas</flux:label>
                        <textarea wire:model="notes" rows="2"
                            class="w-full rounded-lg border-0 py-2 px-3 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm dark:bg-zinc-800 dark:text-white dark:ring-zinc-600"
                            placeholder="Notas adicionales..."></textarea>
                    </div>
                </div>

                <div class="flex justify-end gap-2 pt-4 border-t border-slate-200 dark:border-zinc-700">
                    <flux:button wire:click="$set('showModal', false)" variant="ghost">Cancelar</flux:button>
                    <flux:button wire:click="checkDateAndSave" variant="primary">
                        {{ $editMode ? 'Actualizar' : 'Registrar' }}
                    </flux:button>
                </div>
            </div>
        </flux:modal>

        {{-- Cost Calculator / Sale Registration Modal --}}
        <flux:modal wire:model="showCalculator" class="w-full sm:max-w-3xl max-h-[90vh] overflow-y-auto">
            <div class="space-y-6">
                {{-- Header --}}
                <div class="border-b border-slate-200 dark:border-zinc-800 pb-4">
                    <h2 class="text-lg sm:text-xl font-bold text-slate-900 dark:text-white flex items-center gap-2">
                        <span class="bg-gradient-to-r from-emerald-500 to-teal-500 dark:from-zinc-800 dark:to-zinc-700 text-white p-2 rounded-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                        </span>
                        Registrar Venta (Costo de Mercancía)
                    </h2>
                    <p class="mt-2 text-sm text-slate-500 dark:text-zinc-400">
                        Selecciona los productos vendidos. Se registrará automáticamente la salida de inventario y el asiento contable.
                    </p>
                </div>

                {{-- Date and Reference --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 p-4 bg-slate-50 dark:bg-zinc-800/50 rounded-lg">
                    <div>
                        <flux:input wire:model="calculatorDate" type="date" label="Fecha de la Venta *" />
                    </div>
                    <div>
                        <flux:input wire:model="calculatorReference" label="Referencia (Factura)" placeholder="Ej. FV-001234" />
                    </div>
                </div>

                {{-- Products Section --}}
                {{-- Products Section --}}
                <div>
                    <div class="flex items-center justify-between mb-3 px-1">
                        <h3 class="font-semibold text-slate-900 dark:text-white">Productos Vendidos</h3>
                        <flux:button wire:click="addCalculatorItem" icon="plus" size="sm" variant="ghost">
                            Agregar
                        </flux:button>
                    </div>
                    
                    <div class="space-y-3">
                        @forelse($calculatorItems as $index => $item)
                            <div class="flex flex-col sm:flex-row gap-3 items-start p-3 bg-white dark:bg-zinc-900 rounded-lg border border-slate-200 dark:border-zinc-700 shadow-sm" wire:key="calc-item-{{ $index }}">
                                <div class="flex-1 w-full sm:w-auto">
                                    <label class="text-xs text-slate-500 dark:text-zinc-400 mb-1 block">Producto</label>
                                    <select wire:model.live="calculatorItems.{{ $index }}.product_id"
                                        class="w-full rounded-lg border-0 py-2 px-3 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm dark:bg-zinc-800 dark:text-white dark:ring-zinc-600">
                                        <option value="">Seleccionar...</option>
                                        @foreach ($products as $prod)
                                            <option value="{{ $prod->id }}">{{ $prod->code }} - {{ $prod->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="w-full sm:w-24">
                                    <label class="text-xs text-slate-500 dark:text-zinc-400 mb-1 block">Cantidad</label>
                                    <input type="number" 
                                        wire:model.live="calculatorItems.{{ $index }}.quantity"
                                        step="0.01" min="0"
                                        class="w-full rounded-lg border-0 py-2 px-3 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm dark:bg-zinc-800 dark:text-white dark:ring-zinc-600">
                                </div>
                                <div class="w-full sm:w-28 text-left sm:text-right">
                                    <label class="text-xs text-slate-500 dark:text-zinc-400 mb-1 block">CPP Unit.</label>
                                    @if(isset($this->calculatorItemsDetailed[$index]))
                                        <p class="font-mono text-sm text-slate-900 dark:text-white">
                                            ${{ number_format($this->calculatorItemsDetailed[$index]['unit_cost'], 2) }}
                                        </p>
                                    @else
                                        <p class="text-slate-400 dark:text-zinc-500 text-sm">$0.00</p>
                                    @endif
                                </div>
                                <div class="w-full sm:w-32 text-left sm:text-right">
                                    <label class="text-xs text-slate-500 dark:text-zinc-400 mb-1 block">Subtotal</label>
                                    @if(isset($this->calculatorItemsDetailed[$index]))
                                        <p class="font-mono font-semibold text-emerald-600 dark:text-white">
                                            ${{ number_format($this->calculatorItemsDetailed[$index]['subtotal'], 2) }}
                                        </p>
                                    @else
                                        <p class="text-slate-400 dark:text-zinc-500">$0.00</p>
                                    @endif
                                </div>
                                <div class="pt-1 sm:pt-5 w-full sm:w-auto flex justify-end">
                                    <button wire:click="removeCalculatorItem({{ $index }})" type="button"
                                        class="p-2 text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-900/20 rounded-lg transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8 bg-slate-50 dark:bg-zinc-800 rounded-lg border-2 border-dashed border-slate-200 dark:border-zinc-700">
                                <svg class="w-12 h-12 mx-auto mb-2 text-slate-300 dark:text-zinc-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                                <p class="text-sm text-slate-500 dark:text-zinc-400 mb-2">No hay productos agregados</p>
                                <flux:button wire:click="addCalculatorItem" icon="plus" size="sm" variant="primary">
                                    Agregar Producto
                                </flux:button>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Total & Accounting Preview --}}
                @if(count($calculatorItems) > 0 && $this->calculatorTotal > 0)
                    <div class="border-t border-slate-200 dark:border-zinc-800 pt-4 space-y-4">
                        {{-- Total --}}
                        <div class="flex justify-between items-center p-4 bg-gradient-to-r from-emerald-50 to-teal-50 dark:from-zinc-800 dark:to-zinc-800/50 rounded-lg border border-transparent dark:border-zinc-700">
                            <span class="text-base sm:text-lg font-semibold text-slate-700 dark:text-zinc-300">Costo Total de Venta:</span>
                            <span class="text-xl sm:text-2xl font-bold text-emerald-600 dark:text-white font-mono">
                                ${{ number_format($this->calculatorTotal, 2) }}
                            </span>
                        </div>

                        {{-- Accounting Preview --}}
                        <div class="p-4 bg-blue-50 dark:bg-zinc-800 rounded-lg border border-transparent dark:border-zinc-700">
                            <h4 class="text-sm font-semibold text-blue-800 dark:text-zinc-300 mb-3 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Asiento Contable que se generará:
                            </h4>
                            <div class="overflow-x-auto">
                                <table class="min-w-full text-sm">
                                    <thead>
                                        <tr class="text-left text-xs text-blue-600 dark:text-zinc-500 uppercase">
                                            <th class="pb-2">Código PUC</th>
                                            <th class="pb-2">Nombre Cuenta</th>
                                            <th class="pb-2 text-center">Tipo</th>
                                            <th class="pb-2 text-right">Valor</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-blue-200 dark:divide-zinc-700">
                                        <tr>
                                            <td class="py-2 font-mono font-semibold text-blue-900 dark:text-white">6135</td>
                                            <td class="py-2 text-blue-800 dark:text-zinc-400">Costo de mercancías vendidas</td>
                                            <td class="py-2 text-center">
                                                <span class="px-2 py-1 bg-blue-200 dark:bg-zinc-700 text-blue-800 dark:text-zinc-200 rounded text-xs font-medium">Débito</span>
                                            </td>
                                            <td class="py-2 text-right font-mono font-semibold text-blue-900 dark:text-white">
                                                ${{ number_format($this->calculatorTotal, 2) }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="py-2 font-mono font-semibold text-blue-900 dark:text-white">1435</td>
                                            <td class="py-2 text-blue-800 dark:text-zinc-400">Mercancías no fabricadas por la empresa</td>
                                            <td class="py-2 text-center">
                                                <span class="px-2 py-1 bg-green-200 dark:bg-zinc-700 text-green-800 dark:text-zinc-200 rounded text-xs font-medium">Crédito</span>
                                            </td>
                                            <td class="py-2 text-right font-mono font-semibold text-blue-900 dark:text-white">
                                                ${{ number_format($this->calculatorTotal, 2) }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- Info Box --}}
                        <div class="flex items-start gap-3 p-3 bg-amber-50 dark:bg-amber-900/20 rounded-lg text-sm">
                            <span class="text-amber-500 mt-0.5">ℹ️</span>
                            <div class="text-amber-700 dark:text-amber-300">
                                <p class="font-medium">Al procesar esta venta:</p>
                                <ul class="mt-1 space-y-1 text-xs">
                                    <li>• Se registrará la <strong>salida de inventario</strong> de cada producto</li>
                                    <li>• Se creará el <strong>asiento contable</strong> automáticamente</li>
                                    <li>• El costo se calcula usando el <strong>CPP actual</strong> de cada producto</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Actions --}}
                <div class="flex justify-end gap-2 pt-4 border-t border-slate-200 dark:border-zinc-700">
                    <flux:button wire:click="$set('showCalculator', false)" variant="ghost">Cancelar</flux:button>
                    @if(count($calculatorItems) > 0 && $this->calculatorTotal > 0)
                        <flux:button wire:click="checkDateAndProcessCalculator" variant="primary" icon="check">
                            Procesar Venta
                        </flux:button>
                    @endif
                </div>
            </div>
        </flux:modal>
    </div>

    {{-- Date Confirmation Modal --}}
    <flux:modal name="confirm-movement-date" wire:model="showMovementDateConfirmation" class="w-full max-w-md mx-auto">
        <div class="space-y-6">
            <div>
                <h2 class="text-lg font-bold text-slate-900 dark:text-white text-red-600">⚠ Advertencia de Fecha</h2>
                <p class="text-sm text-slate-500 dark:text-zinc-500 mt-2">
                    Está intentando registrar un movimiento en un <strong>mes anterior</strong> al último registrado en el sistema.
                </p>
                <p class="text-sm text-slate-500 dark:text-zinc-500 mt-2">
                    Esto podría afectar el cálculo de costos y saldos históricos. ¿desea continuar?
                </p>
            </div>

            <div class="flex justify-end gap-2">
                <flux:button wire:click="$set('showMovementDateConfirmation', false)">Cancelar</flux:button>
                <flux:button variant="danger" wire:click="confirmMovementAction">Continuar y Registrar</flux:button>
            </div>
        </div>
    </flux:modal>

    {{-- Date Restriction Error Modal --}}
    <flux:modal name="date-restriction-error" wire:model="showDateRestrictionError" class="w-full max-w-md mx-auto">
        <div class="space-y-6">
            <div class="flex flex-col items-center text-center">
                <div class="mb-4 text-red-500 bg-red-100 dark:bg-red-900/30 p-3 rounded-full">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <h2 class="text-xl font-bold text-slate-900 dark:text-white">Acceso Denegado</h2>
                <p class="text-sm text-slate-500 dark:text-zinc-400 mt-2">
                    No tiene permiso para registrar movimientos anteriores a la última fecha de cierre (<span class="font-bold text-slate-800 dark:text-white">{{ $restrictionDate }}</span>).
                </p>
                <p class="text-xs text-slate-400 dark:text-zinc-500 mt-4">
                    Por favor, contacte al administrador si necesita realizar esta operación.
                </p>
            </div>

            <div class="flex justify-center w-full">
                <flux:button wire:click="$set('showDateRestrictionError', false)" class="w-full">Entendido</flux:button>
            </div>
        </div>
    </flux:modal>
</div>