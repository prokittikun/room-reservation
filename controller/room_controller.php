<?php

require_once('../function/function.php');
require_once('../function/date.php');
require_once('../function/create_random.php');
$search = $_POST['search'] ?? '';
$route = $_POST['route'] ?? '';
$id = isset($_POST['id'])
    ? $_POST['id']
    : 'RM' . random_number(8) . random_char(4) . random_number(2);

$room_name = $_POST['room_name'] ?? '';
$room_number = $_POST['room_number'] ?? '';
$room_type = $_POST['room_type'] ?? '';
$bed_amount = $_POST['bed_amount'] ?? '';
$price = $_POST['price'] ?? '';
$description = $_POST['description'] ?? '';
$detail = $_POST['detail'] ?? '';
$create_at = create_date();
$update_at = create_date();
$old_example_image = isset($_POST['old_img'])
    ? explode(',', $_POST['old_img']) : [];
$old_image_delete = isset($_POST['old_img_delete'])
    ? explode(',', $_POST['old_img_delete']) : [];


$params = [];
$example_image = [...$old_example_image];
$thumb = '';



$image_file_dir = "../assets/images/example_image";
$thumb_file_dir = "../assets/images/thumb";
if (!is_dir($image_file_dir)) {
    mkdir($image_file_dir);
}
if (!is_dir($thumb_file_dir)) {
    mkdir($thumb_file_dir);
}
if (isset($_FILES['img'])) {
    $example_image_file = $_FILES['img'];
    for ($i = 0; $i < count($example_image_file['name']); $i++) {
        $filetype = pathinfo($example_image_file['name'][$i], PATHINFO_EXTENSION);
        $filename = getdate()[0] . random_char(4) . random_number(2) . "." . $filetype;
        $old_target = $example_image_file['tmp_name'][$i];
        $new_target = "$image_file_dir/$filename";
        $m = move_uploaded_file($old_target, $new_target);
        if ($m) array_push($example_image, $filename);
        if (!$m) continue;
    }
}
if (isset($_FILES['thumbnail'])) {
    $thumbnail_file = $_FILES['thumbnail'];
    $filetype = pathinfo($thumbnail_file['name'], PATHINFO_EXTENSION);
    $filename = 'thumb_' . $id . "." . $filetype;
    $old_target = $thumbnail_file['tmp_name'];
    $new_target = "$thumb_file_dir/$filename";
    $m = move_uploaded_file($old_target, $new_target);
    if ($m) {
        $thumb = $filename;
    }
}
switch ($route) {
    case '/room/insert':
        $sql = "INSERT INTO rooms VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $params = [
            $id, $room_type, $room_name, $room_number,
            $bed_amount, $price, $thumb, implode(',', $example_image),
            $description, $detail, $create_at, $update_at, 'false'
        ];
        try {
            $stmt = connect_db()->prepare($sql);
            $stmt->execute($params);
        } catch (PDOException $e) {
            echo json_encode(['err' => $e->getMessage()]);
            http_response_code(500);
        }

        break;
    case '/room/update/id':
        $params = [
            $room_type,
            $room_name,
            $room_number,
            $description,
            $detail,
            $bed_amount,
            $price,
            $update_at,
            implode(',', $example_image)
        ];
        $sql = "UPDATE rooms SET room_type=?,";
        $sql .= "room_name=?,room_number=?,description=?,detail=?,";
        $sql .= "bed_amount=?,price=?,update_at=?,img=?";
        if (!empty($thumb)) {
            $sql .= ",thumbnail=?";
            array_push($params, $thumb);
        }


        $sql .= " WHERE room_id=?";
        array_push($params, $id);
        try {
            $stmt = connect_db()->prepare($sql);
            $stmt->execute($params);
            if (count($old_image_delete) > 0) {
                foreach ($old_image_delete as $img) {
                    $f = "$image_file_dir/$img";
                    if (file_exists($f)) {
                        unlink($f);
                    }
                }
            }
        } catch (PDOException $e) {
            echo json_encode(['err' => $e->getMessage()]);
            http_response_code(500);
        }

        break;
    case '/room/data/id':
        $sql = "SELECT * FROM rooms WHERE room_id=?";
        $params = [$id];
        try {
            $stmt = connect_db()->prepare($sql);
            $stmt->execute($params);
            echo json_encode(['room' => $stmt->fetchAll()]);
        } catch (PDOException $e) {
            echo json_encode(['err' => $e->getMessage()]);
            http_response_code(500);
        }
        break;
    case '/room/soft_delete':
        $sql = "UPDATE rooms SET soft_delete=?,";
        $sql .= "update_at=? WHERE room_id=?";
        $params = ['true', $update_at, $id];
        try {
            $stmt = connect_db()->prepare($sql);
            $stmt->execute($params);
        } catch (PDOException $e) {
            echo json_encode(['err' => $e->getMessage()]);
            http_response_code(500);
        }
        break;
    default:
        return;
        break;
}
