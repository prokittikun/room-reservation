<div class="sidebar">
    <?php
    if (isset($_SESSION['emp_id'])) { ?>
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="info">
                <h5 class="m-0"><?php echo $username  ?></h5>
                <p class="m-0 text-muted"><?php echo $fname  ?></p>
            </div>
        </div>
    <?php   } ?>
    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

            <li class="nav-item">
                <a href="?r=report" class="nav-link">
                    <i class="fa-solid fa-chart-simple nav-icon"></i>
                    <p>รายงานข้อมูลการจอง</p>
                </a>
            </li>



            <li class="nav-item">
                <a href="?r=report_file" class="nav-link">
                    <i class="fa-regular fa-file nav-icon"></i>
                    <p>ไฟล์รายงาน</p>
                </a>
            </li>



            <li class="nav-item">
                <a href="?r=reserv_cancel" class="nav-link">
                    <i class="fa-regular fa-pen-to-square nav-icon"></i>
                    <p>ยืนยันการยกเลิก</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="?r=confirm_pay" class="nav-link">
                    <i class="fa-regular fa-pen-to-square nav-icon"></i>
                    <p>ยืนยันการชำระเงิน</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="?r=reserv_confirm" class="nav-link">
                    <i class="fa-regular fa-pen-to-square nav-icon"></i>
                    <p>ยืนยันการจอง</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="?r=checkin" class="nav-link">
                    <i class="fa-solid fa-arrow-right-from-bracket nav-icon"></i>
                    <p>เข้าห้องพัก</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="?r=checkout" class="nav-link">
                    <i class="fa-solid fa-arrow-right-from-bracket nav-icon"></i>
                    <p>ออกห้องพัก</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="?r=reserv_d" class="nav-link">
                    <i class="fa-regular fa-pen-to-square nav-icon"></i>
                    <p>ข้อมูลการจอง</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="?r=payment_d" class="nav-link">
                    <i class="fa-regular fa-pen-to-square nav-icon"></i>
                    <p>ข้อมูลการชำระ</p>
                </a>
            </li>



            <li class="nav-item" id="infoMenu">
                <a class="nav-link">
                    <i class="fa-solid fa-layer-group  nav-icon"></i>
                    <p>
                        ข้อมูลห้องพัก
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="?r=rtype" class="nav-link">
                            <i class="fa-regular fa-pen-to-square nav-icon"></i>
                            <p>ประเภทห้องพัก</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="?r=rm" class="nav-link">
                            <i class="fa-regular fa-pen-to-square nav-icon"></i>
                            <p>จัดการห้องพัก</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="?r=rf" class="nav-link">
                            <i class="fa-solid fa-plus nav-icon"></i>
                            <p>ข้อมูลห้องพัก</p>
                        </a>
                    </li>

                </ul>
            </li>



            <li class="nav-item">
                <a href="?r=logo" class="nav-link">
                    <i class="fa-solid fa-plus nav-icon"></i>
                    <p>จัดการโลโก้</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="?r=carousel" class="nav-link">
                    <i class="fa-solid fa-plus nav-icon"></i>
                    <p>จัดการรูปภาพสไลด์</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="?r=contact" class="nav-link">
                    <i class="fa-solid fa-plus nav-icon"></i>
                    <p>การติดต่อ</p>
                </a>
            </li>

            <?php if ($role == 'admin') { ?>
                <li class="nav-item" id="userMenu">
                    <a class="nav-link">
                        <i class="fa-solid fa-user-gear nav-icon"></i>
                        <p>
                            พนักงานและผู้ดูแล
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="?r=emp_data" class="nav-link">
                                <i class="fa-regular fa-pen-to-square nav-icon"></i>
                                <p>พนักงานและผู้ดูแล</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="?r=user_m" class="nav-link">
                                <i class="fa-solid fa-users nav-icon"></i>
                                <p>สมาชิก</p>
                            </a>
                        </li>



                        <li class="nav-item">
                            <a href="?r=emp_form" class="nav-link">
                                <i class="fa-solid fa-plus nav-icon"></i>
                                <p>เพิ่มพนักงานและผู้ดูแล</p>
                            </a>
                        </li>

                    </ul>
                </li>
            <?php } ?>




        </ul>

    </nav>
    <!-- /.sidebar-menu -->
</div>