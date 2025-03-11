<?php
require_once('./config/config_db.php');
require_once('./function/function.php');
@session_start();
$member_id = $_SESSION['member_id'] ?? '';
if (empty($member_id)) {
    header('location:./signin.php');
}

$id = $_GET['id'] ??  '';
$row = [];
$img = [];
if (!empty($id)) {
    $id = base64_decode($id);
    $sql = "SELECT rooms.room_id,rooms.room_name,rooms.room_number,rooms.thumbnail,";
    $sql .= "rooms.room_type,room_type.room_type_id,room_type.room_type_name,";
    $sql .= "member.member_id,member.fname,member.lname,";
    $sql .= "reservations.*";
    $sql .= " FROM reservations INNER JOIN member ON ";
    $sql .= " reservations.member_id=member.member_id ";
    $sql .= " LEFT JOIN rooms ON ";
    $sql .= " rooms.room_id=reservations.room_id";
    $sql .= " LEFT JOIN room_type ON ";
    $sql .= " room_type.room_type_id=rooms.room_type";
    $sql .= " WHERE reservations.reservation_id=?";
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
    <script src="./assets/AdminLTE-3.2.0/plugins/moment/moment.min.js"></script>
    <title><?php echo $row['room_name'] . " " . $row['room_number'] ?></title>
</head>

<body>
    <?php require_once('./nav.php') ?>
    <div class="container vh-min-100 my-3">
        <div class="row">
            <div class="col-md-8">
                <div class="card card-body bg-light my-3">
                    <div class="row my-3">
                        <div class="col-md-1">
                            <img src="./assets/images/thumb/<?php echo $row['thumbnail'] ?>" style="width: 100%;">
                        </div>
                        <div class="col-md-7">
                            <h5><?php echo $row['room_name'] . " " . $row['room_number'] ?></h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">




            <div class="card">
                <div class="card-header align-items-center bg-lightblue text-light">
                    <h5 class="cart-title m-0">ข้อมูลการจอง</h5>
                </div>
                <div class="card-body">
                    <p>
                        <span>เลขที่จอง</span>
                        <span class="text-danger"><?php echo $row['reservation_id'] ?></span>
                    </p>
                    <div class="row">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">วันที่</label>
                                    <input type="text" disabled value="<?php echo $row['start_dt'] ?>" class="form-control border-0" id="startDt">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">ถึง</label>
                                    <input type="text" disabled value="<?php echo $row['end_dt'] ?>" class="form-control border-0" id="endDt">
                                </div>
                            </div>
                        </div>
                        <p class="m-0">
                            <strong>จำนวนวัน</strong>
                            <span class="text-danger  mx-2" id="dayCountText"><?php echo $row['day_count'] ?></span>
                            <span>คืน</span>
                        </p>
                        <input type="hidden" id="dayCount">
                        <input type="hidden" id="total">
                        <p class="m-0">
                            <strong>ยอดรวม</strong>
                            <span id="totalText" class="text-success"><?php echo number_format($row['total'], 2) ?></span>
                            <span>บาท</span>
                        </p>
                        <p class="m-0">
                            <strong>มัดจำ 50%</strong>
                        </p>
                        <p class="m-0">
                            <strong>ยอดรวม</strong>
                            <span id="totalText" class="text-success"><?php echo number_format($row['total']/2, 2) ?></span>
                            <span>บาท</span>
                        </p>
                        <?php if ($row['pay_status'] == 'unpaid') { ?>
                            <?php require_once('./payment.php') ?>
                            <div class="my-2">
                                <label for="slipPayment" class="form-label">อัพโหลดหลักฐานการชำระเงิน</label>
                                <input class="form-control" type="file" id="slipPayment" multiple accept="image/*">
                            </div>
                            <p class="err-validate" id="validateSlipPayment"></p>
                            <div class="my-2">
                                <div class="row" id="slipPaymentPreview" style="height: 16rem;overflow-y:scroll;">

                                </div>

                            </div>
                            <button class="btn btn-success" data-id="<?php echo base64_encode($id) ?>" id="reservationHandleSubmit">ชำระเงิน</button>
                        <?php    } ?>


                    </div>
                </div>
            </div>




        </div>


    </div>
    <script src="./js/reservation.js"></script>
</body>

</html>