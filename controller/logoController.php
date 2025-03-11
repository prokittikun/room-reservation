<?php
require_once('../config/config_db.php');
require_once('../function/create_random.php');
require_once('../function/function.php');
require_once('../function/date.php');
$route = $_POST['route'] ?? '';
$search = $_POST['search'] ?? '';
$id = isset($_POST['id']) ? $_POST['id'] : 'I' . random_number(6) . random_char(4) . random_number(2);



$title = $_POST['title'] ?? '';
$icon = '';
$logo_type = $_POST['logo_type'] ?? '';
$logo = $_POST['logo'] ?? '';
$create_at = create_date();
$update_at = create_date();

$logo_dir = "../assets/images/logo";
if (!is_dir($logo_dir)) {
    mkdir($logo_dir);
}

if (isset($_FILES['logo'])) {
    $logo_file = $_FILES['logo'];
    $filetype = pathinfo($logo_file['name'], PATHINFO_EXTENSION);
    $filename = "logo_$id." . $filetype;
    $old_target = $logo_file['tmp_name'];
    $new_target = "$logo_dir/$filename";
    $m = move_uploaded_file($old_target, $new_target);
    if ($m)  $logo = $filename;
}
if (isset($_FILES['icon'])) {
    $icon_file = $_FILES['icon'];
    $filetype = pathinfo($icon_file['name'], PATHINFO_EXTENSION);
    $filename = "icon_$id." . $filetype;
    $old_target = $icon_file['tmp_name'];
    $new_target = "$logo_dir/$filename";
    $m = move_uploaded_file($old_target, $new_target);
    if ($m)  $icon = $filename;
}


$response = [];
switch ($route) {
    case '/logo/save':
        $params = [$id, $title, $icon, $logo_type, $logo, $create_at, $update_at];
        $sql = "SELECT * FROM logo WHERE logo_id=?";


        try {
            $stmt = connect_db()->prepare($sql);
            $stmt->execute([$id]);
            $sql = "INSERT INTO logo VALUES (?,?,?,?,?,?,?)";
            if ($stmt->rowCount() > 0) {

                $params = [$title,  $logo_type];
                $sql = "UPDATE logo SET title=?,logo_type=?,";
                if (!empty($icon)) {
                    $sql .= "icon=?,";
                    array_push($params, $icon);
                }
                if (!empty($logo)) {
                    $sql .= "logo=?,";
                    array_push($params, $logo);
                }
                array_push($params, ...[$update_at, $id]);
                $sql .= "update_at=? WHERE logo_id=?";
            }
            echo $sql;
            $stmt = connect_db()->prepare($sql);
            $stmt->execute($params);
        } catch (PDOException $e) {
            echo json_encode(['err' => $e->getMessage()]);
            http_response_code(500);
        }
    default:
        break;
}
