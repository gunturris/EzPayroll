<?php
require_once('../config.php'); 
list($nik,$nama) = explode("/" ,$_GET['nama'] );
$nik = rtrim($nik,")");
$nik = trim($nik);
$query ="SELECT * FROM karyawan   ";
$query .= " WHERE nik = '{$nik}'";
$res = my_query($query);  
if( my_num_rows($res) > 0 ){
	echo '1';
}

echo '0';
?>