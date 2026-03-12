<?php

namespace App\Livewire;

use App\Models\Diary;
use Livewire\Attributes\Computed;
use Livewire\Component;

class BalanceSheetReport extends Component
{
    public $start_date = '2025-06-01';
    public $end_date;

    public function mount()
    {
        /*   $this->start_date = now()->startOfMonth()->format('Y-m-d'); */
        $this->end_date = now()->endOfMonth()->format('Y-m-d');
    }

    #[Computed]
    public function reportData()
    {
        $query = Diary::query()
            ->join('nomenclatures', 'diaries.nomenclature_id', '=', 'nomenclatures.id');

        if ($this->start_date) {
            $query->whereDate('diaries.date', '>=', $this->start_date);
        }

        if ($this->end_date) {
            $query->whereDate('diaries.date', '<=', $this->end_date);
        }

        // Get all data grouped by code/nomenclature
        $entries = $query->selectRaw('
                nomenclatures.id as nomenclature_id,
                nomenclatures.code,
                nomenclatures.name,
                nomenclatures.category,
                sum(diaries.debit) as total_debit, 
                sum(diaries.credit) as total_credit
            ')
            ->groupBy('nomenclatures.id', 'nomenclatures.code', 'nomenclatures.name', 'nomenclatures.category')
            ->get();

        // Containers
        $assetsCurrent = collect();
        $assetsNonCurrent = collect();
        $liabilitiesCurrent = collect();
        $equity = collect();

        $income = 0;
        $costs = 0;
        $expenses = 0;

        foreach ($entries as $entry) {
            $debit = floatval($entry->total_debit);
            $credit = floatval($entry->total_credit);

            // Calculate Balance based on nature
            // Asset: Debit - Credit
            // Liability/Equity: Credit - Debit

            if (str_starts_with($entry->code, '1')) {
                // Assets
                $val = $debit - $credit;
                if ($val == 0)
                    continue;

                $item = ['code' => $entry->code, 'name' => $entry->name, 'value' => $val];

                if ($entry->category === 'Activos Corrientes') {
                    $assetsCurrent->push($item);
                } elseif ($entry->category === 'Activos No Corrientes') {
                    $assetsNonCurrent->push($item);
                } else {
                    // Fallback if category missing but starts with 1? 
                    // Assume Current if unknown? Or check user requirements.
                    // User said: "en Activos Corrientes van todos ... que tienen la 'category' Activos Corrientes"
                    // If no category, maybe skip or warning? Let's put in Current for now as failsafe.
                    if (str_starts_with($entry->category ?? '', 'Activos No')) {
                        $assetsNonCurrent->push($item);
                    } else {
                        $assetsCurrent->push($item);
                    }
                }
            } elseif (str_starts_with($entry->code, '2')) {
                // Liabilities
                $val = $credit - $debit;
                if ($val == 0)
                    continue;

                // Exclude 240810
                /*  if ($entry->code === '240810')
                    continue; */

                $item = ['code' => $entry->code, 'name' => $entry->name, 'value' => $val];

                if ($entry->category === 'Pasivos Corrientes') {
                    $liabilitiesCurrent->push($item);
                } else {
                    // Assume Current for now
                    $liabilitiesCurrent->push($item);
                }
            } elseif (str_starts_with($entry->code, '3')) {
                // Equity
                $val = $credit - $debit;
                if ($val == 0)
                    continue;

                $item = ['code' => $entry->code, 'name' => $entry->name, 'value' => $val];
                $equity->push($item);
            } elseif (str_starts_with($entry->code, '4')) {
                $income += ($credit - $debit);
            } elseif (str_starts_with($entry->code, '5')) {
                $expenses += ($debit - $credit);
            } elseif (str_starts_with($entry->code, '6')) {
                $costs += ($debit - $credit);
            }
        }

        // Net Result Calculation for Balance Sheet
        $netResult = $income - $costs - $expenses;

        // Totals
        $totalAssetsCurrent = $assetsCurrent->sum('value');
        $totalAssetsNonCurrent = $assetsNonCurrent->sum('value');
        $totalLiabilities = $liabilitiesCurrent->sum('value');
        $totalEquity = $equity->sum('value') + $netResult; // Include result in equity total

        return [
            'assets_current' => $assetsCurrent->sortBy('code'),
            'total_assets_current' => $totalAssetsCurrent,

            'assets_non_current' => $assetsNonCurrent->sortBy('code'),
            'total_assets_non_current' => $totalAssetsNonCurrent,

            'total_assets' => $totalAssetsCurrent + $totalAssetsNonCurrent,

            'liabilities_current' => $liabilitiesCurrent->sortBy('code'),
            'total_liabilities' => $totalLiabilities,

            'equity' => $equity->sortBy('code'),
            'net_result' => $netResult,
            'total_equity' => $totalEquity,

            'total_equity_and_liabilities' => $totalLiabilities + $totalEquity,
        ];
    }

    public function render()
    {
        return view('livewire.balance-sheet-report');
    }
}
