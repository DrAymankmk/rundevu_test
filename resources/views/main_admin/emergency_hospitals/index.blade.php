<?php $page = 'emergency-hospitals'; ?>
@extends('layout_new.mainlayout')

@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="d-flex align-items-sm-center flex-sm-row flex-column gap-2 pb-3 mb-3 border-1 border-bottom">
                <div class="flex-grow-1">
                    <h4 class="fw-bold mb-0">مستشفيات الطوارئ
                        <span class="badge badge-soft-primary fw-medium border py-1 px-2 border-primary fs-13 ms-1">
                            {{ $hospitals->total() }}
                        </span>
                    </h4>
                </div>
                <a href="{{ route('emergency-hospitals.create') }}" class="btn btn-primary ms-2 fs-13 btn-md">
                    <i class="ti ti-plus me-1"></i>Add
                </a>
            </div>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form class="row g-2 mb-3" method="GET">
                <div class="col-md-5">
                    <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Search by Arabic or English name">
                </div>
                <div class="col-md-4">
                    <select name="city_id" class="form-control">
                        <option value="">All cities</option>
                        @foreach($cities as $city)
                            <option value="{{ $city->id }}" {{ request('city_id') == $city->id ? 'selected' : '' }}>
                                {{ app()->getLocale() == 'en' ? $city->name_en : $city->name_ar }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <button class="btn btn-primary w-100" type="submit">Filter</button>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-nowrap">
                    <thead>
                    <tr>
                        <th>Image</th>
                        <th>Arabic Name</th>
                        <th>English Name</th>
                        <th>Phone</th>
                        <th>City</th>
                        <th>Location</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($hospitals as $hospital)
                        <tr>
                            <td><img src="{{ $hospital->image }}" alt="{{ $hospital->name_en }}" class="rounded" style="width:48px;height:48px;object-fit:cover"></td>
                            <td>{{ $hospital->name_ar }}</td>
                            <td>{{ $hospital->name_en }}</td>
                            <td><a href="tel:{{ $hospital->phone }}">{{ $hospital->phone }}</a></td>
                            <td>{{ optional($hospital->city)->{'name_' . app()->getLocale()} }}</td>
                            <td>
                                <a href="https://www.google.com/maps/search/?api=1&query={{ $hospital->lat }},{{ $hospital->lng }}" target="_blank">Map</a>
                            </td>
                            <td>
                                <input type="checkbox" {{ $hospital->status ? 'checked' : '' }} onchange="toggleEmergencyHospital({{ $hospital->id }}, {{ $hospital->status ? 0 : 1 }})">
                            </td>
                            <td>
                                <a href="{{ route('emergency-hospitals.edit', $hospital->id) }}" class="link-reset fs-18 p-1"><i class="ti ti-edit"></i></a>
                                <form action="{{ route('emergency-hospitals.destroy', $hospital->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-link link-reset fs-18 p-1" type="submit" onclick="return confirm('Delete this hospital?')">
                                        <i class="ti ti-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center text-muted">No data</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            {{ $hospitals->links() }}
        </div>
        @component('components.footer')@endcomponent
    </div>

    <script>
        function toggleEmergencyHospital(id, status) {
            fetch(`/admin/emergency-hospitals/${id}/status/${status}`)
                .then(() => window.location.reload());
        }
    </script>
@endsection
