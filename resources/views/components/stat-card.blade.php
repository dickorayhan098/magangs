@props(['value' => '', 'label' => '', 'icon' => '', 'color' => 'primary', 'trend' => null, 'trendUp' => true])

@php
$bgColors = [
    'primary' => 'bg-primary-50 text-primary-600',
    'success' => 'bg-emerald-50 text-emerald-600',
    'warning' => 'bg-amber-50 text-amber-600',
    'danger'  => 'bg-red-50 text-red-600',
    'info'    => 'bg-cyan-50 text-cyan-600',
];
$bg = $bgColors[$color] ?? $bgColors['primary'];
@endphp

<div class="bg-white rounded-xl border border-gray-200 p-5 hover:shadow-md transition-shadow duration-300">
    <div class="flex items-start justify-between">
        <div>
            <p class="text-sm font-medium text-gray-500">{{ $label }}</p>
            <p class="mt-2 text-3xl font-bold text-gray-900 tracking-tight">{{ $value }}</p>
            @if($trend)
                <div class="mt-2 flex items-center gap-1">
                    @if($trendUp)
                        <svg class="w-4 h-4 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
                    @else
                        <svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 012 0v7.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                    @endif
                    <span class="text-xs font-medium {{ $trendUp ? 'text-emerald-600' : 'text-red-600' }}">{{ $trend }}</span>
                </div>
            @endif
        </div>
        <div class="p-3 rounded-xl {{ $bg }}">
            {!! $icon !!}
        </div>
    </div>
</div>
