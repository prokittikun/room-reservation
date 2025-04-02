<?php
$method = $_GET['method'] ?? 'post';
$id = $_GET['id'] ?? '';
$row = [];
$thumb = '';
$example_image = [];
if (!empty($id)) {
    $row = getDataById("SELECT * FROM rooms WHERE room_id =?", [$id]);
    $thumb = $row['thumbnail'] ?? '';
    $example_image = explode(',', $row['img']);
}
?>
<?php
$room_type_sql = "SELECT * FROM room_type WHERE soft_delete !=?";
$room_type_option = getDataOption($room_type_sql, ['true']);
?>

<!-- <div class="form-group">
    <label>ชื่อห้อง</label>
    <input type="text" class="form-control" value="<?php echo $row['room_name'] ?? '' ?>" id="roomName" maxlength="300">
</div> -->
<div class="form-group">
    <label>ประเภทห้อง</label>
    <select class="custom-select font-weight-bold" id="roomType" data-type="<?php echo $row['room_type'] ?? '' ?>">
        <option value="">เลือก</option>
        <?php foreach ($room_type_option as $rt_opt) { ?>
            <option value="<?php echo $rt_opt['room_type_id'] ?>">
                <?php echo $rt_opt['room_type_name'] ?>
            </option>

        <?php  } ?> ?>
        <?php ?>
    </select>
    <p class="err-validate" id="validateRoomType"></p>
</div>
<div class="form-group">
    <label>หมายเลขห้อง</label>
    <input type="text" class="form-control" id="roomNumber" value="<?php echo $row['room_number'] ?? '' ?>" maxlength="50">
</div>
<p class="err-validate" id="validateRoomNameAndNumber"></p>

<div class="row">
    <!-- <div class="col-auto">
        <label>ประเภทห้อง</label>
        <select class="custom-select font-weight-bold" id="roomType" data-type="<?php echo $row['room_type'] ?? '' ?>">
            <option value="">เลือก</option>
            <?php foreach ($room_type_option as $rt_opt) { ?>
                <option value="<?php echo $rt_opt['room_type_id'] ?>">
                    <?php echo $rt_opt['room_type_name'] ?>
                </option>

            <?php  } ?> ?>
            <?php ?>
        </select>
        <p class="err-validate" id="validateRoomType"></p>
    </div> -->
    <div class="col-auto">
        <div class="form-group">
            <label>จำนวนเตียง</label>
            <input type="number" class="form-control" id="bedAmount" min="1" placeholder="ป้อนจำนวนเตียง" value="<?php echo $row['bed_amount'] ?? '' ?>">
        </div>
        <p class="err-validate" id="validateBedAmount"></p>
    </div>
</div>
<div class="row">
    <div class="col-auto">
        <div class="form-group">
            <label>ราคาต่อคืน</label>
            <input type="number" class="form-control" id="price" min="1" placeholder="ป้อนราคาต่อคืน" value="<?php echo $row['price'] ?? '' ?>">
        </div>
        <p class="err-validate" id="validatePrice"></p>
    </div>
</div>
<?php if (!empty($thumb)) { ?>
    <p>รูปภาพเดิม</p>
    <div class="row my-1">
        <div class="col-auto">
            <img class="room-img-preview" src="../assets/images/thumb/<?php echo $thumb ?>" />
        </div>
    </div>
<?php } ?>
<div class="form-group">
    <label for="thumbnail">รูปภาพขนาดย่อ</label>
    <input type="file" class="form-control-file" id="thumbnail" accept="image/*">
</div>
<h6>ตัวอย่างรูปภาพขนาดย่อ</h6>
<div class="row">
    <div class="col-auto">
        <img class="room-img-preview" id="thumbnailPreview" />
    </div>
</div>
<p class="err-validate" id="validateThumbnail"></p>
<input type="hidden" id="oldImg" value="<?php echo $row['img'] ?? '' ?>">
<input type="hidden" id="oldImgDelete">
<?php if (count($example_image) > 0) { ?>
    <p>รูปภาพตัวอย่างเดิม</p>
    <div class="row my-1">
        <?php foreach ($example_image as $img) { ?>
            <div class="col-auto">
                <button class="btn bg-gradient-lightblue" name="old-img-delete" data-img="<?php echo $img ?>">
                    <i class="fa-solid fa-xmark"></i>
                </button>
                <img class="room-img-preview" src="../assets/images/example_image/<?php echo $img ?>" />
            </div>
        <?php   } ?>

    </div>
<?php } ?>
<div class="form-group">
    <label for="img">รูปภาพตัวอย่างห้องพัก</label>
    <input type="file" class="form-control-file" id="img" multiple accept="image/*">
</div>
<h6>ตัวอย่างรูปภาพห้องพัก</h6>
<div class="row" id="imgPreview"> </div>
<p class="err-validate" id="validateImg"></p>
<div class="form-group">
    <label>คำอธิบาย</label>
    <textarea class="form-control" id="description" rows="3" placeholder="อธิบายเกี่ยวกับห้องพัก"><?php echo $row['description'] ?? '' ?></textarea>
</div>
<div class="form-group">
    <label>รายละเอียด</label>
    <textarea class="form-control" id="detail" rows="3" placeholder="ป้อนรายละเอียดของห้องพัก"><?php echo $row['detail'] ?? '' ?></textarea>
</div>


<button class="btn bg-gradient-lightblue" id="roomHandleSave" data-method="<?php echo $method ?>" data-id="<?php echo $id ?>">บันทึก</button>
<script src="./js/room_form.js"></script>