<?php 
header('Content-Type: application/json');

require_once('../config.php');

$query = "SELECT karyawan_id , nik , nama FROM karyawan";
$result = my_query($query);
$datas = array();
while( $row = my_fetch_array($result) ){
	$datas['row'][]= array('id' => $row['karyawan_id'] , 'nik' => $row['nik'] , 'nama'=>$row['nama']);
}

echo json_encode($datas);