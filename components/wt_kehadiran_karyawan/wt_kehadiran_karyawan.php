<?php 
/*
	Files generated by Kornea auto 
	On : 2015-09-20 20:39:16
*/	
my_component_load('__jsload' , false);
my_component_load('__paging' , false);  
my_component_load('list_kalender' , false);   
my_component_load('wt_periode' , false);   
my_component_load('wt_kehadiran_karyawan' );

$task = isset($_GET['task']) ? $_GET['task'] : ''; 
$id = isset( $_GET['id'] ) ? $_GET['id']:  0;
$hari_id = isset( $_GET['hari_id'] ) ? $_GET['hari_id']:  0;
$modulname = 'Waktu kerja <i class="fa fa-caret-right fa-fw"></i>
 Penghitungan <i class="fa fa-caret-right fa-fw"></i> Data kehadiran karyawan';
 
if($_SERVER['REQUEST_METHOD'] == "POST" ){
 

}else{

	if($task == 'detail_hadir'){
		$content = detail_kehadiran($id);
	}else{
		$content = list_kehadiran_karyawan();
	}
}
generate_my_web($content, $modulname );