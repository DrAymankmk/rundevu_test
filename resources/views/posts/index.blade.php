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
                                <li class="breadcrumb-item active">@lang('admin.posts')
                                    ( {{ $data['posts']->total() }} )
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
                            @if (auth()->user()->app_type != 6 || auth()->user()->hasPermissionTo('post_add'))
                            <button class="btn btn-primary" type="button" data-toggle="modal"
                                    data-target="#city" data-whatever="@test">@lang('admin.add_post')
                            </button>
                            @if(app()->getLocale() =='ar')
                                <h6 style="color: #ff0000;text-align: center;font-weight: bold">تنبية: يمكنك
                                    اضافة {{ $data['check_doctor_count']->post_count ?? 0 }} اعلانات فقط خلال الاسبوع </h6>
                            @else
                                <h6 style="color: #ff0000;text-align: center;font-weight: bold"> Note : You only
                                    have {{ $data['check_doctor_count']->post_count ?? 0 }} posts you can publish per
                                    week </h6>
                            @endif

                                                        @endif

                            <div class="modal fade" id="city" tabindex="-1" role="dialog"
                                 aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">@lang('admin.add_post')</h5>
                                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">×</span></button>
                                        </div>
                                        <div class="modal-body">
                                            <form class="needs-validation" novalidate=""
                                                  action="{{route('add-post')}}"
                                                  method="POST" enctype="multipart/form-data">
                                                {{ method_field('POST') }}
                                                {{ csrf_field() }}
                                                <input type="hidden" name="post_count"
                                                       value="{{$data['check_doctor_count']->post_count ?? 0}}"/>

                                                <div class="form-group">
                                                    <label for="validationCustom05"
                                                           class="page-header-left">@lang('admin.add_post_image')</label>
                                                    <div class="custom-file">
                                                        <input class="custom-file-input" type="file"
                                                               name="image" required>
                                                        <label class="custom-file-label"
                                                               for="validatedCustomFile">@lang('admin.add_post_image')</label>
                                                        <div
                                                            class="invalid-feedback">{{ $errors->first('image') }}</div>
                                                    </div>
                                                </div>
                                                <span class="text-danger page-header-left"
                                                      style="color: red;">{{$errors->first('image')}}</span>

                                                <div class="form-group">
                                                    <label for="validationCustom05"
                                                           class="col-form-label page-header-left">@lang('admin.add_content')</label>
                                                    <textarea class="form-control" name="content"
                                                              placeholder="@lang('admin.write_here')"
                                                              required=""></textarea>
                                                    <div class="invalid-feedback">{{ $errors->first('content') }}</div>
                                                </div>
                                                <span class="text-danger page-header-left"
                                                      style="color: red;">{{$errors->first('content')}}</span>

                                                <div class="modal-footer">
                                                    <button class="btn btn-primary"
                                                            type="submit">@lang('admin.add_button_post')</button>
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

                        @if( count($data['posts']) > 0)
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="display dataTable" id="basic-1">
                                        <thead>
                                        <tr>
                                            <th style="display: none">#</th>
                                            <th>@lang('admin.image')</th>
                                            <th>@lang('admin.content')</th>
                                            <th>@lang('admin.date_created')</th>
                                            <th>@lang('admin.status')</th>
                                            <th>@lang('admin.action')</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($data['posts'] as $index=>$post)
                                            <tr>
                                                <td style="display: none">{{ $index + 1 }}</td>
                                                <td><img class="img-80 rounded-circle" src="{{ $post->image }}"
                                                         alt="{{ $post->content }}" style="width:80px;height:80px">
                                                </td>
                                                <td>{{ $post->content }}</td>
                                                <td>{{ $post->created_at }}</td>
                                                @if($post->created_at >= \Illuminate\Support\Carbon::now()->subdays(7))
                                                    <td style="color: green;font-weight: bold">@lang('admin.post_active')</td>
                                                @else
                                                    <td style="color: #ff0000;font-weight: bold">@lang('admin.post_expired')</td>

                                                @endif
                                                {{--                                                <td>--}}
                                                {{--                                                    --}}{{--                                                    @if (auth()->user()->hasPermissionTo('تعديل قسم فرعى'))--}}
                                                {{--                                                    <div class="media-body text-left icon-state">--}}
                                                {{--                                                        <label class="switch">--}}
                                                {{--                                                            <input type="checkbox"--}}
                                                {{--                                                                   {{ $post->status == 1 ? 'checked' : '' }}--}}
                                                {{--                                                                   onchange="change_status_post({{ $post->id }},{{ $post->status }})"><span--}}
                                                {{--                                                                class="switch-state bg-primary"></span>--}}

                                                {{--                                                        </label>--}}
                                                {{--                                                    </div>--}}

                                                {{--                                                    --}}{{--                                                    @endif--}}
                                                {{--                                                </td>--}}
                                                <td>
                                                    @if (auth()->user()->app_type != 6 || auth()->user()->hasPermissionTo('post_edit'))
                                                    @if($post->created_at >= \Illuminate\Support\Carbon::now()->subdays(7))

                                                    <button class="btn btn-primary" type="button"
                                                            data-toggle="modal"
                                                            data-target="#{{ $post->id }}"
                                                            data-whatever="@test"><i class="fa fa-edit"
                                                                                     title="@lang('admin.edit')"></i>
                                                    </button>
                                                    @endif
                                                    @endif
                                                        @if (auth()->user()->app_type != 6 || auth()->user()->hasPermissionTo('post_delete'))

                                                    <form action="{{ route('destroy-post', $post->id) }}"
                                                          method="post" style="display: inline-block">
                                                        {{ csrf_field() }}
                                                        {{ method_field('delete') }}
                                                        <button type="submit" class="btn btn-danger delete btn-sm">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </form>
                                                        @endif
                                                    <div class="modal fade" id="{{ $post->id }}" tabindex="-1"
                                                         role="dialog"
                                                         aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title"> @lang('admin.edit_data')
                                                                        {{ $post->content }} </h5>
                                                                    <button class="close" type="button"
                                                                            data-dismiss="modal"
                                                                            aria-label="Close">
                                                                        <span aria-hidden="true">×</span></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <form class="needs-validation" novalidate=""
                                                                          action="{{route('edit-post',$post->id)}}"
                                                                          method="POST"
                                                                          enctype="multipart/form-data">
                                                                        {{ method_field('POST') }}
                                                                        {{ csrf_field() }}

                                                                        <div class="form-group">
                                                                            <label for="validationCustom05"
                                                                                   class="page-header-left">@lang('admin.edit_image')</label>
                                                                            <label for="file-input"
                                                                                   class="image-upload-label">
                                                                                <img alt="{{ $post->content }}"
                                                                                     src="{{$post->image}}"
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
                                                                            <label for="validationCustom05"
                                                                                   class="col-form-label page-header-left">@lang('admin.add_content')</label>
                                                                            <textarea class="form-control"
                                                                                      name="content"
                                                                                      placeholder="@lang('admin.write_here')"
                                                                                      required="">{{ $post->content }}</textarea>
                                                                            <div
                                                                                class="invalid-feedback">{{ $errors->first('content') }}</div>
                                                                        </div>
                                                                        <span class="text-danger page-header-left"
                                                                              style="color: red;">{{$errors->first('content')}}</span>

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
                            {{ $data['posts']->links() }}
                        @else
                            <h4 class="text-center" style="color: #ff0000"> @lang('admin.no_data') </h4>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function change_status_post(id, value) {
            if (value == 0) {
                value = 1;
            } else {
                value = 0;
            }
            axios.get('update-status-post/' + id + '/' + value)
                .then(function (response) {
                    location.reload();
                })
                .catch(function (error) {
                    alert(error);
                });
        }
    </script>

@endsection
