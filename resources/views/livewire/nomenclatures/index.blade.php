<div class="py-6 sm:py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <h1 class="text-xl sm:text-2xl font-semibold text-slate-900 dark:text-white">Nomenclatura</h1>

            @can('crear nomenclatura')
                <flux:button wire:click="create" icon="plus" variant="primary" class="w-full sm:w-auto">Nueva Cuenta
                </flux:button>
            @endcan

        </div>

        <div class="flex gap-4">
            <flux:input wire:model.live="search" icon="magnifying-glass" placeholder="Buscar por código o nombre..."
                class="w-full sm:max-w-sm" />
        </div>

        <div
            class="overflow-hidden bg-white shadow-sm dark:bg-zinc-900 rounded-lg border border-slate-200 dark:border-zinc-700">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 dark:divide-zinc-700">
                    <thead class="bg-slate-50 dark:bg-zinc-800">
                        <tr>
                            <th scope="col"
                                class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider dark:text-zinc-400">
                                Código</th>
                            <th scope="col"
                                class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider dark:text-zinc-400">
                                Nombre</th>
                            <th scope="col"
                                class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider dark:text-zinc-400 hidden sm:table-cell">
                                Categoría</th>
                            <th scope="col"
                                class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider dark:text-zinc-400">
                                Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-200 dark:bg-zinc-900 dark:divide-zinc-700">
                        @foreach ($nomenclatures as $nomenclature)
                            <tr wire:key="{{ $nomenclature->id }}">
                                <td
                                    class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900 dark:text-white font-mono">
                                    {{ $nomenclature->code }}</td>
                                <td
                                    class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-slate-600 dark:text-zinc-400">
                                    {{ $nomenclature->name }}</td>
                                <td
                                    class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-slate-600 dark:text-zinc-400 hidden sm:table-cell">
                                    <flux:badge size="sm" inset="top bottom">{{ $nomenclature->category }}
                                    </flux:badge>
                                </td>
                                <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex gap-2">
                                        @can('editar nomemclatura')
                                            <flux:button wire:click="edit({{ $nomenclature->id }})" icon="pencil-square"
                                                size="sm" variant="ghost" />
                                        @endcan

                                        @can('eliminar nomemclatura')
                                            <flux:button wire:click="delete({{ $nomenclature->id }})"
                                                wire:confirm="¿Estás seguro de eliminar este registro?" icon="trash"
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

        {{ $nomenclatures->links() }}

        <flux:modal wire:model="showModal" class="w-full max-w-md mx-4 sm:mx-auto">
            <div class="space-y-6">
                <div>
                    <h2 class="text-lg font-medium text-slate-900 dark:text-white">
                        {{ $editMode ? 'Editar Cuenta' : 'Nueva Cuenta' }}
                    </h2>
                    <p class="mt-1 text-sm text-slate-600 dark:text-zinc-400">
                        {{ $editMode ? 'Modifica los detalles de la cuenta contable.' : 'Ingresa los detalles para la nueva cuenta contable.' }}
                    </p>
                </div>

                <div class="space-y-4">
                    <flux:input wire:model="code" label="Código" placeholder="Ej. 1105" />
                    @error('code')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror

                    <flux:input wire:model="name" label="Nombre" placeholder="Ej. Caja" />
                    @error('name')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror

                    <div>
                        <flux:label>Categoría</flux:label>
                        <select wire:model="category"
                            class="w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-zinc-800 dark:text-white dark:ring-zinc-600">
                            <option value="">Seleccione una categoría</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat }}">{{ $cat }}</option>
                            @endforeach
                        </select>
                        @error('category')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="flex justify-end gap-2">
                    <flux:button wire:click="$set('showModal', false)" variant="ghost">Cancelar</flux:button>
                    <flux:button wire:click="save" variant="primary">Guardar</flux:button>
                </div>
            </div>
        </flux:modal>
    </div>
</div>
