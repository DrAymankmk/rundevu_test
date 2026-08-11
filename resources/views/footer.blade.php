

<!-- latest jquery-->
<script src="{{asset('/admin/js/jquery-3.2.1.min.js')}}"></script>
<!-- Bootstrap js-->
<script src="{{asset('/admin/js/bootstrap/popper.min.js')}}"></script>
<script src="{{asset('/admin/js/bootstrap/bootstrap.js')}}"></script>
<!-- feather icon js-->
<script src="{{asset('/admin/js/icons/feather-icon/feather.min.js')}}"></script>
<script src="{{asset('/admin/js/icons/feather-icon/feather-icon.js')}}"></script>
<!-- Sidebar jquery-->
<script src="{{asset('/admin/js/chat-menu.js')}}"></script>

<script src="{{asset('/admin/js/sidebar-menu.js')}}"></script>
<script src="{{asset('/admin/js/config.js')}}"></script>

{{--<script src="{{asset('/admin/js/form-wizard/form-wizard-three.js')}}"></script>--}}
{{--<script src="{{asset('/admin/js/form-wizard/jquery.backstretch.min.js')}}"></script>--}}
<link rel="stylesheet" href="{{ asset('/admin/js/noty/noty.css') }}">

<script src="{{ asset('/admin/js/noty/noty.min.js') }}"></script>

<!-- Plugins JS start-->


<script src="{{ asset('/admin/js/datatable/datatables/jquery.dataTables.min.js')}}"></script>

{{--<script src="{{ asset('js/datatable/datatables/datatable.custom.js')}}"></script>--}}

<script>


    $('#basic-1').dataTable({
        // stateSave: true,
        "language": {
            "sEmptyTable": "ليست هناك بيانات متاحة في الجدول",
            "sLoadingRecords": "جارٍ التحميل...",
            "sProcessing": "جارٍ التحميل...",
            "sLengthMenu": "أظهر _MENU_ مدخلات",
            "sZeroRecords": "لم يعثر على أية سجلات",
            "sInfo": "إظهار _START_ إلى _END_ من أصل _TOTAL_ مدخل",
            "sInfoEmpty": "يعرض 0 إلى 0 من أصل 0 سجل",
            "sInfoFiltered": "(منتقاة من مجموع _MAX_ مُدخل)",
            "sInfoPostFix": "",
            "sSearch": "ابحث:",
            "sUrl": "",
            "oPaginate": {
                "sFirst": "الأول",
                "sPrevious": "السابق",
                "sNext": "التالي",
                "sLast": "الأخير"
            },
            "oAria": {
                "sSortAscending": ": تفعيل لترتيب العمود تصاعدياً",
                "sSortDescending": ": تفعيل لترتيب العمود تنازلياً"
            }
        }
    });


    $('#basic-2').dataTable({
        // stateSave: true,
        "language": {
            "sProcessing": "جارٍ التحميل...",
            "sLengthMenu": "أظهر _MENU_ مدخلات",
            "sZeroRecords": "لم يعثر على أية سجلات",
            "sInfo": "إظهار _START_ إلى _END_ من أصل _TOTAL_ مدخل",
            "sInfoEmpty": "يعرض 0 إلى 0 من أصل 0 سجل",
            "sInfoFiltered": "(منتقاة من مجموع _MAX_ مُدخل)",
            "sInfoPostFix": "",
            "sSearch": "ابحث:",
            "sUrl": "",
            "oPaginate": {
                "sFirst": "الأول",
                "sPrevious": "السابق",
                "sNext": "التالي",
                "sLast": "الأخير"
            }
        }
    });
    $('#basic-3').dataTable({
        // stateSave: true,
        "language": {
            "sProcessing": "جارٍ التحميل...",
            "sLengthMenu": "أظهر _MENU_ مدخلات",
            "sZeroRecords": "لم يعثر على أية سجلات",
            "sInfo": "إظهار _START_ إلى _END_ من أصل _TOTAL_ مدخل",
            "sInfoEmpty": "يعرض 0 إلى 0 من أصل 0 سجل",
            "sInfoFiltered": "(منتقاة من مجموع _MAX_ مُدخل)",
            "sInfoPostFix": "",
            "sSearch": "ابحث:",
            "sUrl": "",
            "oPaginate": {
                "sFirst": "الأول",
                "sPrevious": "السابق",
                "sNext": "التالي",
                "sLast": "الأخير"
            }
        }
    });

    $('#basic-4').dataTable({
        // stateSave: true,
        "language": {
            "sProcessing": "جارٍ التحميل...",
            "sLengthMenu": "أظهر _MENU_ مدخلات",
            "sZeroRecords": "لم يعثر على أية سجلات",
            "sInfo": "إظهار _START_ إلى _END_ من أصل _TOTAL_ مدخل",
            "sInfoEmpty": "يعرض 0 إلى 0 من أصل 0 سجل",
            "sInfoFiltered": "(منتقاة من مجموع _MAX_ مُدخل)",
            "sInfoPostFix": "",
            "sSearch": "ابحث:",
            "sUrl": "",
            "oPaginate": {
                "sFirst": "الأول",
                "sPrevious": "السابق",
                "sNext": "التالي",
                "sLast": "الأخير"
            }
        }
    });

    $('#basic-5').dataTable({
        // stateSave: true,
        "language": {
            "sProcessing": "جارٍ التحميل...",
            "sLengthMenu": "أظهر _MENU_ مدخلات",
            "sZeroRecords": "لم يعثر على أية سجلات",
            "sInfo": "إظهار _START_ إلى _END_ من أصل _TOTAL_ مدخل",
            "sInfoEmpty": "يعرض 0 إلى 0 من أصل 0 سجل",
            "sInfoFiltered": "(منتقاة من مجموع _MAX_ مُدخل)",
            "sInfoPostFix": "",
            "sSearch": "ابحث:",
            "sUrl": "",
            "oPaginate": {
                "sFirst": "الأول",
                "sPrevious": "السابق",
                "sNext": "التالي",
                "sLast": "الأخير"
            }
        }
    });
</script>
<script type="text/javascript" src="{{asset('/admin/js/map.js')}}"></script>

<script src="{{asset('/admin/js/prism/prism.min.js')}}"></script>
<script src="{{asset('/admin/js/clipboard/clipboard.min.js')}}"></script>
<script src="{{asset('/admin/js/counter/jquery.waypoints.min.js')}}"></script>
<script src="{{asset('/admin/js/counter/jquery.counterup.min.js')}}"></script>
<script src="{{asset('/admin/js/counter/counter-custom.js')}}"></script>
<script src="{{asset('/admin/js/custom-card/custom-card.js')}}"></script>

<script src="{{asset('admin/js/select2/select2.full.min.js')}}"></script>
<script src="{{asset('admin/js/select2/select2-custom.js')}}"></script>

<script src="{{asset('/admin/js/notify/bootstrap-notify.min.js')}}"></script>
<script src="{{ asset('/admin/js/datepicker/date-picker/datepicker.js')}}"></script>
<script src="{{ asset('/admin/js/datepicker/date-picker/datepicker.en.js')}}"></script>
<script src="{{ asset('/admin/js/datepicker/date-picker/datepicker.custom.js')}}"></script>


{{--<script src="{{asset('js/typeahead/handlebars.js')}}"></script>--}}
{{--<script src="{{asset('js/typeahead/typeahead.bundle.js')}}"></script>--}}
{{--<script src="{{asset('js/typeahead/typeahead.custom.js')}}"></script>--}}

<script src="{{asset('/admin/js/form-validation-custom.js')}}"></script>

<script src="{{asset('/admin/js/chat-menu.js')}}"></script>
<script src="{{ asset('/admin/js/print.js') }}"></script>
<script src="{{ asset('/admin/js/p.js') }}"></script>
<script src="{{asset('/admin/js/height-equal.js')}}"></script>
<script src="{{asset('/admin/js/tooltip-init.js')}}"></script>

<script src="{{asset('admin/js/rating/jquery.barrating.js')}}"></script>
<script src="{{asset('admin/js/rating/rating-script.js')}}"></script>
<script src="{{asset('admin/js/form-validation-custom.js')}}"></script>
<!-- Plugins JS Ends-->

<!-- Theme js-->
<script src="{{asset('/admin/js/script.js')}}"></script>
<script src="{{asset('admin/js/axios.js')}}"></script>
<script src="{{asset('admin/js/notify/bootstrap-notify.min.js')}}"></script>
<!-- Plugin used-->
@if (session('success'))
    <script>
        new Noty({
            type: 'success',
            layout: 'bottomRight',
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
            layout: 'bottomRight',
            text: "{{ session('error') }}",
            timeout: 2000,
            killer: true
        }).show();
    </script>
@endif


<script>

    (function ($) {
        $('.btnprn').printPage();
        $(document).ready(function () {
            $("#email").attr("autocomplete","off");

            function readURL(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        $('#image-display').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }

            $("#image").change(function () {
                readURL(this);
            });
            //delete
            $('.delete').click(function (e) {

                var that = $(this);
                e.preventDefault();

                var n = new Noty({
                    text: "تاكيد الحذف",
                    type: "error",
                    layout: 'bottomRight',
                    killer: true,
                    buttons: [
                        Noty.button("نعم", 'btn btn-success mr-2', function () {
                            that.closest('form').submit();
                        }),
                        Noty.button("لا", 'btn btn-primary mr-2', function () {
                            n.close();
                        })
                    ]
                });

                n.show();

            });//end of delete

        });
    })(jQuery);
    //end of ready



</script>
