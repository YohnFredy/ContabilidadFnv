<?php

namespace App\Livewire\Settings;

use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Livewire\Attributes\Layout;

class Roles extends Component
{
    public $roles;
    public $name = '';
    public $selectedPermissions = [];
    public $isEditing = false;
    public $editingRoleId = null;
    public $showModal = false;

    public function mount()
    {
        $this->loadRoles();
    }

    public function loadRoles()
    {
        $this->roles = Role::with('permissions')->get();
    }

    public function create()
    {
        $this->resetInput();
        $this->isEditing = false;
        $this->showModal = true;
    }

    public function edit($id)
    {
        $role = Role::with('permissions')->findOrFail($id);
        $this->editingRoleId = $id;
        $this->name = $role->name;
        $this->selectedPermissions = $role->permissions->pluck('name')->toArray();
        $this->isEditing = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|min:3|unique:roles,name,' . $this->editingRoleId,
            'selectedPermissions' => 'array'
        ]);

        if ($this->isEditing) {
            $role = Role::findOrFail($this->editingRoleId);
            $role->update(['name' => $this->name]);
            $role->syncPermissions($this->selectedPermissions);
            session()->flash('message', 'Rol actualizado correctamente.');
        } else {
            $role = Role::create(['name' => $this->name]);
            $role->syncPermissions($this->selectedPermissions);
            session()->flash('message', 'Rol creado correctamente.');
        }

        $this->showModal = false;
        $this->resetInput();
        $this->loadRoles();
    }

    public function delete($id)
    {
        $role = Role::findOrFail($id);
        
        // Prevent deleting admin role or critical roles if needed, though Spatie doesn't block it by default
        if ($role->name === 'admin') {
             session()->flash('error', 'No puedes eliminar el rol de administrador principal.');
             return;
        }

        $role->delete();
        $this->loadRoles();
        session()->flash('message', 'Rol eliminado correctamente.');
    }

    public function resetInput()
    {
        $this->name = '';
        $this->selectedPermissions = [];
        $this->editingRoleId = null;
    }

    public function render()
    {
        $allPermissions = Permission::all();
        // Group permissions by category if possible, but for now simple list
        // Assuming permission names like "view inventory" -> can group effectively?
        // Let's just pass all permissions.
        
        return view('livewire.settings.roles', [
            'permissions' => $allPermissions
        ]);
    }
}
