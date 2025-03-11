function displaySlipEdit(status) {
    const _is = status ? 'inline' : 'none'
    $('#slipPaymentHandleDelete').css('display', _is)
    $('[name="slippayment-btn"]').css('display', _is)
}
$('[name="confirm-payslip"]').click(function () {
    displaySlipEdit(false)
    const id = $(this).attr('data-id')
    $.ajax({
        url: bookingRoute(),
        type: 'post',
        data: { 'route': '/booking/data/id', id },
        complete: function (xhr, textStatus) {
            console.log(xhr.status)
            try {
                const response = JSON.parse(xhr.responseText)
                const { reservation } = response

                if (xhr.status == 200) {
                    const slip_payment = reservation.slip_payment.split(',').filter((f) => f != '')
                    let slipPaymentEl = ``
                    console.log(slip_payment)
                    slip_payment.forEach((s, i) => {
                        const src = `../assets/images/slip_payment/${s}`
                        slipPaymentEl += `<div class="col-md-4">`
                        slipPaymentEl += `<button name="slippayment-btn" data-src="${s}" onclick="deleteSlipPayment('${s}')" class="btn btn-danger"><i class="fa-solid fa-xmark"></i></button>`
                        slipPaymentEl += `<img src="${src}" style="width: 100%;object-fit:contain;"></img>`
                        slipPaymentEl += `</div>`
                    })
                    $('#slipPaymentHandleDelete').attr('data-id', id)
                    $('#slipPaymentData').val(slip_payment.join(','))
                    $('#slipPaymentPreview').html(slipPaymentEl)
                    $('#slipPaymentModal').modal('show')
                } else {
                    errDialog('หลักฐานการชำระเงิน', 'เกิดข้อผิดพลาด', '')
                }
            } catch (error) {
                errDialog('หลักฐานการชำระเงิน', 'เกิดข้อผิดพลาด', '')
            }


        }
    })
})

function deleteSlipPayment(src) {
    const btn = $('[name="slippayment-btn"]').filter(`[data-src="${src}"]`).parent().remove()
    let slipPaymentData = $('#slipPaymentData').val().split(',')
    let slipPaymentDeleteData = $('#slipPaymentDeleteData').val().split(',').filter((f) => f != '')

    slipPaymentDeleteData.push(src)
    slipPaymentData = slipPaymentData.filter((f) => f != src)
    $('#slipPaymentData').val(slipPaymentData.join(','))
    $('#slipPaymentDeleteData').val(slipPaymentDeleteData.join(','))
}

$('#slipPaymentEdit').click(function () {
    displaySlipEdit(true)
})

$('#slipPaymentHandleDelete').click(function () {
    const id = $(this).attr('data-id')
    $.ajax({
        url: bookingRoute(),
        type: 'post',
        data: {
            'route': '/booking/slip/delete/id', id,
            'slip_payment_delete': $('#slipPaymentDeleteData').val(),
            'slip_payment': $('#slipPaymentData').val()
        },
        complete: function (xhr, textStatus) {

            if (xhr.status == 200) {
                success('บันทึกข้อมูลเรียบร้อย')
            } else {
                errDialog('หลักฐานการชำระเงิน', 'เกิดข้อผิดพลาด', '')
            }

        }
    })
})