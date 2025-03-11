<?php
$sql = "SELECT * FROM contact_us ORDER BY update_at DESC";
$row = getDataById($sql, []);
?>

<footer class="bg-black pt-4 text-light">
    <div class="container">
        <div class="row">
            <div class="col-md-4">

                <?php if ($logo_type == 'logoImage') { ?>
                    <img class="rounded-circle" src="<?php echo $logo_dir ?>?" style="width: 50px;height:50px;object-fit:contain;">
                <?php  } else {
                    echo $logo;
                } ?>
                <p><?php echo $row['company_name'] ?? '' ?></p>
            </div>
            <div class="col-md-4">
                <h5>ที่ตั้ง</h5>
                <p class="m-0">
                    <?php if (!empty($row['house_no'] ?? '')) { ?>
                        <span><?php echo $row['house_no'] ?></span>
                    <?php } ?>
                    <?php if (!empty($row['village_no'] ?? '')) { ?>
                        <span>/</span>
                        <span><?php echo $row['village_no'] ?></span>
                    <?php } ?>

                    <?php if (!empty($row['village_name'])) { ?>
                        <span><?php echo $row['village_name'] ?></span>
                    <?php } ?>
                    <?php if (!empty($row['alley'])) { ?>
                        <span>ซอย</span>
                        <span><?php echo $row['alley'] ?></span>
                    <?php } ?>
                    <?php if (!empty($row['junction'])) { ?>
                        <span>แยก</span>
                        <span><?php echo $row['junction'] ?></span>
                    <?php } ?>
                    <?php if (!empty($row['road'])) { ?>
                        <span>ถนน</span>
                        <span><?php echo $row['road'] ?></span>
                    <?php } ?>
                    <?php if (!empty($row['sub_district'] ?? '')) { ?>
                        <span>ตำบล</span>
                        <span><?php echo $row['sub_district'] ?></span>
                    <?php } ?>
                    <?php if (!empty($row['district'] ?? '')) { ?>
                        <span>อำเภอ</span>
                        <span><?php echo $row['district'] ?></span>
                    <?php } ?>
                    <?php if (!empty($row['province'] ?? '')) { ?>
                        <span>จังหวัด</span>
                        <span><?php echo $row['province'] ?></span>
                    <?php } ?>



                </p>
            </div>
            <div class="col-md-4">
                <h5>ติดต่อ</h5>
                <?php if (!empty($row['email'] ?? '')) { ?>
                    <p class="m-0">
                        <?php echo $row['email'] ?>
                    </p>
                <?php } ?>
                <?php if (!empty($row['tel'] ?? '')) { ?>
                    <p class="m-0">
                        <span>Tel.</span>
                        <span><?php echo $row['tel'] ?></span>
                    </p>
                <?php } ?>
                <div class="mt-2 mb-2">
                    <a href="https://www.facebook.com/share/18APJUr3uj/?mibextid=wwXIfr" target="_blank" class="text-light me-2">
                        <i class="fab fa-facebook fa-2x"></i>
                    </a>
                    <a href="https://www.tiktok.com/@staffbankhunseua_camping" target="_blank" class="text-light">
                        <i class="fab fa-tiktok fa-2x"></i>
                    </a>
                </div>
            </div>

        </div>


    </div>
    <div class="bg-dark bg-opacity-50 p-3">
        <div class="container">
            Copyright&copy; <?php echo date("Y") ?> All rights reserved.
        </div>
    </div>
</footer>