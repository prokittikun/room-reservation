<div class="modal fade" tabindex="-1" id="postponeModal">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content ">
            <div class="modal-header bg-lightblue text-light">
                <h5 class="modal-title">เลื่อนการจอง</h5>
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">วันที่</label>
                            <input type="date" class="form-control" onchange="resetvationDate()" id="startDt" value="<?php echo $row['start_dt'] ?? '' ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">ถึง</label>
                            <input type="date" class="form-control" onchange="resetvationDate()" id="endDt" value="<?php echo $row['end_dt'] ?? '' ?>">
                        </div>
                    </div>
                </div>
                <div class="text-right">
                    <div class="card">
                        <div class="card-body">
                            <p class="m-0 fw-bold">ยอดเดิม</p>
                            <p class="m-0">
                                <span>จำนวนวัน</span>
                                <span class="text-danger  mx-2" id="dayCountOldText"></span>
                                <span>คืน</span>
                            </p>
                            <p class="m-0">
                                <span>ยอดรวม</span>
                                <span id="totalOldText" class="text-success"></span>
                                <span>บาท</span>
                            </p>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <p class="m-0 fw-bold">ยอดใหม่</p>
                            <p class="m-0">
                                <span>จำนวนวัน</span>
                                <span class="text-danger  mx-2" id="dayCountText"></span>
                                <span>คืน</span>
                            </p>
                            <input type="hidden" id="dayCount">
                            <input type="hidden" id="total">
                            <input type="hidden" id="roomId">
                            <input type="hidden" id="price" value="0">
                            <p class="m-0">
                                <span>ยอดรวม</span>
                                <span id="totalText" class="text-success"></span>
                                <span>บาท</span>
                            </p>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <p class="m-0 fw-bold">ชำระเพิ่ม</p>
                            <p class="m-0">
                                <strong>ยอดรวม</strong>
                                <span id="paidText" class="text-success"></span>
                                <span>บาท</span>
                            </p>

                        </div>
                    </div>
                    <div class="card card-body">
                        <p class="text-danger fw-bold m-0">
                            หากมีการชำระเงินไปแล้ว และมีตรวจแล้ว ท่านชำระเงินไม่ครบ
                            ผู้ดูแลจะให้ท่านชำระเงินใหม่อีกครั้ง โดยให้ท่านแนบหลักให้ครบตามจำนวนที่ชำระ
                            หากท่านมีหลักฐาน มากกว่า 1 สลิปสามารถอัพโหลดหรือแนบมาให้ครบทั้งหมดได้เลย
                            และ หากหลักฐานที่ท่านแนบมาหากตรวจสอบแล้วไม่ผ่านทางเราจะลบให้อัติโนมัติ
                            เพื่อไม่ให้เกิดความสับสนในการอัพโหลด
                        </p>
                    </div>
                   

                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title fw-bold my-2">หลักฐานการชำระเงินเดิม</h5>
                        </div>
                        <div class="card-body">
                            <div class="my-2">
                                <div class="row" id="slipPaymentOldPreview" style="height: 18rem;overflow-y:scroll;">
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="card my-2">
                    <div class="card-header">
                        <p class=" fw-bold m-0">
                            หากมีการเลื่อนการจอง และ มีการเพิ่มจำนวนวันที่พัก
                            แล้วมีค่าใช้เพิ่มให้ท่านชำระเงินเพิ่มโดยหักจากของเดิมทีท่านชำระแล้ว
                            แล้วให้ท่านอัพโหลดหลักใหม่ชำระเพิ่ม
                        </p>
                    </div>
                    <div class="card card-body my-1">
                        <?php require('./payment.php') ?>
                    </div>
                    <div class="card-body">
                        <div class="my-2">
                            <label for="slipPayment" class="form-label">อัพโหลดหลักฐานการชำระเงิน</label>
                            <input class="form-control" type="file" id="postponeSlipPayment" multiple accept="image/*">
                        </div>
                        <p class="err-validate" id="validatePostponeSlipPayment"></p>
                        <div class="my-2">
                            <div class="row" id="postponeSlipPaymentPreview" style="height: 18rem;overflow-y:scroll;">

                            </div>
                        </div>
                    </div>
                </div>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                <button type="button" id="postponeHandleSubmit" class="btn btn-success">ชำระเงิน</button>
            </div>
        </div>
    </div>
</div>