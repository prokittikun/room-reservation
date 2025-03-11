<div class="modal fade" id="slipPaymentModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header  bg-lightblue align-items-center">
                <h5 class="modal-title">หลักฐานการชำระเงิน</h5>
                <button class="btn bg-danger" data-dismiss="modal">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="slipPaymentData">
                <input type="hidden" id="slipPaymentDeleteData">
                <div class="row" id="slipPaymentPreview" style="min-height:100%;height:18rem;overflow-y:scroll;"></div>
            </div>
            <div class="modal-footer">
                <button type="button"  class="btn bg-gradient-secondary" data-dismiss="modal">
                    ปิด
                </button>
                <button type="button"  id="slipPaymentEdit" class="btn bg-gradient-secondary">
                    แก้ไขสลิป
                </button>
                <button type="button" style="display:none" id="slipPaymentHandleDelete" class="btn bg-gradient-lightblue">
                    บันทึก
                </button>
            </div>
        </div>
    </div>
</div>