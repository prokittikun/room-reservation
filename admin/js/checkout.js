$('[name="reserv-checkout"]').click(function () {
    const id = $(this).attr('data-id')
    confirmDialog('ยืนยันการออกห้องพัก', 'คุณต้องการยืนยันการออกห้องพักใช่ หรือไม่ ?')
        .then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: bookingRoute(),
                    type: 'post',
                    data: { 'route': '/booking/checkout/confirm', id },
                    complete: function (xhr, textStatus) {
                        if (xhr.status == 200) {
                            success('การออกห้องพักเรียบร้อย')
                        } else if (xhr.status == 400) {
                            errDialog('ยืนยันการชำระเงิน', 'ยังไม่ได้ชำระเงิน หรือ ยืนยันการชำระเงิน โปรดชำระเงินก่อนยืนยันการออกห้องพัก', '')
                        } else {
                            errDialog('ยืนยันการออกห้องพัก', 'เกิดข้อผิดพลาด', '')
                        }

                    }
                })
            }
        });
})