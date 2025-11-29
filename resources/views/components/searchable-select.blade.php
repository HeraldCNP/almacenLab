@props(['options', 'placeholder' => 'Seleccione una opción', 'wireModel', 'label' => null, 'optionLabel' => 'label', 'optionValue' => 'id'])

<div
    x-data="{
        open: false,
        search: '',
        selected: @entangle($wireModel).live,
        labelKey: '{{ $optionLabel }}',
        valueKey: '{{ $optionValue }}',
        get filteredOptions() {
            if (this.search === '') return this.options;
            return this.options.filter(option => 
                option[this.labelKey].toLowerCase().includes(this.search.toLowerCase())
            );
        },
        get selectedLabel() {
            const option = this.options.find(o => o[this.valueKey] == this.selected);
            return option ? option[this.labelKey] : '{{ $placeholder }}';
        },
        options: @js($options)
    }"
    class="relative"
>
    @if($label)
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
            {{ $label }}
        </label>
    @endif

    {{-- Trigger Button --}}
    <button 
        type="button" 
        @click="open = !open; if(open) $nextTick(() => $refs.searchInput.focus())"
        class="w-full bg-white dark:bg-white/10 border border-zinc-200 dark:border-white/10 rounded-lg px-3 py-2 text-left shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 sm:text-sm flex justify-between items-center transition-all duration-200 ease-in-out hover:shadow-md hover:border-indigo-300 dark:hover:border-indigo-700 active:scale-[0.98]"
    >
        <span x-text="selectedLabel" :class="{'text-zinc-500 dark:text-zinc-400': !selected, 'text-zinc-900 dark:text-zinc-100': selected}"></span>
        <svg class="h-5 w-5 text-zinc-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
            <path fill-rule="evenodd" d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
        </svg>
    </button>

    {{-- Dropdown --}}
    <div 
        x-show="open" 
        @click.away="open = false"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 translate-y-1 scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 translate-y-1 scale-95"
        class="absolute z-50 mt-1 w-full bg-white dark:bg-zinc-800 shadow-lg border border-zinc-200 dark:border-zinc-700 max-h-60 rounded-md py-1 text-base overflow-auto focus:outline-none sm:text-sm"
        style="display: none;"
    >
        {{-- Search Input --}}
        <div class="sticky top-0 z-10 bg-white dark:bg-zinc-800 px-2 py-1.5 border-b border-zinc-200 dark:border-zinc-700">
            <input 
                x-ref="searchInput"
                x-model="search" 
                type="text" 
                class="w-full border-0 bg-zinc-100 dark:bg-zinc-900 text-zinc-900 dark:text-zinc-100 rounded-md py-1.5 pl-3 pr-10 focus:ring-2 focus:ring-indigo-500 sm:text-sm"
                placeholder="Buscar..."
            >
        </div>

        {{-- Options List --}}
        <ul class="pt-1">
            <template x-for="option in filteredOptions" :key="option[valueKey]">
                <li 
                    @click="selected = option[valueKey]; open = false; search = ''"
                    class="cursor-pointer select-none relative py-2 pl-3 pr-9 hover:bg-indigo-600 hover:text-white text-zinc-900 dark:text-zinc-100 transition-colors duration-150"
                    :class="{'bg-indigo-600 text-white': selected == option[valueKey]}"
                >
                    <span x-text="option[labelKey]" class="block truncate" :class="{'font-semibold': selected == option[valueKey], 'font-normal': selected != option[valueKey]}"></span>
                    
                    <span x-show="selected == option[valueKey]" class="absolute inset-y-0 right-0 flex items-center pr-4" :class="{'text-white': selected == option[valueKey], 'text-indigo-600': selected != option[valueKey]}">
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    </span>
                </li>
            </template>
            <li x-show="filteredOptions.length === 0" class="cursor-default select-none relative py-2 pl-3 pr-9 text-zinc-500 dark:text-zinc-400">
                No se encontraron resultados.
            </li>
        </ul>
    </div>
</div>
