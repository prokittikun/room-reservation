function organizationController() {
    return './controller/organization_controller.php'
}

function roomTypeSubmit() {
    return $('#roomTypeSubmit')
}
$('button[name="rt-remove"]').click(function () {
    const id = $(this).attr('data-id');
    confirmDialog('ลบชื่อหน่วยงาน', "ต้องการลบชื่อหน่วยงานนี้ใช่หรือไม่ ?")
        .then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    'url': roomTyopeRoute(),
                    type: 'post',
                    data: {
                        'id': id,
                        'route': '/roomtype/soft_delete/id'
                    },
                    complete: function (xhr, textStatus) {
                        if (xhr.status == 200) {
                            success('ลบข้อมูลเรียบร้อย')
                        } else {
                            errDialog('แจ้งเตือน', '', xhr.responseText)
                        }

                    }
                })
            }
        })
})
$('button[name="rt-update"]').click(function () {
    const id = $(this).data('id');
    $('#roomTypeName').val('');
    hideErrValidate()
    $.ajax({
        'url': roomTyopeRoute(),
        'type': 'post',
        data: {
            'route': '/roomtype/data/id',
            'id': id
        },
        complete: function (xhr, textStatus) {
            try {
                const data = JSON.parse(xhr.responseText)
                if (xhr.status == 200) {
                    $('#roomTypeName').val(data.roomtype[0].room_type_name)
                    roomTypeSubmit().attr('data-id', id).attr('data-method', 'put')
                    $('#roomTypeModal').modal('show')
                } else {
                    errDialog('แจ้งเตือน', '', data.err)
                }
            } catch (err) {
                errDialog('ข้อผิดพลาด', '', err)
            }
        }
    })
})
$('#addRoomType').click(function () {
    roomTypeSubmit().attr('data-method', 'post')
    $("#roomTypeModal").modal("show");
    $('#roomTypeName').val('')
    hideErrValidate()
})

roomTypeSubmit().click(function () {
    const method = $(this).data('method');
    const id = $(this).data('id');
    const room_type_name = $('#roomTypeName').val().trim();
    const validateText = $("#roomTypeNameValidate");
    if (room_type_name == '') {
        errValidate(true, validateText, "กรุณาป้อนประเภทห้องพัก");
    } else {
        errValidate(false, validateText, "");
        const route = method == 'post' ? '/roomtype/insert' : '/roomtype/update'


        let data = {
            'room_type_name': room_type_name,
            'route': route
        }
        if (method == 'put') {
            Object.assign(data, {
                id: id
            })
        }

        $.ajax({
            type: 'post',
            url: roomTyopeRoute(),
            data: data,
            complete: function (xhr, textStatus) {
                if (xhr.status == 200) {
                    success('บันทึกข้อมูลเรียบร้อย')
                } else {
                    errDialog('แจ้งเตือน', xhr.responseText, '')
                }

            }
        })
    }
})