$('[name="confirm-pay"]').click(function () {
    const id = $(this).attr('data-id')
    confirmDialog('ยืนยันการชำระเงิน', 'คุณต้องการยืนยันการชำระเงินใช่ หรือไม่ ?')
        .then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: bookingRoute(),
                    type: 'post',
                    data: { 'route': '/booking/pay/confirm', id },
                    complete: function (xhr, textStatus) {
                        console.log(xhr.status)
                        if (xhr.status == 200) {
                            success('ยืนยันเรียบร้อย')

                        } else {
                            errDialog('ยืนยันการชำระเงิน', 'เกิดข้อผิดพลาด', '')
                        }

                    }
                })
            }
        });
})

$('[name="re-pay"]').click(function () {
    const id = $(this).attr('data-id')
    confirmDialog('การชำระเงินอีกครั้ง', 'คุณต้องการให้ชำระเงินอีกครั้งใช่ หรือไม่ ?')
        .then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: bookingRoute(),
                    type: 'post',
                    data: { 'route': '/booking/pay/repay', id },
                    complete: function (xhr, textStatus) {
                        console.log(xhr.status)
                        if (xhr.status == 200) {
                            success('ทำรายการเรียบร้อย')

                        } else {
                            errDialog('การชำระเงินอีกครั้ง', 'เกิดข้อผิดพลาด', '')
                        }

                    }
                })
            }
        });
})



