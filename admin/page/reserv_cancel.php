<?php
$r = $_GET['r'] ?? '';
$params = ['true', 'true', 'cancel', 'cancelPaid', 'cancelUnpaid'];
$paginate = paginate();
$page = $paginate['page'];
$per_page = $paginate['per_page'];

$sql = "SELECT rooms.room_id,rooms.room_name,rooms.room_number,";
$sql .= "rooms.room_type,room_type.room_type_id,room_type.room_type_name,";
$sql .= "member.member_id,member.fname,member.lname,";
$sql .= "reservations.*";
$sql .= " FROM reservations INNER JOIN member ON ";
$sql .= " reservations.member_id=member.member_id ";
$sql .= " LEFT JOIN rooms ON ";
$sql .= " rooms.room_id=reservations.room_id";
$sql .= " LEFT JOIN room_type ON ";
$sql .= " room_type.room_type_id=rooms.room_type";
$sql .= " WHERE reservations.soft_delete !=?  AND reservations.is_cancel=?";
$sql .= "AND reservations.status != ? ";
$sql .= "AND reservations.pay_status != ? AND reservations.pay_status != ?";


$all = getDataCountAll($sql, 'reservations.reservation_id', $params, $page, $per_page);

$row_count = $all['row_count'];
$page_all = $all['page_all'];
$start_row = $all['start_row'];
$sql .= "ORDER BY create_at LIMIT ?,?";
array_push($params, $start_row);
array_push($params, $per_page);
$row = getDataAll($sql, $params);
$route_params = get_params_isNotEmpty(['r' => $r]);
?>
<div class="row align-items-center">
    <?php require_once('./page/entries_row.php') ?>
</div>

<div class="card">
    <div class="card-body">
        <div class="m-0 table-responsive">
            <table class="table table-hover m-0" style="width: 1440px;">
                <thead>
                    <tr class="">
                        <th class="text-center" style="width: 5%;" scope="col">ลำดับ</th>
                        <th class="text-center" style="width: 7%;" scope="col">หลักฐาน</th>
                        <th style="width:10%;" scope="col">สถานะการเงิน</th>
                        <!-- <th style="width:7%;" scope="col">รหัสการจอง</th> -->
                        <th style="width:14%;" scope="col"></th>
                        <th style="width:18%;" scope="col">ห้อง</th>
                        <th style="width: 25%;" scope="col">ชื่อ - นามสกุล</th>
                        <th style="width:10%;" scope="col">ยอด</th>
                        <th style="width: 15%;" scope="col">วันที่ทำรายการ</th>

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
                            <td class="text-center">
                                <button name="confirm-payslip" data-id="<?php echo $r['reservation_id'] ?>" class="btn btn-sm  bg-gradient-lightblue">
                                    <i class="fa-solid fa-check"></i>
                                    <strong>ดู</strong>
                                </button>

                            </td>
                            <td>
                                <span class="<?php echo get_reservation_paystatus_text($r['pay_status']) ?>">
                                    <?php echo get_reservation_paystatus($r['pay_status']) ?>
                                </span>
                            </td>

                            <!-- <td class="text-center"><?php echo $r['reservation_id'] ?></td> -->
                            <td>
                                <button name="reserv-confirm" data-id="<?php echo $r['reservation_id'] ?>" class="btn btn-sm btn-success">
                                    <i class="fa-solid fa-check"></i>
                                    <strong>ยืนยัน</strong>
                                </button>

                                <button name="refund-pay" data-id="<?php echo $r['reservation_id'] ?>" class="btn btn-sm bg-gradient-lightblue">
                                    <i class="fa-solid fa-arrows-spin"></i>
                                    <strong>คืนเงิน</strong>
                                </button>

                            </td>
                            <td><?php echo $r['room_name'] . " " . $r['room_type_name'] ?></td>
                            <td><?php echo $r['fname'] . " " . $r['lname'] ?></td>
                            <td><?php echo number_format($r['total'], 2) ?></td>
                            <td><?php echo date('d/m/Y H:i:s', strtotime($r['create_at'])) ?></td>

                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>




<?php
$_p = setPaginateQuery($route_params, $idx, $idx_end, $per_page);
echo create_pagination($page, $page_all, $_p[0], $row_count, $idx_start, $_p[1]);
?>
</div>
<?php require_once('./page/modal/refund_pay_modal.php') ?>
<?php require_once('./page/modal/confirm_pay_modal.php') ?>
<script src="./js/reserv_cancel.js"></script>
<script src="./js/refund_pay.js"></script>
<script src="./js/showSlip.js"></script>