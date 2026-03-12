<div class="py-6 sm:py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        {{-- Header --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-slate-900 dark:text-white">Catálogo de Productos</h1>
                <p class="text-sm text-slate-500 dark:text-zinc-400">Gestiona tu inventario de productos</p>
            </div>
            @can('crear productos')
                <flux:button wire:click="create" icon="plus" variant="primary" class="w-full sm:w-auto">Nuevo Producto</flux:button>
            @endcan
        </div>

        {{-- Filters --}}
        <div class="bg-white dark:bg-zinc-900 rounded-lg border border-slate-200 dark:border-zinc-700 p-4">
            <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4">
                <flux:input wire:model.live="search" icon="magnifying-glass"
                    placeholder="Buscar..." />

                <select wire:model.live="categoryFilter"
                    class="w-full rounded-lg border-0 py-2 px-3 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm dark:bg-zinc-800 dark:text-white dark:ring-zinc-600">
                    <option value="">Todas las categorías</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat }}">{{ $cat }}</option>
                    @endforeach
                </select>

                <select wire:model.live="statusFilter"
                    class="w-full rounded-lg border-0 py-2 px-3 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm dark:bg-zinc-800 dark:text-white dark:ring-zinc-600">
                    <option value="">Todos los estados</option>
                    <option value="active">Activos</option>
                    <option value="inactive">Inactivos</option>
                </select>

                <div class="flex items-center justify-end col-span-2 sm:col-span-1">
                    <span class="text-sm text-slate-500 dark:text-zinc-400">
                        {{ $products->total() }} productos
                    </span>
                </div>
            </div>
        </div>

        {{-- Flash Messages --}}
        @if (session('error'))
            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                <p class="text-sm text-red-600 dark:text-red-400">{{ session('error') }}</p>
            </div>
        @endif

        {{-- Products Table --}}
        <div
            class="overflow-hidden bg-white dark:bg-zinc-900 shadow-sm rounded-lg border border-slate-200 dark:border-zinc-700">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 dark:divide-zinc-700">
                    <thead class="bg-slate-50 dark:bg-zinc-800">
                        <tr>
                            <th scope="col"
                                class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider dark:text-zinc-400">
                                Código</th>
                            <th scope="col"
                                class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider dark:text-zinc-400">
                                Producto</th>
                            <th scope="col"
                                class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider dark:text-zinc-400 hidden md:table-cell">
                                Categoría</th>
                            <th scope="col"
                            <th scope="col"
                                class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider dark:text-zinc-400">
                                Stock Físico</th>
                            <th scope="col"
                                class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider dark:text-zinc-400 hidden sm:table-cell">
                                CPP</th>
                            <th scope="col"
                                class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider dark:text-zinc-400 hidden lg:table-cell">
                                Valor Total</th>
                            <th scope="col"
                                class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider dark:text-zinc-400 hidden sm:table-cell">
                                Estado</th>
                            <th scope="col"
                                class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider dark:text-zinc-400">
                                Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-zinc-200 dark:bg-zinc-900 dark:divide-zinc-700">
                        @forelse ($products as $product)
                            <tr wire:key="{{ $product->id }}"
                                class="{{ $product->is_low_stock ? 'bg-amber-50 dark:bg-amber-900/10' : '' }}">
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm font-mono font-medium text-zinc-900 dark:text-zinc-100">
                                    {{ $product->code }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-zinc-900 dark:text-zinc-100">{{ $product->name }}
                                    </div>
                                    <div class="text-xs text-zinc-500 dark:text-zinc-400">
                                        {{ $units[$product->unit] ?? $product->unit }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-500 dark:text-zinc-400">
                                    @if($product->category)
                                        <flux:badge size="sm" color="zinc">{{ $product->category }}</flux:badge>
                                    @else
                                        <span class="text-zinc-400">—</span>
                                    @endif
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm text-right font-mono {{ $product->is_low_stock ? 'text-amber-600 dark:text-amber-400 font-semibold' : 'text-zinc-900 dark:text-zinc-100' }}">
                                    {{ number_format($product->current_stock, 2) }}
                                    @if($product->is_low_stock && $product->min_stock > 0)
                                        <span class="ml-1 text-amber-500"
                                            title="Stock bajo (mín: {{ $product->min_stock }})">⚠️</span>
                                    @endif

                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm text-right font-mono text-zinc-900 dark:text-zinc-100">
                                    ${{ number_format($product->current_avg_cost, 2) }}
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm text-right font-mono font-medium text-emerald-600 dark:text-emerald-400">
                                    ${{ number_format($product->inventory_value, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if($product->is_active)
                                        <flux:badge size="sm" color="green">Activo</flux:badge>
                                    @else
                                        <flux:badge size="sm" color="red">Inactivo</flux:badge>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="flex justify-center gap-1">
                                        <flux:button wire:click="edit({{ $product->id }})" icon="pencil-square" size="sm"
                                            variant="ghost" title="Editar" />
                                        <flux:button wire:click="toggleStatus({{ $product->id }})"
                                            icon="{{ $product->is_active ? 'eye-slash' : 'eye' }}" size="sm" variant="ghost"
                                            title="{{ $product->is_active ? 'Desactivar' : 'Activar' }}" />
                                        <flux:button wire:click="delete({{ $product->id }})"
                                            wire:confirm="¿Estás seguro de eliminar este producto?" icon="trash" size="sm"
                                            variant="danger" title="Eliminar" />
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-12 h-12 text-zinc-400 mb-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                        </svg>
                                        <p class="text-zinc-500 dark:text-zinc-400 text-sm">No hay productos registrados</p>
                                        <flux:button wire:click="create" size="sm" variant="ghost" class="mt-2">
                                            Crear primer producto
                                        </flux:button>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination --}}
        {{ $products->links() }}

        {{-- Modal --}}
        <flux:modal wire:model="showModal" class="md:w-[500px]">
            <div class="space-y-6">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                        {{ $editMode ? 'Editar Producto' : 'Nuevo Producto' }}
                    </h2>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        {{ $editMode ? 'Modifica los datos del producto.' : 'Ingresa los datos del nuevo producto.' }}
                    </p>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <flux:input wire:model="code" label="Código *" placeholder="SKU-001" />
                        @error('code') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <flux:label>Unidad *</flux:label>
                        <select wire:model="unit"
                            class="w-full rounded-lg border-0 py-2 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm dark:bg-zinc-800 dark:text-white dark:ring-zinc-600">
                            @foreach ($units as $key => $label)
                                <option value="{{ $key }}">{{ $label }} ({{ $key }})</option>
                            @endforeach
                        </select>
                        @error('unit') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div>
                    <flux:input wire:model="name" label="Nombre *" placeholder="Nombre del producto" />
                    @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <flux:label>Descripción</flux:label>
                    <textarea wire:model="description" rows="2"
                        class="w-full rounded-lg border-0 py-2 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm dark:bg-zinc-800 dark:text-white dark:ring-zinc-600"
                        placeholder="Descripción opcional..."></textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <flux:label>Categoría</flux:label>
                        <select wire:model="category"
                            class="w-full rounded-lg border-0 py-2 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm dark:bg-zinc-800 dark:text-white dark:ring-zinc-600">
                            <option value="">Sin categoría</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat }}">{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <flux:input wire:model="min_stock" label="Stock Mínimo" type="number" step="0.01" min="0"
                            placeholder="0" />
                        @error('min_stock') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <input type="checkbox" wire:model="is_active" id="is_active"
                        class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-600 dark:border-zinc-600 dark:bg-zinc-800">
                    <label for="is_active" class="text-sm text-gray-700 dark:text-gray-300">Producto activo</label>
                </div>

                <div class="flex justify-end gap-2 pt-4 border-t border-zinc-200 dark:border-zinc-700">
                    <flux:button wire:click="$set('showModal', false)" variant="ghost">Cancelar</flux:button>
                    <flux:button wire:click="save" variant="primary">
                        {{ $editMode ? 'Actualizar' : 'Guardar' }}
                    </flux:button>
                </div>
            </div>
        </flux:modal>
    </div>
</div>