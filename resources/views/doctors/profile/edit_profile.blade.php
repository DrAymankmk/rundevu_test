@extends('includes_admin.mainlayout')

@section('styles')
    <style>
        .profile-edit-form .profile-basic-layout {
            display: flex;
            align-items: flex-start;
            gap: 24px;
        }

        .profile-edit-form .profile-img-wrap {
            position: relative;
            flex: 0 0 120px;
            inset: auto;
        }

        .profile-edit-form .profile-basic {
            flex: 1 1 auto;
            min-width: 0;
            margin-left: 0;
            margin-right: 0;
        }

        .profile-edit-form .profile-img-wrap img {
            display: block;
            object-fit: cover;
        }

        @media (max-width: 767.98px) {
            .profile-edit-form .profile-basic-layout {
                flex-direction: column;
            }
        }
    </style>
@endsection

@section('content')

    <div class="page-wrapper">
        <div class="content">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a
                                    href="{{ route('admin.dashboard') }}">@lang('admin.dashboard') </a></li>
                            <li class="breadcrumb-item"><i class="feather-chevron-right"></i></li>
                            <li class="breadcrumb-item active">@lang('admin.edit_profile')</li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->
            <form class="profile-edit-form"
                action="{{route('update-department-employee', $data['clinic']->id)}}"
                method="POST" enctype="multipart/form-data">
                {{ method_field('POST') }}
                {{ csrf_field() }}
                <div class="card-box">
                    <h3 class="card-title">@lang('admin.basic_info')</h3>
                    <div class="row">
                        <div class="col-md-12 profile-basic-layout">
                            <div class="profile-img-wrap">
                                <img class="inline-block" src="{{$data['clinic']->image}}" alt="user">
                                <div class="fileupload btn">
                                    <span class="btn-text">@lang('admin.edit')</span>
                                    <input class="upload" type="file" name="image">
                                </div>
                            </div>

                            <div class="profile-basic">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group local-forms">
                                            <label class="focus-label">@lang('admin.name')</label>
                                            <input type="text" class="form-control floating" name="name"
                                                   value="{{$data['clinic']->name}}" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group local-forms">
                                            <label class="focus-label">@lang('admin.phone')</label>
                                            <input type="tel" class="form-control floating" name="phone"
                                                   value="{{$data['clinic']->phone}}">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group local-forms">
                                            <label class="focus-label">@lang('admin.gender')</label>
                                            <select class="form-control select" name="gender" required>
                                                <option value="1"
                                                        @if($data['clinic']->gender == 1)  selected @endif>@lang('admin.male')</option>
                                                <option value="2"
                                                        @if($data['clinic']->gender == 2)  selected @endif>@lang('admin.female')</option>
                                            </select>
                                        </div>
                                    </div>

                                    @if(auth()->user()->app_type == 3)
                                        <div class="col-md-6">
                                            <div class="form-group local-forms">
                                                <label class="degree_id">@lang('admin.doctor_degrees')</label>
                                                <select class="form-control select" id="degree_id" required>
                                                    <option value="">@lang('admin.select')</option>
                                                    @foreach( $data['degree'] as $degree_item)
                                                        <option value="{{$degree_item->id}}"
                                                                @if($data['clinic']->degree_id == $degree_item->id)  selected @endif>{{$degree_item->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    @endif

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if(auth()->user()->app_type == 3)
                    <div class="card-box">
                        <h3 class="card-title">@lang('admin.specialization')</h3>
                        <div class="row">

                            <div class="col-md-6">
                                <div class="form-group local-forms">
                                    <label class="specialist_id">@lang('admin.specialization')</label>
                                    <select class="form-control select" id="specialist_id" required>
                                        <option value="">@lang('admin.select')</option>
                                        @foreach($data['specializations'] as $specialist)
                                            <option
                                                value="{{ $specialist->id }}" {{ (!in_array($specialist->id,$data['specialty_ids'])) ? '' : 'selected'}}>{{ app()->getLocale() == 'en' ? $specialist->name_en : $specialist->name_ar }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group local-forms">
                                    <label class="focus-label">@lang('admin.sub_specialization')</label>
                                    <select class="form-control select" id="sub_specialist_ids"
                                            name="sub_specialist_ids[]"
                                            multiple="multiple">
                                        @foreach($data['sub_specializations'] as $specialist)
                                            <option
                                                value="{{ $specialist->id }}" {{ (!in_array($specialist->id,$data['sub_specialty_ids'])) ? '' : 'selected'}}>{{ app()->getLocale() == 'en' ? $specialist->name_en : $specialist->name_ar }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group local-forms">
                                    <label class="focus-label">@lang('admin.clinics_doctor')</label>
                                    <input type="text" readonly class="form-control floating"
                                           value="{{ Auth::user()->clinic_doctor->name ?? null }}" required>
                                </div>
                            </div>

                        </div>
                    </div>
                @endif

                <div class="text-center m-t-20">
                    <button type="submit" class="btn btn-primary submit-btn">@lang('admin.save')</button>
                </div>
            </form>
        </div>

    </div>


    <script src="{{asset('/admin/js/jquery-3.2.1.min.js')}}"></script>
    <script>
        $(document).ready(function () {

            $('#specialist_id').on('change', function () {
                var specialist_id = this.value;
                $('#sub_specialist_ids').html('');
                $.ajax({
                    url: '{{ route('getSubSpecialist') }}?specialist_id=' + specialist_id,
                    type: 'get',
                    dataType: 'json',
                    success: function (res) {
                        if (res.specialists_count > 0) { // all was ok
                            {{--$('#sub_specialist_ids').html('<option value="" selected disabled>@lang('admin.select')</option>');--}}
                            $.each(res.data, function (key, value) {
                                $('#sub_specialist_ids').append('<option value="' + value
                                    .id + '">' + value.name + '</option>');
                            });
                        } else {
                            $('#sub_specialist_ids').html('<option value="">@lang('admin.no_data')</option>');
                        }
                    }
                });
            });
        });
    </script>

@endsection
