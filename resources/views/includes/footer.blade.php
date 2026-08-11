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
    $('#basic-1').dataTable();
    $('#basic-2').dataTable();
    $('#basic-3').dataTable();
    $('#basic-4').dataTable();
    $('#basic-5').dataTable();
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

@if( Request::segment(2) == 'employee-shifts')
    <script src="{{asset('/admin/js/notify/bootstrap-notify.min.js')}}"></script>
    <script src="{{ asset('/admin/js/datepicker/date-picker/datepicker.js')}}"></script>
    <script src="{{ asset('/admin/js/datepicker/date-picker/datepicker.en.js')}}"></script>
    <script src="{{ asset('/admin/js/datepicker/date-picker/datepicker.custom.js')}}"></script>
@endif

<script src="{{asset('/admin/js/form-validation-custom.js')}}"></script>
{{--<script src="{{asset('/admin/js/chat-menu.js')}}"></script>--}}
{{--<script src="{{ asset('/admin/js/print.js') }}"></script>--}}
<script src="{{ asset('/admin/js/p.js') }}"></script>
<script src="{{asset('/admin/js/height-equal.js')}}"></script>
<script src="{{asset('/admin/js/tooltip-init.js')}}"></script>

{{--<script src="{{asset('admin/js/rating/jquery.barrating.js')}}"></script>--}}
{{--<script src="{{asset('admin/js/rating/rating-script.js')}}"></script>--}}
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

    (function ($) {
        $('.btnprn').printPage();
        $(document).ready(function () {
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
                    text: "Confirm Delete",
                    type: "error",
                    layout: 'topRight',
                    killer: true,
                    buttons: [
                        Noty.button("Yes", 'btn btn-success mr-2', function () {
                            that.closest('form').submit();
                        }),
                        Noty.button("NO", 'btn btn-primary mr-2', function () {
                            n.close();
                        })
                    ]
                });

                n.show();

            });//end of delete

        });
    })(jQuery);
    //end of ready


    {{--$("#category_id").change(function () {--}}
    {{--    const category_id = $(this).val();--}}
    {{--    if (category_id !== '') {--}}
    {{--        $.ajax--}}
    {{--        ({--}}
    {{--            type: "GET",--}}
    {{--            url: "{{route('getCities')}}",--}}
    {{--            data: "category_id=" + encodeURIComponent(category_id),--}}
    {{--            success: function (option) {--}}
    {{--                $("#city_id").html(option);--}}
    {{--            }--}}
    {{--        });--}}
    {{--    } else {--}}
    {{--        $("#city_id").html("<option value=''>اختر اسم القسم الفرعى</option>");--}}
    {{--    }--}}
    {{--    return false;--}}
    {{--});--}}


    // $("#account_type").change(function () {
    //
    //     var account_type = $(this).val();
    //     if (account_type == 1) {
    //         $('#phone2').addClass('hidden');
    //         $('#description').addClass('hidden');
    //         $('#service_id').addClass('hidden');
    //         $('#company_name').addClass('hidden');
    //         $('#license_images').addClass('hidden');
    //     } else {
    //         $('#phone2').removeClass('hidden');
    //         $('#company_name').removeClass('hidden');
    //         $('#license_images').removeClass('hidden');
    //         if (account_type == 3) {
    //             $('#service_id').addClass('hidden');
    //             $('#description').addClass('hidden');
    //         } else {
    //             $('#description').removeClass('hidden');
    //             $('#service_id').removeClass('hidden');
    //         }
    //
    //     }
    //
    // });

    // google.load('visualization', '1.0', {'packages': ['corechart']});
    // google.setOnLoadCallback(drawChart1);
    // google.setOnLoadCallback(drawChart3);


    {{--function drawChart1() {--}}
    {{--    @if(isset($data['projects_charts']))--}}
    {{--    if ($("#projects").length > 0) {--}}
    {{--        var data = google.visualization.arrayToDataTable([--}}

    {{--            ["Element", "projects", {role: "style"}],--}}
    {{--                @if(isset($data['projects_charts']))--}}
    {{--                @foreach($data['projects_charts'] as $account)--}}
    {{--            ["{{$account->date  }}", {{ $account->count }} , "#1e1e2d"],--}}
    {{--            @endforeach--}}
    {{--            @endif--}}
    {{--        ]);--}}
    {{--        var view = new google.visualization.DataView(data);--}}
    {{--        view.setColumns([0, 1,--}}
    {{--            {--}}
    {{--                calc: "stringify",--}}
    {{--                sourceColumn: 1,--}}
    {{--                type: "string",--}}
    {{--                role: "annotation"--}}
    {{--            },--}}
    {{--            2]);--}}
    {{--        var options = {--}}
    {{--            width: '100%',--}}
    {{--            height: 400,--}}
    {{--            bar: {groupWidth: "95%"},--}}
    {{--            legend: {position: "none"},--}}
    {{--        };--}}
    {{--        var chart = new google.visualization.ColumnChart(document.getElementById("projects"));--}}
    {{--        chart.draw(view, options);--}}
    {{--    }--}}

    {{--    @endif--}}
    {{--}--}}

    {{--function drawChart3() {--}}
    {{--    @if(isset($data['all_categories']))--}}
    {{--    if ($("#categories_charts").length > 0) {--}}
    {{--        var accounts = google.visualization.arrayToDataTable([--}}
    {{--            ["Element", "categories_count", {role: "style"}],--}}
    {{--                @foreach($data['all_categories'] as $category)--}}
    {{--            ["{{$category->name_en  }} ", {{ count($category->category) }} , "#1e1e2d"],--}}
    {{--            @endforeach--}}

    {{--        ]);--}}
    {{--        var view = new google.visualization.DataView(accounts);--}}
    {{--        view.setColumns([0, 1,--}}
    {{--            {--}}
    {{--                calc: "stringify",--}}
    {{--                sourceColumn: 1,--}}
    {{--                type: "string",--}}
    {{--                role: "annotation"--}}
    {{--            },--}}
    {{--            2]);--}}
    {{--        var options = {--}}
    {{--            width: '100%',--}}
    {{--            height: 400,--}}
    {{--            bar: {groupWidth: "95%"},--}}
    {{--            legend: {position: "none"},--}}
    {{--        };--}}
    {{--        var account_chart = new google.visualization.ColumnChart(document.getElementById("categories_charts"));--}}
    {{--        account_chart.draw(view, options);--}}
    {{--    }--}}
    {{--    @endif--}}
    {{--}--}}

</script>
