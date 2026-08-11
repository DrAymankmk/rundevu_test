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
                                                  action="{{route('add-terms')}}"
                                                  method="POST" enctype="multipart/form-data">
                                                {{ method_field('POST') }}
                                                {{ csrf_field() }}

                                                <div class="form-group">
                                                    <label for="validationCustom05"
                                                           class="col-form-label page-header-left">العنوان
                                                        بالعربية</label>
                                                    <input class="form-control" name="title_ar"
                                                           type="text" value="{{ old('title_ar') }}"
                                                           placeholder="ادخل عنوان بالعربية"
                                                           required="">
                                                    <div class="invalid-feedback">{{ $errors->first('title_ar') }}</div>
                                                </div>
                                                <span class="text-danger page-header-left"
                                                      style="color: red;">{{$errors->first('title_ar')}}</span>

                                                <div class="form-group">
                                                    <label for="validationCustom05"
                                                           class="col-form-label page-header-left">العنوان
                                                        بالانجليزية</label>
                                                    <input class="form-control" name="title_en"
                                                           type="text" value="{{ old('title_en') }}"
                                                           placeholder="ادخل عنوان بالانجليزية"
                                                           required="">
                                                    <div class="invalid-feedback">{{ $errors->first('title_en') }}</div>
                                                </div>
                                                <span class="text-danger page-header-left"
                                                      style="color: red;">{{$errors->first('title_en')}}</span>


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


                        @if( count($data['terms']) > 0)
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="display dataTable" id="basic-1">
                                        <thead>
                                        <tr>
                                            <th>رقم المسلسل</th>
                                            <th>العنوان بالعربيه</th>
                                            <th>العنوان بالانجليزية</th>
                                            <th>الوصف بالعربيه</th>
                                            <th>الوصف بالانجليزية</th>
                                            <th>الحالة</th>
                                            <th>الاجراء</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($data['terms'] as $index=>$team)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $team->title_ar }}</td>
                                                <td>{{ $team->title_en }}</td>
                                                <td>{{ $team->description_ar }}</td>
                                                <td>{{ $team->description_en }}</td>
                                                <td>
                                                    @if (auth()->user()->hasPermissionTo('تعديل الاعدادات'))
                                                        <div class="media-body text-left icon-state">
                                                            <label class="switch">
                                                                <input type="checkbox"
                                                                       {{ $team->status == 1 ? 'checked' : '' }}
                                                                       onchange="change_status_terms({{ $team->id }},{{ $team->status }})"><span
                                                                        class="switch-state bg-primary"></span>
                                                            </label>
                                                        </div>
                                                    @endif
                                                </td>
                                                <td>

                                                    @if (auth()->user()->hasPermissionTo('تعديل الاعدادات'))
                                                        <button class="btn btn-primary" type="button" data-toggle="modal"
                                                                data-target="#{{ $team->id }}"
                                                                data-whatever="@test"><i class="fa fa-edit"></i>
                                                        </button>
                                                    @endif
                                                    @if (auth()->user()->hasPermissionTo('حذف الاعدادات'))
                                                        <form action="{{ route('delete-terms', $team->id ) }}"
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
                                                                        {{ $team->title_ar }} </h5>

                                                                    <button class="close" type="button"
                                                                            data-dismiss="modal"
                                                                            aria-label="Close">
                                                                        <span aria-hidden="true">×</span></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <form class="needs-validation" novalidate=""
                                                                          action="{{route('edit-terms',$team->id)}}"
                                                                          method="POST" enctype="multipart/form-data">
                                                                        {{ method_field('POST') }}
                                                                        {{ csrf_field() }}
                                                                        <div class="form-group">
                                                                            <label for="validationCustom05"
                                                                                   class="col-form-label page-header-left">عنوان
                                                                                بالعربيه
                                                                            </label>
                                                                            <input class="form-control"
                                                                                   name="title_ar"
                                                                                   type="text"
                                                                                   value="{{ $team->title_ar }}"
                                                                                   placeholder="ادخل العنوان بالعربية"
                                                                                   required="">
                                                                            <div class="invalid-feedback">{{ $errors->first('title_ar') }}</div>
                                                                        </div>
                                                                        <span class="text-danger page-header-left"
                                                                              style="color: red;">{{$errors->first('title_ar')}}</span>

                                                                        <div class="form-group">

                                                                            <label for="validationCustom05"
                                                                                   class="col-form-label page-header-left">عنوان
                                                                                بالانجليزية
                                                                            </label>
                                                                            <input class="form-control"
                                                                                   name="title_en"
                                                                                   type="text"
                                                                                   value="{{ $team->title_en }}"
                                                                                   placeholder="ادخل الاسم بالانجليزية"
                                                                                   required="">
                                                                            <div class="invalid-feedback">{{ $errors->first('title_en') }}</div>
                                                                        </div>
                                                                        <span class="text-danger page-header-left"
                                                                              style="color: red;">{{$errors->first('title_en')}}</span>


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

        function change_status_terms(id, value) {
            if (value == 0) {
                value = 1;
            } else {
                value = 0;
            }
            axios.get("{{\Illuminate\Support\Facades\URL::to('admin/update-status-terms/')}}/"+ id + '/' + value)

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
