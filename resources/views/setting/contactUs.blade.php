@extends('includes_admin.mainlayout')
@section('content')

    <!-- Right sidebar Ends-->

    <div class="page-body">
        <div class="container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col">
                        <div class="page-header-left">
                               <h5> الرسائل المرسله من العملاء </h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    @if( count($messages) > 0)
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="display dataTable" id="basic-1">
                                    <thead>
                                    <tr>
                                        <th>اسم العميل</th>
                                        <th>البريد الالكترونى</th>
                                        <th> الرساله</th>
                                        <th> الرد</th>
                                        <th>العمليات</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($messages as $message)
                                        <tr>
                                            <td>{{ $message->name }}</td>
                                            <td>{{ $message->email }}</td>
                                            <td>{{ $message->message }}</td>
                                            @if(!empty($message->reply))
                                                <td>{{ $message->reply }}</td>
                                            @else
                                                <td style="color: #ff0000">لم يتم الرد بعد</td>
                                            @endif
                                            <td>
                                                @if (auth()->user()->hasPermissionTo('الرد على رسائل التواصل'))
                                                <button class="btn btn-primary" type="button" data-toggle="modal"
                                                        data-target="#{{ $message->id }}"
                                                        data-whatever="@socialMedia"><i class="fa fa-edit"></i>
                                                </button>

                                                    <form action="{{ route('delete-contactUs', $message->id) }}"
                                                          method="post" style="display: inline-block">
                                                        {{ csrf_field() }}
                                                        {{ method_field('delete') }}
                                                        <button type="submit"
                                                                class="btn btn-danger delete btn-sm"
                                                                title="حذف الشكوى او المقترح"><i
                                                                    class="fa fa-trash"></i>
                                                        </button>
                                                    </form>
                                                    
                                                @endif
                                                <div class="modal fade" id="{{ $message->id }}" tabindex="-1"
                                                     role="dialog"
                                                     aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title"> الرد على رساله
                                                                    {{ $message->name }} </h5>
                                                                <button class="close" type="button"
                                                                        data-dismiss="modal"
                                                                        aria-label="Close">
                                                                    <span aria-hidden="true">×</span></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form class="needs-validation" novalidate=""
                                                                      action="{{route('add-reply',['id'=>$message->id])}}"
                                                                      method="POST" enctype="multipart/form-data">
                                                                    {{ method_field('POST') }}
                                                                    {{ csrf_field() }}

                                                                    <div class="form-group">
                                                                        <label for="validationCustom05"
                                                                               class="col-form-label page-header-left">الرد
                                                                            على رساله العميل</label>
                                                                        <input class="form-control" name="message_ar"
                                                                               type="text"
                                                                               value="{{ $message->reply }}"
                                                                               placeholder="ادخل الرد"
                                                                               required>
                                                                        <div
                                                                            class=" invalid-feedback">{{ $errors->first('value') }}</div>
                                                                    </div>

                                                                    <div class="modal-footer">
                                                                        <button class="btn btn-primary"
                                                                                type="submit">
                                                                            ارسال
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

                        <h4 class="text-center"> لا توجد بيانات التواصل الاجتماعى </h4>
                    @endif

                </div>
            </div>
        </div>
    </div>
@endsection
