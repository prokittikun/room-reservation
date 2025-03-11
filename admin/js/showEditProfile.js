$('#handleSubmit').click(function () {
    const resetvationForm = [
        // { 'input': $('#password'), 'msg': 'กรุณาป้อนรหัสผ่าน', 'validate': $('#validatePassword'),'formtype':'password' },
        { 'input': $('#fname'), 'msg': 'กรุณาป้อนชื่อ', 'validate': $('#validateFirstname') },
        { 'input': $('#lname'), 'msg': 'กรุณาป้อนนามสกุล', 'validate': $('#validateLastname') },
        { 'input': $('#tel'), 'msg': 'กรุณาป้อนเบอร์ติดต่อ', 'validate': $('#validateTel') },
    ]
    let validateCount = 0
    resetvationForm.forEach((fd) => {
        const is_pass = fd.input.val().trim() == ''
        errValidate(is_pass, fd.validate, fd.msg)
        validateCount += is_pass ? 1 : 0

    })


    // const isChangePassword = $('#changePassword').is(':checked')
    // const password = $('#password').val().trim()
    // let is_pass = false
    // let p_msg = 'กรุณาป้อนรหัสผ่าน'
    // console.log(password, isChangePassword)
    // if (isChangePassword) {
    //     is_pass = password == ''
    // }
    // if (password != '') {
    //     const { validate, alert } = validatePassword(password)
    //     is_pass = !validate
    //     p_msg = alert

    // }
    // validateCount += is_pass ? 1 : 0
    // errValidate(is_pass, $('#validatePassword'), p_msg)
    // console.log(validateCount,)
    if (validateCount == 0) {
        let data = {
            'route': '/member/update',
            'fname': $('#fname').val().trim(),
            'lname': $('#lname').val().trim(),
            'tel': $('#tel').val(),
            'id': atob($('#handleSubmit').attr('data-id'))
        }
        // if (isChangePassword) {
        //     Object.assign(data, { 'password': password })
        // }

        $.ajax({
            url: './controller/member_controller.php',
            type: 'post',
            data: data,
            complete: function (xhr, textStatus) {
                if (xhr.status == 200) {
                    success('บันทึกสำเร็จ')
                } else {
                    errDialog('แจ้งเตือน', 'เกิดข้อผิดพลาด', '')
                }

            }
        })
    }
})

$('button[name="edit-user"]').click(function () {
    const id = $(this).data('id');
    $('#fname').val('');
    $('#lname').val('');
    $('#tel').val('');
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