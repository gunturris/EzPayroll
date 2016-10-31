<?php
/*
	Files manual by GTR 
	On : 2015-03-22 19:52:03
*/	
my_component_load('__jsload' , false);
my_component_load('__paging' , false);  
my_component_load('master_konfigurasi' );
$task = isset($_GET['task']) ? $_GET['task'] : ''; 
$id = isset( $_GET['id'] ) ? $_GET['id']:  0;

$modulname = 'Konfigurasi <i class="fa fa-caret-right fa-fw"></i> Setup
	<i class="fa fa-caret-right fa-fw"></i> Profil Pajak Perusahaan' ;

if($_SERVER['REQUEST_METHOD'] == "POST" ){ 
	$validatepost = validasi_setel_wp();
	if($validatepost){
		$errors = message_multi_error($validatepost);
		$content = $errors;
	}else{
		save_update_konfigurasi($id);
		$content = message_correct("Data konfigurasi wajib pajak berhasil di revisi");
	} 
		$content .= config_master($id); 
}else{    
	$content =  config_master() ;  
}  
generate_my_web($content, $modulname );
?> 