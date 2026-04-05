<div class="p-4 sm:p-6">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-slate-900 dark:text-white">Libro Mayor</h1>
            <p class="text-sm text-slate-500 dark:text-zinc-500">Resumen de saldos por cuenta</p>
        </div>

        <div class="flex flex-col sm:flex-row items-stretch sm:items-end gap-2 w-full sm:w-auto">
            {{-- Multi-select de cuentas --}}
            <div
                x-data="{
                    open: false,
                    search: '',
                    selected: @entangle('nomenclatureIds'),
                    options: @js($this->nomenclatures->map(fn($n) => ['id' => $n->id, 'label' => $n->code . ' - ' . $n->name])->values()->toArray()),
                    get filtered() {
                        if (!this.search) return this.options;
                        const q = this.search.toLowerCase();
                        return this.options.filter(o => o.label.toLowerCase().includes(q));
                    },
                    get label() {
                        const n = this.selected ? this.selected.length : 0;
                        if (n === 0) return 'Todas las cuentas';
                        return n + ' cuenta' + (n > 1 ? 's' : '') + ' seleccionada' + (n > 1 ? 's' : '');
                    },
                    toggle(id) {
                        const strId = String(id);
                        const idx = (this.selected || []).findIndex(s => String(s) === strId);
                        if (idx === -1) { this.selected = [...(this.selected || []), id]; }
                        else { this.selected = (this.selected || []).filter((_, i) => i !== idx); }
                    },
                    isChecked(id) {
                        return (this.selected || []).some(s => String(s) === String(id));
                    }
                }"
                x-on:keydown.escape.window="open = false; search = ''"
                x-on:click.outside="open = false; search = ''"
                class="relative w-full sm:w-64"
            >
                <div class="flex flex-col gap-1">
                    <span class="text-xs font-medium text-slate-600 dark:text-zinc-400">Cuentas
                        <span x-show="selected && selected.length > 0"
                              class="ml-1 inline-flex items-center justify-center w-4 h-4 rounded-full bg-indigo-600 text-white text-[10px] font-bold"
                              x-text="selected.length"></span>
                    </span>
                    <button type="button" x-on:click="open = !open"
                        class="w-full flex items-center justify-between gap-2 rounded-lg border bg-white dark:bg-zinc-800 px-3 h-10 text-sm text-left shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all"
                        :class="selected && selected.length > 0
                            ? 'border-indigo-400 dark:border-indigo-600 text-slate-900 dark:text-white'
                            : 'border-zinc-300 dark:border-zinc-700 text-slate-400 dark:text-zinc-500'">
                        <span x-text="label" class="truncate flex-1 text-sm"></span>
                        <span class="flex items-center gap-1 shrink-0">
                            <span x-show="selected && selected.length > 0"
                                  x-on:click.stop="selected = []; search = ''"
                                  title="Limpiar"
                                  class="flex items-center justify-center w-5 h-5 rounded-full bg-slate-200 dark:bg-zinc-600 hover:bg-red-100 dark:hover:bg-red-900/40 hover:text-red-500 text-slate-500 dark:text-zinc-300 cursor-pointer transition-colors">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 12 12"><path d="M6.707 6l2.647-2.646a.5.5 0 00-.708-.708L6 5.293 3.354 2.646a.5.5 0 10-.708.708L5.293 6 2.646 8.646a.5.5 0 00.708.708L6 6.707l2.646 2.647a.5.5 0 00.708-.708L6.707 6z"/></svg>
                            </span>
                            <svg class="w-4 h-4 text-slate-400 dark:text-zinc-500 transition-transform duration-200"
                                 :class="open ? 'rotate-180' : ''"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </span>
                    </button>
                </div>

                {{-- Dropdown panel --}}
                <div x-show="open"
                     x-transition:enter="transition ease-out duration-150"
                     x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                     x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-100"
                     x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                     x-transition:leave-end="opacity-0 scale-95 -translate-y-1"
                     class="absolute right-0 z-50 mt-1 w-full min-w-[360px] rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 shadow-2xl overflow-hidden origin-top-right"
                     style="display:none;">

                    {{-- Search --}}
                    <div class="p-2 border-b border-zinc-100 dark:border-zinc-800">
                        <div class="relative">
                            <svg class="absolute left-2.5 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
                            </svg>
                            <input x-model="search" x-on:click.stop type="text"
                                   placeholder="Buscar cuenta..."
                                   class="w-full pl-8 pr-3 py-1.5 text-sm rounded-lg border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-zinc-500 focus:outline-none focus:ring-2 focus:ring-indigo-500"/>
                        </div>
                    </div>

                    {{-- Options --}}
                    <ul class="max-h-64 overflow-y-auto py-1" style="scrollbar-width:thin;">
                        <template x-for="option in filtered" :key="option.id">
                            <li x-on:click="toggle(option.id)"
                                class="flex items-center gap-2.5 px-3 py-2 text-sm cursor-pointer select-none transition-colors"
                                :class="isChecked(option.id)
                                    ? 'bg-indigo-50 dark:bg-indigo-900/20 text-indigo-700 dark:text-indigo-300'
                                    : 'text-slate-800 dark:text-zinc-200 hover:bg-slate-50 dark:hover:bg-zinc-800'">
                                <span class="flex-shrink-0 w-4 h-4 rounded border transition-all flex items-center justify-center"
                                      :class="isChecked(option.id)
                                          ? 'bg-indigo-600 border-indigo-600'
                                          : 'border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-800'">
                                    <svg x-show="isChecked(option.id)" class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </span>
                                <span x-text="option.label" class="leading-snug flex-1"></span>
                            </li>
                        </template>
                        <li x-show="filtered.length === 0"
                            class="px-4 py-5 text-sm text-center text-slate-400 dark:text-zinc-500 italic">
                            Sin resultados
                        </li>
                    </ul>

                    {{-- Footer --}}
                    <div class="flex items-center justify-between px-3 py-2 border-t border-zinc-100 dark:border-zinc-800 bg-slate-50 dark:bg-zinc-800/60">
                        <span class="text-xs text-slate-500 dark:text-zinc-400"
                              x-text="(!selected || selected.length === 0) ? 'Sin filtro — mostrando todas' : selected.length + ' cuenta' + (selected.length === 1 ? '' : 's') + ' seleccionada' + (selected.length === 1 ? '' : 's')"></span>
                        <button x-show="selected && selected.length > 0"
                                type="button" x-on:click="selected = []; search = ''"
                                class="text-xs font-semibold text-red-500 hover:text-red-600 transition-colors">
                            Limpiar
                        </button>
                    </div>
                </div>
            </div>

            <flux:input type="date" wire:model.live="start_date" label="Desde" />
            <flux:input type="date" wire:model.live="end_date" label="Hasta" />
            <div class="flex items-end">
                <flux:button wire:click="updateLedger" icon="arrow-path" class="w-full sm:w-auto h-10">Actualizar Saldos</flux:button>
            </div>
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