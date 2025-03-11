<?php
@session_start();
$member_id = $_SESSION['member_id'] ?? '';
$is_login = !empty($member_id) ? 'true' : 'false';
require_once('./config/config_db.php');
require_once('./function/function.php');
$id = $_GET['id'] ??  '';
$row = [];
$img = [];
if (!empty($id)) {
    $id = base64_decode($id);
    $sql = "SELECT rooms.*,room_type.room_type_id,";
    $sql .= "room_type.room_type_name FROM rooms LEFT JOIN room_type";
    $sql .= " ON rooms.room_type=room_type.room_type_id WHERE rooms.room_id=?";
    $row = getDataById($sql, [$id]);
    $img = !empty($row['img']) ? explode(',', $row['img']) : [];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require_once('./logo.php') ?>
    <?php require_once('./head.php') ?>
    <script src="./assets/AdminLTE-3.2.0/plugins/moment/moment.min.js"></script>
    <title><?php echo $row['room_name'] . " " . $row['room_number'] ?></title>
</head>

<body>
    <?php require_once('./nav.php') ?>
    <div class="container min-vh-100 my-3">
        <div class="row">
            <div class="col-md-9">
                <div class="row my-3">
                    <div class="col-md-5">
                        <div id="carouselExampleImages" class="carousel slide" data-bs-ride="true">
                            <div class="carousel-indicators">
                                <?php
                                foreach ($img as $_i => $_img) {
                                    $_active = $_i == 0 ? 'active' : '' ?>
                                    <button type="button" data-bs-target="#carouselExampleImages" data-bs-slide-to="<?php echo $_i ?>" class="<?php echo $_active ?>" aria-current="true" aria-label="Slide 1"></button>
                                <?php    } ?>
                            </div>
                            <div class="carousel-inner">
                                <?php
                                foreach ($img as $_i => $_img) {
                                    $_active = $_i == 0 ? 'active' : '' ?>
                                    <div class="carousel-item <?php echo $_active ?>">
                                        <img src="./assets/images/example_image/<?php echo $_img ?>" class="d-block w-100" alt="...">
                                    </div>
                                <?php    } ?>
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleImages" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleImages" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <h5><?php echo $row['room_name'] . " " . $row['room_number'] ?></h5>
                        <p><?php echo $row['description']  ?></p>
                        <div class="d-flex flex-wrap align-self-end">

                            <span class="text-muted me-2">
                                <i class="fa-solid fa-users-viewfinder text-muted"></i>
                                <span><?php echo ucwords($row['room_type_name']) ?></span>
                            </span>
                            <span class="text-muted">
                                <i class="fa-solid fa-bed text-black text-muted"></i>
                                <span><?php echo $row['bed_amount'] ?></span>
                            </span>
                        </div>
                        <p class="text-danger m-0"><?php echo number_format($row['price'], 2) ?></p>
                        <a href="#reservationForm" data-login="<?php echo $is_login ?>" id="reservationBtn" class="btn btn-success">
                            จองห้องพัก
                        </a>

                    </div>
                    <?php if (!empty($row['detail'])) { ?>
                        <section class="my-2">
                            <h5 class="">รายละเอียด</h5>
                            <p><?php echo $row['detail'] ?></p>
                        </section>
                    <?php  } ?>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="row my-2 align-items-center justify-content-center">
                <div class="col-auto">
                    <select id="calendar-date-month" class="form-select">
                        <option value="0">มกราคม</option>
                        <option value="1">กุมภาพันธ์</option>
                        <option value="2">มีนาคม</option>
                        <option value="3">เมษายน</option>
                        <option value="4">พฤษภาคม</option>
                        <option value="5">มิถุนายน</option>
                        <option value="6">กรกฎาคม</option>
                        <option value="7">สิงหาคม</option>
                        <option value="8">กันยายน</option>
                        <option value="9">ตุลาคม</option>
                        <option value="10">พฤศจิกายน</option>
                        <option value="11">ธันวาคม</option>
                    </select>
                </div>
                <div class="col-auto">
                    <div class="d-flex align-items-center">
                        <p class="form-text text-muted m-0 p-1 mr-1">ป้อนปีแบบ ค.ศ.</p>
                        <div class="flex-glow">
                            <input id="year" min="1" placeholder="ป้อนปีแบบ ค.ศ." maxlength="4" type="number" class="form-control" />
                        </div>
                    </div>
                </div>
                <div class="col-auto">
                    <button id="calendarFind" class="btn btn-success">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </div>
            </div>
            <div class="card my-3">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered" style="min-width:100%;width: 480px;">
                            <thead>
                                <tr class="bg-teal">
                                    <th style="width: 14%" scope="col">Sun</th>
                                    <th style="width: 14%" scope="col">Mon</th>
                                    <th style="width: 14%" scope="col">Tue</th>
                                    <th style="width: 14%" scope="col">Wed</th>
                                    <th style="width: 14%" scope="col">Thu</th>
                                    <th style="width: 14%" scope="col">Fri</th>
                                    <th style="width: 12%" scope="col">Sat</th>
                                </tr>
                            </thead>
                            <tbody class="table-bordered" id="calendarBody"></tbody>
                        </table>
                    </div>
                </div>
            </div>
            <section id="reservationForm" style="display:none">

                <div class="card">
                    <div class="card-header align-items-center bg-lightblue text-light">
                        <h5 class="cart-title m-0">ข้อมูลการจอง</h5>
                    </div>
                    <div class="card-body">
                        <label for="">ตัวเลือกเพิ่มเติม</label>
                        <div>
                            <input class="form-check-input" type="checkbox" value="" id="additionalCushion">
                            <label class="form-check-label" for="additionalCushion">
                                เบาะเสริม (+300 บาท)
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
                            <input type="hidden" id="price" value="<?php echo $row['price'] ?>">
                            <p class="m-0">
                                <strong>จำนวนวัน</strong>
                                <span class="text-danger  mx-2" id="dayCountText">0</span>
                                <span>คืน</span>
                            </p>
                            <input type="hidden" id="dayCount">
                            <input type="hidden" id="total">
                            <div id="priceBreakdown">
                                <p class="m-0">
                                    <strong>ราคาห้องพัก</strong>
                                    <span id="roomPriceText" class="text-muted"><?php echo number_format($row['price'], 2) ?></span>
                                    <span>บาท/คืน</span>
                                </p>
                                <div id="additionalCushionRow" style="display: none;">
                                    <p class="m-0">
                                        <strong>เบาะเสริม</strong>
                                        <span class="text-muted">300.00</span>
                                        <span>บาท</span>
                                    </p>
                                </div>
                            </div>
                            <p class="m-0">
                                <strong>ยอดรวม</strong>
                                <span id="totalText" class="text-success"><?php echo number_format(0, 2) ?></span>
                                <span>บาท</span>
                            </p>

                            <button class="btn btn-success" data-id="<?php echo base64_encode($id) ?>" id="reservationHandleSubmit">ตกลง</button>
                        </div>
                    </div>
                </div>


            </section>

        </div>


    </div>
    <script src="./js/resetvationDate.js"></script>
    <script src="./js/reservation_room.js"></script>
</body>

</html>
<script>
    $('#additionalCushion').change(function() {
        const additionalCharge = $('#additionalCushion').is(':checked') ? 300 : 0;
        if (!additionalCharge) {
            const currentTotal = parseFloat($('#totalText').text().replace(/,/g, '')) || 0;
            const newTotal = currentTotal - 300;
            $('#totalText').text(getNumberFormat(newTotal));
            $('#total').val(newTotal)
        } else {
            const currentTotal = parseFloat($('#totalText').text().replace(/,/g, '')) || 0;
            const newTotal = currentTotal + additionalCharge;
            $('#totalText').text(getNumberFormat(newTotal));
            $('#total').val(newTotal)
        }

    });
</script>