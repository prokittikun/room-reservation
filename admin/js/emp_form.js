retainOption($('#role').attr('data-role'), $('#role'))
$('#username').keyup(function () {
    const username = $(this).val().trim()
    if (username != '') {
        const is_username = isUsernameThaiLetter(username)
        errValidate(!is_username, $('#usernameValidate'), 'กรุณาป้อนเป็นอักษรภาษาอังกฤษเท่านั้น')
        if (!is_username) {
            $('#username').val(username.substring(0, username.length - 1))
        }
    }

})


function userHandleSubmit() {
    return $('#empHandleSubmit')
}
userHandleSubmit().click(function () {
    const emplForm = [
        {
            formtype: "text",
            input: $("#role"),
            validate: $("#roleValidate"),
            msg: "กรุณาเลือกบาทบาทหรือขอบเขตผู้ใช้งาน",
        },
        {
            formtype: "text",
            input: $("#fname"),
            validate: $("#fnameValidate"),
            msg: "กรุณาป้อนชื่อ",
        },
        {
            formtype: "text",
            input: $("#lname"),
            validate: $("#lnameValidate"),
            msg: "กรุณาป้อนนามสกุล",
        },
        {
            formtype: "text",
            input: $("#username"),
            validate: $("#usernameValidate"),
            msg: "กรุณาป้อนชื่อบัญชีผู้ใช้งาน",
        },
        {
            formtype: "password",
            input: $("#password"),
            validate: $("#passwordValidate"),
            msg: "กรุณาป้อนรหัสผ่าน",
        },
    ];
    const id = userHandleSubmit().attr('data-id')
    const method = userHandleSubmit().attr('data-method')

    const route = method == 'post' ? '/emp/insert' : '/emp/update/id'
    const is_password = $("#changePassword").is(':checked')
    let validateCount = 0;
    emplForm.forEach((fd) => {
        const {
            input,
            validate,
            formtype
        } = fd;

        let msg = fd.msg
        let is_validate = false
        if (formtype == "text") {

            const value = input.val().trim();
            if (value == "") {


                console.log('ddddd')
                validateCount++;
                is_validate = true


            }

        }
        
        if (formtype == "password" && (method == 'post' || is_password)) {
            const pass = input.val().trim();
            if (pass == "") {
                validateCount++;
                is_validate = true
            }

            if (pass != "") {
                const result = validatePassword(pass);
                const validateErr = result.validate;
                const err = result.alert;
                if (!validateErr) {
                    validateCount++;
                    is_validate = true
                    msg = err
                }
                if (validateErr) {
                    is_validate = false
                    msg = ''
                }
            }
        }
        errValidate(is_validate, validate, msg);
    });
    const username = emplForm[3].input.val()
    const validateUsername = emplForm[3].validate


    if (username != '') {
        if (username.toLocaleLowerCase() == 'admin') {
            validateCount++
            errValidate(true, validateUsername, 'ไม่สามารถใช้งานนี้ได้ โปรดใช้ชื่ออื่น')
        }

    }
    if (validateCount == 0) {
        const formData = new FormData(); // 2
        formData.append("route", route);
        formData.append("fname", $("#fname").val());
        formData.append("lname", $("#lname").val());
        formData.append("username", $("#username").val());
        formData.append("role", $("#role").val());


        if (is_password && method == 'put') {
            formData.append("password", $("#password").val());
        }
        if (method == 'post') {
            formData.append("password", $("#password").val());
        }

        if (method == 'put') {
            formData.append("id", id);
        }


        $.ajax({
            url: employeeRoute(),
            type: 'post',
            processData: false,
            contentType: false,
            data: formData,
            complete: function (xhr, textStatus) {

                if (xhr.status == 200) {
                    success('บันทึกสำเร็จ')
                } else if (xhr.status == 401) {
                    errDialog('ข้อมูลผู้ดูแลระบบ', 'มีผู้ใช้งานนี้ในระบบแล้ว', '')
                } else {
                    errDialog('แจ้งเตือน', '', data.err)
                }

            }
        })
    }
});