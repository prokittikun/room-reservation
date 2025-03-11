retainRadio($('#logoType').val(), $('[name="logotype"]'))
$('#iconFile').change(function () {
    const file = $(this)[0].files[0]
    previewImage(file, $('#previewIcon'))
})

$('#logoFile').change(function () {
    const file = $(this)[0].files[0]
    previewImage(file, $('#previewLogo'))
})
$('[name="logotype"]').change(function () {
    const logotype = $(this).filter(':checked').attr('id')
    const is_disable = logotype == 'logoImage'

    $('#logoFile').prop('disabled', !is_disable)
    $('#logo').prop('disabled', is_disable)
})
function previewImage(file, previewId) {
    let src = ''
    if (file) {
        src = URL.createObjectURL(file)
    }
    previewId.attr('src', src)
}

$('#logoHandleSubmit').click(function () {
    const form = [
        { 'input': $('#title'), 'validate': $('#validateTitle'), 'msg': 'กรุณาป้อนป้อนชื่อเว็บไซต์', 'formtype': 'text' },
        { 'input': $('[name="logotype"]'), 'validate': $('#validateLogoType'), 'msg': 'กรุณาเลือกรูปแบบโลโก้', 'formtype': 'radio' }
    ]
    let validateCount = 0
    form.forEach((fd) => {
        let { msg, input, validate, formtype } = fd
        let is_pass = false
        if (formtype == 'text') {
            is_pass = input.val().trim() == ''
        }
        if (formtype == 'file') {
            is_pass = input[0].files.length == 0
        }
        if (formtype == 'radio') {
            is_pass = input.filter(':checked').length == 0
        }
        validateCount += is_pass ? 1 : 0
        errValidate(is_pass, validate, msg)
    })
    const formData = new FormData()
    const id = $('#logoHandleSubmit').attr('data-id')
    const logo_type = $('[name="logotype"]').filter(':checked').attr('id')
    const validateLogo = $('#validateLogo')
    const validateICon = $('#validateIcon')
    let is_logo = false
    let is_icon = false
    let logo_msg = ''

    const icon = $('#iconFile')
    const iconFile = icon[0].files
    if (iconFile.length == 0) {
        is_icon = icon.attr('data-file') != 'true'
    }

    if (iconFile.length == 1) {
        formData.append('icon', iconFile[0])
    }
    formData.append('title', $('#title').val())
    formData.append('logo_type', logo_type)
    formData.append('route', '/logo/save')

    if (id != '') {
        formData.append('id', id)
    }

    if (logo_type == 'logoImage') {
        const logoFile = $('#logoFile')[0].files
        is_logo = logoFile.length == 0
        logo_msg = 'กรุณาอัพโหลดโลโก้'
        if (!is_logo) {
            console.log('dd')
            formData.append('logo', logoFile[0])
        }
        if (is_logo) {
            is_logo = $('#logoFile').attr('data-file') != 'true'
        }

    }
    if (logo_type == 'logoText') {
        const logo = $('#logo').val().trim()
        is_logo = logo == ''
        logo_msg = 'กรุณาป้อนโลโก้'
        if (!is_logo) {
            console.log('dd')
            formData.append('logo', logo)
        }


    }
    validateCount += is_logo ? 1 : 0
    validateCount += is_icon ? 1 : 0

    errValidate(is_logo, validateLogo, logo_msg)
    errValidate(is_icon, validateICon, form[1].msg)


    if (validateCount == 0) {
        $.ajax({
            url: logoRoute(),
            type: 'post',
            processData: false,
            contentType: false,
            data: formData,
            complete: function (xhr, textStatus) {

                if (xhr.status == 200) {
                    success('บันทึกสำเร็จ')
                } else {
                    errDialog('แจ้งเตือน', xhr.resonseText)
                }

            }
        })
    }

})