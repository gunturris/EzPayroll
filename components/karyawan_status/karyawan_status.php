<?php
my_component_load('__jsload' , false);
my_component_load('__paging' , false);  
my_component_load('karyawan_status' );
$task = isset($_GET['task']) ? $_GET['task'] : ''; 
$id = isset( $_GET['id'] ) ? $_GET['id']:  0;
$modulname = 'Konfigurasi <i class="fa fa-caret-right fa-fw"></i> Data Referensi <i class="fa fa-caret-right fa-fw"></i> Status karyawan  ';

if($_SERVER['REQUEST_METHOD'] == "POST" ):
 	switch($task){
		case   "edit" :
			$validatepost = form_karyawan_status_validate($id);
			if($validatepost){
				$errors = message_multi_error($validatepost);
				$content = $errors;
				$content .= edit_karyawan_status($id); 
			}else{
				submit_karyawan_status($id);
				$content =  "Updated";
				my_direct('index.php?com='.$_GET['com']);
			 }
			break; 
	}
else: 	
	if($task == "edit"){ 
		$content =  edit_karyawan_status($id) ;
	}elseif($task == "delete"){ 
		my_delete_record('karyawan_status','karyawan_status_id', $id);
		my_direct($_SERVER['HTTP_REFERER']);
	}else{ 
		load_facebox_script();
		$content =  list_karyawan_status() ; 
	}
endif; 
generate_my_web($content, $modulname );
?>
