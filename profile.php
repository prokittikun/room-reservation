<?php
require_once('./config/config_db.php');
require_once('./function/function.php');
require_once('./function/date.php');
require_once('./pagination.php');

@session_start();
$member_id = $_SESSION['member_id'] ?? '';
$is_login = !empty($member_id) ? 'true' : 'false';

if (empty($member_id)) {
    header('location:./signin.php');
}
$params = ['true'];
$paginate = paginate();
$page = $paginate['page'];
$per_page = $paginate['per_page'];
$row = [];
$sql = "SELECT * FROM member WHERE member_id=?";
if (!empty($member_id)) {
    $row = getDataById($sql, [$member_id]);
}



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require_once('./logo.php') ?>
    <?php require_once('./head.php') ?>
    <title>ประวัติการจอง</title>
</head>

<body>
    <?php require_once('./nav.php') ?>
    <div class="container vh-min-100 my-3">

        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="cart-title m-0">ข้อมูลส่วนตัว</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">ผู้ใช้งาน</label>
                                    <input type="text" disabled class="form-control" value="<?php echo $row['username'] ?? '' ?>" maxlength="300" id="username" placeholder="ป้อนขื่อผู้ใช้งาน">

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
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="changePassword">
                                    <label class="form-check-label" for="changePassword">
                                        เปลี่ยนรหัสผ่าน
                                    </label>
                                </div>
                              
                                <p class="err-validate" id="validatePassword"></p>
                            </div>
                        </div>

                        <div class="row">

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">ขื่อ</label>
                                    <input type="text" class="form-control" value="<?php echo $row['fname'] ?? '' ?>" maxlength="300" id="fname" placeholder="ป้อนขื่อ">
                                </div>
                                <p class="err-validate" id="validateFirstname"></p>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">นามสกุล</label>
                                    <input type="text" class="form-control" value="<?php echo $row['lname'] ?? '' ?>" maxlength="300" id="lname" placeholder="ป้อนนามสกุล">
                                </div>
                                <p class="err-validate" id="validateLastname"></p>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">เบอร์ติดต่อ</label>
                                    <input type="text" class="form-control" max="14" value="<?php echo $row['tel'] ?? '' ?>" id="tel" placeholder="ป้อนเบอร์ติดต่อ">
                                </div>
                                <p class="err-validate" id="validateTel"></p>
                            </div>
                        </div>
                        <button data-id="<?php echo base64_encode($member_id) ?>" id="handleSubmit" class="btn btn-success w-100">บันทึก</button>
                    </div>
                </div>
            </div>
        </div>






    </div>

    <?php require_once('./slippayment_modal.php') ?>
    <?php require_once('./postpone_modal.php') ?>
    <script src="./js/profile.js"></script>
    <script src="./js/resetvationDate.js"></script>
    <script src="./js/slipPayment.js"></script>
</body>

</html>