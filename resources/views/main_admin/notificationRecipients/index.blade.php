@extends('layout_new.mainlayout')
@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="card-title mb-0">@lang('main.notification_recipients') ({{ $recipients->count() }})</h5>
                                <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#add_recipient" class="btn btn-primary btn-sm">
                                    @lang('main.notification_add_recipient')
                                </a>
                            </div>

                            @if(session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif

                            @if($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="table-responsive">
                                <table id="notification-recipients-table" class="table border-0 custom-table comman-table mb-0 w-100">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>@lang('main.notification_recipient_email')</th>
                                            <th>@lang('main.notification_recipient_label')</th>
                                            <th>@lang('main.notification_subscribed_events')</th>
                                            <th>@lang('admin.status')</th>
                                            <th>@lang('admin.options')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($recipients as $index => $recipient)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $recipient->email }}</td>
                                                <td>{{ $recipient->label ?? '—' }}</td>
                                                <td>
                                                    @foreach($recipient->events as $event)
                                                        <span class="badge bg-light text-dark border me-1">{{ $event->localizedName() }}</span>
                                                    @endforeach
                                                </td>
                                                <td>
                                                    @if($recipient->is_active)
                                                        <span class="badge bg-success">@lang('admin.Active')</span>
                                                    @else
                                                        <span class="badge bg-secondary">@lang('admin.In Active')</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <a href="javascript:void(0)" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#edit_recipient_{{ $recipient->id }}">
                                                            <i class="fa fa-pen-to-square"></i>
                                                        </a>
                                                        <a href="javascript:void(0)" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#delete_recipient_{{ $recipient->id }}">
                                                            <i class="fa fa-trash-can"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@foreach($recipients as $recipient)
    <div class="modal fade" id="edit_recipient_{{ $recipient->id }}" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('notification-recipients.update', $recipient->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">@lang('main.notification_edit_recipient')</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        @include('main_admin.notificationRecipients.partials.form-fields', [
                            'recipient' => $recipient,
                            'events' => $events,
                            'modalKey' => 'edit-' . $recipient->id,
                        ])
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-white" data-bs-dismiss="modal">@lang('admin.cancel')</button>
                        <button type="submit" class="btn btn-primary">@lang('admin.edit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade delete-modal" id="delete_recipient_{{ $recipient->id }}" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('notification-recipients.destroy', $recipient->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-body text-center">
                        <h3>@lang('admin.Are you sure you want to delete this?')</h3>
                        <p class="text-muted">{{ $recipient->email }}</p>
                        <div class="m-t-20">
                            <a href="javascript:void(0);" class="btn btn-white" data-bs-dismiss="modal">@lang('admin.close')</a>
                            <button type="submit" class="btn btn-danger">@lang('admin.delete')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach

<div class="modal fade" id="add_recipient" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('notification-recipients.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">@lang('main.notification_add_recipient')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    @include('main_admin.notificationRecipients.partials.form-fields', [
                        'recipient' => null,
                        'events' => $events,
                        'modalKey' => 'add',
                    ])
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-bs-dismiss="modal">@lang('admin.cancel')</button>
                    <button type="submit" class="btn btn-primary">@lang('admin.add')</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    var tableEl = $('#notification-recipients-table');

    if ($.fn.DataTable.isDataTable(tableEl)) {
        tableEl.DataTable().destroy();
    }

    tableEl.DataTable({
        ordering: true,
        autoWidth: false,
        language: Object.assign({}, (typeof languages !== 'undefined' && languages[language]) ? languages[language] : {}, {
            emptyTable: @json(__('main.notification_no_recipients'))
        }),
        columnDefs: [
            { orderable: false, targets: 5 }
        ]
    });
});
</script>
@endpush
