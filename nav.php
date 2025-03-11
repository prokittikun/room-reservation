<?php
@session_start();
$member_id = $_SESSION['member_id'] ?? '';
$username = $_SESSION['username_member'] ?? '';
?>
<nav class="navbar navbar-expand-lg bg-black navbar-dark shadow-lg p-0">
    <div class="container">
    <a class="navbar-brand" href="./">
      <?php if ($logo_type == 'logoImage') { ?>
        <img src="<?php echo $logo_dir ?>?" style="width: 50px;height:50px;object-fit:contain;">
      <?php  } else {
        echo $logo;
      } ?>
    </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="./rooms.php">ห้องพัก</a>
                </li>

                <?php if (!empty($member_id)) { ?>
                    <!-- <li class="nav-item">
                        <a class="nav-link" href="./profile.php">
                        <i class="fa-solid fa-user"></i>
                            <span>ข้อมูลส่วนตัว</span>
                        </a>
                    </li> -->
                    <li class="nav-item">
                        <a class="nav-link" href="./reservation_history.php">
                            <i class="fa-solid fa-rotate"></i>
                            ประวัติการจอง
                        </a>
                    </li>
                    <li class="nav-item">
                        <a id="logout" class="nav-link btn">ออกจากระบบ</a>
                    </li>
                <?php } ?>
                <?php if (empty($member_id)) { ?>
                    <li class="nav-item">
                        <a class="nav-link" href="./signin.php">เข้าสู่ระบบ</a>
                    </li>
                <?php } ?>
            </ul>

        </div>
    </div>
</nav>
<script src="./js/logout.js"></script>