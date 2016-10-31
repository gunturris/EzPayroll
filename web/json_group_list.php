<?php 
header('Content-Type: application/json');

require_once('../config.php');

$query = "SELECT  id , groups_name  FROM karyawan_groups";
$result = my_query($query);
$datas = array();
while( $row = my_fetch_array($result) ){
	$datas['row'][]= array('id' => $row['id'] ,   'nama'=>$row['groups_name']);
}

echo json_encode($datas);