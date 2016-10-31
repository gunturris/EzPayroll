<?php 
my_component_load('__jsload' , false);
my_component_load('__paging' , false);  
my_component_load('pay_formula' , false);  
my_component_load('karyawan_renumerasi' );
$task = isset($_GET['task']) ? $_GET['task'] : ''; 
$id = isset( $_GET['id'] ) ? $_GET['id']:  0;
$modulname = 'Remunerasi <i class="fa fa-caret-right fa-fw"></i> Dasar penghitungan ';
 	
if($task == "detail"){ 
	$content =  detail_renumerasi($id) ;
}elseif($task == "delete"){ 
	my_delete_record('karyawan','karyawan_id', $id);
	my_direct($_SERVER['HTTP_REFERER']);	
}else{  
	$content =  list_karyawan_renumerasi() ; 
}  
generate_my_web($content, $modulname );
?>