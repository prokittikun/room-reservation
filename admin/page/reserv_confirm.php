<?php
$r = $_GET['r'] ?? '';
$params = ['true', 'progress', 'true', 'paid', 'progress'];
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
$sql .= " WHERE reservations.soft_delete !=?  AND reservations.status = ?";
$sql .= " AND reservations.is_cancel != ? AND (pay_status=? OR pay_status=?)";


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
        <div class="table-responsive m-0">
            <table class="m-0 table table-hover table-striped " style="width: 1440px;">
                <thead>
                    <tr class="">
                        <th class="text-center" style="width: 3%;" scope="col">ลำดับ</th>
                        <th style="width:8%;" scope="col">รหัส</th>
                        <th style="width:10%;" scope="col">สถานะการจ่ายเงิน</th>
                        <th style="width:18%;" scope="col"></th>
                        <th style="width: 10%;" scope="col">ยอด</th>
                        <th style="width:18%;" scope="col">ห้อง</th>
                        <th style="width: 18%;" scope="col">ชื่อ - นามสกุล</th>
                        <th style="width: 15%;" scope="col">วันที่จอง</th>

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
                            <td>
                                <span class="<?php echo get_reservation_paystatus_text($r['pay_status']) ?>">
                                    <?php echo get_reservation_paystatus($r['pay_status']) ?>
                                </span>

                            </td>
                            <td>
                                <button name="reserv-confirm" data-id="<?php echo $r['reservation_id'] ?>" class="btn btn-sm m-1 bg-gradient-success">
                                    <i class="fa-solid fa-check"></i>
                                    <strong>ยืนยัน</strong>
                                </button>

                                <button name="refund-pay" data-id="<?php echo $r['reservation_id'] ?>" class="btn btn-sm m-1 bg-gradient-maroon">
                                    <i class="fa-solid fa-arrows-spin"></i>
                                    <strong>คืนเงิน</strong>
                                </button>

                            </td>
                            <td class="text-right"><?php echo number_format($r['total'], 2)  ?></td>
                            <td><?php echo $r['room_name'] . " " . $r['room_type_name'] ?></td>

                            <td><?php echo $r['fname'] . " " . $r['lname'] ?></td>
                            <td>

                                <p class="m-0">
                                    <span class="text-muted">วันที่</span>
                                    <span class="text-success">
                                        <?php echo getShortThaiDate($r['start_dt']) ?>
                                    </span>
                                </p>
                                <p class="m-0">
                                    <span class="text-muted">ถึง</span>
                                    <span class="text-danger">
                                        <?php echo getShortThaiDate($r['end_dt']) ?>
                                    </span>
                                </p>
                            </td>

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
    <?php require_once('./page/modal/refund_pay_modal.php') ?>
    <script src="./js/reserv_confirm.js"></script>
    <script src="./js/refund_pay.js"></script>
