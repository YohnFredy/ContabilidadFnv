<div x-data="{ 
    show: false, 
    message: '', 
    variant: 'success', 
    timeout: null,
    
    showToast(event) {
        this.message = event.detail.message;
        this.variant = event.detail.variant || 'success';
        this.show = true;
        
        if (this.timeout) clearTimeout(this.timeout);
        
        this.timeout = setTimeout(() => {
            this.show = false;
        }, 5000); // 5 seconds
    }
}" 
x-on:show-toast.window="showToast($event)"
class="fixed bottom-4 right-4 z-[9999] flex flex-col gap-2 max-w-sm w-full pointer-events-none">

    <div x-show="show" 
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
        x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="pointer-events-auto w-full overflow-hidden rounded-lg shadow-lg ring-1 ring-black ring-opacity-5 p-4 mb-2 flex items-start gap-3"
        :class="{
            'bg-white dark:bg-zinc-800 border-l-4 border-green-500': variant === 'success',
            'bg-white dark:bg-zinc-800 border-l-4 border-red-500': variant === 'danger',
            'bg-white dark:bg-zinc-800 border-l-4 border-yellow-500': variant === 'warning',
            'bg-white dark:bg-zinc-800 border-l-4 border-blue-500': variant === 'info'
        }">
        
        <div class="flex-shrink-0">
            <template x-if="variant === 'success'">
                <svg class="h-6 w-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </template>
            <template x-if="variant === 'danger'">
                <svg class="h-6 w-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </template>
            <template x-if="variant === 'warning'">
                <svg class="h-6 w-6 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </template>
            <template x-if="variant === 'info'">
                <svg class="h-6 w-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </template>
        </div>
        
        <div class="flex-1 w-0">
            <p class="text-sm font-medium text-gray-900 dark:text-gray-100" x-text="message"></p>
        </div>
        
        <div class="ml-4 flex flex-shrink-0">
            <button @click="show = false" class="inline-flex rounded-md bg-transparent text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                <span class="sr-only">Close</span>
                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>
    </div>
</div>
