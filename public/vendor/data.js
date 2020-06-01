$(function () {
    $('#example2').DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
        "info": false
    });
});



$('.btn_predict').on('click', function (event) {
    let timerInterval
    Swal.fire({
        title: 'Đang chạy chương trình!',
        timer: 3000000,
        timerProgressBar: true,
        onBeforeOpen: () => {
            Swal.showLoading()
            timerInterval = setInterval(() => {
                const content = Swal.getContent()
                if (content) {
                    const b = content.querySelector('b')
                    if (b) {
                        b.textContent = Swal.getTimerLeft()
                    }
                }
            }, 100)
        },
        onClose: () => {
            clearInterval(timerInterval)
        }
    })

    event.preventDefault();
    var url = $('#url').val();
    let urlRequest = $(this).data('url');
    $.ajax({
        url: urlRequest,
        type: 'GET',
        data: { url: url },
        success: function (data) {
            if (data.code == 200) {

                Swal.fire({
                    title: 'Đã Xong',
                    icon: 'success',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Xem kết quả'
                }).then((result) => {
                    if (result.value) {
                        window.location.replace("http://127.0.0.1:8000/dashboard")
                    }
                })
            }
        }
    });

});
