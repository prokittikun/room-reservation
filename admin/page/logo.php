<?php
$sql = "SELECT * FROM logo ORDER BY update_at DESC";
$row = getDataById($sql, []);
$id = '';
$icon = '';
$logo = '';
$logo_type = '';
$is_logo = false;
$is_icon = false;
$logo_dir = '';
$icon_dir = '';
$logo_text = 'disabled';
$logo_image = 'disabled';
if (count($row) > 0) {
    $id =   $row['logo_id'] ?? '';
    $icon = $row['icon'] ?? '';
    $logo = $row['logo'] ?? '';
    $logo_type = $row['logo_type'];
    $icon = $row['icon'];
    $icon_dir = "../assets/images/logo/$icon";
    $is_icon = file_exists($icon_dir);
    if ($logo_type == 'logoImage') {
        $logo_dir = "../assets/images/logo/$logo";
        $is_logo = file_exists($logo_dir);
        $logo = '';
        $logo_image = '';
    }
    if ($logo_type == 'logoText') {

        $logo_text = '';
    }
}
?>
<div class="card">
    <div class="card-body">
        <label>ไอคอน Icon</label>
        <div class="form-group">
            <label for="iconFile">อัพโหลดไอคอน</label>
            <input data-file="<?php echo $is_icon ? 'true' : 'false' ?>" type="file" class="form-control-file" id="iconFile">
        </div>
        <p class="err-validate" id="validateIcon"></p>


        <div class="row">
            <?php if ($is_icon) { ?>
                <div class="col-md-3">
                    <p>ตัวอย่างรูปไอคอนเดิม</p>
                    <img src="<?php echo $icon_dir  ?>" style="width: 100%;object-fit:contain;">
                </div>
            <?php    } ?>
            <div class="col-md-3">
                <p>ตัวอย่างรูปไอคอน</p>
                <img id="previewIcon" style="width: 100%;object-fit:contain;">
            </div>
        </div>
        <div class="form-group my-2">
            <label>ชื่อเว็บไซต์</label>
            <input type="text" value="<?php echo $row['title'] ?? '' ?>" class="form-control" id="title" placeholder="ป้อนชื่อเว็บไซต์">
        </div>
        <p class="err-validate" id="validateTitle"></p>
        <input id="logoType" type="hidden" value="<?php echo $logo_type ?>">
        <label>โลโก้</label>
        <div class="custom-control custom-radio">
            <input class="custom-control-input" type="radio" id="logoImage" name="logotype">
            <label for="logoImage" class="custom-control-label">รูปภาพ</label>
        </div>
        <div class="custom-control custom-radio">
            <input class="custom-control-input" type="radio" id="logoText" name="logotype">
            <label for="logoText" class="custom-control-label">ตัวอักษร</label>
        </div>
        <p class="err-validate" id="validateLogoType"></p>
        <div class="form-group">
            <label>โลโก้</label>
            <input <?php echo $logo_text ?> value="<?php echo $logo ?>" type="text" class="form-control" id="logo" placeholder="ป้อนโลโก้">
        </div>
        <div class="form-group">
            <label for="logoFile">อัพโหลดไลโก้</label>
            <input type="file" data-file="<?php echo $is_logo ? 'true' : 'false' ?>" class="form-control-file" <?php echo $logo_image ?> id="logoFile">
        </div>
        <p class="err-validate" id="validateLogo"></p>

        <div class="row">
            <?php if ($is_logo) { ?>
                <div class="col-md-3">
                    <label for="">ตัวอย่างโลโก้เดิม</label>
                    <img src="<?php echo $logo_dir ?>" style="width: 100%;object-fit:contain;">
                </div>
            <?php  } ?>

            <div class="col-md-3">
                <label for="">ตัวอย่างโลโก้ใหม่</label>
                <img id="previewLogo" style="width: 100%;object-fit:contain;">
            </div>
        </div>

        <div class="my-2">
            <button id="logoHandleSubmit" data-id="<?php echo $id ?>" class="btn bg-gradient-lightblue">บันทึก</button>
        </div>
    </div>
</div>

<script src="./js/logo.js"></script>