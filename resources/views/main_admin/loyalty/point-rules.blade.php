<?php $page = 'loyalty-point-rules'; ?>

@extends('layout_new.mainlayout')

@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="d-flex align-items-sm-center flex-sm-row flex-column gap-2 pb-3 mb-3 border-1 border-bottom">
            <div class="flex-grow-1">
                <h4 class="fw-bold mb-0">
                    @lang('main.loyalty_point_rules')
                    <span class="badge badge-soft-primary fw-medium border py-1 px-2 border-primary fs-13 ms-1">
                        {{ $rules->total() }}
                    </span>
                </h4>
                <p class="text-muted mb-0 mt-1">@lang('main.loyalty_point_rules_hint')</p>
            </div>
            <div>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addRuleModal">
                    @lang('main.loyalty_add_rule')
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
                            <th>@lang('main.loyalty_rule_key')</th>
                            <th>@lang('admin.name')</th>
                            <th>@lang('main.all_points')</th>
                            <th>@lang('main.loyalty_max_per_day')</th>
                            <th>@lang('main.loyalty_min_words')</th>
                            <th>@lang('main.loyalty_expires_after_months')</th>
                            <th>@lang('admin.status')</th>
                            <th>@lang('admin.Actions')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($rules as $rule)
                            @php $locale = app()->getLocale(); @endphp
                            <tr>
                                <td>{{ $rule->id }}</td>
                                <td><code>{{ $rule->key }}</code></td>
                                <td>{{ $locale === 'ar' ? $rule->name_ar : $rule->name_en }}</td>
                                <td>{{ $rule->points }}</td>
                                <td>{{ $rule->max_per_day ?? '—' }}</td>
                                <td>{{ $rule->min_words ?? '—' }}</td>
                                <td>{{ $rule->expires_after_months }}</td>
                                <td>
                                    <div class="form-check form-switch mb-0">
                                        <input type="checkbox" class="form-check-input rule-status-toggle"
                                               data-id="{{ $rule->id }}"
                                               {{ $rule->status ? 'checked' : '' }}>
                                    </div>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal"
                                            data-bs-target="#editRule{{ $rule->id }}">@lang('admin.edit')</button>
                                    <form action="{{ route('loyalty-point-rules.destroy', $rule->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"
                                                onclick="return confirm(@json(__('admin.confirm_delete')))">@lang('admin.delete')</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted">@lang('main.no_data')</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                {{ $rules->links() }}
            </div>
        </div>
    </div>
</div>

@foreach($rules as $rule)
    <div class="modal fade" id="editRule{{ $rule->id }}" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form method="POST" action="{{ route('loyalty-point-rules.update', $rule->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">@lang('admin.edit') — {{ $rule->key }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        @include('main_admin.loyalty.partials.point-rule-form-fields', ['rule' => $rule])
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

<div class="modal fade" id="addRuleModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" action="{{ route('loyalty-point-rules.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">@lang('main.loyalty_add_rule')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    @include('main_admin.loyalty.partials.point-rule-form-fields', ['rule' => null])
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">@lang('admin.cancel')</button>
                    <button type="submit" class="btn btn-primary">@lang('admin.save')</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('.rule-status-toggle').forEach(function (toggle) {
        toggle.addEventListener('change', function () {
            var id = this.dataset.id;
            var status = this.checked ? 1 : 0;
            var checkbox = this;

            fetch("{{ url('admin/loyalty-point-rules') }}/" + id + "/status/" + status, {
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
