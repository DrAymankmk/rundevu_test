@extends('includes_admin.mainlayout')
@section('content')
    @php
        $isPatientMessages = $isPatientMessages ?? false;
        $pageTitle = $pageTitle ?? trans('admin.complaints_box');
        $messageLabel = $isPatientMessages ? trans('admin.patient_message') : trans('admin.message');
    @endphp

    <style>
        .messages-table {
            table-layout: fixed;
            width: 100%;
        }

        .messages-table th,
        .messages-table td {
            vertical-align: middle;
        }

        .messages-table .message-text,
        .messages-table .reply-text {
            max-width: 360px;
            white-space: normal;
            word-break: break-word;
            line-height: 1.7;
        }

        .messages-table .actions-cell {
            min-width: 185px;
            white-space: nowrap;
        }

        .reply-action-btn {
            min-width: 96px;
            padding: 10px 18px;
            font-size: 15px;
            font-weight: 700;
        }

        .delete-action-btn {
            padding: 10px 13px;
            font-size: 15px;
        }
    </style>

    <!-- Right sidebar Ends-->
    <div class="page-body">
        <div class="container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col">
                        <div class="page-header-left">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}"><i
                                                data-feather="home"> </i> {{ trans('admin.dashboard') }} </a></li>
                                <li class="breadcrumb-item active">{{ $pageTitle }} ({{ $messages->total() }})</li>
                            </ol>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="row">

                <div class="col-sm-12">
                    <div class="card">
                        @if( count($messages) > 0)
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="display dataTable messages-table" id="basic-1">
                                        <thead>
                                        <tr>
                                            <th style="display: none">#</th>
                                            <th>{{ trans('admin.name') }}</th>
                                            <th>{{ trans('admin.accounts.phone') }}</th>
                                            <th>{{ trans('admin.accounts.email') }}</th>
                                            <th>{{ $messageLabel }}</th>
                                            <th> {{ trans('admin.created_at') }}</th>
                                            <th> {{ trans('admin.reply') }}</th>
                                            <th> {{ trans('admin.action') }}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($messages as $index=>$message)
                                            <tr>
                                                <td style="display: none">{{$index + 1}}</td>
                                                <td>{{ optional($message->users)->name }}</td>
                                                <td>{{ optional($message->users)->phone }}</td>
                                                <td>{{ optional($message->users)->email }}</td>
                                                <td class="message-text">{{ $message->complain }}</td>
                                                <td>{{ $message->created_at }}</td>
                                                @if(!empty($message->reply))
                                                    <td class="reply-text">{{ $message->reply }}</td>
                                                @else
                                                    <td class="reply-text" style="color: #ff0000">{{ trans('admin.no_response') }}</td>
                                                @endif
                                                <td class="actions-cell">
{{--                                                    @if (auth()->user()->hasPermissionTo('رد على اتصل بنا'))--}}
                                                        <button class="btn btn-primary reply-action-btn" type="button" data-toggle="modal"
                                                                data-target="#{{ $message->id }}"
                                                                data-whatever="@socialMedia"><i class="fa fa-edit"></i>
                                                            {{ trans('admin.reply') }}
                                                        </button>
{{--                                                    @endif--}}
{{--                                                    @if (auth()->user()->hasPermissionTo('حذف رساله اتصل بنا'))--}}
                                                        <form action="{{ route('delete-message', $message->id) }}"
                                                              method="post" style="display: inline-block">
                                                            {{ csrf_field() }}
                                                            {{ method_field('delete') }}
                                                            <button type="submit"
                                                                    class="btn btn-danger delete delete-action-btn"
                                                                    title="{{ trans('admin.delete') }}"><i
                                                                        class="fa fa-trash"></i>
                                                            </button>
                                                        </form>
{{--                                                    @endif--}}


                                                    <div class="modal fade" id="{{ $message->id }}" tabindex="-1"
                                                             role="dialog"
                                                             aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title"> @lang('admin.reply_message')
                                                                        ( {{ optional($message->users)->name }} )</h5>
                                                                    <button class="close" type="button"
                                                                            data-dismiss="modal"
                                                                            aria-label="Close">
                                                                        <span aria-hidden="true">×</span></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <form class="needs-validation" novalidate=""
                                                                          action="{{route('add-reply',$message->id)}}"
                                                                          method="POST" enctype="multipart/form-data">
                                                                        {{ method_field('POST') }}
                                                                        {{ csrf_field() }}

                                                                        <div class="form-group">
                                                                            <label for="validationCustom05"
                                                                                   class="col-form-label page-header-left">{{ trans('admin.reply') }}  </label>
                                                                            <textarea class="form-control"
                                                                                      name="message"
                                                                                      placeholder="{{ trans('admin.enter') }} {{ trans('admin.reply') }}"
                                                                                      required>{{ $message->reply }}</textarea>
                                                                            <div
                                                                                    class=" invalid-feedback">{{ $errors->first('value') }}</div>
                                                                        </div>

                                                                        <div class="modal-footer">
                                                                            <button class="btn btn-primary"
                                                                                    type="submit">
                                                                                @lang('admin.send')
                                                                            </button>
                                                                            <button class="btn btn-secondary"
                                                                                    type="button"
                                                                                    data-dismiss="modal"> {{ trans('admin.close') }}
                                                                            </button>
                                                                        </div>
                                                                    </form>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>


                                                </td>


                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            {{ $messages->links() }}
                        @else

                            <h4 class="text-center" style="color: #ff0000">{{ trans('admin.no_data') }} </h4>
                        @endif

                    </div>
                </div>
            </div>


        </div>
    </div>


@endsection
