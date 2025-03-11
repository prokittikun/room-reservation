$('[name="reserv-confirm"]').click(function () {
    const id = $(this).attr('data-id')
    confirmDialog('ยืนยันยกเลิกการจอง', 'คุณต้องการยืนยันยกเลิกการจองใช่ หรือไม่ ?')
        .then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: bookingRoute(),
                    type: 'post',
                    data: { 'route': '/booking/cancel/confirm',id },
                    complete: function (xhr, textStatus) {
                        console.log(xhr.status)
                        if (xhr.status == 200) {
                            success('ยืนยันเรียบร้อย')

                        } else {
                            errDialog('ยืนยันยกเลิกการจอง', 'เกิดข้อผิดพลาด', '')
                        }

                    }
                })
            }
        });
})