<?php

require_once('../config/config_db.php');
require_once('../config/config_system.php');
$admin = $_POST['admin'];
$password = $_POST['password'];
$config =  new CompareUsername();
$compare = $config->compare($admin, $password);
@session_start();
if ($compare != false) {

    $_SESSION =  array_merge($_SESSION, [
        'emp_id' => $compare['id'],
        'admin_name' => $compare['username'],
        'fname' => $compare['name'],
        'lname' => '',
        'role' => 'admin',
    ]);

    echo json_encode([
        'result' => true, 'status' => 'success',
    ]);
} else {


    try {
        $sql = "SELECT * FROM employee WHERE username=? AND soft_delete !=?";
        $stmt = connect_db()->prepare($sql);
        $stmt->bindParam(1, $admin);
        $stmt->execute([$admin, 'true']);
        $row_count = $stmt->rowCount();

        if ($row_count == 0) {
            echo json_encode([
                'result' => false, 'status' => 'error',
                'is_username' => false
            ]);
            return;
        }
        if ($row_count > 0) {
            $emp =  $stmt->fetch(PDO::FETCH_ASSOC);
            $emp_id = $emp['emp_id'];
            $_pass = $emp['password'];


            $hass_pass =   password_verify($password, $_pass);
            if (!$hass_pass) {
                echo json_encode([
                    'result' => false, 'status' => 'error',
                    'is_password' => false
                ]);
                return;
            }
            if ($hass_pass) {
                $_SESSION = array_merge($_SESSION, [
                    'emp_id' => $emp_id,
                    'admin_name' => $emp['username'],
                    'fname' => $emp['fname'],
                    'lname' => $emp['lname'],
                    'role' => $emp['role'],
                ]);


                echo json_encode([
                    'result' => true, 'status' => 'success',
                ]);
            }
        }
    } catch (PDOException $e) {
        echo json_encode(['result' => false, 'status' => 'error', 'err' => $e->getMessage()]);
        http_response_code(500);
    }
}
