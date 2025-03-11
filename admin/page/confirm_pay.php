<?php
$r = $_GET['r'] ?? '';
$params = ['true', 'progress'];
$paginate = paginate();
$page = $paginate['page'];
$per_page = $paginate['per_page'];
$id = $_GET['id'] ?? '';
$sql = "SELECT rooms.room_id,rooms.room_name,rooms.room_number,";
$sql .= "rooms.room_type,room_type.room_type_id,room_type.room_type_name,";
$sql .= "member.member_id,member.fname,member.lname,member.tel,";
$sql .= "reservations.*";
$sql .= " FROM reservations INNER JOIN member ON ";
$sql .= " reservations.member_id=member.member_id ";
$sql .= " LEFT JOIN rooms ON ";
$sql .= " rooms.room_id=reservations.room_id";
$sql .= " LEFT JOIN room_type ON ";
$sql .= " room_type.room_type_id=rooms.room_type";
$sql .= " WHERE reservations.soft_delete !=?  AND reservations.pay_status=? ";


if (!empty($id)) {
    $sql .= " AND (reservations.reservation_id = ?) ";
    array_push($params, $id);
}


$all = getDataCountAll($sql, 'reservations.reservation_id', $params, $page, $per_page);

$row_count = $all['row_count'];
$page_all = $all['page_all'];
$start_row = $all['start_row'];
$sql .= "ORDER BY create_at LIMIT ?,?";
array_push($params, $start_row);
array_push($params, $per_page);
$row = getDataAll($sql, $params);
$route_params = get_params_isNotEmpty(['r' => $r,'id'=>$id]);
?>
<div class="row align-items-center">
    <?php require_once('./page/entries_row.php') ?>
</div>
<div class="input-group my-2">

    <input type="text" value="<?php echo str_ireplace('-', ' ', $id) ?>" class="form-control" id="queryIdAndName" placeholder="รหัสคำสั่งซื่อ ชื่อสมาชิก">
    <button class=" btn bg-gradient-lightblue" onclick="pasteData($('#queryIdAndName'))">
        <i class="fa-solid fa-paste"></i>
    </button>
</div>
<?php require_once('./page/query_form.php') ?>

<div class="card">
    <div class="table-responsive m-0">
        <table class="m-0 table table-hover table-striped " style="width: 1440px;min-width: 100%;">
            <thead>
                <tr>
                    <th class="text-center" style="width: 3%;" scope="col">ลำดับ</th>
                    <th style="width: 7%;" scope="col">รหัส</th>
                    <th class="text-center" style="width: 7%;" scope="col">หลักฐาน</th>
                    <th style="width:7%;" scope="col">ยอด</th>
                    <th style="width: 16%;" scope="col"></th>
                    <th style="width:10%;" scope="col">สถานะ</th>
                    <th style="width:16%;" scope="col">เบอร์ติดต่อ</th>
                    <th style="width: 22%;" scope="col">ชื่อ - นามสกุล</th>
                    <th style="width: 13%;" scope="col">วันที่ทำรายการ</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $idx_start = ($page * $per_page) + 1;
                $idx_end = ($idx_start + $per_page) - 1;
                $idx = $start_row + 1;
                foreach ($row as $r) {

                ?>
                    <tr>
                        <td class="text-center"><?php echo $idx++ ?></td>
                        <td><?php echo $r['reservation_id'] ?></td>
                        <td class="text-center">
                            <button name="confirm-payslip" data-id="<?php echo $r['reservation_id'] ?>" class="btn btn-sm  bg-gradient-lightblue">
                                <i class="fa-solid fa-check"></i>
                                <strong>ดู</strong>
                            </button>

                        </td>

                        <td class="text-right"><?php echo number_format($r['total'], 2) ?></td>
                        <td>
                            <?php if ($r['status'] != 'cancel') { ?>
                                <button name="re-pay" data-id="<?php echo $r['reservation_id'] ?>" class="btn btn-sm  bg-gradient-warning">
                                    <i class="fa-solid fa-xmark"></i>
                                    <strong>ชำระอีกครั้ง</strong>
                                </button>
                                <button name="confirm-pay" data-id="<?php echo $r['reservation_id'] ?>" class="btn btn-sm bg-gradient-olive">
                                    <i class="fa-solid fa-check"></i>
                                    <strong>ยืนยัน</strong>
                                </button>
                            <?php } ?>

                            <?php if ($r['status'] == 'cancel') { ?>
                                <button name="refund-pay" data-id="<?php echo $r['reservation_id'] ?>" class="btn btn-sm bg-gradient-lime">
                                    <i class="fa-solid fa-arrows-spin"></i>
                                    <strong>คืนเงิน</strong>
                                </button>
                            <?php } ?>

                        </td>
                        <td>
                            <span class="<?php echo get_reservation_status_text($r['status']) ?>">
                                <?php echo get_reservation_status($r['status']) ?>
                            </span>
                        </td>

                        <td><?php echo $r['tel'] ?></td>
                        <td><?php echo $r['fname'] . " " . $r['lname'] ?></td>

                        <td><?php echo date('d/m/Y H:i:s', strtotime($r['create_at'])) ?></td>

                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>







<?php
$_p = setPaginateQuery($route_params, $idx, $idx_end, $per_page);
echo create_pagination($page, $page_all, $_p[0], $row_count, $idx_start, $_p[1]);
?>
</div>
<?php require_once('./page/modal/confirm_pay_modal.php') ?>
<?php require_once('./page/modal/refund_pay_modal.php') ?>
<script src="./js/confirm_pay.js"></script>
<script src="./js/refund_pay.js"></script>
<script src="./js/query_form.js"></script>
<script src="./js/showSlip.js"></script>