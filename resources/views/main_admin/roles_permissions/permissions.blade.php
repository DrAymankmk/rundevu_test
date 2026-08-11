<?php $page = 'permissions'; ?>
@extends('layout_new.mainlayout')
@section('content')

    <div class="page-wrapper">
        <!-- Start Content -->
        <div class="content">

            <!-- Back to Roles -->
            <h6 class="fs-14 mb-3">
                <a href="{{ route('roles.index') }}">
                    <i class="ti ti-chevron-left me-1"></i>@lang('admin.Roles')
                </a>
            </h6>

            <!-- Page Header -->
            <div class="d-flex align-items-sm-center flex-sm-row flex-column gap-2 mb-3 pb-3 border-bottom">
                <div class="flex-grow-1">
                    <h4 class="fw-bold mb-0">@lang('admin.Permissions')</h4>
                </div>
                <div class="text-end d-flex">
                    <div class="dropdown">
                        <a href="javascript:void(0);"
                           class="dropdown-toggle btn bg-white btn-md d-inline-flex align-items-center fw-normal rounded border text-dark px-2 py-1 fs-14"
                           data-bs-toggle="dropdown">
                            <span class="text-body me-1">@lang('admin.Role') :</span>
                            {{ $role->name ?? '' }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end p-2">
                            @foreach(\Spatie\Permission\Models\Role::all() as $r)
                                <li>
                                    <a href="{{ route('roles.show', $r->id) }}" class="dropdown-item rounded-1">
                                        {{ $r->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            <!-- End Page Header -->

            <!-- Permissions List -->
            <form id="permissions-form" action="{{ route('roles.permissions.update', $role->id) }}" method="POST">
                @csrf
                @method('PUT')

                @foreach($permissions->get() as $permission)
                    <div class="card mb-3">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6 class="fw-bold mb-0">
                                {{ app()->getLocale() == 'en' ? $permission->name_en : $permission->name_ar }}
                            </h6>
                            <div class="form-check">
                                <input id="select-all-{{ $permission->id }}"
                                       class="form-check-input select-all"
                                       type="checkbox"
                                       data-group="group-{{ $permission->id }}">
                                <label>@lang('admin.Allow All')</label>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive border">
                                <table class="table table-bordered text-center align-middle">
                                    <thead class="thead-light">
                                    <tr>
                                        @foreach($permission->child as $child)
                                            <th>{{ app()->getLocale() == 'en' ? $child->name_en : $child->name_ar }}</th>
                                        @endforeach
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        @foreach($permission->child as $child)
                                            <td>
                                                <div class="form-check d-flex justify-content-center">
                                                    <input class="form-check-input child-permission group-{{ $permission->id }}"
                                                           type="checkbox"
                                                           name="permissions[]"
                                                           value="{{ $child->id }}"
                                                           data-role="{{ $role->id }}"
                                                           data-id="{{ $child->id }}"
                                                           data-name="{{ $child->name }}"
                                                           data-group="group-{{ $permission->id }}"
                                                        {{ $role->permissions->contains('id', $child->id) ? 'checked' : '' }}>
                                                </div>
                                            </td>
                                        @endforeach
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endforeach

                <div class="text-center mt-3">
                    <button type="submit" class="btn btn-primary px-5">@lang('admin.Save')</button>
                </div>
            </form>
            <!-- End Permissions List -->

        </div>
        <!-- End Content -->

        @component('components.footer') @endcomponent
    </div>


    <script>
        document.addEventListener("DOMContentLoaded", function () {

            // === Allow All toggle ===
            document.querySelectorAll('.select-all').forEach(selectAll => {
                selectAll.addEventListener('change', function () {
                    let groupId = this.dataset.group;
                    let checkboxes = document.querySelectorAll('[data-group="' + groupId + '"]');
                    checkboxes.forEach(cb => {
                        cb.checked = this.checked;
                        cb.dispatchEvent(new Event('change')); // trigger ajax
                    });
                });
            });

            // === Single child checkbox AJAX ===
            document.querySelectorAll('.child-permission').forEach(cb => {
                cb.addEventListener('change', function () {
                    let roleId = this.dataset.role;
                    let permissionId = this.dataset.id;
                    let checked = this.checked;

                    fetch("{{ route('roles.permissions.update', $role->id) }}", {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify({
                            permission_id: permissionId,
                            checked: checked
                        })
                    })
                        .then(res => res.json())
                        .then(data => {
                            console.log("Updated:", data);
                        })
                        .catch(err => console.error(err));
                });
            });

        });
    </script>

@endsection
