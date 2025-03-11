<?php
require_once('./config/config_db.php');
require_once('./function/function.php');
require_once('./pagination.php');
$params = ['true'];
$paginate = paginate();
$page = $paginate['page'];
$per_page = $paginate['per_page'];

$sql = "SELECT rooms.*,room_type.room_type_id,room_type.room_type_name ";
$sql .= " FROM rooms LEFT JOIN room_type ON ";
$sql .= " rooms.room_type=room_type.room_type_id ";
$sql .= " WHERE rooms.soft_delete !=?";
if (!empty($start_dt) && !empty($end_dt)) {
    $sql .= " AND (create_at BETWEEN ? AND ?) ";
    array_push($params, $start_dt);
    array_push($params, $end_dt);
}

if (!empty($status) && $status != 'all') {
    $sql .= " AND status =? ";
    array_push($params, $status);
}

if(isset($_GET['roomType']) && !empty($_GET['roomType'])) {
    $sql .= " AND room_type.room_type_name = ? ";
    array_push($params, $_GET['roomType']);
}

$all = getDataCountAll($sql, 'room_id', $params, $page, $per_page);

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
    <title>ห้องพัก</title>
</head>

<body>
    <?php require_once('./nav.php') ?>
    <div class="container my-4 min-vh-100">
        <h4>ห้องพัก</h4>
        <div class="row">
            <?php
            $idx_start = ($page * $per_page) + 1;
            $idx_end = ($idx_start + $per_page) - 1;
            $idx = $start_row + 1;
            foreach ($row as $r) {
                $idx++ ?>
                <div class="col-md-6 p-2">
                    <div class="card card-body">
                        <div class="d-flex align-items-center">
                            <div class="w-25">
                                <img src="./assets/images/thumb/<?php echo $r['thumbnail'] ?>" class="card-img-top" style="width: 100%;height:100%;object-fit:contain;">
                            </div>
                            <div class="w-75 p-2">
                                <div class="card border-0">
                                    <div class="card-body">
                                        <h5 class=""><?php echo $r['room_name'] . " " . $r['room_number'] ?></h5>
                                        <p class="card-text">
                                            <?php echo $r['description'] ?>

                                        </p>

                                    </div>
                                    <div class="card-footer">
                                        <div class="d-flex justify-content-between align-items-start ">
                                            <div class="d-flex flex-wrap align-self-end">
                                                <strong class="text-danger">
                                                    <span>&#3647;</span>
                                                    <?php echo number_format($r['price'], 2) ?>
                                                </strong>
                                                <span class="badge bg-success  mx-2">
                                                    <i class="fa-solid fa-users-viewfinder"></i>
                                                    <span><?php echo ucwords($r['room_type_name']) ?></span>
                                                </span>
                                                <span class="badge bg-warning text-black">
                                                    <i class="fa-solid fa-bed text-black"></i>
                                                    <span><?php echo $r['bed_amount'] ?></span>
                                                </span>
                                            </div>
                                            <a href="./reservation_room.php?id=<?php echo base64_encode($r['room_id']) ?>" class="btn bg-lightblue">
                                                <strong>รายละเอียด</strong>
                                            </a>
                                        </div>



                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>



                </div>
            <?php    } ?>
        </div>
        <?php
        $_p = setPaginateQuery($route_params, $idx, $idx_end, $per_page);
        $_p[0] = "rooms.php" . str_ireplace('&per_page', '?per_page', $_p[0]);
        echo create_pagination($page, $page_all, $_p[0], $row_count, $idx_start, $_p[1]);
        ?>
    </div>

</body>

</html>