<?php
/*
	Files generated by Kornea auto 
	On : 2015-03-29 09:33:31
*/	
my_component_load('__jsload' , false);
my_component_load('__paging' , false);  
my_component_load('progress_bar' ,false );
my_component_load('pay_proses_tutup_bulan' );
$task = isset($_GET['task']) ? $_GET['task'] : ''; 
$karyawan_id = isset( $_GET['karyawan_id'] ) ? $_GET['karyawan_id']:  0;
$id = isset( $_GET['id'] ) ? $_GET['id']:  0;
$modulname = 'Proses gaji <i class="fa fa-caret-right fa-fw"></i> 
		Reguler <i class="fa fa-caret-right fa-fw"></i> Tutup bulan';
 
if($task == "tutup_bulan"){
	$content = proses_tutup_bulan(); 
	echo '<center><h2>Selesai</h2>';
	echo '<a href="index.php">Kembali ke aplikasi</a></center>';
	exit;
}else{ 
	$content =  informasi_akhir_proses_reguler();
}

generate_my_web($content, $modulname );		
?>