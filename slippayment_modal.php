<div class="modal fade" tabindex="-1" id="slipPaymentModal">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content ">
            <div class="modal-header bg-lightblue text-light">
                <h5 class="modal-title">ชำระเงิน</h5>
                <button type="button" class="btn bg-transparent" data-bs-dismiss="modal">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="card card-body  my-1">
                    <?php require('./payment.php') ?>
                </div>
                <div class="my-2">
                    <label for="slipPayment" class="form-label">อัพโหลดหลักฐานการชำระเงิน</label>
                    <input class="form-control" type="file" id="slipPayment" multiple accept="image/*">
                </div>
                <p class="err-validate" id="validateSlipPayment"></p>
                <div class="my-2">
                    <div class="row" id="slipPaymentPreview" style="height: 18rem;overflow-y:scroll;">
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                <button type="button" id="paymentHandleSubmit" class="btn btn-success">ชำระเงิน</button>
            </div>
        </div>
    </div>
</div>