<?php
require_once('../config/config_db.php');
require_once('../function/create_random.php');
require_once('../function/function.php');
require_once('../function/date.php');
$route = $_POST['route'] ?? '';
$id = isset($_POST['id']) ? $_POST['id'] : 'EMPY' . random_number(6) . random_char(4) . random_number(8);
$fname = $_POST['fname'] ?? '';
$lname = $_POST['lname'] ?? '';
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';
$create_at = create_date();
$update_at = create_date();
$role = $_POST['role'] ?? '';

switch ($route) {
    case '/user/authen':
        $sql = "SELECT username FROM employee WHERE username=?";
        $params = [$username];
        $stmt = connect_db()->prepare($sql);
        $stmt->execute($params);
        $row_count = $stmt->rowCount();
        $is_authen = $row_count > 0;
        echo json_encode(['is_authen' => $is_authen]);
        return;
        break;

    case '/emp/insert':
        $sql_m = "SELECT * FROM employee WHERE username=?";
        $stmt_m = connect_db()->prepare($sql_m);
        $stmt_m->execute([$username]);
        if ($stmt_m->rowCount() > 0) {
            http_response_code(401);
            return;
        }
        $sql = "INSERT INTO employee VALUES (?,?,?,?,?,?,?,?,?)";
        $params = [
            $id,
            $role,
            $username,
            password_hash($password, PASSWORD_BCRYPT),
            $fname,
            $lname,
            $create_at,
            $update_at,
            'false'
        ];

        try {
            $stmt = connect_db()->prepare($sql);
            $stmt->execute($params);
        } catch (PDOException $e) {
            echo json_encode(['err' => $e->getMessage()]);
            http_response_code(500);
        }


        break;
    case '/emp/update/id':
        $params = [$role, $fname, $lname, $username,];
        $sql = "UPDATE employee SET role=?,fname=?,lname=?,username=?,";

        if (!empty($password)) {
            echo $password;
            $sql .= "password=?,";
            array_push($params, password_hash($password, PASSWORD_BCRYPT));
        }
        $sql .= "update_at=? WHERE emp_id=?";
        array_push($params, ...[$update_at, $id]);
        try {
            $stmt = connect_db()->prepare($sql);
            $stmt->execute($params);
        } catch (PDOException $e) {
            echo json_encode(['err' => $e->getMessage()]);
            http_response_code(500);
        }

        break;
    case '/emp/soft_delete':
        $sql = "UPDATE employee SET soft_delete =?,";
        $sql .= "update_at = ? WHERE emp_id=?";
        $params = ['true', $update_at, $id];
        try {
            $stmt = connect_db()->prepare($sql);
            $stmt->execute($params);
        } catch (PDOException $e) {
            echo json_encode(['err' => $e->getMessage()]);
            http_response_code(500);
        }

        break;
    case '/member/soft_delete':
        $sql = "UPDATE member SET soft_delete =?,";
        $sql .= "update_at = ? WHERE member_id=?";
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
        break;
}
