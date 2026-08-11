<div class="table-responsive">
    <table class="table table-nowrap">
        <thead>
        <tr>
            <th>{{ trans('admin.name') }}</th>
            <th>{{trans('main.Phone')}}</th>
            <th>{{trans('main.email')}}</th>
            <th>{{trans('main.total_points')}}</th>
            <th>{{ trans('admin.Created Date') }}</th>
            <th>{{ trans('admin.Status') }}</th>
            <th>{{ trans('admin.Action') }}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($accounts as $account)
            <tr id="row-{{ $account->id }}">
                <td>
                    <div class="d-flex align-items-center">
                        <a href="{{url('patient-details')}}" class="avatar avatar-md me-2">
                            <img src="{{ $account->image }}" alt="{{ $account->name }}" class="rounded-circle">
                        </a>
                        <a href="{{url('patient-details')}}" class="text-dark fw-semibold">{{ $account->name ?? null }} <span class="text-body fs-13 fw-normal d-block"> {{ app()->getLocale() == 'en' ? $account->city->name_en ?? null : $account->city->name_ar ?? null }}, @if($account->gender == 1) @lang('admin.male')@else @lang('admin.female') @endif </span>  </a>
                    </div>
                </td>
                <td>{{ $account->phone ?? null }}</td>
                <td>{{ $account->email ?? null }}</td>
                <td>0</td>
                <td>{{ $account->created_at->format('d M Y') }}</td>
                <td><span class="badge @if($account->status == 1) badge-soft-success border border-success @else badge-soft-danger border border-danger @endif  px-2 py-1 fs-13 fw-medium">@if($account->status == 1) @lang('admin.Active') @else @lang('admin.Inactive') @endif </span></td>
                <td>
                    {{--                                <a href="{{ route('SubSpecialties', $user->id) }}" class="link-reset fs-18 p-1"> <i class="ti ti-eye"></i></a>--}}
                    <a href="javascript:void(0);"
                       class="link-reset fs-18 p-1 delete-btn"
                       data-route="{{ route('destroy-clinic', $account->id) }}"
                       data-id="row-{{ $account->id }}"
                       data-bs-toggle="modal"
                       data-bs-target="#genericDeleteModal">
                        <i class="ti ti-trash"></i>
                    </a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@include('layout_new.partials.delete_modal')
