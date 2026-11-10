@extends('layout_new.mainlayout')

@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('deals.index') }}">@lang('deals.title')</a></li>
                <li class="breadcrumb-item active">@lang('deals.edit_deal')</li>
            </ul>
        </div>
        <div class="card">
            <div class="card-body">
                <form method="post" action="{{ route('deals.update', $deal->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    @include('main_admin.deals.partials.form-fields', ['deal' => $deal, 'clinics' => $clinics, 'specialties' => $specialties])
                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary">@lang('deals.save')</button>
                        <a href="{{ route('deals.index') }}" class="btn btn-light">@lang('deals.cancel')</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
