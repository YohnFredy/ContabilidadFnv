<?php

namespace App\Livewire\AccountingRules;

use App\Models\AccountingRuleCategory;
use Livewire\Component;
use Livewire\WithPagination;

class Categories extends Component
{
    use WithPagination;

    public $search = '';

    public $showModal = false;

    public $editMode = false;

    public $categoryId;

    public $name = '';

    public $description = '';

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ];
    }

    public function create()
    {
        $this->authorize('crear reglas contables');
        $this->reset(['name', 'description', 'categoryId']);
        $this->editMode = false;
        $this->showModal = true;
    }

    public function edit($id)
    {
        $this->authorize('editar reglas contables');
        $category = AccountingRuleCategory::findOrFail($id);
        $this->categoryId = $id;
        $this->name = $category->name;
        $this->description = $category->description;

        $this->editMode = true;
        $this->showModal = true;
    }

    public function save()
    {
        if ($this->editMode) {
            $this->authorize('editar reglas contables');
        } else {
            $this->authorize('crear reglas contables');
        }

        $this->validate();

        AccountingRuleCategory::updateOrCreate(
            ['id' => $this->categoryId],
            [
                'name' => $this->name,
                'description' => $this->description,
            ]
        );

        $this->showModal = false;
        $this->reset(['name', 'description', 'categoryId']);
    }

    public function delete($id)
    {
        $this->authorize('eliminar reglas contables');

        $category = AccountingRuleCategory::withCount('accountingRules')->findOrFail($id);

        if ($category->accounting_rules_count > 0) {
            $this->dispatch('show-toast', message: 'No se puede eliminar la categoría porque tiene reglas asociadas.', variant: 'danger');

            return;
        }

        $category->delete();
    }

    public function render()
    {
        $categories = AccountingRuleCategory::withCount('accountingRules')
            ->where('name', 'like', '%'.$this->search.'%')
            ->paginate(10);

        return view('livewire.accounting-rules.categories', [
            'categories' => $categories,
        ]);
    }
}
