<div class="p-4 sm:p-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <h1 class="text-xl sm:text-2xl font-bold text-slate-900 dark:text-white">Libro Diario</h1>
        @can('crear asientos de diario')
        <flux:button variant="primary" wire:click="create" class="w-full sm:w-auto">Agregar Asiento</flux:button>
        @endcan
    </div>

    <!-- Filters -->
    <div class="grid grid-cols-1 sm:grid-cols-12 gap-4 mb-6">
        <div class="sm:col-span-2">
            <flux:field>
                <flux:label>Desde</flux:label>
                <flux:input type="date" wire:model="searchDateFrom" />
            </flux:field>
        </div>
        <div class="sm:col-span-2">
            <flux:field>
                <flux:label>Hasta</flux:label>
                <flux:input type="date" wire:model="searchDateTo" />
            </flux:field>
        </div>
        <div class="sm:col-span-3">
            <flux:field>
                <flux:label>Cuenta (Nomenclatura)</flux:label>
                <flux:select wire:model="searchNomenclatureId" placeholder="Todas las cuentas..." searchable>
                    <flux:select.option value="">Todas</flux:select.option>
                    @foreach ($this->nomenclatures as $nom)
                        <flux:select.option value="{{ $nom->id }}">{{ $nom->code }} - {{Str::limit($nom->name, 30)}}</flux:select.option>
                    @endforeach
                </flux:select>
            </flux:field>
        </div>
        <div class="sm:col-span-2">
            <flux:field>
                <flux:label>No. Factura</flux:label>
                <flux:input wire:model="searchInvoiceNumber" placeholder="Buscar..." />
            </flux:field>
        </div>
        <div class="sm:col-span-3 flex items-end gap-2">
            <flux:button variant="primary" wire:click="search" class="w-full" icon="magnifying-glass">Buscar</flux:button>
            <flux:button wire:click="clearFilters" icon="x-mark">Limpiar</flux:button>
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
                        Fecha</th>
                    <th scope="col"
                        class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider dark:text-zinc-400 hidden sm:table-cell">
                        Documento</th>
                    <th scope="col"
                        class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider dark:text-zinc-400 hidden md:table-cell">
                        Descripción</th>
                    <th scope="col"
                        class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider dark:text-zinc-400">
                        Detalle Contable</th>

                    <th scope="col"
                        class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider dark:text-zinc-400">
                        Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-slate-200 dark:bg-zinc-900 dark:divide-zinc-700">
                @foreach ($this->records as $diary)
                    <tr wire:key="diary-{{ $diary->id }}"
                        class="hover:bg-slate-50 dark:hover:bg-zinc-800/50 transition-colors">
                        <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm text-slate-900 dark:text-white align-top">
                            <div class="font-bold">{{ $diary->date->format('d/m/Y') }}</div>
                            <div class="text-xs text-slate-500 dark:text-zinc-500">{{ $diary->created_at->format('H:i') }}</div>
                        </td>
                        <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm text-slate-900 dark:text-white align-top hidden sm:table-cell">
                            @if($diary->invoice_number)
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 dark:bg-zinc-700 dark:text-zinc-200">
                                    {{ $diary->invoice_number }}
                                </span>
                            @else
                                <span class="text-slate-400 dark:text-zinc-500">-</span>
                            @endif
                        </td>
                        <td class="px-3 sm:px-6 py-4 text-sm text-slate-900 dark:text-white align-top max-w-xs truncate hidden md:table-cell"
                            title="{{ $diary->description }}">
                            {{ Str::limit($diary->description, 10) }}
                        </td>
                        <td class="px-3 sm:px-6 py-4 text-sm text-slate-900 dark:text-white align-top">
                            <div class="space-y-1 font-mono text-xs">
                                <!-- Parent -->
                                <div class="flex justify-between items-center {{ $diary->credit > 0 ? 'pl-4 sm:pl-8' : '' }}">
                                    <span>
                                        <span
                                            class="font-bold text-slate-700 dark:text-zinc-300">{{ $diary->nomenclature?->code }}</span>
                                        <span class="hidden sm:inline">{{ Str::limit($diary->nomenclature?->name, 60) }}</span>
                                    </span>
                                    <span
                                        class="font-bold {{ $diary->debit > 0 ? 'text-slate-900 dark:text-white' : 'text-slate-500 dark:text-zinc-500' }}">
                                        {{ number_format($diary->debit > 0 ? $diary->debit : $diary->credit, 2) }}
                                    </span>
                                </div>
                                <!-- Children -->
                                @foreach($diary->children as $child)
                                    <div class="flex justify-between items-center {{ $child->credit > 0 ? 'pl-4 sm:pl-8' : '' }}">
                                        <span>
                                            <span
                                                class="font-bold text-slate-700 dark:text-zinc-300">{{ $child->nomenclature?->code }}</span>
                                            <span class="hidden sm:inline">{{ Str::limit($child->nomenclature?->name, 60) }}</span>
                                        </span>
                                        <span
                                            class="font-bold {{ $child->debit > 0 ? 'text-slate-900 dark:text-white' : 'text-slate-500 dark:text-zinc-500' }}">
                                            {{ number_format($child->debit > 0 ? $child->debit : $child->credit, 2) }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </td>

                        <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <flux:button.group>
                                <flux:button size="sm" icon="eye" wire:click="view({{ $diary->id }})" />
                                @can('editar asientos de diario')
                                    <flux:button size="sm" icon="pencil-square" wire:click="edit({{ $diary->id }})" />
                                @endcan
                            </flux:button.group>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $this->records->links() }}
    </div>

    <flux:modal name="edit-diary" wire:model="showModal" class="w-full max-w-3xl mx-4 sm:mx-auto">
        <div class="space-y-6">
            <h2 class="text-lg font-bold text-slate-900 dark:text-white">{{ $editing_diary_id ? 'Editar Asiento' : 'Nuevo Asiento' }}</h2>

            <!-- Global Fields -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <flux:field>
                    <flux:label>Regla Contable (Pre-llenar)</flux:label>
                    <flux:select wire:model.live="selected_rule_id" placeholder="Seleccione una regla...">
                        <flux:select.option value="">Ninguna</flux:select.option>
                        @foreach ($this->accountingRules as $rule)
                            <flux:select.option value="{{ $rule->id }}">{{ $rule->name }}</flux:select.option>
                        @endforeach
                    </flux:select>
                </flux:field>

                <flux:field>
                    <flux:label>Fecha</flux:label>
                    <flux:input type="date" wire:model="date" />
                </flux:field>
            </div>

            <!-- Rows -->
            <div class="space-y-4">
                <div class="hidden sm:grid grid-cols-12 gap-2 text-sm font-medium text-slate-500 dark:text-zinc-500">
                    <div class="col-span-2">Código PUC</div>
                    <div class="col-span-4">Nombre Cuenta</div>
                    <div class="col-span-3">Tipo</div>
                    <div class="col-span-3">Valor</div>
                </div>

                @foreach ($entries as $index => $entry)
                    <div class="grid grid-cols-2 sm:grid-cols-12 gap-2 items-center p-3 sm:p-0 bg-slate-50 dark:bg-zinc-800 sm:bg-transparent dark:sm:bg-transparent rounded-lg sm:rounded-none" wire:key="row-{{ $index }}">
                        <div class="col-span-1 sm:col-span-2">
                            <label class="sm:hidden text-xs text-slate-500 dark:text-zinc-500 mb-1 block">Código</label>
                            <flux:input wire:model.live="entries.{{ $index }}.code" placeholder="Código" />
                        </div>
                        <div class="col-span-1 sm:col-span-4">
                            <label class="sm:hidden text-xs text-slate-500 dark:text-zinc-500 mb-1 block">Cuenta</label>
                            <flux:input wire:model="entries.{{ $index }}.name" readonly  />
                        </div>
                        <div class="col-span-1 sm:col-span-3">
                            <label class="sm:hidden text-xs text-slate-500 dark:text-zinc-500 mb-1 block">Tipo</label>
                            <flux:select wire:model="entries.{{ $index }}.type">
                                <flux:select.option value="Débito">Débito</flux:select.option>
                                <flux:select.option value="Crédito">Crédito</flux:select.option>
                            </flux:select>
                        </div>
                        <div class="col-span-1 sm:col-span-3">
                            <label class="sm:hidden text-xs text-slate-500 dark:text-zinc-500 mb-1 block">Valor</label>
                            <flux:input type="number" step="0.01" wire:model.blur="entries.{{ $index }}.value"
                                 />
                        </div>
                    </div>
                @endforeach
                <!-- Totals -->
                <div class="flex justify-end border-t border-slate-200 dark:border-zinc-700 pt-4 mt-2">
                    <div class="grid grid-cols-2 gap-4 sm:gap-8 text-sm">
                        <div class="text-right">
                            <span class="text-slate-500 dark:text-zinc-500 block">Total Débito</span>
                            <span
                                class="font-bold font-mono text-lg {{ abs($this->totalDebit - $this->totalCredit) < 0.01 ? 'text-green-600 dark:text-white' : 'text-slate-900 dark:text-white' }}">
                                {{ number_format($this->totalDebit, 2) }}
                            </span>
                        </div>
                        <div class="text-right">
                            <span class="text-slate-500 dark:text-zinc-500 block">Total Crédito</span>
                            <span
                                class="font-bold font-mono text-lg {{ abs($this->totalDebit - $this->totalCredit) < 0.01 ? 'text-green-600 dark:text-white' : 'text-red-500 dark:text-zinc-400' }}">
                                {{ number_format($this->totalCredit, 2) }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="text-right text-xs mt-1">
                    @if(abs($this->totalDebit - $this->totalCredit) > 0.01)
                        <span class="text-red-500 dark:text-zinc-400 font-medium">Diferencia:
                            {{ number_format(abs($this->totalDebit - $this->totalCredit), 2) }}</span>
                    @else
                        <span class="text-green-600 dark:text-white font-medium">Partida Balanceada</span>
                    @endif
                </div>
            </div>

            <!-- Footer Fields -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <flux:field>
                    <flux:label>No. Factura</flux:label>
                    <flux:input wire:model="invoice_number" placeholder="Opcional" />
                </flux:field>

                <flux:field>
                    <flux:label>Descripción</flux:label>
                    <flux:textarea wire:model="description" placeholder="Descripción del asiento..." />
                </flux:field>
            </div>

            <div class="flex justify-end gap-2">
                <flux:button wire:click="$set('showModal', false)">Cancelar</flux:button>
                <flux:button variant="primary" wire:click="checkDateAndSave">Guardar</flux:button>
            </div>
        </div>
    </flux:modal>

    <flux:modal name="confirm-date-save" wire:model="showStartMonthConfirmation" class="w-full max-w-md mx-auto">
        <div class="space-y-6">
            <div>
                <h2 class="text-lg font-bold dark:text-white text-red-600">⚠ Advertencia de Fecha</h2>
                <p class="text-sm text-slate-500 dark:text-zinc-500 mt-2">
                    Está intentando guardar un registro en un <strong>mes anterior</strong> al último registrado en el sistema.
                </p>
                <p class="text-sm text-slate-500 dark:text-zinc-500 mt-2">
                    Esto podría afectar el orden cronológico de los registros. ¿Está seguro que desea continuar?
                </p>
            </div>

            <div class="flex justify-end gap-2">
                <flux:button wire:click="$set('showStartMonthConfirmation', false)">Cancelar</flux:button>
                <flux:button variant="danger" wire:click="confirmSave">Continuar y Guardar</flux:button>
            </div>
        </div>
    </flux:modal>

    <flux:modal name="view-diary" wire:model="showViewModal" class="w-full max-w-3xl mx-4 sm:mx-auto">
        @if($viewingDiary)
            <div class="space-y-6">
                <!-- Header -->
                <div class="border-b border-slate-200 dark:border-zinc-700 pb-4">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-start gap-4">
                        <div>
                            <h2 class="text-xl font-bold text-slate-900 dark:text-white">Detalle de Asiento</h2>
                            <p class="text-sm text-slate-500 dark:text-zinc-500">Comprobante Contable</p>
                        </div>
                        <div class="text-left sm:text-right">
                            <div class="font-mono font-bold text-lg text-slate-900 dark:text-white">{{ $viewingDiary->date->format('d/m/Y') }}</div>
                            @if($viewingDiary->invoice_number)
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 dark:bg-zinc-700 dark:text-zinc-200 mt-1">
                                    Doc: {{ $viewingDiary->invoice_number }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <div class="bg-slate-50 dark:bg-zinc-800/50 p-4 rounded-lg">
                    <h3 class="text-xs font-bold text-slate-500 dark:text-zinc-500 uppercase tracking-wider mb-2">Descripción</h3>
                    <p class="text-sm text-slate-900 dark:text-white">{{ $viewingDiary->description }}</p>
                </div>

                <!-- Accounting Details Table -->
                <div class="overflow-hidden border border-slate-200 dark:border-zinc-700 rounded-lg">
                    <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 dark:divide-zinc-700">
                        <thead class="bg-slate-50 dark:bg-zinc-800">
                            <tr>
                                <th scope="col"
                                    class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider dark:text-zinc-400">
                                    Cuenta</th>
                                <th scope="col"
                                    class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider dark:text-zinc-400 hidden sm:table-cell">
                                    Nombre</th>
                                <th scope="col"
                                    class="px-4 sm:px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider dark:text-zinc-400">
                                    Débito</th>
                                <th scope="col"
                                    class="px-4 sm:px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider dark:text-zinc-400">
                                    Crédito</th>
                            </tr>
                        </thead>
                        <tbody
                            class="bg-white divide-y divide-slate-200 dark:bg-zinc-900 dark:divide-zinc-700 font-mono text-sm">
                            <!-- Parent -->
                            <tr>
                                <td class="px-4 sm:px-6 py-3 text-slate-900 dark:text-white">
                                    {{ $viewingDiary->nomenclature?->code }}</td>
                                <td class="px-4 sm:px-6 py-3 text-slate-600 dark:text-zinc-300 font-sans hidden sm:table-cell">
                                    {{ $viewingDiary->nomenclature?->name }}</td>
                                <td class="px-4 sm:px-6 py-3 text-right text-slate-900 dark:text-white">
                                    {{ $viewingDiary->debit > 0 ? number_format($viewingDiary->debit, 2) : '-' }}</td>
                                <td class="px-4 sm:px-6 py-3 text-right text-slate-900 dark:text-white">
                                    {{ $viewingDiary->credit > 0 ? number_format($viewingDiary->credit, 2) : '-' }}</td>
                            </tr>
                            <!-- Children -->
                            @foreach($viewingDiary->children as $child)
                                <tr>
                                    <td class="px-4 sm:px-6 py-3 text-slate-900 dark:text-white">{{ $child->nomenclature?->code }}
                                    </td>
                                    <td class="px-4 sm:px-6 py-3 text-slate-600 dark:text-zinc-300 font-sans hidden sm:table-cell">
                                        {{ $child->nomenclature?->name }}</td>
                                    <td class="px-4 sm:px-6 py-3 text-right text-slate-900 dark:text-white">
                                        {{ $child->debit > 0 ? number_format($child->debit, 2) : '-' }}</td>
                                    <td class="px-4 sm:px-6 py-3 text-right text-slate-900 dark:text-white">
                                        {{ $child->credit > 0 ? number_format($child->credit, 2) : '-' }}</td>
                                </tr>
                            @endforeach

                            <!-- Totals -->
                            @php
                                $totalDebit = $viewingDiary->debit + $viewingDiary->children->sum('debit');
                                $totalCredit = $viewingDiary->credit + $viewingDiary->children->sum('credit');
                            @endphp
                            <tr
                                class="bg-slate-50 dark:bg-zinc-800 font-bold border-t-2 border-slate-200 dark:border-zinc-600">
                                <td colspan="2" class="px-4 sm:px-6 py-3 text-right uppercase text-xs tracking-wider hidden sm:table-cell">Totales</td>
                                <td class="px-4 sm:px-6 py-3 text-right uppercase text-xs tracking-wider sm:hidden">Totales</td>
                                <td class="px-4 sm:px-6 py-3 text-right text-slate-900 dark:text-white">
                                    {{ number_format($totalDebit, 2) }}</td>
                                <td class="px-4 sm:px-6 py-3 text-right text-slate-900 dark:text-white">
                                    {{ number_format($totalCredit, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                    </div>
                </div>

                <div class="flex justify-end">
                    <flux:button wire:click="$set('showViewModal', false)">Cerrar</flux:button>
                </div>
            </div>
        @endif
    </flux:modal>

    {{-- Date Restriction Error Modal --}}
    <flux:modal name="date-restriction-error" wire:model="showDateRestrictionError" class="w-full max-w-md mx-auto">
        <div class="space-y-6">
            <div class="flex flex-col items-center text-center">
                <div class="mb-4 text-red-500 bg-red-100 dark:bg-red-900/30 p-3 rounded-full">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <h2 class="text-xl font-bold text-slate-900 dark:text-white">Acceso Denegado</h2>
                <p class="text-sm text-slate-500 dark:text-zinc-400 mt-2">
                    No tiene permiso para ingresar datos en un mes anterior al último registrado (<span class="font-bold text-slate-800 dark:text-white">{{ $restrictionDate }}</span>).
                </p>
                <p class="text-xs text-slate-400 dark:text-zinc-500 mt-4">
                    Por favor, contacte al administrador si necesita realizar esta operación.
                </p>
            </div>

            <div class="flex justify-center w-full">
                <flux:button wire:click="$set('showDateRestrictionError', false)" class="w-full">Entendido</flux:button>
            </div>
        </div>
    </flux:modal>
</div>