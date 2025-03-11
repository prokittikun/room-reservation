<?php
$r = $_GET['r'] ?? '';

$params = ['true'];
$paginate = paginate();
$page = $paginate['page'];
$per_page = $paginate['per_page'];

$sql = "SELECT member_id, fname,lname,tel FROM member WHERE soft_delete !=? ";

$all = getDataCountAll($sql, 'member_id', $params, $page, $per_page);

$row_count = $all['row_count'];
$page_all = $all['page_all'];
$start_row = $all['start_row'];
$sql .= "ORDER BY create_at LIMIT ?,?";
array_push($params, $start_row);
array_push($params, $per_page);
$row = getDataAll($sql, $params);
$route_params = get_params_isNotEmpty(['r' => $r]);
?>


<div class="row align-items-center">
    <?php require_once('./page/entries_row.php') ?>
</div>
<?php require_once('./page/query_form.php') ?>

<div class="card">
    <div class="table-responsive m-0">
        <table class="m-0 table table-hover" style="width: 1600px;">
            <thead>
                <tr>
                    <th class="text-center" style="width: 3%;" scope="col">ลำดับ</th>
                    <!-- <th style="width: 8%;" scope="col">ยอด</th>
                    <th style="width:18%;" scope="col">ห้อง</th>
                     -->
                    <th style="width: 12%;" scope="col">ชื่อ - นามสกุล</th>
                    <th style="width: 6%;" scope="col">เบอร์</th>
                    <th style="width: 13%;" scope="col"></th>

                </tr>
            </thead>
            <tbody>
                <?php
                $idx_start = ($page * $per_page) + 1;
                $idx_end = ($idx_start + $per_page) - 1;
                $idx = $start_row + 1;
                foreach ($row as $r) {

                ?>
                    <tr>
                        <td class="text-center"><?php echo $idx++ ?></td>
                        <td><?php echo $r['fname'] . " " . $r['lname'] ?></td>
                        <td><?php echo $r['tel'] ?></td>
                        <td>
                            <!-- <a name="reserv-edit" class="btn btn-sm bg-gradient-secondary">
                                <i class="fa-solid fa-pen"></i>
                                <strong>แก้ไข</strong>
                            </a> -->
                            <button name="edit-user" data-id="<?php echo $r['member_id'] ?>" class="btn btn-sm bg-gradient-secondary">
                                <i class="fa-solid fa-pen"></i>
                            </button>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>






<?php
$_p = setPaginateQuery($route_params, $idx, $idx_end, $per_page);
echo create_pagination($page, $page_all, $_p[0], $row_count, $idx_start, $_p[1]);
?>
<?php require_once('./page/modal/update_user_modal.php') ?>
<script src="./js/showEditProfile.js"></script>
<script src="./js/checkin.js"></script>
<script src="./js/checkin.js"></script>
<script src="./js/refund_pay.js"></script>
<script src="./js/query_form.js"></script>
<script src="./js/reservation_data.js"></script>