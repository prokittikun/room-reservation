$('[name="r-remove"]').click(function () {
    const id = $(this).attr('data-id');
    confirmDialog('ลบข้อมูลห้องข้อมูลห้องพัก', "ต้องการลบข้อมูลรายการนี้ใช่หรือไม่ ?")
        .then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    'url': roomRoute(),
                    type: 'post',
                    data: {
                        'id': id,
                        'route': '/room/soft_delete'
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