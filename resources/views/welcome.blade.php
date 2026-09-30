<!DOCTYPE html>
<html lang="id" class="h-full scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Heavy2000 Digiroom — Dealer & Bengkel Alat Berat Terbesar di Indonesia</title>
    <meta name="description" content="Platform Digital Manajemen Armada Alat Berat & Servis Bengkel Terlengkap di Indonesia terinspirasi Auto2000. Booking servis berkala, lacak work order, dan order suku cadang resmi.">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        .auto2000-red { background-color: #D3122A; }
        .auto2000-red-text { color: #D3122A; }
        .auto2000-red-hover:hover { background-color: #b70f24; }
        .hero-pattern {
            background-image: radial-gradient(rgba(211, 18, 42, 0.08) 1px, transparent 1px);
            background-size: 24px 24px;
        }
    </style>
</head>
<body class="font-sans antialiased bg-gray-50 text-gray-800">

    {{-- ═══ TOP NOTIFICATION STRIP (AUTO2000 STYLE) ═══ --}}
    <div class="bg-[#1C1C1E] text-gray-300 text-xs py-2 px-4 border-b border-gray-800">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-2">
            <div class="flex items-center gap-4 text-[11px]">
                <span class="flex items-center gap-1.5 font-medium">
                    <svg class="w-3.5 h-3.5 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 4V3z"/></svg>
                    Halo Heavy2000: <strong class="text-white">1-500-898</strong>
                </span>
                <span class="hidden md:inline text-gray-600">|</span>
                <span class="hidden md:flex items-center gap-1.5 text-emerald-400">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    Emergency Mobile Workshop (THS) 24/7 Siap Meluncur
                </span>
            </div>
            <div class="flex items-center gap-4 text-[11px]">
                <a href="#lacak-wo" class="hover:text-white transition">Lacak Progres Work Order</a>
                <span class="text-gray-600">|</span>
                <a href="{{ route('dashboard') }}" class="font-semibold text-white hover:text-red-400 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                    Portal Internal Workshop Bengkel &rarr;
                </a>
            </div>
        </div>
    </div>

    {{-- ═══ MAIN NAVIGATION NAVBAR ═══ --}}
    <header class="sticky top-0 z-40 bg-white/95 backdrop-blur border-b border-gray-200 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                {{-- Logo Auto2000 Style --}}
                <div class="flex items-center gap-3">
                    <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                        <div class="w-11 h-11 auto2000-red rounded-xl flex items-center justify-center shadow-md group-hover:scale-105 transition-transform">
                            <span class="font-black text-white text-xl tracking-tighter">H2K</span>
                        </div>
                        <div>
                            <div class="flex items-center gap-1.5">
                                <span class="font-black text-xl text-gray-900 tracking-tight">HEAVY2000</span>
                                <span class="px-1.5 py-0.5 rounded text-[10px] font-black tracking-wider uppercase text-white auto2000-red">DIGIROOM</span>
                            </div>
                            <p class="text-[10px] text-gray-500 font-medium tracking-tight">Heavy Machinery & Fleet Maintenance Portal</p>
                        </div>
                    </a>
                </div>

                {{-- Desktop Navigation Links --}}
                <nav class="hidden lg:flex items-center gap-8 text-sm font-semibold text-gray-700">
                    <a href="#katalog-alat" class="hover:auto2000-red-text transition">Armada Unit</a>
                    <a href="#layanan-bengkel" class="hover:auto2000-red-text transition">Layanan THS Bengkel</a>
                    <a href="#paket-servis" class="hover:auto2000-red-text transition">Paket Servis PM</a>
                    <a href="#suku-cadang" class="hover:auto2000-red-text transition">Suku Cadang Resmi</a>
                    <a href="#lacak-wo" class="hover:auto2000-red-text transition">Lacak WO</a>
                </nav>

                {{-- Action Buttons --}}
                <div class="flex items-center gap-3">
                    <a href="{{ route('work-orders.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl auto2000-red auto2000-red-hover text-white text-xs font-bold shadow-md shadow-red-500/20 hover:shadow-lg transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Booking Servis / Intake
                    </a>
                    <a href="{{ route('dashboard') }}" class="hidden sm:inline-flex items-center px-4 py-2.5 rounded-xl border border-gray-300 text-gray-800 text-xs font-bold hover:bg-gray-50 transition">
                        Workshop Admin
                    </a>
                </div>
            </div>
        </div>
    </header>

    {{-- ═══ HERO SECTION WITH AUTO2000 DIGIROOM VIBES ═══ --}}
    <section class="relative bg-gradient-to-b from-white via-red-50/20 to-gray-50 pt-10 pb-20 hero-pattern overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-10">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-red-100 text-red-800 text-xs font-bold mb-4">
                    <span class="w-2 h-2 rounded-full auto2000-red"></span>
                    Standard Pelayanan Servis Bengkel Terdepan di Indonesia
                </div>
                <h1 class="text-3xl sm:text-5xl font-black text-gray-900 tracking-tight leading-tight">
                    Urusan Servis & Perawatan <br>
                    <span class="auto2000-red-text">Alat Berat Jadi Lebih Mudah</span>
                </h1>
                <p class="mt-4 text-base sm:text-lg text-gray-600 font-normal leading-relaxed">
                    Sistem pemeliharaan armada industri, monitoring Hours Meter (HM), intake Service Advisor, suku cadang OEM, hingga verifikasi QC berstandar Auto2000.
                </p>
            </div>

            {{-- ═══ FLOATING INTERACTIVE DIGIROOM BOOKING & TRACKING WIDGET ═══ --}}
            <div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden" x-data="{ tab: 'booking' }">
                {{-- Tabs Header ala Auto2000 --}}
                <div class="flex border-b border-gray-200 bg-gray-50 text-xs font-bold">
                    <button @click="tab = 'booking'"
                            :class="tab === 'booking' ? 'bg-white auto2000-red-text border-b-2 border-[#D3122A] shadow-xs' : 'text-gray-500 hover:text-gray-800'"
                            class="flex-1 py-4 px-4 text-center flex items-center justify-center gap-2 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                        <span>Booking Servis Berkala (PM)</span>
                    </button>
                    <button @click="tab = 'track'"
                            :class="tab === 'track' ? 'bg-white auto2000-red-text border-b-2 border-[#D3122A] shadow-xs' : 'text-gray-500 hover:text-gray-800'"
                            class="flex-1 py-4 px-4 text-center flex items-center justify-center gap-2 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                        <span>Lacak Status Work Order</span>
                    </button>
                    <button @click="tab = 'parts'"
                            :class="tab === 'parts' ? 'bg-white auto2000-red-text border-b-2 border-[#D3122A] shadow-xs' : 'text-gray-500 hover:text-gray-800'"
                            class="flex-1 py-4 px-4 text-center flex items-center justify-center gap-2 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        <span>Cari Suku Cadang & Oli</span>
                    </button>
                </div>

                {{-- Tab 1: Booking Servis Form --}}
                <div x-show="tab === 'booking'" class="p-6">
                    <form action="{{ route('work-orders.create') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-end">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Pilih Model / Kategori Alat</label>
                            <select name="category" class="w-full px-3 py-2.5 text-sm bg-gray-50 border border-gray-300 rounded-xl focus:bg-white focus:ring-2 focus:ring-red-500 focus:outline-none">
                                <option value="">Semua Kategori Unit</option>
                                <option value="Excavator">Hydraulic Excavator (20-40 Ton)</option>
                                <option value="Bulldozer">Crawler Bulldozer</option>
                                <option value="Dump Truck">Heavy Articulated Dump Truck</option>
                                <option value="Wheel Loader">Wheel Loader</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Paket Pemeliharaan</label>
                            <select name="maintenance_type" class="w-full px-3 py-2.5 text-sm bg-gray-50 border border-gray-300 rounded-xl focus:bg-white focus:ring-2 focus:ring-red-500 focus:outline-none">
                                <option value="periodic">Periodic Maintenance (PM-250 s/d 2000)</option>
                                <option value="corrective">Corrective Repair (Perbaikan Terencana)</option>
                                <option value="breakdown">Emergency Breakdown Service</option>
                                <option value="overhaul">General Overhaul Mesin/Hidrolik</option>
                            </select>
                        </div>
                        <div>
                            <button type="submit" class="w-full py-2.5 px-4 auto2000-red auto2000-red-hover text-white text-sm font-bold rounded-xl shadow-md transition flex items-center justify-center gap-2">
                                <span>Cek Jadwal & Intake</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </button>
                        </div>
                    </form>
                    <div class="mt-4 pt-4 border-t border-gray-100 flex flex-wrap items-center justify-between text-xs text-gray-500">
                        <span class="flex items-center gap-1.5 text-emerald-600 font-semibold">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Didukung Tim Teknisi Komatsu & Caterpillar Bersertifikat
                        </span>
                        <span class="text-gray-400">Estimasi biaya transparan tanpa biaya tersembunyi</span>
                    </div>
                </div>

                {{-- Tab 2: Lacak Work Order --}}
                <div x-show="tab === 'track'" x-cloak class="p-6">
                    <form action="{{ route('work-orders.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-end">
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-bold text-gray-700 mb-1">Nomor Work Order atau Kode Unit</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                </span>
                                <input type="text" name="search" placeholder="Contoh: WO-20260930-0001 atau EX-01" required
                                       class="w-full pl-9 pr-4 py-2.5 text-sm bg-gray-50 border border-gray-300 rounded-xl focus:bg-white focus:ring-2 focus:ring-red-500 focus:outline-none font-mono">
                            </div>
                        </div>
                        <div>
                            <button type="submit" class="w-full py-2.5 px-4 bg-gray-900 hover:bg-black text-white text-sm font-bold rounded-xl shadow-md transition flex items-center justify-center gap-2">
                                <span>Lacak Status WO</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </button>
                        </div>
                    </form>
                    <div class="mt-4 pt-4 border-t border-gray-100 flex items-center justify-between text-xs text-gray-500">
                        <span>Pantau tahapan pengerjaan: Intake &rarr; Sedang Dikerjakan &rarr; QC Testing &rarr; Handover</span>
                    </div>
                </div>

                {{-- Tab 3: Cari Suku Cadang --}}
                <div x-show="tab === 'parts'" x-cloak class="p-6">
                    <form action="{{ route('spare-parts.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-end">
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-bold text-gray-700 mb-1">Part Number atau Nama Suku Cadang</label>
                            <input type="text" name="search" placeholder="Contoh: 6732-71-6120, Filter Oli, Seal Kit Boom..." required
                                   class="w-full px-4 py-2.5 text-sm bg-gray-50 border border-gray-300 rounded-xl focus:bg-white focus:ring-2 focus:ring-red-500 focus:outline-none">
                        </div>
                        <div>
                            <button type="submit" class="w-full py-2.5 px-4 auto2000-red auto2000-red-hover text-white text-sm font-bold rounded-xl shadow-md transition flex items-center justify-center gap-2">
                                <span>Cari Stok Gudang</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </button>
                        </div>
                    </form>
                    <div class="mt-4 pt-4 border-t border-gray-100 flex items-center justify-between text-xs text-gray-500">
                        <span>100% Genuine OEM Suku Cadang Komatsu, Caterpillar, Volvo & Pelumas Industri Shell</span>
                    </div>
                </div>
            </div>

            {{-- ═══ 4 KEY ADVANTAGES (AUTO2000 DIGIROOM PILLARS) ═══ --}}
            <div id="layanan-bengkel" class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-16">
                <div class="bg-white p-6 rounded-2xl border border-gray-200/80 shadow-xs hover:shadow-md transition">
                    <div class="w-12 h-12 rounded-xl bg-red-100 text-red-600 flex items-center justify-center mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <h3 class="text-base font-bold text-gray-900 mb-1">THS — Total Heavy Service</h3>
                    <p class="text-xs text-gray-500 leading-relaxed">
                        Armada bengkel keliling kami siap datang ke site tambang atau remote area untuk servis on-site tanpa perlu mobilisasi unit.
                    </p>
                </div>

                <div class="bg-white p-6 rounded-2xl border border-gray-200/80 shadow-xs hover:shadow-md transition">
                    <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                    <h3 class="text-base font-bold text-gray-900 mb-1">Quality Control 8 Titik</h3>
                    <p class="text-xs text-gray-500 leading-relaxed">
                        Setiap pengerjaan diverifikasi oleh Foreman QC profesional untuk uji tekanan hidrolik, kelistrikan, dan kebocoran sebelum diserahkan.
                    </p>
                </div>

                <div class="bg-white p-6 rounded-2xl border border-gray-200/80 shadow-xs hover:shadow-md transition">
                    <div class="w-12 h-12 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h3 class="text-base font-bold text-gray-900 mb-1">Pencegahan Dini via HM</h3>
                    <p class="text-xs text-gray-500 leading-relaxed">
                        Sistem mendeteksi unit yang mendekati interval PM (250, 500, 1000 HM) secara otomatis untuk mencegah unit mati mendadak (breakdown).
                    </p>
                </div>

                <div class="bg-white p-6 rounded-2xl border border-gray-200/80 shadow-xs hover:shadow-md transition">
                    <div class="w-12 h-12 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    </div>
                    <h3 class="text-base font-bold text-gray-900 mb-1">100% Suku Cadang Genuine</h3>
                    <p class="text-xs text-gray-500 leading-relaxed">
                        Pasokan komponen asli Komatsu, CAT, Volvo, dan pelumas Shell dengan garansi resmi dan pemotongan stok otomatis dari gudang.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- ═══ SECTION: KATALOG ARMADA ALAT BERAT PILIHAN (FEATURED FLEET) ═══ --}}
    <section id="katalog-alat" class="py-16 bg-white border-t border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-10">
                <div>
                    <span class="text-xs font-extrabold uppercase tracking-widest auto2000-red-text">Master Data Armada</span>
                    <h2 class="text-2xl sm:text-3xl font-black text-gray-900 mt-1">Armada Alat Berat Siap Operasi</h2>
                    <p class="text-sm text-gray-500 mt-1">Pantau kondisi, status jam kerja (HM), dan riwayat servis setiap unit</p>
                </div>
                <a href="{{ route('units.index') }}" class="mt-4 md:mt-0 inline-flex items-center gap-1.5 text-sm font-bold auto2000-red-text hover:underline">
                    Lihat Seluruh Armada ({{ $totalFleetCount }} Unit) &rarr;
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($featuredUnits as $unit)
                    <div class="bg-gray-50 rounded-2xl border border-gray-200 p-5 flex flex-col justify-between hover:shadow-lg transition">
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <span class="px-2.5 py-1 rounded-lg text-xs font-mono font-bold bg-white text-gray-800 border border-gray-200">
                                    {{ $unit->unit_code }}
                                </span>
                                <x-status-badge :status="$unit->status" />
                            </div>

                            {{-- Visual Placeholder Box Auto2000 Style --}}
                            <div class="h-36 rounded-xl bg-gradient-to-tr from-gray-200 to-gray-100 flex items-center justify-center p-4 mb-4 relative overflow-hidden">
                                <div class="text-center">
                                    <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">{{ $unit->category }}</span>
                                    <div class="text-base font-black text-gray-800 mt-1">{{ $unit->brand }} {{ $unit->model }}</div>
                                    <span class="text-[11px] text-gray-500 font-mono">SN: {{ $unit->serial_number }}</span>
                                </div>
                            </div>

                            <h3 class="font-bold text-gray-900 text-sm mb-1">{{ $unit->name }}</h3>
                            <div class="space-y-1.5 text-xs text-gray-600 mb-4">
                                <div class="flex justify-between">
                                    <span class="text-gray-400">Hours Meter:</span>
                                    <span class="font-mono font-bold text-gray-900">{{ number_format($unit->current_hm, 1) }} HM</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-400">Next PM:</span>
                                    <span class="font-semibold text-gray-800">PM-{{ $unit->getNextPmInterval() }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-400">Lokasi:</span>
                                    <span class="text-gray-700 truncate max-w-[120px]">{{ $unit->location ?? 'Site Utama' }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="pt-3 border-t border-gray-200/60 flex items-center gap-2">
                            <a href="{{ route('units.show', $unit) }}" class="flex-1 py-2 px-3 text-center bg-white border border-gray-300 text-gray-700 text-xs font-bold rounded-lg hover:bg-gray-100 transition">
                                Detail Unit
                            </a>
                            <a href="{{ route('work-orders.create', ['unit_id' => $unit->id]) }}" class="flex-1 py-2 px-3 text-center auto2000-red text-white text-xs font-bold rounded-lg auto2000-red-hover transition shadow-xs">
                                Intake WO
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ═══ SECTION: PAKET PERIODIC MAINTENANCE AUTO2000 DIGIROOM ═══ --}}
    <section id="paket-servis" class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-12">
                <span class="text-xs font-extrabold uppercase tracking-widest auto2000-red-text">Standar Servis Terjadwal</span>
                <h2 class="text-2xl sm:text-3xl font-black text-gray-900 mt-1">Paket Periodic Maintenance (PM)</h2>
                <p class="text-sm text-gray-500 mt-2">Didesain khusus untuk memperpanjang usia alat berat dan meminimalkan resiko downtime operasional</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                {{-- PM-250 --}}
                <div class="bg-white rounded-2xl border border-gray-200 p-6 flex flex-col justify-between hover:border-red-500 transition">
                    <div>
                        <span class="px-2.5 py-1 rounded text-[11px] font-bold bg-blue-50 text-blue-700">Interval 250 HM</span>
                        <h3 class="text-lg font-bold text-gray-900 mt-3">PM-250 (Basic Lube & Filter)</h3>
                        <p class="text-xs text-gray-500 mt-2 mb-4">Penggantian oli mesin & filter oli, drain water separator, serta inspeksi 21 titik keselamatan.</p>
                        <ul class="text-xs text-gray-600 space-y-2 mb-6">
                            <li class="flex items-center gap-2"><svg class="w-4 h-4 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg> Engine Oil 15W-40 Replacement</li>
                            <li class="flex items-center gap-2"><svg class="w-4 h-4 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg> Genuine Engine Oil Filter</li>
                            <li class="flex items-center gap-2"><svg class="w-4 h-4 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg> Grease All Boom & Arm Pins</li>
                        </ul>
                    </div>
                    <a href="{{ route('work-orders.create', ['maintenance_type' => 'periodic', 'pm_interval' => 250]) }}" class="w-full py-2.5 text-center text-xs font-bold rounded-xl border border-gray-300 text-gray-800 hover:bg-gray-50 transition">
                        Pilih PM-250
                    </a>
                </div>

                {{-- PM-500 --}}
                <div class="bg-white rounded-2xl border-2 border-red-500 p-6 flex flex-col justify-between shadow-lg relative">
                    <div class="absolute -top-3 left-1/2 -translate-x-1/2 px-3 py-0.5 rounded-full auto2000-red text-white text-[10px] font-black uppercase tracking-wider">
                        Paling Sering Digunakan
                    </div>
                    <div>
                        <span class="px-2.5 py-1 rounded text-[11px] font-bold bg-red-50 auto2000-red-text">Interval 500 HM</span>
                        <h3 class="text-lg font-bold text-gray-900 mt-3">PM-500 (Mid Maintenance)</h3>
                        <p class="text-xs text-gray-500 mt-2 mb-4">Termasuk seluruh paket PM-250 ditambah penggantian filter bahan bakar primer dan sekunder.</p>
                        <ul class="text-xs text-gray-600 space-y-2 mb-6">
                            <li class="flex items-center gap-2"><svg class="w-4 h-4 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg> Semua Fitur PM-250</li>
                            <li class="flex items-center gap-2"><svg class="w-4 h-4 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg> Primary & Secondary Fuel Filter</li>
                            <li class="flex items-center gap-2"><svg class="w-4 h-4 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg> Hydraulic Return Filter Inspection</li>
                        </ul>
                    </div>
                    <a href="{{ route('work-orders.create', ['maintenance_type' => 'periodic', 'pm_interval' => 500]) }}" class="w-full py-2.5 text-center text-xs font-bold rounded-xl auto2000-red auto2000-red-hover text-white transition shadow-sm">
                        Pilih PM-500
                    </a>
                </div>

                {{-- PM-1000 --}}
                <div class="bg-white rounded-2xl border border-gray-200 p-6 flex flex-col justify-between hover:border-red-500 transition">
                    <div>
                        <span class="px-2.5 py-1 rounded text-[11px] font-bold bg-amber-50 text-amber-700">Interval 1000 HM</span>
                        <h3 class="text-lg font-bold text-gray-900 mt-3">PM-1000 (Major Tune-up)</h3>
                        <p class="text-xs text-gray-500 mt-2 mb-4">Penggantian oli transmisi, final drive, damper case, dan penyetelan valve clearance mesin.</p>
                        <ul class="text-xs text-gray-600 space-y-2 mb-6">
                            <li class="flex items-center gap-2"><svg class="w-4 h-4 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg> Final Drive & Transmission Oil</li>
                            <li class="flex items-center gap-2"><svg class="w-4 h-4 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg> Air Cleaner Element Change</li>
                            <li class="flex items-center gap-2"><svg class="w-4 h-4 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg> Engine Valve Clearance Check</li>
                        </ul>
                    </div>
                    <a href="{{ route('work-orders.create', ['maintenance_type' => 'periodic', 'pm_interval' => 1000]) }}" class="w-full py-2.5 text-center text-xs font-bold rounded-xl border border-gray-300 text-gray-800 hover:bg-gray-50 transition">
                        Pilih PM-1000
                    </a>
                </div>

                {{-- PM-2000 --}}
                <div class="bg-white rounded-2xl border border-gray-200 p-6 flex flex-col justify-between hover:border-red-500 transition">
                    <div>
                        <span class="px-2.5 py-1 rounded text-[11px] font-bold bg-purple-50 text-purple-700">Interval 2000 HM</span>
                        <h3 class="text-lg font-bold text-gray-900 mt-3">PM-2000 (Full Flushing)</h3>
                        <p class="text-xs text-gray-500 mt-2 mb-4">Penggantian total oli hidrolik, pendingin mesin (coolant), uji pompa hidrolik & injector test.</p>
                        <ul class="text-xs text-gray-600 space-y-2 mb-6">
                            <li class="flex items-center gap-2"><svg class="w-4 h-4 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg> Full Hydraulic Oil Drain & Refill</li>
                            <li class="flex items-center gap-2"><svg class="w-4 h-4 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg> Radiator Coolant Flush</li>
                            <li class="flex items-center gap-2"><svg class="w-4 h-4 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg> Komprehensif QC 8 Titik Kelaikan</li>
                        </ul>
                    </div>
                    <a href="{{ route('work-orders.create', ['maintenance_type' => 'periodic', 'pm_interval' => 2000]) }}" class="w-full py-2.5 text-center text-xs font-bold rounded-xl border border-gray-300 text-gray-800 hover:bg-gray-50 transition">
                        Pilih PM-2000
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- ═══ SECTION: SUKU CADANG & PELUMAS ASLI (GENUINE PARTS) ═══ --}}
    <section id="suku-cadang" class="py-16 bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-10">
                <div>
                    <span class="text-xs font-extrabold uppercase tracking-widest auto2000-red-text">Gudang & Logistik Suku Cadang</span>
                    <h2 class="text-2xl sm:text-3xl font-black text-gray-900 mt-1">Suku Cadang & Pelumas Resmi</h2>
                    <p class="text-sm text-gray-500 mt-1">100% Produk Original dari Manufaktur Terkemuka Dunia</p>
                </div>
                <a href="{{ route('spare-parts.index') }}" class="mt-4 md:mt-0 inline-flex items-center gap-1.5 text-sm font-bold auto2000-red-text hover:underline">
                    Katalog Spare Part Lengkap &rarr;
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($popularParts as $part)
                    <div class="bg-gray-50 rounded-2xl border border-gray-200 p-5 flex flex-col justify-between hover:shadow-md transition">
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-[10px] font-bold font-mono px-2 py-0.5 rounded bg-white border border-gray-200 text-gray-700">
                                    {{ $part->part_number }}
                                </span>
                                @if($part->is_critical)
                                    <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-red-100 text-red-700">Kritis</span>
                                @endif
                            </div>

                            <div class="my-4 text-center">
                                <div class="w-12 h-12 mx-auto rounded-xl bg-red-50 text-red-600 flex items-center justify-center mb-2">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                </div>
                                <h4 class="font-bold text-gray-900 text-sm line-clamp-1">{{ $part->name }}</h4>
                                <p class="text-xs text-gray-500">{{ $part->brand ?? 'OEM Genuine' }} &bull; {{ $part->category }}</p>
                            </div>

                            <div class="bg-white p-3 rounded-xl border border-gray-100 mb-4 text-xs">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-400">Harga Satuan:</span>
                                    <span class="font-mono font-bold text-gray-900 text-sm">Rp {{ number_format($part->unit_price, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between items-center mt-1">
                                    <span class="text-gray-400">Stok Gudang:</span>
                                    <span class="font-semibold {{ $part->isLowStock() ? 'text-red-600' : 'text-emerald-600' }}">
                                        {{ $part->stock_quantity }} {{ $part->uom }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <a href="{{ route('parts-requisitions.create', ['spare_part_id' => $part->id]) }}" class="w-full py-2 text-center text-xs font-bold rounded-lg border border-red-200 text-red-700 bg-red-50/50 hover:bg-red-100 transition">
                            Ajukan Permintaan Part
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ═══ SECTION: PERFORMANCE CREDIBILITY STATS ═══ --}}
    <section class="py-14 bg-[#1C1C1E] text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                <div>
                    <div class="text-3xl sm:text-4xl font-black auto2000-red-text font-mono">{{ $totalFleetCount }}+</div>
                    <p class="text-xs sm:text-sm text-gray-400 mt-1 font-medium">Armada Unit Terdaftar</p>
                </div>
                <div>
                    <div class="text-3xl sm:text-4xl font-black text-white font-mono">{{ $certifiedMechanicsCount }}</div>
                    <p class="text-xs sm:text-sm text-gray-400 mt-1 font-medium">Mekanik Spesialis Bersertifikat</p>
                </div>
                <div>
                    <div class="text-3xl sm:text-4xl font-black text-emerald-400 font-mono">99.4%</div>
                    <p class="text-xs sm:text-sm text-gray-400 mt-1 font-medium">Lulus QC Tanpa Re-work</p>
                </div>
                <div>
                    <div class="text-3xl sm:text-4xl font-black text-amber-400 font-mono">24/7</div>
                    <p class="text-xs sm:text-sm text-gray-400 mt-1 font-medium">Respon Tanggap Darurat</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ═══ SECTION: BANNER WORKSHOP JOB BOARD CALL TO ACTION ═══ --}}
    <section id="lacak-wo" class="py-16 bg-gradient-to-r from-red-900 to-gray-900 text-white relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="flex flex-col lg:flex-row items-center justify-between gap-8">
                <div class="max-w-2xl">
                    <span class="px-3 py-1 rounded-full bg-red-600/30 border border-red-500/40 text-red-300 text-xs font-bold uppercase tracking-wider">
                        Sistem Job Control Terintegrasi
                    </span>
                    <h2 class="text-3xl sm:text-4xl font-black tracking-tight mt-3">
                        Akses Langsung ke Sistem Bengkel & Job Control
                    </h2>
                    <p class="text-sm text-gray-300 mt-3 leading-relaxed">
                        Kelola alur kerja Service Advisor, penugasan teknisi lapangan, pengeluaran suku cadang dari gudang, hingga tanda tangan digital QC dalam satu sistem.
                    </p>
                </div>
                <div class="flex flex-wrap items-center gap-4 flex-shrink-0">
                    <a href="{{ route('dashboard') }}" class="px-6 py-3.5 rounded-xl auto2000-red auto2000-red-hover text-white text-sm font-bold shadow-lg transition">
                        Buka Dashboard Workshop &rarr;
                    </a>
                    <a href="{{ route('work-orders.index') }}" class="px-6 py-3.5 rounded-xl bg-white/10 hover:bg-white/20 text-white text-sm font-bold backdrop-blur border border-white/20 transition">
                        Lihat Job Board WO ({{ $activeWorkOrdersCount }} Aktif)
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- ═══ FOOTER AUTO2000 DIGIROOM ═══ --}}
    <footer class="bg-white border-t border-gray-200 pt-16 pb-12 text-xs text-gray-600">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-8 mb-12">
                <div class="md:col-span-2 space-y-4">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 auto2000-red rounded-lg flex items-center justify-center font-black text-white text-base">H2K</div>
                        <span class="font-black text-lg text-gray-900 tracking-tight">HEAVY2000 DIGIROOM</span>
                    </div>
                    <p class="text-gray-500 leading-relaxed pr-6">
                        Layanan digital servis, perbaikan, dan suku cadang alat berat terlengkap di Indonesia dengan standar mutu purna jual Auto2000. Melayani sektor pertambangan, kehutanan, perkebunan, dan infrastruktur konstruksi.
                    </p>
                    <div class="pt-2 text-gray-500">
                        <div>Kantor Pusat: Workshop Terpadu Kawasan Industri Sentul, Jawa Barat</div>
                        <div class="mt-1">Hotline Halo Heavy2000: <strong class="text-gray-800">1-500-898</strong> (Bebas Pulsa)</div>
                    </div>
                </div>

                <div>
                    <h4 class="font-bold text-gray-900 mb-4 uppercase text-[11px] tracking-wider">Layanan Bengkel</h4>
                    <ul class="space-y-2 text-gray-500">
                        <li><a href="{{ route('work-orders.create') }}" class="hover:auto2000-red-text">Booking Servis Intake</a></li>
                        <li><a href="#layanan-bengkel" class="hover:auto2000-red-text">THS (Total Heavy Service)</a></li>
                        <li><a href="#paket-servis" class="hover:auto2000-red-text">Paket PM 250 - 2000 HM</a></li>
                        <li><a href="{{ route('qc-checklists.index') }}" class="hover:auto2000-red-text">Inspeksi Quality Control</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-bold text-gray-900 mb-4 uppercase text-[11px] tracking-wider">Armada & Suku Cadang</h4>
                    <ul class="space-y-2 text-gray-500">
                        <li><a href="{{ route('units.index') }}" class="hover:auto2000-red-text">Katalog Excavator</a></li>
                        <li><a href="{{ route('units.index') }}" class="hover:auto2000-red-text">Katalog Bulldozer & Dump Truck</a></li>
                        <li><a href="{{ route('spare-parts.index') }}" class="hover:auto2000-red-text">Filter & Pelumas Mesin</a></li>
                        <li><a href="{{ route('spare-parts.index') }}" class="hover:auto2000-red-text">Seal Kit & Pompa Hidrolik</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-bold text-gray-900 mb-4 uppercase text-[11px] tracking-wider">Portal Workshop</h4>
                    <ul class="space-y-2 text-gray-500">
                        <li><a href="{{ route('dashboard') }}" class="hover:auto2000-red-text">Dashboard Monitoring</a></li>
                        <li><a href="{{ route('work-orders.index') }}" class="hover:auto2000-red-text">Job Control & Work Order</a></li>
                        <li><a href="{{ route('mechanics.index') }}" class="hover:auto2000-red-text">Tim Mekanik & Teknisi</a></li>
                        <li><a href="{{ route('parts-requisitions.index') }}" class="hover:auto2000-red-text">Parts Requisition System</a></li>
                    </ul>
                </div>
            </div>

            <div class="pt-8 border-t border-gray-200 flex flex-col sm:flex-row items-center justify-between gap-4 text-gray-400">
                <p>&copy; {{ date('Y') }} Heavy2000 Digiroom &bull; All Rights Reserved. Adapted from Auto2000 Service Operational Standards for Heavy Machinery.</p>
                <div class="flex items-center gap-4">
                    <a href="#" class="hover:text-gray-600">Syarat & Ketentuan</a>
                    <span>&bull;</span>
                    <a href="#" class="hover:text-gray-600">Kebijakan Privasi</a>
                    <span>&bull;</span>
                    <a href="{{ route('dashboard') }}" class="hover:text-gray-900 font-semibold">Workshop Login</a>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>
