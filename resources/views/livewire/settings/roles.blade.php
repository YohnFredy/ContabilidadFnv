<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <flux:heading size="xl">Gestión de Roles</flux:heading>
            <flux:subheading>Crea y administra roles personalizados y sus permisos.</flux:subheading>
        </div>
        <flux:button variant="primary" wire:click="create" icon="plus">Nuevo Rol</flux:button>
    </div>

    @if (session('message'))
        <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
            <span class="font-medium">Éxito!</span> {{ session('message') }}
        </div>
    @endif
    @if (session('error'))
        <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
            <span class="font-medium">Error!</span> {{ session('error') }}
        </div>
    @endif

    <div class="overflow-hidden bg-white dark:bg-zinc-900 shadow-sm rounded-lg border border-slate-200 dark:border-zinc-700">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 dark:divide-zinc-700">
                <thead class="bg-slate-50 dark:bg-zinc-800">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider dark:text-zinc-400">Nombre</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider dark:text-zinc-400">Permisos</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider dark:text-zinc-400">Usuarios</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider dark:text-zinc-400">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-zinc-200 dark:bg-zinc-900 dark:divide-zinc-700">
                    @foreach ($roles as $role)
                        <tr wire:key="role-{{ $role->id }}">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-zinc-900 dark:text-zinc-100">
                                {{ ucfirst($role->name) }}
                            </td>
                            <td class="px-6 py-4 text-sm text-zinc-500 dark:text-zinc-400">
                                <span class="inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-700/10 dark:bg-blue-400/10 dark:text-blue-400 dark:ring-blue-400/30">
                                    {{ $role->permissions->count() }} permisos
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-zinc-500 dark:text-zinc-400">
                                {{ $role->users()->count() }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-900 dark:text-zinc-100">
                                <div class="flex gap-2">
                                    <flux:button size="sm" wire:click="edit({{ $role->id }})" icon="pencil-square">Editar</flux:button>
                                    @if($role->name !== 'admin')
                                        <flux:button size="sm" wire:click="delete({{ $role->id }})" wire:confirm="¿Estás seguro de eliminar este rol?" icon="trash" variant="danger">Eliminar</flux:button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modal --}}
    <flux:modal wire:model="showModal" class="min-w-[30rem] md:min-w-[40rem]">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">{{ $isEditing ? 'Editar Rol' : 'Crear Nuevo Rol' }}</flux:heading>
                <flux:subheading>Define el nombre del rol y asigna sus permisos.</flux:subheading>
            </div>

            <flux:input label="Nombre del Rol" wire:model="name" placeholder="Ej. Supervisor de Inventario" />
            @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

            <div class="border-t border-slate-200 dark:border-zinc-700 pt-4">
                <flux:label class="mb-3">Permisos Disponibles</flux:label>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 max-h-[400px] overflow-y-auto p-2 border border-slate-200 dark:border-zinc-700 rounded-lg">
                    @foreach($permissions as $permission)
                        <div class="flex items-start" wire:key="perm-{{ $permission->id }}">
                            <flux:checkbox 
                                wire:model="selectedPermissions" 
                                value="{{ $permission->name }}" 
                                label="{{ ucfirst($permission->name) }}"
                            />
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="flex justify-end gap-2 pt-4">
                <flux:button wire:click="$set('showModal', false)">Cancelar</flux:button>
                <flux:button variant="primary" wire:click="save">{{ $isEditing ? 'Actualizar Rol' : 'Crear Rol' }}</flux:button>
            </div>
        </div>
    </flux:modal>
</div>
