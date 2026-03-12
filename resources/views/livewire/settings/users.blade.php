<div class="space-y-6">
    <div class="flex justify-between items-center">
        <flux:heading size="xl">Gestión de Usuarios</flux:heading>
        <!-- <flux:button>Invite User</flux:button> -->
    </div>

    <div class="overflow-hidden bg-white dark:bg-zinc-900 shadow-sm rounded-lg border border-slate-200 dark:border-zinc-700">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 dark:divide-zinc-700">
                <thead class="bg-slate-50 dark:bg-zinc-800">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider dark:text-zinc-400">Nombre</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider dark:text-zinc-400">Email</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider dark:text-zinc-400">Roles</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider dark:text-zinc-400">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-zinc-200 dark:bg-zinc-900 dark:divide-zinc-700">
                    @foreach ($users as $user)
                        <tr wire:key="{{ $user->id }}">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-900 dark:text-zinc-100">{{ $user->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-900 dark:text-zinc-100">{{ $user->email }}</td>
                            <td class="px-6 py-4 text-sm text-zinc-900 dark:text-zinc-100">
                                <div class="flex gap-2 flex-wrap">
                                    @foreach($user->roles as $role)
                                        <flux:badge size="sm">{{ $role->name }}</flux:badge>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-900 dark:text-zinc-100">
                                <flux:button size="sm" wire:click="edit({{ $user->id }})">Editar</flux:button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div>
        {{ $users->links() }}
    </div>

    <flux:modal wire:model="showEditModal" class="min-w-[20rem]">
        <div class="space-y-6">
            <flux:heading size="lg">Editar Usuario</flux:heading>

            <flux:input label="Nombre" wire:model="name" />
            <flux:input label="Email" wire:model="email" type="email" />

            <flux:field>
                <flux:label>Roles</flux:label>
                <div class="space-y-2 mt-2">
                    @foreach($roles as $role)
                        <flux:checkbox 
                            wire:model="selectedRoles" 
                            label="{{ $role->name }}" 
                            value="{{ $role->name }}" 
                        />
                    @endforeach
                </div>
            </flux:field>

            <div class="flex justify-end gap-2">
                <flux:button wire:click="$set('showEditModal', false)">Cancelar</flux:button>
                <flux:button variant="primary" wire:click="save">Guardar</flux:button>
            </div>
        </div>
    </flux:modal>
</div>
