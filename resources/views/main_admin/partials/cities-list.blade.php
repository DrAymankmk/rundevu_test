@foreach($cities as $index=>$city)
    <tr>
        <td>{{ $index  + 1 }}</td>
        <td><a >{{ $city->name_ar }}</a></td>
        <td><a >{{ $city->name_en }}</a></td>
        <td>
            @if($city->status == 1)
                <button
                    class="custom-badge status-green">@lang('admin.Active')</button>
            @else
                <button
                    class="custom-badge status-red">@lang('admin.In Active')</button>
            @endif
        </td>
        <td>{{$city->admin->name ?? null}}</td>

        <td class="d-flex gap-2">
            <a href="javascript:void(0)" class="add-table-invoice"
               data-bs-toggle="modal"
               data-bs-target="#edit_city_{{$city->id}}"
               title="Edit"><i
                    class="fa fa-pen-to-square"></i></a>
            <a href="javascript:void(0)" class="add-table-invoice danger"
               data-bs-toggle="modal"
               data-bs-target="#delete_city_{{$city->id}}"
               title="Delete"><i class="fa fa-trash-can"></i></a>

            <!-- Delete Department Modal -->
            <div id="delete_city_{{$city->id}}"
                 class="modal fade delete-modal" role="dialog">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <form class="needs-validation" novalidate=""
                              action="{{route('destroy-city',$city->id) }}"
                              method="POST">
                            {{ method_field('delete') }}
                            {{ csrf_field() }}
                            <div class="modal-body text-center">
                                <img src="/assets/img/sent.png" alt=""
                                     width="50" height="46">
                                <h3>@lang('admin.Are you sure you want to delete this?')</h3>
                                <div class="m-t-20"><a href="#"
                                                       class="btn btn-white"
                                                       data-bs-dismiss="modal">@lang('admin.close')</a>
                                    <button type="submit"
                                            class="btn btn-danger">@lang('admin.delete')</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Delete Department Modal End -->

            <!-- Add Department Modal -->
            <div class="modal custom-modal modal-bg fade bank-details"
                 id="edit_city_{{$city->id}}" role="dialog">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-header py-2 px-3">
                            <div class="form-header text-start mb-0">
                                <h4 class="mb-0">@lang('main.edit_city')</h4>
                            </div>
                            <button type="button" class="close"
                                    data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body text-start py-4 px-3">
                            <form id="add_department_form"
                                  action="{{ route('update-city',$city->id) }}"
                                  method="POST">
                                @csrf
                                <div class="bank-inner-details">

                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group local-forms">
                                                <label>@lang('admin.name_ar')
                                                    <span
                                                        class="login-danger">*</span></label>
                                                <input class="form-control"
                                                       placeholder="@lang('admin.name_ar')"
                                                       name="name_ar"
                                                       value="{{$city->name_ar}}"
                                                       required>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="form-group local-forms">
                                                <label>@lang('admin.name_en')
                                                    <span
                                                        class="login-danger">*</span></label>
                                                <input class="form-control"
                                                       placeholder="@lang('admin.name_en')"
                                                       name="name_en"
                                                       value="{{$city->name_en}}"
                                                       required>
                                            </div>
                                        </div>


                                        <div class="col-12">
                                            <div class="form-group local-forms modal-select">
                                                <label>@lang('admin.status') <span class="login-danger">*</span></label>
                                                <select class="form-control select" name="status">
                                                    <option selected="true"
                                                            disabled="disabled">@lang('admin.select') @lang('admin.status')</option>
                                                    <option value="1" @if($city->status == 1) selected @endif>@lang('admin.Active')</option>
                                                    <option value="0" @if($city->status == 0) selected @endif>@lang('admin.In Active')</option>
                                                </select>
                                            </div>
                                        </div>


                                    </div>

                                </div>

                                <div class="modal-footer p-3">

                                    <div class="bank-details-btn">
                                        <a href="javascript:void(0);"
                                           data-bs-dismiss="modal"
                                           class="btn bank-cancel-btn me-2">{{ trans('admin.cancel') }}</a>
                                        <button class="btn bank-save-btn"
                                                type="submit">
                                            {{ trans('admin.edit') }}
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
            <!-- Add Department Modal End -->
        </td>
    </tr>
@endforeach
