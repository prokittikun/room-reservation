$('[name="reserv-delete"]').click(function () {
    const id = $(this).attr('data-id')
    confirmDialog('ลบการจอง', 'คุณต้องการลบการจองใช่ หรือไม่ ?')
        .then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: bookingRoute(),
                    type: 'post',
                    data: { 'route': '/booking/delete/id', id },
                    complete: function (xhr, textStatus) {
                        console.log(xhr.status)
                        if (xhr.status == 200) {
                            success('ลบเรียบร้อย')
                        } else {
                            errDialog('ลบการจอง', 'เกิดข้อผิดพลาด', '')
                        }

                    }
                })
            }
        });
})