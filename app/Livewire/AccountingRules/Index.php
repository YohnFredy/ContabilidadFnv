<?php

namespace App\Livewire\AccountingRules;

use App\Models\AccountingRule;
use App\Models\AccountingRuleCategory;
use App\Models\Nomenclature;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';

    public $showModal = false;

    public $editMode = false;

    public $ruleId;

    // Form fields
    public $name = '';

    public $accounting_rule_category_id = '';

    public $nomenclature_id_1;

    public $nature_1 = 'Débito';

    public $nomenclature_id_2;

    public $nature_2 = 'Crédito';

    public $nomenclature_id_3;

    public $nature_3 = 'Débito';

    public $nomenclature_id_4;

    public $nature_4 = 'Crédito';

    public $nomenclature_id_5;

    public $nature_5 = 'Débito';

    public $nomenclature_id_6;

    public $nature_6 = 'Crédito';

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'accounting_rule_category_id' => 'nullable|exists:accounting_rule_categories,id',
            'nomenclature_id_1' => 'nullable|exists:nomenclatures,id',
            'nature_1' => 'nullable|in:Débito,Crédito',
            'nomenclature_id_2' => 'nullable|exists:nomenclatures,id',
            'nature_2' => 'nullable|in:Débito,Crédito',
            'nomenclature_id_3' => 'nullable|exists:nomenclatures,id',
            'nature_3' => 'nullable|in:Débito,Crédito',
            'nomenclature_id_4' => 'nullable|exists:nomenclatures,id',
            'nature_4' => 'nullable|in:Débito,Crédito',
            'nomenclature_id_5' => 'nullable|exists:nomenclatures,id',
            'nature_5' => 'nullable|in:Débito,Crédito',
            'nomenclature_id_6' => 'nullable|exists:nomenclatures,id',
            'nature_6' => 'nullable|in:Débito,Crédito',
        ];
    }

    public function create()
    {
        $this->authorize('crear reglas contables');
        $this->reset([
            'name',
            'accounting_rule_category_id',
            'nomenclature_id_1',
            'nature_1',
            'nomenclature_id_2',
            'nature_2',
            'nomenclature_id_3',
            'nature_3',
            'nomenclature_id_4',
            'nature_4',
            'nomenclature_id_5',
            'nature_5',
            'nomenclature_id_6',
            'nature_6',
            'ruleId',
        ]);
        $this->nature_1 = 'Débito';
        $this->nature_2 = 'Crédito';
        $this->nature_3 = 'Débito';
        $this->nature_4 = 'Crédito';
        $this->nature_5 = 'Débito';
        $this->nature_6 = 'Crédito';
        $this->editMode = false;
        $this->showModal = true;
    }

    public function edit($id)
    {
        $this->authorize('editar reglas contables');
        $rule = AccountingRule::findOrFail($id);
        $this->ruleId = $id;
        $this->name = $rule->name;
        $this->accounting_rule_category_id = $rule->accounting_rule_category_id;

        $this->nomenclature_id_1 = $rule->nomenclature_id_1;
        $this->nature_1 = $rule->nature_1;

        $this->nomenclature_id_2 = $rule->nomenclature_id_2;
        $this->nature_2 = $rule->nature_2;

        $this->nomenclature_id_3 = $rule->nomenclature_id_3;
        $this->nature_3 = $rule->nature_3;

        $this->nomenclature_id_4 = $rule->nomenclature_id_4;
        $this->nature_4 = $rule->nature_4 ?? 'Crédito';

        $this->nomenclature_id_5 = $rule->nomenclature_id_5;
        $this->nature_5 = $rule->nature_5 ?? 'Débito';

        $this->nomenclature_id_6 = $rule->nomenclature_id_6;
        $this->nature_6 = $rule->nature_6 ?? 'Crédito';

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

        AccountingRule::updateOrCreate(
            ['id' => $this->ruleId],
            [
                'name' => $this->name,
                'accounting_rule_category_id' => $this->accounting_rule_category_id ?: null,
                'nomenclature_id_1' => $this->nomenclature_id_1 ?: null,
                'nature_1' => $this->nature_1,
                'nomenclature_id_2' => $this->nomenclature_id_2 ?: null,
                'nature_2' => $this->nature_2,
                'nomenclature_id_3' => $this->nomenclature_id_3 ?: null,
                'nature_3' => $this->nature_3,
                'nomenclature_id_4' => $this->nomenclature_id_4 ?: null,
                'nature_4' => $this->nature_4,
                'nomenclature_id_5' => $this->nomenclature_id_5 ?: null,
                'nature_5' => $this->nature_5,
                'nomenclature_id_6' => $this->nomenclature_id_6 ?: null,
                'nature_6' => $this->nature_6,
            ]
        );

        $this->showModal = false;
        $this->reset([
            'name',
            'accounting_rule_category_id',
            'nomenclature_id_1',
            'nature_1',
            'nomenclature_id_2',
            'nature_2',
            'nomenclature_id_3',
            'nature_3',
            'nomenclature_id_4',
            'nature_4',
            'nomenclature_id_5',
            'nature_5',
            'nomenclature_id_6',
            'nature_6',
            'ruleId',
        ]);
    }

    public function delete($id)
    {
        $this->authorize('eliminar reglas contables');
        AccountingRule::findOrFail($id)->delete();
    }

    public function render()
    {
        $rules = AccountingRule::with(['category', 'nomenclature1', 'nomenclature2', 'nomenclature3', 'nomenclature4', 'nomenclature5', 'nomenclature6'])
            ->where('name', 'like', '%'.$this->search.'%')
            ->paginate(10);

        return view('livewire.accounting-rules.index', [
            'rules' => $rules,
            'categories' => AccountingRuleCategory::orderBy('name')->get(),
            'nomenclatures' => Nomenclature::orderBy('code')->get(),
            'natures' => AccountingRule::NATURES,
        ]);
    }
}
