@extends('includes_admin.mainlayout')
@section('content')
    <!-- Right sidebar Ends-->
    <div class="page-body">
        <div class="container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col">
                        <div class="page-header-left">
                            <h3>التقارير</h3>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}"><i
                                                data-feather="home"> </i> الرئيسية </a></li>
                                <li class="breadcrumb-item active">التقارير</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Default ordering (sorting) Starts-->
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <form class="needs-validation" novalidate="" action="{{route('report-result')}}"
                                  method="post">
                                {{csrf_field()}}
                                <div class="form-row">
                                    <div class="col-md-3 mb-3">
                                        <input class="datepicker-here form-control digits" name="from"
                                               type="date"
                                               data-language="en"
                                               data-multiple-dates-separator=", "
                                               data-position="bottom left"
                                               placeholder="بدايه من " autocomplete="true">
                                        <div class="invalid-feedback">{{ $errors->first('from') }}.</div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <input class="datepicker-here form-control digits" name="to"
                                               type="date"
                                               data-language="en"
                                               data-multiple-dates-separator=", "
                                               data-position="bottom left"
                                               placeholder="نهايه الى" autocomplete="true">
                                        <div class="invalid-feedback">{{ $errors->first('to') }}.</div>
                                    </div>

                                    <div class="col-md-3 mb-3">
                                        <input class="datepicker-here form-control digits" name="name"
                                               type="text"
                                               placeholder="اسم العقار">
                                        <div class="invalid-feedback">{{ $errors->first('to') }}.</div>
                                    </div>


                                    <div class="col-md-3 mb-3">
                                        <select name="category_id" id="category_id"
                                                class="form-control">
                                            <option value="">اختر القسم</option>
                                            @foreach($data['categories'] as $category)
                                                <option value="{{$category->id}}">{{$category->name_ar}}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">{{ $errors->first('category_id') }}</div>
                                    </div>

                                    <div class="col-md-3 mb-3">
                                        <select name="city_id" id="city_id" class="form-control">
                                            <option value="">اختر القسم اولا</option>
                                        </select>
                                        <div class="invalid-feedback">{{ $errors->first('city_id') }}</div>
                                    </div>

                                    <div class="col-md-3 mb-3">
                                        <select name="confirm"
                                                class="form-control">
                                            <option value="1">اختر حالة بيع العقار</option>
                                         <option value="0">عقارات تم بيعها</option>
                                         <option value="1">عقارات لم يتم بيعها</option>
                                        </select>
                                        <div class="invalid-feedback">{{ $errors->first('confirm') }}</div>
                                    </div>

                                    <div class="col-md-3 mb-3">
                                        <button class="btn btn-primary" type="submit">بحث</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection
