<?php $page = 'points-exchanges'; ?>

@extends('layout_new.mainlayout')

@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="d-flex align-items-sm-center flex-sm-row flex-column gap-2 pb-3 mb-3 border-1 border-bottom">
            <div class="flex-grow-1">
                <h4 class="fw-bold mb-0">
                    @lang('main.points_exchanges')
                    <span class="badge badge-soft-primary fw-medium border py-1 px-2 border-primary fs-13 ms-1">
                        {{ $exchanges->total() }}
                    </span>
                </h4>
                <p class="text-muted mb-0 mt-1">@lang('main.points_exchanges_hint')</p>
            </div>
            <div>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addExchangeModal">
                    <i class="ti ti-plus me-1"></i>@lang('main.add_points_exchanges')
                </button>
            </div>
        </div>

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>@lang('admin.points')</th>
                            <th>@lang('admin.price')</th>
                            <th>@lang('admin.Created Date')</th>
                            <th>@lang('admin.status')</th>
                            <th>@lang('admin.Actions')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($exchanges as $exchange)
                            <tr>
                                <td>{{ $exchange->id }}</td>
                                <td>{{ $exchange->points }}</td>
                                <td>{{ number_format((float) $exchange->price, 2) }} @lang('main.loyalty_currency_sar')</td>
                                <td>{{ $exchange->created_at?->format('Y-m-d') }}</td>
                                <td>
                                    <div class="form-check form-switch mb-0">
                                        <input type="checkbox" class="form-check-input exchange-status-toggle"
                                               data-id="{{ $exchange->id }}"
                                               {{ $exchange->status ? 'checked' : '' }}>
                                    </div>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal"
                                            data-bs-target="#editExchange{{ $exchange->id }}">@lang('admin.edit')</button>
                                    <form action="{{ route('points-exchanges.destroy', $exchange->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"
                                                onclick="return confirm(@json(__('admin.confirm_delete')))">@lang('admin.delete')</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">@lang('main.no_data')</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                {{ $exchanges->links() }}
            </div>
        </div>
    </div>
</div>

@foreach($exchanges as $exchange)
    <div class="modal fade" id="editExchange{{ $exchange->id }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('points-exchanges.update', $exchange->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">@lang('admin.edit') — {{ $exchange->points }} @lang('admin.points')</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        @include('main_admin.points.partials.exchange-form-fields', ['exchange' => $exchange])
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">@lang('admin.cancel')</button>
                        <button type="submit" class="btn btn-primary">@lang('admin.save')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach

<div class="modal fade" id="addExchangeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('points-exchanges.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">@lang('main.add_points_exchanges')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    @include('main_admin.points.partials.exchange-form-fields', ['exchange' => null])
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">@lang('admin.cancel')</button>
                    <button type="submit" class="btn btn-primary">@lang('admin.add')</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('.exchange-status-toggle').forEach(function (toggle) {
        toggle.addEventListener('change', function () {
            var id = this.dataset.id;
            var status = this.checked ? 1 : 0;
            var checkbox = this;

            fetch("{{ url('admin/points-exchanges') }}/" + id + "/status/" + status, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            }).catch(function () {
                checkbox.checked = !checkbox.checked;
            });
        });
    });
</script>
@endpush
