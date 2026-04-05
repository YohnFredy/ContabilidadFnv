<div class="py-6 sm:py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <h1 class="text-xl sm:text-2xl font-semibold text-slate-900 dark:text-white">Reglas Contables</h1>
            @can('crear reglas contables')
            <flux:button wire:click="create" icon="plus" variant="primary" class="w-full sm:w-auto">Nueva Regla
            </flux:button>
            @endcan
        </div>

        <div class="flex gap-4">
            <flux:input wire:model.live="search" icon="magnifying-glass" placeholder="Buscar regla..."
                class="w-full sm:max-w-sm" />
        </div>

        <div
            class="overflow-hidden bg-white shadow-sm dark:bg-zinc-900 rounded-lg border border-slate-200 dark:border-zinc-700">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 dark:divide-zinc-700">
                    <thead class="bg-slate-50 dark:bg-zinc-800">
                        <tr>
                            <th scope="col"
                                class="px-3 sm:px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider dark:text-zinc-400">
                                Nombre</th>
                            <th scope="col"
                                class="px-3 sm:px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider dark:text-zinc-400">
                                Categoría</th>
                            <th scope="col"
                                class="px-3 sm:px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider dark:text-zinc-400">
                                Cuenta 1</th>
                            <th scope="col"
                                class="px-3 sm:px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider dark:text-zinc-400 hidden md:table-cell">
                                Cuenta 2</th>
                            <th scope="col"
                                class="px-3 sm:px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider dark:text-zinc-400 hidden lg:table-cell">
                                Cuenta 3</th>
                            <th scope="col"
                                class="px-3 sm:px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider dark:text-zinc-400 hidden lg:table-cell">
                                Cuenta 4</th>
                            <th scope="col"
                                class="px-3 sm:px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider dark:text-zinc-400 hidden xl:table-cell">
                                Cuenta 5</th>
                            <th scope="col"
                                class="px-3 sm:px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider dark:text-zinc-400 hidden xl:table-cell">
                                Cuenta 6</th>
                            <th scope="col"
                                class="px-3 sm:px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider dark:text-zinc-400">
                                Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-200 dark:bg-zinc-900 dark:divide-zinc-700">
                        @foreach ($rules as $rule)
                        <tr wire:key="{{ $rule->id }}">
                            <td
                                class="px-3 sm:px-4 py-3  text-sm font-medium text-slate-900 dark:text-white">
                                {{ $rule->name }}
                            </td>
                            <td
                                class="px-3 sm:px-4 py-3text-sm text-slate-600 dark:text-zinc-400">
                                {{ $rule->category?->name ?? '-' }}
                            </td>
                            <td
                                class="px-3 sm:px-4 py-3 whitespace-nowrap text-sm text-slate-600 dark:text-zinc-400">
                                @if ($rule->nomenclature1)
                                <div class="flex flex-col">
                                    <span class="font-mono text-xs">{{ $rule->nomenclature1->code }}</span>
                                    <flux:badge size="sm"
                                        color="{{ $rule->nature_1 == 'Débito' ? 'blue' : 'green' }}">
                                        {{ $rule->nature_1 }}
                                    </flux:badge>
                                </div>
                                @else
                                <span class="text-slate-400 dark:text-zinc-500">-</span>
                                @endif
                            </td>
                            <td
                                class="px-3 sm:px-4 py-3 whitespace-nowrap text-sm text-slate-600 dark:text-zinc-400 hidden md:table-cell">
                                @if ($rule->nomenclature2)
                                <div class="flex flex-col">
                                    <span class="font-mono text-xs">{{ $rule->nomenclature2->code }}</span>
                                    <flux:badge size="sm"
                                        color="{{ $rule->nature_2 == 'Débito' ? 'blue' : 'green' }}">
                                        {{ $rule->nature_2 }}
                                    </flux:badge>
                                </div>
                                @else
                                <span class="text-slate-400 dark:text-zinc-500">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-zinc-500 dark:text-zinc-400">
                                @if ($rule->nomenclature3)
                                <div class="flex flex-col">
                                    <span class="font-mono text-xs">{{ $rule->nomenclature3->code }}</span>
                                    <flux:badge size="sm"
                                        color="{{ $rule->nature_3 == 'Débito' ? 'blue' : 'green' }}">
                                        {{ $rule->nature_3 }}
                                    </flux:badge>
                                </div>
                                @else
                                <span class="text-zinc-400">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-zinc-500 dark:text-zinc-400">
                                @if ($rule->nomenclature4)
                                <div class="flex flex-col">
                                    <span class="font-mono text-xs">{{ $rule->nomenclature4->code }}</span>
                                    <flux:badge size="sm"
                                        color="{{ $rule->nature_4 == 'Débito' ? 'blue' : 'green' }}">
                                        {{ $rule->nature_4 }}
                                    </flux:badge>
                                </div>
                                @else
                                <span class="text-zinc-400">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-zinc-500 dark:text-zinc-400">
                                @if ($rule->nomenclature5)
                                <div class="flex flex-col">
                                    <span class="font-mono text-xs">{{ $rule->nomenclature5->code }}</span>
                                    <flux:badge size="sm"
                                        color="{{ $rule->nature_5 == 'Débito' ? 'blue' : 'green' }}">
                                        {{ $rule->nature_5 }}
                                    </flux:badge>
                                </div>
                                @else
                                <span class="text-zinc-400">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-zinc-500 dark:text-zinc-400">
                                @if ($rule->nomenclature6)
                                <div class="flex flex-col">
                                    <span class="font-mono text-xs">{{ $rule->nomenclature6->code }}</span>
                                    <flux:badge size="sm"
                                        color="{{ $rule->nature_6 == 'Débito' ? 'blue' : 'green' }}">
                                        {{ $rule->nature_6 }}
                                    </flux:badge>
                                </div>
                                @else
                                <span class="text-zinc-400">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm font-medium">
                                <div class="flex gap-2">
                                    @can('editar reglas contables')
                                    <flux:button wire:click="edit({{ $rule->id }})" icon="pencil-square"
                                        size="sm" variant="ghost" />
                                    @endcan
                                    @can('eliminar reglas contables')
                                    <flux:button wire:click="delete({{ $rule->id }})"
                                        wire:confirm="¿Estás seguro de eliminar esta regla?" icon="trash"
                                        size="sm" variant="danger" />
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{ $rules->links() }}

        <flux:modal wire:model="showModal" class="md:w-[600px] max-h-[90vh] overflow-y-auto">
            <div class="space-y-6">
                <div>
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        {{ $editMode ? 'Editar Regla' : 'Nueva Regla' }}
                    </h2>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        Define las cuentas contables asociadas a esta regla (hasta 5 cuentas).
                    </p>
                </div>

                <div class="space-y-4">
                    <flux:input wire:model="name" label="Nombre de la Regla" placeholder="Ej. Compras con IVA" />
                    @error('name')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror

                    <flux:select wire:model="accounting_rule_category_id" label="Categoría" placeholder="Seleccione una categoría (opcional)...">
                        <flux:select.option value="">Ninguna</flux:select.option>
                        @foreach ($categories as $cat)
                        <flux:select.option value="{{ $cat->id }}">{{ $cat->name }}</flux:select.option>
                        @endforeach
                    </flux:select>
                    @error('accounting_rule_category_id')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror

                    <!-- Cuenta 1 (Principal) -->
                    <div class="p-3 bg-zinc-50 dark:bg-zinc-800 rounded-lg space-y-2">
                        <flux:label class="text-xs font-semibold">Cuenta 1 (Principal)</flux:label>
                        <div class="grid grid-cols-3 gap-2">
                            <div class="col-span-2">
                                <select wire:model="nomenclature_id_1"
                                    class="w-full rounded-md border-zinc-300 dark:border-zinc-700 dark:bg-zinc-900 py-1.5 text-sm">
                                    <option value="">Seleccionar Cuenta...</option>
                                    @foreach ($nomenclatures as $nom)
                                    <option value="{{ $nom->id }}">{{ $nom->code }} -
                                        {{ $nom->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <select wire:model="nature_1"
                                    class="w-full rounded-md border-zinc-300 dark:border-zinc-700 dark:bg-zinc-900 py-1.5 text-sm">
                                    @foreach ($natures as $nat)
                                    <option value="{{ $nat }}">{{ $nat }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Cuenta 2 -->
                    <div class="p-3 bg-zinc-50 dark:bg-zinc-800 rounded-lg space-y-2">
                        <flux:label class="text-xs font-semibold">Cuenta 2</flux:label>
                        <div class="grid grid-cols-3 gap-2">
                            <div class="col-span-2">
                                <select wire:model="nomenclature_id_2"
                                    class="w-full rounded-md border-zinc-300 dark:border-zinc-700 dark:bg-zinc-900 py-1.5 text-sm">
                                    <option value="">Seleccionar Cuenta...</option>
                                    @foreach ($nomenclatures as $nom)
                                    <option value="{{ $nom->id }}">{{ $nom->code }} -
                                        {{ $nom->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <select wire:model="nature_2"
                                    class="w-full rounded-md border-zinc-300 dark:border-zinc-700 dark:bg-zinc-900 py-1.5 text-sm">
                                    @foreach ($natures as $nat)
                                    <option value="{{ $nat }}">{{ $nat }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Cuenta 3 -->
                    <div class="p-3 bg-zinc-50 dark:bg-zinc-800 rounded-lg space-y-2">
                        <flux:label class="text-xs font-semibold">Cuenta 3</flux:label>
                        <div class="grid grid-cols-3 gap-2">
                            <div class="col-span-2">
                                <select wire:model="nomenclature_id_3"
                                    class="w-full rounded-md border-zinc-300 dark:border-zinc-700 dark:bg-zinc-900 py-1.5 text-sm">
                                    <option value="">Seleccionar Cuenta...</option>
                                    @foreach ($nomenclatures as $nom)
                                    <option value="{{ $nom->id }}">{{ $nom->code }} -
                                        {{ $nom->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <select wire:model="nature_3"
                                    class="w-full rounded-md border-zinc-300 dark:border-zinc-700 dark:bg-zinc-900 py-1.5 text-sm">
                                    @foreach ($natures as $nat)
                                    <option value="{{ $nat }}">{{ $nat }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Cuenta 4 -->
                    <div class="p-3 bg-zinc-50 dark:bg-zinc-800 rounded-lg space-y-2">
                        <flux:label class="text-xs font-semibold">Cuenta 4</flux:label>
                        <div class="grid grid-cols-3 gap-2">
                            <div class="col-span-2">
                                <select wire:model="nomenclature_id_4"
                                    class="w-full rounded-md border-zinc-300 dark:border-zinc-700 dark:bg-zinc-900 py-1.5 text-sm">
                                    <option value="">Seleccionar Cuenta...</option>
                                    @foreach ($nomenclatures as $nom)
                                    <option value="{{ $nom->id }}">{{ $nom->code }} -
                                        {{ $nom->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <select wire:model="nature_4"
                                    class="w-full rounded-md border-zinc-300 dark:border-zinc-700 dark:bg-zinc-900 py-1.5 text-sm">
                                    @foreach ($natures as $nat)
                                    <option value="{{ $nat }}">{{ $nat }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Cuenta 5 -->
                    <div class="p-3 bg-zinc-50 dark:bg-zinc-800 rounded-lg space-y-2">
                        <flux:label class="text-xs font-semibold">Cuenta 5</flux:label>
                        <div class="grid grid-cols-3 gap-2">
                            <div class="col-span-2">
                                <select wire:model="nomenclature_id_5"
                                    class="w-full rounded-md border-zinc-300 dark:border-zinc-700 dark:bg-zinc-900 py-1.5 text-sm">
                                    <option value="">Seleccionar Cuenta...</option>
                                    @foreach ($nomenclatures as $nom)
                                    <option value="{{ $nom->id }}">{{ $nom->code }} -
                                        {{ $nom->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <select wire:model="nature_5"
                                    class="w-full rounded-md border-zinc-300 dark:border-zinc-700 dark:bg-zinc-900 py-1.5 text-sm">
                                    @foreach ($natures as $nat)
                                    <option value="{{ $nat }}">{{ $nat }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Cuenta 6 -->
                    <div class="p-3 bg-zinc-50 dark:bg-zinc-800 rounded-lg space-y-2">
                        <flux:label class="text-xs font-semibold">Cuenta 6</flux:label>
                        <div class="grid grid-cols-3 gap-2">
                            <div class="col-span-2">
                                <select wire:model="nomenclature_id_6"
                                    class="w-full rounded-md border-zinc-300 dark:border-zinc-700 dark:bg-zinc-900 py-1.5 text-sm">
                                    <option value="">Seleccionar Cuenta...</option>
                                    @foreach ($nomenclatures as $nom)
                                    <option value="{{ $nom->id }}">{{ $nom->code }} -
                                        {{ $nom->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <select wire:model="nature_6"
                                    class="w-full rounded-md border-zinc-300 dark:border-zinc-700 dark:bg-zinc-900 py-1.5 text-sm">
                                    @foreach ($natures as $nat)
                                    <option value="{{ $nat }}">{{ $nat }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="flex justify-end gap-2">
                    <flux:button wire:click="$set('showModal', false)" variant="ghost">Cancelar</flux:button>
                    <flux:button wire:click="save" variant="primary">Guardar Regla</flux:button>
                </div>
            </div>
        </flux:modal>
    </div>
</div>