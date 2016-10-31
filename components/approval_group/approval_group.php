<?php
my_component_load('__jsload' , false);
my_component_load('__paging' , false);  
my_component_load('approval_group' );
$task = isset($_GET['task']) ? $_GET['task'] : ''; 
$id = isset( $_GET['id'] ) ? $_GET['id']:  0;
$modulname = 'Setelan / Kelompok persetujuan';

if($_SERVER['REQUEST_METHOD'] == "POST" ):
 	switch($task){
		case   "edit" :
			$validatepost = approval_group_submit_validate($id);
			if($validatepost){
				$errors = message_multi_error($validatepost);
				$content = $errors;
				$content .= approval_group_form($id);;
				generate_my_web($content,$modulname);
				exit; 
			}else{
				approval_group_submit($id);
				$content =  "Updated";
				my_direct('index.php?com='.$_GET['com']);
			}
		break; 
	}
else: 	
	if($task == "edit"){ 
		$content =  approval_group_form($id) ;
	}else{
		load_facebox_script();
		$content =  list_approval_group() ; 
	}
endif; 
generate_my_web($content, $modulname );
 