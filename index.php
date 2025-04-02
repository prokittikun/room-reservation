<?php
@session_start();
require_once('./config/config_db.php');
require_once('./function/function.php');
$member_id = $_SESSION['member_id'] ?? '';
$is_login = !empty($member_id) ? 'true' : 'false';
$params = ['true'];
$start_dt = $_GET['checkin'] ?? '';
$end_dt = $_GET['checkout'] ?? '';
$room_type = $_GET['room_type'] ?? '';
$stmt_rt = connect_db()->prepare("SELECT * FROM room_type WHERE soft_delete != ?");
$stmt_rt->execute(['true']);
$row_rt = $stmt_rt->fetchAll();
$row = [];
if (!empty($start_dt) && !empty($end_dt)) {
    $r_sql = "SELECT DISTINCT room_id FROM reservations WHERE soft_delete !=?";
    $date_data = get_overday_datestamp($start_dt, $end_dt);
    $_sql = get_available_datestamp($date_data);
    $r_sql .= " AND ";
    $r_sql .= $_sql[0];
    array_push($params, ...$_sql[1]);
    $stmt_r = connect_db()->prepare($r_sql);
    $stmt_r->execute($params);
    $row_reserv = $stmt_r->fetchAll();
    $r_reserv_id = array_map(function ($r) {
        return $r['room_id'];
    }, $row_reserv);

    $params = ['true'];
    $sql  = "SELECT room_type.room_type_id,room_type.room_type_name,";
    $sql  .= "rooms.* FROM rooms LEFT JOIN room_type ";
    $sql  .= " ON rooms.room_type=room_type.room_type_id ";
    $sql  .= " WHERE rooms.soft_delete != ? ";
    if (!empty($room_type)) {
        $sql .= " AND rooms.room_type=? ";
        array_push($params, base64_decode($room_type));
    }

    if (count($r_reserv_id) > 0) {
        $_r_sql = get_in_sql($r_reserv_id, $params);
        $sql .= " AND rooms.room_id NOT IN ( ";
        $sql .= $_r_sql[0];
        $sql .= " )";
        array_push($params, ...$_r_sql[1]);
    }

    $stmt = connect_db()->prepare($sql);
    $stmt->execute($params);
    $row = $stmt->fetchAll();
}

$carousel_items = [];
try {
    $sql = "SELECT * FROM carousel WHERE is_active = 1 ORDER BY order_num ASC";
    $stmt = connect_db()->query($sql);
    $carousel_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Handle error silently
    error_log("Error fetching carousel items: " . $e->getMessage());
}

// Get contact information for footer
$contact_info = [];
try {
    $sql = "SELECT * FROM contact LIMIT 1";
    $stmt = connect_db()->query($sql);
    $contact_info = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Handle error silently
    error_log("Error fetching contact info: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require_once('./logo.php') ?>
    <?php require_once('./head.php') ?>
    <title><?php echo $title ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <style>
        /* Custom styles */
        .carousel-item {
            height: 500px;
            background-size: cover;
            background-position: center;
        }

        .carousel-caption {
            background-color: rgba(0, 0, 0, 0.6);
            padding: 20px;
            border-radius: 10px;
            max-width: 600px;
            margin: 0 auto;
        }

        .welcome-section {
            padding: 60px 0;
        }

        .feature-box {
            padding: 30px;
            border-radius: 10px;
            margin-bottom: 30px;
            transition: transform 0.3s;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .feature-box:hover {
            transform: translateY(-10px);
        }
    </style>

</head>

<body>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <?php require_once('./nav.php') ?>

    <div class="container min-vh-100 my-3">
        <!-- <div class="card">
            <div class="card-body bg-light">
                <h5 class="text-center">ค้นหาห้องพัก</h5>
                <div class="row align-items-center justify-content-center">
                    <div class="col-auto">
                        <label for="">วันที่</label>
                    </div>
                    <div class="col-auto">
                        <input type="date" class="form-control" id="checkin" value="<?php echo $start_dt ?>">
                        <p class="err-validate" id="validateCheckin"></p>
                    </div>

                    <div class="col-auto">
                        <label for="">ถึง</label>
                    </div>
                    <div class="col-auto">
                        <input type="date" class="form-control" id="checkout" value="<?php echo $end_dt ?>">
                        <p class="err-validate" id="validateCheckout"></p>
                    </div>
                    <div class="col-auto">
                        <label for="">ประเภทห้องพัก</label>
                    </div>
                    <div class="col-auto">
                        <select class="form-select" id="roomType" data-type="<?php echo $room_type ?>">
                            <option value="">เลือก</option>
                            <?php foreach ($row_rt as $rt) { ?>
                                <option value="<?php echo base64_encode($rt['room_type_id']) ?>">
                                    <?php echo $rt['room_type_name'] ?>
                                </option>
                            <?php    } ?>

                        </select>
                    </div>
                    <div class="col-auto">
                        <button id="findRoomByForm" class="btn btn-success">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div> -->
        <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner">
                <?php foreach ($carousel_items as $index => $item): ?>
                    <div class="carousel-item <?php echo ($index === 0) ? 'active' : ''; ?>">
                        <img class="d-block w-100" src='<?php echo substr($item['image_path'], '1')  ?>'>
                    </div>

                <?php endforeach; ?>
                <!-- <div class="carousel-item active">
                    <img class="d-block w-100" src="./assets/images/carousel/2.jpg" alt="First slide">
                </div> -->
                <!-- <div class="carousel-item">
                    <img class="d-block w-100" src="./assets/images/carousel/2.jpg" alt="Second slide">
                </div>
                <div class="carousel-item">
                    <img class="d-block w-100" src="./assets/images/carousel/2.jpg" alt="Third slide">
                </div> -->
            </div>
            <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>
        <div class="mt-5"></div>
        <div class="d-flex flex-row gap-4 justify-content-center flex-wrap">
            <?php foreach ($row_rt as $rt) { ?>
                <a class="btn btn-outline-primary btn-lg text-black px-5 py-3 rounded-pill shadow-lg fw-bold transition"
                    href="./rooms.php?roomType=<?= $rt['room_type_name']; ?>">
                    <?php echo $rt['room_type_name'] ?>
                </a>
            <?php } ?>
        </div>
        <div class="row my-3">
            <?php foreach ($row as $r) { ?>
                <div class="col-md-2">
                    <div class="card border-0">
                        <img src="./assets/images/thumb/<?php echo $r['thumbnail'] ?>" class="card-img-top" style="width: 100%;object-fit:cover;height:6rem;">
                        <div class="card-body">
                            <h5 class=""><?php echo $r['room_name'] . " " . $r['room_number'] ?></h5>
                            <div class="d-flex flex-wrap">
                                <span class="me-2">
                                    <i class="fa-solid fa-users-viewfinder text-black"></i>
                                    <span><?php echo ucwords($r['room_type_name']) ?></span>
                                </span>
                                <span class="">
                                    <i class="fa-solid fa-bed text-black"></i>
                                    <span><?php echo $r['bed_amount'] ?></span>
                                </span>
                            </div>
                            <p class="text-muted m-0"><?php echo number_format($r['price'], 2) ?></p>
                        </div>
                        <div class="card-footer">

                            <div class="text-end">
                                <a data-login="<?php echo $is_login ?>" data-id="<?php echo base64_encode($r['room_id']) ?>" name="reserv-btn" class="btn btn-success"
                                    href="./reservation_room.php?id=<?= base64_encode($r['room_id']) ?>">จอง</a>
                                <a target="_blank" href="./reservation_room.php?id=<?php echo base64_encode($r['room_id']) ?>" class="btn bg-lightblue">
                                    รายละเอียด
                                </a>
                            </div>


                        </div>
                    </div>


                </div>
            <?php   } ?>
        </div>



    </div>
    <?php require_once('./footer.php') ?>
    <?php require_once('./reservation_modal.php') ?>
    <script src="./js/findRoomByForm.js"></script>
</body>

</html>

<style>
    .btn-outline-primary {
        border: 2px solid #007bff;
        color: #007bff;
        font-size: 1.25rem;
    }

    .btn-outline-primary:hover {
        background-color: #007bff;
        color: white;
        transform: scale(1.05);
        transition: all 0.3s ease-in-out;
    }
</style>