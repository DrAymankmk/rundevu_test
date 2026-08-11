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
                                            data-feather="home"> </i> @lang('admin.dashboard') </a></li>
                                <li class="breadcrumb-item active">@lang('admin.Manage Branch')
                                    ( {{ $data['branches']->total() }} )
                                </li>
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
                        <div class="card-body btn-showcase">
                            <!-- Simple demo-->
                            @if (auth()->user()->app_type != 6 || auth()->user()->hasPermissionTo('branches_add'))
                            <button class="btn btn-primary" type="button" data-toggle="modal"
                                    data-target="#city" data-whatever="@test">@lang('admin.add_branch')
                            </button>

                                                        @endif

                            <div class="modal fade" id="city" tabindex="-1" role="dialog"
                                 aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">@lang('admin.add_branch')</h5>
                                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">×</span></button>
                                        </div>
                                        <div class="modal-body">
                                            <form class="needs-validation" novalidate=""
                                                  action="{{route('add-branch')}}"
                                                  method="POST" enctype="multipart/form-data">
                                                {{ method_field('POST') }}
                                                {{ csrf_field() }}

                                                <div class="form-group">
                                                    <label for="validationCustom05"
                                                           class="page-header-left">@lang('admin.image')</label>
                                                    <div class="custom-file">
                                                        <input class="custom-file-input" type="file"
                                                               name="image" required>
                                                        <label class="custom-file-label"
                                                               for="validatedCustomFile">@lang('admin.image')</label>
                                                        <div
                                                            class="invalid-feedback">{{ $errors->first('image') }}</div>
                                                    </div>
                                                </div>
                                                <span class="text-danger page-header-left"
                                                      style="color: red;">{{$errors->first('image')}}</span>

                                                <div class="form-group">
                                                    <label for="name"
                                                           class="col-form-label page-header-left">@lang('admin.name')</label>
                                                    <input type="text" class="form-control" name="name"
                                                           placeholder="@lang('admin.name')"
                                                           id="name"
                                                           required="">
                                                    <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                                                </div>
                                                <span class="text-danger page-header-left"
                                                      style="color: red;">{{$errors->first('name')}}</span>


                                                <div class="form-group">
                                                    <label for="email"
                                                           class="col-form-label page-header-left">@lang('admin.email')</label>
                                                    <input type="email" class="form-control" name="email"
                                                           placeholder="@lang('admin.email')"
                                                           id="email"
                                                           required="">
                                                    <div class="invalid-feedback">{{ $errors->first('email') }}</div>
                                                </div>
                                                <span class="text-danger page-header-left"
                                                      style="color: red;">{{$errors->first('email')}}</span>


                                                <div class="form-group">
                                                    <label for="phone"
                                                           class="col-form-label page-header-left">@lang('admin.phone')</label>
                                                    <input type="number" class="form-control" name="phone"
                                                           placeholder="@lang('admin.phone')"
                                                           id="phone"
                                                           required="">
                                                    <div class="invalid-feedback">{{ $errors->first('phone') }}</div>
                                                </div>
                                                <span class="text-danger page-header-left"
                                                      style="color: red;">{{$errors->first('phone')}}</span>


                                                <div class="form-group">
                                                    <label for="password"
                                                           class="col-form-label page-header-left">@lang('admin.password')</label>
                                                    <input type="password" class="form-control" name="password"
                                                           placeholder="@lang('admin.password')"
                                                           id="password"
                                                           required="">
                                                    <div class="invalid-feedback">{{ $errors->first('password') }}</div>
                                                </div>
                                                <span class="text-danger page-header-left"
                                                      style="color: red;">{{$errors->first('password')}}</span>

                                                <div class="modal-footer">
                                                    <button class="btn btn-primary"
                                                            type="submit">@lang('admin.add_branch')</button>
                                                    <button class="btn btn-secondary" type="button"
                                                            data-dismiss="modal">@lang('admin.close')
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if( count($data['branches']) > 0)
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
                                        @foreach($data['branches'] as $index=>$branch)
                                            <tr>
                                                <td style="display: none">{{ $index + 1 }}</td>
                                                <td><img class="img-80 rounded-circle" src="{{ $branch->image }}"
                                                         alt="{{ $branch->name }}" style="width:80px;height:80px">
                                                </td>
                                                <td>{{ $branch->name }}</td>
                                                <td>{{ $branch->email }}</td>
                                                <td>{{ $branch->phone }}</td>
                                                <td>
                                                    @if (auth()->user()->app_type != 6 || auth()->user()->hasPermissionTo('branches_status'))

                                                    <div class="media-body text-left icon-state">
                                                        <label class="switch">
                                                            <input type="checkbox"
                                                                   {{ $branch->status == 1 ? 'checked' : '' }}
                                                                   onchange="change_status_branch({{ $branch->id }},{{ $branch->status }})"><span
                                                                class="switch-state bg-primary"></span>

                                                        </label>
                                                    </div>
                                                    @endif

                                                </td>
                                                <td>
                                                    @if (auth()->user()->app_type != 6 || auth()->user()->hasPermissionTo('branches_edit'))

                                                    <button class="btn btn-primary" type="button"
                                                            data-toggle="modal"
                                                            data-target="#{{ $branch->id }}"
                                                            data-whatever="@test"><i class="fa fa-edit" title="@lang('admin.edit')"></i>
                                                    </button>
@endif
                                                        @if (auth()->user()->app_type != 6 || auth()->user()->hasPermissionTo('branches_status'))

                                                        <form action="{{ route('destroy-branch', $branch->id) }}"
                                                          method="post" style="display: inline-block">
                                                        {{ csrf_field() }}
                                                        {{ method_field('delete') }}
                                                        <button type="submit" class="btn btn-danger delete btn-sm">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </form>
                                                        @endif
                                                    <div class="modal fade" id="{{ $branch->id }}" tabindex="-1"
                                                         role="dialog"
                                                         aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title"> @lang('admin.edit_data')
                                                                        {{ $branch->name }} </h5>
                                                                    <button class="close" type="button"
                                                                            data-dismiss="modal"
                                                                            aria-label="Close">
                                                                        <span aria-hidden="true">×</span></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <form class="needs-validation" novalidate=""
                                                                          action="{{route('edit-branch',$branch->id)}}"
                                                                          method="POST"
                                                                          enctype="multipart/form-data">
                                                                        {{ method_field('POST') }}
                                                                        {{ csrf_field() }}

                                                                        <div class="form-group">
                                                                            <label for="validationCustom05"
                                                                                   class="page-header-left">@lang('admin.edit_image')</label>
                                                                            <label for="file-input"
                                                                                   class="image-upload-label">
                                                                                <img alt="{{ $branch->name }}"
                                                                                     src="{{$branch->image}}"
                                                                                     class="thumb"
                                                                                     style="width: 100px"/>
                                                                            </label>
                                                                            <div class="custom-file">
                                                                                <input
                                                                                    class="custom-file-input image"
                                                                                    type="file"
                                                                                    name="image">
                                                                                <label class="custom-file-label"
                                                                                       for="image">@lang('admin.select_image')</label>
                                                                                <div
                                                                                    class="invalid-feedback">{{ $errors->first('image') }}</div>
                                                                            </div>
                                                                        </div>
                                                                        <span class="text-danger page-header-left"
                                                                              style="color: red;">{{$errors->first('image')}}</span>

                                                                        <div class="form-group">
                                                                            <label for="name"
                                                                                   class="col-form-label page-header-left">@lang('admin.name')</label>
                                                                            <input type="text" class="form-control"
                                                                                   name="name"
                                                                                   placeholder="@lang('admin.name')"
                                                                                   value="{{$branch->name}}"
                                                                                   id="name"
                                                                                   required="">
                                                                            <div
                                                                                class="invalid-feedback">{{ $errors->first('name') }}</div>
                                                                        </div>
                                                                        <span class="text-danger page-header-left"
                                                                              style="color: red;">{{$errors->first('name')}}</span>


                                                                        <div class="form-group">
                                                                            <label for="email"
                                                                                   class="col-form-label page-header-left">@lang('admin.email')</label>
                                                                            <input type="email" class="form-control"
                                                                                   name="email"
                                                                                   placeholder="@lang('admin.email')"
                                                                                   id="email"
                                                                                   value="{{$branch->email}}"
                                                                                   required="">
                                                                            <div
                                                                                class="invalid-feedback">{{ $errors->first('email') }}</div>
                                                                        </div>
                                                                        <span class="text-danger page-header-left"
                                                                              style="color: red;">{{$errors->first('email')}}</span>


                                                                        <div class="form-group">
                                                                            <label for="phone"
                                                                                   class="col-form-label page-header-left">@lang('admin.phone')</label>
                                                                            <input type="number" class="form-control"
                                                                                   name="phone"
                                                                                   placeholder="@lang('admin.phone')"
                                                                                   id="phone"
                                                                                   value="{{$branch->phone}}"
                                                                                   required="">
                                                                            <div
                                                                                class="invalid-feedback">{{ $errors->first('phone') }}</div>
                                                                        </div>
                                                                        <span class="text-danger page-header-left"
                                                                              style="color: red;">{{$errors->first('phone')}}</span>


                                                                        <div class="form-group">
                                                                            <label for="password"
                                                                                   class="col-form-label page-header-left">@lang('admin.password')</label>
                                                                            <input type="password" class="form-control"
                                                                                   name="password"
                                                                                   placeholder="@lang('admin.password')"
                                                                                   id="password">
                                                                            <div
                                                                                class="invalid-feedback">{{ $errors->first('password') }}</div>
                                                                        </div>
                                                                        <span class="text-danger page-header-left"
                                                                              style="color: red;">{{$errors->first('password')}}</span>

                                                                        <div class="modal-footer">
                                                                            <button class="btn btn-primary"
                                                                                    type="submit">
                                                                                @lang('admin.edit')
                                                                            </button>
                                                                            <button class="btn btn-secondary"
                                                                                    type="button"
                                                                                    data-dismiss="modal">@lang('admin.close')
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
                            {{ $data['branches']->links() }}
                        @else
                            <h4 class="text-center" style="color: #ff0000"> @lang('admin.no_data') </h4>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function change_status_branch(id, value) {
            if (value == 0) {
                value = 1;
            } else {
                value = 0;
            }
            axios.get('update-status-branch/' + id + '/' + value)
                .then(function (response) {
                    location.reload();
                })
                .catch(function (error) {
                    alert(error);
                });
        }
    </script>

@endsection
