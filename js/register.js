$('#username').keyup(function () {
    const username = $(this).val().trim()
    if (username != '') {
        const is_username = isUsernameThaiLetter(username)
        errValidate(!is_username, $('#validateUsername'), 'กรุณาป้อนเป็นอักษรภาษาอังกฤษเท่านั้น')
        if (!is_username) {
            $('#username').val(username.substring(0, username.length - 1))
        }
    }

})
$('#password').keyup(function () {
    const pass = $(this).val().trim()
    if (pass != '') {
        const { validate, alert } = validatePassword(pass)
        errValidate(!validate, $('#validatePassword'), alert)
    }

})

$('#handleRegister').click(function () {
    console.log('r')
    const resetvationForm = [
        { 'input': $('#username'), 'msg': 'กรุณาป้อนผู้ใช้งาน', 'validate': $('#validateUsername') },
        { 'input': $('#password'), 'msg': 'กรุณาป้อนรหัสผ่าน', 'validate': $('#validatePassword') },
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

    const password = $('#password').val().trim()
    if (password != '') {
        const { validate, alert } = validatePassword(password)
        errValidate(!validate, $('#validatePassword'), alert)
        validateCount += !validate ? 1 : 0
    }

    if (validateCount == 0) {
        const data = {
            'route': '/member/insert',
            'username': $('#username').val().trim(),
            'password': $('#password').val().trim(),
            'fname': $('#fname').val().trim(),
            'lname': $('#lname').val().trim(),
            'tel': $('#tel').val()
        }

        $.ajax({
            url: './controller/member_controller.php',
            type: 'post',
            data: data,
            complete: function (xhr, textStatus) {
                if (xhr.status == 200) {
                    success('บันทึกสำเร็จ', false)
                    const params = getParam(location.search)
                    const state = params.get('state')
                    let r = `./signin.php?`
                    r += state ? `state=${state}` : ''
                    location.assign(r)
                } else if (xhr.status == 401) {
                    errDialog('แจ้งเตือน', 'มีบัญชีผู้ใช้นี้อยู่ในระบบแล้ว โปรดใช้ชื่อใช้ชื่ออื่น', '')
                } else {
                    errDialog('แจ้งเตือน', 'เกิดข้อผิดพลาด', '')
                }

            }
        })
    }
})