<?php
my_component_load('__jsload' , false);
my_component_load('__paging' , false);  
my_component_load('karyawan_gol_jab' );
$task = isset($_GET['task']) ? $_GET['task'] : ''; 
$id = isset( $_GET['id'] ) ? $_GET['id']:  0;
$modulname = 'Konfigurasi <i class="fa fa-caret-right fa-fw"></i> Data referensi <i class="fa fa-caret-right fa-fw"></i> Klasifikasi level jabatan';

if($_SERVER['REQUEST_METHOD'] == "POST" ):
 	switch($task){
		case   "edit" :
			$validatepost = form_karyawan_gol_jab_validate($id);
			if($validatepost){
				$errors = message_multi_error($validatepost);
				$content = $errors;
				$content .= edit_karyawan_gol_jab($id);  
			}else{
				submit_karyawan_gol_jab($id);
				$content =  "Updated";
				my_direct('index.php?com='.$_GET['com']);
			 }
			break; 
	}
else: 	
	if($task == "edit"){ 
		$content =  edit_karyawan_gol_jab($id) ;
	}elseif($task == "delete"){ 
		my_delete_record('karyawan_gol_jab','karyawan_gol_jab_id', $id);
		my_direct($_SERVER['HTTP_REFERER']);
	}else{ 
		load_facebox_script();
		$content =  list_karyawan_gol_jab() ; 
	}
endif; 
generate_my_web($content, $modulname );
?>
