<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    protected $fillable = [
        'code',
        'name',
        'description',
        'unit',
        'category',
        'min_stock',
        'is_active',
        'current_stock',
        'current_avg_cost',
        'inventory_value',
    ];

    protected $casts = [
        'min_stock' => 'decimal:4',
        'current_stock' => 'decimal:4',
        'current_avg_cost' => 'decimal:6',
        'inventory_value' => 'decimal:6',
        'is_active' => 'boolean',
    ];

    public const UNITS = [
        'UND' => 'Unidad',
        'KG' => 'Kilogramo',
        'LT' => 'Litro',
        'MT' => 'Metro',
        'M2' => 'Metro²',
        'M3' => 'Metro³',
        'GL' => 'Galón',
        'CJ' => 'Caja',
        'PQ' => 'Paquete',
        'BL' => 'Bolsa',
    ];

    public const CATEGORIES = [
        'Materia Prima',
        'Producto Terminado',
        'Producto en Proceso',
        'Insumos',
        'Repuestos',
        'Empaques',
        'Otros',
    ];

    /**
     * Get all movements for this product
     */
    public function movements(): HasMany
    {
        return $this->hasMany(InventoryMovement::class)->orderBy('movement_date', 'desc');
    }

    /**
     * Check if stock is below minimum
     */
    public function getIsLowStockAttribute(): bool
    {
        return $this->current_stock <= $this->min_stock;
    }

    /**
     * Scope for active products
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for low stock products
     */
    public function scopeLowStock($query)
    {
        return $query->whereColumn('current_stock', '<=', 'min_stock');
    }

    /**
     * Recalculate stock and CPP from all movements chronologically
     * This is used when a movement is deleted or edited
     * 
     * @param string|null $fromDate Only save movements from this date forward (optimization)
     * 
     * Logic: 
     * - Entry: inventory_value += total_cost, stock += quantity
     * - Exit: inventory_value -= total_cost, stock -= quantity
     * - CPP = inventory_value / current_stock
     */
    public function recalculateFromMovements(?string $fromDate = null): void
    {
        DB::transaction(function () use ($fromDate) {
            // Get all movements ordered by date (oldest first)
            // Use reorder() to clear the default DESC ordering from the relation
            $movements = $this->movements()
                ->reorder()
                ->orderBy('movement_date', 'asc')
                ->orderBy('id', 'asc')
                ->get();

            if ($movements->isEmpty()) {
                $this->current_stock = 0;
                $this->current_avg_cost = 0;
                $this->inventory_value = 0;
                $this->save();
                return;
            }

            // Recalculate
            $runningStock = 0;
            $runningInventoryValue = 0;
            $shouldSave = ($fromDate === null); // If no fromDate, save all

            foreach ($movements as $movement) {
                // Start saving when we reach or pass the fromDate
                if (!$shouldSave && $fromDate !== null) {
                    if ($movement->movement_date->format('Y-m-d H:i:s') >= $fromDate) {
                        $shouldSave = true;
                    }
                }

                if (in_array($movement->type, InventoryMovement::ENTRY_TYPES)) {
                    // Entry: Add to inventory
                    $totalCost = $movement->quantity * $movement->unit_cost;
                    $runningStock += $movement->quantity;
                    $runningInventoryValue += $totalCost;
                    $movement->total_cost = $totalCost;
                } else {
                    // Exit: Use avg cost at the time, subtract from inventory
                    $avgCost = $runningStock > 0 ? $runningInventoryValue / $runningStock : 0;
                    $movement->unit_cost = $avgCost;
                    $totalCost = $movement->quantity * $avgCost;
                    $runningStock -= $movement->quantity;
                    $runningInventoryValue -= $totalCost;
                    $movement->total_cost = $totalCost;
                }

                // Only save if we're past the fromDate
                if ($shouldSave) {
                    $movement->saveQuietly();
                }
            }

            // Update product with final values
            $this->current_stock = $runningStock;
            $this->inventory_value = $runningInventoryValue;
            $this->current_avg_cost = $runningStock > 0 ? $runningInventoryValue / $runningStock : 0;
            $this->save();
        });
    }
}
