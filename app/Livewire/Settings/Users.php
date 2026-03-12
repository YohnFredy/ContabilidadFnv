<?php

namespace App\Livewire\Settings;

use Livewire\Component;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Livewire\WithPagination;

class Users extends Component
{
    use WithPagination;

    public $editingUser = null;
    public $name = '';
    public $email = '';
    public $selectedRoles = [];
    public $showEditModal = false;

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . ($this->editingUser->id ?? 'null'),
            'selectedRoles' => 'array',
        ];
    }

    public function edit(User $user)
    {
        $this->editingUser = $user;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->selectedRoles = $user->roles->pluck('name')->toArray();
        $this->showEditModal = true;
    }

    public function save()
    {
        $this->validate();

        $this->editingUser->update([
            'name' => $this->name,
            'email' => $this->email,
        ]);

        $this->editingUser->syncRoles($this->selectedRoles);

        $this->showEditModal = false;
        $this->reset(['editingUser', 'name', 'email', 'selectedRoles']);
        
        session()->flash('message', 'Usuario actualizado correctamente.');
    }

    public function render()
    {
        return view('livewire.settings.users', [
            'users' => User::with('roles')->paginate(10),
            'roles' => Role::all(),
        ]);
    }
}
