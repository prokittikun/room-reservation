<?php
$dt_start_query = ['confirm_pay'];
$dt_end_query = ['confirm_pay', 'checkout'];
$is_ds_query = gettype(array_search($r, $dt_start_query));
$is_de_query = gettype(array_search($r, $dt_end_query));
?>
<div class="card">
    <div class="card-body">
        <div class="row align-items-center">
            <?php if (!($is_ds_query == 'integer')) { ?>
                <label class="col-auto" for="">วันที่</label>
                <div class="col-auto">
                    <input type="date" class="form-control" value="<?php echo $start_dt ?>" id="startDate" placeholder="ค้นหา">
                    <p class="err-validate" id="validateStartDate"></p>
                </div>
            <?php   } ?>
            <?php if (!($is_de_query == 'integer')) { ?>
                <label class="col-auto" for="">ถึง</label>
                <div class="col-auto">
                    <input type="date" class="form-control" value="<?php echo $end_dt ?>" id="endDate" placeholder="ค้นหา">
                    <p class="err-validate" id="validateEndDate"></p>
                </div>
            <?php   } ?>

            <?php if ($r == 'report' || $r == 'reserv_d') { ?>
                <label class="col-auto" for="">สถานะการจอง</label>
                <div class="col-auto">

                    <select class="custom-select font-weight-bold" id="status" data-status="<?php echo $status  ?>">
                        <option value="" selected>เลือก</option>
                        <option value="progress">รอการยืนยัน</option>
                        <option value="cancel">ยกเลิก</option>
                        <option value="confirm">ยืนยันแล้ว รอเข้าเข้าพักห้อง</option>
                        <option value="checkin">เข้าพักห้องอยู่</option>
                        <option value="checkout">ออกห้องพักแล้ว</option>
                    </select>
                    <p class="err-validate" id="validateStatus"></p>
                </div>
                <label class="col-auto" for="">สถานะการชำระเงิน</label>
                <div class="col-auto">

                    <select class="custom-select font-weight-bold" id="payStatus" data-status="<?php echo $pay_status  ?>">
                        <option value="" selected>เลือก</option>
                        <option value="progress">รอการยืนยัน</option>
                        <option value="paid">ชำระเงินแล้ว</option>
                        <option value="unpaid">ยังไม่ชำระเงิน</option>
                        <option value="cancelPaid">ยกเลิกแต่มีการจ่ายเงิน</option>
                        <option value="cancelUnpaid">ยกเลิกแบบไม่จ่ายเงิน</option>
                    </select>
                    <p class="err-validate" id="validatePayStatus"></p>
                </div>



            <?php } ?>
            <div class="col-auto">
                <button class="btn btn-sm bg-gradient-lightblue m-1" id="findDataByQueryForm">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <span class="ml-1">ค้นหา</span>
                </button>
            </div>
            <?php if ($r == 'report') { ?>
                <div class="col-auto">
                    <button name="report-to-file" data-file="pdf" class="btn btn-sm bg-gradient-lightblue m-1">
                        <i class="fa-solid fa-file-pdf"></i>
                        <span class="ml-1">PDF</span>
                    </button>
                </div>
            <?php } ?>
        </div>
    </div>
</div>