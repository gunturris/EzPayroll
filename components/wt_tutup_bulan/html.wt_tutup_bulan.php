<?php 

function tutup_bulan_waktu_kerja(){

	$p = new ProgressBar();
	echo '<center><div style="width: 400px;">';
	$p->render();
	echo '</div></center>'; 
	logged_kehadiran($wt_periode_id);
	$p->setProgressBarProgress( rand(10,20) ); 
	
	logged_lembur($wt_periode_id);
	$p->setProgressBarProgress( rand(21,40) ); 
	
	truncate_temp_lembur_kehadiran();
	$p->setProgressBarProgress( rand(41,85) ); 
	 
	
	set_new_periode();
	$p->setProgressBarProgress(100);
	
	return $i;
	
}

function logged_kehadiran( $wt_periode_id ){
	$query = "
	INSERT INTO log_wt_kehadiran_karyawan(
		wt_periode_id , tanggal_caat , karyawan_id , 
		karyawan_nik , karyawan_nama , karyawan_status ,
		karyawan_gol_jab , kode_status_kehadiran , status_hadir , 
		jam_datang , jam_pulang ,created_on ,user_updated_id )
	SELECT
		{$wt_periode_id} , a.date_implementation , a.karyawan_id, 
		b.karyawan_nik , b.nama_karyawan , c.karyawan_status_label , 
		d.karyawan_gol_jab_label , a.kode_presensi  , a.status_hadir ,
		a.finger_in , a.finger_out  , NOW() , {$_SESSION['user_id']}
	FROM wt_temp_kehadiran a
	INNER JOIN karyawan b 
		ON a.karyawan_id = b.karyawan_id 
	INNER JOIN karyawan_status c 
		ON c.karyawan_status_id = b.karyawan_status_id
	INNER JOIN karyawan_gol_jab d 
		ON b.karyawan_gol_jab_id = d.karyawan_gol_jab_id 
		";
	my_query($query);
}

function logged_lembur( $wt_periode_id ){
	$query = "
	INSERT INTO log_wt_overtime_karyawan(
		wt_periode_id , karyawan_id , 
		karyawan_nik , karyawan_nama , karyawan_status ,
		karyawan_gol_jab , jam_lembur , jam_hitung ,
		created_on ,user_updated_id )
	SELECT
		{$wt_periode_id} , a.karyawan_id, 
		b.karyawan_nik , b.nama_karyawan , c.karyawan_status_label , 
		d.karyawan_gol_jab_label , a.jam_lembur , a.jam_hitung ,
		NOW() , {$_SESSION['user_id']} 
	FROM wt_temp_lembur a
	INNER JOIN karyawan b 
		ON a.karyawan_id = b.karyawan_id 
	INNER JOIN karyawan_status c 
		ON c.karyawan_status_id = b.karyawan_status_id
	INNER JOIN karyawan_gol_jab d 
		ON b.karyawan_gol_jab_id = d.karyawan_gol_jab_id";
	my_query($query);
}

function truncate_temp_lembur_kehadiran(){
$query = "TRUNCATE wt_temp_lembur";
my_query($query);
$query = "TRUNCATE wt_temp_kehadiran";
my_query($query); 
}


function set_new_periode(){
	//CHECK LAST DATE
	$query = "select id, date_end FROM wt_periode WHERE status_aktif = '1' ";
	$result = my_query($query);
	$row =my_fetch_array($result);
	$current_periode_id = $row['id'];
	$curent_end_date = $row['date_end'];
}
