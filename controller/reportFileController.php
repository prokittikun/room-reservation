<?php
@session_start();
$user_type = $_SESSION['user_type'] ?? '';
require_once('../config/config_db.php');
require_once('../function/create_random.php');
require_once('../function/function.php');
require_once('../function/date.php');
$route = $_POST['route'] ?? '';
$id = $_POST['id'] ?? '';
$check_id = $_POST['check_id'] ?? '';

$column_name = $_POST['column_name'] ?? '';
$table_name = $_POST['table_name'] ?? '';
$update_at = create_date();
$sql = '';
$params = [];
$storage = "../assets/pdf";

switch ($route) {
    case '/report/delete/many':
        $check_id_list = explode(',', $check_id);
        $sql = "DELETE FROM reportfile  WHERE file_id IN ( ";
        for ($i = 0; $i < count($check_id_list); $i++) {
            $sql .= "?";
            array_push($params, $check_id_list[$i]);
            if ($i < count($check_id_list) - 1) $sql .= ",";
        }
        $sql .= " ) ";
        $delete_filename = explode(',', $_POST['filename']);
        foreach ($delete_filename as $f) {
            $file_delete =  "$storage/$f";
            if (file_exists($file_delete)) {
                unlink($file_delete);
                echo $file_delete;
            }
        }
        echo $sql;
        try {

            $stmt = connect_db()->prepare($sql);
            $stmt->execute($params);
        } catch (PDOException $e) {
            echo json_encode(['err' => $e->getMessage()]);
            http_response_code(500);
        }

        break;
    default:
        break;
}
