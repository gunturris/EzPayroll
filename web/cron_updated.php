<?php 
ini_set("display_errors" , 0);
require_once("../autoload.php");
$last_periode  = get_last_periode();
$query = "
		SELECT 
			bank_nama , 
			SUM(nominal_transfer) AS nomtrans
		FROM log_payroll_reguler_bank_transfer 
		WHERE pay_periode_id = {$last_periode['pay_periode_reguler_id']}
		GROUP BY bank_id 
		ORDER BY nomtrans DESC
		";
$result = my_query($query);
$datas = array();
while($row = my_fetch_array($result)){
	$datas[$row['bank_nama']] = $row['nomtrans'];
}  
$path = '../files/services/bank_transfer.json';
if(file_exists($path))unlink($path);
 
if (!$handle = fopen(	$path, 'a'	)) {
	 return false;
} 
$res 	= json_encode($datas  );

if (fwrite($handle, $res) === FALSE) {
	return false;
}
fclose($handle);
 