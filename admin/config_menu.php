<?php
// $menu = [
//     ['type' => '', 'file' => '.php'],
//     // ['type' => 'editborrow-book', 'file' => 'editborrow_book.php'],
//     ['type' => '', 'file' => '.php'],
//     ['type' => '', 'file' => '']
// ];

$menu = [
    'dashboard' => [
        'template' => 'menu',
        'title' => 'สรุปผมการยืม',
    ],
    'rtype' => [
        'template' => 'roomtype',
        'title' => 'ประเภทห้องพัก',
    ],

    'rm' => [
        'title' => 'จัดการห้องพัก',
        'template' => 'room_manage',
    ],
    'rf' => [
        'template' => 'room_form',
        'title' => 'ข้อมูลห้องพัก',
    ],
    'reserv_cancel' => [
        'template' => 'reserv_cancel',
        'title' => 'ยืนยันการยกเลิก',
    ],
    'dashboard' => [
        'template' => 'dashboard',
        'title' => 'สรุปการขาย',
    ],
    'confirm_pay' => [
        'title' => 'ยืนยันการชำระเงิน',
        'template' => 'confirm_pay',
    ],
    'reserv_confirm' => [
        'title' => 'ยืนยันการจอง',
        'template' => 'reserv_confirm',
    ],
    'checkin' => [
        'title' => 'เข้าห้องพัก',
        'template' => 'checkin',
    ],
    'checkout' => [
        'title' => 'ออกห้องพัก',
        'template' => 'checkout',
    ],
    'reserv_d' => [
        'title' => 'ข้อมูลการจอง',
        'template' => 'reversation_data',
    ],
    'reserv_f' => [
        'title' => 'ข้อมูลการจอง',
        'template' => 'reversation_form',
    ],
    'payment_d' => [
        'title' => 'ข้อมูลการชำระ',
        'template' => 'payment_detail',
    ],
    'user_m' => [
        'title' => 'สมาชิก',
        'template' => 'member_data',
    ],
    'emp_data' => [
        'title' => 'ข้อมูลผู้ใช้งาน และพนักงาน',
        'template' => 'emp_data',
    ],
    'emp_form' => [
        'title' => 'ฟอร์มบันทึกข้อมูลผู้ใช้งาน และ พนักงาน',
        'template' => 'emp_form',
    ],
    'user_form' => [
        'title' => 'ฟอร์มบันทึกข้อมูลผู้ใช้งาน และ พนักงาน',
        'template' => 'user_form',
    ],
    'logo' => [
        'template' => 'logo',
        'title' => 'จัดการโลโก้'
    ], 
    'carousel' => [
        'title' => 'จัดการรูปภาพสไลด์',
        'template' => 'carousel',
    ],
    'contact' => [
        'template' => 'contact',
        'title' => 'ติดต่อเรา'
    ],
   
    'report' => [
        'title' => 'รายงานข้อมูลการจอง',
        'template' => 'report',
    ],

    'report_file' => [
        'title' => 'ไฟล์รายงาน',
        'template' => 'report_file',
    ],
  
];
