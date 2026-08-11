@extends('includes_admin.mainlayout')
@section('content')
    <div class="page-body">
        <div class="container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col">
                        <div class="page-header-left">
                            <h4>من نحن</h4>
                        </div>
                    </div>

                </div>
            </div>
        </div>


        <!-- Container-fluid starts-->
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                        </div>
                        <form class="form theme-form" action="{{route('update-aboutUs', $aboutUs->id)}}" method="POST"
                              enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-12 col-md-12 col-lg-12">
                                        <div class="form-group mb-0">
                                            <label for="exampleFormControlTextarea4"> عنوان من نحن بالعربية</label>
                                            <textarea class="form-control" name="about_ar" required
                                                      rows="3"> {{$aboutUs->about_ar}}</textarea>
                                            <small class="text-danger">{{ $errors->first('title_ar') }}</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-12 col-md-12 col-lg-12">
                                        <div class="form-group mb-0">
                                            <label for="exampleFormControlTextarea4"> عنوان من نحن بالانجليزية</label>
                                            <textarea class="form-control" name="about_en" required
                                                      rows="3"> {{$aboutUs->about_en}}</textarea>
                                            <small class="text-danger">{{ $errors->first('title_en') }}</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-12 col-md-12 col-lg-12">
                                        <div class="form-group mb-0">
                                            <label for="exampleFormControlTextarea4"> هدفنا بالعربية</label>
                                            <textarea class="form-control" name="mission_ar" required
                                                      rows="3"> {{$aboutUs->mission_ar}}</textarea>
                                            <small class="text-danger">{{ $errors->first('mission_ar') }}</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 col-md-12 col-lg-12">
                                        <div class="form-group mb-0">
                                            <label for="exampleFormControlTextarea4"> هدفنا بالانجليزية</label>
                                            <textarea class="form-control" name="mission_en" required
                                                      rows="3"> {{$aboutUs->mission_en}}</textarea>
                                            <small class="text-danger">{{ $errors->first('mission_en') }}</small>
                                        </div>
                                    </div>
                                </div>
                                <br>

                                <div class="row">
                                    <div class="col-sm-12 col-md-12 col-lg-12">
                                        <div class="form-group mb-0">
                                            <label for="exampleFormControlTextarea4"> رويتنا بالعربية</label>
                                            <textarea class="form-control" name="vision_ar" required
                                                      rows="3"> {{$aboutUs->vision_ar}}</textarea>
                                            <small class="text-danger">{{ $errors->first('vision_ar') }}</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-12 col-md-12 col-lg-12">
                                        <div class="form-group mb-0">
                                            <label for="exampleFormControlTextarea4"> رويتنا بالانجليزية</label>
                                            <textarea class="form-control" name="vision_en" required
                                                      rows="3"> {{$aboutUs->vision_en}}</textarea>
                                            <small class="text-danger">{{ $errors->first('vision_en') }}</small>
                                        </div>
                                    </div>
                                </div>
                                <br>

                                <div class="form-row">

                                    <div class="col-md-6 mb-3">
                                        <div class="custom-file">
                                            <label for="validationCustom05">صورة</label>
                                            <input class="form-control" type="file" name="image">
                                        </div>
                                        <div class="invalid-feedback">{{ $errors->first('image') }}
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <img src="{{$aboutUs->image }}" alt="{{asset('website/images/1.jpg')}}"
                                             class="img-thumbnail image-preview img-100 rounded-circle">
                                    </div>
                                </div>


                                <br>
                                <br>

                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="validationCustom04">عدد العملاء حتى الان</label>
                                        <input class="form-control" type="number" name="customers_number"
                                               value="{{ $statistic->customers_number }}"
                                               required>
                                        <div class="invalid-feedback">{{ $errors->first('customers_number') }}.</div>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="validationCustom04">اجمالى مبالغ المبيعات</label>
                                        <input class="form-control" type="number" name="home_sales"
                                               placeholder="مبلغ مبيعات المشاريع"
                                               value="{{ $statistic->home_sales }}"
                                               required>
                                        <div class="invalid-feedback">{{ $errors->first('home_sales') }}.</div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="validationCustom04">مبلغ التوفير</label>
                                        <input class="form-control" type="number" name="savings"
                                               value="{{ $statistic->savings }}"
                                               required>
                                        <div class="invalid-feedback">{{ $errors->first('savings') }}.</div>
                                    </div>


                                </div>

                            </div>

                            @if (auth()->user()->hasPermissionTo('تعديل الاعدادات'))
                            <div class="card-footer">
                                <button class="btn btn-primary" type="submit">{{trans('admin.edit')}}</button>
                            </div>
@endif
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection