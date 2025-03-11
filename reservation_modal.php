<div class="modal fade" tabindex="-1" id="reservModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">บันทึกข้อมูลการจอง</h5>
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="roomId">
                <div class="card border-0">
                    <div class="card-body" id="roomCard">

                    </div>
                </div>
                <label for="">ตัวเลือกเพิ่มเติม</label>
                <div>
                    <input class="form-check-input" type="checkbox" value="" id="additionalCushion">
                    <label class="form-check-label" for="additionalCushion">
                        เบาะเสริม
                    </label>
                </div>

                <div class="row">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">วันที่</label>
                                <input type="date" class="form-control" id="startDt" onchange="resetvationDate()">
                                <p class="err-validate" id="validateStartDt"></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">ถึง</label>
                                <input type="date" class="form-control" id="endDt" onchange="resetvationDate()">
                                <p class="err-validate" id="validateEndDt"></p>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" id="price">
                    <p class="m-0">
                        <strong>จำนวนวัน</strong>
                        <span class="text-danger  mx-2" id="dayCountText">0</span>
                        <span>คืน</span>
                    </p>
                    <input type="hidden" id="dayCount">
                    <input type="hidden" id="total">
                    <p class="m-0">
                        <strong>ยอดรวม</strong>
                        <span id="totalText" class="text-success"><?php echo number_format(0, 2) ?></span>
                        <span>บาท</span>
                    </p>


                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    ปิด
                </button>
                <button class="btn btn-success" id="reservationHandleSubmit">จอง</button>
            </div>
        </div>
    </div>
</div>