<?php

namespace App\Livewire\Inventory;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class Products extends Component
{
    use WithPagination;

    public $search = '';
    public $categoryFilter = '';
    public $statusFilter = '';

    // Modal state
    public $showModal = false;
    public $editMode = false;
    public $productId;

    // Form fields
    public $code = '';
    public $name = '';
    public $description = '';
    public $unit = 'UND';
    public $category = 'Producto Terminado';
    public $min_stock = 0;
    public $is_active = true;

    protected function rules()
    {
        return [
            'code' => 'required|string|max:50|unique:products,code,' . $this->productId,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'unit' => 'required|string|in:' . implode(',', array_keys(Product::UNITS)),
            'category' => 'nullable|string|in:' . implode(',', Product::CATEGORIES),
            'min_stock' => 'required|numeric|min:0',
            'is_active' => 'boolean',
        ];
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function create()
    {
        $this->authorize('crear productos');
        $this->reset(['code', 'name', 'description', 'unit', 'category', 'min_stock', 'is_active', 'productId']);
        $this->unit = 'UND';
        $this->is_active = true;
        $this->min_stock = 0;
        $this->editMode = false;
        $this->showModal = true;
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $this->productId = $id;
        $this->code = $product->code;
        $this->name = $product->name;
        $this->description = $product->description;
        $this->unit = $product->unit;
        $this->category = $product->category;
        $this->min_stock = $product->min_stock;
        $this->is_active = $product->is_active;

        $this->editMode = true;
        $this->showModal = true;
    }

    public function save()
    {
        if (!$this->editMode) {
             $this->authorize('crear productos');
        }
        
        $this->validate();

        Product::updateOrCreate(
            ['id' => $this->productId],
            [
                'code' => $this->code,
                'name' => $this->name,
                'description' => $this->description,
                'unit' => $this->unit,
                'category' => $this->category,
                'min_stock' => $this->min_stock,
                'is_active' => $this->is_active,
            ]
        );

        $this->showModal = false;
        $this->reset(['code', 'name', 'description', 'unit', 'category', 'min_stock', 'is_active', 'productId']);
    }

    public function delete($id)
    {
        $product = Product::findOrFail($id);

        // Check if product has movements
        if ($product->movements()->count() > 0) {
            session()->flash('error', 'No se puede eliminar un producto con movimientos registrados.');
            return;
        }

        $product->delete();
    }

    public function toggleStatus($id)
    {
        $product = Product::findOrFail($id);
        $product->is_active = !$product->is_active;
        $product->save();
    }

    public function render()
    {
        $products = Product::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('code', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->categoryFilter, function ($query) {
                $query->where('category', $this->categoryFilter);
            })
            ->when($this->statusFilter !== '', function ($query) {
                $query->where('is_active', $this->statusFilter === 'active');
            })
            ->orderBy('current_stock', 'desc')
            ->orderBy('code')
            ->paginate(15);

        return view('livewire.inventory.products', [
            'products' => $products,
            'units' => Product::UNITS,
            'categories' => Product::CATEGORIES,
        ]);
    }
}
