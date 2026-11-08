@props(['title', 'value', 'icon', 'color' => 'primary', 'description' => null])

<div class="card stat-card h-100">
    <div class="card-body d-flex justify-content-between align-items-center">
        <div>
            <div class="text-secondary mb-2">{{ $title }}</div>
            <h3 class="fw-bold mb-1">{{ $value }}</h3>

            @if ($description)
                <small class="text-secondary">{{ $description }}</small>
            @endif
        </div>

        <div class="stat-icon bg-{{ $color }}-subtle text-{{ $color }}">
            <i class="bi {{ $icon }}"></i>
        </div>
    </div>
</div>
