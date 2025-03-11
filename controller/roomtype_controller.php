<?php

require_once('../function/function.php');
require_once('../function/date.php');
require_once('../function/create_random.php');
$search = $_POST['search'] ?? '';
$route = $_POST['route'] ?? '';
$id = isset($_POST['id'])
    ? $_POST['id']
    : 'RT' . random_number(8) . random_char(4);
$room_type_name = $_POST['room_type_name'] ?? '';
$create_at = create_date();
$update_at = create_date();
$sql = '';
$params = [];


switch ($route) {
    case '/roomtype/insert':
        $sql = "INSERT INTO room_type VALUES (?,?,?,?,?)";
        $params = [$id, $room_type_name, $create_at, $update_at,'false'];
        try {
            $stmt = connect_db()->prepare($sql);
            $stmt->execute($params);
        } catch (PDOException $e) {
            echo json_encode(['err' => $e->getMessage()]);
            http_response_code(500);
        }
        break;
    case '/roomtype/update':
        $sql = "UPDATE room_type SET room_type_name=?,";
        $sql .= "update_at=? WHERE room_type_id=?";
        $params = [$room_type_name, $update_at, $id];
        try {
            $stmt = connect_db()->prepare($sql);
            $stmt->execute($params);
        } catch (PDOException $e) {
            echo json_encode(['err' => $e->getMessage()]);
            http_response_code(500);
        }
        break;
    case '/roomtype/data/id':
        $sql = "SELECT * FROM room_type WHERE room_type_id=?";
        $params = [$id];
        try {
            $stmt = connect_db()->prepare($sql);
            $stmt->execute($params);
            $row = $stmt->fetchAll();
            echo json_encode(['roomtype' => $row]);
        } catch (PDOException $e) {
            echo json_encode(['err' => $e->getMessage()]);
            http_response_code(500);
        }

        break;
    case '/roomtype/soft_delete/id':
        $sql = "UPDATE room_type SET soft_delete=?,";
        $sql .= "update_at=? WHERE room_type_id=?";
        try {
            $stmt = connect_db()->prepare($sql);
            $stmt->execute(['true', $update_at, $id]);
        } catch (PDOException $e) {
            echo json_encode(['err' => $e->getMessage()]);
            http_response_code(500);
        }

        break;
    default:
        return;
        break;
}
