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
                                <h5 class="card-title mb-0">
                                    @lang('main.contact_us')
                                    ({{ $messages->count() }})
                                    @if($messages->where('is_read', false)->count() > 0)
                                        <span class="badge bg-danger ms-2">{{ $messages->where('is_read', false)->count() }} @lang('main.unread')</span>
                                    @endif
                                </h5>
                            </div>

                            @if(session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif

                            <div class="table-responsive">
                                <table id="contact-us-table" class="table border-0 custom-table comman-table mb-0 w-100">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>@lang('main.name')</th>
                                            <th>@lang('main.email')</th>
                                            <th>@lang('main.phone_number')</th>
                                            <th>@lang('main.your_message')</th>
                                            <th>@lang('admin.status')</th>
                                            <th>@lang('main.created_date')</th>
                                            <th>@lang('admin.options')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($messages as $index => $message)
                                            <tr class="{{ $message->is_read ? '' : 'table-warning' }}">
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $message->name }}</td>
                                                <td><a href="mailto:{{ $message->email }}">{{ $message->email }}</a></td>
                                                <td>
                                                    @if($message->phone)
                                                        <a href="tel:{{ preg_replace('/[^\d+]/', '', $message->phone) }}">{{ $message->phone }}</a>
                                                    @else
                                                        —
                                                    @endif
                                                </td>
                                                <td style="max-width: 280px; white-space: normal;">{{ Str::limit($message->message, 120) }}</td>
                                                <td>
                                                    @if($message->is_read)
                                                        <span class="badge bg-success">@lang('main.read')</span>
                                                        @if($message->readByClinic)
                                                            <small class="d-block text-muted mt-1">{{ $message->readByClinic->name }}</small>
                                                        @endif
                                                    @else
                                                        <span class="badge bg-danger">@lang('main.unread')</span>
                                                    @endif
                                                </td>
                                                <td>{{ $message->created_at?->format('d M Y H:i') }}</td>
                                                <td>
                                                    @if(! $message->is_read)
                                                        <form action="{{ route('contact-us.mark-read', $message) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="btn btn-sm btn-primary">
                                                                @lang('main.mark_as_read')
                                                            </button>
                                                        </form>
                                                    @else
                                                        <span class="text-muted">—</span>
                                                    @endif
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
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    var tableEl = $('#contact-us-table');

    if ($.fn.DataTable.isDataTable(tableEl)) {
        tableEl.DataTable().destroy();
    }

    tableEl.DataTable({
        order: [[6, 'desc']],
        language: Object.assign({}, (typeof languages !== 'undefined' && languages[language]) ? languages[language] : {}, {
            emptyTable: @json(__('main.no_contact_us_yet'))
        })
    });
});
</script>
@endpush
