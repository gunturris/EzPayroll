<?php 
my_component_load('__paging' , false);
my_component_load('jpgraph' , false);
my_component_load('graph');
$task = isset($_GET['task']) ? $_GET['task'] : ""; 
$kode_termin = isset($_GET['kode_termin']) ? $_GET['kode_termin'] : ""; 
$start = isset($_GET['start']) ? $_GET['start'] : date('Y-m-d' );
$end = isset($_GET['end']) ? $_GET['end'] : date('Y-m-d' );
$id = isset( $_GET['id'] ) ? $_GET['id']:  0;
 	
	if($task == "gender"){ 
		gender_porsion();
	}elseif($task == "bank_transfer"){ 
		bank_transfer();
	}elseif($task == "bar_kehadiran_karyawan"){
		kehadiran_karyawan();
	}elseif($task == "bar_daily"){ 
		gambar_kotak();
	}elseif($task == "odo"){
		odo_last_periode();
	}else{
		$pagename = "Daftar karyawan";
		load_facebox_script();
		$content = '';
	} 
?>