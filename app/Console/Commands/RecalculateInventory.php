<?php

namespace App\Console\Commands;

use App\Models\InventoryMovement;
use App\Models\Product;
use Illuminate\Console\Command;

class RecalculateInventory extends Command
{
    protected $signature = 'inventory:recalculate {--debug} {--product=}';
    protected $description = 'Recalculate inventory_value for all products based on movements';

    public function handle()
    {
        $productId = $this->option('product');
        
        if ($productId) {
            $product = Product::find($productId);
            if (!$product) {
                $this->error("Product not found");
                return 1;
            }
            
            $this->info("Movements for {$product->name}:");
            $movements = $product->movements()
                ->orderBy('movement_date', 'asc')
                ->orderBy('id', 'asc')
                ->get();
                
            foreach ($movements as $m) {
                $this->line("{$m->id} {$m->type} qty={$m->quantity} unit={$m->unit_cost} total={$m->total_cost} date={$m->movement_date}");
            }
            
            $this->newLine();
            $this->info("Recalculating...");
            $product->recalculateFromMovements();
            
            $this->info("After recalculation:");
            $product->refresh();
            foreach ($product->movements()->orderBy('movement_date', 'asc')->orderBy('id', 'asc')->get() as $m) {
                $m->refresh();
                $this->line("{$m->id} {$m->type} qty={$m->quantity} unit={$m->unit_cost} total={$m->total_cost}");
            }
            $this->info("Product: stock={$product->current_stock}, inv_value={$product->inventory_value}, avg_cost={$product->current_avg_cost}");
            return 0;
        }
        
        $products = Product::all();
        $count = $products->count();
        
        $this->info("Recalculating inventory for {$count} products...");
        
        foreach ($products as $product) {
            $product->recalculateFromMovements();
            
            if ($this->option('debug')) {
                $this->line("Product {$product->name}: stock={$product->current_stock}, inv_value={$product->inventory_value}, avg_cost={$product->current_avg_cost}");
            }
        }
        
        $this->info("Done! Recalculated {$count} products.");
        
        return 0;
    }
}
