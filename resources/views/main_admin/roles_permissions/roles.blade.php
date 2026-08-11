<?php $page = 'roles-and-permissions'; ?>
@extends('layout_new.mainlayout')
@section('content')

    <div class="page-wrapper">
        <div class="content">

            <!-- Start Page Header -->
            <div class="d-flex align-items-sm-center flex-sm-row flex-column gap-2 mb-3 pb-3 border-bottom">
                <div class="flex-grow-1">
                    <h4 class="fw-bold mb-0">@lang('admin.Roles')</h4>
                </div>
                <div class="text-end d-flex">
                    <a href="javascript:void(0);" class="btn btn-primary ms-2 fs-13 btn-md" data-bs-toggle="modal" data-bs-target="#add_role">
                        <i class="ti ti-plus me-1"></i>@lang('admin.New Role')
                    </a>
                </div>
            </div>
            <!-- End Page Header -->

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-nowrap">
                    <thead class="thead-light">
                    <tr>
                        <th>@lang('admin.Role')</th>
                        <th>@lang('admin.Created On')</th>
                        <th>@lang('admin.Status')</th>
                        <th>@lang('admin.Actions')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($roles as $role)
                        <tr>
                            <td>{{ $role->name }}</td>
                            <td>{{ $role->created_at->format('d M Y') }}</td>
                            <td>
                                @if($role->status == 1)
                                    <span class="badge badge-soft-success border border-success px-2 py-1 fs-13 fw-medium">@lang('admin.Active')</span>
                                @else
                                    <span class="badge badge-soft-danger border border-danger px-2 py-1 fs-13 fw-medium">@lang('admin.Inactive')</span>
                                @endif
                            </td>
                            <td class="d-flex gap-2">
                                <!-- Permissions -->
                                <a href="{{ route('roles.show',$role->id) }}" class="btn btn-white border text-dark">
                                    <i class="ti ti-shield-half me-1"></i>@lang('admin.Permissions')
                                </a>

                                <!-- Edit -->
                                <a href="javascript:void(0);" class="btn btn-warning text-white" data-bs-toggle="modal" data-bs-target="#edit_role_{{ $role->id }}">
                                    <i class="ti ti-edit me-1"></i>@lang('admin.Edit')
                                </a>

                                <!-- Delete -->
                                <form action="{{ route('roles.destroy',$role->id) }}" method="POST" onsubmit="return confirm('@lang('admin.Are you sure?')')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger text-white">
                                        <i class="ti ti-trash me-1"></i>@lang('admin.Delete')
                                    </button>
                                </form>
                            </td>
                        </tr>

                        <!-- Edit Role Modal -->
                        <div class="modal fade" id="edit_role_{{ $role->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <form method="POST" action="{{ route('roles.update',$role->id) }}">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">@lang('admin.Edit Role')</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label>@lang('admin.Name')</label>
                                                <input type="text" name="name" class="form-control" value="{{ $role->name }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label>@lang('admin.Name (Arabic)')</label>
                                                <input type="text" name="name_ar" class="form-control" value="{{ $role->name_ar }}">
                                            </div>
                                            <div class="mb-3">
                                                <label>@lang('admin.Status')</label>
                                                <select name="status" class="form-control">
                                                    <option value="1" @if($role->status == 1) selected @endif>@lang('admin.Active')</option>
                                                    <option value="0" @if($role->status == 0) selected @endif>@lang('admin.Inactive')</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-primary">@lang('admin.Update')</button>
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">@lang('admin.Close')</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- End Edit Modal -->
                    @endforeach
                    </tbody>
                </table>
            </div>
            <!-- End Table -->

        </div>

        @component('components.footer')
        @endcomponent

    </div>

    <!-- Add Role Modal -->
    <div class="modal fade" id="add_role" tabindex="-1">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('roles.store') }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">@lang('admin.New Role')</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>@lang('admin.Name')</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>@lang('admin.Name (Arabic)')</label>
                            <input type="text" name="name_ar" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label>@lang('admin.Status')</label>
                            <select name="status" class="form-control">
                                <option value="1">@lang('admin.Active')</option>
                                <option value="0">@lang('admin.Inactive')</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">@lang('admin.Save')</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">@lang('admin.Close')</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- End Add Role Modal -->

@endsection
