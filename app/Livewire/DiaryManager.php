<?php

namespace App\Livewire;

use App\Models\AccountingRule;
use App\Models\Diary;
use App\Models\Nomenclature;
use Flux\Flux;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class DiaryManager extends Component
{
    use WithPagination;

    public $date;
    public $invoice_number;
    public $description;
    public $selected_rule_id;

    public $entries = [];

    public $editing_diary_id = null;
    public $showModal = false;

    public $viewingDiary = null;
    public $showViewModal = false;

    // Search Filters
    public $searchDateFrom;
    public $searchDateTo;
    public $searchNomenclatureId;
    public $searchInvoiceNumber;

    public function mount()
    {
        $this->resetForm();
    }

    public function view($id)
    {
        $this->viewingDiary = Diary::with(['nomenclature', 'children.nomenclature'])->find($id);
        $this->showViewModal = true;
    }

    public function search()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->searchDateFrom = null;
        $this->searchDateTo = null;
        $this->searchNomenclatureId = null;
        $this->searchInvoiceNumber = null;
        $this->resetPage();
    }


    public function resetForm()
    {
        $this->date = now()->format('Y-m-d');
        $this->invoice_number = '';
        $this->description = '';
        $this->selected_rule_id = '';
        $this->editing_diary_id = null;
        $this->entries = [
            ['nomenclature_id' => '', 'code' => '', 'name' => '', 'type' => 'Débito', 'value' => ''],
            ['nomenclature_id' => '', 'code' => '', 'name' => '', 'type' => 'Crédito', 'value' => ''],
            ['nomenclature_id' => '', 'code' => '', 'name' => '', 'type' => 'Crédito', 'value' => ''],
            ['nomenclature_id' => '', 'code' => '', 'name' => '', 'type' => 'Crédito', 'value' => ''],
            ['nomenclature_id' => '', 'code' => '', 'name' => '', 'type' => 'Crédito', 'value' => ''],
            ['nomenclature_id' => '', 'code' => '', 'name' => '', 'type' => 'Crédito', 'value' => ''],
        ];
    }

    public function updatedSelectedRuleId($value)
    {
        if (!$value) {
            return;
        }

        $rule = AccountingRule::find($value);
        if (!$rule) {
            return;
        }

        // Reset entries but keep structure
        $this->entries = [
            ['nomenclature_id' => '', 'code' => '', 'name' => '', 'type' => 'Débito', 'value' => ''],
            ['nomenclature_id' => '', 'code' => '', 'name' => '', 'type' => 'Crédito', 'value' => ''],
            ['nomenclature_id' => '', 'code' => '', 'name' => '', 'type' => 'Crédito', 'value' => ''],
            ['nomenclature_id' => '', 'code' => '', 'name' => '', 'type' => 'Crédito', 'value' => ''],
            ['nomenclature_id' => '', 'code' => '', 'name' => '', 'type' => 'Crédito', 'value' => ''],
            ['nomenclature_id' => '', 'code' => '', 'name' => '', 'type' => 'Crédito', 'value' => ''],
        ];

        // Fill based on rule - Account 1
        if ($rule->nomenclature_id_1) {
            $n1 = Nomenclature::find($rule->nomenclature_id_1);
            $this->entries[0] = [
                'nomenclature_id' => $n1->id,
                'code' => $n1->code,
                'name' => $n1->name,
                'type' => $rule->nature_1 ?? 'Débito',
                'value' => '',
            ];
        }

        // Account 2
        if ($rule->nomenclature_id_2) {
            $n2 = Nomenclature::find($rule->nomenclature_id_2);
            $this->entries[1] = [
                'nomenclature_id' => $n2->id,
                'code' => $n2->code,
                'name' => $n2->name,
                'type' => $rule->nature_2 ?? 'Crédito',
                'value' => '',
            ];
        }

        // Account 3
        if ($rule->nomenclature_id_3) {
            $n3 = Nomenclature::find($rule->nomenclature_id_3);
            $this->entries[2] = [
                'nomenclature_id' => $n3->id,
                'code' => $n3->code,
                'name' => $n3->name,
                'type' => $rule->nature_3 ?? 'Crédito',
                'value' => '',
            ];
        }

        // Account 4
        if ($rule->nomenclature_id_4) {
            $n4 = Nomenclature::find($rule->nomenclature_id_4);
            $this->entries[3] = [
                'nomenclature_id' => $n4->id,
                'code' => $n4->code,
                'name' => $n4->name,
                'type' => $rule->nature_4 ?? 'Crédito',
                'value' => '',
            ];
        }

        // Account 5
        if ($rule->nomenclature_id_5) {
            $n5 = Nomenclature::find($rule->nomenclature_id_5);
            $this->entries[4] = [
                'nomenclature_id' => $n5->id,
                'code' => $n5->code,
                'name' => $n5->name,
                'type' => $rule->nature_5 ?? 'Crédito',
                'value' => '',
            ];
        }

         // Account 6
        if ($rule->nomenclature_id_6) {
            $n6 = Nomenclature::find($rule->nomenclature_id_6);
            $this->entries[5] = [
                'nomenclature_id' => $n6->id,
                'code' => $n6->code,
                'name' => $n6->name,
                'type' => $rule->nature_6 ?? 'Crédito',
                'value' => '',
            ];
        }
    }

    public function updatedEntries($value, $key)
    {
        // $key like '0.code'
        $parts = explode('.', $key);
        $index = $parts[0];
        if (count($parts) == 2 && $parts[1] === 'code') {

            $code = $this->entries[$index]['code'];
            $nomenclature = Nomenclature::where('code', $code)->first();

            if ($nomenclature) {
                $this->entries[$index]['nomenclature_id'] = $nomenclature->id;
                $this->entries[$index]['name'] = $nomenclature->name;
            } else {
                $this->entries[$index]['nomenclature_id'] = '';
                $this->entries[$index]['name'] = '';
            }
        }

        /* lógica venta */
        if ($this->entries[1]['type'] === 'Crédito' && str_starts_with($this->entries[2]['code'], '2')  && $this->entries[3]['code'] === '') {
            if ($index == 0 && $this->entries[0]['value'] > 0) {
                $this->entries[1]['value'] = round($this->entries[0]['value'] / 1.19, 2);
                $this->entries[2]['value'] = round($this->entries[1]['value'] * 0.19, 2);
            }

            if ($index == 1) {

                if ($this->entries[0]['value'] === $this->entries[1]['value']) {
                    $this->entries['2']['value'] = 0;
                } else {
                    $this->entries['2']['value'] = $this->entries[0]['value'] - $this->entries[1]['value'];
                }
            }
            return;
        }

        /* lógica traslado */
        if ($this->entries[1]['type'] === 'Crédito' && $this->entries[3]['code'] === '') {
            if ($index == 0) {
                $this->entries[1]['value'] = $this->entries[0]['value'];
            }
        }

        /* lógica nota y hacer factura */
        if ($this->entries[1]['type'] === 'Débito' && str_starts_with($this->entries[1]['code'], '2') && str_starts_with($this->entries[3]['code'], '2')) {

            if ($index == 0 && $this->entries[0]['value'] > 0) {
                $this->entries[1]['value'] = round($this->entries[0]['value'] * 0.19, 2);
                $this->entries[2]['value'] = $this->entries[0]['value'];
                $this->entries[3]['value'] = round($this->entries[2]['value'] * 0.19, 2);
            }

            if ($index == 2 && $this->entries[2]['value'] > 0) {
                $this->entries[3]['value'] = round($this->entries[2]['value'] * 0.19, 2);
            }

            return;
        }

        /* lógica Compra */
        if ($this->entries[1]['type'] === 'Débito' && str_starts_with($this->entries[1]['code'], '2')) {

            if ($index == 0 && $this->entries[0]['value'] > 0) {
                $this->entries[1]['value'] = round($this->entries[0]['value'] * 0.19, 2);
                $this->entries['2']['value'] = $this->entries[0]['value'] + $this->entries[1]['value'];
            }

            if ($index == 1) {

                if (!$this->entries[1]['value']) {
                    $this->entries[1]['value'] = 0;
                }
                $this->entries['2']['value'] = $this->entries[0]['value'] + $this->entries[1]['value'];
            }
        }
    }

    public function create()
    {
        $this->authorize('crear asientos de diario');
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit($id)
    {
        $this->authorize('editar asientos de diario');
        $this->resetForm();
        $this->editing_diary_id = $id;

        $parent = Diary::with('children', 'nomenclature')->find($id);

        if (!$parent)
            return;

        $this->date = $parent->date->format('Y-m-d');
        $this->invoice_number = $parent->invoice_number;
        $this->description = $parent->description;

        // Populate entries
        // Row 1: Parent
        $this->entries[0] = [
            'nomenclature_id' => $parent->nomenclature_id,
            'code' => $parent->nomenclature ? $parent->nomenclature->code : '',
            'name' => $parent->nomenclature ? $parent->nomenclature->name : '',
            'type' => $parent->debit > 0 ? 'Débito' : 'Crédito',
            'value' => $parent->debit > 0 ? $parent->debit : $parent->credit,
        ];

        // Children
        foreach ($parent->children as $index => $child) {
            // Mapping child to rows 1..4 (since 0 is parent)
            $row_index = $index + 1;
            if ($row_index < 5) {
                $this->entries[$row_index] = [
                    'nomenclature_id' => $child->nomenclature_id,
                    'code' => $child->nomenclature ? $child->nomenclature->code : '',
                    'name' => $child->nomenclature ? $child->nomenclature->name : '',
                    'type' => $child->debit > 0 ? 'Débito' : 'Crédito',
                    'value' => $child->debit > 0 ? $child->debit : $child->credit,
                ];
            }
        }

        $this->showModal = true;
    }

    public $showStartMonthConfirmation = false;
    public $pendingSave = false;

    public $showDateRestrictionError = false;
    public $restrictionDate = null; // To store the date to show in message

    public function checkDateAndSave()
    {
        $this->validate([
            'date' => 'required|date',
        ]);

        $maxDate = Diary::max('date');

        // If there are no records, just save
        if (!$maxDate) {
            $this->save();
            return;
        }

        $inputDate = \Carbon\Carbon::parse($this->date);
        $lastRecordedDate = \Carbon\Carbon::parse($maxDate);

        // Check if input date is in a month prior to the last recorded date's month
        // We compare the first day of the months
        if ($inputDate->startOfMonth()->lt($lastRecordedDate->copy()->startOfMonth())) {

            // Check Permission to bypass date restriction
            if (auth()->user()->can('omitir restriccion de fecha')) {
                // User with permission gets a warning
                $this->showStartMonthConfirmation = true;
                $this->pendingSave = true;
            } else {
                // Other users are blocked - Show Blocking Modal
                $this->restrictionDate = $lastRecordedDate->format('F Y');
                $this->showDateRestrictionError = true;
            }
            return;
        }

        $this->save();
    }

    public function confirmSave()
    {
        $this->showStartMonthConfirmation = false;
        if ($this->pendingSave) {
            $this->save();
            $this->pendingSave = false;
        }
    }

    public function save()
    {
        if ($this->editing_diary_id) {
            $this->authorize('editar asientos de diario');
        } else {
            $this->authorize('crear asientos de diario');
        }

        $this->validate([
            'date' => 'required|date',
            // Basic validation for entries - at least one entry?
            // User implies parent is first.
        ]);

        // Filter empty entries (those without nomenclature_id or code, or with value <= 0)
        $valid_entries = array_filter($this->entries, function ($e) {
            return !empty($e['code']) && !empty($e['nomenclature_id']) && floatval($e['value'] ?? 0) > 0;
        });

        if (empty($valid_entries)) {
             $this->dispatch('show-toast', message: 'Debe agregar al menos un registro.', variant: 'danger');
             return;
        }

        // Calculate totals
        $total_debit = 0;
        $total_credit = 0;

        foreach ($valid_entries as $e) {
            $val = floatval($e['value']);
            if ($e['type'] === 'Débito') {
                $total_debit += $val;
            } else {
                $total_credit += $val;
            }
        }

        if (abs($total_debit - $total_credit) > 0.01) {
             $this->dispatch('show-toast', message: 'La partida doble no cuadra. Débito: ' . number_format($total_debit, 2) . ' - Crédito: ' . number_format($total_credit, 2), variant: 'danger');
             return;
        }

        DB::transaction(function () use ($valid_entries) {
            // If editing, delete old children? Or update? 
            // Simpler to delete children and recreate for the parent update logic, 
            // OR strictly update. User said "parent is the form of knowing who is related".
            // If we are editing, we update the parent, delete existing children, and create new ones.

            $parent_entry = null;

            if ($this->editing_diary_id) {
                $parent_entry = Diary::find($this->editing_diary_id);
                // Delete children
                $parent_entry->children()->delete();
            } else {
                $parent_entry = new Diary();
            }

            // First entry from the FORM is the parent, as requested.
            // But wait, array_filter reindexes? NO. keys are preserved.
            // We need the first VALID entry to act as parent? Or strictly Row 0?
            // User says "primer campo coloque el código PUC... al guardar el primero es el padre".
            // So Row 0 is Parent.

            $row0 = $this->entries[0];
            if (empty($row0['code']) || floatval($row0['value'] ?? 0) <= 0) {
                 $this->dispatch('show-toast', message: 'El primer registro (Padre) con valor es obligatorio.', variant: 'danger');
                 throw new \Exception('Missing parent');
            }

            $parent_entry->date = $this->date;
            $parent_entry->invoice_number = $this->invoice_number;
            $parent_entry->description = $this->description;
            $parent_entry->nomenclature_id = $row0['nomenclature_id'];

            $val = floatval($row0['value']);
            if ($row0['type'] === 'Débito') {
                $parent_entry->debit = $val;
                $parent_entry->credit = 0;
            } else {
                $parent_entry->debit = 0;
                $parent_entry->credit = $val;
            }
            $parent_entry->save();

            // Children
            foreach ($this->entries as $index => $row) {
                if ($index === 0)
                    continue; // Skip parent
                if (empty($row['code']) || floatval($row['value'] ?? 0) <= 0)
                    continue; // Skip empty or zero-value

                $child = new Diary();
                $child->parent_id = $parent_entry->id;
                $child->date = $this->date;
                $child->invoice_number = $this->invoice_number;
                $child->description = $this->description;
                $child->nomenclature_id = $row['nomenclature_id'];

                $val = floatval($row['value']);
                if ($row['type'] === 'Débito') {
                    $child->debit = $val;
                    $child->credit = 0;
                } else {
                    $child->debit = 0;
                    $child->credit = $val;
                }
                $child->save();
            }
        });

        $this->showModal = false;
        $this->dispatch('show-toast', message: 'Registro guardado correctamente.', variant: 'success');
    }

    #[Computed]
    public function records()
    {
        $query = Diary::whereNull('parent_id')
            ->with(['nomenclature', 'children'])
            ->orderBy('date', 'desc')
            ->orderBy('id', 'desc');

        if ($this->searchDateFrom) {
            $query->whereDate('date', '>=', $this->searchDateFrom);
        }

        if ($this->searchDateTo) {
            $query->whereDate('date', '<=', $this->searchDateTo);
        }

        if ($this->searchInvoiceNumber) {
            $query->where('invoice_number', 'like', '%' . $this->searchInvoiceNumber . '%');
        }

        if ($this->searchNomenclatureId) {
            $id = $this->searchNomenclatureId;
            $query->where(function ($q) use ($id) {
                // Parent matches
                $q->where('nomenclature_id', $id)
                    // OR any child matches
                    ->orWhereHas('children', function ($sq) use ($id) {
                        $sq->where('nomenclature_id', $id);
                    });
            });
        }

        return $query->paginate(10);
    }

    #[Computed]
    public function nomenclatures()
    {
        return Nomenclature::select('id', 'code', 'name')
            ->orderBy('code')
            ->get();
    }

    #[Computed]
    public function accountingRules()
    {
        return AccountingRule::orderBy('name')->get();;
    }

    #[Computed]
    public function totalDebit()
    {
        return collect($this->entries)->where('type', 'Débito')->sum(fn($row) => floatval($row['value']));
    }

    #[Computed]
    public function totalCredit()
    {
        return collect($this->entries)->where('type', 'Crédito')->sum(fn($row) => floatval($row['value']));
    }

    public function render()
    {
        return view('livewire.diary-manager');
    }
}
