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
        <div
            x-data="{
                open: false,
                tab: 'include',
                search: '',
                selected: @entangle('searchNomenclatureIds'),
                excluded: @entangle('searchNomenclatureExcludedIds'),
                movement: @entangle('searchMovementType'),
                options: @js($this->nomenclatures->map(fn($n) => ['id' => $n->id, 'label' => $n->code . ' - ' . $n->name])->values()->toArray()),
                get filtered() {
                    if (!this.search) return this.options;
                    const q = this.search.toLowerCase();
                    return this.options.filter(o => o.label.toLowerCase().includes(q));
                },
                get label() {
                    const incl = this.selected ? this.selected.length : 0;
                    const excl = this.excluded ? this.excluded.length : 0;
                    let text = '';
                    if (incl === 0 && excl === 0) text = 'Todas las cuentas...';
                    else {
                        const parts = [];
                        if (incl > 0) parts.push(incl + ' incluida' + (incl > 1 ? 's' : ''));
                        if (excl > 0) parts.push(excl + ' excluida' + (excl > 1 ? 's' : ''));
                        text = parts.join(' · ');
                    }

                    if (this.movement === 'debit') text += ' (Débito)';
                    if (this.movement === 'credit') text += ' (Crédito)';
                    return text;
                },
                get hasSelection() {
                    return (this.selected && this.selected.length > 0) || (this.excluded && this.excluded.length > 0) || (this.movement !== '');
                },
                toggle(id) {
                    const list = this.tab === 'include' ? 'selected' : 'excluded';
                    const strId = String(id);
                    const idx = (this[list] || []).findIndex(s => String(s) === strId);
                    if (idx === -1) { this[list] = [...(this[list] || []), id]; }
                    else { this[list] = (this[list] || []).filter((_, i) => i !== idx); }
                },
                isChecked(id) {
                    const list = this.tab === 'include' ? this.selected : this.excluded;
                    return (list || []).some(s => String(s) === String(id));
                },
                isInOther(id) {
                    const list = this.tab === 'include' ? this.excluded : this.selected;
                    return (list || []).some(s => String(s) === String(id));
                }
            }"
            x-on:keydown.escape.window="open = false; search = ''"
            x-on:click.outside="open = false; search = ''"
            class="sm:col-span-3 relative"
        >
            <flux:field>
                <flux:label>
                    Cuentas
                    <template x-if="selected && selected.length > 0">
                        <span class="ml-1 inline-flex items-center gap-0.5 px-1.5 py-0.5 rounded-full bg-indigo-100 dark:bg-indigo-900/40 text-indigo-700 dark:text-indigo-300 text-xs font-semibold">
                            <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                            <span x-text="selected.length"></span>
                        </span>
                    </template>
                    <template x-if="excluded && excluded.length > 0">
                        <span class="ml-1 inline-flex items-center gap-0.5 px-1.5 py-0.5 rounded-full bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-300 text-xs font-semibold">
                            <svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 12 12"><path d="M6.707 6l2.647-2.646a.5.5 0 00-.708-.708L6 5.293 3.354 2.646a.5.5 0 10-.708.708L5.293 6 2.646 8.646a.5.5 0 00.708.708L6 6.707l2.646 2.647a.5.5 0 00.708-.708L6.707 6z"/></svg>
                            <span x-text="excluded.length"></span>
                        </span>
                    </template>
                    <template x-if="movement !== ''">
                        <span class="ml-1 inline-flex items-center px-1.5 py-0.5 rounded-full bg-blue-100 dark:bg-blue-900/40 text-blue-700 dark:text-blue-300 text-xs font-semibold"
                              x-text="movement === 'debit' ? 'Débito' : 'Crédito'">
                        </span>
                    </template>
                </flux:label>

                {{-- Trigger button --}}
                <button type="button" x-on:click="open = !open"
                    class="relative w-full flex items-center justify-between gap-2 rounded-lg border bg-white dark:bg-zinc-800 px-3 h-10 text-sm text-left shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all"
                    :class="hasSelection
                        ? 'border-indigo-400 dark:border-indigo-600 text-slate-900 dark:text-white'
                        : 'border-zinc-200 dark:border-zinc-700 text-slate-400 dark:text-zinc-500'">
                    <span x-text="label" class="truncate flex-1"></span>
                    <span class="flex items-center gap-1 shrink-0">
                        <span x-show="hasSelection"
                              x-on:click.stop="selected = []; excluded = []; movement = ''; search = ''"
                              title="Limpiar todo"
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

                {{-- Dropdown panel --}}
                <div x-show="open"
                     x-transition:enter="transition ease-out duration-150"
                     x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                     x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-100"
                     x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                     x-transition:leave-end="opacity-0 scale-95 -translate-y-1"
                     class="absolute z-50 mt-1 w-full min-w-[440px] rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 shadow-2xl overflow-hidden origin-top"
                     style="display:none;">

                    {{-- Tabs --}}
                    <div class="flex border-b border-zinc-100 dark:border-zinc-800">
                        <button type="button"
                                x-on:click="tab = 'include'; search = ''"
                                class="flex-1 flex items-center justify-center gap-1.5 px-4 py-2.5 text-xs font-semibold transition-colors"
                                :class="tab === 'include'
                                    ? 'text-indigo-600 dark:text-indigo-400 border-b-2 border-indigo-600 dark:border-indigo-400 bg-indigo-50/60 dark:bg-indigo-900/20'
                                    : 'text-slate-500 dark:text-zinc-400 hover:text-slate-700 dark:hover:text-zinc-200'">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            Incluir
                            <span x-show="selected && selected.length > 0"
                                  class="ml-0.5 inline-flex items-center justify-center w-4 h-4 rounded-full bg-indigo-600 text-white text-[10px] font-bold"
                                  x-text="selected.length"></span>
                        </button>
                        <div class="w-px bg-zinc-100 dark:bg-zinc-800 my-1"></div>
                        <button type="button"
                                x-on:click="tab = 'exclude'; search = ''"
                                class="flex-1 flex items-center justify-center gap-1.5 px-4 py-2.5 text-xs font-semibold transition-colors"
                                :class="tab === 'exclude'
                                    ? 'text-red-600 dark:text-red-400 border-b-2 border-red-500 dark:border-red-400 bg-red-50/60 dark:bg-red-900/20'
                                    : 'text-slate-500 dark:text-zinc-400 hover:text-slate-700 dark:hover:text-zinc-200'">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M18 12H6"/></svg>
                            Excluir
                            <span x-show="excluded && excluded.length > 0"
                                  class="ml-0.5 inline-flex items-center justify-center w-4 h-4 rounded-full bg-red-500 text-white text-[10px] font-bold"
                                  x-text="excluded.length"></span>
                        </button>
                    </div>

                    {{-- Context hint --}}
                    <div class="px-3 pt-2 pb-0">
                        <p class="text-[11px] text-slate-400 dark:text-zinc-500 italic"
                           x-text="tab === 'include'
                               ? 'Mostrar solo asientos que contengan estas cuentas'
                               : 'Ocultar asientos que contengan estas cuentas'"></p>
                    </div>

                    {{-- Movement Type Segmented Control --}}
                    <div class="px-3 pt-3">
                        <div class="flex items-center bg-slate-100 dark:bg-zinc-800 rounded-lg p-1 gap-1">
                            <button type="button" x-on:click.stop="movement = ''"
                                class="flex-1 px-3 py-1.5 text-xs font-medium rounded-md transition-all shadow-sm"
                                :class="movement === '' ? 'bg-white dark:bg-zinc-700 text-slate-900 dark:text-white' : 'text-slate-500 hover:text-slate-700 dark:text-zinc-400 dark:hover:text-zinc-200 shadow-none'">
                                Ambas
                            </button>
                            <button type="button" x-on:click.stop="movement = 'debit'"
                                class="flex-1 px-3 py-1.5 text-xs font-medium rounded-md transition-all shadow-sm"
                                :class="movement === 'debit' ? 'bg-white dark:bg-zinc-700 text-slate-900 dark:text-white ring-1 ring-slate-200 dark:ring-zinc-600' : 'text-slate-500 hover:text-slate-700 dark:text-zinc-400 dark:hover:text-zinc-200 shadow-none'">
                                Solo Débito
                            </button>
                            <button type="button" x-on:click.stop="movement = 'credit'"
                                class="flex-1 px-3 py-1.5 text-xs font-medium rounded-md transition-all shadow-sm"
                                :class="movement === 'credit' ? 'bg-white dark:bg-zinc-700 text-slate-900 dark:text-white ring-1 ring-slate-200 dark:ring-zinc-600' : 'text-slate-500 hover:text-slate-700 dark:text-zinc-400 dark:hover:text-zinc-200 shadow-none'">
                                Solo Crédito
                            </button>
                        </div>
                    </div>

                    {{-- Search box --}}
                    <div class="p-2">
                        <div class="relative">
                            <svg class="absolute left-2.5 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
                            </svg>
                            <input x-model="search" x-on:click.stop type="text"
                                   placeholder="Buscar cuenta..."
                                   class="w-full pl-8 pr-3 py-1.5 text-sm rounded-lg border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-zinc-500 focus:outline-none focus:ring-2"
                                   :class="tab === 'include' ? 'focus:ring-indigo-500' : 'focus:ring-red-400'"/>
                        </div>
                    </div>

                    {{-- Options list --}}
                    <ul class="max-h-52 overflow-y-auto pb-1" style="scrollbar-width:thin;">
                        <template x-for="option in filtered" :key="option.id">
                            <li x-on:click="toggle(option.id)"
                                class="flex items-center gap-2.5 px-3 py-2 text-sm cursor-pointer select-none transition-colors"
                                :class="isChecked(option.id)
                                    ? (tab === 'include'
                                        ? 'bg-indigo-50 dark:bg-indigo-900/20 text-indigo-700 dark:text-indigo-300'
                                        : 'bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-300')
                                    : 'text-slate-800 dark:text-zinc-200 hover:bg-slate-50 dark:hover:bg-zinc-800'">
                                {{-- Checkbox --}}
                                <span class="flex-shrink-0 w-4 h-4 rounded border transition-all flex items-center justify-center"
                                      :class="isChecked(option.id)
                                          ? (tab === 'include' ? 'bg-indigo-600 border-indigo-600' : 'bg-red-500 border-red-500')
                                          : 'border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-800'">
                                    <svg x-show="isChecked(option.id)" class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </span>
                                <span x-text="option.label" class="leading-snug flex-1"></span>
                                {{-- Badge si está en la otra lista --}}
                                <span x-show="isInOther(option.id)"
                                      class="flex-shrink-0 text-[10px] font-semibold px-1.5 py-0.5 rounded-full"
                                      :class="tab === 'include'
                                          ? 'bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400'
                                          : 'bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400'"
                                      x-text="tab === 'include' ? 'excluida' : 'incluida'"></span>
                            </li>
                        </template>
                        <li x-show="filtered.length === 0"
                            class="px-4 py-5 text-sm text-center text-slate-400 dark:text-zinc-500 italic">
                            Sin resultados para "<span x-text="search"></span>"
                        </li>
                    </ul>

                    {{-- Footer --}}
                    <div class="flex items-center justify-between px-3 py-2 border-t border-zinc-100 dark:border-zinc-800 bg-slate-50 dark:bg-zinc-800/60">
                        <div class="flex items-center gap-3 text-xs">
                            <span x-show="selected && selected.length > 0"
                                  class="text-indigo-600 dark:text-indigo-400 font-medium"
                                  x-text="selected.length + ' incluida' + (selected.length === 1 ? '' : 's')"></span>
                            <span x-show="excluded && excluded.length > 0"
                                  class="text-red-500 dark:text-red-400 font-medium"
                                  x-text="excluded.length + ' excluida' + (excluded.length === 1 ? '' : 's')"></span>
                            <span x-show="(!selected || selected.length === 0) && (!excluded || excluded.length === 0)"
                                  class="text-slate-400 dark:text-zinc-500">Sin filtros activos</span>
                        </div>
                        <button x-show="hasSelection"
                                type="button" x-on:click="selected = []; excluded = []; movement = ''; search = ''"
                                class="text-xs font-semibold text-red-500 hover:text-red-600 transition-colors">
                            Limpiar todo
                        </button>
                    </div>
                </div>
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
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <flux:field>
                    <flux:label>Categoría (Filtro)</flux:label>
                    <flux:select wire:model.live="selected_category_id">
                        <flux:select.option value="">Todas</flux:select.option>
                        @foreach ($this->categories as $category)
                        <flux:select.option value="{{ $category->id }}">{{ $category->name }}</flux:select.option>
                        @endforeach
                    </flux:select>
                </flux:field>
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
                        @php
                        $isAccount11 = isset($entries[$index]['code']) && str_starts_with($entries[$index]['code'], '11');
                        @endphp
                        <flux:input wire:model.live="entries.{{ $index }}.code" placeholder="Código"
                            class="{{ $isAccount11 ? 'bg-zinc-600 text-amber-900 dark:bg-zinc-600 dark:text-zinc-50 rounded-lg font-bold transition-colors' : 'transition-colors' }}" />
                    </div>
                    <div class="col-span-1 sm:col-span-4">
                        <label class="sm:hidden text-xs text-slate-500 dark:text-zinc-500 mb-1 block">Cuenta</label>
                        <flux:input wire:model="entries.{{ $index }}.name" readonly
                            class="{{ $isAccount11 ? 'bg-amber-50 text-amber-900 dark:bg-zinc-600 dark:text-zinc-50 rounded-lg font-bold transition-colors' : 'transition-colors' }}" />
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
                        <flux:input type="number" step="0.01" wire:model.blur="entries.{{ $index }}.value" />
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

                <flux:field>
                    <flux:label>Nit/CC</flux:label>
                    <flux:input wire:model="nit_cc" placeholder="Opcional" />
                </flux:field>

                <flux:field>
                    <flux:label>Nombre o Razón Social</flux:label>
                    <flux:input wire:model="business_name" placeholder="Opcional" />
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

            <!-- Description & extra info -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-slate-50 dark:bg-zinc-800/50 p-4 rounded-lg">
                    <h3 class="text-xs font-bold text-slate-500 dark:text-zinc-500 uppercase tracking-wider mb-2">Descripción</h3>
                    <p class="text-sm text-slate-900 dark:text-white">{{ $viewingDiary->description ?: '-' }}</p>
                </div>
                <div class="bg-slate-50 dark:bg-zinc-800/50 p-4 rounded-lg">
                    <h3 class="text-xs font-bold text-slate-500 dark:text-zinc-500 uppercase tracking-wider mb-2">Nit/CC</h3>
                    <p class="text-sm font-mono text-slate-900 dark:text-white">{{ $viewingDiary->nit_cc ?: '-' }}</p>
                </div>
                <div class="bg-slate-50 dark:bg-zinc-800/50 p-4 rounded-lg">
                    <h3 class="text-xs font-bold text-slate-500 dark:text-zinc-500 uppercase tracking-wider mb-2">Nombre o Razón Social</h3>
                    <p class="text-sm text-slate-900 dark:text-white">{{ $viewingDiary->business_name ?: '-' }}</p>
                </div>
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
                                    {{ $viewingDiary->nomenclature?->code }}
                                </td>
                                <td class="px-4 sm:px-6 py-3 text-slate-600 dark:text-zinc-300 font-sans hidden sm:table-cell">
                                    {{ $viewingDiary->nomenclature?->name }}
                                </td>
                                <td class="px-4 sm:px-6 py-3 text-right text-slate-900 dark:text-white">
                                    {{ $viewingDiary->debit > 0 ? number_format($viewingDiary->debit, 2) : '-' }}
                                </td>
                                <td class="px-4 sm:px-6 py-3 text-right text-slate-900 dark:text-white">
                                    {{ $viewingDiary->credit > 0 ? number_format($viewingDiary->credit, 2) : '-' }}
                                </td>
                            </tr>
                            <!-- Children -->
                            @foreach($viewingDiary->children as $child)
                            <tr>
                                <td class="px-4 sm:px-6 py-3 text-slate-900 dark:text-white">{{ $child->nomenclature?->code }}
                                </td>
                                <td class="px-4 sm:px-6 py-3 text-slate-600 dark:text-zinc-300 font-sans hidden sm:table-cell">
                                    {{ $child->nomenclature?->name }}
                                </td>
                                <td class="px-4 sm:px-6 py-3 text-right text-slate-900 dark:text-white">
                                    {{ $child->debit > 0 ? number_format($child->debit, 2) : '-' }}
                                </td>
                                <td class="px-4 sm:px-6 py-3 text-right text-slate-900 dark:text-white">
                                    {{ $child->credit > 0 ? number_format($child->credit, 2) : '-' }}
                                </td>
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
                                    {{ number_format($totalDebit, 2) }}
                                </td>
                                <td class="px-4 sm:px-6 py-3 text-right text-slate-900 dark:text-white">
                                    {{ number_format($totalCredit, 2) }}
                                </td>
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
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