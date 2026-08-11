@extends('includes_admin.mainlayout')
@section('content')

    <!-- Right sidebar Ends-->
    <div class="page-body">
        <div class="container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col">
                        <div class="page-header-left">
                            <h3>شركاؤنا</h3>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}"><i
                                                data-feather="home"> </i> الرئيسية </a></li>
                                <li class="breadcrumb-item active">شركاؤنا</li>
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
                                        data-target="#partner" data-original-title="اعلان">اضافه لوجو شركه
                                </button>
                            @endif
                            <div class="modal fade" id="partner" tabindex="-1" role="dialog"
                                 aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">اضافه لوجو</h5>
                                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">×</span></button>
                                        </div>
                                        <div class="modal-body">
                                            <form class="needs-validation" novalidate=""
                                                  action="{{route('create-partner')}}"
                                                  method="POST" enctype="multipart/form-data">
                                                {{ method_field('POST') }}
                                                {{ csrf_field() }}

                                                <div class="form-group">
                                                    <label for="validationCustom05"
                                                           class="page-header-left">صوره</label>
                                                    <div class="custom-file">
                                                        <input class="custom-file-input" type="file"
                                                               name="image" required>
                                                        <label class="custom-file-label" for="validatedCustomFile">اختر
                                                            صوره</label>
                                                        <div class="invalid-feedback">{{ $errors->first('images') }}</div>
                                                    </div>
                                                </div>
                                                <span class="text-danger page-header-left"
                                                      style="color: red;">{{$errors->first('image')}}</span>
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

                        @if( count($data['partners']) > 0)
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="display dataTable" id="basic-1">
                                        <thead>
                                        <tr>
                                            <th>رقم المسلسل</th>
                                            <th>الصور</th>
                                            <th>الحاله</th>
                                            <th>الاجراء</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($data['partners'] as $index=>$slider)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td><img
                                                            class="img-100 rounded-circle"
                                                            src="{{ $slider->image }}"
                                                            alt="#"
                                                            data-original-title=""
                                                            title=""></td>
                                                <td>
                                                    @if (auth()->user()->hasPermissionTo('تعديل الاعدادات'))
                                                        <div class="media-body text-left icon-state">
                                                            <label class="switch">
                                                                <input type="checkbox"
                                                                       {{ $slider->status == 1 ? 'checked' : '' }}
                                                                       onchange="change_status_slider({{ $slider->id }},{{ $slider->status }})"><span
                                                                        class="switch-state bg-primary"></span>

                                                            </label>
                                                        </div>
                                                    @endif
                                                </td>
                                                <td>
                                                    <button class="btn btn-primary" type="button" data-toggle="modal"
                                                            data-target="#{{ $slider->id }}"
                                                            data-whatever="@test"><i class="fa fa-edit"></i>
                                                    </button>
                                                    @if (auth()->user()->hasPermissionTo('حذف الاعدادات'))
                                                        <form action="{{ route('delete-partners',$slider->id) }}"
                                                              method="post" style="display: inline-block">
                                                            {{ csrf_field() }}
                                                            {{ method_field('delete') }}
                                                            <button type="submit" class="btn btn-danger delete btn-sm">
                                                                <i class="fa fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    @endif

                                                    <div class="modal fade" id="{{ $slider->id }}" tabindex="-1"
                                                         role="dialog"
                                                         aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h6 class="modal-title"> تعديل الصورة</h6>
                                                                    <button class="close" type="button"
                                                                            data-dismiss="modal"
                                                                            aria-label="Close">
                                                                        <span aria-hidden="true">×</span></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <form class="needs-validation" novalidate=""
                                                                          action="{{route('edit-partners',$slider->id)}}"
                                                                          method="POST" enctype="multipart/form-data">
                                                                        {{ method_field('POST') }}
                                                                        {{ csrf_field() }}

                                                                        <span class="text-danger page-header-left"
                                                                              style="color: red;">{{$errors->first('ads_link')}}</span>

                                                                        <div class="form-group">
                                                                            <label for="validationCustom05"
                                                                                   class="page-header-left">صوره</label>
                                                                            <label for="file-input"
                                                                                   class="image-upload-label">
                                                                                <img alt="upload-partners-image"
                                                                                     src="{{$slider->image}}"
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
                            <h4 class="text-center" style="color: #ff0000"> لا توجد صور مضافه</h4>
                        @endif


                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        function change_status_slider(id, value) {
            if (value == 0) {
                value = 1;
            } else {
                value = 0;
            }
            axios.get('update-status-partners/' + id + '/' + value)
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
