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
$params = ['true', $member_id];
$paginate = paginate();
$page = $paginate['page'];
$per_page = $paginate['per_page'];

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
$sql .= " WHERE reservations.soft_delete !=? AND member.member_id=?";
if (!empty($start_dt) && !empty($end_dt)) {
    $sql .= " AND (reservations.create_at BETWEEN ? AND ?) ";
    array_push($params, $start_dt);
    array_push($params, $end_dt);
}

if (!empty($status) && $status != 'all') {
    $sql .= " AND status =? ";
    array_push($params, $status);
}

$all = getDataCountAll($sql, 'reservations.reservation_id', $params, $page, $per_page);

$row_count = $all['row_count'];
$page_all = $all['page_all'];
$start_row = $all['start_row'];
$sql .= "ORDER BY create_at LIMIT ?,?";
array_push($params, $start_row);
array_push($params, $per_page);
$row = getDataAll($sql, $params);
$route_params = get_params_isNotEmpty([]);
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
    <div class="container min-vh-100 my-3">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title m-0 fw-bold">ประวัติการจอง</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered table-striped" style="width: 1540px;">
                        <thead>
                            <tr class="">
                                <th class="text-center" style="width: 5%;" scope="col">ลำดับ</th>
                                <th class="text-center" style="width:8%;" scope="col">รหัส</th>
                                <th style="width:8%;" scope="col">สถานะ</th>
                                <th style="width:7%;" scope="col">การชำระเงิน</th>
                                <th style="width: 15%;" scope="col"></th>
                                <th class="text-center" style="width:4%;" scope="col"></th>
                                <th style="width:15%;" scope="col">ห้อง</th>
                                <th style="width:9%;" scope="col">ยอด</th>

                                <th style="width: 12%;" scope="col">วันที่ทำรายการ</th>
                                <th style="width: 12%;" scope="col">วันที่จอง</th>
                                <th class="text-center" style="width: 6%;" scope="col">จำนวน</th>

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
                                        <span class="<?php echo get_reservation_status_text($r['status']) ?>">
                                            <?php echo get_reservation_status($r['status']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="<?php echo get_reservation_paystatus_text($r['pay_status']) ?>">
                                            <?php echo get_reservation_paystatus($r['pay_status']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($r['pay_status'] == 'unpaid') { ?>
                                            <button name="repay" data-id="<?php echo $r['reservation_id'] ?>" class="btn btn-sm m-1 btn-secondary">
                                                <i class="fa-solid fa-xmark"></i>
                                                <strong>ชำระเงินอีกครั้ง</strong>
                                            </button>
                                        <?php   } ?>
                                        <?php
                                        $_start_dt = strtotime(explode(' ', $r['start_dt'])[0]);
                                        $_dt = strtotime(date('Y-m-d 00:00:00'));

                                        $_three_days_before = strtotime('-3 days', $_start_dt);
                                        //$r['status'] == 'progress' && 
                                        if ($r['status'] != 'cancel' && $_start_dt > $_dt && $_dt < $_three_days_before) { ?>
                                            <button name="reserv-postpone" data-id="<?php echo $r['reservation_id'] ?>" class="btn btn-sm m-1 bg-lightblue">
                                                <i class="fa-solid fa-retweet"></i>
                                                <strong>เลื่อน</strong>
                                            </button>
                                        <?php   } ?>

                                        <?php if (($r['status'] == 'confirm' || $r['status'] == 'progress') && $r['is_cancel'] != 'true') { ?>
                                            <button name="reserv-cancel" data-id="<?php echo $r['reservation_id'] ?>" class="btn btn-sm m-1 btn-danger">
                                                <i class="fa-solid fa-xmark"></i>
                                                <strong>ยกเลิก</strong>
                                            </button>
                                        <?php   } ?>

                                    </td>
                                    <td class="text-center">
                                        <img src="./assets/images/thumb/<?php echo $r['thumbnail']  ?>" style="height: 3rem;">
                                    </td>
                                    <td><?php echo $r['room_name'] . " " . $r['room_type_name'] ?></td>
                                    <td class="text-end"><?php echo number_format($r['total'], 2) ?></td>
                                    <td><?php echo date('d/m/Y H:i:s', strtotime($r['create_at'])) ?></td>
                                    <td>
                                        <p class="m-0">
                                            <span class="text-muted">วันที่</span>
                                            <strong class="text-success">
                                                <?php echo getShortThaiDate($r['start_dt']) ?>
                                            </strong>
                                        </p>
                                        <p class="m-0">
                                            <span class="text-muted">ถึง</span>
                                            <strong class="text-danger">
                                                <?php echo getShortThaiDate($r['end_dt']) ?>
                                            </strong>
                                        </p>
                                    </td>
                                    <td class="text-center"><?php echo $r['day_count'] . " คืน" ?></td>

                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <?php
        $_p = setPaginateQuery($route_params, $idx, $idx_end, $per_page);
        $_p[0] = "reservation_history.php" . str_ireplace('&per_page', '?per_page', $_p[0]);
        echo create_pagination($page, $page_all, $_p[0], $row_count, $idx_start, $_p[1]);
        ?>
    </div>
    <?php require_once('./slippayment_modal.php') ?>
    <?php require_once('./postpone_modal.php') ?>
    <script src="./js/reservation_history.js"></script>
    <script src="./js/resetvationDate.js"></script>
    <script src="./js/slipPayment.js"></script>
</body>

</html>