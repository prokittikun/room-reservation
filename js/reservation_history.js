$('[name="repay"]').click(function () {
    const id = $(this).attr('data-id')
    $('#paymentHandleSubmit').attr('data-id', id)
    $('#slipPaymentModal').modal('show')
})
$('#paymentHandleSubmit').click(function () {
    const id = $(this).attr('data-id')

    const slipPayment = $('#slipPayment')[0].files
    const is_slip = slipPayment.length == 0
    errValidate(is_slip, $('#validateSlipPayment'), 'กรุณาอัพโหลดหลักฐานการขำระเงิน')
    const validateCount = is_slip ? 1 : 0

    if (validateCount == 0) {
        const formData = new FormData();
        formData.append("route", '/booking/slippayment');
        formData.append("id", id);
        for (let i = 0; i < slipPayment.length; i++) {
            formData.append("slip_payment[]", slipPayment[i]);
        }

        $.ajax({
            url: './controller/reservation_controller.php',
            type: 'post',
            processData: false,
            contentType: false,
            data: formData,
            complete: function (xhr, textStatus) {

                if (xhr.status == 200) {
                    success('ชำระเงินเรียบร้อย')
                } else {
                    errDialog('แจ้งเตือน', 'เกิดข้อผิดพลาด', '')
                }

            }
        })
    }
})
$('#postponeSlipPayment').change(function () {
    const file = $(this)[0].files
    let postponeSlipPaymentEl = ``
    for (let i = 0; i < file.length; i++) {
        const src = URL.createObjectURL(file[i])
        postponeSlipPaymentEl += `<div class="col-md-4">`
        postponeSlipPaymentEl += `<img src="${src}" style="width: 100%;object-fit:contain;"></img>`
        postponeSlipPaymentEl += `</div>`
    }

    $('#postponeSlipPaymentPreview').html(postponeSlipPaymentEl)
})


$('[name="reserv-cancel"]').click(function () {
    const id = $(this).attr('data-id')
    confirmDialog('ยกเลิกการจอง', 'คุณต้องการจองใช่ หรือไม่ ?')
        .then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: './controller/reservation_controller.php',
                    type: 'post',
                    data: { 'route': '/booking/cancel', id },
                    complete: function (xhr, textStatus) {
                        console.log(xhr.status)
                        if (xhr.status == 200) {
                            success('ยกเลิกการจองเรียบร้อย')

                        } else {
                            errDialog('ยกเลิกการจอง', 'เกิดข้อผิดพลาด', '')
                        }

                    }
                })
            }
        });
})

$('[name="reserv-postpone"]').click(function () {
    const id = $(this).attr('data-id')
    console.log(id, 'id')
    $.ajax({
        url: './controller/reservation_controller.php',
        type: 'post',
        data: { 'route': '/booking/data/id', id },
        complete: function (xhr, textStatus) {
            console.log(xhr.status)
            try {
                const response = JSON.parse(xhr.responseText)
                const { reservation } = response
                $('#postponeHandleSubmit').attr('data-id', id)

                $('#postponeModal').modal('show')
                if (xhr.status == 200) {
                    $('#startDt').val(reservation.start_dt)
                    $('#endDt').val(reservation.end_dt)
                    $('#total').val(reservation.total)
                    $('#dayCount').val(reservation.day_count)
                    $('#totalOldText').text(getNumberFormat(reservation.total))
                    $('#dayCountOldText').text(reservation.day_count)
                  
                    $('#totalText').text(getNumberFormat(0))
                    $('#dayCountText').text(getNumberFormat(0))
                    $('#paidText').text(getNumberFormat(0))
                    $('#price').val(reservation.price)
                    $('#roomId').val(reservation.room_id)
                    const slip_payment = reservation.slip_payment.split(',').filter((f) => f != '')
                    let slipPaymentEl = ``
                    console.log(slip_payment)
                    slip_payment.forEach((s, i) => {
                        const src = `./assets/images/slip_payment/${s}`
                        slipPaymentEl += `<div class="col-md-4">`
                        slipPaymentEl += `<img src="${src}" style="width: 100%;object-fit:contain;"></img>`
                        slipPaymentEl += `</div>`
                    })
                    $('#slipPaymentOldPreview').html(slipPaymentEl)
                } else {
                    errDialog('เลื่อนการจอง', 'เกิดข้อผิดพลาด', '')
                }
            } catch (error) {
                errDialog('เลื่อนการจอง', 'เกิดข้อผิดพลาด', '')
            }


        }
    })

})

$('#postponeHandleSubmit').click(function () {
    const id = $(this).attr('data-id')
    console.log(id)
    const postponeSlipPayment = $('#postponeSlipPayment')[0].files
    const is_slip = postponeSlipPayment.length == 0
    errValidate(is_slip, $('#validatePostponeSlipPayment'), 'กรุณาอัพโหลดหลักฐานการขำระเงิน')
    const validateCount = is_slip ? 1 : 0

    if (validateCount == 0) {
        const formData = new FormData();
        formData.append("route", '/booking/postpone');
        formData.append("id", id);
        formData.append("start_dt", $('#startDt').val());
        formData.append("end_dt", $('#endDt').val());
        formData.append("day_count", $('#dayCount').val());
        formData.append("total", $('#total').val());
        formData.append("room_id", $('#roomId').val());
        for (let i = 0; i < postponeSlipPayment.length; i++) {
            formData.append("slip_payment[]", postponeSlipPayment[i]);
        }



        $.ajax({
            url: './controller/reservation_controller.php',
            type: 'post',
            processData: false,
            contentType: false,
            data: formData,
            complete: function (xhr, textStatus) {

                if (xhr.status == 200) {
                    success('ชำระเงินเรียบร้อย')
                } else if (xhr.status == 400) {
                    errDialog('แจ้งเตือน', 'มีการจองห้องพักห้องนี้ในวันที่นี้แล้ว โปรดจองห้องอื่นหรือ วันอื่น', '')
                } else {
                    errDialog('แจ้งเตือน', 'เกิดข้อผิดพลาด', '')
                }

            }
        })
    }
})