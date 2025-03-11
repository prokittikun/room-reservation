<?php
function entries_row_query($per_page, $path)
{
    if (isset($path['page'])) $path['page'] = 0;
    $r = concat_path($path);
    $option = '<div class="d-flex align-items-center">';
    $option .= '<label class="mr-1">แสดง</label>';
    $option .= '<select class="custom-select  my-1" id="pagination-query">';
    $option .= "<option value=''>เลือก</option>";
    $i = 5;
    while ($i <= 100) {
        $is_selected = $i == $per_page ? 'selected' : '';
        $v = "$r&per_page=$i";
        $option .= "<option value='$v' $is_selected >$i</option>";
        if ($i > 5) {
            $i += 10;
        } else {
            $i += 5;
        }
    }

    $option .= "</select>";
    $option .= '<label class="mx-2">รายการ</label>';
    $option .= '</div>';
    return $option;
}

function create_pagination($index, $count, $route, $row_all, $idx_start, $idx_end)
{


    $pagination = '<div class="row align-items-center">';
    $pagination .= '<div class="col-md-6 text-center text-md-left">';
    if ($row_all > 0) {
        $pagination .= '<span>รายการ</span>';
        $pagination .= '<strong class="font-weight-bold mx-1">' . $idx_start . '</strong>';
        $pagination .= '<span>ถึง</span>';
        $pagination .= '<strong class="font-weight-bold mx-1">' . $idx_end . '</strong>';
    }


    $pagination .= '<span>ทั้งหมด</span>';
    $pagination .= "<strong class='text-teal mx-1'>$row_all</strong>";
    $pagination .= '<span>รายการ</span>';
    $pagination .= '</div>';

    $start = 0;
    $end = 0;

    if ($count <= 5) {
        $end = 5;
        if ($end > $count) $end = $count;
    }

    if ($count > 5) {
        if ($index >= 2) {
            $end = $index + 3;
            $start =  $index - 2;
        }
        if ($index < 2) {
            $start = 0;
            $end =  5;
        }
        if ($index == $count - 1) {
            $start = $count - 5;
            $end = $index + 1;
        }
    }

    if ($index >= 3 && ($index == $count - 2)) {
        $start = $index - 3;
    }

    if ($end == $count + 1)  $end -= 1;

    if ($count > 0) {
        $pagination .= '<div class="col-md-6">';
        $pagination .=     '<nav class="m-0 d-flex align-items-center  justify-content-md-end justify-content-center  font-weight-bold">';
    }
    if ($row_all > 0) {
        $pagination .= '<p class="p-1 m-0 text-right">';
        $pagination .= '<span>หน้า</span>';
        $pagination .= '<strong class="text-muted mx-1">' . ($index + 1) . '</strong>';
        $pagination .= '<span>จาก</span>';
        $pagination .= "<strong class='text-muted mx-1'>$count</strong>";
        $pagination .= '</p>';
    }
    if ($count > 0) {
        $pagination .= '<ul class="pagination m-0">';
    }
    if ($index > 0 && $count > 0) {
        $pagination .= '<li class="page-item m-1 rounded">';
        $pagination .= "<a class='page-link' href='./$route&page=" . ($index - 1) . "'>";
        $pagination .= '<i class="fa-solid fa-angle-left"></i>';
        $pagination .= '</a>';
        $pagination .= '</li>';
    }

    $link = "";
    for ($i = $start; $i < $end; $i++) {
        if ($i <= $count) {
            $link .= '<li class="page-item m-1 rounded';
            $link .= $i == $index  ? ' active">' : '">';
            $link .= "<a class='page-link' href='./$route&page=" . ($i) . "'>";
            $link .= $i + 1;
            $link .= "</a></li>";
        }
    }
    $pagination .= $link;
    if ($index < $count - 1) {
        $pagination .=  '<li class="page-item m-1 rounded">';
        $pagination .=  "<a class='page-link' href='./$route&page=" . ($index + 1) . "'>";
        $pagination .=  '<i class="fa-solid fa-angle-right"></i>';
        $pagination .=  "</a></li>";
    }

    $pagination .=  '</ul>';

    $pagination .= '</nav>';
    $pagination .= '</div>';
    $pagination .= '</div>';



    return $pagination;
}
