@props(['status'])
@php
    $map = [
        'ACTIVE' => 'success',
        'PENDING' => 'warning',
        'TERMINATED' => 'danger',
        'EXPIRED' => 'secondary',
        'ISSUED' => 'primary',
        'PAID' => 'success',
        'OVERDUE' => 'danger',
        'VOID' => 'dark',
        'ACTIVE_U' => 'success',
        'INACTIVE' => 'secondary',
    ];
    $variant = $map[$status] ?? 'secondary';
@endphp
<span class="badge bg-{{ $variant }}">{{ $status }}</span>

