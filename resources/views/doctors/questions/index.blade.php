@extends('includes_admin.mainlayout')
@section('content')

    <div class="page-wrapper">
        <div class="content">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a
                                    href="{{ route('admin.dashboard') }}">@lang('admin.dashboard') </a></li>
                            <li class="breadcrumb-item"><i class="feather-chevron-right"></i></li>
                            <li class="breadcrumb-item active">@lang('admin.doctor.questions received')</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">

                    <div class="card card-table show-entire">
                        <div class="card-body">

                            <!-- Table Header -->
                            <div class="page-table-header mb-2">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <div class="doctor-table-blk">
                                            <h3>@lang('admin.doctor.questions received')</h3>
                                            <div class="doctor-search-blk">
                                                <div class="top-nav-search table-search-blk">
                                                    <form>
                                                        <input type="text" class="form-control"
                                                               placeholder="@lang('admin.search_here')">
                                                        <a class="btn"><img src="/assets/img/icons/search-normal.svg"
                                                                            alt=""></a>
                                                    </form>
                                                </div>
                                                <div class="add-group">
                                                    <a href="javascript:;"
                                                       class="btn btn-primary doctor-refresh ms-2"><img
                                                            src="/assets/img/icons/re-fresh.svg" alt=""></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-auto text-end float-end ms-auto download-grp">
                                        <a href="javascript:;" class=" me-2"><img
                                                src="/assets/img/icons/pdf-icon-01.svg" alt=""></a>
                                        <a href="javascript:;" class=" me-2"><img
                                                src="/assets/img/icons/pdf-icon-02.svg" alt=""></a>
                                        <a href="javascript:;" class=" me-2"><img
                                                src="/assets/img/icons/pdf-icon-03.svg" alt=""></a>
                                        <a href="javascript:;"><img src="/assets/img/icons/pdf-icon-04.svg" alt=""></a>

                                    </div>
                                </div>
                            </div>
                            <!-- /Table Header -->

                            <div class="table-responsive">
                                <table class="table border-0 custom-table comman-table datatable mb-0">
                                    <thead>
                                    <tr>
                                        <th>@lang('admin.name')</th>
                                        <th> @lang('admin.phone') </th>
                                        <th> @lang('admin.email') </th>
                                        <th> @lang('admin.question') </th>
                                        <th> @lang('admin.created_at') </th>
                                        <th>@lang('admin.reply') </th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($messages as $message)
                                        <tr>
                                            <td class="profile-image"><a href="{{ route('reply', $message->id) }}"><img width="28" height="28"
                                                                                                  src="{{$message->users->image ?? null}}"
                                                                                                  class="rounded-circle m-r-5"
                                                                                                  alt="">{{$message->users->name ?? null}}
                                                </a></td>
                                            <td><a href="tel:{{$message->users->phone}}">{{$message->users->phone}}</a>
                                            </td>
                                            <td>
                                                <a href="mailto:{{$message->users->email}}">{{$message->users->email}}</a>
                                            </td>
                                            <td>{{$message->complain}}</td>
                                            <td>{{ $message->created_at->format('F jS') }}</td>
                                            <td>{{$message->reply ?? null}}</td>

                                            <td class="text-end">

                                                <div class="dropdown dropdown-action">
                                                    <a href="#" class="action-icon dropdown-toggle"
                                                       data-bs-toggle="dropdown" aria-expanded="false"><i
                                                            class="fa fa-ellipsis-v"></i></a>
                                                    <div class="dropdown-menu dropdown-menu-end">

                                                        <a class="dropdown-item" href="{{ route('reply', $message->id) }}"><i
                                                                class="feather-eye m-r-5"></i> @lang('admin.reply')
                                                        </a>
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
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
