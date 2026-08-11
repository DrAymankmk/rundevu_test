<?php $page = "supervisors"; ?>
@extends('layout_new.mainlayout')

@section('content')
    <div class="page-wrapper">
        <div class="content">

            <div class="page-title">
                <h4>@lang('admin.supervisors')</h4>
            </div>

            {{-- زرار إضافة مشرف جديد --}}
            <div class="mb-3">
                <a href="javascript:void(0)" class="btn btn-primary" data-bs-toggle="modal"
                   data-bs-target="#addSupervisor">
                    <i class="ti ti-plus"></i> @lang('admin.add_supervisor')
                </a>
            </div>

            {{-- جدول عرض المشرفين --}}
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-nowrap">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>@lang('admin.image')</th>
                                <th>@lang('admin.name')</th>
                                <th>@lang('main.email')</th>
                                <th>@lang('main.phone')</th>
                                <th>@lang('main.city')</th>
                                <th>@lang('admin.status')</th>
                                <th>@lang('admin.role')</th>
                                <th>@lang('admin.options')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($data['supervisors'] as $index => $supervisor)
                                <tr>
                                    <td>{{ $index+1 }}</td>
                                    <td>
                                        <img src="{{ $supervisor->image ? asset($supervisor->image) : asset('images/default.png') }}"
                                             class="rounded-circle" width="40" height="40">
                                    </td>
                                    <td>{{ $supervisor->name }}</td>
                                    <td>{{ $supervisor->email }}</td>
                                    <td>{{ $supervisor->phone }}</td>
                                    <td>
                                        {{ app()->getLocale() == 'en' ? $supervisor->city->name_en ?? '-' : $supervisor->city->name_ar ?? '-' }}
                                    </td>
                                    <td>
                                        @if($supervisor->status)
                                            <span class="badge bg-success">@lang('admin.Active')</span>
                                        @else
                                            <span class="badge bg-danger">@lang('admin.Inactive')</span>
                                        @endif
                                    </td>
                                    <td>

                                            <span class="badge bg-info">
                                                {{ app()->getLocale() == 'en' ? $supervisor->role->name ?? "-" : $supervisor->role->name_ar ?? "-" }}
                                            </span>
                                    </td>
                                    <td>
                                        <a href="javascript:void(0)" data-bs-toggle="modal"
                                           data-bs-target="#editSupervisor{{ $supervisor->id }}"
                                           class="btn btn-sm btn-primary">
                                            <i class="ti ti-pencil"></i>
                                        </a>
                                        <a href="javascript:void(0)" data-bs-toggle="modal"
                                           data-bs-target="#deleteSupervisor{{ $supervisor->id }}"
                                           class="btn btn-sm btn-danger">
                                            <i class="ti ti-trash"></i>
                                        </a>
                                    </td>
                                </tr>

                                {{-- Edit Modal --}}
                                <div class="modal fade" id="editSupervisor{{ $supervisor->id }}">
                                    <div class="modal-dialog modal-lg modal-dialog-centered">
                                        <div class="modal-content">
                                            <form action="{{ route('update-supervisor',$supervisor->id) }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-header">
                                                    <h5 class="modal-title">@lang('admin.edit_supervisor')</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body row g-3">
                                                    <div class="col-md-6">
                                                        <label>@lang('admin.name')</label>
                                                        <input type="text" name="name" class="form-control" value="{{ $supervisor->name }}" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label>@lang('main.email')</label>
                                                        <input type="email" name="email" class="form-control" value="{{ $supervisor->email }}" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label>@lang('main.phone')</label>
                                                        <input type="text" name="phone" class="form-control" value="{{ $supervisor->phone }}" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label>@lang('main.city')</label>
                                                        <select name="city_id" class="form-select" required>
                                                            @foreach($data['cities'] as $city)
                                                                <option value="{{ $city->id }}" {{ $supervisor->city_id == $city->id ? 'selected' : '' }}>
                                                                    {{ app()->getLocale() == 'en' ? $city->name_en : $city->name_ar }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label>@lang('admin.password') (@lang('admin.leave_empty_if_not_changed'))</label>
                                                        <input type="password" name="password" class="form-control">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label>@lang('admin.image')</label>
                                                        <input type="file" name="image" class="form-control">
                                                    </div>
                                                    <div class="col-md-12">
                                                        <label>@lang('admin.role')</label>
                                                        <select name="role_id" class="form-control"  required>
                                                            @foreach($data['roles'] as $role)
                                                                <option value="{{ $role->id }}" {{ $supervisor->roles->contains($role->id) ? 'selected' : '' }}>
                                                                    {{ app()->getLocale() == 'en' ? $role->name : $role->name_ar }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">@lang('admin.close')</button>
                                                    <button type="submit" class="btn btn-primary">@lang('admin.save')</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                {{-- Delete Modal --}}
                                <div class="modal fade" id="deleteSupervisor{{ $supervisor->id }}">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <form action="{{ route('destroy-Account',$supervisor->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <div class="modal-body text-center">
                                                    <p>@lang('admin.confirm_delete')</p>
                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">@lang('admin.cancel')</button>
                                                    <button type="submit" class="btn btn-danger">@lang('admin.delete')</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Add Modal --}}
            <div id="addSupervisor" class="modal fade">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <form action="{{ route('create-supervisor') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title">@lang('admin.add_supervisor')</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body row g-3">
                                <div class="col-md-6">
                                    <label>@lang('admin.name')</label>
                                    <input type="text" name="name" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label>@lang('main.email')</label>
                                    <input type="email" name="email" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label>@lang('main.phone')</label>
                                    <input type="text" name="phone" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label>@lang('main.city')</label>
                                    <select name="city_id" class="form-select" required>
                                        @foreach($data['cities'] as $city)
                                            <option value="{{ $city->id }}">{{ $city->name_ar }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label>@lang('admin.password')</label>
                                    <input type="password" name="password" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label>@lang('admin.image')</label>
                                    <input type="file" name="image" class="form-control">
                                </div>
                                <div class="col-md-12">
                                    <label>@lang('admin.role')</label>
                                    <select name="role_id" class="form-control" required>
                                        @foreach($data['roles'] as $role)
                                            <option value="{{ $role->id }}"> {{ app()->getLocale() == 'en' ? $role->name : $role->name_ar }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">@lang('admin.close')</button>
                                <button type="submit" class="btn btn-primary">@lang('admin.save')</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
