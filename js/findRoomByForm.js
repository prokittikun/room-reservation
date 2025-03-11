retainOption($('#roomType').attr('data-type'), $('#roomType'))
$('#findRoomByForm').click(function () {
    const reservationForm = [
        { 'input': $('#checkin'), 'msg': 'กรุณาเลือกวันที่เริ่มต้น', 'validate': $('#validateCheckin') },
        { 'input': $('#checkout'), 'msg': 'กรุณาเลือกวันที่สิ้นสุด', 'validate': $('#validateCheckout') },
    ]
    let validateCount = 0
    reservationForm.forEach((fd) => {
        const is_pass = fd.input.val() == ''
        errValidate(is_pass, fd.validate, fd.msg)
        validateCount += is_pass ? 1 : 0
    })

    if (validateCount == 0) {
        const checkin = reservationForm[0].input.val()
        const checkout = reservationForm[1].input.val()
        let is_check = true
        let msg = ''
        if (checkin != '' && checkout != '') {
            const checkin_stamp = new Date(`${checkin} 00:00:00`).valueOf()
            const checkout_stamp = new Date(`${checkout} 00:00:00`).valueOf()
            const now = new Date().toISOString().split('T')[0]
            const now_stamp = new Date(`${now} 00:00:00`).valueOf()


            if (checkin_stamp < now_stamp) {
                is_check = false
                msg = 'โปรดเลือกวันที่ปัจจุบัน'
            }
            if (now_stamp >= checkin_stamp) {
                if (checkout_stamp <= checkin_stamp) {
                    is_check = false
                    msg = 'วันที่เริ่มต้นต้องมากกว่าวันที่สิ้นสุด'
                }
            }

        }
        if (!is_check) {
            errDialog('แจ้งเตือน', msg, '')
        }
        if (is_check) {
            let r = `./?checkin=${checkin}&checkout=${checkout}`
            const roomtype = $('#roomType').val()
            r += roomtype != '' ? `&room_type=${roomtype}` : ''
            location.assign(r)
        }
    }

})


function getDateStamp(date) {
    return new Date(`${date} 00:00:00`).valueOf()
}
const params = getParam(location.search)
$('[name="reserv-btn"]').click(function () {
    const is_login = $(this).attr('data-login') == 'true'
    const id = $(this).attr('data-id')

    const checkin = params.get('checkin')
    const checkout = params.get('checkout')
    const room_type = params.get('room_type')

    if (!is_login) {


        let state = ``
        state += checkin && checkout != '' ? `./?checkin=${checkin}` : ''
        state += checkout && checkout != '' ? `&checkout=${checkout}` : ''
        state += room_type && room_type != '' ? `&room_type=${room_type}` : ''
        state += `&id=${id}&is_reserv=true`
        location.assign(`./signin.php?state=${btoa(state)}`)
    }
    if (is_login) {
        addReservation(id, 'reserv')
    }
})

function addReservation(id, acton) {
    const is_reserv = params.get('is_reserv')
    const is_pass = acton == 'reserv' || is_reserv && is_reserv == 'true'

    const checkin = params.get('checkin')
    const checkout = params.get('checkout')
    const checkin_stamp = getDateStamp(checkin)
    const checkout_stamp = getDateStamp(checkout)
    const day_count = (checkout_stamp - checkin_stamp) / (1000 * 60 * 60 * 24)
    if ((is_pass)) {
        $.ajax({
            url: './controller/reservation_controller.php',
            type: 'post',
            data: {
                'route': '/booking/available', 'room_id': atob(id),
                'start_dt': checkout, 'end_dt': checkout
            },
            complete: function (xhr, textStatus) {

                try {

                    if (xhr.status == 200) {
                        const response = JSON.parse(xhr.responseText)
                        const is_available = response.is_available
                        if (is_available) {
                            $.ajax({
                                url: './controller/room_controller.php',
                                type: 'post',
                                data: { 'route': '/room/data/id', 'id': atob(id) },
                                complete: function (xhr, textStatus) {

                                    try {
                                        const response = JSON.parse(xhr.responseText)
                                        if (xhr.status == 200) {

                                            const { thumbnail, room_number, room_name, price } = response.room[0]

                                            const total = price * day_count
                                            $('#roomId').val(btoa(id))
                                            $('#startDt').val(checkin)
                                            $('#endDt').val(checkout)
                                            $('#dayCount').val(day_count)
                                            $('#total').val(total)
                                            $('#dayCountText').text(day_count)
                                            $('#totalText').text(getNumberFormat(total))
                                            $('#reservationHandleSubmit').attr('data-id', id)
                                            let roomCard = ``
                                            roomCard += `<div class="row">`
                                            roomCard += `<div class="col-md-4">`
                                            roomCard += `<img src="./assets/images/thumb/${thumbnail}" class="card-img-top" style="width: 100%;object-fit:cover;height:6rem;">`
                                            roomCard += `</div>`
                                            roomCard += `<div class="col-md-8">`
                                            roomCard += `<h5 class="">${room_name} ${room_number}</h5>`
                                            roomCard += `</div>`
                                            roomCard += `</div>`




                                            $('#roomCard').html(roomCard)
                                            $('#reservModal').modal('show')
                                        } else {
                                            errDialog('แจ้งเตือน', 'เกิดข้อผิดพลาด', '')
                                        }
                                    } catch (error) {
                                        errDialog('แจ้งเตือน', 'เกิดข้อผิดพลาด', '')
                                    }



                                }
                            })
                        }
                    } else {
                        errDialog('แจ้งเตือน', 'เกิดข้อผิดพลาด', '')
                    }
                } catch (error) {
                    errDialog('แจ้งเตือน', 'เกิดข้อผิดพลาด', '')
                }



            }
        })


    }
}
window.addEventListener('DOMContentLoaded', () => {
    const id = params.get('id')
    addReservation(id, 'load')
})

$('#reservationHandleSubmit').click(function () {

    const formData = new FormData();
    formData.append("route", '/booking/insert')
    formData.append("room_id", atob($('#reservationHandleSubmit').attr('data-id')));
    formData.append("day_count", $("#dayCount").val());
    formData.append("total", $("#total").val());
    formData.append("start_dt", $("#startDt").val());
    formData.append("end_dt", $("#endDt").val());
    formData.append("additional", $("#additionalCushion").is(':checked'));

    $.ajax({
        url: './controller/reservation_controller.php',
        type: 'post',
        processData: false,
        contentType: false,
        data: formData,
        complete: function (xhr, textStatus) {

            try {

                if (xhr.status == 200) {
                    const response = JSON.parse(xhr.responseText)
                    success('บันทึกสำเร็จ', false)
                    setTimeout(() => {
                        location.assign(`./reservation.php?id=${response.id}`)
                    },2000)
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

})
