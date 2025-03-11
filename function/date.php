<?php
date_default_timezone_set('ASIA/BANGKOK');
function create_date()
{
    return date('Y-m-d H:i:s');
}

function date_stamp_id()
{
    return date('YmdHis');
}

function date_stamp_number()
{
    return getdate()[0];
}
function getCountDate($d)
{
    return strlen((string)$d) == 2 ? $d : "0$d";
}


function getMonthThai($m)
{
    $month = [
        'มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน',
        'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม',
        'กันยนยน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'
    ];
    return $month[(int)$m - 1];
}
function getShortMonthThai($m)
{
    $month = [
        'ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.',
        'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.',
        'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'
    ];
    return $month[(int)$m - 1];
}
function getYearThai($y)
{
    return (int)$y + 543;
}
function get_countdate($date)
{
    return  strlen((string)$date) == 1 ?  "0$date" : $date;
}

function getFullThaiDate($date)
{
    $dt = date('j', strtotime($date));
    $m = getMonthThai(date('m', strtotime($date)));
    $y = getYearThai(date('Y', strtotime($date)));
    return "$dt $m $y";
}
function getShortThaiDate($date)
{
    $dt = date('j', strtotime($date));
    $m = getShortMonthThai(date('m', strtotime($date)));
    $y = getYearThai(date('Y', strtotime($date)));
    return "$dt $m $y";
}
function getMonthAndYearThai($date)
{
    $m = getMonthThai(date('m', strtotime($date)));
    $y = getYearThai(date('Y', strtotime($date)));
    return "$m $y";
}

function getDayWeekName($w)
{
    return [
        'sun' => 'อาทิตย์', 'mon' => 'จันทร์',
        'tue' => 'อังคาร', 'wed' => 'พุธ',
        'thu' => 'พฤหัสบดี', 'fri' => 'ศุกร์',
        'sat' => 'เสาร์',
    ][$w];
}
