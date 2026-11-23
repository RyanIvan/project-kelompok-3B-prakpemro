@props(['status'])
@php
    $map = [
        'pending' => ['Menunggu Validasi', 'bg-amber-100 text-amber-700'],
        'approved' => ['Disetujui', 'bg-emerald-100 text-emerald-700'],
        'rejected' => ['Ditolak', 'bg-red-100 text-red-700'],
    ];
    [$text, $color] = $map[strtolower((string) $status)] ?? [(string) $status, 'bg-gray-200 text-gray-600'];
@endphp
<span {{ $attributes->merge(['class' => "inline-block px-2.5 py-0.5 rounded-full text-xs font-semibold $color"]) }}>{{ $text }}</span>
