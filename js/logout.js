$('#logout').click(function () {
    confirmDialog('ออกจากระบบ', 'คุณต้องการออกจากระบบใช่ หรือไม่ ?')
        .then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: './logout.php',
                    type: 'post',
                    complete: function (xhr, textStatus) {
                        console.log(xhr.status)
                        if (xhr.status == 200) {
                            success('ออกจากระบบสำเร็จ', false)
                            setInterval(() => {
                                location.assign('./')
                            }, 2000)
                        } else {
                            errDialog('ออกจากระบบ', 'เกิดข้อผิดพลาด', '')
                        }

                    }
                })
            }
        });


})