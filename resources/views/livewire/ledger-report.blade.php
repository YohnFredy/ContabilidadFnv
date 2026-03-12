<div class="p-4 sm:p-6">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-slate-900 dark:text-white">Libro Mayor</h1>
            <p class="text-sm text-slate-500 dark:text-zinc-500">Resumen de saldos por cuenta</p>
        </div>

        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 w-full sm:w-auto">
            <flux:input type="date" wire:model.live="start_date" label="Desde" />
            <flux:input type="date" wire:model.live="end_date" label="Hasta" />
            <flux:button wire:click="updateLedger" icon="arrow-path" class="w-full sm:w-auto">Actualizar Saldos</flux:button>
        </div>
    </div>

    <div
        class="overflow-hidden bg-white shadow-sm dark:bg-zinc-900 rounded-lg border border-slate-200 dark:border-zinc-700">
        <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200 dark:divide-zinc-700">
            <thead class="bg-slate-50 dark:bg-zinc-800">
                <tr>
                    <th scope="col"
                        class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider dark:text-zinc-400">
                        Código PUC</th>
                    <th scope="col"
                        class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider dark:text-zinc-400 hidden sm:table-cell">
                        Nombre Cuenta</th>
                    <th scope="col"
                        class="px-3 sm:px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider dark:text-zinc-400">
                        Débito</th>
                    <th scope="col"
                        class="px-3 sm:px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider dark:text-zinc-400">
                        Crédito</th>
                    <th scope="col"
                        class="px-3 sm:px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider dark:text-zinc-400 hidden md:table-cell">
                        Saldo Deudor</th>
                    <th scope="col"
                        class="px-3 sm:px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider dark:text-zinc-400 hidden md:table-cell">
                        Saldo Acreedor</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-slate-200 dark:bg-zinc-900 dark:divide-zinc-700">
                @foreach ($this->ledgerEntries as $entry)
                    <tr wire:key="ledger-{{ $entry->code }}">
                        <td
                            class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900 dark:text-white font-mono">
                            {{ $entry->code }}</td>
                        <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm text-slate-900 dark:text-white hidden sm:table-cell">{{ $entry->name }}
                        </td>
                        <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm text-slate-900 dark:text-white text-right">
                            {{ number_format($entry->debit, 2) }}</td>
                        <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm text-slate-900 dark:text-white text-right">
                            {{ number_format($entry->credit, 2) }}</td>
                        <td
                            class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm text-right font-medium text-blue-600 dark:text-blue-400 hidden md:table-cell">
                            {{ number_format($entry->debtor_balance, 2) }}
                        </td>
                        <td
                            class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm text-right font-medium text-orange-600 dark:text-orange-400 hidden md:table-cell">
                            {{ number_format($entry->creditor_balance, 2) }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot class="bg-slate-50 dark:bg-zinc-800 font-bold">
                <tr>
                    <td colspan="2" class="px-3 sm:px-6 py-4 text-sm text-right text-slate-900 dark:text-white hidden sm:table-cell">TOTALES:</td>
                    <td class="px-3 sm:px-6 py-4 text-sm text-right text-slate-900 dark:text-white sm:hidden">TOTALES:</td>
                    <td class="px-3 sm:px-6 py-4 text-sm text-right text-slate-900 dark:text-white">
                        {{ number_format($this->totals['debit'], 2) }}</td>
                    <td class="px-3 sm:px-6 py-4 text-sm text-right text-slate-900 dark:text-white">
                        {{ number_format($this->totals['credit'], 2) }}</td>
                    <td class="px-3 sm:px-6 py-4 text-sm text-right text-slate-900 dark:text-white hidden md:table-cell">
                        {{ number_format($this->totals['debtor'], 2) }}</td>
                    <td class="px-3 sm:px-6 py-4 text-sm text-right text-slate-900 dark:text-white hidden md:table-cell">
                        {{ number_format($this->totals['creditor'], 2) }}</td>
                </tr>
            </tfoot>
        </table>
        </div>
    </div>

    <div class="mt-6 flex justify-end">
        @php
            $diffDebtorCreditor = abs($this->totals['debtor'] - $this->totals['creditor']);
            // Wait, Balance Sheet logic: Assets = Labs + Equity. 
            // Users request: "si los datos son iguales diga algo como ok". 
            // Usually Debits = Credits. Debtor Balance total might NOT equal Creditor Balance total unless it's a closed trial balance.
            // But Trial Balance (Balance de Prueba) usually sums Debits = Credits.
            // Let's assume user wants to check if Total Debits from columns = Total Credits from columns?
            // User Text: "Total Deudores y el Total Acreedores, que si los datos son iguales diga algo como ok"
            // So we compare Total Saldos Deudores vs Total Saldos Acreedores.
        @endphp

        @if(abs($this->totals['debtor'] - $this->totals['creditor']) < 0.01)
            <div
                class="flex items-center gap-2 text-green-600 dark:text-green-400 bg-green-50 dark:bg-green-900/30 px-4 py-2 rounded-lg border border-green-200 dark:border-green-800">
                <flux:icon name="check-circle" class="w-6 h-6" />
                <span class="font-bold text-lg">Balance OK</span>
            </div>
        @else
            <div
                class="flex items-center gap-2 text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-900/30 px-4 py-2 rounded-lg border border-red-200 dark:border-red-800">
                <flux:icon name="exclamation-triangle" class="w-6 h-6" />
                <span class="font-bold text-lg">Error: Diferencia de
                    {{ number_format(abs($this->totals['debtor'] - $this->totals['creditor']), 2) }}</span>
            </div>
        @endif
    </div>
</div>