$('[name="refund-pay"]').click(function () {
    const id = $(this).attr('data-id')
    $('#refundPayHandleSubmit').attr('data-id', id)
    $('#refundPayModal').modal('show')
})
function updateRefundPay() {
    const pay = parseFloat($('#pay').val())
    const refund_pay = parseFloat($('#refundPay').val())
    let paid = 0
    if (!isNaN(pay) && pay >= 0 && !isNaN(refund_pay) && refund_pay >= 0) {
        if (pay >= refund_pay) {
            paid = pay - refund_pay
        }
    }
    $('#paid').val(paid)
}

$('#refundPayHandleSubmit').click(function () {
    const refundForm = [
        { 'input': $('#pay'), 'msg': 'กรุณาป้อนยอดที่จ่ายเข้ามา', 'validate': $('#validatePay') },
        { 'input': $('#refundPay'), 'msg': 'กรุณาป้อนยอดที่ต้องการคืน', 'validate': $('#validateRefundPay') },
        { 'input': $('#paid'), 'msg': 'กรุณาป้อนยอดที่คงในระบบ', 'validate': $('#validatePaid') },
    ]
    let validateCount = 0
    refundForm.forEach((fd) => {
        const is_pass = fd.input.val() == ''
        errValidate(is_pass, fd.validate, fd.msg)
        validateCount += is_pass ? 1 : 0
    })
    if (validateCount == 0) {

        const id = $('#refundPayHandleSubmit').attr('data-id')
        const data = { 'paid': $('#paid').val(), 'route': '/booking/pay/refund', id }

        $.ajax({
            url: bookingRoute(),
            type: 'post',
            data: data,
            complete: function (xhr, textStatus) {
                if (xhr.status == 200) {
                    success('บันทึกสำเร็จ')
                } else {
                    errDialog('แจ้งเตือน', '', xhr.responseText)
                }

            }
        })
    }
})