<?php
// Dark Mode Toggle Component - v0 style with proper .dark class toggle
?>
<div 
    x-data="{ 
        isDark: localStorage.getItem('theme') === 'dark' || (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches),
        init() {
            this.updateTheme();
        },
        toggle() {
            this.isDark = !this.isDark;
            this.updateTheme();
        },
        updateTheme() {
            if (this.isDark) {
                document.documentElement.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            } else {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('theme', 'light');
            }
        }
    }" 
    x-init="init()"
>
    <button 
        @click="toggle()"
        class="relative p-2 rounded-lg hover:bg-muted transition-all duration-300 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-ring"
        aria-label="Toggle dark mode"
    >
        <!-- Sun Icon (Light Mode) -->
        <svg 
            x-show="isDark"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 rotate-90 scale-0"
            x-transition:enter-end="opacity-100 rotate-0 scale-100"
            class="w-5 h-5 text-foreground" 
            fill="none" 
            stroke="currentColor" 
            viewBox="0 0 24 24"
        >
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
        </svg>
        
        <!-- Moon Icon (Dark Mode) -->
        <svg 
            x-show="!isDark"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 -rotate-90 scale-0"
            x-transition:enter-end="opacity-100 rotate-0 scale-100"
            class="w-5 h-5 text-foreground" 
            fill="none" 
            stroke="currentColor" 
            viewBox="0 0 24 24"
        >
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
        </svg>
    </button>
</div>
