@extends('includes_admin.mainlayout')

@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('admin.dashboard')</a></li>
                <li class="breadcrumb-item active">@lang('deals.clinic_deals')</li>
            </ul>
        </div>

        <form method="get" class="card mb-3">
            <div class="card-body row g-2 align-items-end">
                <div class="col-md-4">
                    <label class="form-label">@lang('deals.status')</label>
                    <select name="status" class="form-control">
                        <option value="">@lang('deals.all')</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>@lang('deals.active')</option>
                        <option value="not_active" {{ request('status') === 'not_active' ? 'selected' : '' }}>@lang('deals.not_active')</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary w-100" type="submit">@lang('deals.filters')</button>
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
                        <th>@lang('deals.title')</th>
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
                            <td>{{ $deal->specialty->name_ar ?? '-' }}</td>
                            <td>{{ number_format($deal->price_before, 2) }}</td>
                            <td>{{ number_format($deal->price_after, 2) }}</td>
                            <td>{{ $deal->number_of_uses }} / {{ $deal->maximum_number_of_usage ?? __('deals.unlimited') }}</td>
                            <td>{{ optional($deal->expiration_date)->format('Y-m-d') ?? '-' }}</td>
                            <td>{{ $deal->status === 'active' ? __('deals.active') : __('deals.not_active') }}</td>
                            <td>
                                <a href="{{ route('clinic-deals.show', $deal->id) }}" class="btn btn-sm btn-outline-primary">@lang('deals.details')</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="10" class="text-center">@lang('deals.no_deals')</td></tr>
                    @endforelse
                    </tbody>
                </table>
                {{ $deals->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
