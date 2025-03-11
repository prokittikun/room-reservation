$('[name="reserv-checkin"]').click(function () {
    const id = $(this).attr('data-id')
    confirmDialog('ยืนยันการเข้าพัก', 'คุณต้องการเข้าพักใช่ หรือไม่ ?')
        .then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: bookingRoute(),
                    type: 'post',
                    data: { 'route': '/booking/checkin/confirm', id },
                    complete: function (xhr, textStatus) {
                        if (xhr.status == 200) {
                            success('เข้าพักเรียบร้อย')
                        } else {
                            errDialog('ยืนยันการเข้าพัก', 'เกิดข้อผิดพลาด', '')
                        }

                    }
                })
            }
        });
})