var SweetAlert_custom = {
    init: function () {
        document.querySelector('.sweet-5').onclick = function () {
            swal({
                title: "هل تريد حذف هذا القسم?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
                .then((willDelete) => {
                    if (willDelete) {
                        swal("تم حذف القسم بنجاح!", {
                            icon: "success",
                        });
                    } else {
                        swal("لا يمكنك حذف هذا القسم!");
                    }
                })
        }
    }
};
(function ($) {
    SweetAlert_custom.init()
})(jQuery);
