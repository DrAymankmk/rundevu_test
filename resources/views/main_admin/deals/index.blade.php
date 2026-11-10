@extends('layout_new.mainlayout')

@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('admin.dashboard')</a></li>
                        <li class="breadcrumb-item active">@lang('deals.title')</li>
                    </ul>
                </div>
                <div class="col-auto">
                    <a href="{{ route('deals.create') }}" class="btn btn-primary">@lang('deals.add_deal')</a>
                </div>
            </div>
        </div>

        <form method="get" class="card mb-3">
            <div class="card-body row g-2 align-items-end">
                <div class="col-md-4">
                    <label class="form-label">@lang('deals.search')</label>
                    <input type="text" name="search" class="form-control" value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">@lang('deals.clinic')</label>
                    <select name="clinic_id" class="form-control">
                        <option value="">@lang('deals.all')</option>
                        @foreach($clinics as $clinic)
                            <option value="{{ $clinic->id }}" {{ (string) request('clinic_id') === (string) $clinic->id ? 'selected' : '' }}>{{ $clinic->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">@lang('deals.status')</label>
                    <select name="status" class="form-control">
                        <option value="">@lang('deals.all')</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>@lang('deals.active')</option>
                        <option value="not_active" {{ request('status') === 'not_active' ? 'selected' : '' }}>@lang('deals.not_active')</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary w-100" type="submit">@lang('deals.search')</button>
                </div>
            </div>
        </form>

        <div class="card">
            <div class="card-body table-responsive">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>@lang('deals.image')</th>
                        <th>@lang('deals.title_ar')</th>
                        <th>@lang('deals.clinic')</th>
                        <th>@lang('deals.specialty')</th>
                        <th>@lang('deals.price_before')</th>
                        <th>@lang('deals.price_after')</th>
                        <th>@lang('deals.uses')</th>
                        <th>@lang('deals.expiration_date')</th>
                        <th>@lang('deals.status')</th>
                        <th>@lang('deals.actions')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($deals as $deal)
                        <tr>
                            <td>{{ $deal->id }}</td>
                            <td>@if($deal->image)<img src="{{ $deal->image }}" style="height:40px">@endif</td>
                            <td>{{ $deal->localizedTitle() }}</td>
                            <td>{{ $deal->clinic->name ?? '-' }}</td>
                            <td>{{ $deal->specialty->name_ar ?? '-' }}</td>
                            <td>{{ number_format($deal->price_before, 2) }}</td>
                            <td>{{ number_format($deal->price_after, 2) }}</td>
                            <td>{{ $deal->number_of_uses }} / {{ $deal->maximum_number_of_usage ?? __('deals.unlimited') }}</td>
                            <td>{{ optional($deal->expiration_date)->format('Y-m-d') ?? '-' }}</td>
                            <td>
                                @if($deal->status === 'active')
                                    <a href="{{ route('deals.toggle', [$deal->id, 'not_active']) }}" class="badge bg-success">@lang('deals.active')</a>
                                @else
                                    <a href="{{ route('deals.toggle', [$deal->id, 'active']) }}" class="badge bg-secondary">@lang('deals.not_active')</a>
                                @endif
                            </td>
                            <td class="text-nowrap">
                                <a href="{{ route('deals.edit', $deal->id) }}" class="btn btn-sm btn-outline-primary">@lang('deals.edit_deal')</a>
                                <form action="{{ route('deals.destroy', $deal->id) }}" method="post" class="d-inline" onsubmit="return confirm('@lang('admin.Are you sure?')')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" type="submit">@lang('admin.delete')</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="11" class="text-center">@lang('deals.no_deals')</td></tr>
                    @endforelse
                    </tbody>
                </table>
                {{ $deals->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
