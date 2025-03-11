<?php
$r = $_GET['r'] ?? '';
$params = ['true'];
$paginate = paginate();
$page = $paginate['page'];
$per_page = $paginate['per_page'];

$sql = "SELECT * FROM room_type WHERE soft_delete != ?";
if (!empty($start_dt) && !empty($end_dt)) {
  $sql .= " AND (create_at BETWEEN ? AND ?) ";
  array_push($params, $start_dt);
  array_push($params, $end_dt);
}

if (!empty($status) && $status != 'all') {
  $sql .= " AND status =? ";
  array_push($params, $status);
}

$all = getDataCountAll($sql, 'room_type_id', $params, $page, $per_page);

$row_count = $all['row_count'];
$page_all = $all['page_all'];
$start_row = $all['start_row'];
$sql .= "ORDER BY create_at LIMIT ?,?";
array_push($params, $start_row);
array_push($params, $per_page);
$row = getDataAll($sql, $params);

$route_params = get_params_isNotEmpty(['r' => $r]);
?>

<div class="row">
  <div class="col-auto">
    <?php echo entries_row_query($per_page, $route_params) ?>
  </div>
  <div class="col-auto">
    <button id="addRoomType" class="btn btn-sm bg-gradient-lightblue">
      <i class="fa-solid fa-plus"></i>
      <span class="ml-1">เพิ่มข้อมูล</span>
    </button>
  </div>
</div>

<div class="card">
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-hover">
        <thead>
          <tr class="">
            <th class="text-center" style="width: 5%;" scope="col">ลำดับ</th>
            <th style="width:50%;" scope="col">ชื่อประเภทห้องพัก</th>
            <th style="width: 20%;" scope="col">วันที่เพิ่ม</th>
            <th style="width: 25%;" scope="col"></th>
          </tr>
        </thead>
        <tbody>
          <?php if ($row_count == 0) { ?>
            <td colspan="4" class="text-center">ไม่พบข้อมูล</td>
          <?php } ?>
          <?php
          $idx_start = ($page * $per_page) + 1;
          $idx_end = ($idx_start + $per_page) - 1;
          $idx = $start_row + 1;
          foreach ($row as $r) { ?>
            <tr>
              <td class="text-center"><?php echo $idx++ ?></td>
              <td><?php echo $r['room_type_name'] ?></td>
              <td><?php echo $r['create_at'] ?></td>
              <td>
                <button name="rt-update" data-id="<?php echo $r['room_type_id'] ?>" class="btn btn-sm bg-gradient-lightblue">
                  <i class="fa-solid fa-pen"></i>
                </button>
                <button name="rt-remove" data-id="<?php echo $r['room_type_id'] ?>" class="btn btn-sm btn-light">
                  <i class="fa-solid fa-trash-can"></i>
                </button>
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
<?php require_once('./page/modal/roomtype_modal.php') ?>
<script src="./js/roomtype.js"></script>