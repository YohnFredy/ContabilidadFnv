<div class="py-6 sm:py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <h1 class="text-xl sm:text-2xl font-semibold text-slate-900 dark:text-white">Categorías de Reglas Contables</h1>
            @can('crear reglas contables')
                <flux:button wire:click="create" icon="plus" variant="primary" class="w-full sm:w-auto">Nueva Categoría
                </flux:button>
            @endcan
        </div>

        <div class="flex gap-4">
            <flux:input wire:model.live="search" icon="magnifying-glass" placeholder="Buscar categoría..."
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
                                class="px-3 sm:px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider dark:text-zinc-400 hidden sm:table-cell">
                                Descripción</th>
                            <th scope="col"
                                class="px-3 sm:px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider dark:text-zinc-400 text-center">
                                Reglas Asociadas</th>
                            <th scope="col"
                                class="px-3 sm:px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider dark:text-zinc-400">
                                Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-200 dark:bg-zinc-900 dark:divide-zinc-700">
                        @foreach ($categories as $category)
                            <tr wire:key="{{ $category->id }}">
                                <td
                                    class="px-3 sm:px-4 py-4 whitespace-nowrap text-sm font-medium text-slate-900 dark:text-white">
                                    {{ $category->name }}</td>
                                <td
                                    class="px-3 sm:px-4 py-4 text-sm text-slate-600 dark:text-zinc-400 hidden sm:table-cell">
                                    {{ Str::limit($category->description, 50) ?: '-' }}
                                </td>
                                <td
                                    class="px-3 sm:px-4 py-4 whitespace-nowrap text-sm text-center text-slate-600 dark:text-zinc-400">
                                    <flux:badge size="sm" color="zinc">{{ $category->accounting_rules_count }}</flux:badge>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex gap-2">
                                        @can('editar reglas contables')
                                            <flux:button wire:click="edit({{ $category->id }})" icon="pencil-square"
                                                size="sm" variant="ghost" />
                                        @endcan
                                        @can('eliminar reglas contables')
                                            <flux:button wire:click="delete({{ $category->id }})"
                                                wire:confirm="¿Estás seguro de eliminar esta categoría?" icon="trash"
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

        {{ $categories->links() }}

        <flux:modal wire:model="showModal" class="md:w-[500px]">
            <div class="space-y-6">
                <div>
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        {{ $editMode ? 'Editar Categoría' : 'Nueva Categoría' }}
                    </h2>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        Asigna un nombre a la categoría para agrupar las reglas contables.
                    </p>
                </div>

                <div class="space-y-4">
                    <flux:input wire:model="name" label="Nombre" placeholder="Ej. Ingresos, Pagos a Proveedores..." />

                    <flux:textarea wire:model="description" label="Descripción (Opcional)" placeholder="Breve descripción de la categoría" />
                </div>

                <div class="flex justify-end gap-2">
                    <flux:button wire:click="$set('showModal', false)" variant="ghost">Cancelar</flux:button>
                    <flux:button wire:click="save" variant="primary">Guardar Categoría</flux:button>
                </div>
            </div>
        </flux:modal>
    </div>
</div>
