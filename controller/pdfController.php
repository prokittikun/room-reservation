<?php
require_once  "../assets/lib/mpdf/vendor/autoload.php";
require_once('../config/config_db.php');
require_once('../function/date.php');
require_once('../function/bath_format.php');
require_once('../function/function.php');


$start_dt =  $_POST['start_dt'] ?? '';
$end_dt =  $_POST['end_dt'] ?? '';
$pay_status  = $_POST['pay_status'] ?? '';
$status = $_POST['status'] ?? '';
$sql  = "SELECT * FROM resevartions WHERE start_dt LIKE ? ";
$sql = "SELECT rooms.room_id,rooms.room_name,rooms.room_number,";
$sql .= "rooms.room_type,room_type.room_type_id,room_type.room_type_name,";
$sql .= "member.member_id,member.fname,member.lname,member.tel,";
$sql .= "reservations.*";
$sql .= " FROM reservations INNER JOIN member ON ";
$sql .= " reservations.member_id=member.member_id ";
$sql .= " LEFT JOIN rooms ON ";
$sql .= " rooms.room_id=reservations.room_id";
$sql .= " LEFT JOIN room_type ON ";
$sql .= " room_type.room_type_id=rooms.room_type";
$sql .= " WHERE reservations.soft_delete !=? ";
$sql .= " AND (( reservations.start_dt BETWEEN ? AND ?) ";
$sql .= " OR ( reservations.end_dt BETWEEN ? AND ?)) ";
$_start = "$start_dt 00:00:00";
$_end = "$end_dt 23:59:59";
$params = ['true', $_start, $_end, $_start, $_end];


if (!empty($status)) {
    $sql .= " AND reservations.status = ?";
    array_push($params, $status);
}
if (!empty($pay_status)) {
    $sql .= " AND reservations.pay_status = ?";
    array_push($params, $pay_status);
}

$sql .= " ORDER BY reservations.start_dt  ";

function create_table_body($data)
{
    $table = '<tr class="align-middle" style="border:1px solid #000;">';
    for ($i = 0; $i < count($data); $i++) {
        $align =  $i == 0 ? 'text-center' : '';
        $table .=  "<td class='$align' style='border:1px solid #000;'>$data[$i]</td>";
    }
    $table .= '</tr>';
    return $table;
}

function get_data_entries($data)
{
    $idx = $data['idx'];
    $d = $data['data'];
    $room = $d['room_name'] . " " . $d['room_number'];
    $name = "$d[fname] $d[lname]";
    $date_text = "<p class='m-0'>";
    $date_text .= $d['start_dt'];
    $date_text .= "</p>";
    $date_text .= "<p class='m-0'>";
    $date_text .= $d['end_dt'];
    $date_text .= "</p>";
    $date_text .= "</p>";

    $_datatable = [
        $idx,
        $room,
        $name,
        $d['tel'],
        number_format($d['total'], 2),
        $date_text,

        // $d['reservation_id'],
        // get_reservation_paystatus($d['pay_status']),
        // get_reservation_status($d['status']),
    ];

    $table = create_table_body($_datatable);
    return $table;
}



try {
    $data_items = 10000;
    $all_result =  getDataCountAll($sql, 'reservation_id', $params, 0, 10);
    $all_row = $all_result['row_count'];

    if ($all_row > $data_items) {
        echo json_encode([
            'result' => true,
            'status' => 'ok',
            'data_item' => $all_row,
            'is_items' => false
        ]);
        http_response_code(500);
    } else {
        $stmt = connect_db()->prepare($sql);
        $stmt->execute($params);
        $idx = 1;
        $return_count = 0;
        $borrow_count = 0;
        $rowCount = $stmt->rowCount();
        $table = '';
        $total = 0;
        $paid = 0;
        $cancel = 0;


        $table = '';
        $table .= '<table class="table">';
        $table .= '<thead>';
        $table .= '<tr>';
        $table .= '<th class="text-center" style="width: 3%;" scope="col">ลำดับ</th>';
        $table .= '<th style="width:21%;" scope="col">ห้อง</th>';
        $table .= '<th style="width: 18%;" scope="col">ชื่อ - นามสกุล</th>';
        $table .= '<th style="width: 9%;" scope="col">เบอร์</th>';
        $table .= '<th style="width: 8%;" scope="col">ยอด</th>';
        $table .= '<th style="width: 12%;" scope="col">วันที่จอง</th>';

        // $table .= '<th style="width:8%;" scope="col">รหัส</th>';
        // $table .= '<th style="width:10%;" scope="col">การจ่ายเงิน</th>';
        // $table .= '<th style="width:10%;" scope="col">สถานะ</th>';
        $table .= '</tr>';
        $table .= '</thead>';
        $table .= '<tbody>';
        if ($rowCount == 0) {
            $table .= '<tr class="align-middle" style="border:1px solid #000;">' .
                '<td colspan="8" style="border:1px solid #000;">' . "ไม่มีข้อมูล" . '</td>' .
                '</tr>';
        }
        $idx = 1;
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            if ($row['status'] == 'cancel') {
                $cancel += 1;
            }
            $paid += (float)$row['paid'];
            $total += (float)$row['total'];
            $table .=   get_data_entries(['idx' => $idx++, 'data' => $row]);
        }
        $table .= '</tbody></table>';

        $m_thai_start = getMonthThai(date("m", strtotime($start_dt)));
        $y_thai_start = getYearThai(date("Y", strtotime($start_dt)));
        $dt_thai_start = date("j", strtotime($start_dt));
        $m_thai_end = getMonthThai(date("m", strtotime($end_dt)));
        $y_thai_end = getYearThai(date("Y", strtotime($end_dt)));
        $dt_thai_end = date("j", strtotime($end_dt));
        $start_dt_thai = "$dt_thai_start $m_thai_start $y_thai_start";
        $end_dt_thai = "$dt_thai_end $m_thai_end $y_thai_end";
        $title = "รายงาน";
        $subTitle = "ตั้งแต่วันที่ $start_dt_thai ถึง $end_dt_thai";
        $header = "รายงานการจองห้องพัก";
        $footer = 'รายงาน' . date('วันที่ d-m-Y', strtotime($start_dt));
        $footer .= date(' ถึง วันที่ d-m-Y ', strtotime($end_dt));
        $footer .= date('สร้างเมื่อ Y-m-d');
        $mpdf = new \Mpdf\Mpdf(
            [
                'default_font' => 'sf-thonburi',
                'format' => [297, 210]
            ]
        );

        $stylesheet = file_get_contents('../assets/bootstrap-5.2.3-dist/css/bootstrap.min.css');

        $mpdf_style = file_get_contents('../admin/css/mpdf.css');
        $mpdf->defaultheaderfontsize = 10;
        $mpdf->defaultheaderfontstyle = 'B';
        $mpdf->defaultheaderline = 0;
        $mpdf->defaultfooterfontsize = 10;
        $mpdf->defaultfooterfontstyle = 'B';
        $mpdf->defaultfooterline = 0;

        $mpdf->SetHeader($header);
        $mpdf->SetFooter($footer);
        $mpdf->SetTitle($title);
        $mpdf->SetSubject($subTitle);
        $mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);
        $mpdf->WriteHTML($mpdf_style, \Mpdf\HTMLParserMode::HEADER_CSS);
        $mpdf->WriteHTML($table);
        $report_created = date_stamp_id();
        $start = str_replace('-', '', $start_dt);
        $end = str_replace('-', '', $end_dt);

        $report_filename = "PDF$report_created" . "S$start" . "E$end.pdf";
        $sql = "INSERT INTO reportfile VALUES (?,?,?,?,?,?,?)";

        $stmt = connect_db()->prepare($sql);
        $pdf_id = "PDF" . date_stamp_id();

        $pdf_directory = '../assets/pdf/';
        if (!is_dir($pdf_directory)) {
            mkdir($pdf_directory, 0777, true); // Create directory with full permissions
        }

        $file_location = __DIR__ . "/../assets/pdf/$report_filename";
        $params = [$pdf_id, $report_filename, 'pdf', substr($file_location, 2), create_date(), create_date(), 'false'];
        $stmt->execute($params);
        $mpdf->Output($file_location, 'F');

        echo json_encode([
            'result' => true,
            'status' => 'success',
            'file_target' => "pdf/$report_filename"
        ]);
    }
} catch (PDOException $e) {
    echo json_encode(['result' => false, 'status' => 'error', 'err' => $e->getMessage()]);
    http_response_code(500);
}
