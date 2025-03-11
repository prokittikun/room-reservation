<?php
$dir = __DIR__;
$path_len = strlen(__DIR__);
$base_url =  substr($dir, 0, $path_len - strlen('function'));
require_once("$base_url/config/config_db.php");


function get_percent_total($sum, $amount)
{
    $percent = (float) $amount * 100 / (float) $sum;
    return number_format($percent, 2);
}


function is_params_empty($params)
{
    $new_filter = [];
    foreach ($params as $p) {
        if (!empty($p) || $p == 0) {
            array_push($new_filter, $p);
        }
    };
    return $new_filter;
}

function paginate()
{
    $page = isset($_GET['page']) ? (int)  $_GET['page'] : 0;
    $per_page = isset($_GET['per_page']) ? (int)  $_GET['per_page'] : 10;
    return ['page' => $page, 'per_page' => $per_page];
}



function getDataCountAll($sql, $column, $params, $page, $per_page)
{
    $fp = stripos($sql, "FROM");
    $cut = substr($sql, 0, $fp);
    $sql = "SELECT " . str_replace($cut, " COUNT($column) AS count ", $sql);
    $stmt = connect_db()->prepare($sql);
    $stmt->execute($params);
    $row =   $stmt->fetchAll();
    $row_count = (int)$row[0]['count'];
    $page_all = (int) ceil($row_count / $per_page);
    $start = (int) $page * $per_page;
    return [
        'row_count' => $row_count,
        'page_all' => $page_all,
        'start_row' => $start
    ];
}
function getPageAll($row_all, $page, $per_page)
{
    $page_all = (int) ceil($row_all / $per_page);
    $start = (int) $page * $per_page;
    return [
        'page_all' => $page_all,
        'start_row' => $start
    ];
}
function getDataAll($sql, $params)
{
    $row = [];
    $count_params =  count($params);
    $stmt = connect_db()->prepare($sql);
    for ($i = 0; $i < count($params); $i++) {
        if ($i == $count_params - 1 || $i == $count_params - 2) {
            $stmt->bindParam($i + 1, $params[$i], PDO::PARAM_INT);
        } else {
            $stmt->bindParam($i + 1, $params[$i]);
        }
    }
    $stmt->execute();
    $row =  $stmt->fetchAll();
    return $row;
}
function getDataOption($sql, $params)
{
    $row = [];
    $stmt = connect_db()->prepare($sql);
    $stmt->execute($params);
    $row =  $stmt->fetchAll();
    return $row;
}
function getDataById($sql, $params)
{
    $stmt = connect_db()->prepare($sql);
    $stmt->execute($params);
    $row =  $stmt->fetchAll();
    return  $row[0] ?? [];
}






function converttosqlstr($data_string)
{
    $list = explode(',', $data_string);
    $list_map_string = array_map(function ($d) {
        return "'$d'";
    }, $list);

    $str = implode(',', $list_map_string);
    return $str;
}


function findByName($column, $name, $params)
{
    $column_str = '';
    foreach ($column as $i => $col) {
        $column_str .= "$col LIKE ?";
        if ($i < count($column) - 1) $column_str .= " OR ";
    }
    $name_list = explode(' ', $name);
    $str_sql = ' AND (';
    foreach ($name_list as $i => $_n) {
        $str_sql .= " ($column_str) ";
        if ($i < count($name_list) - 1)  $str_sql .= " AND ";
        for ($p = 0; $p < count($column); $p++) {
            array_push($params, "%$_n%");
        }
    }
    $str_sql .= " ) ";
    return ['sql' => $str_sql, 'params' => $params];
}


function get_reservation_status($status)
{
    return [
        'progress' => 'รอการยืนยัน',
        'cancel' => 'ยกเลิก',
        'confirm' => 'ยืนยันแล้ว รอเข้าเข้าพักห้อง',
        'checkin' => 'เข้าพักห้องอยู่',
        'checkout' => 'ออกห้องพักแล้ว'
    ][$status] ?? '';
}
function get_reservation_paystatus($pay)
{
    return [
        'progress' => 'รอการยืนยัน',
        'paid' => 'ชำระเงินแล้ว',
        'unpaid' => 'ยังไม่ชำระเงิน',
        'cancelPaid' => 'ยกเลิกแต่มีการจ่ายเงิน',
        'cancelUnpaid' => 'ยกเลิกแบบไม่จ่ายเงิน',
    ][$pay] ?? '';
}

function get_reservation_status_text($status)
{
    return [
        'progress' => 'badge font-weight-light bg-warning text-black',
        'cancel' => 'text-danger font-weight-bold fw-bold',
        'confirm' => 'text-success font-weight-bold fw-bold',
        'checkin' => 'badge font-weight-light bg-primary bg-maroon ',
        'checkout' => 'bg-lightblue badge font-weight-light'
    ][$status] ?? '';
}
function get_reservation_paystatus_text($pay)
{
    return [
        'progress' => 'bg-warning text-black badge font-weight-light py-1',
        'paid' => 'bg-success badge font-weight-light py-1',
        'unpaid' => 'bg-danger badge font-weight-light fw-light',
        'cancelPaid' => 'bg-lightblue badge font-weight-light py-1 ',
        'cancelUnpaid' => 'text-danger',
    ][$pay] ?? '';
}


function difftime($start, $end)
{
    $diff = (int) strtotime($start) - (int) strtotime($end);
    return ceil($diff / 3600);
}





function setPaginateQuery($params, $idx, $idx_end, $per_page)
{
    $params['per_page']  = $per_page;
    $path = concat_path($params);
    $idx_end = $idx_end < $idx - 1 ?  $idx_end : $idx - 1;
    return [$path, $idx_end];
}

function get_params_isNotEmpty($params)
{
    $new = [];
    foreach ($params as $k => $r) {
        if (!empty($r) && !empty($k)) {
            $new["$k"] = $r;
        }
    }
    return $new;
}


function concat_path($params)
{
    $path = '';

    foreach ($params as $k => $r) {
        if (!empty($r) && !empty($k)) {
            $path .= $k == 'r'  ? '?' : '&';
            $path .= "$k=$r";
        }
    }
    return $path;
}


function get_in_sql($data_list)
{
    $params = [];
    $sql = '';
    for ($i = 0; $i < count($data_list); $i++) {
        $sql .= "?";
        if ($i < count($data_list) - 1) $sql .= ",";
        array_push($params, $data_list[$i]);
    }
    return [$sql, $params];
}

function get_available_datestamp($data)
{
    $sql = "(";
    $params = [];
    foreach ($data as $i => $d) {
        $sql .= "( date_stamp LIKE ? )";
        if ($i < count($data) - 1) $sql .= " OR ";
        array_push($params, "%$d%");
    }
    $sql .= ")";
    return [$sql, $params];
}
function get_available_sql($data)
{
    $sql = "";
    $params = [];
    foreach ($data as $i => $t) {
        $sql .= "( time_data LIKE ? )";
        if ($i < count($data) - 1) $sql .= " OR ";
        array_push($params, "%$t%");
    }
    return [$sql, $params];
}
function get_overday_time($date_start, $date_end, $time_hs, $time_ms, $time_he, $time_me)
{
    $time_data = [];
    $s = strtotime("$date_start $time_hs:$time_ms");
    $e = strtotime("$date_end $time_he:$time_me");
    for ($h = $s; $h <= $e; $h += 300) {
        $h_txt = date('H:i', $h);
        $is_t = array_search($h_txt, $time_data);
        if (gettype($is_t) == 'boolean') {
            array_push($time_data, $h_txt);
        }
    }
    return  $time_data;
}
function get_overday_datestamp($date_start, $date_end)
{
    $time_data = [];
    $s = strtotime("$date_start");
    $e = strtotime("$date_end");
    for ($dt = $s; $dt <= $e; $dt += 86400) {
        $dt_txt = date('Y-m-d', $dt);

        array_push($time_data, $dt_txt);
    }
    return  $time_data;
}
