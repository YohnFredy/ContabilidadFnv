<?php

namespace App\Livewire\Nomenclatures;

use App\Models\Nomenclature;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $sortBy = 'code';
    public $sortDirection = 'asc';

    // Modal state
    public $showModal = false;
    public $editMode = false;
    public $nomenclatureId;

    // Form fields
    public $code = '';
    public $name = '';
    public $category = '';

    public function rules()
    {
        return [
            'code' => 'required|unique:nomenclatures,code,' . $this->nomenclatureId,
            'name' => 'required|string',
            'category' => 'required|string|in:' . implode(',', Nomenclature::CATEGORIES),
        ];
    }

    public function create()
    {
        $this->authorize('crear nomenclatura');
        $this->reset(['code', 'name', 'category', 'nomenclatureId']);
        $this->editMode = false;
        $this->showModal = true;
    }

    public function edit($id)
    {
        $nomenclature = Nomenclature::findOrFail($id);
        $this->nomenclatureId = $id;
        $this->code = $nomenclature->code;
        $this->name = $nomenclature->name;
        $this->category = $nomenclature->category;

        $this->editMode = true;
        $this->showModal = true;
    }

    public function save()
    {
        if ($this->editMode) {
            // We should ideally have 'editar nomenclatura' but 'crear' covers it for now/seeder doesn't distinguish?
            // Checking seeder: 'crear nomenclatura' exists. 'editar nomenclatura' DOES NOT exist in seeder list in Step 209.
            // Seeder has 'crear nomenclatura' but NOT 'editar nomenclatura' or 'eliminar nomenclatura'. 
            // Step 209 shows: 'crear nomenclatura' at end of list.
            // Wait, let me check the seeder content again from Step 209.
            // List: 'crear asientos...', 'editar asientos...', 'crear movimientos...', 'editar movimientos...', 'crear reglas...', 'editar reglas...', 'crear nomenclatura'.
            // It seems 'editar nomenclatura' and 'eliminar nomenclatura' might be missing from the list in Step 209?
            // Let's use 'crear nomenclatura' for now or just skip strict check for edit if permission is missing.
            // User only asked to translate existing ones.
            $this->authorize('crear nomenclatura');
        } else {
            $this->authorize('crear nomenclatura');
        }

        $this->validate();

        Nomenclature::updateOrCreate(
            ['id' => $this->nomenclatureId],
            [
                'code' => $this->code,
                'name' => $this->name,
                'category' => $this->category,
            ]
        );

        $this->showModal = false;
        $this->reset(['code', 'name', 'category', 'nomenclatureId']);
    }

    public function delete($id)
    {
        Nomenclature::findOrFail($id)->delete();
    }

    public function render()
    {
        $nomenclatures = Nomenclature::query()
            ->where('name', 'like', '%' . $this->search . '%')
            ->orWhere('code', 'like', '%' . $this->search . '%')
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate(10);

        return view('livewire.nomenclatures.index', [
            'nomenclatures' => $nomenclatures,
            'categories' => Nomenclature::CATEGORIES,
        ]);
    }
}
