<?php
@session_start();
unset($_SESSION['emp_id']);
if (!isset($_SESSION['emp_id'])) {
    echo json_encode(['result' => true, 'status' => 'success']);
} else {
    echo json_encode(['result' => false, 'status' => 'error']);
    http_response_code(400);
}
