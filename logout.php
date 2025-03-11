<?php
@session_start();
unset($_SESSION['member_id']);
if (!isset($_SESSION['member_id'])) {
    echo json_encode(['result' => true]);
} else {
    echo json_encode(['result' => false]);
    http_response_code(500);
}
