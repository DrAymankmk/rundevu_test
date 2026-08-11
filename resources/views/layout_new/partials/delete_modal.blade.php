
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
