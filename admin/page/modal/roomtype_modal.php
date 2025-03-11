<div class="modal fade" id="roomTypeModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-gradient-lightblue align-items-center">
        <h5 class="modal-title">ประเภทห้องพัก</h5>
        <button type="button" class="btn bg-transparent" data-dismiss="modal">
          <i class="fa-solid fa-xmark"></i>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <input type="text" maxlength="150" class="form-control" id="roomTypeName" placeholder="ป้อนประเภทห้องพัก" />
        </div>
        <p class="err-validate" id="roomTypeNameValidate"></p>

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