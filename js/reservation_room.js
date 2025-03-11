function calendarDateMonth() {
    return $("#calendar-date-month");
}



function yearInput() {
    return $("#year");
}
$('#calendarFind').click(function () {
    const m = calendarDateMonth().val()
    const y = yearInput().val()
    if (m != '' && y != '') {
        location.assign(`./reservation_room.php?id=${params.get('id')}&m=${m}&y=${y}`)
    }
})


const params = new URLSearchParams(location.search)
function creteCalendar() {

    const m = params.get("m");
    const y = params.get("y");

    const now = new Date();
    const month = m ? parseInt(m) : parseInt(now.getMonth());
    const year = y ?? now.getFullYear();
    calendarDateMonth().val(month);
    yearInput().val(year);
    let date = new Date();
    const day = date.getDate();
    const sum_month = new Date(year, month + 1, 0).getDate();
    const startMonth = new Date(year, month, 1).getDay();


    const colspan = startMonth;
    const sumWeek = Math.ceil(sum_month / 7);
    let calendar = ``;
    let d = 0;
    for (let i = 0; i < sumWeek; i++) {
        let r = "";
        if (i == 0) {
            r += `<tr>`;

            for (let p = 0; p < colspan; p++) {
                r += `<td class="cr-date"></td>`;
            }

            for (let c = 0; c < colspan + 7; c++) {
                if (c + 1 < 7 - colspan + 1) {
                    let t = i + (c + 1);
                    r += `<td class="cr-date" data-date="${year}-${getCountDate(month + 1)}-${getCountDate(t)}"><span>${t}</span></td>`;
                    d = t;
                }
            }

            r += `</tr>`;
        }
        if (i >= 1 && i < sumWeek - 1) {
            r += `<tr>`;
            let t = 0;
            for (let c = 0; c < 7; c++) {
                t = d + (c + 1);
                r += `<td class="cr-date" data-date="${year}-${getCountDate(month + 1)}-${getCountDate(t)}"><span>${t}</span></td>`;
            }
            d = t;
            r += `</tr>`;
        }
        if (i == sumWeek - 1) {
            r += `<tr>`;
            let t = 0;
            for (let c = 0; c < 7; c++) {
                if (d + (c + 1) <= sum_month) {
                    t = d + (c + 1);
                    r += `<td class="cr-date" data-date="${year}-${getCountDate(month + 1)}-${getCountDate(t)}"><span>${t}</span></td>`;
                }
            }
            d = t;
            r += `</tr>`;
            if (d < sum_month) {
                for (let c = 0; c < 7; c++) {
                    if (d + (c + 1) <= sum_month) {
                        t = d + (c + 1);
                        r += `<td class="cr-date" data-date="${year}-${getCountDate(month + 1)}-${getCountDate(t)}"><span>${t}</span></td>`;
                    }
                }
            }
        }
        calendar += r;
    }
    $("#calendarBody").html(calendar);

    const _m = getCountDate(month + 1)
    const start_m_dt = `${year}-${_m}-01`
    const end_m_dt = `${year}-${_m}-${getCountDayOfMonth(y, month + 1)}`
    const data = {
        'route': '/booking/reservat',
        'start_dt': start_m_dt,
        'end_dt': end_m_dt,
        'room_id': atob(params.get('id'))
    }


    $.ajax({
        url: './controller/reservation_controller.php',
        type: 'post',
        data: data,
        complete: function (xhr, textStatus) {
            try {
                const data = JSON.parse(xhr.responseText)
                const reservat_calendar = data.reservat_calendar
                let reservat_list = []
                if (xhr.status == 200) {

                    reservat_calendar.forEach((d) => {
                        const _ds = d.start_dt
                        const _de = d.end_dt
                        const _ds_stamp = new Date(`${_ds} 00:00:00`).valueOf()
                        const _de_stamp = new Date(`${_de} 00:00:00`).valueOf()
                        const oneDay = 1000 * 60 * 60 * 24
                        for (let h = _ds_stamp; h <= _de_stamp; h += oneDay) {

                            const dt = moment(h).format('YYYY-MM-DD')
                            if (!reservat_list.includes(dt)) {
                                reservat_list.push(dt)
                            }
                        }

                    });
                    $.each($(".cr-date"), (idx, c) => {
                        reservat_list.forEach((d) => {
                            if (d == $(c).data("date")) {
                                const __dt = $(c).data("date").split('-')[2]
                                let btn = `<i class="fa-solid fa-circle-check" style="font-size:1.2rem;"></i>`
                                btn += `<span class="ms-2">${__dt}</span>`
                                $(c).html(btn)
                            }
                        });
                    });

                } else {
                    errDialog('แจ้งเตือน', '', data.err)
                }
            } catch (err) {
                errDialog('แจ้งเตือน', '', err)
            }
        }
    })
}

document.addEventListener('DOMContentLoaded', () => {
    creteCalendar()
    const path = location.href
    if (path.indexOf('#') >= 0) {
        $('#reservationForm').css('display', 'block')
    }
})
$('#reservationBtn').click(function () {
    const is_login = $(this).attr('data-login') == 'true'
    $('#reservationForm').css('display', 'block')
    if (!is_login) {
        const params = getParam(location.search)
        const id = params.get('id')
        const state = `./room_detail.php?$id=${id}#reservationForm`
        location.assign(`./signin.php?state=${btoa(state)}`)
    }
})


$('#slipPayment').change(function () {
    const file = $(this)[0].files[0]
    let src = ''
    if (file) {
        src = URL.createObjectURL(file)
    }
    $('#slipPaymentPreview').attr('src', src)
})

$('#reservationHandleSubmit').click(function () {
    const resetvationForm = [
        { 'input': $('#startDt'), 'msg': 'กรุณาเลือกวันที่เริ่มต้น', 'validate': $('#validateStartDt') },
        { 'input': $('#endDt'), 'msg': 'กรุณาเลือกวันที่สิ้นสุด', 'validate': $('#validateEndDt') },
    ]
    let validateCount = 0
    resetvationForm.forEach((fd) => {
        const is_pass = fd.input.val() == ''
        errValidate(is_pass, fd.validate, fd.msg)
        validateCount += is_pass ? 1 : 0
    })


    if (validateCount == 0) {
        const formData = new FormData();
        formData.append("route", '/booking/insert')
        formData.append("room_id", atob($('#reservationHandleSubmit').attr('data-id')));
        formData.append("day_count", $("#dayCount").val());
        formData.append("total", $("#total").val());
        formData.append("start_dt", $("#startDt").val());
        formData.append("end_dt", $("#endDt").val());
        formData.append("additional", $("#additionalCushion").is(':checked'));
        // formData.append("additional_price", $("#additionalCushion").is(':checked') ? 300 : 0);
        $.ajax({
            url: './controller/reservation_controller.php',
            type: 'post',
            processData: false,
            contentType: false,
            data: formData,
            complete: function (xhr, textStatus) {

                try {
                    const response = JSON.parse(xhr.responseText)
                    if (xhr.status == 200) {
                        success('บันทึกสำเร็จ', false)
                        setTimeout(() => {
                            location.assign(`./reservation.php?id=${response.id}`)
                        }, 2000)
                    } else if (xhr.status == 400) {
                        errDialog('แจ้งเตือน', 'มีการจองห้องพักห้องนี้ในวันที่นี้แล้ว โปรดจองห้องอื่นหรือ วันอื่น', '')
                    } else {
                        errDialog('แจ้งเตือน', 'เกิดข้อผิดพลาด', '')
                    }
                } catch (error) {
                    errDialog('แจ้งเตือน', 'เกิดข้อผิดพลาด', '')
                }



            }
        })
    }
})