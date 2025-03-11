function resetvationDate() {
    const startDt = $('#startDt').val()
    const endDt = $('#endDt').val()
    const price = parseFloat($('#price').val())
    const additionalCushion = $('#additionalCushion').is(':checked')
    const cushionPrice = 300 // Price for additional cushion

    let dayCount = 0
    let total = 0
    let is_pass = true

    const start_stamp = new Date(`${startDt} 00:00:00`).valueOf()
    const end_stamp = new Date(`${endDt} 00:00:00`).valueOf()
    const now = new Date().toISOString()
    const now_stamp = new Date(`${now.split('T')[0]} 00:00:00`).valueOf()
    const oneDay = 24 * 60 * 60 * 1000
    const ofterDay = start_stamp - (oneDay * 3)

    if (startDt != '') {
        if (ofterDay < now_stamp) {
            errDialog('เลื่อนการจอง', 'โปรดเลืิอกวันที่จองก่อนวันเข้าพักอย่างน้อย 3 วันล่วงหน้า', '')
            $('#startDt').val('')
            $('#endDt').val('')
            return
        }
    }

    if (startDt != '' && endDt != '') {
        if (start_stamp < end_stamp) {
            if (start_stamp >= now_stamp || page) {
                dayCount = (end_stamp - start_stamp) / oneDay
                total = dayCount * price

                // Add cushion price if checked
                if (additionalCushion) {
                    total += cushionPrice
                }
            } else {
                is_pass = false
            }
        } else {
            is_pass = false
        }
    }

    $('#dayCountText').text(dayCount)
    $('#dayCount').val(dayCount)
    $('#totalText').text(getNumberFormat(total))
    $('#total').val(total)
    $('#paidText').text(getNumberFormat(total - price))

    if (!is_pass) {
        $('#startDt').val('')
        $('#endDt').val('')
    }
}

// Add event listener for the checkbox
$(document).ready(function () {
    $('#additionalCushion').change(function () {
        if ($(this).is(':checked')) {
            $('#additionalCushionRow').show();
        } else {
            $('#additionalCushionRow').hide();
        }
        resetvationDate();
    });
});