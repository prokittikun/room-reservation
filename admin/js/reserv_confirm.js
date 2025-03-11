$('[name="reserv-confirm"]').click(function () {
    const id = $(this).attr('data-id')
    confirmDialog('ยืนยันการจอง', 'คุณต้องการยืนยันการจองใช่ หรือไม่ ?')
        .then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: bookingRoute(),
                    type: 'post',
                    data: { 'route': '/booking/confirm',id },
                    complete: function (xhr, textStatus) {
                        if (xhr.status == 200) {
                            success('ยืนยันเรียบร้อย')

                        } else {
                            errDialog('ยืนยันการจอง', 'เกิดข้อผิดพลาด', '')
                        }

                    }
                })
            }
        });
})