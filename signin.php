<?php
require_once('./config/config_db.php');
require_once('./function/function.php');
@session_start();
$state = $_GET['state'] ?? '';
$member_id = $_SESSION['member_id'] ?? '';

if (!empty($member_id)) {
    header('location:./');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require_once('./logo.php') ?>
    <?php require_once('./head.php') ?>
    <title>ลงชื่อเข้าใช้งาน</title>
</head>

<body>
    <?php require_once('./nav.php') ?>
    <div class="container vh-min-100 my-3">

        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="cart-title m-0">ลงชื่อเข้าสู่ระบบ</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">ผู้ใช้งาน</label>
                            <input type="text" class="form-control" id="username" placeholder="ป้อนขื่อผู้ใช้งาน">
                            <div class="err-validate" id="validateUsername"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">รหัสผ่าน</label>
                            <input type="password" class="form-control" id="password" placeholder="ป้อนรหัสผ่าน">
                            <div class="err-validate" id="validatePassword"></div>
                        </div>
                        <div class="form-check">
                                    <input class="form-check-input" type="checkbox" onchange="obscureText('#password')" value="" id="showPassword">
                                    <label class="form-check-label" for="showPassword">
                                        แสดงรหัสผ่าน
                                    </label>
                                </div>
                        <div class="text-end">
                            <button id="login" class="btn bg-lightblue">ลงชื่อเข้าใช้</button>
                            <a href="./register.php" class="btn btn-success">สมัครสมาขิก</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
   
    <script src="./js/signin.js"></script>
</body>

</html>