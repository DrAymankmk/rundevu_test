@extends('includes_admin.mainlayout')
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
                            <li class="breadcrumb-item active">{{ $title }}</li>
                        </ul>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-md-12">
                    <div class="blog-view">
                        <article class="blog blog-single-post">
                            <h3 class="blog-title">{{ $setting->title }}</h3>
                            <div class="blog-image" style="text-align: -webkit-center">
                                <a href="#."><img alt="" src="/media/icons/setting_image.svg" style="width: 700px;height: 300px" class="img-fluid"></a>
                            </div>
                            <div class="blog-content">
                                <p>{{ $setting->content }}</p>
                            </div>
                        </article>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection
