<?php
@session_start();
$emp_id = $_SESSION['emp_id'] ?? '';
if (!empty($emp_id)) {
    header('location:./?r=report');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ลงชื่อเข้าสู่ระบบ บ้านคุณเสือแคมป์ปิ้ง</title>
    <script src="../assets/jquery/jquery-3.7.1.min.js"></script>
    <link rel="stylesheet" href="../assets/bootstrap-4.6.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/sweetalert2/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="../assets/fontawesome-free-6.5.1-web/css/all.css">
    <script src="../assets/sweetalert2/dist/sweetalert2.min.js"></script>
    <script src="../assets/js/sweetalert.js"></script>
    <script src="../assets/js/function.js"></script>
    <script src="../assets/js/errValidate.js"></script>

    <style>
        body {
            background: url('../assets/images/background.jpg') no-repeat center center fixed;
            background-size: cover;
        }

        @font-face {
            font-family: 'Sarabun';
            src: url('../assets/fonts/Sarabun-Regular.ttf');
        }

        * {
            box-sizing: border-box;
            font-size: 14px;
            font-family: Sarabun;
        }
    </style>
</head>

<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card  shadow-sm my-2">
                    <div class="card-body">
                        <h5 class="text-center font-weight-bold">ลงชื่อเข้าสู่ระบบ บ้านคุณเสือแคมป์ปิ้ง</h5>
                        <div class="form-group">
                            <label>บัญชีผู้ดูแล</label>
                            <input type="text" class="form-control" id="admin" placeholder="ป้อนชื่อบัญชีผู้ดูแล">

                        </div>
                        <p class="err-validate" id="empty-admin"></p>
                        <div class="form-group">
                            <label>รหัสผ่าน</label>
                            <input type="password" class="form-control" id="password" placeholder="ป้อนรหัสผ่าน">
                        </div>
                        <p class="err-validate" id="empty-password"></p>
                        <div class="custom-control custom-checkbox">
                            <input class="custom-control-input" type="checkbox" id="changePassword">
                            <label for="changePassword" onclick="obscureText('#password')" class="custom-control-label">แสดงรหัสผ่าน</label>
                        </div>
                        <div class="my-1 p-1">
                            <button class="btn btn-teal btn-info w-100" id="login">
                                ลงชื่อเข้าใช้งาน
                            </button>
                        </div>

                    </div>
                </div>
            </div>
        </div>



    </div>

    <script>
        const login = $('#login')

        const loginForm = [{
            'name': 'admin',
            'input': $('#admin'),
            'alert': $('#empty-admin'),
            'msg': 'กรุณาป้อนชื่อบัญชีผู้ดูแล'
        }, {
            'name': 'password',
            'input': $('#password'),
            'alert': $('#empty-password'),
            'msg': 'กรุณาป้อนรหัสผ่าน'
        }]


        login.click(function() {
            let emptyCount = 0
            loginForm.forEach((fd) => {
                const is_pass = fd.input.val() == ''
                emptyCount += is_pass ? 1 : 0
                errValidate(is_pass, fd.alert, fd.msg)
            })
            if (emptyCount == 0) {
                const data = {
                    'admin': loginForm[0].input.val().trim(),
                    'password': loginForm[1].input.val().trim()
                }
                $.ajax({
                    url: './signin_submit.php',
                    type: 'post',
                    data: data,
                    dataType: 'json',
                    complete: function(xhr, textStatus) {
                        let msg = ''
                        let text = ''
                        let is_validate = true
                        try {
                            const data = JSON.parse(xhr.responseText)
                            if (data.result) {
                                location.assign('./?r=report')
                            } else {
                                is_validate = false
                                if (data.is_username == false) {
                                    msg = 'ไม่พบผู้ใช้บัญชีนี้ในระบบ'
                                } else if (data.is_password == false) {
                                    msg = 'รหัสผ่านไม่ถูกต้อง'
                                } else {
                                    msg = data.err
                                }
                            }
                            if (!is_validate) errDialog('แจ้งเตือน', msg, '')

                        } catch (err) {
                            errDialog('ข้อผิดพลาด', msg, err)
                        }

                    }
                })
            }

        })
    </script>
</body>

</html>