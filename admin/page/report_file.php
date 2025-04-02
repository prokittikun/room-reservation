<?php
$r = $_GET['r'];
$page_target = "?r=$r";
$page = $_GET['page'] ?? 0;
$per_page = $_GET['per_page'] ?? 10;
$index_start = (int)$page * (int)$per_page;

$start_dt = $_GET['start_dt'] ?? '';
$end_dt = $_GET['end_dt'] ?? '';
$filetype = $_GET['filetype'] ?? '';
$soft_delete = 'true';
$params = ['true'];

$sql = "SELECT * FROM reportfile WHERE  soft_delete != ? ";


if (!empty($start_dt) && !empty($end_dt)) {
    $sql .= " AND  (create_at BETWEEN ? AND ?) ";
    $_start = "$start_dt 00:00:00";
    $_end = "$end_dt 23:59:59";
    $range = [$_start, $_end];
    array_push($params, ...$range);
}


$all = getDataCountAll($sql, 'file_id', $params, $page, $per_page);
$row_count = $all['row_count'];
$start_row = $all['start_row'];
$page_all = $all['page_all'];
$sql .= " ORDER BY create_at DESC LIMIT ?,?";
array_push($params, $start_row);
array_push($params, $per_page);
$row = getDataAll($sql, $params);
$idx_start = ($page * $per_page) + 1;
$idx_end = ($idx_start + $per_page) - 1;
$idx = $start_row + 1;

$route_params = get_params_isNotEmpty([
    'r' => $r,
    'page' => $page,
    'start_dt' => $start_dt, 'end_dt' => $end_dt,
]);
?>

<div class="row align-items-center">
    <?php require_once('./page/entries_row.php') ?>
    <div class="col-auto">
        <button id="selectAll" class="btn btn-sm bg-gradient-lightblue">
            <i class="fa-solid fa-check"></i>
            <span class="ml-1">เลือกทั้งหมด</span>
        </button>
    </div>
    <div class="col-auto">
        <button id="deleteBySelectAll" class="btn btn-sm bg-gradient-lightblue">
            <i class="fa-solid fa-trash-can"></i>
            <span class="ml-1">ลบ</span>
        </button>
    </div>

</div>
<?php require_once('./page/query_form.php') ?>

<?php if (isset($_GET['start_dt'])) { ?>
    <div class="row my-1 p-2 title-header rounded">
        <div class="col-auto">
            <span>ตั้งแต่วันที่ </span>
            <strong class="text-danger"><?php echo $start_dt ?></strong>
            <span>ถึงวันที่ </span>
            <strong class="text-danger"><?php echo $end_dt ?></strong>
        </div>
    </div>
<?php } ?>





<div class="card">
    <div class="card-body">
        <div class="m-0 table-responsive">
            <table class="m-0 table table-striped">
                <thead>
                    <tr>
                        <th style="width:5%" class="text-center" scope="col">ลำดับ</th>
                        <th style="width:20%">วันที่สร้าง</th>
                        <th style="width:55%">ชื่อไฟล์</th>
                        <th style="width:15%" scope="col"></th>
                        <th style="width:10%" scope="col"></th>
                    </tr>
                </thead>
                <tbody class="">
                    <?php foreach ($row as $r) { ?>
                        <tr>
                            <th class="text-center" scope="row"><?php echo $idx++ ?></th>
                            <td><?php echo $r['create_at'] ?></td>
                            <td><?php echo $r['filename'] ?></td>
                            <td>
                                <!-- <a class="btn btn-sm bg-gradient-lightblue" target="_blank" href="../<?php echo $r['storage'] ?>">
                                    <i class="fa-solid fa-circle-down"></i>
                                </a> -->
                                <button name="filereport-remove" data-filename="<?php echo $r['filename'] ?>" data-id="<?php echo $r['file_id']  ?>" class="btn btn-sm bg-gradient-lightblue">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>

                            </td>
                            <td class="text-center">
                                <div class="custom-control custom-checkbox d-inline">
                                    <input class="custom-control-input" type="checkbox" data-filename="<?php echo $r['filename'] ?>" name="file-select" id="<?php echo $r['file_id'] ?>">
                                    <label for="<?php echo $r['file_id'] ?>" class="custom-control-label"></label>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php
$_p = setPaginateQuery($route_params, $idx, $idx_end, $per_page);
echo create_pagination($page, $page_all, $_p[0], $row_count, $idx_start, $_p[1]);
?>

<script src="./js/report_file.js"></script>
<script src="./js/query_form.js"></script>