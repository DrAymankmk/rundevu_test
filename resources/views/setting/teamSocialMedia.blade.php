@extends('includes_admin.mainlayout')
@section('content')

    <!-- Right sidebar Ends-->

    <div class="page-body">
        <div class="container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col">
                        <div class="page-header-left">
                            <h3>بيانات التواصل الاجتماعى</h3>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}"><i
                                                data-feather="home"> </i> الرئيسية </a></li>
                                <li class="breadcrumb-item active">اضافه بيانات التواصل الخاصه
                                    ب {{ $data['team']->name }}</li>
                            </ol>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    @if( count($data['social_media']) > 0)
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="display dataTable" id="basic-1">
                                    <thead>
                                    <tr>
                                        <th>{{trans('admin.image')}}</th>

                                        <th>بيانات التواصل</th>
                                        <th>{{trans('admin.action')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($data['social_media'] as $media)
                                        <tr>
                                            <td><i class="{{$media->image}}" style="font-size:48px;color:#000000"></i>
                                            </td>
                                            @if($media->social_media)
                                                <td><a style="color: #1b8d39" href="{{ $media->social_media->social_link  }}"
                                                       target="_blank"> {{ $media->social_media->social_link  }}</a>
                                                </td>
                                            @else
                                                <td>لا يوجد بيانات مضافه</td>
                                            @endif

                                            <td>
                                                @if (auth()->user()->hasPermissionTo('تعديل الاعدادات'))
                                                <button class="btn btn-primary" type="button" data-toggle="modal"
                                                        data-target="#{{ $media->id }}"
                                                        data-whatever="@socialMedia"><i class="fa fa-edit"></i>
                                                </button>
                                                @endif
                                                <div class="modal fade" id="{{ $media->id }}" tabindex="-1"
                                                     role="dialog"
                                                     aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title"> {{ trans('admin.edit') }}
                                                                    {{ $media->name }} </h5>
                                                                <button class="close" type="button"
                                                                        data-dismiss="modal"
                                                                        aria-label="Close">
                                                                    <span aria-hidden="true">×</span></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form class="needs-validation" novalidate=""
                                                                      action="{{route('edit-TeamSocialMedia',[$media->id,$data['team']->id])}}"
                                                                      method="POST" enctype="multipart/form-data">
                                                                    {{ method_field('POST') }}
                                                                    {{ csrf_field() }}

                                                                    <div class="form-group">
                                                                        <label for="validationCustom05"
                                                                               class="col-form-label page-header-left">بيانات
                                                                            التواصل</label>
                                                                        <input class="form-control" name="social_link"
                                                                               type="url"
                                                                               value="{{ !empty($media->social_media) ? $media->social_media->social_link : ""}}"
                                                                               placeholder="ادخل بيانات التواصل"
                                                                               required>
                                                                        <div class=" invalid-feedback">{{ $errors->first('url') }}</div>
                                                                    </div>

                                                                    <div class="modal-footer">
                                                                        <button class="btn btn-primary"
                                                                                type="submit">
                                                                            {{ trans('admin.edit') }}
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

                        <h4 class="text-center" style="color: #ff0000"> {{ trans('admin.no_data') }} </h4>
                    @endif

                </div>
            </div>
        </div>
    </div>
@endsection
