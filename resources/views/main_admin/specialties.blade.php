@extends('layout_new.mainlayout')
@section('content')

    <!-- ========================
    Start Page Content
========================= -->

    <div class="page-wrapper">

        <!-- Start Content -->
        <div class="content">

            <!-- Start Page Header -->
            <div class="d-flex align-items-sm-center flex-sm-row flex-column gap-2 mb-3 pb-3 border-bottom">
                <div class="flex-grow-1">
                    <h4 class="fw-bold mb-0">{{ trans('admin.Specializations') }}<span class="badge badge-soft-primary border border-primary fs-13 fw-medium ms-2">{{ trans('admin.Total Specializations') }} : {{ count($data['specializations']) }}</span></h4>
                </div>
                <div class="text-end d-flex">
                    <a href="javascript:void(0);" class="btn btn-primary text-white ms-2 fs-13 btn-md" data-bs-toggle="modal" data-bs-target="#add_specialization"><i class="ti ti-plus me-1"></i>{{ trans('admin.Add New Specialization') }}</a>
                </div>

            </div>
            <!-- End Page Header -->

            <div class=" d-flex align-items-center justify-content-between flex-wrap row-gap-3">
                <div class="search-set mb-3">
                    <div class="d-flex align-items-center flex-wrap gap-2">
                        <div class="table-search d-flex align-items-center mb-0">
                            <div class="search-input">
                                <a href="javascript:void(0);" class="btn-searchset"></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex table-dropdown mb-3 pb-1 right-content align-items-center flex-wrap row-gap-3">
                    <div class="dropdown me-2">
                        <a href="javascript:void(0);" class="btn btn-white fs-14 py-1 bg-white border d-inline-flex text-dark align-items-center" data-bs-toggle="dropdown" data-bs-auto-close="outside">
                            <i class="ti ti-filter text-gray-5 me-1"></i>{{ trans('admin.Filters') }}
                        </a>
                        <div class="dropdown-menu dropdown-lg dropdown-menu-end filter-dropdown p-0" id="filter-dropdown">
                            <div class="d-flex align-items-center justify-content-between border-bottom filter-header">
                                <h5 class="mb-0 fw-bold">{{ trans('admin.Filter') }}</h5>
                                <div class="d-flex align-items-center">
                                    <a href="javascript:void(0);" class="link-danger text-decoration-underline">{{ trans('admin.Clear All') }}</a>
                                </div>
                            </div>
                            <form action="#">
                                <div class="filter-body pb-0">
                                    <div class="mb-3">
                                        <label class="form-label mb-1 text-dark fs-14 fw-medium">{{ trans('admin.date') }}</label>
                                        <div class="input-icon-end position-relative">
                                            <input type="text" class="form-control datetimepicker" name="date_from" placeholder="dd/mm/yyyy">
                                            <span class="input-icon-addon" >
                                                <i class="ti ti-calendar"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="mb-0">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <label class="form-label">{{ trans('admin.status') }}</label>
                                        </div>
                                        <select class="select" name="status" required>
                                            <option value="1" selected>{{ trans('admin.Active') }}</option>
                                            <option value="0">{{ trans('admin.Inactive') }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="filter-footer d-flex align-items-center justify-content-end border-top">
                                    <a href="javascript:void(0);" class="btn btn-light btn-md me-2" id="close-filter">{{ trans('admin.Close') }}</a>
                                    <button type="submit" class="btn btn-primary btn-md">{{ trans('admin.Filter') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="dropdown">
                        <a href="javascript:void(0);" class="dropdown-toggle btn bg-white btn-md d-inline-flex align-items-center fw-normal rounded border text-dark px-2 py-1 fs-14" data-bs-toggle="dropdown">
                            <span  class="me-1"> {{ trans('admin.Sort By') }} : </span>  {{ trans('admin.Recent') }}
                        </a>
                        <ul class="dropdown-menu  dropdown-menu-end p-2">
                            <li>
                                <a href="javascript:void(0);" class="dropdown-item rounded-1">{{ trans('admin.Recent') }}</a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" class="dropdown-item rounded-1">{{ trans('admin.Oldest') }}</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-nowrap datatable">
                    <thead class="thead-light">
                    <tr>
                        <th>{{ trans('admin.name') }}</th>
                        <th>{{ trans('admin.Created Date') }}</th>
                        <th>{{ trans('admin.No of Clinics') }}</th>
                        <th>@lang('admin.numbers_sub_specialization')</th>
                        <th>{{ trans('admin.Status') }}</th>
                        <th>@lang('admin.created_by')</th>
                        <th>{{ trans('admin.Action') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($data['specializations'] as $index=>$specialization)
                        <tr id="row-{{ $specialization->id }}">
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($specialization->imageUrl())
                                        <img src="{{ $specialization->imageUrl() }}" alt="" width="36" height="36" class="rounded-circle me-2" style="object-fit: cover;">
                                    @elseif(filled($specialization->icon))
                                        <span class="avatar avatar-sm bg-light text-primary me-2 d-inline-flex align-items-center justify-content-center">
                                        <i class="{{ $specialization->icon }}"></i>
                                    </span>
                                    @endif
                                    <div>
                                        <h6 class="mb-0 fs-14 fw-semibold"><a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#view_staff">{{ app()->getLocale() == 'en' ? $specialization->name_en : $specialization->name_ar }}</a></h6>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $specialization->created_at->format('d M Y') }}</td>
                            <td>{{ $specialization->clinic_specialties_count ?? 0 }}</td>
                            <td>{{ $specialization->sub_specialties_list_count }}</td>
                            <td><span class="badge @if($specialization->status == 1) badge-soft-success border border-success @else badge-soft-danger border border-danger @endif  px-2 py-1 fs-13 fw-medium">@if($specialization->status == 1) @lang('admin.Active') @else @lang('admin.Inactive') @endif </span></td>
                            <td>{{$specialization->admin->name ?? null}}</td>

                            <td>
                                <a href="{{ route('SubSpecialties', $specialization->id) }}" class="link-reset fs-18 p-1"> <i class="ti ti-eye"></i></a>
                                <a href="javascript: void(0);" class="link-reset fs-18 p-1" data-bs-toggle="modal" data-bs-target="#edit_specialization_{{$specialization->id}}"> <i class="ti ti-pencil"></i></a>
                                <a href="javascript:void(0);"
                                   class="link-reset fs-18 p-1 edit-specialty-seo-btn"
                                   title="{{ __('seo.edit_seo') }}"
                                   data-specialty-name="{{ app()->getLocale() == 'en' ? $specialization->name_en : $specialization->name_ar }}"
                                   data-seo-url="{{ route('main-specialty-seo-form', $specialization->id) }}">
                                    <i class="ti ti-world-www"></i>
                                </a>

                                <a href="javascript:void(0);"
                                   class="link-reset fs-18 p-1 delete-btn"
                                   data-route="{{ route('destroy-mainSpecialty', $specialization->id) }}"
                                   data-id="row-{{ $specialization->id }}"
                                   data-bs-toggle="modal"
                                   data-bs-target="#genericDeleteModal">
                                    <i class="ti ti-trash"></i>
                                </a>
                            </td>
                            <!-- Start Add Modal -->
                            <div id="edit_specialization_{{$specialization->id}}" class="modal fade">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="text-dark modal-title fw-bold">{{ trans('admin.Edit Specialization') }} {{ app()->getLocale() == 'en' ? $specialization->name_en : $specialization->name_ar }}</h4>
                                            <button type="button" class="btn-close btn-close-modal custom-btn-close" data-bs-dismiss="modal" aria-label="Close"><i class="ti ti-x"></i></button>
                                        </div>
                                        <form action="{{ route('update-MainSpecialty',$specialization->id) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">{{ trans('admin.name_ar') }}<span class="text-danger ms-1">*</span></label>
                                                    <input type="text" class="form-control"  placeholder="@lang('admin.name_ar')" name="name_ar"
                                                           value="{{$specialization->name_ar}}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">{{ trans('admin.name_en') }}<span class="text-danger ms-1">*</span></label>
                                                    <input type="text" class="form-control"  placeholder="@lang('admin.name_en')" name="name_en"
                                                           value="{{$specialization->name_en}}" required>
                                                </div>
                                                @include('main_admin.partials.specialty_media_fields', [
                                                    'inputId' => 'specialty_icon_'.$specialization->id,
                                                    'iconValue' => old('icon', $specialization->icon),
                                                    'imageUrl' => $specialization->imageUrl(),
                                                    'showRemove' => true,
                                                ])
                                                <div class="mb-0">
                                                    <label class="form-label">{{ trans('admin.status') }}</label>
                                                    <select class="select" name="status">
                                                        <option value="1" @if($specialization->status == 1) selected @endif>@lang('admin.Active')</option>
                                                        <option value="0" @if($specialization->status == 0) selected @endif>@lang('admin.Inactive')</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="modal-footer d-flex align-items-center gap-1">
                                                <button type="button" class="btn btn-white border" data-bs-dismiss="modal">{{ trans('admin.cancel') }}</button>
                                                <button type="submit" class="btn btn-primary">{{ trans('admin.Save Changes') }}</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- End Add Modal -->

                        </tr>
                    @endforeach

                    </tbody>
                </table>
            </div>

        </div>
        <!-- End Content -->

        @component('components.footer')
        @endcomponent

    </div>



    <!-- ========================
        End Page Content
    ========================= -->
    <!-- Start Add Modal -->
    <div id="add_specialization" class="modal fade">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="text-dark modal-title fw-bold">{{ trans('admin.Add New Specialization') }}</h4>
                    <button type="button" class="btn-close btn-close-modal custom-btn-close" data-bs-dismiss="modal" aria-label="Close"><i class="ti ti-x"></i></button>
                </div>
                <form id="add_department_form" action="{{ route('add-mainSpecialty') }}"
                      method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">{{ trans('admin.name_ar') }}<span class="text-danger ms-1">*</span></label>
                            <input type="text" class="form-control"  placeholder="@lang('admin.name_ar')" name="name_ar"
                                   value="{{old('name_ar')}}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">{{ trans('admin.name_en') }}<span class="text-danger ms-1">*</span></label>
                            <input type="text" class="form-control"  placeholder="@lang('admin.name_en')" name="name_en"
                                   value="{{old('name_en')}}" required>
                        </div>

                        @include('main_admin.partials.specialty_media_fields', [
                            'inputId' => 'specialty_icon_create',
                            'iconValue' => old('icon'),
                        ])

                        <div class="mb-0">
                            <label class="form-label">{{ trans('admin.status') }}</label>
                            <select class="select" name="status" required>
                                <option value="1" selected>@lang('admin.Active')</option>
                                <option value="0">@lang('admin.Inactive')</option>
                            </select>
                        </div>

                    </div>
                    <div class="modal-footer d-flex align-items-center gap-1">
                        <button type="button" class="btn btn-light border" data-bs-dismiss="modal">{{ trans('admin.cancel') }}</button>
                        <button type="submit" class="btn btn-primary">{{ trans('admin.Add Specialization') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- End Add Modal -->

    @include('components.icon-picker', [
        'renderSharedModal' => true,
        'sharedModalId' => 'specialtyIconPicker',
    ])

    @include('components.rich-text-editor-assets')

    <div class="modal fade" id="specialtySeoModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title me-auto">
                        {{ __('seo.edit_seo') }}
                        <span class="text-muted fs-14" id="specialtySeoModalSubtitle"></span>
                    </h5>
                    @if(app()->environment('local'))
                        <button type="button" class="btn btn-sm btn-outline-warning me-2" id="fillSpecialtySeoTestData">
                            {{ __('seo.fill_test_data') }}
                        </button>
                    @endif
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="specialtySeoModalBody">
                    <p class="text-muted mb-0">@lang('main.loading')</p>
                </div>
            </div>
        </div>
    </div>



    <!-- General Delete Modal -->
    <div class="modal fade" id="genericDeleteModal">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <form id="genericDeleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-body text-center position-relative z-1">
                        <img src="{{ URL::asset('build/img/bg/delete-modal-bg-01.png') }}" class="img-fluid position-absolute top-0 start-0 z-n1" alt="">
                        <img src="{{ URL::asset('build/img/bg/delete-modal-bg-02.png') }}" class="img-fluid position-absolute bottom-0 end-0 z-n1" alt="">
                        <div class="mb-3">
                            <span class="avatar avatar-lg bg-danger text-white"><i class="ti ti-trash fs-24"></i></span>
                        </div>
                        <h5 class="fw-bold mb-1">{{ trans('admin.Delete Confirmation') }}</h5>
                        <p class="mb-3">{{ trans('admin.Are you sure want to delete?') }}</p>
                        <div class="d-flex justify-content-center">
                            <button type="button" class="btn btn-light me-3" data-bs-dismiss="modal">{{ trans('admin.Cancel') }}</button>
                            <button type="submit" class="btn btn-danger" id="confirmDeleteBtn">{{ trans('admin.Yes, Delete') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const specialtySeoModalEl = document.getElementById('specialtySeoModal');
                const specialtySeoModalBody = document.getElementById('specialtySeoModalBody');
                const specialtySeoModalSubtitle = document.getElementById('specialtySeoModalSubtitle');
                const specialtySeoModal = specialtySeoModalEl && window.bootstrap
                    ? bootstrap.Modal.getOrCreateInstance(specialtySeoModalEl)
                    : null;

                document.addEventListener('click', function (event) {
                    const trigger = event.target.closest('.edit-specialty-seo-btn');
                    if (!trigger || !specialtySeoModal || !specialtySeoModalBody) {
                        return;
                    }

                    event.preventDefault();
                    const seoUrl = trigger.getAttribute('data-seo-url');
                    const specialtyName = trigger.getAttribute('data-specialty-name') || '';
                    if (specialtySeoModalSubtitle) {
                        specialtySeoModalSubtitle.textContent = specialtyName ? ` - ${specialtyName}` : '';
                    }
                    if (typeof window.teardownCmsQuillRoot === 'function') {
                        specialtySeoModalBody.querySelectorAll('.cms-quill-root').forEach(function (wrap) {
                            window.teardownCmsQuillRoot(wrap);
                        });
                    }
                    specialtySeoModalBody.innerHTML = `<p class="text-muted mb-0">{{ trans('main.loading') }}</p>`;
                    specialtySeoModal.show();

                    fetch(seoUrl, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'text/html',
                        },
                    })
                        .then(function (res) {
                            if (!res.ok) {
                                throw new Error('Failed to load SEO form');
                            }
                            return res.text();
                        })
                        .then(function (html) {
                            specialtySeoModalBody.innerHTML = html;
                            if (typeof window.initCmsQuillRoots === 'function') {
                                window.initCmsQuillRoots(specialtySeoModalBody);
                            }
                        })
                        .catch(function () {
                            specialtySeoModalBody.innerHTML = `<div class="text-danger">{{ __('cms.an_error_occurred') }}</div>`;
                        });
                });

                @if(app()->environment('local'))
                const fillSpecialtySeoTestData = document.getElementById('fillSpecialtySeoTestData');
                if (fillSpecialtySeoTestData) {
                    fillSpecialtySeoTestData.addEventListener('click', function () {
                        fillSpecialtySeoForm(specialtySeoModalBody);
                    });
                }
                @endif
            });

            @if(app()->environment('local'))
            function fillSpecialtySeoForm(root) {
                if (!root || !root.querySelector('form')) {
                    return;
                }

                const samples = {
                    en: {
                        meta_title: 'Test: Brain and Nerve Doctors',
                        meta_description: '<p>Test meta description for brain and nerve doctors.</p>',
                        meta_keywords: 'test, neurology, brain, nerve',
                        module_description: '<p>Local test module text shown above the doctor list for this specialty.</p>',
                        og_title: 'Test OG title',
                        og_description: 'Test Open Graph description for this specialty.',
                        twitter_title: 'Test Twitter title',
                        twitter_description: 'Test Twitter description for this specialty.',
                        schema_json: JSON.stringify({
                            '@context': 'https://schema.org',
                            '@type': 'WebPage',
                            'name': 'Test: Brain and Nerve Doctors',
                            'description': 'Local SEO test data'
                        }, null, 2)
                    },
                    ar: {
                        meta_title: 'تجريبي: أطباء المخ والأعصاب',
                        meta_description: '<p>وصف ميتا تجريبي لأطباء المخ والأعصاب.</p>',
                        meta_keywords: 'تجريبي, مخ, أعصاب',
                        module_description: '<p>نص تجريبي يظهر أعلى قائمة الأطباء لهذا التخصص.</p>',
                        og_title: 'عنوان OG تجريبي',
                        og_description: 'وصف Open Graph تجريبي لهذا التخصص.',
                        twitter_title: 'عنوان تويتر تجريبي',
                        twitter_description: 'وصف تويتر تجريبي لهذا التخصص.',
                        schema_json: JSON.stringify({
                            '@context': 'https://schema.org',
                            '@type': 'WebPage',
                            'name': 'تجريبي: أطباء المخ والأعصاب',
                            'description': 'بيانات SEO تجريبية'
                        }, null, 2)
                    }
                };

                function sampleFor(locale) {
                    if (samples[locale]) {
                        return samples[locale];
                    }

                    return {
                        meta_title: 'Test meta title (' + locale + ')',
                        meta_description: '<p>Test meta description (' + locale + ').</p>',
                        meta_keywords: 'test, ' + locale,
                        module_description: '<p>Test module description (' + locale + ').</p>',
                        og_title: 'Test OG title (' + locale + ')',
                        og_description: 'Test OG description (' + locale + ').',
                        twitter_title: 'Test Twitter title (' + locale + ')',
                        twitter_description: 'Test Twitter description (' + locale + ').',
                        schema_json: samples.en.schema_json
                    };
                }

                function setNamed(name, value) {
                    const field = root.querySelector('input[type="checkbox"][name="' + name + '"]')
                        || root.querySelector('[name="' + name + '"]:not([type="hidden"]):not([type="file"])');
                    if (!field || field.type === 'file' || field.type === 'hidden') {
                        return;
                    }
                    if (field.type === 'checkbox') {
                        field.checked = value === true || value === '1' || value === 1;
                        field.dispatchEvent(new Event('change', { bubbles: true }));
                        return;
                    }
                    field.value = value;
                    field.dispatchEvent(new Event('input', { bubbles: true }));
                    field.dispatchEvent(new Event('change', { bubbles: true }));
                    const editor = field.closest('.cms-quill-root');
                    const quill = editor ? editor.querySelector('.ql-editor') : null;
                    if (quill) {
                        quill.innerHTML = value;
                    }
                }

                setNamed('seo[canonical_url]', window.location.origin + '/doctors/brain-and-nerve-diseases');
                setNamed('seo[robots]', 'index,follow');
                setNamed('seo[og_type]', 'website');
                setNamed('seo[is_active]', '1');

                const locales = new Set();
                root.querySelectorAll('[name^="seo[translations]["]').forEach(function (field) {
                    const match = field.name.match(/^seo\[translations\]\[([^\]]+)\]/);
                    if (match) {
                        locales.add(match[1]);
                    }
                });

                locales.forEach(function (locale) {
                    const sample = sampleFor(locale);
                    Object.keys(sample).forEach(function (key) {
                        setNamed('seo[translations][' + locale + '][' + key + ']', sample[key]);
                    });
                });

                const imageInput = root.querySelector('input[type="file"][name="seo[og_image_file]"]');
                if (imageInput && typeof DataTransfer !== 'undefined') {
                    const canvas = document.createElement('canvas');
                    canvas.width = 120;
                    canvas.height = 63;
                    const context = canvas.getContext('2d');
                    context.fillStyle = '#3E66F3';
                    context.fillRect(0, 0, canvas.width, canvas.height);
                    context.fillStyle = '#ffffff';
                    context.font = '16px sans-serif';
                    context.fillText('OG test', 22, 38);
                    canvas.toBlob(function (blob) {
                        if (!blob) {
                            return;
                        }
                        const transfer = new DataTransfer();
                        transfer.items.add(new File([blob], 'og-test.png', { type: 'image/png' }));
                        imageInput.files = transfer.files;
                    }, 'image/png');
                }
            }
            @endif
        </script>
    @endpush


@endsection
