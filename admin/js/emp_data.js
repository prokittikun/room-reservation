$('button[name="emp-remove"]').click(function () {
    const id = $(this).attr('data-id');
    confirmDialog('ลบข้อมูลสมาชิก', "ต้องการข้อมูลรายการนี้ใช่หรือไม่ ?")
        .then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    'url': employeeRoute(),
                    type: 'post',
                    data: {
                        'id': id,
                        'route': '/emp/soft_delete'
                    },
                    complete: function (xhr, textStatus) {

                        if (xhr.status == 200) {
                            success('ลบข้อมูลเรียบร้อย')
                        } else {
                            errDialog('แจ้งเตือน', '', data.err)
                        }

                    }
                })
            }
        })
})