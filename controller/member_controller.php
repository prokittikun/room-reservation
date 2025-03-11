<?php
require_once('../config/config_db.php');
require_once('../function/create_random.php');
require_once('../function/function.php');
require_once('../function/date.php');
$route = $_POST['route'] ?? '';
$search = $_POST['search'] ?? '';
$id = isset($_POST['id']) ? $_POST['id'] : 'MBID' . random_number(6) . random_char(4) . random_number(8);
$fname = $_POST['fname'] ?? '';
$lname = $_POST['lname'] ?? '';
$tel = $_POST['tel'] ?? '';
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';
$create_at = create_date();
$update_at = create_date();


switch ($route) {
    case '/user/authen':
        $sql = "SELECT username FROM meetingroom_user WHERE username=?";
        $params = [$username];
        $stmt = connect_db()->prepare($sql);
        $stmt->execute($params);
        $row_count = $stmt->rowCount();
        $is_authen = $row_count > 0;
        echo json_encode(['is_authen' => $is_authen]);
        return;
        break;

    case '/member/insert':
        $sql_m = "SELECT * FROM member WHERE username=?";
        $stmt_m = connect_db()->prepare($sql_m);
        $stmt_m->execute([$username]);
        if ($stmt_m->rowCount() > 0) {
            http_response_code(401);
            return;
        }
        $sql = "INSERT INTO member VALUES (?,?,?,?,?,?,?,?,?)";
        $params = [
            $id,
            $username,
            password_hash($password, PASSWORD_BCRYPT),
            $fname,
            $lname,
            $tel,
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
    case '/member/update':
        $sql = "UPDATE member SET fname=?,lname=?,";
        $sql .= "tel=?,update_at=?";
        $params = [$fname, $lname, $tel,  $update_at];
        if (!empty($password)) {
            array_push($params,  password_hash($password, PASSWORD_BCRYPT));
            $sql .= ",password=?";
        }
        $sql .= " WHERE member_id=?";
        array_push($params, $id);

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