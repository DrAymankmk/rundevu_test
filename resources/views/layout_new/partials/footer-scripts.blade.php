<!-- jQuery -->
<script src="{{URL::asset('build/js/jquery-3.7.1.min.js')}}"></script>

<!-- Bootstrap Core JS -->
<script src="{{URL::asset('build/js/bootstrap.bundle.min.js')}}"></script>

<!-- Simplebar JS -->
<script src="{{URL::asset('build/plugins/simplebar/simplebar.min.js')}}"></script>

<!-- Daterangepikcer JS -->
<script src="{{URL::asset('build/js/moment.min.js')}}"></script>
<script src="{{URL::asset('build/plugins/daterangepicker/daterangepicker.js')}}"></script>

<!-- Date Time Pikcer JS -->
<script src="{{URL::asset('build/js/bootstrap-datetimepicker.min.js')}}"></script>


@if (Route::is(['appointment-calendar', 'calendar', 'doctors-appointment-details', 'patient-appointment-details']))
    <!-- Fullcalendar JS -->
    <script src="{{URL::asset('build/plugins/fullcalendar/index.global.min.js')}}"></script>
    <script src="{{URL::asset('build/plugins/fullcalendar/calendar-data.js')}}"></script>
@endif

<!-- Bootstrap Tagsinput JS -->
<script src="{{URL::asset('build/plugins/bootstrap-tagsinput/bootstrap-tagsinput.js')}}"></script>

@if (Route::is(['contact-messages', 'create-patient', 'edit-patient', 'new-appointment', 'patient-details', 'patients-doctor-details', 'security-settings', 'ticket-details', 'tickets']))
    <!-- intel Input -->
    <script src="{{URL::asset('build/plugins/intltelinput/js/intlTelInput.js')}}"></script>
@endif

@if (Route::is(['assets', 'expenses', 'income', 'invoices', 'patient-details', 'patient-doctor-details', 'payments', 'roles-and-permissions', 'services', 'staffs', 'transactions', 'ui-rangeslider']))
    <!-- Rangeslider JS -->
    <script src="{{URL::asset('build/plugins/ion-rangeslider/js/ion.rangeSlider.js')}}"></script>
    <script src="{{URL::asset('build/plugins/ion-rangeslider/js/custom-rangeslider.js')}}"></script>
    <script src="{{URL::asset('build/plugins/ion-rangeslider/js/ion.rangeSlider.min.js')}}"></script>
@endif

<!-- Datatable JS -->
<script src="{{URL::asset('build/js/jquery.dataTables.min.js')}}"></script>
<script src="{{URL::asset('build/js/dataTables.bootstrap5.min.js')}}"></script>

@if (Route::is(['notificationsList.create']))
    <!-- Quill JS -->
    <script src="{{URL::asset('build/plugins/quill/quill.min.js')}}"></script>
    <script src="{{URL::asset('build/js/form-quilljs.js')}}"></script>
@endif

@if (Route::is(['email-reply', 'search-list', 'social-feed']))
    <!-- Fancybox JS -->
    <script src="{{URL::asset('build/plugins/fancybox/jquery.fancybox.min.js')}}"></script>
@endif

@if (Route::is(['appointment-report', 'chart-apex', 'doctor-dashboard', 'file-manager', 'index', 'layout-full-width', 'layout-hidden', 'layout-hover-view', 'layout-mini', 'layout-rtl', 'patient-dashboard', 'widgets']))
    <!-- Chart JS -->
    <script src="{{URL::asset('build/plugins/apexchart/apexcharts.min.js')}}"></script>
    <script src="{{URL::asset('build/plugins/apexchart/chart-data.js')}}"></script>
@endif

@if (Route::is(['chart-c3']))
    <!-- Chart JS -->
    <script src="{{URL::asset('build/plugins/c3-chart/d3.v5.min.js')}}"></script>
    <script src="{{URL::asset('build/plugins/c3-chart/c3.min.js')}}"></script>
    <script src="{{URL::asset('build/plugins/c3-chart/chart-data.js')}}"></script>
@endif

@if (Route::is(['chart-flot']))
    <!-- Chart JS -->
    <script src="{{URL::asset('build/plugins/flot/jquery.flot.js')}}"></script>
    <script src="{{URL::asset('build/plugins/flot/jquery.flot.fillbetween.js')}}"></script>
    <script src="{{URL::asset('build/plugins/flot/jquery.flot.pie.js')}}"></script>
    <script src="{{URL::asset('build/plugins/flot/chart-data.js')}}"></script>
@endif

@if (Route::is(['chart-js']))
    <!-- Chart JS -->
    <script src="{{URL::asset('build/plugins/chartjs/chart.min.js')}}"></script>
    <script src="{{URL::asset('build/plugins/chartjs/chart-data.js')}}"></script>
@endif

@if (Route::is(['chart-morris']))
    <!-- Chart JS -->
    <script src="{{URL::asset('build/plugins/morris/raphael-min.js')}}"></script>
    <script src="{{URL::asset('build/plugins/morris/morris.min.js')}}"></script>
    <script src="{{URL::asset('build/plugins/morris/chart-data.js')}}"></script>
@endif

@if (Route::is(['chart-peity']))
    <!-- Chart JS -->
    <script src="{{URL::asset('build/plugins/peity/jquery.peity.min.js')}}"></script>
    <script src="{{URL::asset('build/plugins/peity/chart-data.js')}}"></script>
@endif

@if (Route::is(['extended-dragula']))
    <!-- Dragula js-->
    <script src="{{URL::asset('build/plugins/dragula/dragula.min.js')}}"></script>

    <!-- Dragula Demo Component js -->
    <script src="{{URL::asset('build/js/dragula.js')}}"></script>
@endif

@if (Route::is(['form-fileupload']))
    <!-- Dropzone File Js -->
    <script src="{{URL::asset('build/plugins/dropzone/dropzone-min.js')}}"></script>

    <!-- File Upload Demo js -->
    <script src="{{URL::asset('build/js/form-fileupload.js')}}"></script>
@endif

@if (Route::is(['form-mask']))
    <!-- Mask JS -->
    <script src="{{URL::asset('build/js/jquery.maskedinput.min.js')}}"></script>
    <script src="{{URL::asset('build/js/mask.js')}}"></script>
@endif

@if (Route::is(['form-pickers', 'kanban-view', 'notes', 'todo-list', 'todo']))
    <!-- Flatpickr JS -->
    <script src="{{URL::asset('build/plugins/flatpickr/flatpickr.min.js')}}"></script>
@endif

@if (Route::is(['form-range-slider']))
    <!-- noUiSlider js -->
    <script src="{{URL::asset('build/plugins/nouislider/nouislider.min.js')}}"></script>
    <script src="{{URL::asset('build/plugins/wnumb/wNumb.min.js')}}"></script>

    <!-- Plugins only -->
    <script src="{{URL::asset('build/js/extended-range-slider.js')}}"></script>
@endif

@if (!Route::is(['form-mask']))
    <!-- Select2 JS -->
    <script src="{{URL::asset('build/plugins/select2/js/select2.min.js')}}"></script>
@endif

{{--@if (Route::is(['notificationsList.create']))--}}
<!-- Choices JS -->
<script src="{{URL::asset('build/plugins/choices.js/public/assets/scripts/choices.min.js')}}"></script>
{{--@endif--}}

@if (Route::is(['form-validation']))
    <!-- Validation JS -->
    <script src="{{URL::asset('build/js/form-validation.js')}}"></script>
@endif

@if (Route::is(['form-wizard']))
    <!-- Wizrd JS -->
    <script src="{{URL::asset('build/plugins/vanilla-wizard/js/wizard.min.js')}}"></script>

    <!-- Wizard JS -->
    <script src="{{URL::asset('build/js/form-wizard.js')}}"></script>
@endif

@if (Route::is(['gallery', 'ui-lightbox', 'widgets']))
    <!-- Lightbox JS -->
    <script src="{{URL::asset('build/plugins/lightbox/glightbox.min.js')}}"></script>
    <script src="{{URL::asset('build/plugins/lightbox/lightbox.js')}}"></script>
@endif

@if (Route::is(['maps-leaflet']))
    <!-- Leaflet Maps JS -->
    <script src="{{URL::asset('build/plugins/leaflet/leaflet.js')}}"></script>
    <script src="{{URL::asset('build/js/leaflet.js')}}"></script>
@endif

@if (Route::is(['maps-vector']))
    <!-- Map JS -->
    <script src="{{URL::asset('build/plugins/jsvectormap/js/jsvectormap.min.js')}}"></script>

    <!-- JSVector Maps MapsJS -->
    <script src="{{URL::asset('build/plugins/jsvectormap/maps/world-merc.js')}}"></script>
    <script src="{{URL::asset('build/js/us-merc-en.js')}}"></script>
    <script src="{{URL::asset('build/js/russia.js')}}"></script>
    <script src="{{URL::asset('build/js/spain.js')}}"></script>
    <script src="{{URL::asset('build/js/canada.js')}}"></script>
    <script src="{{URL::asset('build/js/jsvectormap.js')}}"></script>
@endif

@if (Route::is(['ui-rating']))
    <!-- Rater JS -->
    <script src="{{URL::asset('build/plugins/rater-js/index.js')}}"></script>

    <!-- Internal Ratings JS -->
    <script src="{{URL::asset('build/js/ratings.js')}}"></script>
@endif

@if (Route::is(['ui-scrollbar', 'ui-scrollspy']))
    <!-- Scrollbar JS -->
    <script src="{{URL::asset('build/plugins/scrollbar/scrollbar.min.js')}}"></script>
    <script src="{{URL::asset('build/plugins/scrollbar/custom-scroll.js')}}"></script>
@endif

@if (Route::is(['ui-sortable']))
    <!-- Sortable JS -->
    <script src="{{URL::asset('build/plugins/sortablejs/Sortable.min.js')}}"></script>

    <!-- Internal Sortable JS -->
    <script src="{{URL::asset('build/js/sortable.js')}}"></script>
@endif

@if (Route::is(['ui-sweetalerts']) || Route::is('cms.*') || Route::is('website-media.*'))
    <!-- Sweet Alerts js -->
    <script src="{{URL::asset('build/plugins/sweetalert2/sweetalert2.min.js')}}"></script>
    @if (Route::is(['ui-sweetalerts']))
        <script src="{{URL::asset('build/js/sweetalerts.js')}}"></script>
    @endif
@endif


<!-- Toastr JS -->
<script src="{{URL::asset('build/plugins/toastr/toastr.min.js')}}"></script>
<script src="{{URL::asset('build/plugins/toastr/toastr.js')}}"></script>


@if (Route::is(['ui-tooltips']))
    <!-- Custom JS -->
    <script src="{{URL::asset('build/js/popover.js')}}"></script>
@endif

@if (Route::is(['doctor-details', 'doctors-patient-details', 'notes', 'patients-doctor-details', 'social-feed', 'file-manager']))
    <!-- Sticky Sidebar JS -->
    <script src="{{URL::asset('build/plugins/theia-sticky-sidebar/ResizeSensor.js')}}"></script>
    <script src="{{URL::asset('build/plugins/theia-sticky-sidebar/theia-sticky-sidebar.js')}}"></script>
@endif

@if (Route::is(['chat', 'messages', 'video-call']))
    <!-- Slimscroll JS -->
    <script src="{{URL::asset('build/js/jquery.slimscroll.min.js')}}"></script>
@endif

@if (Route::is(['kanban-view']))
    <!-- Drag Card -->
    <script src="{{URL::asset('build/js/jquery-ui.min.js')}}"></script>
    <script src="{{URL::asset('build/js/jquery.ui.touch-punch.min.js')}}"></script>
@endif

{{--@if (Route::is(['add-doctor', 'appointment-report', 'attendance', 'delete-account-request', 'designation', 'doctor-schedule', 'doctors-notifications', 'doctors-schedules', 'doctors', 'edit-doctor', 'expense-report', 'hrm-departments', 'income-report', 'leaves', 'notifications', 'patient-notifications', 'patient-report', 'payroll-2', 'payroll', 'permissions', 'profit-and-loss', 'roles-and-permissions', 'services', 'main-specialties', 'staffs']))--}}
{{--    <!-- Custom JS -->--}}
{{--    <script src="{{URL::asset('build/js/doctors.js')}}"></script>--}}
{{--@endif--}}

@if (Route::is(['chat', 'messages', 'video-call']))
    <!-- Custom JS -->
    <script src="{{URL::asset('build/js/chat.js')}}"></script>
    <script src="{{URL::asset('build/js/slimscroll.js')}}"></script>
@endif

@if (Route::is(['doctor-details', 'doctors-patient-details', 'notes', 'social-feed']))
    <!-- Custom JS -->
    <script src="{{URL::asset('build/js/social-feed.js')}}"></script>
@endif

@if (Route::is(['doctor-details', 'doctors-patient-details', 'email-reply', 'email', 'social-feed']))
    <!-- Custom JS -->
    <script src="{{URL::asset('build/js/email.js')}}"></script>
@endif

@if (Route::is(['coming-soon', 'under-maintenance']))
    <!-- Custom JS -->
    <script src="{{URL::asset('build/js/coming-soon.js')}}"></script>
@endif

@if (Route::is(['kanban-view']))
    <!-- Custom JS -->
    <script src="{{URL::asset('build/js/kanban.js')}}"></script>
@endif

@if (Route::is(['todo-list', 'todo']))
    <!-- Custom JS -->
    <script src="{{URL::asset('build/js/todo.js')}}"></script>
@endif

@if (Route::is(['ui-clipboard']))
    <!-- Clipboard JS -->
    <script src="{{URL::asset('build/plugins/clipboard/clipboard.min.js')}}"></script>

    <!-- Custom JS -->
    <script src="{{URL::asset('build/js/clipboard.js')}}"></script>
@endif

@if (Route::is(['ui-counter']))
    <!-- counter JS -->
    <script src="{{URL::asset('build/plugins/countup/jquery.counterup.min.js')}}"></script>
    <script src="{{URL::asset('build/plugins/countup/jquery.waypoints.min.js')}}"></script>
    <script src="{{URL::asset('build/plugins/countup/jquery.missofis-countdown.js')}}"></script>
    <!-- Custom JS -->
    <script src="{{URL::asset('build/js/counter.js')}}"></script>
@endif


<script>
    window.datatableI18n = window.datatableI18n || {};
    window.datatableI18n.searchPlaceholder = @json(__('main.search_by_name'));
</script>

<!-- Main JS -->
<script src="{{URL::asset('build/js/script.js')}}"></script>
