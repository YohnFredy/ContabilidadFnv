<?php

namespace App\Livewire;

use App\Models\Diary;
use Livewire\Attributes\Computed;
use Livewire\Component;

class ResultsReport extends Component
{
    public $start_date = '2025-06-01';
    public $end_date;

    public function mount()
    {
        /*  $this->start_date = now()->startOfMonth()->format('Y-m-d'); */
        $this->end_date = now()->endOfMonth()->format('Y-m-d');
    }

    #[Computed]
    public function results()
    {
        $query = Diary::query()
            ->join('nomenclatures', 'diaries.nomenclature_id', '=', 'nomenclatures.id');

        if ($this->start_date) {
            $query->whereDate('diaries.date', '>=', $this->start_date);
        }

        if ($this->end_date) {
            $query->whereDate('diaries.date', '<=', $this->end_date);
        }

        $entries = $query->selectRaw('
                nomenclatures.code, 
                nomenclatures.name,
                sum(diaries.debit) as total_debit, 
                sum(diaries.credit) as total_credit
            ')
            ->where(function ($q) {
                $q->where('nomenclatures.code', 'like', '4%')
                    ->orWhere('nomenclatures.code', 'like', '5%')
                    ->orWhere('nomenclatures.code', 'like', '6%');
            })
            ->groupBy('nomenclatures.code', 'nomenclatures.name')
            ->get();

        // Calculate totals based on class
        $income = 0; // Class 4 (Crédito - Débito)
        $expenses = 0; // Class 5 (Débito - Crédito)
        $costs = 0; // Class 6 (Débito - Crédito)

        $incomeDetails = [];

        foreach ($entries as $entry) {
            $debit = floatval($entry->total_debit);
            $credit = floatval($entry->total_credit);

            if (str_starts_with($entry->code, '4')) {
                $amount = ($credit - $debit);
                $income += $amount;
                // Only add to details if amount is non-zero
                if (abs($amount) > 0.01) {
                    $incomeDetails[] = [
                        'code' => $entry->code,
                        'name' => $entry->name ?? 'Cuenta ' . $entry->code, // Fallback if name is missing but it should be there due to query
                        'amount' => $amount
                    ];
                }
            } elseif (str_starts_with($entry->code, '5')) {
                $expenses += ($debit - $credit);
            } elseif (str_starts_with($entry->code, '6')) {
                $costs += ($debit - $credit);
            }
        }

        $grossProfit = $income - $costs;
        $netResult = $grossProfit - $expenses;

        // Percentages (based on Total Income)
        $grossMargin = $income > 0 ? ($grossProfit / $income) * 100 : 0;
        $netMargin = $income > 0 ? ($netResult / $income) * 100 : 0;

        return [
            'income' => $income,
            'income_details' => $incomeDetails,
            'costs' => $costs,
            'expenses' => $expenses,
            'gross_profit' => $grossProfit,
            'net_result' => $netResult,
            'gross_margin' => $grossMargin,
            'net_margin' => $netMargin,
        ];
    }

    public function render()
    {
        return view('livewire.results-report');
    }
}
