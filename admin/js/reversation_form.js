retainOption($('#status').attr('data-status'), $("#status"))
retainOption($('#payStatus').attr('data-status'), $("#payStatus"))
$("#additionalCushion").prop('checked', $("#additionalCushion").attr('data-check') == 'true')
$('#reservationHandleSubmit').click(function () {
    const resetvationForm = [
        { 'input': $('#status'), 'msg': 'กรุณาเลือกสถานะการจอง', 'validate': $('#validateStatus') },
        { 'input': $('#payStatus'), 'msg': 'กรุณาเลือกสถานะการชำระเงิน', 'validate': $('#validatePayStatus') },

    ]
    let validateCount = 0
    resetvationForm.forEach((fd) => {
        const is_pass = fd.input.val() == ''
        errValidate(is_pass, fd.validate, fd.msg)
        validateCount += is_pass ? 1 : 0
    })


    if (validateCount == 0) {
        const formData = new FormData();
        formData.append("route", '/booking/update/id')
        formData.append("id", $('#reservationHandleSubmit').attr('data-id'));
        formData.append("day_count", $("#dayCount").val());
        formData.append("total", $("#total").val());
        formData.append("start_dt", $("#startDt").val());
        formData.append("end_dt", $("#endDt").val());
        formData.append("status", $("#status").val());
        formData.append("pay_status", $("#payStatus").val());
        formData.append('slip_payment_delete', $('#slipPaymentDeleteData').val());
        formData.append('slip_payment', $('#slipPaymentData').val());
        formData.append("additional", $("#additionalCushion").is(':checked'));



        $.ajax({
            url: bookingRoute(),
            type: 'post',
            processData: false,
            contentType: false,
            data: formData,
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
$('[name="slippayment-delete"]').click(function () {
    const btn = $(this).parent().remove()
    const src = $(this).attr('data-src')
    let slipPaymentData = $('#slipPaymentData').val().split(',')
    let slipPaymentDeleteData = $('#slipPaymentDeleteData').val().split(',').filter((f) => f != '')

    slipPaymentDeleteData.push(src)
    slipPaymentData = slipPaymentData.filter((f) => f != src)
    $('#slipPaymentData').val(slipPaymentData.join(','))
    $('#slipPaymentDeleteData').val(slipPaymentDeleteData.join(','))
})
