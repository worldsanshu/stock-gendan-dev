<?php
//抓歌手列表
include('init.php');
$json = array();
$limit = 100;
if (isset($_GET["s"])) {
    $s = $_GET["s"];
    $where = ' ';
    switch ($s) {
        case 'search';
            $k = $_GET["k"];
            $where = "WHERE `name` LIKE '%$k%' OR `company` LIKE '%$k%'  OR `email` LIKE '%$k%' 
             OR `phone` LIKE '%$k%'  OR `fax` LIKE '%$k%'  OR `position` LIKE '%$k%'  OR `wechat` LIKE '%$k%' 
              OR `attention` LIKE '%$k%'  OR `other` LIKE '%$k%' 
               OR `other` LIKE '%$k%' 
                OR `stage` LIKE '%$k%' 
                 OR `phone2` LIKE '%$k%' 
                  OR `organization` LIKE '%$k%' 
                   OR `website` LIKE '%$k%'   OR `address` LIKE '%$k%'   OR `nature` LIKE '%$k%'   OR `notes` LIKE '%$k%' ";
            break;
        case 0;
            $where = '';
        case 1;
            $where = "where `phone` <> '' ";
            break;
        case 2;
            $where = "where `wechat` <> '' ";
            break;
        case 3;
            $where = "where `name` <> '' ";
            break;
        case 4;
            $where = "where `stage` <> '' ";
            break;
        case 5;
            $where = "where `position` <> '' ";
            break;
        case 6;
            $where = "where `exed_status` IN (1,2,3) ";
            break;
    }
}
$sql = "select * from  infolist  $where  limit $limit";
$artistList = $db->query($sql)->fetchAll(PDO::FETCH_CLASS);
if ($artistList) {
    $json["status"] = 200;
    $json["msg"] = "获取成功";
    $json["list"] = $artistList;
} else {
    $json["status"] = 500;
    $json["msg"] = "获取失败";
    $json["list"] = '';
}
echo json_encode($json);