<?php
@session_start();
require_once('./config_menu.php');
require_once('../function/function.php');
require_once('./pagination.php');
require_once('../function/date.php');
require_once('../config/config_system.php');
$emp_id = $_SESSION['emp_id'] ?? '';
$username = $_SESSION['admin_name'] ?? '';
$fname = $_SESSION['fname'] ?? '';
$user_type = $_SESSION['user_type'] ?? '';
$role = $_SESSION['role'] ?? '';

$r = $_GET['r'] ?? 'report';
$template = $menu[$r]['template'];
$title = $menu[$r]['title'] ?? '';
$default_page = "./?r=$r";
$emp_role = [
    'emp_form' => [
        'role' => ['admin']
    ],
    'emp_data' => [
        'role' => ['admin']
    ]
];
if (!empty($emp_id)) {
    $user_sql = "SELECT * FROM employee ";
    $user_sql .= " WHERE emp_id=? AND soft_delete != ?";
    $user_stmt = connect_db()->prepare($user_sql);
    $user_stmt->execute([$emp_id, 'true']);
    if ($user_stmt->rowCount() <= 0) {
        $config =  new CompareUsername();
        if ($emp_id != $config->get_admin()) {
            
           unset($_SESSION['emp_id']);
           header("location:./signin.php");
        }
    }
} else {
    header("location:./signin.php");
}
$role_key = $emp_role[$r] ?? '';
$has_role = true;
if (!empty($role_key)) {
    $_role = $role_key['role'];
    $has_role = in_array($role, $_role);
}


if (!$has_role) {
    header("location:./index.php?r=report");
    return;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title  ?></title>
    <?php require_once('./head.php')  ?>
</head>



<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <nav class="main-header navbar navbar-expand navbar-lightblue">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="btn nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <?php if (!empty($emp_id)) { ?>
                        <button id="signOutHandleSubmit" class="nav-link">
                            <i class="fa-solid fa-right-from-bracket"></i>
                            <span>ออกจากระบบ</span>
                        </button>
                    <?php } ?>
                    <?php if (empty($emp_id)) { ?>
                        <a href="./signin.php" class="nav-link">
                            <span>เข้าสู่ระบบ</span>
                            <i class="fa-solid fa-right-to-bracket"></i>
                        </a>
                    <?php } ?>

            </ul>
        </nav>
        <aside class="main-sidebar sidebar-light-lightblue elevation-1">
            <div class="brand-link bg-lightblue">
                <img src="../assets/AdminLTE-3.2.0/dist/img/AdminLTELogo.png" class="brand-image" style="opacity: .8">
                <h5 class="brand-text text-light m-0 ">ระบบผู้ดูแล</h5>
                <p class="m-0 font-weight-bold">ระบบจองห้องพัก</p>
            </div>
            <?php require_once('./sidebar.php')  ?>
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <section class="content">
                <div class="container-fluid py-1">
                    <nav class="p-3 title-header m-0 rounded my-1">
                        <p class="m-0"><?php echo $title ?></p>
                    </nav>
                    <?php require_once("page/$template.php") ?>
                </div>
            </section>
            <script src="./js/pagination.js"></script>
            <script>
                const params = new URLSearchParams(location.search)
                const p = params.get('r') ?? ''
                $.each($('.nav-link'), (index, navLink) => {
                    const pathname = location.pathname
                    const protocol = location.protocol
                    const host = location.host
                    const href = $(navLink).attr('href')
                    const url = new URL(`${protocol}//${host}${pathname}${href}`)
                    const _p = url.searchParams
                    const link = _p.get('r')


                    if (link == p) $(navLink).addClass('active')
                    const navItem = $(navLink).parent().parent().parent().attr('id')
                    if (navItem) {
                        const nav_treeview = $(`#${navItem}`)
                            .children(':eq(1)')
                            .children()
                            .children()
                        const nav_treeview_items = $.map(nav_treeview, (el) => $(el).attr('href')).join(' ')
                        $.each($(nav_treeview), (i, el) => {
                            if ($(el).hasClass('active')) {
                                $(`#${navItem}`).addClass('menu-is-open')
                                $(`#${navItem}`).addClass('menu-open')
                            }
                        })
                    }
                })

                $('#signOutHandleSubmit').click(function() {

                    $.ajax({
                        url: './signout.php',
                        type: 'post',
                        complete: function(xhr, textStatus) {
                           
                                if (xhr.status == 200) {
                                    success('ออกจากระบบสำเร็จ', false)
                                    setInterval(() => {
                                        location.assign(`./signin.php`)
                                    }, 2000)
                                } else {
                                    errDialog('เกิดข้อผิดพลาด', 'ไม่สามารถออกจากระบบ', '')
                                }
                            
                        }
                    })

                })
            </script>
        </div>
    </div>

    <link rel="stylesheet" href="../assets/AdminLTE-3.2.0/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
    <script src="../assets/AdminLTE-3.2.0/plugins/jquery/jquery.min.js"></script>
    <script src="../assets/AdminLTE-3.2.0/plugins/jquery-ui/jquery-ui.min.js"></script>
    <script src="../assets/AdminLTE-3.2.0/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/AdminLTE-3.2.0/plugins/sparklines/sparkline.js"></script>
    <script src="../assets/AdminLTE-3.2.0/plugins/jqvmap/jquery.vmap.min.js"></script>
    <script src="../assets/AdminLTE-3.2.0/plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
    <script src="../assets/AdminLTE-3.2.0/plugins/sparklines/sparkline.js"></script>
    <script src="../assets/AdminLTE-3.2.0/plugins/moment/moment.min.js"></script>
    <script src="../assets/AdminLTE-3.2.0/plugins/daterangepicker/daterangepicker.js"></script>

    <script src="../assets/AdminLTE-3.2.0/plugins/summernote/summernote-bs4.min.js"></script>
    <script src="../assets/AdminLTE-3.2.0/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
    <script src="../assets/AdminLTE-3.2.0/dist/js/adminlte.js"></script>
</body>

</html>



</html>