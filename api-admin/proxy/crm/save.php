<?php
//抓歌手列表
include('init.php');
$json = array();
$limit = 100;
if (isset($_GET["v"])) {
    $v = $_GET["v"];
    $id = $_GET["id"];
    $sql = "update infolist set notes='$v' where id=$id";
}
if (isset($_GET["s"])) {
    $s = $_GET["s"];
    $id = $_GET["id"];
    $sql = "update infolist set exed_status='$s' where id=$id";
}
if (isset($_GET["zt"])) {
    $s = $_GET["zt"];
    $id = $_GET["id"];
    $sql = "update infolist set status='$s' where id=$id";
}
if ($db->exec($sql)) {
    $json["status"] = 200;
    $json["msg"] = "更新成功";
} else {
    $json["status"] = 500;
    $json["msg"] = "更新失败";
    $json["list"] = '';
}
echo json_encode($json);