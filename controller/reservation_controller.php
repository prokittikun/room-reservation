<?php
@session_start();
$user_type = $_SESSION['user_type'] ?? '';
$member_id = $_SESSION['member_id'] ?? 'MT';
require_once('../config/config_db.php');
require_once('../function/create_random.php');
require_once('../function/function.php');
require_once('../function/date.php');
$route = $_POST['route'] ?? '';
$id = isset($_POST['id']) ? $_POST['id'] : 'RESV' . date_stamp_id() . random_number(6) . random_char(4);
$sql = '';
$start = $_POST['date'] ?? '';

$room_id = $_POST['room_id'] ?? '';
$total = $_POST["total"] ?? '';
$start_dt = $_POST['start_dt'] ?? '';
$end_dt = $_POST['end_dt'] ?? '';
$day_count = $_POST['day_count'] ?? '';
$create_at = create_date();
$update_at = create_date();
$status = $_POST['status'] ?? '';
$pay_status = $_POST['pay_status'] ?? '';
$slip_payment = '';
$slip_payment_list = [];
$paid  = $_POST['paid'] ?? '';
$additional = $_POST['additional'] ?? '';
$slip_payment_dir = "../assets/images/slip_payment";
if (!is_dir($slip_payment_dir)) {
    mkdir($slip_payment_dir);
}

if (isset($_FILES['slip_payment'])) {
    $slip_payment_file = $_FILES['slip_payment'];
    for ($i = 0; $i < count($slip_payment_file['name']); $i++) {
        $filetype = pathinfo($slip_payment_file['name'][$i], PATHINFO_EXTENSION);
        $filename = "$id" . getdate()[0] . random_char(4) . random_number(2) . "." . $filetype;
        $old_target = $slip_payment_file['tmp_name'][$i];
        $new_target = "$slip_payment_dir/$filename";
        $m = move_uploaded_file($old_target, $new_target);
        if ($m) array_push($slip_payment_list, $filename);
        if (!$m) continue;
    }
}



switch ($route) {
    case '/booking/insert':
        $date_stamp = get_overday_datestamp("$start_dt 00:00:00", "$end_dt 00:00:00");
        $_sql =   get_available_datestamp($date_stamp);
        $params = ['confirm', 'progress', $room_id];
        $sql = "SELECT * FROM reservations ";
        $sql .= " WHERE (status =? OR status = ?) ";
        $sql .= " AND room_id=?  AND (";
        $_dt_sql = get_available_datestamp($date_stamp);
        $sql .= $_dt_sql[0];
        array_push($params, ...$_sql[1]);
        $sql .= ")";
        $stmt_reser = connect_db()->prepare($sql);
        $stmt_reser->execute($params);

        if ($stmt_reser->rowCount() > 0) {
            http_response_code(400);
            return;
        }


        $sql = "INSERT INTO reservations  VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $params = [
            $id,
            $member_id,
            $room_id,
            $start_dt,
            $end_dt,
            $day_count,
            $total,
            implode(',', $slip_payment_list),
            "progress",
            "unpaid",
            0,
            "false",
            $create_at,
            $update_at,
            'false',
            implode(',', $date_stamp),
            $additional
        ];
        try {

            $stmt = connect_db()->prepare($sql);
            $stmt->execute($params);
            echo json_encode(['id' => base64_encode($id)]);
        } catch (PDOException $e) {
            echo json_encode(['err' => $e->getMessage()]);
            http_response_code(500);
        }

        break;
    case '/booking/cancel':
        $sql = "UPDATE reservations SET is_cancel =? WHERE reservation_id=?";
        try {
            $stmt = connect_db()->prepare($sql);
            $stmt->execute(['true', $id]);
        } catch (PDOException $e) {
            echo json_encode(['err' => $e->getMessage()]);
            http_response_code(500);
        }
        break;
    case '/booking/cancel/confirm':
        $sql = "UPDATE reservations SET status=? WHERE reservation_id=?";
        try {
            $stmt = connect_db()->prepare($sql);
            $stmt->execute(['cancel', $id]);
        } catch (PDOException $e) {
            echo json_encode(['err' => $e->getMessage()]);
            http_response_code(500);
        }
        break;
    case '/booking/confirm':

        $sql = "UPDATE reservations SET status=? WHERE reservation_id=?";
        try {
            $stmt = connect_db()->prepare($sql);
            $stmt->execute(['confirm',  $id]);
        } catch (PDOException $e) {
            echo json_encode(['err' => $e->getMessage()]);
            http_response_code(500);
        }
        break;
    case '/booking/pay/confirm':
        $row = getDataById("SELECT * FROM reservations WHERE reservation_id=?", [$id]);
        $paid = (float)  $row['total'];
        $sql = "UPDATE reservations SET pay_status=?,paid=? WHERE reservation_id=?";
        try {
            $stmt = connect_db()->prepare($sql);
            $stmt->execute(['paid', $paid, $id]);
        } catch (PDOException $e) {
            echo json_encode(['err' => $e->getMessage()]);
            http_response_code(500);
        }
        break;
    case '/booking/pay/repay':
        $sql = "UPDATE reservations SET pay_status=? WHERE reservation_id=?";
        try {
            $stmt = connect_db()->prepare($sql);
            $stmt->execute(['unpaid', $id]);
        } catch (PDOException $e) {
            echo json_encode(['err' => $e->getMessage()]);
            http_response_code(500);
        }
        break;
    case '/booking/pay/refund':
        $sql = "UPDATE reservations SET pay_status=?,paid=? WHERE reservation_id=?";
        $pay_status = $paid == 0 ? 'cancelUnpaid' : 'cancelPaid';

        try {
            $stmt = connect_db()->prepare($sql);
            $stmt->execute([$pay_status, $paid, $id]);
        } catch (PDOException $e) {
            echo json_encode(['err' => $e->getMessage()]);
            http_response_code(500);
        }
        break;
    case '/booking/checkin/confirm':
        $sql = "UPDATE reservations SET status=? WHERE reservation_id=?";

        try {
            $stmt = connect_db()->prepare($sql);
            $stmt->execute(["checkin", $id]);
        } catch (PDOException $e) {
            echo json_encode(['err' => $e->getMessage()]);
            http_response_code(500);
        }
        break;
    case '/booking/checkout/confirm':
        $r = getDataById("SELECT * FROM reservations WHERE reservation_id=?", [$id]);
        $is_status = $r['pay_status'] == 'paid';
        if (!$is_status) {
            http_response_code(400);
            return;
        }
        $sql = "UPDATE reservations SET status=? WHERE reservation_id=?";

        try {
            $stmt = connect_db()->prepare($sql);
            $stmt->execute(["checkout", $id]);
        } catch (PDOException $e) {
            echo json_encode(['err' => $e->getMessage()]);
            http_response_code(500);
        }
        break;
    case '/booking/update/id':
        $slip_payment = $_POST['slip_payment'];
        $slip_payment_delete = explode(',', $_POST['slip_payment_delete']);
        if (count($slip_payment_delete) > 0) {

            foreach ($slip_payment_delete as $s) {
                if (!empty($s)) {
                    $f = "$slip_payment_dir/$s";
                    if (file_exists($f)) {
                        unlink($f);
                    }
                }
            }
        }
        $sql = "UPDATE reservations SET status=?,pay_status=?,start_dt=?,slip_payment=?,";
        $sql .= "end_dt=?,update_at=?,additional=? WHERE reservation_id=?";
        $params = [
            $status, $pay_status, $start_dt, $slip_payment,
            $end_dt, $update_at, $additional,  $id
        ];
        try {
            $stmt = connect_db()->prepare($sql);
            $stmt->execute($params);
        } catch (PDOException $e) {
            echo json_encode(['err' => $e->getMessage()]);
            http_response_code(500);
        }
        break;
    case '/booking/delete/id':
        $sql = "UPDATE reservations SET soft_delete=?,";
        $sql .= "update_at=? WHERE reservation_id=?";
        echo $sql;
        $params = ['true', $update_at, $id];
        print_r($params);
        try {
            $stmt = connect_db()->prepare($sql);
            $stmt->execute($params);
        } catch (PDOException $e) {
            echo json_encode(['err' => $e->getMessage()]);
            http_response_code(500);
        }
        return;
        break;
    case '/booking/confirm':
        $params = [
            $paid,
            $total,
            $discount,
            $status,
            $pay_status,
            $update_at,
            $id
        ];
        $sql = "UPDATE meetingroom_meetingbooking SET paid=?,";
        $sql .= "total=?,discount=?,status=?,pay_status=?,";
        $sql .= "update_at=? WHERE meeting_booking_id=?";
        break;
    case '/booking/slippayment':
        try {
            $row_slip = getDataById("SELECT * FROM reservations WHERE reservation_id=?", [$id]);
            $row_slip = !empty($row_slip['slip_payment']) ? explode(',', $row_slip['slip_payment']) : [];
            $slip_payment_list = [...$row_slip, ...$slip_payment_list];
            $params = [implode(',', $slip_payment_list), "progress", $update_at, $id];
            $sql = "UPDATE reservations SET slip_payment=?,";
            $sql .= "pay_status=?,update_at=? WHERE reservation_id=?";
            $stmt = connect_db()->prepare($sql);
            $stmt->execute($params);
        } catch (PDOException $e) {
            echo json_encode(['err' => $e->getMessage()]);
            http_response_code(500);
            return;
        }
        break;
    case '/booking/postpone':
        try {
            $date_stamp = get_overday_datestamp("$start_dt 00:00:00", "$end_dt 00:00:00");
            $_sql =   get_available_datestamp($date_stamp);
            $params = [$id, 'confirm', 'progress', $room_id];
            $sql = "SELECT * FROM reservations ";
            $sql .= " WHERE reservation_id !=? AND (status =? OR status = ?) ";
            $sql .= " AND room_id=?  AND (";
            $_dt_sql = get_available_datestamp($date_stamp);
            $sql .= $_dt_sql[0];
            array_push($params, ...$_sql[1]);
            $sql .= ")";
            $stmt_reser = connect_db()->prepare($sql);
            $stmt_reser->execute($params);

            if ($stmt_reser->rowCount() > 0) {
                http_response_code(400);
                return;
            }
            $row_slip = getDataById("SELECT * FROM reservations WHERE reservation_id=?", [$id]);
            $row_slip = !empty($row_slip['slip_payment']) ? explode(',', $row_slip['slip_payment']) : [];
            $slip_payment_list = [...$row_slip, ...$slip_payment_list];
            $params = [
                implode(',', $slip_payment_list),
                $total, $day_count, $start_dt, $end_dt, $additional,
                "progress", $update_at, $id
            ];
            $sql = "UPDATE reservations SET slip_payment=?,total=?,day_count=?,";
            $sql .= "start_dt=?,end_dt=?,additional=?,";
            $sql .= "pay_status=?,update_at=? WHERE reservation_id=?";

            $stmt = connect_db()->prepare($sql);
            $stmt->execute($params);
        } catch (PDOException $e) {
            echo json_encode(['err' => $e->getMessage()]);
            http_response_code(500);
            return;
        }
        break;
    case '/booking/slip/delete/id':
        $slip_payment = $_POST['slip_payment'];
        $slip_payment_delete = explode(',', $_POST['slip_payment_delete']);
        try {
            $params = [$slip_payment, $update_at, $id];
            $sql = "UPDATE reservations SET slip_payment=?,";
            $sql .= "update_at=? WHERE reservation_id=?";
            $stmt = connect_db()->prepare($sql);
            $stmt->execute($params);
            if (count($slip_payment_delete) > 0) {
                foreach ($slip_payment_delete as $s) {
                    $f = "$slip_payment_dir/$s";
                    if (file_exists($f)) {
                        unlink($f);
                    }
                }
            }
        } catch (PDOException $e) {
            echo json_encode(['err' => $e->getMessage()]);
            http_response_code(500);
            return;
        }
        break;

    case '/booking/data/id':
        $sql = "SELECT rooms.room_id,rooms.room_name,rooms.room_number,rooms.thumbnail,rooms.price,";
        $sql .= "rooms.room_type,room_type.room_type_id,room_type.room_type_name,";
        $sql .= "member.member_id,member.fname,member.lname,";
        $sql .= "reservations.*";
        $sql .= " FROM reservations INNER JOIN member ON ";
        $sql .= " reservations.member_id=member.member_id ";
        $sql .= " LEFT JOIN rooms ON ";
        $sql .= " rooms.room_id=reservations.room_id";
        $sql .= " LEFT JOIN room_type ON ";
        $sql .= " room_type.room_type_id=rooms.room_type";
        $sql .= " WHERE reservations.reservation_id =?";
        try {
            $data_stmt = connect_db()->prepare($sql);
            $data_stmt->execute([$id]);
            $data_booking = $data_stmt->fetchAll();

            echo json_encode(['reservation' => $data_booking[0]]);
            return;
        } catch (PDOException $e) {
            echo json_encode(['err' => $e->getMessage()]);
            http_response_code(500);
            return;
        }
        break;
    case '/booking/available':
        $date_stamp = get_overday_datestamp("$start_dt 00:00:00", "$end_dt 00:00:00");
        $params = ['confirm', 'progress', $room_id];
        $sql = "SELECT * FROM reservations  ";
        $sql .= " WHERE (status =? OR status = ?) ";
        $sql .= " AND room_id=?  AND (";
        $_dt_sql = get_available_datestamp($date_stamp);
        $sql .= $_dt_sql[0];
        array_push($params, ...$_dt_sql[1]);
        $sql .= ")";
        try {
            $stmt = connect_db()->prepare($sql);
            $stmt->execute($params);
            $sql = '';
            $is_available = $stmt->rowCount() == 0;
            echo json_encode(['is_available' => $is_available]);
            return;
        } catch (PDOException $e) {
            echo json_encode(['err' => $e->getMessage()]);
            http_response_code(500);
            return;
        }
        break;
    case '/booking/reservat':
        $params = [$room_id];
        $sql = "SELECT * FROM reservations WHERE room_id=? AND ";
        $date_stamp = get_overday_datestamp($start_dt, $end_dt);
        $_dt_sql = get_available_datestamp($date_stamp);
        $sql .= $_dt_sql[0];
        array_push($params, ...$_dt_sql[1]);
        $stmt = connect_db()->prepare($sql);
        $stmt->execute($params);
        $row = $stmt->fetchAll();
        echo json_encode(['reservat_calendar' => $row]);
        return;
        break;
    default:
        break;
}
