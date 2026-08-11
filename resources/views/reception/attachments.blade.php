@extends('includes_admin.mainlayout')
@section('content')

    <style>
        .delete {
            background-color: #ea545520;
        }

        .delete i {
            color: #ea5455 !important;
        }
    </style>

    <div class="page-wrapper">
        <div class="content">

            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('patients') }}">@lang('admin.reception.reception') </a></li>
                            <li class="breadcrumb-item px-2"><i id="breadcrumbArrow"></i></li>
                            <li class="breadcrumb-item active">@lang('admin.reception.attachments_list')</li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->

            <div class="row">
                <div class="col-sm-12">
                    <div class="card card-table show-entire">
                        <div class="card-body">

                            <!-- Table Header -->
                            <div class="page-table-header mb-2">
                                <div class="row align-items-center gap-2 d-md-flex d-block">
                                    <div class="col">
                                        <div class="doctor-table-blk">
                                            <h3>@lang('admin.reception.attachments_list')</h3>
                                            <div class="doctor-search-blk">
                                                <div class="top-nav-search table-search-blk">
                                                    <!-- <form> -->
                                                    <input onkeyup="search(event)" type="text" class="form-control"
                                                           placeholder="ابحث هنا">
                                                    <a class="btn"><img src="/assets/img/icons/search-normal.svg"
                                                                        alt=""></a>
                                                    <!-- </form> -->
                                                </div>
                                                <div class="add-group">
                                                    @if(!empty($patient_id))
                                                    <a href="#" data-bs-toggle="modal" data-bs-target="#add_attachment"
                                                       class="btn btn-primary add-pluss"><img
                                                            src="/assets/img/icons/plus.svg" alt=""></a>
                                                    @endif
                                                    <a href="javascript:;" class="btn btn-primary doctor-refresh "><img
                                                            src="/assets/img/icons/re-fresh.svg" alt=""></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- <div class="col-auto text-end float-end ms-auto download-grp">
                                        <a href="javascript:;" class=" me-2"><img src="assets/img/icons/pdf-icon-01.svg" alt=""></a>
                                        <a href="javascript:;" class=" me-2"><img src="assets/img/icons/pdf-icon-02.svg" alt=""></a>
                                        <a href="javascript:;" class=" me-2"><img src="assets/img/icons/pdf-icon-03.svg" alt=""></a>
                                        <a href="javascript:;" ><img src="assets/img/icons/pdf-icon-04.svg" alt=""></a>
                                    </div> -->
                                </div>
                            </div>
                            <!-- /Table Header -->
                            <div class="position-relative">
                                <div class="table-loader">
                                    <div class="spinner"></div>
                                </div>
                                <div class="table-responsive">
                                    <table id="AttachmentsTable"
                                           class="table border-0 custom-table comman-table datatable mb-0">
                                        <thead>
                                        <tr>
                                            <th>@lang('admin.patient_name')</th>
                                            <th>@lang('admin.file')</th>
                                            <th>@lang('admin.Date')</th>
                                            <th>@lang('admin.notes')</th>
                                            <th>@lang('admin.reception.options')</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($attachments as $index=>$attachment)
                                            <tr>
                                                <td>{{$attachment->patient->name ?? null}}</td>
                                                <td>
                                                    @php
                                                        $extension = pathinfo($attachment->image, PATHINFO_EXTENSION);
                                                    @endphp
                                                    @if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif']))
                                                        <img src="{{$attachment->image}}" alt="@lang('admin.no_image')"
                                                             style="width:50px;height: 50px;">
                                                    @else
                                                        <a class="link"
                                                           onclick="downloadFile('{{ $attachment->image ?? null }}')">{{ basename($attachment->image ?? null) }}</a>
                                                    @endif

                                                </td>
                                                <td>{{date('Y-m-d',strtotime($attachment->created_at))}}</td>
                                                <td class="text-wrap"
                                                    style="width: 800px; min-width: 500px;">{{ $attachment->notes ?? null }}</td>
                                                <td>
                                                    <a href="javascript:void(0)" onclick="selectRow(event)"
                                                       data-bs-toggle="modal"
                                                       data-bs-target="#delete_attachment{{$attachment->id}}"
                                                       class="text-success font-18 add-table-invoice delete"
                                                       title="@lang('admin.delete')"><i class="fa fa-trash-alt"></i></a>

                                                    <div id="delete_attachment{{$attachment->id}}"
                                                         class="modal fade delete-modal" role="dialog">
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            <div class="modal-content">
                                                                <div class="modal-body text-center">
                                                                    <img src="/assets/img/sent.png" alt="" width="50"
                                                                         height="46">
                                                                    <h3>@lang('admin.confirm_delete')</h3>
                                                                    <div class="m-t-20">
                                                                        <a href="#" class="btn btn-white"
                                                                           data-bs-dismiss="modal">@lang('admin.close')</a>
                                                                        <button
                                                                            onclick="deleteAttachment({{ $attachment->id ?? null }})"
                                                                            type="button" class="btn btn-danger"
                                                                            data-bs-dismiss="modal">@lang('admin.delete')
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>

                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Attachment Modal -->
    <div class="modal custom-modal modal-bg fade bank-details" id="add_attachment" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <form
                    action="{{route('add-attachment')}}"
                    method="POST" enctype="multipart/form-data">
                    {{ method_field('POST') }}
                    {{ csrf_field() }}
                    <input type="hidden" name="patient_id" value="{{$patient_id ?? null}}">

                <div class="modal-header py-2 px-3">
                    <div class="form-header text-start mb-0">
                        <h4 class="mb-0">@lang('admin.reception.add_attachment')</h4>
                    </div>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-start py-4 px-3">
                    <div class="bank-inner-details">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group local-top-form">
                                    <label class="local-top"> @lang('admin.reception.attachment')<span
                                            class="login-danger">*</span></label>
                                    <div class="settings-btn upload-files-avator">
                                        <label id="fileName" style="position: absolute; top: 10px; font-size: 14px;"></label>
                                        <input type="file" accept=".doc,.docx,.pdf" name="image" id="file" onchange="uploadFile(event)" class="hide-input" required>
                                        <label for="file" class="upload">@lang('admin.reception.select_file')</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group local-forms mb-0">
                                    <label> @lang('admin.notes')<span class="login-danger">*</span></label>
                                    <textarea class="form-control" style="min-height: 87px;" name="notes" placeholder="@lang('admin.enter_text_here')" rows="3" cols="30">{{old('notes')}}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer p-3">
                    <div class="bank-details-btn">
                        <a href="javascript:void(0);" data-bs-dismiss="modal"
                           class="btn bank-cancel-btn me-2">@lang('admin.cancel')</a>
                        <button type="submit" class="btn bank-save-btn">@lang('admin.send')</button>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Add Attachment Modal End -->

    <script>
        function downloadFile(file) {
            var a = document.createElement("a");
            a.style = "display: none";
            a.href = file;
            a.download = "download.pdf";
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
        }

        function search(e) {
            if (e.key === 'Enter' || e.keyCode === 13) {
                document.getElementsByClassName('table-loader')[0].style.display = 'flex';
                setTimeout(function () {
                    document.getElementsByClassName('table-loader')[0].style.display = 'none';
                }, 3000)
            }
        }

        function uploadFile(e) {
            document.getElementById('fileName').innerHTML = e.target.files[0].name;
        }

        var deletedRow = ''

        function selectRow(event) {
            deletedRow = event
        }

        function deleteAttachment(attachmentId) {

            // Send AJAX request to delete the item
            $.ajax({
                url: '{{ url('/admin/destroy-attachment', ['id' => '']) }}/' + attachmentId, // Pass the attachmentId in the URL
                type: 'DELETE',
                data: {
                    "_token": "{{ csrf_token() }}",
                },
                success: function (response) {
                    new Noty({
                        type: 'success',
                        layout: 'topRight',
                        text: response,
                        timeout: 3000,
                        killer: true
                    }).show();

                    // Remove the corresponding row from the table
                    // $('#delete_attachment' + attachmentId).closest('tr').remove();
                    document.getElementsByClassName('table-loader')[0].style.display = 'flex';
                    setTimeout(function () {
                        deletedRow.target.closest("tr").remove();
                        document.getElementsByClassName('table-loader')[0].style.display = 'none';
                    }, 3000)
                },
                error: function (xhr, status, error) {
                    // Handle errors
                    console.error(xhr.responseText);
                }
            });
        }
    </script>

@endsection
