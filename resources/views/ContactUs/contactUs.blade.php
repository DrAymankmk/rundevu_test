@extends('includes_admin.mainlayout')
@section('content')

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
                                <li class="breadcrumb-item active">@lang('admin.complaints_box') ({{ $messages->total() }})</li>
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
                                    <table class="display dataTable" id="basic-1">
                                        <thead>
                                        <tr>
                                            <th style="display: none">#</th>
                                            <th>{{ trans('admin.name') }}</th>
                                            <th>{{ trans('admin.accounts.phone') }}</th>
                                            <th>{{ trans('admin.accounts.email') }}</th>
                                            <th>{{ trans('admin.message') }}</th>
                                            <th> {{ trans('admin.created_at') }}</th>
                                            <th> {{ trans('admin.reply') }}</th>
                                            <th> {{ trans('admin.action') }}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($messages as $index=>$message)
                                            <tr>
                                                <td style="display: none">{{$index + 1}}</td>
                                                <td>{{ $message->users->name }}</td>
                                                <td>{{ $message->users->phone }}</td>
                                                <td>{{ $message->users->email }}</td>
                                                <td>{{ $message->complain }}</td>
                                                <td>{{ $message->created_at }}</td>
                                                @if(!empty($message->reply))
                                                    <td>{{ $message->reply }}</td>
                                                @else
                                                    <td style="color: #ff0000">{{ trans('admin.no_response') }}</td>
                                                @endif
                                                <td>
{{--                                                    @if (auth()->user()->hasPermissionTo('رد على اتصل بنا'))--}}
                                                        <button class="btn btn-primary" type="button" data-toggle="modal"
                                                                data-target="#{{ $message->id }}"
                                                                data-whatever="@socialMedia"><i class="fa fa-edit"></i>
                                                        </button>
{{--                                                    @endif--}}
{{--                                                    @if (auth()->user()->hasPermissionTo('حذف رساله اتصل بنا'))--}}
                                                        <form action="{{ route('delete-message', $message->id) }}"
                                                              method="post" style="display: inline-block">
                                                            {{ csrf_field() }}
                                                            {{ method_field('delete') }}
                                                            <button type="submit"
                                                                    class="btn btn-danger delete btn-sm"
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
                                                                        ( {{ $message->users->name }} )</h5>
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
