<?php

namespace App\Livewire\Inventory;

use App\Models\Diary;
use App\Models\InventoryMovement;
use App\Models\Nomenclature;
use App\Models\Product;
use Flux\Flux;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Movements extends Component
{
    use WithPagination;

    public $search = '';
    public $productFilter = '';
    public $typeFilter = '';
    public $dateFrom = '2025-06-01';
    public $dateTo = '';

    // Modal state
    public $showModal = false;
    public $editMode = false;
    public $movementId = null;

    // Form fields
    public $product_id = '';
    public $type = 'entrada';
    public $quantity = '';
    public $unit_cost = '';
    public $reference = '';
    public $notes = '';
    public $movement_date = '';

    // Cost Calculator
    public $showCalculator = false;
    public $calculatorItems = [];
    public $calculatorDate = '';
    public $calculatorReference = '';

    // Batch Entry (for Entradas)
    public $entryItems = [];

    protected function rules()
    {
        if ($this->type === 'entrada' && !$this->editMode) {
            return [
                'movement_date' => 'required|date',
                'reference' => 'nullable|string|max:100',
                'notes' => 'nullable|string',
                'entryItems' => 'required|array|min:1',
                'entryItems.*.product_id' => 'required|exists:products,id',
                'entryItems.*.quantity' => 'required|numeric|min:0.0001',
                'entryItems.*.unit_cost' => 'required|numeric|min:0',
            ];
        }

        $rules = [
            'product_id' => 'required|exists:products,id',
            'type' => 'required|in:' . implode(',', array_keys(InventoryMovement::TYPES)),
            'quantity' => 'required|numeric|min:0.0001',
            'reference' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
            'movement_date' => 'required|date',
        ];

        // Unit cost is required only for entries
        if (in_array($this->type, InventoryMovement::ENTRY_TYPES)) {
            $rules['unit_cost'] = 'required|numeric|min:0';
        }

        return $rules;
    }

    public function mount()
    {
        $this->movement_date = now()->format('Y-m-d\TH:i');
        $this->dateTo = now()->format('Y-m-d');
        $this->calculatorDate = now()->format('Y-m-d');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function create()
    {
        $this->authorize('crear movimientos de inventario');
        $this->reset(['product_id', 'type', 'quantity', 'unit_cost', 'reference', 'notes', 'movementId', 'entryItems']);
        $this->type = 'entrada';
        $this->editMode = false;
        $this->movement_date = now()->format('Y-m-d\TH:i');
        
        // Initialize with one empty item for batch entry
        $this->addEntryItem();
        
        $this->showModal = true;
    }

    public function edit($id)
    {
        $this->authorize('editar movimientos de inventario');
        $movement = InventoryMovement::findOrFail($id);

        $this->movementId = $id;
        $this->product_id = $movement->product_id;
        $this->type = $movement->type;
        $this->quantity = $movement->quantity;
        $this->unit_cost = $movement->unit_cost;
        $this->reference = $movement->reference;
        $this->notes = $movement->notes;
        $this->movement_date = $movement->movement_date->format('Y-m-d\TH:i');

        $this->editMode = true;
        $this->showModal = true;
    }

    public $showMovementDateConfirmation = false;
    public $pendingMovementAction = null; // 'save' or 'processCalculator'

    public $showDateRestrictionError = false;
    public $restrictionDate = null;

    public function checkDateAndSave()
    {
        $this->validate(); // Pre-validate to ensure date is present

        if ($this->shouldWarnAboutDate($this->movement_date)) {
             if (auth()->user()->can('omitir restriccion de fecha')) {
                 $this->pendingMovementAction = 'save';
                 $this->showMovementDateConfirmation = true;
             } else {
                 $maxDate = InventoryMovement::max('movement_date');
                 $this->restrictionDate = \Carbon\Carbon::parse($maxDate)->format('d/m/Y');
                 $this->showDateRestrictionError = true;
             }
             return;
        }

        $this->save();
    }

    public function checkDateAndProcessCalculator()
    {
        // Basic validation for calculator date happens in processCalculatorSale, but we need date first
        if (empty($this->calculatorDate)) {
             $this->dispatch('show-toast', message: 'La fecha es requerida.', variant: 'danger');
             return;
        }

        if ($this->shouldWarnAboutDate($this->calculatorDate)) {
             if (auth()->user()->can('omitir restriccion de fecha')) {
                 $this->pendingMovementAction = 'processCalculator';
                 $this->showMovementDateConfirmation = true;
             } else {
                 $maxDate = InventoryMovement::max('movement_date');
                 $this->restrictionDate = \Carbon\Carbon::parse($maxDate)->format('d/m/Y');
                 $this->showDateRestrictionError = true;
             }
             return;
        }

        $this->processCalculatorSale();
    }

    public function confirmMovementAction()
    {
        $this->showMovementDateConfirmation = false;

        if ($this->pendingMovementAction === 'save') {
            $this->save();
        } elseif ($this->pendingMovementAction === 'processCalculator') {
            $this->processCalculatorSale();
        }
        
        $this->pendingMovementAction = null;
    }

    protected function shouldWarnAboutDate($inputDateStr)
    {
        $maxDate = InventoryMovement::max('movement_date');
        
        if (!$maxDate) {
            return false;
        }

        $inputDate = \Carbon\Carbon::parse($inputDateStr);
        $lastRecordedDate = \Carbon\Carbon::parse($maxDate);

        // Compare start of months
        return $inputDate->startOfMonth()->lt($lastRecordedDate->copy()->startOfMonth());
    }

    public function save()
    {
        if ($this->editMode) {
            $this->authorize('editar movimientos de inventario');
        } else {
             $this->authorize('crear movimientos de inventario');
        }

        $this->validate();

        DB::transaction(function () {
            if ($this->editMode && $this->movementId) {
                // Editing existing movement - update in place and recalculate
                $movement = InventoryMovement::findOrFail($this->movementId);
                $product = $movement->product;
                
                // Get the earliest date affected (old or new movement date)
                $oldDate = $movement->movement_date->format('Y-m-d H:i:s');
                $newDate = $this->movement_date;
                $fromDate = min($oldDate, $newDate);
                
                // Update the movement fields
                $movement->product_id = $this->product_id;
                $movement->type = $this->type;
                $movement->quantity = $this->quantity;
                $movement->reference = $this->reference;
                $movement->notes = $this->notes;
                $movement->movement_date = $this->movement_date;
                
                // For entries, use provided cost; for exits, cost will be recalculated
                if (in_array($this->type, InventoryMovement::ENTRY_TYPES)) {
                    $movement->unit_cost = $this->unit_cost;
                    $movement->total_cost = $this->quantity * $this->unit_cost;
                }
                
                $movement->saveQuietly(); // Save without triggering events
                
                // Recalculate from the earliest affected date
                $product->recalculateFromMovements($fromDate);
                
            } elseif ($this->type === 'entrada') {
                // Batch entry for new entries
                foreach ($this->entryItems as $item) {
                    $product = Product::findOrFail($item['product_id']);
                    $this->createMovement($product, $item['quantity'], $item['unit_cost']);
                }
            } else {
                // Single movement for other types (salida, ajuste)
                $product = Product::findOrFail($this->product_id);

                // For exits, check if there's enough stock
                if (in_array($this->type, InventoryMovement::EXIT_TYPES)) {
                    if ($product->current_stock < $this->quantity) {
                        throw new \Exception('Stock insuficiente para ' . $product->name . '. Disponible: ' . number_format((float)$product->current_stock, 4));
                    }
                }


                $this->createMovement($product, $this->quantity, $this->unit_cost);
            }
        });

        $this->showModal = false;
        $this->reset(['product_id', 'type', 'quantity', 'unit_cost', 'reference', 'notes', 'movementId', 'editMode', 'entryItems']);
    }

    protected function createMovement($product, $qty, $cost)
    {
        return InventoryMovement::create([
            'product_id' => $product->id,
            'type' => $this->type,
            'quantity' => $qty,
            'unit_cost' => in_array($this->type, InventoryMovement::ENTRY_TYPES)
                ? $cost
                : $product->current_avg_cost,
            'reference' => $this->reference,
            'notes' => $this->notes,
            'movement_date' => $this->movement_date,
        ]);
    }

    public function delete($id)
    {
        $this->authorize('eliminar movimientos de inventario');
        $movement = InventoryMovement::findOrFail($id);
        $movement->delete();
    }

    public function getSelectedProductProperty()
    {
        if ($this->product_id) {
            return Product::find($this->product_id);
        }
        return null;
    }

    // Batch Entry Methods
    public function addEntryItem()
    {
        array_unshift($this->entryItems, [
            'product_id' => '',
            'quantity' => '',
            'unit_cost' => '',
        ]);
    }

    public function removeEntryItem($index)
    {
        unset($this->entryItems[$index]);
        $this->entryItems = array_values($this->entryItems);
        
        if (empty($this->entryItems)) {
            $this->addEntryItem();
        }
    }

    public function getEntryTotalProperty()
    {
        $total = 0;
        foreach ($this->entryItems as $item) {
            if (!empty($item['quantity']) && !empty($item['unit_cost'])) {
                $total += (float)$item['quantity'] * (float)$item['unit_cost'];
            }
        }
        return $total;
    }

    // Cost Calculator Methods
    public function openCalculator()
    {
        $this->authorize('registrar ventas');
        $this->calculatorItems = [];
        $this->calculatorDate = now()->format('Y-m-d');
        $this->calculatorReference = '';
        $this->showCalculator = true;
    }

    public function addCalculatorItem()
    {
        array_unshift($this->calculatorItems, [
            'product_id' => '',
            'quantity' => 1,
        ]);
    }

    public function removeCalculatorItem($index)
    {
        unset($this->calculatorItems[$index]);
        $this->calculatorItems = array_values($this->calculatorItems);
    }

    public function getCalculatorTotalProperty()
    {
        $total = 0;
        foreach ($this->calculatorItems as $item) {
            if (!empty($item['product_id']) && !empty($item['quantity'])) {
                $product = Product::find($item['product_id']);
                if ($product) {
                    $total += $product->current_avg_cost * $item['quantity'];
                }
            }
        }
        return $total;
    }

    public function getCalculatorItemsDetailedProperty()
    {
        $items = [];
        foreach ($this->calculatorItems as $index => $item) {
            if (!empty($item['product_id'])) {
                $product = Product::find($item['product_id']);
                if ($product) {
                    $items[$index] = [
                        'product' => $product,
                        'quantity' => $item['quantity'] ?? 0,
                        'unit_cost' => $product->current_avg_cost,
                        'subtotal' => $product->current_avg_cost * ($item['quantity'] ?? 0),
                    ];
                }
            }
        }
        return $items;
    }

    /**
     * Process the calculator: create inventory exits and diary entries
     */
    public function processCalculatorSale()
    {
        $this->authorize('registrar ventas');
        // Validate we have items
        $validItems = array_filter($this->calculatorItems, function ($item) {
            return !empty($item['product_id']) && !empty($item['quantity']) && $item['quantity'] > 0;
        });

        if (empty($validItems)) {
            $this->dispatch('show-toast', message: 'Debe agregar al menos un producto.', variant: 'danger');
            return;
        }

        // Validate date
        if (empty($this->calculatorDate)) {
            $this->dispatch('show-toast', message: 'La fecha es requerida.', variant: 'danger');
            return;
        }

        // Validate stock availability
        foreach ($validItems as $item) {
            $product = Product::find($item['product_id']);
            if ($product && $product->current_stock < $item['quantity']) {
                $this->dispatch('show-toast', message: "Stock insuficiente para {$product->name}. Disponible: " . number_format((float)$product->current_stock, 2), variant: 'danger');
                return;
            }
        }

        // Get nomenclatures for diary entries
        $nomenclature6135 = Nomenclature::where('code', '6135')->first();
        $nomenclature1435 = Nomenclature::where('code', '1435')->first();

        if (!$nomenclature6135 || !$nomenclature1435) {
            $this->dispatch('show-toast', message: 'No se encontraron las cuentas PUC 6135 o 1435. Verifique la nomenclatura.', variant: 'danger');
            return;
        }

        $totalCost = $this->calculatorTotal;

        DB::transaction(function () use ($validItems, $nomenclature6135, $nomenclature1435, $totalCost) {
            $productNames = [];

            // 1. Create inventory movements (exits) for each product
            foreach ($validItems as $item) {
                $product = Product::find($item['product_id']);
                if ($product) {
                    $productNames[] = $product->name . ' x' . $item['quantity'];
                    
                    InventoryMovement::create([
                        'product_id' => $product->id,
                        'type' => 'salida',
                        'quantity' => $item['quantity'],
                        'unit_cost' => $product->current_avg_cost,
                        'reference' => $this->calculatorReference,
                        'notes' => 'Venta desde calculadora de costos',
                        'movement_date' => $this->calculatorDate . ' ' . now()->format('H:i:s'),
                    ]);
                }
            }

            $description = 'Costo de venta: ' . implode(', ', $productNames);

            // 2. Create diary entry for 6135 (Debit - Costo de mercancías vendidas)
            $parentDiary = Diary::create([
                'date' => $this->calculatorDate,
                'nomenclature_id' => $nomenclature6135->id,
                'debit' => $totalCost,
                'credit' => 0,
                'invoice_number' => $this->calculatorReference,
                'description' => $description,
                'parent_id' => null,
            ]);

            // 3. Create diary entry for 1435 (Credit - Mercancías no fabricadas por la empresa)
            Diary::create([
                'date' => $this->calculatorDate,
                'nomenclature_id' => $nomenclature1435->id,
                'debit' => 0,
                'credit' => $totalCost,
                'invoice_number' => $this->calculatorReference,
                'description' => $description,
                'parent_id' => $parentDiary->id,
            ]);
        });

        $this->showCalculator = false;
        $this->reset(['calculatorItems', 'calculatorDate', 'calculatorReference']);
        
        $this->dispatch('show-toast', message: 'Salida de inventario y asiento contable registrados correctamente.', variant: 'success');
    }

    public function render()
    {
        $movements = InventoryMovement::query()
            ->with(['product', 'user'])
            ->when($this->search, function ($query) {
                $query->whereHas('product', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('code', 'like', '%' . $this->search . '%');
                })
                    ->orWhere('reference', 'like', '%' . $this->search . '%');
            })
            ->when($this->productFilter, function ($query) {
                $query->where('product_id', $this->productFilter);
            })
            ->when($this->typeFilter, function ($query) {
                $query->where('type', $this->typeFilter);
            })
            ->when($this->dateFrom, function ($query) {
                $query->whereDate('movement_date', '>=', $this->dateFrom);
            })
            ->when($this->dateTo, function ($query) {
                $query->whereDate('movement_date', '<=', $this->dateTo);
            })
            ->orderBy('movement_date', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(20);

        $products = Product::active()->orderBy('name')->get();

        return view('livewire.inventory.movements', [
            'movements' => $movements,
            'products' => $products,
            'types' => InventoryMovement::TYPES,
        ]);
    }
}
