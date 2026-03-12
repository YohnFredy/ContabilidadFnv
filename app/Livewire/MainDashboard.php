<?php

namespace App\Livewire;

use App\Models\Diary;
use App\Models\InventoryMovement;
use App\Models\Nomenclature;
use App\Models\Product;
use Carbon\Carbon;
use Livewire\Attributes\Computed;
use Livewire\Component;

class MainDashboard extends Component
{
    public $period = 'month'; // week, month, year

    public function mount()
    {
        $this->period = 'month';
    }

    public function getDateRange()
    {
        return match ($this->period) {
            'week' => [now()->startOfWeek(), now()->endOfWeek()],
            'month' => [now()->startOfMonth(), now()->endOfMonth()],
            'year' => [now()->startOfYear(), now()->endOfYear()],
            default => [now()->startOfMonth(), now()->endOfMonth()],
        };
    }

    #[Computed]
    public function totalAssets()
    {
        // PUC codes starting with 1 = Assets
        return Nomenclature::where('code', 'like', '1%')
            ->whereRaw('LENGTH(code) >= 6')
            ->get()
            ->sum(function ($nom) {
                $debits = Diary::where('nomenclature_id', $nom->id)->sum('debit');
                $credits = Diary::where('nomenclature_id', $nom->id)->sum('credit');
                return $debits - $credits;
            });
    }

    #[Computed]
    public function totalLiabilities()
    {
        // PUC codes starting with 2 = Liabilities
        return Nomenclature::where('code', 'like', '2%')
            ->whereRaw('LENGTH(code) >= 6')
            ->get()
            ->sum(function ($nom) {
                $debits = Diary::where('nomenclature_id', $nom->id)->sum('debit');
                $credits = Diary::where('nomenclature_id', $nom->id)->sum('credit');
                return $credits - $debits;
            });
    }

    #[Computed]
    public function totalEquity()
    {
        // PUC codes starting with 3 = Equity
        return Nomenclature::where('code', 'like', '3%')
            ->whereRaw('LENGTH(code) >= 6')
            ->get()
            ->sum(function ($nom) {
                $debits = Diary::where('nomenclature_id', $nom->id)->sum('debit');
                $credits = Diary::where('nomenclature_id', $nom->id)->sum('credit');
                return $credits - $debits;
            });
    }

    #[Computed]
    public function periodRevenue()
    {
        [$from, $to] = $this->getDateRange();
        // PUC codes starting with 4 = Revenue
        return Nomenclature::where('code', 'like', '4%')
            ->whereRaw('LENGTH(code) >= 6')
            ->get()
            ->sum(function ($nom) use ($from, $to) {
                $credits = Diary::where('nomenclature_id', $nom->id)
                    ->whereBetween('date', [$from, $to])
                    ->sum('credit');
                $debits = Diary::where('nomenclature_id', $nom->id)
                    ->whereBetween('date', [$from, $to])
                    ->sum('debit');
                return $credits - $debits;
            });
    }

    #[Computed]
    public function periodExpenses()
    {
        [$from, $to] = $this->getDateRange();
        // PUC codes starting with 5 (Gastos) and 6 (Costos)
        $expenses = Nomenclature::where(function ($q) {
            $q->where('code', 'like', '5%')
                ->orWhere('code', 'like', '6%');
        })
            ->whereRaw('LENGTH(code) >= 6')
            ->get()
            ->sum(function ($nom) use ($from, $to) {
                $debits = Diary::where('nomenclature_id', $nom->id)
                    ->whereBetween('date', [$from, $to])
                    ->sum('debit');
                $credits = Diary::where('nomenclature_id', $nom->id)
                    ->whereBetween('date', [$from, $to])
                    ->sum('credit');
                return $debits - $credits;
            });
        return $expenses;
    }

    #[Computed]
    public function netIncome()
    {
        return $this->periodRevenue - $this->periodExpenses;
    }

    #[Computed]
    public function totalInventoryValue()
    {
        return Product::active()->sum(\Illuminate\Support\Facades\DB::raw('current_stock * current_avg_cost'));
    }

    #[Computed]
    public function totalProducts()
    {
        return Product::count();
    }

    #[Computed]
    public function activeProducts()
    {
        return Product::active()->count();
    }

    #[Computed]
    public function lowStockCount()
    {
        return Product::active()->lowStock()->count();
    }

    #[Computed]
    public function periodInventoryMovements()
    {
        [$from, $to] = $this->getDateRange();
        return InventoryMovement::whereBetween('movement_date', [$from, $to])->count();
    }

    #[Computed]
    public function periodCostOfSales()
    {
        [$from, $to] = $this->getDateRange();
        return InventoryMovement::exits()
            ->whereBetween('movement_date', [$from, $to])
            ->sum('total_cost');
    }

    #[Computed]
    public function recentDiaryEntries()
    {
        return Diary::whereNull('parent_id')
            ->with('nomenclature')
            ->orderBy('date', 'desc')
            ->orderBy('id', 'desc')
            ->limit(5)
            ->get();
    }

    #[Computed]
    public function recentMovements()
    {
        return InventoryMovement::with('product')
            ->orderBy('movement_date', 'desc')
            ->limit(5)
            ->get();
    }

    #[Computed]
    public function topProductsByValue()
    {
        return Product::active()
            ->orderByRaw('current_stock * current_avg_cost DESC')
            ->limit(5)
            ->get();
    }

    #[Computed]
    public function lowStockProducts()
    {
        return Product::active()
            ->lowStock()
            ->orderBy('current_stock')
            ->limit(5)
            ->get();
    }

    #[Computed]
    public function monthlyRevenueData()
    {
        $data = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $from = $month->copy()->startOfMonth();
            $to = $month->copy()->endOfMonth();
            
            $revenue = Nomenclature::where('code', 'like', '4%')
                ->whereRaw('LENGTH(code) >= 6')
                ->get()
                ->sum(function ($nom) use ($from, $to) {
                    return Diary::where('nomenclature_id', $nom->id)
                        ->whereBetween('date', [$from, $to])
                        ->sum('credit');
                });
            
            $data[] = [
                'month' => $month->format('M'),
                'value' => $revenue,
            ];
        }
        return $data;
    }

    #[Computed]
    public function accountCounts()
    {
        return [
            'nomenclatures' => Nomenclature::count(),
            'diary_entries' => Diary::whereNull('parent_id')->count(),
            'total_debits' => Diary::sum('debit'),
            'total_credits' => Diary::sum('credit'),
        ];
    }

    public function render()
    {
        return view('livewire.main-dashboard');
    }
}
