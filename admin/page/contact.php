<?php
$sql = "SELECT * FROM contact_us ORDER BY update_at DESC";
$row = getDataById($sql, []);
$id = '';
if (count($row) > 0) {
    $id = $row['id'];
}
?>


<div class="card">

    <div class="card-body">
        <label class="">ชื่อบริษัท</label>
        <div class="my-1">
            <input type="text" class="form-control" placeholder="ป้อนชื่อบริษัท" id="companyName" value="<?php echo $row['company_name'] ?? '' ?>">
        </div>
        <div class="row">
            <div class="col-md-4 my-1">
                <label for="">บ้านเลขที่</label>
                <input type="text" class="form-control" placeholder="บ้านเลขที่" id="houseNo" value="<?php echo $row['house_no'] ?? '' ?>">
            </div>
            <div class="col-md-4 my-1">
                <label for="">หมู่ที่</label>
                <input type="number" class="form-control" placeholder="หมู่ที่" id="villageNo" value="<?php echo $row['village_no'] ?? '' ?>">
            </div>
            <div class="col-md-4 my-1">
                <label for="">ชื่อหมู่บ้าน</label>
                <input type="text" class="form-control" placeholder="ชื่อหมู่บ้าน" id="villageName" value="<?php echo $row['village_name'] ?? '' ?>">
            </div>
            <div class="col-md-4 my-1">
                <label for="">ซอย</label>
                <input type="text" class="form-control" placeholder="ซอย" id="alley" value="<?php echo $row['alley'] ?? '' ?>">
            </div>
            <div class="col-md-4 my-1">
                <label for="">แยก</label>
                <input type="text" class="form-control" placeholder="แยก" id="junction" value="<?php echo $row['junction'] ?? '' ?>">
            </div>
            <div class="col-md-4 my-1">
                <label for="">ถนน</label>
                <input type="text" class="form-control" placeholder="ถนน" id="road" value="<?php echo $row['road'] ?? '' ?>">
            </div>
            <div class="col-auto">
                <label for="">จังหวัด</label>
            </div>
            <div class="col-auto">
                <?php
                $province_sql = "SELECT * FROM province";
                $province_stmt = connect_db()->prepare($province_sql);
                $province_stmt->execute();
                $province_row = $province_stmt->fetchAll();
                ?>

                <select class="custom-select" data-province="<?php echo $row['province'] ?? '' ?>" id="province">
                    <option value="">เลือก</option>
                    <?php foreach ($province_row as $province) { ?>
                        <option value="<?php echo trim($province['province'])  ?>">
                            <?php echo trim($province['province'])  ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
            <div class="col-auto">
                <label>อำเภอ</label>
            </div>
            <div class="col-auto">
                <div class="form-group">
                    <input type="text" class="form-control" id="district" value="<?php echo $row['district'] ?? '' ?>">
                </div>
            </div>
            <div class="col-auto">
                <label>ตำบล</label>
            </div>
            <div class="col-auto">
                <div class="form-group">
                    <input type="text" class="form-control" id="subDistrict" value="<?php echo $row['sub_district'] ?? '' ?>">
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-2 col-form-label text-right">
                อีเมล
            </label>
            <div class="col-md-10">
                <input type="text" class="form-control" placeholder="ป้อนอีเมล" id="email" value="<?php echo $row['email'] ?? '' ?>">
            </div>
        </div>

        <div class="form-group row">
            <label class="col-md-2 col-form-label text-right">เบอร์ติดต่อ</label>
            <div class="col-md-10">
                <input type="text" class="form-control" placeholder="ป้อนเบอร์ติดต่อ" id="tel" value="<?php echo $row['tel'] ?? '' ?>">
            </div>
        </div>
    </div>
</div>

<div class="text-center">
    <button data-id="<?php echo $id ?>" class="btn  bg-gradient-lightblue" id="contactHandleSave">
        <span class="ml-1 font-weight-bold">บันทึก</span>
    </button>
</div>
<script src="./js/contact.js"></script>