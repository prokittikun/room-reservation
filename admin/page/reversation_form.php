<?php
$id = $_GET['id'] ?? '';
$row = [];
$slip_payment = [];
$additional = false;
if (!empty($id)) {
    $sql = "SELECT rooms.room_id,rooms.room_name,rooms.room_number,";
    $sql .= "rooms.thumbnail,";
    $sql .= "rooms.room_type,room_type.room_type_id,room_type.room_type_name,";

    $sql .= "member.member_id,member.fname,member.lname,member.tel,";
    $sql .= "reservations.*";
    $sql .= " FROM reservations INNER JOIN member ON ";
    $sql .= " reservations.member_id=member.member_id ";
    $sql .= " LEFT JOIN rooms ON ";
    $sql .= " rooms.room_id=reservations.room_id";
    $sql .= " LEFT JOIN room_type ON ";
    $sql .= " room_type.room_type_id=rooms.room_type";
    $sql .= " WHERE reservations.reservation_id =? ";
    $row = getDataById($sql, [$id]);
    $additional = $row['additional'];
    $slip_payment = !empty($row['slip_payment']) ? explode(',', $row['slip_payment']) : [];
}
?>

<div class="card">
    <div class="card-header align-items-center bg-gradient-lightblue">
        <p class="cart-title m-0 font-weight-bold">ข้อมูลการจอง</p>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-2">
                <img src="../assets/images/thumb/<?php echo $row['thumbnail']  ?>" style="width:100%;object-fit:contain;">
            </div>
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header bg-light">
                        <p class="card-title font-weight-bold">ข้อมูลห้องพัก</p>
                    </div>
                    <div class="card-body">
                        <h4 class="text-muted"><?php echo $row['room_name'] . " " . $row['room_type_name'] ?></h4>
                    </div>
                </div>
            </div>
        </div>



        <div class="row">
            <div class="col-auto">
                <label for="">สถานะการจอง</label>
                <select class="custom-select font-weight-bold" id="status" data-status="<?php echo $row['status'] ?? '' ?>">
                    <option value="" selected>เลือก</option>
                    <option value="progress">รอการยืนยัน</option>
                    <option value="cancel">ยกเลิก</option>
                    <option value="confirm">ยืนยันแล้ว รอเข้าเข้าพักห้อง</option>
                    <option value="checkin">เข้าพักห้องอยู่</option>
                    <option value="checkout">ออกห้องพักแล้ว</option>
                </select>
                <p class="err-validate" id="validateStatus"></p>
            </div>
            <div class="col-auto">
                <label for="">สถานะการชำระเงิน</label>
                <select class="custom-select font-weight-bold" id="payStatus" data-status="<?php echo $row['pay_status'] ?? '' ?>">
                    <option value="" selected>เลือก</option>
                    <option value="progress">รอการยืนยัน</option>
                    <option value="paid">ชำระเงินแล้ว</option>
                    <option value="unpaid">ยังไม่ชำระเงิน</option>
                    <option value="cancelPaid">ยกเลิกแต่มีการจ่ายเงิน</option>
                    <option value="cancelUnpaid">ยกเลิกแบบไม่จ่ายเงิน</option>
                </select>
                <p class="err-validate" id="validatePayStatus"></p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label>ชื่อ</label>
                    <input type="text" class="form-control" disabled id="fname" value="<?php echo $row['fname'] ?? '' ?>">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>ชื่อ</label>
                    <input type="text" class="form-control" disabled id="lname" value="<?php echo $row['lname'] ?? '' ?>">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>เบอร์ติดต่อ</label>
                    <input type="text" class="form-control" id="tel" value="<?php echo $row['tel'] ?? '' ?>">
                </div>
            </div>
        </div>



        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">วันที่</label>
                    <input type="date" disabled class="form-control" id="startDt" value="<?php echo $row['start_dt'] ?? '' ?>">
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">ถึง</label>
                    <input type="date" disabled class="form-control" id="endDt" value="<?php echo $row['end_dt'] ?? '' ?>">
                </div>
            </div>
        </div>
        <label for="">ตัวเลือกเพิ่มเติม</label>
        <div class="custom-control custom-checkbox">
            <input class="custom-control-input" type="checkbox" data-check="<?php echo $additional ?>" <?php echo ($additional == 'true') ? 'checked' : ''; ?> id="additionalCushion">
            <label for="additionalCushion" class="custom-control-label">เบาะเสริม</label>
        </div>
        <div class="text-right">
            <div class="card bg-light">
                <div class="card-body">
                    <p class="m-0">
                        <strong>จำนวนวัน</strong>
                        <span class="text-danger  mx-2" id="dayCountText"><?php echo $row['day_count'] ?? 0 ?></span>
                        <span>คืน</span>
                    </p>
                    <input type="hidden" id="dayCount">
                    <input type="hidden" id="total">
                    <p class="m-0">
                        <strong>ยอดรวม</strong>
                        <span id="totalText" class="text-success"><?php echo number_format($row['total'] ?? 0, 2) ?></span>
                        <span>บาท</span>
                    </p>
                </div>
            </div>






        </div>
        <input type="hidden" id="slipPaymentData" value="<?php echo implode(',', $slip_payment) ?>">
        <input type="hidden" id="slipPaymentDeleteData">
        <div class="card">
            <div class="card-header bg-gradient-lightblue">
                <p class="font-weight-bold m-0">หลักฐานการชำระเงิน</p>
            </div>
            <div class="card-body">
                <div class="row" style="height: 18rem;overflow-y:scroll;">
                    <?php foreach ($slip_payment as $s) { ?>
                        <div class="col-md-3">
                            <button name="slippayment-delete" data-src="<?php echo $s ?>" class="btn btn-danger">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                            <img id="slipPaymentPreview" src="../assets/images/slip_payment/<?php echo $s ?>" style="width: 100%;object-fit:contain;"></img>
                            <p class="err-validate" id="validateSlipPayment"></p>

                        </div>
                    <?php    }  ?>

                </div>

            </div>
        </div>
        <button class="btn bg-gradient-lightblue" data-id="<?php echo $id ?>" id="reservationHandleSubmit">
            <strong>บันทึก</strong>
        </button>

    </div>
</div>
<script src="./js/reversation_form.js"></script>