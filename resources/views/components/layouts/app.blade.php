<!DOCTYPE html>
<html lang="id" class="h-full bg-gray-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'HeavyMaint' }} — Heavy Equipment Maintenance System</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full font-sans antialiased">
<div class="flex h-full" x-data="{ sidebarOpen: true }">

    {{-- ═══ SIDEBAR ═══ --}}
    <aside class="flex flex-col w-64 min-h-screen bg-sidebar text-white transition-all duration-300 flex-shrink-0"
           :class="sidebarOpen ? 'w-64' : 'w-20'"
    >
        {{-- Logo --}}
        <div class="flex items-center gap-3 px-5 py-5 border-b border-white/10">
            <div class="flex items-center justify-center w-10 h-10 rounded-xl bg-primary-600 flex-shrink-0">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <div x-show="sidebarOpen" x-transition class="overflow-hidden">
                <h1 class="text-base font-bold leading-tight">HeavyMaint</h1>
                <p class="text-[11px] text-gray-400">Maintenance System</p>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
            <p class="px-3 mb-2 text-[10px] font-semibold tracking-widest uppercase text-gray-500" x-show="sidebarOpen">Menu Utama</p>

            <x-sidebar-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')" icon="dashboard">
                Dashboard
            </x-sidebar-link>

            <x-sidebar-link href="{{ route('units.index') }}" :active="request()->routeIs('units.*')" icon="truck">
                Unit Alat Berat
            </x-sidebar-link>

            <x-sidebar-link href="{{ route('work-orders.index') }}" :active="request()->routeIs('work-orders.*')" icon="clipboard">
                Work Order
            </x-sidebar-link>

            <x-sidebar-link href="{{ route('mechanics.index') }}" :active="request()->routeIs('mechanics.*')" icon="wrench">
                Mekanik
            </x-sidebar-link>

            <p class="px-3 mt-6 mb-2 text-[10px] font-semibold tracking-widest uppercase text-gray-500" x-show="sidebarOpen">Inventory & QC</p>

            <x-sidebar-link href="{{ route('spare-parts.index') }}" :active="request()->routeIs('spare-parts.*')" icon="cube">
                Spare Parts
            </x-sidebar-link>

            <x-sidebar-link href="{{ route('parts-requisitions.index') }}" :active="request()->routeIs('parts-requisitions.*')" icon="document">
                Parts Requisition
            </x-sidebar-link>

            <x-sidebar-link href="{{ route('qc-checklists.index') }}" :active="request()->routeIs('qc-checklists.*')" icon="shield">
                QC Checklist
            </x-sidebar-link>
        </nav>

        {{-- Footer --}}
        <div class="px-4 py-3 border-t border-white/10">
            <p class="text-[10px] text-gray-500" x-show="sidebarOpen">v1.0 — Heavy Equipment</p>
        </div>
    </aside>

    {{-- ═══ MAIN CONTENT ═══ --}}
    <div class="flex flex-col flex-1 min-w-0 overflow-hidden">
        {{-- Top Bar --}}
        <header class="flex items-center justify-between h-16 px-6 bg-white border-b border-gray-200 flex-shrink-0">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-lg hover:bg-gray-100 transition">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">{{ $header ?? 'Dashboard' }}</h2>
                    @if(isset($subtitle))
                        <p class="text-xs text-gray-500">{{ $subtitle }}</p>
                    @endif
                </div>
            </div>
            <div class="flex items-center gap-3">
                <span class="text-sm text-gray-600">{{ now()->translatedFormat('l, d M Y') }}</span>
                <div class="w-8 h-8 rounded-full bg-primary-600 flex items-center justify-center">
                    <span class="text-xs font-bold text-white">A</span>
                </div>
            </div>
        </header>

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="mx-6 mt-4 px-4 py-3 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center gap-2" x-data="{ show: true }" x-show="show" x-transition>
                <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                {{ session('success') }}
                <button @click="show = false" class="ml-auto">&times;</button>
            </div>
        @endif
        @if(session('error'))
            <div class="mx-6 mt-4 px-4 py-3 rounded-lg bg-red-50 border border-red-200 text-red-800 text-sm flex items-center gap-2" x-data="{ show: true }" x-show="show" x-transition>
                <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v4a1 1 0 002 0V5zm-1 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/></svg>
                {{ session('error') }}
                <button @click="show = false" class="ml-auto">&times;</button>
            </div>
        @endif

        {{-- Page Content --}}
        <main class="flex-1 overflow-y-auto p-6">
            {{ $slot }}
        </main>
    </div>
</div>

{{-- Alpine.js CDN --}}
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>
