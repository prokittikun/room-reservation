<?php
$name = $_GET['name'] ?? '';
$params = ['true'];
$paginate = paginate();
$page = $paginate['page'];
$per_page = $paginate['per_page'];

$sql = "SELECT meetingroom_meetingroom.*,";
$sql .= "meetingroom_buildingmeeting.building_meeting_id,";
$sql .= "meetingroom_buildingmeeting.building_name";
$sql .= " FROM meetingroom_meetingroom LEFT JOIN ";
$sql .= " meetingroom_buildingmeeting ON ";
$sql .= "meetingroom_meetingroom.building_meeting_id=";
$sql .= "meetingroom_buildingmeeting.building_meeting_id ";
$sql .= "WHERE meetingroom_meetingroom.soft_delete !=?";
if (!empty($start_dt) && !empty($end_dt)) {
  $sql .= " AND (create_at BETWEEN ? AND ?) ";
  array_push($params, $start_dt);
  array_push($params, $end_dt);
}

if (!empty($name)) {
  $col = ['meetingroom_meetingroom.meeting_room_name'];
  $name = str_ireplace('-', ' ', $name);
  $_params =  findByName($col, $name, $params);
  $sql .= $_params['sql'];
  $params = $_params['params'];
}

$all = getDataCountAll($sql, 'meetingroom_meetingroom.meeting_room_id', $params, $page, $per_page);

$row_count = $all['row_count'];
$page_all = $all['page_all'];
$start_row = $all['start_row'];
$sql .= "ORDER BY create_at LIMIT ?,?";
array_push($params, $start_row);
array_push($params, $per_page);
$row = getDataAll($sql, $params);

$idx_start = ($page * $per_page) + 1;
$idx_end = ($idx_start + $per_page) - 1;
$idx = $start_row + 1;
$route_params = get_params_isNotEmpty(['r' => $r]);
?>
<div class="row align-items-center">
  <div class="col-auto">
    <?php echo entries_row_query($per_page, $route_params) ?>
  </div>
  <div class="col-md-4">
    <div class="d-flex my-1">
      <input type="text" value="<?php echo str_ireplace('-', '', $name) ?>" class="form-control" id="findByName" placeholder="ชื่อห้องประชุม">
      <button class="btn btn-light ml-1" id="findByNameBtn">
        <i class="fa-solid fa-magnifying-glass"></i>
      </button>
    </div>
  </div>
  <div class="col-auto">
    <a href="./index.php?r=room" class="btn btn-sm btn-light">ค่าเริ่มต้น</a>
  </div>
</div>

<?php
foreach ($row as $r) {
  $idx++;
?>
  <div class="card shadow-sm">
    <div class="card-body">
      <div class="row my-2">
        <div class="col-md-2">
          <img src="./assets/images/meetingroomcover_image/<?php echo $r['cover_image'] ?>" style="width: 100%; object-fit: contain" />
        </div>
        <div class="col-md-10">
          <p class="m-0"><?php echo $r['meeting_room_name'] ?></p>
          <p class="m-0">
            <span>อาคาร</span>
            <span class="text-muted mx-1"><?php echo $r['building_name'] ?></span>

            <span>ชั้น</span>
            <span class="text-muted ml-1"><?php echo $r['meeting_room_floor'] ?></span>

          </p>
          <a href="?r=room_detail&id=<?php echo $r['meeting_room_id'] ?>" class="text-teal">
            <strong>ข้อมูล</strong>
          </a>
        </div>
      </div>
    </div>
  </div>
<?php  } ?>


<?php

$_p = setPaginateQuery($route_params, $idx, $idx_end, $per_page);
echo create_pagination($page, $page_all, $_p[0], $row_count, $idx_start, $_p[1]);
?>
<script src="./assets/js/findData.js"></script>