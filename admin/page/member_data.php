<?php
$start_dt = $_GET['start_dt'] ?? '';
$end_dt = $_GET['end_dt'] ?? '';
$status = $_GET['status'] ?? '';
$params = ['true'];
$paginate = paginate();
$page = $paginate['page'];
$per_page = $paginate['per_page'];

$sql = "SELECT * FROM member WHERE soft_delete !=?";

if (!empty($start_dt) && !empty($end_dt)) {
  $sql .= " AND (create_at BETWEEN ? AND ?) ";
  array_push($params, $start_dt);
  array_push($params, $end_dt);
}

if (!empty($status) && $status != 'all') {
  $sql .= " AND status =? ";
  array_push($params, $status);
}

$all = getDataCountAll($sql, 'member_id', $params, $page, $per_page);

$row_count = $all['row_count'];
$page_all = $all['page_all'];
$start_row = $all['start_row'];
$sql .= "ORDER BY create_at LIMIT ?,?";
array_push($params, $start_row);
array_push($params, $per_page);
$row = getDataAll($sql, $params);
$route_params = get_params_isNotEmpty(['r' => $r, 'page' => $page,]);

?>


<div class="row align-items-center">
  <div class="col-auto">
    <?php echo entries_row_query($per_page, $route_params) ?>
  </div>
  <!-- <div class="col-auto">
    <a class="btn btn-sm bg-gradient-lightblue" href="./?r=user_form">
      <i class="fa-solid fa-plus"></i>
      <span>เพิ่มผู้ใช้งาน</span>
    </a>
  </div> -->
</div>
<div class="card">
  <div class="card-body">
    <div class="m-0 table-responsive">
      <table class="table table-striped m-0">
        <thead>
          <tr>
            <th style="width: 5%;" scope="col">ลำดับ</th>
            <th style="width: 20%;" scope="col">ผู้ใช้งาน</th>
            <th style="width: 25%;" scope="col">ชื่อ - นามสกุล</th>
            <th style="width: 25%;" scope="col">นามสกุล</th>
            <th style="width: 20%;" scope="col"></th>
          </tr>
        </thead>
        <tbody>



          <?php
          $idx_start = ($page * $per_page) + 1;
          $idx_end = ($idx_start + $per_page) - 1;
          $idx = $start_row + 1;

          foreach ($row as $r) { ?>
            <tr>
              <td class="text-center"><?php echo $idx++ ?></td>
              <td><?php echo $r['username']    ?></td>
              <td><?php echo $r['fname']  ?></td>
              <td><?php echo  $r['lname']  ?></td>


              <td>
                <a class="btn btn-sm bg-gradient-lightblue" href="?r=user_form&method=put&id=<?php echo $r['member_id']   ?>">
                  <i class="fa-solid fa-pen"></i>
                </a>

                <button name="emp-remove" data-id="<?php echo $r['member_id']   ?>" class="btn btn-sm btn-light">
                  <i class="fa-solid fa-trash-can"></i>
                </button>
              </td>
            </tr>
          <?php     } ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<?php
$_p = setPaginateQuery($route_params, $idx, $idx_end, $per_page);
echo create_pagination($page, $page_all, $_p[0], $row_count, $idx_start, $_p[1]);
?>

<script src="./assets/js/user_data.js"></script>
<script >
  $('button[name="emp-remove"]').click(function () {
    const id = $(this).attr('data-id');
    confirmDialog('ลบข้อมูลสมาชิก', "ต้องการข้อมูลรายการนี้ใช่หรือไม่ ?")
        .then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    'url': employeeRoute(),
                    type: 'post',
                    data: {
                        'id': id,
                        'route': '/member/soft_delete'
                    },
                    complete: function (xhr, textStatus) {

                        if (xhr.status == 200) {
                            success('ลบข้อมูลเรียบร้อย')
                        } else {
                            errDialog('แจ้งเตือน', '', data.err)
                        }

                    }
                })
            }
        })
})
</script>
