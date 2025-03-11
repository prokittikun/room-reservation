retainOption($('#roomType').attr('data-type'), $('#roomType'))
$("#thumbnail").change(function () {
    const file = $(this)[0].files;
    if (file.length > 0) {
        const src = URL.createObjectURL(file[0]);
        $("#thumbnailPreview").attr("src", src);
    }
});
$("#img").change(function () {
    let example = ``
    const file = $(this)[0].files;
    if (file.length > 5) {
        $('#img').val('')
        errDialog('แจ้งเตือน', '', 'สามารถอัพโหลดรูปภาพได้สูงสุด 5 ภาพ')
    }
    if (file.length > 0) {
        for (let i = 0; i < file.length; i++) {
            const f = file[i]
            const src = URL.createObjectURL(f);
            const img = `<img class="room-img-preview" src="${src}">`
            example += `<div class="col-auto p-1">${img}</div>`
        }
    }
    $("#imgPreview").html(example)
});


$("#roomHandleSave").click(function () {
    const method = $(this).attr('data-method')
    const id = $(this).attr('data-id')
    const route = method == 'post' ? '/room/insert' : '/room/update/id'
    const meetingRoomForm = [{
        input: [$("#roomName"), $('#roomNumber')],
        validate: $("#validateRoomNameAndNumber"),
        msg: "โปรดป้อนขื่อ หรือ หมายเลขห้องพัก",
        formType: "name",
    }, {
        input: $('#roomType'),
        validate: $("#validateRoomType"),
        msg: "โปรดเลือกประเภทห้องพัก",
        formType: "text",
    },
    {
        input: $("#bedAmount"),
        validate: $("#validateBedAmount"),
        msg: "โปรดป้อนจำนวนเตียง",
        formType: "number",
    },

    {
        input: $("#thumbnail"),
        validate: $("#validateThumbnail"),
        msg: "โปรดอัพโหลดรูปภาพขนาดย่อห้องพัก",
        formType: "file",
    },
    {
        input: $("#img"),
        validate: $("#validateImg"),
        msg: "โปรดอัพโหลดตัวอย่างรูปภาพห้องพัก",
        formType: "file",
    },
    ];

    let validateCount = 0;
    meetingRoomForm.forEach((fd) => {
        let is_pass = false
        let msg = fd.msg
        const { formType, input, validate } = fd
        if (formType == "text") {
            is_pass = input.val().trim() == ''
        }
        if (formType == "name") {
            const [name, no] = $.map(input, (o) => ($(o).val().trim()))
            is_pass = name == '' && no == ''
        }
        if (formType == "number") {
            const n = parseFloat(input.val().trim())
            if (isNaN(n)) {
                is_pass = true
            } else {
                is_pass = n <= 0
                msg = 'ป้อนค่ามากกว่า 0 '
            }

        }

        if (formType == "file" && method == 'post') {
            is_pass = input[0].files.length == 0;
        }
        validateCount += is_pass ? 1 : 0
        errValidate(is_pass, validate, msg);
    });

    if (validateCount == 0) {
        const formData = new FormData();
        formData.append("route", route)
        formData.append("room_name", $("#roomName").val().trim());
        formData.append("room_number", $("#roomNumber").val().trim());
        formData.append("bed_amount", $("#bedAmount").val());
        formData.append("room_type", $("#roomType").val());
        formData.append("price", $("#price").val());
        formData.append("detail", $("#detail").val().trim());
        formData.append("description", $("#description").val().trim());



        const thumbnail = $("#thumbnail")[0].files
        const img = $('#img')[0].files
        if (thumbnail.length > 0) {
            formData.append("thumbnail", thumbnail[0])
        }
        if (img.length > 0) {
            for (let i = 0; i < img.length; i++) {
                formData.append("img[]", img[i]);
            }
        }
        if (method == 'put') {
            formData.append("id", id)
            formData.append('old_img', $('#oldImg').val().trim())
            formData.append('old_img_delete', $('#oldImgDelete').val().trim())
        }
        $.ajax({
            url: roomRoute(),
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
});

function getOldImg() {
    return $('#oldImg').val() != '' ? $('#oldImg').val().split(',') : []
}

function getOldImgDelete() {
    return $('#oldImgDelete').val() != '' ? $('#oldImgDelete').val().split(',') : []
}

$('[name="old-img-delete"]').click(function () {
    const data_img = $(this).attr('data-img')
    let oldImg = getOldImg()
    let oldImgDelete = getOldImgDelete()
    oldImg = oldImg.filter((r) => r != data_img)
    oldImgDelete.push(data_img)


    $('#oldImg').val(oldImg.join(','))
    $('#oldImgDelete').val(oldImgDelete.join(','))
    $(this).parent().remove()

})