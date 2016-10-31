<?php 
my_component_load('__jsload' , false);
my_component_load('__paging' , false);    
my_component_load('unreg_karyawan_hasil_kalkulasi' );
$task = isset($_GET['task']) ? $_GET['task'] : ''; 
$karyawan_id = isset( $_GET['karyawan_id'] ) ? $_GET['karyawan_id']:  0;
$id = isset( $_GET['id'] ) ? $_GET['id']:  0;
$modulname = 'Proses gaji <i class="fa fa-caret-right fa-fw"></i> Unreguler
<i class="fa fa-caret-right fa-fw"></i> Hasil kalkulasi 
<i class="fa fa-caret-right fa-fw"></i> Detail hitung karyawan 
';
 	
if($task == "delete"){  
}else{  
	$content =  detail_kalkulasi($karyawan_id) ; 
}  
generate_my_web($content, $modulname );
?>