<?php
@session_start();

require_once('./config/config_db.php');

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';


try {
    $sql = "SELECT * FROM member WHERE username=?";
    $stmt = connect_db()->prepare($sql);
    $stmt->execute([$username]);
    if ($stmt->rowCount() == 0) {
        http_response_code(401);
        return;
    } else {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $hass_pass = $row['password'];
        $is_pass = password_verify($password, $hass_pass);
        if (!$is_pass) {
            http_response_code(403);
            return;
        } else {
            $_SESSION['member_id'] = $row['member_id'];
            $_SESSION['username_member'] = $row['username'];
            $_SESSION['member_fname'] = $row['fname'];
            $_SESSION['member_lname'] = $row['lname'];
        }
    }
} catch (PDOException $e) {
    echo json_encode(['result' => false, 'err' => $e->getMessage()]);
    http_response_code(500);
}
