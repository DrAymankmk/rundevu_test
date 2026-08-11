<!DOCTYPE html>
@if(app()->getLocale() == 'en')
    {{--@if (!Route::is(['layout-mini', 'layout-hidden', 'layout-hover-view', 'layout-full-width', 'layout-rtl']))--}}
    <html lang="en" dir="ltr">
    {{--@endif--}}
    @endif

    @if (Route::is(['layout-hidden']))
        <html lang="en" data-layout="hidden">
        @endif

        @if (Route::is(['layout-hover-view']))
            <html lang="en" data-layout="hoverview">
            @endif

            @if (Route::is(['layout-mini']))
                <html lang="en" data-layout="mini">
                @endif


                @if(app()->getLocale() == 'ar')
                    <html lang="ar" dir="rtl">
                    @endif

                    @if (Route::is(['layout-full-width']))
                        <html lang="en" data-layout="full-width">
                        @endif

                        @include('layout_new.partials.title-meta')

                        @stack('styles')

                        @if (!Route::is(['layout-mini']))

                            <body>
                            @endif

                            @if (Route::is(['layout-mini']))

                                <body class="mini-sidebar">
                                @endif

                                <!-- Start Main Wrapper -->
                                @if(!Route::is(['login-basic', 'login-illustration', 'login-cover', 'login', 'register-basic',
                                'register-illustration', 'register-cover', 'forgot-password-basic', 'forgot-password-illustration',
                                'forgot-password-cover', 'reset-password-basic', 'reset-password-illustration',
                                'reset-password-cover', 'email-verification-basic', 'email-verification-illustration',
                                'email-verification-cover', 'success-basic', 'success-illustration', 'success-cover',
                                'two-step-verification-basic', 'two-step-verification-illustration', 'two-step-verification-cover',
                                'lock-screen', 'error-404', 'error-500', 'coming-soon', 'under-maintenance']))
                                    <div class="main-wrapper">
                                        @endif

                                        @if (Route::is(['login']))
                                            <div class="main-wrapper auth-bg auth-bg-custom position-relative overflow-hidden">
                                                @endif

                                                @if (Route::is(['coming-soon', 'under-maintenance']))
                                                    <div class="main-wrapper auth-bg">
                                                        @endif

                                                        @if(Route::is(['login-basic', 'login-illustration', 'login-cover',
                                                        'register-basic', 'register-illustration', 'register-cover',
                                                        'forgot-password-basic', 'forgot-password-illustration',
                                                        'forgot-password-cover', 'reset-password-basic',
                                                        'reset-password-illustration', 'reset-password-cover',
                                                        'email-verification-basic', 'email-verification-illustration',
                                                        'email-verification-cover', 'success-basic', 'success-illustration',
                                                        'success-cover', 'two-step-verification-basic',
                                                        'two-step-verification-illustration', 'two-step-verification-cover',
                                                        'lock-screen', 'error-404', 'error-500']))
                                                            <div class="main-wrapper auth-bg position-relative overflow-hidden">
                                                                @endif

                                                                <link href="{{asset('admin/css/icons.min.css')}}"
                                                                      rel="stylesheet" type="text/css">

                                                                <link rel="stylesheet"
                                                                      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

                                                                @if (!Route::is(['login-basic', 'login-illustration',
                                                                'login-cover', 'login', 'register-basic',
                                                                'register-illustration', 'register-cover',
                                                                'forgot-password-basic', 'forgot-password-illustration',
                                                                'forgot-password-cover', 'reset-password-basic',
                                                                'reset-password-illustration', 'reset-password-cover',
                                                                'email-verification-basic',
                                                                'email-verification-illustration',
                                                                'email-verification-cover', 'success-basic',
                                                                'success-illustration', 'success-cover',
                                                                'two-step-verification-basic',
                                                                'two-step-verification-illustration',
                                                                'two-step-verification-cover', 'lock-screen', 'error-404',
                                                                'error-500', 'coming-soon', 'under-maintenance']))
                                                                    @include('layout_new.partials.header')

                                                                    @include('layout_new.partials.sidebar')
                                                                @endif

                                                                @yield('content')

                                                                {{--        @component('components.modal-popup')--}}
                                                                {{--        @endcomponent--}}

                                                            </div>
                                                            <!-- End Main Wrapper -->



                                                            @include('layout_new.partials.footer-scripts')
                                                            @include('includes_admin.realtime-notification-sound')
                                                            @if (session('success'))
                                                                <div id="successToast"
                                                                     class="toast position-fixed bottom-0 end-0 m-3 bg-success-subtle"
                                                                     style="z-index: 9999;" role="alert" aria-live="assertive"
                                                                     aria-atomic="true" data-bs-delay="5000">
                                                                    <div class="toast-header bg-success text-white">
                                                                        <strong class="me-auto">Success</strong>
                                                                        <button type="button"
                                                                                class="btn-close btn-close-white"
                                                                                data-bs-dismiss="toast"
                                                                                aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="toast-body">
                                                                        {{ session('success') }}
                                                                    </div>
                                                                </div>

                                                                <script>
                                                                    document.addEventListener('DOMContentLoaded', function() {
                                                                        var toastEl = document.getElementById('successToast');
                                                                        if (toastEl) {
                                                                            var toast = new bootstrap.Toast(toastEl);
                                                                            toast.show();
                                                                        }
                                                                    });
                                                                </script>
                                                            @endif


                                                            @if (session('error'))

                                                                <div id="dangerToast"
                                                                     class="toast colored-toast top-0 bg-danger-subtle"
                                                                     role="alert" aria-live="assertive" aria-atomic="true"
                                                                     style="z-index: 9999;">
                                                                    <div class="toast-header bg-danger text-fixed-white">
                                                                        <strong class="me-auto">Toast</strong>
                                                                        <button type="button" class="btn-close"
                                                                                data-bs-dismiss="toast"
                                                                                aria-label="Close"><i
                                                                                class="fa-solid fa-xmark text-white"></i></button>
                                                                    </div>
                                                                    <div class="toast-body">
                                                                        {{ session('error') }}
                                                                    </div>
                                                                </div>

                                                                <script>
                                                                    document.addEventListener('DOMContentLoaded', function() {
                                                                        var toastEl = document.getElementById('dangerToast');
                                                                        if (toastEl) {
                                                                            var toast = new bootstrap.Toast(toastEl);
                                                                            toast.show();
                                                                        }
                                                                    });
                                                                </script>
                                                            @endif

                                                            <script>
                                                                document.addEventListener('DOMContentLoaded', function() {
                                                                    const deleteForm = document.getElementById('genericDeleteForm');

                                                                    if (deleteForm) {
                                                                        document.querySelectorAll('.delete-btn[data-route]').forEach(button => {
                                                                            button.addEventListener('click', function() {
                                                                                const route = this
                                                                                    .getAttribute(
                                                                                        'data-route'
                                                                                    );
                                                                                const rowId = this
                                                                                    .getAttribute(
                                                                                        'data-id'
                                                                                    );
                                                                                deleteForm.setAttribute(
                                                                                    'action',
                                                                                    route
                                                                                );
                                                                                deleteForm.setAttribute(
                                                                                    'data-row-id',
                                                                                    rowId
                                                                                );
                                                                            });
                                                                        });

                                                                        deleteForm.addEventListener('submit', function(e) {
                                                                            e.preventDefault();
                                                                            const rowId = this.getAttribute(
                                                                                'data-row-id');

                                                                            fetch(this.action, {
                                                                                method: 'POST',
                                                                                headers: {
                                                                                    'X-CSRF-TOKEN': this
                                                                                        .querySelector(
                                                                                            '[name=_token]'
                                                                                        )
                                                                                        .value,
                                                                                    'X-Requested-With': 'XMLHttpRequest'
                                                                                },
                                                                                body: new URLSearchParams(
                                                                                    new FormData(
                                                                                        this
                                                                                    )
                                                                                )
                                                                            })
                                                                                .then(response => response.json()
                                                                                    .then(data => ({
                                                                                        ok: response.ok,
                                                                                        data
                                                                                    }))
                                                                                )
                                                                                .then(({ ok, data }) => {
                                                                                    if (!ok) {
                                                                                        throw data;
                                                                                    }
                                                                                    return data;
                                                                                })
                                                                                .then(data => {
                                                                                    if (data
                                                                                        .status
                                                                                    ) {
                                                                                        // حذف العنصر من الصفحة
                                                                                        document.getElementById(
                                                                                            rowId
                                                                                        )
                                                                                            ?.remove();

                                                                                        // إغلاق المودال
                                                                                        const modal =
                                                                                            bootstrap
                                                                                                .Modal
                                                                                                .getInstance(
                                                                                                    document
                                                                                                        .getElementById(
                                                                                                            'genericDeleteModal'
                                                                                                        )
                                                                                                );
                                                                                        modal
                                                                                            .hide();

                                                                                        // تشغيل toast إذا موجود
                                                                                        showToast(data
                                                                                            .message
                                                                                        ); // مثلاً: "تم الحذف بنجاح"
                                                                                    } else {
                                                                                        showToast('حدث خطأ أثناء الحذف ❌',
                                                                                            'danger'
                                                                                        ); // danger
                                                                                    }
                                                                                })
                                                                                .catch(error => {
                                                                                    console.error(
                                                                                        error
                                                                                    );
                                                                                    if (error && error.message) {
                                                                                        showToast(error.message, 'danger');
                                                                                        return;
                                                                                    }
                                                                                    alert(
                                                                                        'حدث خطأ في الاتصال بالسيرفر ❌'
                                                                                    );
                                                                                });
                                                                        });
                                                                    }
                                                                });
                                                            </script>

                                                            <div id="toast-container" class="position-fixed top-0 end-0 p-3"
                                                                 style="z-index: 9999;"></div>

                                                            <script>
                                                                function showToast(message, type = 'success') {
                                                                    const toastContainer = document.getElementById('toast-container');

                                                                    // ✅ تأكد إن العنصر موجود
                                                                    if (!toastContainer) {
                                                                        console.warn('❗️لم يتم العثور على العنصر #toast-container');
                                                                        return;
                                                                    }

                                                                    const id = `toast-${Date.now()}`;
                                                                    const toastHTML = `
            <div id="${id}" class="toast align-items-center text-white bg-${type} border-0 mb-2" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">${message}</div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>`;

                                                                    toastContainer.insertAdjacentHTML('beforeend', toastHTML);
                                                                    const toastEl = document.getElementById(id);
                                                                    const bsToast = new bootstrap.Toast(toastEl);
                                                                    bsToast.show();

                                                                    // ✅ نظف العنصر بعد اختفائه
                                                                    toastEl.addEventListener('hidden.bs.toast', () => toastEl.remove());
                                                                }
                                                            </script>

                                                            <script>
                                                                const languages = {
                                                                    @if(App::getLocale() == 'en')
                                                                    en: {
                                                                        paginate: {
                                                                            previous: "<i class='mdi mdi-chevron-left'></i> Previous",
                                                                            next: "Next <i class='mdi mdi-chevron-right'></i>"
                                                                        },
                                                                        info: "Showing records _START_ to _END_ of _TOTAL_",
                                                                        lengthMenu: "Display _MENU_ records",
                                                                        search: "_INPUT_",
                                                                        searchPlaceholder: @json(__('main.search_by_name')),
                                                                        zeroRecords: "No matching records found",
                                                                        infoEmpty: "No records to display",
                                                                        infoFiltered: "(filtered from _MAX_ total records)"
                                                                    },
                                                                    @else
                                                                    ar: {
                                                                        paginate: {
                                                                            previous: "<i class='mdi mdi-chevron-right'></i> السابق",
                                                                            next: "التالي <i class='mdi mdi-chevron-left'></i>"
                                                                        },
                                                                        info: "عرض السجلات من _START_ إلى _END_ من إجمالي _TOTAL_ سجلات",
                                                                        lengthMenu: "عرض _MENU_ سجلات",
                                                                        search: "_INPUT_",
                                                                        searchPlaceholder: @json(__('main.search_by_name')),
                                                                        zeroRecords: "لا توجد سجلات مطابقة",
                                                                        infoEmpty: "لا توجد سجلات للعرض",
                                                                        infoFiltered: "(تمت التصفية من إجمالي _MAX_ سجلات)"
                                                                    }
                                                                    @endif
                                                                };

                                                                const language = '{{ App::getLocale() }}';

                                                                // Detect system language
                                                                const systemLanguage = navigator.languages ? navigator.languages[0] : navigator.language;
                                                                const isArabic = systemLanguage.startsWith('ar');


                                                                // Listen for keydown events
                                                                document.addEventListener('keydown', function(event) {
                                                                    if ((event.ctrlKey || event.metaKey)) {
                                                                        if (!isArabic && (event.key === 'c' || event.key === 'v')) {
                                                                            event.stopPropagation(); // Allow copy-paste for English layout
                                                                        }
                                                                        const arabicKeyPattern =
                                                                            /[\u0600-\u06FF]/; // Arabic character Unicode range
                                                                        if (arabicKeyPattern.test(event.key)) {
                                                                            console.log("Arabic keyboard detected!");
                                                                            event.stopPropagation(); // Allow copy-paste for Arabic layout
                                                                        }

                                                                    }
                                                                }, true);


                                                                //      document.addEventListener("DOMContentLoaded", function() {
                                                                //          fetchNotifications();
                                                                //      });
                                                            </script>

                                    @stack('scripts')




                                </body>

                        </html>
