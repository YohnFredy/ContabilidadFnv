<?php

namespace App\Livewire\Inventory;

use App\Models\InventoryMovement;
use App\Models\Product;
use Livewire\Component;

class Dashboard extends Component
{
    public $dateFrom = '2025-06-01';
    public $dateTo = '';

    public function mount()
    {
        $this->dateTo = now()->format('Y-m-d');
    }

    public function getTotalProductsProperty()
    {
        return Product::count();
    }

    public function getActiveProductsProperty()
    {
        return Product::active()->count();
    }

    public function getTotalInventoryValueProperty()
    {
        return Product::active()->get()->sum(function ($product) {
            return $product->current_stock * $product->current_avg_cost;
        });
    }

    public function getTotalStockUnitsProperty()
    {
        return Product::active()->sum('current_stock');
    }

    public function getLowStockProductsProperty()
    {
        return Product::active()
            ->lowStock()
            ->where('min_stock', '>', 0)
            ->orderBy('current_stock')
            ->limit(10)
            ->get();
    }

    public function getRecentMovementsProperty()
    {
        return InventoryMovement::with(['product', 'user'])
            ->orderBy('movement_date', 'desc')
            ->orderBy('id', 'desc')
            ->limit(10)
            ->get();
    }

    public function getCostOfGoodsSoldProperty()
    {
        return InventoryMovement::whereIn('type', InventoryMovement::EXIT_TYPES)
            ->whereDate('movement_date', '>=', $this->dateFrom)
            ->whereDate('movement_date', '<=', $this->dateTo)
            ->sum('total_cost');
    }

    public function getTotalEntriesProperty()
    {
        return InventoryMovement::whereIn('type', InventoryMovement::ENTRY_TYPES)
            ->whereDate('movement_date', '>=', $this->dateFrom)
            ->whereDate('movement_date', '<=', $this->dateTo)
            ->sum('total_cost');
    }

    public function getMovementCountProperty()
    {
        return InventoryMovement::whereDate('movement_date', '>=', $this->dateFrom)
            ->whereDate('movement_date', '<=', $this->dateTo)
            ->count();
    }

    public function getTopProductsByValueProperty()
    {
        return Product::active()
            ->where('current_stock', '>', 0)
            ->get()
            ->sortByDesc(function ($product) {
                return $product->current_stock * $product->current_avg_cost;
            })
            ->take(5);
    }

    public function render()
    {
        return view('livewire.inventory.dashboard');
    }
}
