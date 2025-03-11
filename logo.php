<?php
$sql_logo = "SELECT * FROM logo ORDER BY update_at DESC";
$row_logo = getDataById($sql_logo, []);
$logo = 'จองห้องพัก';
$icon = '';
$title = 'จองห้องพัก';
$icon_dir = "";
$is_icon = false;
$is_logo =  false;
$logo_type = '';
if (count($row_logo)  > 0) {
    $logo_type = $row_logo['logo_type'];
    $logo = $row_logo['logo'];
    $icon = $row_logo['icon'];
    $title = $row_logo['title'];
    $icon_dir = "./assets/images/logo/$icon";

    $is_icon = file_exists($icon_dir);

    if ($logo_type == 'logoImage') {
        $logo_dir = "./assets/images/logo/$logo";
        $is_logo = file_exists($logo_dir);
    }
}
?>
<?php if ($is_icon) { ?>
    <link rel="icon" href="<?php echo $icon_dir ?>" sizes="16x16">
<?php   } ?>