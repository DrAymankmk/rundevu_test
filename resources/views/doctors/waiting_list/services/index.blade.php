@extends('includes_admin.mainlayout')
@section('content')

    <style>
        .container {
            max-width: 800px;
            margin: 0 auto;
        }

        .image-or-pdf {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
        }

        .image-or-pdf img,
        .image-or-pdf embed {
            max-width: 100%;
            height: auto;
        }

        .download-link {
            text-align: center;
            margin-top: 10px;
        }
    </style>

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
                            <li class="breadcrumb-item active">{{ $data['title'] }}</li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->
            <form
                action="{{route('add-status-conversion', $reservation_id)}}"
                method="POST" enctype="multipart/form-data">
                {{ method_field('POST') }}
                {{ csrf_field() }}
                <input type="hidden" name="type" value="{{$data['type'] ?? 1}}"/>

                @if($data['type'] == 3 || $data['type'] == 4)
                    <div class="card-box">
                        <div class="row">
                            <div class="col-12 col-md-12 col-xl-12">
                                <div class="form-group theme-set row">
                                    <label class="col-lg-3 col-form-label">@lang('admin.image')</label>
                                    <div class="col-12 col-md-12 col-xl-12">
                                        <input class="form-control" type="file" name="image">
                                    </div>
                                    <span class="text-danger page-header-left"
                                          style="color: red;">{{$errors->first('notes')}}</span>
                                </div>
                            </div>

                        </div>
                        @if($data['type'] == 4)
                            <div class="col-12 col-md-12 col-xl-12">
                                <div class="form-group local-forms">
                                    <label>@lang('admin.notes') <span class="login-danger">*</span></label>
                                    <textarea name="notes" class="form-control" required></textarea>
                                </div>
                                <span class="text-danger page-header-left"
                                      style="color: red;">{{$errors->first('notes')}}</span>
                            </div>
                        @endif


                    </div>
                @else
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
                                                value="{{ $specialist->id }}">{{  $specialist->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group local-forms">
                                    <label class="focus-label">@lang('admin.doctors')</label>
                                    <select class="form-control select" id="doctor_id" name="doctor_id" required>
                                        <option>@lang('admin.select_specialization_first')</option>
                                    </select>
                                </div>
                            </div>


                            <div class="col-12 col-md-12 col-xl-12">
                                <div class="form-group local-forms">
                                    <label>@lang('admin.notes') <span class="login-danger">*</span></label>
                                    <textarea name="notes" class="form-control" required></textarea>
                                </div>
                                <span class="text-danger page-header-left"
                                      style="color: red;">{{$errors->first('notes')}}</span>
                            </div>

                            @if($data['type'] == 2)
                                <div class="col-12 col-md-12 col-xl-12">
                                    <div class="form-group theme-set row">
                                        <label class="col-lg-3 col-form-label">@lang('admin.image')</label>
                                        <div class="col-12 col-md-12 col-xl-12">
                                            <input class="form-control" type="file" name="image">
                                        </div>
                                        <span class="text-danger page-header-left"
                                              style="color: red;">{{$errors->first('notes')}}</span>
                                    </div>
                                </div>

                            @endif

                        </div>
                    </div>
                @endif


                <div class="text-center m-t-20">
                    <button type="submit" class="btn btn-primary submit-btn">@lang('admin.send')</button>
                </div>
            </form>


            @if($data['type'] == 4)
                <div class="container">
                    <h1>@lang('admin.attachment')</h1>

                    <!-- Replace this URL with the path to your image or PDF file -->
                    @foreach($data['attachment'] as $index=>$attachment)
                        @php
                            $extension = pathinfo($attachment->image, PATHINFO_EXTENSION);
                        @endphp
                        <div class="image-or-pdf">
                            @if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif']))
                                <img src="{{$attachment->image}}" alt="@lang('admin.no_image')">
                            @else
                                <!-- Use 'embed' for PDF files: -->
{{--                                <iframe src="{{$attachment->image}}" style="width: 100%; height: 600px;"></iframe>--}}
                            @endif
                        </div>

                        <div class="download-link">
                            <!-- Replace 'your-image.jpg' or 'your-file.pdf' with the actual file path -->
                          @lang('admin.notes') :  <p>{{$attachment->notes ?? null}}</p>
                            <a href="{{$attachment->image}}" download>@lang('admin.Download') ({{ $index + 1 }})</a>
                        </div>
                    @endforeach

                </div>
            @endif


        </div>

    </div>


    <script src="{{asset('/admin/js/jquery-3.2.1.min.js')}}"></script>
    <script>
        $(document).ready(function () {

            $('#specialist_id').on('change', function () {
                var specialist_id = this.value;
                $('#doctor_id').html('');
                $.ajax({
                    url: '{{ route('getDoctorsFromSpecialist') }}?specialist_id=' + specialist_id,
                    type: 'get',
                    dataType: 'json',
                    success: function (res) {
                        if (res.doctors_count > 0) { // all was ok
                            {{--$('#sub_specialist_ids').html('<option value="" selected disabled>@lang('admin.select')</option>');--}}
                            $.each(res.data, function (key, value) {
                                $('#doctor_id').append('<option value="' + value
                                    .id + '">' + value.name + '</option>');
                            });
                        } else {
                            $('#doctor_id').html('<option value="">@lang('admin.no_data')</option>');
                        }
                    }
                });
            });
        });
    </script>

@endsection
