<div class="modal fade" id="refundPayModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header  bg-lightblue align-items-center">
                <h5 class="modal-title ">คืนเงิน</h5>
                <button class="btn bg-danger" data-dismiss="modal">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <div class="modal-body">

                <div class="form-group">
                    <label>ยอดจ่าย</label>
                    <input type="number" class="form-control" id="pay" onkeyup="updateRefundPay()">
                    <p class="err-validate" id="validatePay"></p>
                </div>
                <div class="form-group">
                    <label>ยอดคืน</label>
                    <input type="number" class="form-control" id="refundPay" onkeyup="updateRefundPay()">
                    <p class="err-validate" id="validateRefundPay"></p>
                </div>
                <div class="form-group">
                    <label>คงในระบบ</label>
                    <input type="number" disabled class="form-control" id="paid">
                    <p class="err-validate" id="validatePaid"></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn bg-gradient-secondary" data-dismiss="modal">
                    ปิด
                </button>
                <button type="button" id="refundPayHandleSubmit" class="btn bg-gradient-olive">
                    ตกลง
                </button>
            </div>
        </div>
    </div>
</div>