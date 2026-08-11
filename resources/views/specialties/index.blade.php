@extends('includes_admin.mainlayout')
@section('content')
    <div class="page-body">
        <div class="container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col">
                        <div class="page-header-left">
                            <h3>@lang('admin.specialties')</h3>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href=" {{route('admin.dashboard')}}"><i
                                            data-feather="home"> </i> {{ trans('admin.dashboard') }}  </a></li>
                                <li class="breadcrumb-item active"> @lang('admin.specialties') </li>
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

                        <div class="card-body">
                            <h5 class="mb-3">@lang('admin.select_specialties')</h5>
                            <p class="text-muted mb-3">@lang('admin.select_specialties_hint')</p>

                            <form action="{{ route('update-bulk-specialties') }}" method="POST">
                                {{ csrf_field() }}
                                {{ method_field('POST') }}

                                <div class="row">
                                    @foreach($data['specializations'] as $specialty)
                                        <div class="col-md-4 col-lg-3 mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox"
                                                       name="specialty_ids[]"
                                                       value="{{ $specialty->id }}"
                                                       id="specialty_{{ $specialty->id }}"
                                                       {{ in_array($specialty->id, $data['clinic_specialty_ids']) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="specialty_{{ $specialty->id }}">
                                                    {{ app()->getLocale() == 'en' ? $specialty->name_en : $specialty->name_ar }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <button class="btn btn-primary mt-3" type="submit">
                                    <i class="fa fa-save"></i> @lang('admin.save')
                                </button>
                            </form>
                        </div>

                    </div>
                </div>

            </div>
        </div>

        @if( count($data['clinic_specializations']) > 0)
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="mb-3">@lang('admin.assigned_specialties')</h5>
                                <div class="table-responsive">
                                    <table class="display dataTable" id="basic-1">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>{{ trans('admin.specialties') }}</th>
                                            <th>{{ trans('admin.status') }}</th>
                                            <th>{{ trans('admin.action') }}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($data['clinic_specializations'] as $index=>$specialty_item)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ app()->getLocale() == 'en' ? $specialty_item->specialties->name_en : $specialty_item->specialties->name_ar }}</td>
                                                <td>
                                                    @if (auth()->user()->app_type != 6 || auth()->user()->hasPermissionTo('specialties_delete'))

                                                    <div class="media-body text-left icon-state">
                                                        <label class="switch">
                                                            <input type="checkbox"
                                                                   {{ $specialty_item->status == 1 ? 'checked' : '' }}
                                                                   onchange="change_status_specialty(this, {{ $specialty_item->id }})"><span
                                                                class="switch-state bg-primary"></span>
                                                        </label>
                                                    </div>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if (auth()->user()->app_type != 6 || auth()->user()->hasPermissionTo('specialties_edit'))

                                                    <button class="btn btn-primary" type="button"
                                                            data-toggle="modal"
                                                            data-target="#{{ $specialty_item->id }}"
                                                            data-whatever="@test"><i class="fa fa-edit"
                                                                                     title="@lang('admin.edit')"></i>
                                                    </button>
                                                    @endif
                                                        @if (auth()->user()->app_type != 6 || auth()->user()->hasPermissionTo('specialties_delete'))

                                                    <form action="{{ route('destroy-specialty',$specialty_item->id) }}"
                                                          method="post" style="display: inline-block">
                                                        {{ csrf_field() }}
                                                        {{ method_field('delete') }}
                                                        <button type="submit" class="btn btn-danger delete btn-sm">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </form>
                                                        @endif

                                                    <div class="modal fade" id="{{ $specialty_item->id }}" tabindex="-1"
                                                         role="dialog"
                                                         aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title"> @lang('admin.edit_data')
                                                                        {{ app()->getLocale() == 'en' ? $specialty_item->specialties->name_en : $specialty_item->specialties->name_ar }} </h5>
                                                                    <button class="close" type="button"
                                                                            data-dismiss="modal"
                                                                            aria-label="Close">
                                                                        <span aria-hidden="true">×</span></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <form class="needs-validation" novalidate=""
                                                                          action="{{route('update-specialty',$specialty_item->id)}}"
                                                                          method="POST"
                                                                          enctype="multipart/form-data">
                                                                        {{ method_field('POST') }}
                                                                        {{ csrf_field() }}


                                                                        <div class="form-group">
                                                                            <label for="specialty_id"
                                                                                   class="page-header-left"> {{trans('admin.specialties')}}</label>
                                                                            <select name="specialty_id" class="form-control" required>
                                                                                <option value=""> {{trans('admin.select')}}</option>
                                                                                @foreach($data['specializations'] as $specialty)
                                                                                    <option
                                                                                        value="{{$specialty->id}}"  @if($specialty_item->specialty_id == $specialty->id)  selected @endif>
                                                                                        {{ app()->getLocale() == 'en' ? $specialty->name_en : $specialty->name_ar }}
                                                                                    </option>
                                                                                @endforeach
                                                                            </select>
                                                                            <span class="text-danger page-header-left"
                                                                                  style="color: red;">{{$errors->first('specialty_id')}}</span>
                                                                        </div>

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
                            {{ $data['clinic_specializations']->links() }}
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>


    <script>

        function change_status_specialty(input, id) {
            const value = input.checked ? 1 : 0;
            input.disabled = true;

            axios.get("{{ url('/admin/update-status-specialty') }}/" + id + "/" + value)
                .then(function (response) {
                    location.reload();
                })
                .catch(function (error) {
                    console.log(error);
                    input.checked = !input.checked;
                    alert(error.response && error.response.data && error.response.data.message
                        ? error.response.data.message
                        : '{{ trans('messages.something_went_wrong') }}');
                })
                .finally(function () {
                    input.disabled = false;
                });
        };


    </script>

@endsection