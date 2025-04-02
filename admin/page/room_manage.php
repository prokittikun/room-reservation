<?php

// สำหรับการค้นหา
$params = ['true'];
$paginate = paginate();
$page = $paginate['page'];
$per_page = $paginate['per_page'];

$sql = "SELECT * FROM rooms WHERE soft_delete !=?";
if (!empty($start_dt) && !empty($end_dt)) {
  $sql .= " AND (create_at BETWEEN ? AND ?) ";
  array_push($params, $start_dt);
  array_push($params, $end_dt);
}

if (!empty($status) && $status != 'all') {
  $sql .= " AND status =? ";
  array_push($params, $status);
}

$all = getDataCountAll($sql, 'room_id', $params, $page, $per_page);

$row_count = $all['row_count'];
$page_all = $all['page_all'];
$start_row = $all['start_row'];
$sql .= "ORDER BY create_at LIMIT ?,?";
array_push($params, $start_row);
array_push($params, $per_page);
$row = getDataAll($sql, $params);
$route_params = get_params_isNotEmpty(['r' => $r, 'page' => $page]);
?>

<div class="row">
  <div class="col-auto">
    <?php echo entries_row_query($per_page, $route_params) ?>
  </div>
</div>
<div class="card">
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-hover">
        <thead>
          <tr>
            <th style="width: 5%;" scope="col">ลำดับ</th>
            <th style="width: 10%;" scope="col">รูปภาพ</th>
            <th style="width: 40%;" scope="col">ประเภทห้อง</th>
            <th style="width: 15%;" scope="col">หมายเลขห้อง</th>
            <th class="text-center" style="width: 10%;" scope="col">ราคา</th>
            <th style="width: 20%;" scope="col"></th>
          </tr>
        </thead>
        <tbody>
          <?php if ($row_count == 0) { ?>
            <tr>
              <td class="text-center" colspan="5">ไม่มีข้อมูล</td>
            </tr>
          <?php } ?>
          <?php

          $idx_start = ($page * $per_page) + 1;
          $idx_end = ($idx_start + $per_page) - 1;
          $idx = $start_row + 1;
          foreach ($row as $r) { 
            //get room type from room_type table
            $sql = "SELECT * FROM room_type WHERE room_type_id=?";
            $room_type = getDataById($sql, [$r['room_type']]);
            ?>
            <tr>
              <td class="text-center"><?php echo $idx++ ?></td>
              <td>
                <img src="../assets/images/thumb/<?php echo $r['thumbnail'] ?>" style="width: 2.4rem;" />
              </td>
              <td><?php echo $room_type['room_type_name'] ?></td>
              <td><?php echo $r['room_number'] ?></td>
              <td class="text-center"><?php echo number_format($r['price'], 2) ?></td>
              <td>
                <a href="?r=rf&method=put&id=<?php echo $r['room_id'] ?>" class="btn btn-sm bg-gradient-lightblue">
                  <i class="fa-solid fa-pen"></i>
                  <strong>Edit</strong>
                </a>

                <button name="r-remove" data-id="<?php echo $r['room_id'] ?>" class="btn btn-sm btn-light">
                  <i class="fa-solid fa-trash-can"></i>
                  <strong>Del</strong>
                </button>
              </td>
            </tr>
          <?php  } ?>

        </tbody>
      </table>
    </div>
  </div>
</div>
<?php
$_p = setPaginateQuery($route_params, $idx, $idx_end, $per_page);
echo create_pagination($page, $page_all, $_p[0], $row_count, $idx_start, $_p[1]);
?>
<script src="./js/room_manage.js"></script>