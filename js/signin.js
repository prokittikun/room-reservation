$('#login').click(function () {
    const loginForm = [{
        'input': $('#username'),
        'validate': $('#validateUsername'),
        'msg': 'กรุณาป้อนชื่อผู้ใข้งาน'
    },
    {
        'input': $('#password'),
        'validate': $('#validatePassword'),
        'msg': 'กรุณาป้อนรหัสผ่าน'
    }]
    let validateCount = 0
    loginForm.forEach((fd) => {
        const v = fd.input.val().trim()
        const is_pass = v == ''
        validateCount += is_pass ? 1 : 0
        errValidate(is_pass, fd.validate, fd.msg)
    })


    if (validateCount == 0) {
        $.ajax({
            url: './signin_submit.php',
            type: 'post',
            data: {
                'username': $('#username').val().trim(),
                'password': $('#password').val().trim()
            },
            complete: function (xhr, textStatus) {
                if (xhr.status == 200) {
                    const params = getParam(location.search)
                    const state = params.get('state')
                    const r = atob(state)
                    const to = state ? r : './'
                    location.assign(to)
                } else if (xhr.status == 401) {
                    errDialog('ลงชื่อเข้าสู่ระบบ', 'ไม่พบบัญชีผู้ใช้งานนี้', '')
                } else if (xhr.status == 403) {
                    errDialog('ลงชื่อเข้าสู่ระบบ', 'รหัสผ่านไม่ถูกต้อง', '')
                } else {
                    errDialog('ลงชื่อเข้าสู่ระบบ', 'เกิดข้อผิดพลาด', '')
                }

            }
        })
    }

})