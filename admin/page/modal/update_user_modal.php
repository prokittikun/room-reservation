<div class="modal fade" id="userModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header  bg-lightblue align-items-center">
                <h5 class="modal-title">แก้ไขข้อมูลผู้ใช้งาน</h5>
                <button class="btn bg-danger" data-dismiss="modal">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <input type="text" maxlength="150" class="form-control" id="fname" placeholder="ชื่อ" />
                </div>
                <div class="form-group
                ">
                    <input type="text" maxlength="150" class="form-control" id="lname" placeholder="นามสกุล" />
                </div>
                <div class="form-group">
                    <input type="text" maxlength="10" class="form-control" id="tel" placeholder="เบอร์โทรศัพท์" />
                </div>
                <!-- <p class="err-validate" id="roomTypeNameValidate"></p> -->

            </div>
            <div class="modal-footer">
                <button class="btn bg-gradient-secondary" data-dismiss="modal">
                    ปิด
                </button>
                <button id="roomTypeSubmit" class="btn bg-gradient-lightblue">
                    <span>บันทึก</span>
                </button>
            </div>
        </div>
    </div>
</div>