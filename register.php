<?php
$state = $_GET['state'] ?? '';
require_once('./config/config_db.php');
require_once('./function/function.php');
$id = $_GET['id'] ??  '';
$row = [];
$img = [];
if (!empty($id)) {
    $id = base64_decode($id);
    $sql = "SELECT rooms.*,room_type.room_type_id,";
    $sql .= "room_type.room_type_name FROM rooms LEFT JOIN room_type";
    $sql .= " ON rooms.room_type=room_type.room_type_id WHERE rooms.room_id=?";
    $row = getDataById($sql, [$id]);
    $img = !empty($row['img']) ? explode(',', $row['img']) : [];
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
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="cart-title m-0">สมัครสมาชิก</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">ผู้ใช้งาน</label>
                                    <input type="text" class="form-control" maxlength="300" id="username" placeholder="ป้อนขื่อผู้ใช้งาน">

                                </div>
                                <p class="err-validate" id="validateUsername"></p>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">รหัสผ่าน</label>
                                    <input type="password" class="form-control" id="password" placeholder="ป้อนรหัสผ่าน">
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" onchange="obscureText('#password')" value="" id="showPassword">
                                    <label class="form-check-label" for="showPassword">
                                        แสดงรหัสผ่าน
                                    </label>
                                </div>
                                <p class="err-validate" id="validatePassword"></p>
                            </div>
                        </div>

                        <div class="row">

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">ขื่อ</label>
                                    <input type="text" class="form-control" maxlength="300" id="fname" placeholder="ป้อนขื่อ">
                                </div>
                                <p class="err-validate" id="validateFirstname"></p>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">นามสกุล</label>
                                    <input type="text" class="form-control" maxlength="300" id="lname" placeholder="ป้อนนามสกุล">
                                </div>
                                <p class="err-validate" id="validateLastname"></p>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">เบอร์ติดต่อ</label>
                                    <input type="text" class="form-control" max="14" id="tel" placeholder="ป้อนเบอร์ติดต่อ">
                                </div>
                                <p class="err-validate" id="validateTel"></p>
                            </div>
                        </div>
                        <button id="handleRegister" class="btn bg-lightblue w-100">สมัครสมาขิก</button>
                        <div class="text-center my-2">
                            <p class="m-0 p-2">หากมีบัญชีผู้ใช้งานแล้ว ?</p>
                            <a href="./signin.php" class="btn btn-success">

                                <strong>ลงชื่อเข้าใช้</strong>
                                <i class="fa-solid fa-right-to-bracket"></i>
                            </a>

                        </div>
                    </div>
                </div>
            </div>
        </div>






    </div>
    <script src="./js/register.js"></script>
</body>

</html>