<?php
$id = $_GET['id'] ?? '';
$method = $_GET['method'] ?? 'post';
$row = [];
$dept_id = '';
$dept_name = '';
$is_username = '';


$img = '';
if (!empty($id)) {
  $sql = "SELECT *  FROM member WHERE member_id = ?";
  $row =  getDataById($sql, [$id]);
  $is_username = 'disabled';
}
?>

<!-- <div class="p-2">
    <a class="btn btn-sm bg-gradient-lightblue" href="?r=member_data">
      <i class="fa-solid fa-plus"></i>
      <span>สมาชิก</span>
    </a>
  </div> -->


<div class="row">
  <div class="col-md-9">
    <div class="card border shadow-none">
      <div class="card-header bg-light">
        <h5 class="m-0 card-title">รายละเอียดพนักงานและผู้ดูแล</h5>
      </div>
      <div class="card-body">
        <!-- <div class="form-group row">
          <label class="col-md-2 col-form-label text-md-right text-left">บทบาท</label>
          <div class="col-md-10">
            <select class="custom-select" id="role" data-role="<?php echo $row['role'] ?? '' ?>">
              <option value="">เลือก</option>
              <option value="general">ทั่วไป</option>
              <option value="admin">ผู้ดูแล</option>
            </select>
            <p class="validate-text" id="roleValidate"></p>
          </div>
        </div> -->
        <div class="form-group row">
          <label class="col-md-2 col-form-label text-md-right text-left">ชื่อ</label>
          <div class="col-md-10">
            <input type="text" value="<?php echo $row['fname'] ?? '' ?>" class="form-control" id="fname" placeholder="ป้อนชื่อ" />
            <p class="validate-text" id="fnameValidate"></p>
          </div>
        </div>
        <div class="form-group row">
          <label class="col-md-2 col-form-label text-md-right text-left">นามสกุล</label>
          <div class="col-md-10">
            <input type="text" value="<?php echo $row['lname'] ?? '' ?>" class="form-control" id="lname" placeholder="ป้อนนามสกุล" />
            <p class="validate-text" id="lnameValidate"></p>
          </div>
        </div>
        <div class="form-group row">
          <label class="col-md-2 col-form-label text-md-right text-left">เบอร์ติดต่อ</label>
          <div class="col-md-10">
            <input type="text" max="14" value="<?php echo $row['tel'] ?? '' ?>" class="form-control" id="tel" placeholder="ป้อนเบอร์ติดต่อ" />
            <p class="validate-text" id="telValidate"></p>
          </div>
        </div>



        <div class="form-group row">
          <label class="col-md-2 col-form-label text-md-right text-left">ชื่อเข้าใช้ระบบ</label>
          <div class="col-md-10">
            <input type="text" <?php echo $is_username ?> value="<?php echo $row['username'] ?? '' ?>" class="form-control" id="username" placeholder="ป้อนภาษาอังกฤษกับตัวเลขเท่านั้น" data-auth="true" />
            <p class="validate-text" id="usernameValidate"></p>
          </div>
        </div>
        <p class="text-danger">สำหรับรหัสผ่านหากท่านต้องการเปลี่ยนให้ท่านป้อนรหัสที่ต้องการได้เลย แล้ว เลือกเปลี่ยนรหัส </p>
        <div class="form-group row">
          <label class="col-md-2 col-form-label text-md-right text-left">รหัสผ่าน</label>
          <div class="col-md-10">
            <input type="password" class="form-control" id="password" placeholder="ป้อนภาษาอังกฤษกับตัวเลขเท่านั้น" />
            <p class="validate-text" id="passwordValidate"></p>
            <!-- <div class="custom-control custom-checkbox">
              <input class="custom-control-input" onclick="obscureText('#password')" type="checkbox" id="showPassword">
              <label for="showPassword" class="custom-control-label">แสดงรหัสผ่าน</label>
            </div> -->
            <div class="custom-control custom-checkbox">
              <input class="custom-control-input" type="checkbox" id="changePassword">
              <label for="changePassword" class="custom-control-label">เปลี่ยนรหัสผ่าน</label>
            </div>
          </div>

        </div>

      </div>
    </div>
  </div>

</div>
<div class="text-center m-1">
  <button data-method="<?php echo $method ?>" data-id="<?php echo $id ?>" id="userUpdateHandleSubmit" class="btn bg-gradient-lightblue">
    บันทึก
  </button>
</div>
<script src="./js/user_form.js"></script>