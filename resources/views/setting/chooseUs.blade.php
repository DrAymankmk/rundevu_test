@extends('includes_admin.mainlayout')
@section('content')
    <!-- Right sidebar Ends-->
    <div class="page-body">
        <div class="container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col">
                        <div class="page-header-left">
                            <h3>اسباب اختيارك لنا</h3>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href=" {{route('admin.dashboard')}}"><i
                                                data-feather="home"> </i> {{ trans('admin.dashboard') }}  </a></li>
                                <li class="breadcrumb-item active"> اسباب اختيارك لنا</li>
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

                        @if( count($data['chooseUs']) > 0)
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="display dataTable" id="basic-1">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>{{ trans('admin.name_ar') }}</th>
                                            <th>{{ trans('admin.name_en') }}</th>
                                            <th>{{ trans('admin.action') }}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($data['chooseUs'] as $index=>$choose)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $choose->title_ar }}</td>
                                                <td>{{ $choose->title_en }}</td>
                                                <td>
                                                    @if (auth()->user()->hasPermissionTo('تعديل الاعدادات'))
                                                    <button class="btn btn-primary" type="button" data-toggle="modal"
                                                            data-target="#{{ $choose->id }}"
                                                            data-whatever="@test"><i class="fa fa-edit"></i>
                                                    </button>
                                                    @endif
                                                    <div class="modal fade" id="{{ $choose->id }}" tabindex="-1"
                                                         role="dialog"
                                                         aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title"> تعديل بيانات
                                                                        {{ $choose->title_ar }}
                                                                    </h5>
                                                                    <button class="close" type="button"
                                                                            data-dismiss="modal"
                                                                            aria-label="Close">
                                                                        <span aria-hidden="true">×</span></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <form class="needs-validation" novalidate=""
                                                                          action="{{route('edit-chooseUs',$choose->id)}}"
                                                                          method="POST" enctype="multipart/form-data">
                                                                        {{ method_field('POST') }}
                                                                        {{ csrf_field() }}
                                                                        <div class="form-group">
                                                                            <label for="validationCustom05"
                                                                                   class="col-form-label page-header-left">{{ trans('admin.name_ar') }}
                                                                            </label>
                                                                            <input class="form-control" name="title_ar"
                                                                                   type="text"
                                                                                   value="{{ $choose->title_ar }}"
                                                                                   placeholder="{{ trans('admin.enter') }} {{ trans('admin.name_ar') }}"
                                                                                   required="">
                                                                            <div
                                                                                    class="invalid-feedback">{{ $errors->first('name_ar') }}</div>
                                                                        </div>
                                                                        <span class="text-danger page-header-left"
                                                                              style="color: red;">{{$errors->first('name_ar')}}</span>

                                                                        <div class="form-group">
                                                                            <label for="validationCustom05"
                                                                                   class="col-form-label page-header-left">الوصف
                                                                                بالعربية
                                                                            </label>
                                                                            <textarea class="form-control"
                                                                                      name="message_ar"
                                                                                      required="">
                                                                                {{ $choose->message_ar }}
                                                                            </textarea>
                                                                            <div class="invalid-feedback">{{ $errors->first('message_ar') }}</div>
                                                                        </div>
                                                                        <span class="text-danger page-header-left"
                                                                              style="color: red;">{{$errors->first('message_ar')}}</span>

                                                                        <div class="form-group">

                                                                            <label for="validationCustom05"
                                                                                   class="col-form-label page-header-left">{{ trans('admin.name_en') }}
                                                                            </label>
                                                                            <input class="form-control" name="title_en"
                                                                                   type="text"
                                                                                   value="{{ $choose->title_en }}"
                                                                                   placeholder="{{ trans('admin.enter') }} {{ trans('admin.name_en') }}"
                                                                                   required="">
                                                                            <div
                                                                                    class="invalid-feedback">{{ $errors->first('title_en') }}</div>
                                                                        </div>
                                                                        <span class="text-danger page-header-left"
                                                                              style="color: red;">{{$errors->first('title_en')}}</span>

                                                                        <div class="form-group">
                                                                            <label for="validationCustom05"
                                                                                   class="col-form-label page-header-left">الوصف
                                                                                بالانجليزية
                                                                            </label>
                                                                            <textarea class="form-control"
                                                                                      name="message_en"
                                                                                      required="">
                                                                                {{ $choose->message_en }}
                                                                            </textarea>
                                                                            <div class="invalid-feedback">{{ $errors->first('message_en') }}</div>
                                                                        </div>
                                                                        <span class="text-danger page-header-left"
                                                                              style="color: red;">{{$errors->first('message_en')}}</span>


                                                                        <div class="modal-footer">
                                                                            <button class="btn btn-primary"
                                                                                    type="submit">
                                                                                {{ trans('admin.update') }}
                                                                            </button>
                                                                            <button class="btn btn-secondary"
                                                                                    type="button"
                                                                                    data-dismiss="modal">{{ trans('admin.close') }}
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
                        @else
                            <h4 class="text-center" style="color: #ff0000">  {{ trans('admin.no_data') }} </h4>
                        @endif

                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection
