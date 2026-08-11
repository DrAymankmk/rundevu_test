@extends('includes_admin.mainlayout')
@section('content')
    <div class="page-body">

        <div class="container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col">
                        <div class="page-header-left">
                            <h3>@lang('admin.supervisor')</h3>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}"><i
                                                data-feather="home"> </i> @lang('admin.dashboard') </a></li>
                                <li class="breadcrumb-item active">@lang('admin.supervisor')</li>
                            </ol>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col">
                        @if (auth()->user()->app_type != 6 || auth()->user()->hasPermissionTo('supervisors_add'))
                            <div class="page-header-left">
                                <a class="btn btn-square btn-primary" href="{{route('create-supervisor')}}"
                                   title="@lang('admin.add')">
                                    @lang('admin.add_supervisor')</a> &nbsp; &nbsp;
                            </div>
                        @endif


                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        @if( count($all_supervisor) > 0)
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="display dataTable" id="basic-1">
                                        <thead>
                                        <tr>
                                            <th style="display: none">#</th>
                                            <th>@lang('admin.image')</th>
                                            <th>@lang('admin.name')</th>
                                            <th>@lang('admin.email')</th>
                                            <th>@lang('admin.phone')</th>
                                            <th>@lang('admin.status')</th>
                                            <th>@lang('admin.action')</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($all_supervisor as $index=>$account)
                                            <tr>
                                                <td style="display: none">{{ $index + 1 }}</td>
                                                <td><img class="img-80 rounded-circle" src="{{ $account->image }}"
                                                         alt="{{ $account->name }}" style="width:80px;height:80px">
                                                </td>
                                                <td>{{ $account->name }}</td>
                                                <td>{{ $account->email }}</td>
                                                <td>{{ $account->phone }}</td>

                                                <td>

                                                    @if (auth()->user()->app_type != 6 || auth()->user()->hasPermissionTo('supervisors_delete'))
                                                        <div class="media-body text-left icon-state">
                                                            <label class="switch">
                                                                <input type="checkbox"
                                                                       {{ $account->status == 1 ? 'checked' : '' }}
                                                                       onchange="change_status_account({{ $account->id }},{{ $account->status }})"><span
                                                                        class="switch-state bg-primary"></span>

                                                            </label>
                                                        </div>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if (auth()->user()->app_type != 6 || auth()->user()->hasPermissionTo('supervisors_edit'))

                                                    <form action="{{route('supervisor-update',$account->id)}}"
                                                          method="get" style="display: inline-block">
                                                        <button class="btn btn-info btn-sm" type="submit" data-whatever="@test" title="@lang('admin.edit_supervisor')"><i class="fa fa-edit"></i></button>

                                                    </form>
                                                    @endif
                                                        @if (auth()->user()->app_type != 6 || auth()->user()->hasPermissionTo('supervisors_delete'))
                                                        <form action="{{ route('destroyAccount', $account->id) }}"
                                                              method="post" style="display: inline-block">
                                                            {{ csrf_field() }}
                                                            {{ method_field('delete') }}
                                                            <button type="submit"
                                                                    class="btn btn-danger delete btn-sm"><i
                                                                        class="fa fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        @else
                            <h6 class="text-center"> @lang('admin.no_data')</h6>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>

        function change_status_account(id, value) {
            if (value == 0) {
                value = 1;
            } else {
                value = 0;
            }
            axios.get('UpdateStatusSuper/' + id + '/' + value)
                .then(function (response) {
                    location.reload();
                })
                .catch(function (error) {
                    console.log(error);
                    alert(error);
                });
        };


    </script>

@endsection
