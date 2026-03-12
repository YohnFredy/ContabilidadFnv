<?php

namespace App\Livewire;

use App\Models\Diary;
use App\Models\Ledger;
use App\Models\Nomenclature;
use Flux\Flux;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Component;

class LedgerReport extends Component
{
    public $start_date = '2025-06-01';
    public $end_date;

    public function mount()
    {
        // Default to current month or year? Let's say empty for "All Time" or current month.
        // User asked for "Desde fecha xxx hasta fecha xxx". 
        // Defaulting to "All Time" initially for the persistent table view, or current month for filtering.
        /*  $this->start_date = now()->startOfMonth()->format('Y-m-d'); */
        $this->end_date = now()->endOfMonth()->format('Y-m-d');
    }

    public function updateLedger()
    {
        // Button "Actualizar la suma del libro diario"
        // Truncate ledgers and re-populate from ALL TIME diaries for robust consistency.

        DB::transaction(function () {
            Ledger::query()->delete();

            // Group by nomenclature, sum debit/credit
            // Get all 'children' entries or all entries?
            // "Diary" entries with values are what matters.
            // If parent has value, include it. If children have values, include them.
            // Basically any Diary entry with value.

            $sums = Diary::selectRaw('nomenclature_id, sum(debit) as total_debit, sum(credit) as total_credit')
                ->groupBy('nomenclature_id')
                ->get();

            foreach ($sums as $sum) {
                if ($sum->total_debit == 0 && $sum->total_credit == 0)
                    continue;

                Ledger::create([
                    'nomenclature_id' => $sum->nomenclature_id,
                    'debit' => $sum->total_debit,
                    'credit' => $sum->total_credit,
                ]);
            }
        });

        Flux::toast('Libro Mayor actualizado correctamente.');
    }

    #[Computed]
    public function ledgerEntries()
    {
        // If filtering by date, we calculate dynamically from Diary
        // If "All Time" (no dates? or specific flag?), we could use Ledger table.
        // User asked for "mostrar el libro mayor dependido de la fecha".
        // The Ledger TABLE is updated by the button. 
        // The VIEW should probably reflect the filter.

        // Strategy:
        // Always query Diary for the specific range to show in the table. 
        // The "Ledger" table might be just for caching or "Official" closed periods, 
        // OR the user wants the "Update" button to just refresh what is seen.
        // User: "que tega un botón para actualizar la suma ... por si de pronto hay alguna inconsistencia".
        // This implies the standard view might come from 'ledgers' table, but date filter overrides it?

        // Let's implement:
        // 1. Query Diary filters by date.
        // 2. Return collection formatted like Ledger items.

        $query = Diary::query();

        if ($this->start_date) {
            $query->whereDate('date', '>=', $this->start_date);
        }

        if ($this->end_date) {
            $query->whereDate('date', '<=', $this->end_date);
        }

        $sums = $query->selectRaw('nomenclature_id, sum(debit) as total_debit, sum(credit) as total_credit')
            ->whereNotNull('nomenclature_id')
            ->groupBy('nomenclature_id')
            ->with('nomenclature') // Load name
            ->get();

        // Transform to behave like Ledger models with accessors
        return $sums->map(function ($item) {
            // We need to hydrate a Ledger object or similar to use the accessors, 
            // or just calculate here.

            $debit = floatval($item->total_debit);
            $credit = floatval($item->total_credit);

            return (object) [
                'code' => $item->nomenclature->code,
                'name' => $item->nomenclature->name,
                'debit' => $debit,
                'credit' => $credit,
                'debtor_balance' => $debit > $credit ? $debit - $credit : 0,
                'creditor_balance' => $credit > $debit ? $credit - $debit : 0,
            ];
        })->sortBy('code');
    }

    #[Computed]
    public function totals()
    {
        $entries = $this->ledgerEntries();
        return [
            'debit' => $entries->sum('debit'),
            'credit' => $entries->sum('credit'),
            'debtor' => $entries->sum('debtor_balance'),
            'creditor' => $entries->sum('creditor_balance'),
        ];
    }

    public function render()
    {
        return view('livewire.ledger-report');
    }
}
