<?php
require_once('../config.php');
$qq = isset($_GET['q']) ? $_GET['q'] : '' ; 
$query = " SELECT  nama_karyawan, karyawan_nik FROM  karyawan 	WHERE   CONCAT(karyawan_nik,nama_karyawan) LIKE '%{$qq}%' LIMIT 15  ";
 
$res = my_query($query); 
while( $rw=my_fetch_array($res) ){
echo strtoupper($rw['karyawan_nik']."/".$rw['nama_karyawan'])."\n";
}
exit;
?>