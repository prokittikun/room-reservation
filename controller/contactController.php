<?php
require_once('../config/config_db.php');
require_once('../function/create_random.php');
require_once('../function/function.php');
require_once('../function/date.php');
$route = $_POST['route'] ?? '';
$search = $_POST['search'] ?? '';
$id = isset($_POST['id']) ? $_POST['id'] : 'CTUS' . random_number(6) . random_char(4) . random_number(2);

$company_name = $_POST['company_name'] ?? '';
$email = $_POST['email'] ?? '';
$tel = $_POST['tel'] ?? '';
$province = $_POST['province'] ?? '';
$district = $_POST['district'] ?? '';
$sub_district = $_POST['sub_district'] ?? '';
$address = $_POST['address'] ?? '';
$house_no = $_POST['house_no'] ?? '';
$village_no = $_POST['village_no'] ?? '';
$create_at = create_date();
$update_at = create_date();

switch ($route) {
    case '/contact/save':
        $params = [
            $id,
            $company_name,
            $house_no,
            $village_no,
            $province,
            $district,
            $sub_district,
            $email,
            $tel,
            $create_at,
            $update_at
        ];
        $sql = "SELECT * FROM contact_us WHERE id=?";

        try {
            $stmt = connect_db()->prepare($sql);
            $stmt->execute([$id]);
            $sql = "INSERT INTO contact_us (id, company_name, house_no, village_no, province, district, sub_district, email, tel, create_at, update_at) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            if ($stmt->rowCount() > 0) {
                $params = [
                    $company_name,
                    $house_no,
                    $village_no,
                    $province,
                    $district,
                    $sub_district,
                    $email,
                    $tel,
                    $update_at,
                    $id
                ];
                $sql = "UPDATE contact_us SET company_name=?, house_no=?, village_no=?, 
                        province=?, district=?, sub_district=?, email=?, tel=?, update_at=? 
                        WHERE id=?";
            }

            echo $sql;
            $stmt = connect_db()->prepare($sql);
            $stmt->execute($params);
        } catch (PDOException $e) {
            echo json_encode(['err' => $e->getMessage()]);
            http_response_code(500);
        }
        break;
}
