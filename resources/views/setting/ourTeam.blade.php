@extends('includes_admin.mainlayout')
@section('content')

    <!-- Right sidebar Ends-->
    <div class="page-body">
        <div class="container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col">
                        <div class="page-header-left">
                            <h3>{{  $data['title'] }}</h3>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}"><i
                                                data-feather="home"> </i> الرئيسية </a></li>
                                <li class="breadcrumb-item active">  {{ $data['title'] }}</li>
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
                            @if (auth()->user()->hasPermissionTo('اضافه الاعدادات'))
                            <button class="btn btn-primary" type="button" data-toggle="modal"
                                    data-target="#city" data-whatever="@test">اضافه {{ $data['title'] }}
                            </button>
                            @endif

                            <div class="modal fade" id="city" tabindex="-1" role="dialog"
                                 aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">اضافه {{ $data['title'] }}</h5>
                                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">×</span></button>
                                        </div>
                                        <div class="modal-body">
                                            <form class="needs-validation" novalidate=""
                                                  action="{{route('add-team',$data['type'])}}"
                                                  method="POST" enctype="multipart/form-data">
                                                {{ method_field('POST') }}
                                                {{ csrf_field() }}

                                                <div class="form-group">
                                                    <label for="validationCustom05"
                                                           class="col-form-label page-header-left">الاسم</label>
                                                    <input class="form-control" name="name"
                                                           type="text" value="{{ old('name') }}"
                                                           placeholder="ادخل الاسم"
                                                           required="">
                                                    <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                                                </div>
                                                <span class="text-danger page-header-left"
                                                      style="color: red;">{{$errors->first('name')}}</span>

                                                <div class="form-group">
                                                    <label for="validationCustom05"
                                                           class="col-form-label page-header-left">عنوان الوظيفة
                                                        بالعربية</label>
                                                    <input class="form-control" name="job_title_ar"
                                                           type="text" value="{{ old('job_title_ar') }}"
                                                           placeholder="ادخل عنوان الوظيفة بالعربية"
                                                           required="">
                                                    <div class="invalid-feedback">{{ $errors->first('job_title_ar') }}</div>
                                                </div>
                                                <span class="text-danger page-header-left"
                                                      style="color: red;">{{$errors->first('job_title_ar')}}</span>

                                                <div class="form-group">
                                                    <label for="validationCustom05"
                                                           class="col-form-label page-header-left">عنوان الوظيفة
                                                        بالانجليزية</label>
                                                    <input class="form-control" name="job_title_en"
                                                           type="text" value="{{ old('job_title_en') }}"
                                                           placeholder="ادخل عنوان الوظيفة بالانجليزية"
                                                           required="">
                                                    <div class="invalid-feedback">{{ $errors->first('job_title_en') }}</div>
                                                </div>
                                                <span class="text-danger page-header-left"
                                                      style="color: red;">{{$errors->first('job_title_en')}}</span>

                                                @if($data['type'] == 2)
                                                    <div class="form-group">
                                                        <label for="validationCustom05"
                                                               class="col-form-label page-header-left"> الوصف
                                                            بالعربية</label>
                                                        <textarea class="form-control" name="description_ar"
                                                                  placeholder="ادخل الوصف بالعربية"
                                                                  required=""></textarea>
                                                        <div class="invalid-feedback">{{ $errors->first('description_ar') }}</div>
                                                    </div>
                                                    <span class="text-danger page-header-left"
                                                          style="color: red;">{{$errors->first('description_ar')}}</span>

                                                    <div class="form-group">
                                                        <label for="validationCustom05"
                                                               class="col-form-label page-header-left">الوصف
                                                            بالانجليزية</label>
                                                        <textarea class="form-control" name="description_en"
                                                                  placeholder="ادخل الوصف بالانجليزية"
                                                                  required=""></textarea>
                                                        <div class="invalid-feedback">{{ $errors->first('description_en') }}</div>
                                                    </div>
                                                    <span class="text-danger page-header-left"
                                                          style="color: red;">{{$errors->first('description_en')}}</span>
                                                @endif

                                                <div class="form-group">
                                                    <label for="validationCustom05"
                                                           class="page-header-left">{{ trans('admin.image') }}</label>
                                                    <div class="custom-file">
                                                        <input class="custom-file-input" type="file" name="image"
                                                               placeholder="{{ trans('admin.select') }} {{ trans('admin.image') }}"
                                                               required>
                                                        <label class="custom-file-label"
                                                               for="validatedCustomFile">{{ trans('admin.select') }} {{ trans('admin.image') }}</label>
                                                        <div
                                                                class="invalid-feedback">{{ $errors->first('image') }}</div>
                                                    </div>
                                                </div>

                                                <div class="modal-footer">
                                                    <button class="btn btn-primary" type="submit">اضافه</button>
                                                    <button class="btn btn-secondary" type="button"
                                                            data-dismiss="modal">اغلاق
                                                    </button>
                                                </div>
                                            </form>
                                        </div>


                                    </div>
                                </div>
                            </div>
                        </div>


                        @if( count($data['our_team']) > 0)
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="display dataTable" id="basic-1">
                                        <thead>
                                        <tr>
                                            <th>رقم المسلسل</th>
                                            <th>الصورة</th>
                                            <th>الاسم</th>
                                            <th>الوظيفة بالعربيه</th>
                                            <th>الوظيفة بالانجليزية</th>
                                            <th>الحالة</th>
                                            <th>الاجراء</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($data['our_team'] as $index=>$team)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td><img
                                                            class="img-100 rounded-circle"
                                                            src="{{ $team->image }}"
                                                            alt="#"
                                                            data-original-title=""
                                                            title=""></td>
                                                <td>{{ $team->name }}</td>
                                                <td>{{ $team->job_title_ar }}</td>
                                                <td>{{ $team->job_title_en }}</td>
                                                <td>
                                                    @if (auth()->user()->hasPermissionTo('تعديل الاعدادات'))
                                                    <div class="media-body text-left icon-state">
                                                        <label class="switch">
                                                            <input type="checkbox"
                                                                   {{ $team->status == 1 ? 'checked' : '' }}
                                                                   onchange="change_status_team({{ $team->id }},{{ $team->status }}, {{ $data['type'] }})"><span
                                                                    class="switch-state bg-primary"></span>
                                                        </label>
                                                    </div>
                                                        @endif
                                                </td>
                                                <td>
                                                    @if($data['type'] == 1)
                                                        <a class="btn btn-primary btn-group-sm"
                                                           href="{{ route('TeamSocialMedia',$team->id) }}"
                                                           data-whatever="@test" title="اضافه وعرض وسائل التواصل"><i
                                                                    class="fa fa-eye"></i></a>
                                                    @endif

                                                        @if (auth()->user()->hasPermissionTo('تعديل الاعدادات'))
                                                    <button class="btn btn-primary" type="button" data-toggle="modal"
                                                            data-target="#{{ $team->id }}"
                                                            data-whatever="@test"><i class="fa fa-edit"></i>
                                                    </button>
                                                        @endif
                                                            @if (auth()->user()->hasPermissionTo('حذف الاعدادات'))
                                                    <form action="{{ route('destroy-team', [$team->id, $data['type']] ) }}"
                                                          method="post" style="display: inline-block">
                                                        {{ csrf_field() }}
                                                        {{ method_field('delete') }}
                                                        <button type="submit" class="btn btn-danger delete btn-sm"><i
                                                                    class="fa fa-trash"></i>
                                                        </button>
                                                    </form>
                                                            @endif
                                                    <div class="modal fade" id="{{ $team->id }}" tabindex="-1"
                                                         role="dialog"
                                                         aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title"> تعديل بيانات
                                                                        {{ $team->name }} </h5>

                                                                    <button class="close" type="button"
                                                                            data-dismiss="modal"
                                                                            aria-label="Close">
                                                                        <span aria-hidden="true">×</span></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <form class="needs-validation" novalidate=""
                                                                          action="{{route('edit-team',[$team->id, $data['type']])}}"
                                                                          method="POST" enctype="multipart/form-data">
                                                                        {{ method_field('POST') }}
                                                                        {{ csrf_field() }}
                                                                        <div class="form-group">

                                                                            <label for="validationCustom05"
                                                                                   class="col-form-label page-header-left">الاسم
                                                                            </label>
                                                                            <input class="form-control" name="name"
                                                                                   type="text"
                                                                                   value="{{ $team->name }}"
                                                                                   placeholder="ادخل الاسم"
                                                                                   required="">
                                                                            <div
                                                                                    class="invalid-feedback">{{ $errors->first('name') }}</div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label for="validationCustom05"
                                                                                   class="col-form-label page-header-left">عنوان
                                                                                الوظيفة
                                                                                بالعربيه
                                                                            </label>
                                                                            <input class="form-control"
                                                                                   name="job_title_ar"
                                                                                   type="text"
                                                                                   value="{{ $team->job_title_ar }}"
                                                                                   placeholder="ادخل العنوان بالعربية"
                                                                                   required="">
                                                                            <div class="invalid-feedback">{{ $errors->first('job_title_ar') }}</div>
                                                                        </div>
                                                                        <span class="text-danger page-header-left"
                                                                              style="color: red;">{{$errors->first('job_title_ar')}}</span>

                                                                        <div class="form-group">

                                                                            <label for="validationCustom05"
                                                                                   class="col-form-label page-header-left">عنوان
                                                                                الوظيفة
                                                                                بالانجليزية
                                                                            </label>
                                                                            <input class="form-control"
                                                                                   name="job_title_en"
                                                                                   type="text"
                                                                                   value="{{ $team->job_title_en }}"
                                                                                   placeholder="ادخل الاسم بالانجليزية"
                                                                                   required="">
                                                                            <div class="invalid-feedback">{{ $errors->first('job_title_en') }}</div>
                                                                        </div>
                                                                        <span class="text-danger page-header-left"
                                                                              style="color: red;">{{$errors->first('job_title_en')}}</span>

                                                                        <div class="form-group">
                                                                            <label for="validationCustom05"
                                                                                   class="page-header-left">صوره</label>
                                                                            <label for="file-input"
                                                                                   class="image-upload-label">
                                                                                <img alt="upload-slider-image"
                                                                                     src="{{$team->image}}"
                                                                                     class="img-100 rounded-circle">
                                                                            </label>
                                                                            <div class="custom-file">
                                                                                <input class="custom-file-input"
                                                                                       type="file"
                                                                                       name="image">
                                                                                <label class="custom-file-label"
                                                                                       for="validatedCustomFile">اختر
                                                                                    صوره</label>
                                                                                <div
                                                                                        class="invalid-feedback">{{ $errors->first('images') }}</div>
                                                                            </div>
                                                                        </div>

                                                                        @if($data['type'] == 2)
                                                                            <div class="form-group">
                                                                                <label for="validationCustom05"
                                                                                       class="col-form-label page-header-left">
                                                                                    الوصف
                                                                                    بالعربية</label>
                                                                                <textarea class="form-control"
                                                                                          name="description_ar"
                                                                                          placeholder="ادخل الوصف بالعربية"
                                                                                          required="">{{ $team->description_ar  }}</textarea>
                                                                                <div class="invalid-feedback">{{ $errors->first('description_ar') }}</div>
                                                                            </div>
                                                                            <span class="text-danger page-header-left"
                                                                                  style="color: red;">{{$errors->first('description_ar')}}</span>

                                                                            <div class="form-group">
                                                                                <label for="validationCustom05"
                                                                                       class="col-form-label page-header-left">الوصف
                                                                                    بالانجليزية</label>
                                                                                <textarea class="form-control"
                                                                                          name="description_en"
                                                                                          placeholder="ادخل الوصف بالانجليزية"
                                                                                          required="">{{ $team->description_en  }}</textarea>
                                                                                <div class="invalid-feedback">{{ $errors->first('description_en') }}</div>
                                                                            </div>
                                                                            <span class="text-danger page-header-left"
                                                                                  style="color: red;">{{$errors->first('description_en')}}</span>
                                                                        @endif

                                                                        <div class="modal-footer">
                                                                            <button class="btn btn-primary"
                                                                                    type="submit">
                                                                                تعديل
                                                                            </button>
                                                                            <button class="btn btn-secondary"
                                                                                    type="button"
                                                                                    data-dismiss="modal">اغلاق
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
                            <h4 class="text-center" style="color: #ff0000"> لا توجد بيانات مضافه </h4>
                        @endif


                    </div>
                </div>

            </div>
        </div>
    </div>


    <script>

        function change_status_team(id, value, type) {
            if (value == 0) {
                value = 1;
            } else {
                value = 0;
            }
            axios.get("{{\Illuminate\Support\Facades\URL::to('admin/update-status-team/')}}/"+ id + '/' + value + '/' + type)

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
