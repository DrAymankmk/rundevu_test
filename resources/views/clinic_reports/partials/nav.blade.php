@php
    $pages = [
        'appointments' => ['route' => 'clinic-reports.appointments', 'label' => 'clinic_reports.appointments'],
        'doctors' => ['route' => 'clinic-reports.doctors', 'label' => 'clinic_reports.doctors'],
        'patients' => ['route' => 'clinic-reports.patients', 'label' => 'clinic_reports.patients'],
        'reviews' => ['route' => 'clinic-reports.reviews', 'label' => 'clinic_reports.reviews'],
    ];
@endphp
<div class="card mb-3">
    <div class="card-body py-2">
        <ul class="nav nav-pills flex-wrap">
            @foreach($pages as $key => $item)
                <li class="nav-item">
                    <a class="nav-link {{ ($page ?? '') === $key ? 'active' : '' }}"
                       href="{{ route($item['route']) }}">
                        @lang($item['label'])
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</div>
