@extends('includes_admin.mainlayout')
@section('content')
    <!-- Right sidebar Ends-->
    <div class="page-body">
        <div class="container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col">
                        <div class="page-header-left">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}"><i
                                            data-feather="home"> </i> @lang('admin.dashboard') </a></li>
                                <li class="breadcrumb-item active">@lang('admin.offers')
                                    ( {{ $data['offers']->total() }} )
                                </li>
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
                            @if (auth()->user()->app_type != 6 || auth()->user()->hasPermissionTo('offers_add'))
                                <button class="btn btn-primary" type="button" data-toggle="modal"
                                        data-target="#city" data-whatever="@test">@lang('admin.Add a discount item')
                                </button>
                            @endif

                            <div class="modal fade" id="city" tabindex="-1" role="dialog"
                                 aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">@lang('admin.Add a discount item')</h5>
                                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">×</span></button>
                                        </div>
                                        <div class="modal-body">
                                            <form class="needs-validation" novalidate=""
                                                  action="{{route('add-offer')}}"
                                                  method="POST" enctype="multipart/form-data">
                                                {{ method_field('POST') }}
                                                {{ csrf_field() }}

                                                <div class="form-group">
                                                    <label for="specialty_id"
                                                           class="col-form-label page-header-left">@lang('admin.specialties')</label>
                                                    <select class="form-control" name="specialty_id" required>
                                                        <option value="">@lang('admin.select')</option>
                                                        @foreach($data['specialties'] as $specialty)
                                                            <option value="{{ $specialty->id }}"
                                                                    {{ old('specialty_id') == $specialty->id ? 'selected' : '' }}>
                                                                {{ app()->getLocale() == 'en' ? $specialty->name_en : $specialty->name_ar }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <div class="invalid-feedback">{{ $errors->first('specialty_id') }}</div>
                                                </div>
                                                <span class="text-danger page-header-left"
                                                      style="color: red;">{{$errors->first('specialty_id')}}</span>

                                                <div class="form-group">
                                                    <label for="validationCustom05"
                                                           class="col-form-label page-header-left">@lang('admin.title_ar')</label>
                                                    <input class="form-control" name="title_ar"
                                                           type="text" value="{{ old('title_ar') }}"
                                                           placeholder="@lang('admin.title_ar')"
                                                           required="">
                                                    <div class="invalid-feedback">{{ $errors->first('title_ar') }}</div>
                                                </div>
                                                <span class="text-danger page-header-left"
                                                      style="color: red;">{{$errors->first('title_ar')}}</span>

                                                <div class="form-group">
                                                    <label for="validationCustom05"
                                                           class="col-form-label page-header-left">@lang('admin.title_en')</label>
                                                    <input class="form-control" name="title_en"
                                                           type="text" value="{{ old('title_en') }}"
                                                           placeholder="@lang('admin.title_en')"
                                                           required="">
                                                    <div class="invalid-feedback">{{ $errors->first('title_en') }}</div>
                                                </div>
                                                <span class="text-danger page-header-left"
                                                      style="color: red;">{{$errors->first('title_en')}}</span>


                                                <div class="form-group">
                                                    <label for="validationCustom05"
                                                           class="col-form-label page-header-left">@lang('admin.Discount Percentage')
                                                        % </label>
                                                    <input class="form-control" name="discount"
                                                           type="number" value="{{ old('discount') }}"
                                                           placeholder="@lang('admin.Discount Percentage')"
                                                           required="">
                                                    <div class="invalid-feedback">{{ $errors->first('discount') }}</div>
                                                </div>
                                                <span class="text-danger page-header-left"
                                                      style="color: red;">{{$errors->first('discount')}}</span>

                                                <div class="form-group">
                                                    <label class="col-form-label page-header-left">@lang('admin.From')</label>
                                                    <input class="form-control" name="start_date" type="date" min="{{ date('Y-m-d') }}" value="{{ old('start_date', date('Y-m-d')) }}">
                                                    <div class="invalid-feedback">{{ $errors->first('start_date') }}</div>
                                                </div>
                                                <span class="text-danger page-header-left"
                                                      style="color: red;">{{$errors->first('start_date')}}</span>

                                                <div class="form-group">
                                                    <label class="col-form-label page-header-left">@lang('admin.To')</label>
                                                    <input class="form-control" name="end_date" type="date" min="{{ date('Y-m-d') }}" value="{{ old('end_date') }}">
                                                    <div class="invalid-feedback">{{ $errors->first('end_date') }}</div>
                                                </div>
                                                <span class="text-danger page-header-left"
                                                      style="color: red;">{{$errors->first('end_date')}}</span>

                                                <div class="modal-footer">
                                                    <button class="btn btn-primary"
                                                            type="submit">@lang('admin.add')</button>
                                                    <button class="btn btn-secondary" type="button"
                                                            data-dismiss="modal">@lang('admin.close')
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if( count($data['offers']) > 0)
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="display dataTable" id="basic-1">
                                        <thead>
                                        <tr>
                                            <th style="display: none">#</th>
                                            <th>@lang('admin.arabic_name')</th>
                                            <th>@lang('admin.english_name')</th>
                                            <th>@lang('admin.specialties')</th>
                                            <th>@lang('admin.Discount Percentage')</th>
                                            <th>@lang('admin.From')</th>
                                            <th>@lang('admin.To')</th>
                                            <th>@lang('admin.status')</th>
                                            <th>@lang('admin.action')</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($data['offers'] as $index=>$offer)
                                            <tr>
                                                <td style="display: none">{{ $index + 1 }}</td>
                                                <td>{{ $offer->title_ar }}</td>
                                                <td>{{ $offer->title_en }}</td>
                                                <td>{{ optional($offer->specialty)->{'name_' . app()->getLocale()} }}</td>
                                                <td>{{ $offer->discount }}</td>
                                                <td>{{ $offer->start_date }}</td>
                                                <td>{{ $offer->end_date }}</td>
                                                <td>
                                                    @if (auth()->user()->app_type != 6 || auth()->user()->hasPermissionTo('offers_delete'))
                                                        <div class="media-body text-left icon-state">
                                                            <label class="switch">
                                                                <input type="checkbox"
                                                                       {{ $offer->status == 1 ? 'checked' : '' }}
                                                                       onchange="change_status_city({{ $offer->id }},{{ $offer->status }})"><span
                                                                    class="switch-state bg-primary"></span>

                                                            </label>
                                                        </div>

                                                    @endif
                                                </td>
                                                <td>
                                                    @if (auth()->user()->app_type != 6 || auth()->user()->hasPermissionTo('offer_edit'))

                                                        <button class="btn btn-primary" type="button"
                                                                data-toggle="modal"
                                                                data-target="#{{ $offer->id }}"
                                                                data-whatever="@test"><i class="fa fa-edit"
                                                                                         title="@lang('admin.edit')"></i>
                                                        </button>
                                                    @endif
                                                    @if (auth()->user()->app_type != 6 || auth()->user()->hasPermissionTo('offers_delete'))

                                                        <form action="{{ route('destroy-offer', $offer->id) }}"
                                                              method="post" style="display: inline-block">
                                                            {{ csrf_field() }}
                                                            {{ method_field('delete') }}
                                                            <button type="submit" class="btn btn-danger delete btn-sm">
                                                                <i class="fa fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                    <div class="modal fade" id="{{ $offer->id }}" tabindex="-1"
                                                         role="dialog"
                                                         aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title"> @lang('admin.edit_data')
                                                                        {{ $offer->title_en }} </h5>
                                                                    <button class="close" type="button"
                                                                            data-dismiss="modal"
                                                                            aria-label="Close">
                                                                        <span aria-hidden="true">×</span></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <form class="needs-validation" novalidate=""
                                                                          action="{{route('edit-offer',$offer->id)}}"
                                                                          method="POST"
                                                                          enctype="multipart/form-data">
                                                                        {{ method_field('POST') }}
                                                                        {{ csrf_field() }}
                                                                        <div class="form-group">
                                                                            <label for="specialty_id"
                                                                                   class="col-form-label page-header-left">@lang('admin.specialties')</label>
                                                                            <select class="form-control" name="specialty_id" required>
                                                                                <option value="">@lang('admin.select')</option>
                                                                                @foreach($data['specialties'] as $specialty)
                                                                                    <option value="{{ $specialty->id }}"
                                                                                            {{ $offer->specialty_id == $specialty->id ? 'selected' : '' }}>
                                                                                        {{ app()->getLocale() == 'en' ? $specialty->name_en : $specialty->name_ar }}
                                                                                    </option>
                                                                                @endforeach
                                                                            </select>
                                                                            <div class="invalid-feedback">{{ $errors->first('specialty_id') }}</div>
                                                                        </div>
                                                                        <span class="text-danger page-header-left"
                                                                              style="color: red;">{{$errors->first('specialty_id')}}</span>

                                                                        <div class="form-group">
                                                                            <label for="title_ar"
                                                                                   class="col-form-label page-header-left">@lang('admin.title_ar')
                                                                            </label>
                                                                            <input class="form-control"
                                                                                   name="title_ar"
                                                                                   id="title_ar"
                                                                                   type="text"
                                                                                   value="{{ $offer->title_ar }}"
                                                                                   placeholder="@lang('admin.title_ar')"
                                                                                   required="">
                                                                            <div
                                                                                class="invalid-feedback">{{ $errors->first('title_ar') }}</div>
                                                                        </div>
                                                                        <span class="text-danger page-header-left"
                                                                              style="color: red;">{{$errors->first('title_ar')}}</span>

                                                                        <div class="form-group">
                                                                            <label for="validationCustom05"
                                                                                   class="col-form-label page-header-left">@lang('admin.title_en')</label>
                                                                            <input class="form-control" name="title_en"
                                                                                   type="text"
                                                                                   value="{{ $offer->title_en }}"
                                                                                   placeholder="@lang('admin.title_en')"
                                                                                   required="">
                                                                            <div
                                                                                class="invalid-feedback">{{ $errors->first('title_en') }}</div>
                                                                        </div>
                                                                        <span class="text-danger page-header-left"
                                                                              style="color: red;">{{$errors->first('title_en')}}</span>


                                                                        <div class="form-group">
                                                                            <label for="validationCustom05"
                                                                                   class="col-form-label page-header-left">@lang('admin.Discount Percentage')
                                                                                % </label>
                                                                            <input class="form-control" name="discount"
                                                                                   type="number"
                                                                                   value="{{ $offer->discount}}"
                                                                                   placeholder="@lang('admin.Discount Percentage')"
                                                                                   required="">
                                                                            <div
                                                                                class="invalid-feedback">{{ $errors->first('discount') }}</div>
                                                                        </div>
                                                                        <span class="text-danger page-header-left"
                                                                              style="color: red;">{{$errors->first('discount')}}</span>

                                                                        <div class="form-group">
                                                                            <label class="col-form-label page-header-left">@lang('admin.From')</label>
                                                                            <input class="form-control" name="start_date"
                                                                                   type="date"
                                                                                   min="{{ date('Y-m-d') }}"
                                                                                   value="{{ $offer->start_date }}">
                                                                            <div
                                                                                class="invalid-feedback">{{ $errors->first('start_date') }}</div>
                                                                        </div>
                                                                        <span class="text-danger page-header-left"
                                                                              style="color: red;">{{$errors->first('start_date')}}</span>

                                                                        <div class="form-group">
                                                                            <label class="col-form-label page-header-left">@lang('admin.To')</label>
                                                                            <input class="form-control" name="end_date"
                                                                                   type="date"
                                                                                   min="{{ date('Y-m-d') }}"
                                                                                   value="{{ $offer->end_date }}">
                                                                            <div
                                                                                class="invalid-feedback">{{ $errors->first('end_date') }}</div>
                                                                        </div>
                                                                        <span class="text-danger page-header-left"
                                                                              style="color: red;">{{$errors->first('end_date')}}</span>

                                                                        <div class="modal-footer">
                                                                            <button class="btn btn-primary"
                                                                                    type="submit">
                                                                                @lang('admin.edit')
                                                                            </button>
                                                                            <button class="btn btn-secondary"
                                                                                    type="button"
                                                                                    data-dismiss="modal">@lang('admin.close')
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
                            {{ $data['offers']->links() }}
                        @else
                            <h4 class="text-center" style="color: #ff0000"> @lang('admin.no_data') </h4>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function change_status_city(id, value) {
            if (value == 0) {
                value = 1;
            } else {
                value = 0;
            }
            axios.get('update-status-offer/' + id + '/' + value)
                .then(function (response) {
                    location.reload();
                })
                .catch(function (error) {
                    console.log(error);
                    alert(error);
                });
        }
    </script>

@endsection
