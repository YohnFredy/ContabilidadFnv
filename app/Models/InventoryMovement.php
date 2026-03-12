<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class InventoryMovement extends Model
{
    protected $fillable = [
        'product_id',
        'type',
        'quantity',
        'unit_cost',
        'total_cost',
        'reference',
        'notes',
        'user_id',
        'movement_date',
    ];

    protected $casts = [
        'quantity' => 'decimal:4',
        'unit_cost' => 'decimal:6',
        'total_cost' => 'decimal:6',
        'movement_date' => 'datetime',
    ];

    public const TYPES = [
        'entrada' => 'Entrada',
        'salida' => 'Salida',
        'ajuste_positivo' => 'Ajuste (+)',
        'ajuste_negativo' => 'Ajuste (-)',
    ];

    public const ENTRY_TYPES = ['entrada', 'ajuste_positivo'];
    public const EXIT_TYPES = ['salida', 'ajuste_negativo'];

    /**
     * Get the product
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the user who created the movement
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Boot method for model events
     */
    protected static function booted(): void
    {
        static::creating(function (InventoryMovement $movement) {
            $movement->calculateMovement();
        });

        static::created(function (InventoryMovement $movement) {
            $movement->updateProductStock();
        });

        // Use deleting to capture the date BEFORE the movement is deleted
        static::deleting(function (InventoryMovement $movement) {
            // Store the date for use in the deleted event
            $movement->_deletedDate = $movement->movement_date->format('Y-m-d H:i:s');
        });

        static::deleted(function (InventoryMovement $movement) {
            // Use the stored date to only recalculate from that point forward
            $fromDate = $movement->_deletedDate ?? null;
            $movement->product->recalculateFromMovements($fromDate);
        });
    }

    /**
     * Calculate movement fields based on type and quantity
     * 
     * New logic:
     * - Entry: total_cost = quantity × unit_cost
     * - Exit: unit_cost = current_avg_cost, total_cost = quantity × unit_cost
     */
    public function calculateMovement(): void
    {
        $product = Product::find($this->product_id);

        if (!$product) {
            return;
        }

        // Set user if not set
        if (!$this->user_id && Auth::check()) {
            $this->user_id = Auth::id();
        }

        // For exits, use current average cost
        if (in_array($this->type, self::EXIT_TYPES)) {
            $this->unit_cost = $product->current_avg_cost;
        }

        // Calculate total cost
        $this->total_cost = $this->quantity * $this->unit_cost;
    }

    /**
     * Update product stock after movement is saved
     * 
     * Entry: inventory_value += total_cost, stock += quantity
     * Exit: inventory_value -= total_cost, stock -= quantity
     * CPP = inventory_value / current_stock
     */
    public function updateProductStock(): void
    {
        $product = $this->product;
        
        if (in_array($this->type, self::ENTRY_TYPES)) {
            // Entry: add to inventory
            $product->inventory_value = $product->inventory_value + $this->total_cost;
            $product->current_stock = $product->current_stock + $this->quantity;
        } else {
            // Exit: subtract from inventory
            $product->inventory_value = $product->inventory_value - $this->total_cost;
            $product->current_stock = $product->current_stock - $this->quantity;
        }
        
        // Calculate new avg cost
        if ($product->current_stock > 0) {
            $product->current_avg_cost = $product->inventory_value / $product->current_stock;
        } else {
            $product->current_avg_cost = 0;
        }
        
        $product->save();
    }

    /**
     * Scope for entries
     */
    public function scopeEntries($query)
    {
        return $query->whereIn('type', self::ENTRY_TYPES);
    }

    /**
     * Scope for exits
     */
    public function scopeExits($query)
    {
        return $query->whereIn('type', self::EXIT_TYPES);
    }

    /**
     * Scope for date range
     */
    public function scopeDateRange($query, $from, $to)
    {
        return $query->whereBetween('movement_date', [$from, $to]);
    }

    /**
     * Get the type label
     */
    public function getTypeLabelAttribute(): string
    {
        return self::TYPES[$this->type] ?? $this->type;
    }

    /**
     * Check if this is an entry movement
     */
    public function getIsEntryAttribute(): bool
    {
        return in_array($this->type, self::ENTRY_TYPES);
    }
}

