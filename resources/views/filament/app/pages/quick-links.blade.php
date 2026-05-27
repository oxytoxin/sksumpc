@php
$groupColors = [
    'Cashier' => 'bg-sky-50 border-sky-200 hover:border-sky-400 hover:bg-sky-100',
    'Loan' => 'bg-amber-50 border-amber-200 hover:border-amber-400 hover:bg-amber-100',
    'Share Capital' => 'bg-emerald-50 border-emerald-200 hover:border-emerald-400 hover:bg-emerald-100',
    'Bookkeeping' => 'bg-violet-50 border-violet-200 hover:border-violet-400 hover:bg-violet-100',
    'Management' => 'bg-rose-50 border-rose-200 hover:border-rose-400 hover:bg-rose-100',
    'Reports' => 'bg-orange-50 border-orange-200 hover:border-orange-400 hover:bg-orange-100',
];
$groupIconColors = [
    'Cashier' => 'text-sky-500',
    'Loan' => 'text-amber-500',
    'Share Capital' => 'text-emerald-500',
    'Bookkeeping' => 'text-violet-500',
    'Management' => 'text-rose-500',
    'Reports' => 'text-orange-500',
];
$groupHeadingColors = [
    'Cashier' => 'border-sky-300 text-sky-700',
    'Loan' => 'border-amber-300 text-amber-700',
    'Share Capital' => 'border-emerald-300 text-emerald-700',
    'Bookkeeping' => 'border-violet-300 text-violet-700',
    'Management' => 'border-rose-300 text-rose-700',
    'Reports' => 'border-orange-300 text-orange-700',
];
@endphp

<x-filament-panels::page>
    <div class="grid gap-10">
        @foreach($groups as $group)
            <div>
                <div class="flex items-center gap-3 mb-5 pb-3 border-b-2 {{ $groupHeadingColors[$group['label']] ?? 'border-gray-200 text-gray-900' }}">
                    <x-filament::icon icon="{{ $group['icon'] }}" class="h-7 w-7 {{ $groupIconColors[$group['label']] ?? 'text-primary-500' }}" />
                    <h2 class="text-xl font-black tracking-tight">
                        {{ $group['label'] }}
                    </h2>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-3">
                    @foreach($group['items'] as $item)
                        <a href="{{ $item['url'] }}"
                           class="aspect-square flex flex-col items-center justify-center gap-2 rounded-xl border p-3 text-center transition-all duration-200 hover:shadow-lg hover:-translate-y-0.5 {{ $groupColors[$group['label']] ?? 'bg-white border-gray-200 hover:border-primary-400' }}">
                            <x-filament::icon icon="{{ $item['icon'] }}" class="h-8 w-8 {{ $groupIconColors[$group['label']] ?? 'text-gray-400' }}" />
                            <span class="text-xs font-semibold {{ $groupHeadingColors[$group['label']] ?? 'text-gray-600' }} leading-tight line-clamp-2">
                                {{ $item['label'] }}
                            </span>
                        </a>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</x-filament-panels::page>
