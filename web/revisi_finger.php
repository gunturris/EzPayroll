<?php 
require_once('../config.php');
function get_jadwal_by_code($code){

	$query = "SELECT * FROM wt_jadwal_kerja WHERE jadwal_kode = '{$code}'";
	$result = my_query($query);
	$row = my_fetch_array($result);
	return $row;
}

function get_datas_match_mesin_finger($karyawan_id){
	$query = "SELECT * FROM mesin_finger_match WHERE karyawan_id = {$karyawan_id}";
	$result = my_query($query);
	if(my_num_rows($result) > 0){
		$row = my_fetch_array($result);
		return $row;
	}
	return false;
}

function check_data_manual_exists($datetime , $karyawan_id){
	$row = get_datas_match_mesin_finger($karyawan_id);
	$query2 = "SELECT * FROM log_data_mesin 
		WHERE mesin_id = {$row['mesin_id']} 
			AND finger_swap_id = {$row['finger_id']} 
			AND datetime_swap = '{$datetime}' 
			AND manual = '1'";
	$res = my_query($query2);
	if( my_num_rows($res)){
		return true;
	}
	return false;
}

function check_realisasi_karyawan($karyawan_id , $date, $status  ){
	$query 	= "SELECT * FROM wt_temp_kehadiran 
		WHERE karyawan_id = {$karyawan_id}
			AND date_implementation = '{$date}' ";
	$result = my_query($query);
	if( my_num_rows($result) > 0 ){
		$row = my_fetch_array($result);
		if($status == 'in'){
			if($row['manual_in'] == '1'){
				return false;
			}elseif($row['finger_in'] == '00:00:00'){
				return false;
			}
			return $row['finger_in'];
		}else
		if($status == 'out'){
			if($row['manual_out'] == '1'){
				return false;
			}elseif($row['finger_out'] == '00:00:00'){
				return false;
			}
			return $row['finger_out'];
		}
	}
	return false;
}

function check_time_exists_manual($karyawan_id , $date, $status){
	$query 	= "SELECT * FROM wt_temp_kehadiran 
		WHERE karyawan_id = {$karyawan_id}
			AND date_implementation = '{$date}' AND manual_{$status} = '1' ";
	$result = my_query($query);
	if( my_num_rows($result) > 0){
		return true;
	}
	return false;
}

if(isset($_SESSION['user_id'])){ 

	if(!check_realisasi_karyawan($_GET['karyawan_id'] , $_GET['date'], $_GET['status_check']  )){
	 
		$jadwal = get_jadwal_by_code( $_GET['jadwal'] );
		if($_GET['status_check'] == 'in' ){
			$check_in_manual = check_time_exists_manual( $_GET['karyawan_id'] , $_GET['date'] , 'in' );
			if($check_in_manual){ //KOSONGKAN TABLE
				$query = " 
					UPDATE wt_temp_kehadiran 
						SET finger_in = '00:00:00' , manual_in = '0'
							WHERE karyawan_id = {$_GET['karyawan_id']}
								AND date_implementation = '{$_GET['date']}'
				";
				my_query($query);
				$query_delete = "DELETE FROM log_data_mesin WHERE datetime_swap ='{$_GET['date']} {$jadwal['jadwal_in']}' AND manual = '1' ";
				my_query($query_delete);
				echo '00:00';
				exit;
			}else{
				$query = " 
					UPDATE wt_temp_kehadiran 
						SET finger_in = '{$jadwal['jadwal_in']}' , manual_in = '1'  
							WHERE karyawan_id = {$_GET['karyawan_id']}
								AND date_implementation = '{$_GET['date']}'
				";
				my_query($query);
				$match = get_datas_match_mesin_finger($_GET['karyawan_id']);
				$query_insert = "INSERT INTO log_data_mesin 
						SET datetime_swap ='{$_GET['date']} {$jadwal['jadwal_in']}',
							manual = '1' ,download_time = NOW() ,mesin_id = {$match['mesin_id']} ,
							finger_swap_id = {$match['finger_id']}";
				my_query($query_insert);

			echo date('H:i',strtotime($jadwal['jadwal_in']));
			exit;				
			}
		}elseif($_GET['status_check'] == 'out' ){
			$check_in_manual = check_time_exists_manual( $_GET['karyawan_id'] , $_GET['date'] , 'out' );
			if($check_in_manual){ //KOSONGKAN TABLE
				$query = " 
					UPDATE wt_temp_kehadiran 
						SET finger_out = '00:00:00' , manual_out = '0'  
							WHERE karyawan_id = {$_GET['karyawan_id']}
								AND date_implementation = '{$_GET['date']}'
				";
				my_query($query);
				$query_delete = "DELETE FROM log_data_mesin 
					WHERE datetime_swap ='{$_GET['date']} {$jadwal['jadwal_out']}' AND manual = '1' ";
				my_query($query_delete);
				echo '00:00';
				exit;
			}else{ 
				$query = " 
					UPDATE wt_temp_kehadiran 
						SET finger_out = '{$jadwal['jadwal_out']}' , manual_out = '1'  
							WHERE karyawan_id = {$_GET['karyawan_id']}
								AND date_implementation = '{$_GET['date']}'
				";
				my_query($query);
				$match = get_datas_match_mesin_finger($_GET['karyawan_id']);
				$query_insert = "INSERT INTO log_data_mesin 
						SET datetime_swap ='{$_GET['date']} {$jadwal['jadwal_out']}',
							manual = '1' ,download_time = NOW() ,mesin_id = {$match['mesin_id']} ,
							finger_swap_id = {$match['finger_id']}";
							 
				my_query($query_insert);
			}
			echo date('H:i',strtotime($jadwal['jadwal_out']));
			exit;
		}
	}
}
 
?>