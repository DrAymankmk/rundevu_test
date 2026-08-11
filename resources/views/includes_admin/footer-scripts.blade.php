@if(in_array(auth()->user()->app_type, [1, 2, 5, 6, 7, 8, 9, 10, 25, 26]))
    {{--    <script>--}}
    {{--        function uploadImage(e) {--}}
    {{--            document.getElementById('avatar').src = URL.createObjectURL(e.target.files[0]);--}}
    {{--        }--}}
    {{--    </script>--}}

    <!-- jQuery -->
    <script src="/reception/assets/js/jquery-3.6.1.min.js"></script>

    <!-- Bootstrap Core JS -->
    <script src="/reception/assets/js/bootstrap.bundle.min.js"></script>

    <!-- Feather Js -->
    <script src="/reception/assets/js/feather.min.js"></script>

    <!-- Slimscroll -->
    <script src="/reception/assets/js/jquery.slimscroll.js"></script>


    <script src="/reception/assets/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="/reception/assets/plugins/datatables/datatables.min.js"></script>

    @if(Request::segment(2) == 'patients')
        <script src="/reception/assets/js/qr-code.js"></script>
    @endif

    <!-- Select2 Js -->
    <script src="/reception/assets/js/select2.min.js"></script>


{{--    @if(Request::segment(2) == 'add-patient' || Request::segment(2) == 'edit-patient' || (Request::segment(2) == 'appointments') ))--}}
        <!-- Datepicker Core JS -->
        <script src="/reception/assets/plugins/moment/moment.min.js"></script>
        <script src="/reception/assets/js/bootstrap-datetimepicker.min.js"></script>
{{--    @endif--}}

    @if(Request::segment(2) != 'add-patient' || Request::segment(2) != 'edit-patient'))
        <!-- Datatables JS -->
{{--        <script src="/reception/assets/plugins/datatables/jquery.dataTables.min.js"></script>--}}
{{--        <script src="/reception/assets/plugins/datatables/datatables.min.js"></script>--}}

        <!-- counterup JS -->
        <script src="/reception/assets/js/jquery.waypoints.js"></script>
        <script src="/reception/assets/js/jquery.counterup.min.js"></script>

        <!-- Apexchart JS -->
        <script src="/reception/assets/plugins/apexchart/apexcharts.min.js"></script>
        <script src="/reception/assets/plugins/apexchart/chart-data.js"></script>
    @endif

{{--    @if((Request::segment(2) == 'attachments') || (Request::segment(2) == 'appointments') )--}}
{{--        <script src="/reception/assets/plugins/datatables/jquery.dataTables.min.js"></script>--}}
{{--        <script src="/reception/assets/plugins/datatables/datatables.min.js"></script>--}}
{{--    @endif--}}


{{--    <script src="/reception/assets/plugins/moment/moment.min.js"></script>--}}
{{--    <script src="/reception/assets/js/bootstrap-datetimepicker.min.js"></script>--}}




    <!-- Custom JS -->
    <script src="/reception/assets/js/app.js"></script>

    <script src="{{asset('admin/js/axios.js')}}"></script>
    <script src="{{asset('/admin/js/form-validation-custom.js')}}"></script>

@else
    <!-- jQuery -->
    <script src="/assets/js/jquery-3.6.1.min.js"></script>

    <!-- Bootstrap Core JS -->
    <script src="/assets/js/bootstrap.bundle.min.js"></script>

    <!-- Feather Js -->
    <script src="/assets/js/feather.min.js"></script>

    <!-- Slimscroll -->
    <script src="/assets/js/jquery.slimscroll.js"></script>

    <!-- Select2 Js -->
    <script src="/assets/js/select2.min.js"></script>

    <!-- Datatables JS -->
    <script src="/assets/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="/assets/plugins/datatables/datatables.min.js"></script>

    {{--@if( Request::segment(2) == 'notifications')--}}
    <!-- Datepicker Core JS -->
    <script src="/assets/plugins/moment/moment.min.js"></script>
    <script src="/assets/js/bootstrap-datetimepicker.min.js"></script>
    {{--@endif--}}

    <!-- counterup JS -->
    <script src="/assets/js/jquery.waypoints.js"></script>
    <script src="/assets/js/jquery.counterup.min.js"></script>

    <!-- Apexchart JS -->
    <script src="/assets/plugins/apexchart/apexcharts.min.js"></script>
    <script src="/assets/plugins/apexchart/chart-data.js"></script>

    <!-- Circle Progress JS -->
    <script src="/assets/js/circle-progress.min.js"></script>

    <!-- Custom JS -->
    <script src="/assets/js/app.js"></script>

@endif

<link rel="stylesheet" href="{{ asset('/admin/js/noty/noty.css') }}">
<script src="{{ asset('/admin/js/noty/noty.min.js') }}"></script>

<script src="{{asset('admin/js/notify/bootstrap-notify.min.js')}}"></script>


@if (session('success'))

    <script>
        new Noty({
            type: 'success',
            layout: 'topRight',
            text: "{{ session('success') }}",
            timeout: 2000,
            killer: true
        }).show();
    </script>
@endif

@if (session('error'))
    <script>
        new Noty({
            type: 'error',
            layout: 'topRight',
            text: "{{ session('error') }}",
            timeout: 2000,
            killer: true
        }).show();
    </script>
@endif

@if (session('failed'))
    <script>
        new Noty({
            type: 'error',
            layout: 'topRight',
            text: "{{ session('failed') }}",
            timeout: 2000,
            killer: true
        }).show();
    </script>
@endif

@if ($errors->any())
    @foreach ($errors->all() as $error)
        <script>
            new Noty({
                type: 'error',
                layout: 'topRight',
                text: "{{ $error }}",
                timeout: 3000,
                killer: true
            }).show();
        </script>
    @endforeach
@endif


<script>
    $(document).ready(function () {
        if ($.fn.DataTable) {
            $('.page-body table').each(function () {
                var table = this;
                var $table = $(table);

                if ($table.closest('.modal').length || $.fn.DataTable.isDataTable(table)) {
                    return;
                }

                var headerCount = $table.find('thead tr').last().children('th, td').length;
                var hasInvalidBodyRow = false;

                $table.find('tbody tr').each(function () {
                    var $cells = $(this).children('th, td');

                    if ($cells.length !== headerCount || $cells.filter('[colspan], [rowspan]').length) {
                        hasInvalidBodyRow = true;
                        return false;
                    }
                });

                if (headerCount && $table.find('tbody tr').length && !hasInvalidBodyRow) {
                    $table.addClass('table border-0 custom-table comman-table mb-0');
                    $table.DataTable({
                        retrieve: true,
                        responsive: true,
                        autoWidth: false,
                        pageLength: 10,
                        dom: '<"datatable-top row align-items-center mb-3"<"col-12 col-md-6"l><"col-12 col-md-6"f>>rt<"datatable-bottom row align-items-center mt-3"<"col-12 col-md-5"i><"col-12 col-md-7"p>>',
                        language: {
                            search: "",
                            searchPlaceholder: "{{ trans('admin.search') }}",
                            lengthMenu: "_MENU_",
                            info: "_START_ - _END_ / _TOTAL_",
                            paginate: {
                                next: "{{ app()->getLocale() == 'ar' ? 'التالي' : 'Next' }}",
                                previous: "{{ app()->getLocale() == 'ar' ? 'السابق' : 'Previous' }}"
                            }
                        }
                    });
                }
            });
        }

        $('form.needs-validation').each(function () {
            $(this).attr('novalidate', 'novalidate');
        });

        if ($.fn.select2) {
            $('.js-example-placeholder-multiple').each(function () {
                var $select = $(this);
                if ($select.hasClass('select2-hidden-accessible')) {
                    return;
                }

                $select.select2({
                    width: '100%',
                    placeholder: "{{ trans('admin.select') }}",
                    allowClear: true,
                    dir: "{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}"
                });
            });
        }

        $(document).on('submit', 'form.needs-validation', function (event) {
            if (!this.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }

            $(this).addClass('was-validated');
        });

        if (window.bootstrap && !$.fn.modal) {
            $.fn.modal = function (action) {
                return this.each(function () {
                    var modal = bootstrap.Modal.getOrCreateInstance(this);
                    if (action === 'hide') {
                        modal.hide();
                    } else if (action === 'toggle') {
                        modal.toggle();
                    } else {
                        modal.show();
                    }
                });
            };
        }

        $(document).on('click', '[data-toggle="modal"]', function (event) {
            var target = $(this).attr('data-target');
            if (!target || !window.bootstrap) {
                return;
            }

            event.preventDefault();
            event.stopPropagation();

            var modalElement = target.charAt(0) === '#'
                ? document.getElementById(target.substring(1))
                : document.querySelector(target);

            if (modalElement) {
                if (modalElement.parentNode !== document.body) {
                    document.body.appendChild(modalElement);
                }

                $('.modal-backdrop').remove();
                bootstrap.Modal.getOrCreateInstance(modalElement).show();
            }
        });

        $(document).on('click', '[data-dismiss="modal"]', function (event) {
            if (!window.bootstrap) {
                return;
            }

            event.preventDefault();
            event.stopPropagation();
            var modalElement = $(this).closest('.modal')[0];
            if (modalElement) {
                bootstrap.Modal.getOrCreateInstance(modalElement).hide();
            }
        });

        $('.delete-confirm').on('click', function(e) {
            e.preventDefault();
            var deleteUrl = $(this).data('delete-url');
            var rowToDelete = $(this).closest('.row-to-delete'); // Assuming each row has a class 'row-to-delete'
            var modal = $(this).closest('.modal');
            // Send AJAX request to delete the row
            $.ajax({
                url: deleteUrl,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    // Remove the row from the UI
                    rowToDelete.remove();
                    modal.modal('hide');

                    new Noty({
                        type: 'success',
                        layout: 'topRight',
                        text: response,
                        timeout: 2000,
                        killer: true
                    }).show();
                    // Optionally, you can show a success message using Noty or any other notification library
                },
                error: function(xhr, status, error) {
                    // Handle error, show error message if necessary
                }
            });
        });

        // if($('.datetimepicker').length > 0) {
        //     $('.datetimepicker').datetimepicker({
        //         format: 'YYYY-MM-DD'
        //     });
        // }
        });
</script>
