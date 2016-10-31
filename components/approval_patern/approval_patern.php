<?php
my_component_load('__jsload' , false);
my_component_load('__paging' , false);  
my_component_load('approval_patern' );
$task = isset($_GET['task']) ? $_GET['task'] : ''; 
$id = isset( $_GET['id'] ) ? $_GET['id']:  0;
$modulname = 'Persetujuan / Pola persetujuan';

if($_SERVER['REQUEST_METHOD'] == "POST" ):
 	switch($task){
		case   "edit" :
			$validatepost = approval_patern_validate($id);
			if($validatepost){
				$errors = message_multi_error($validatepost);
				$content = $errors;
				$content .= edit_approval_definition($id);;
				generate_my_web($content,$modulname);
				exit; 
			}else{
				approval_patern_submit($id);
				$content =  "Updated";
				my_direct('index.php?com='.$_GET['com']);
			}
		break; 
	}
else: 	
	if($task == "edit"){ 
		$content =  edit_approval_definition($id) ;
	}else{
		load_facebox_script();
		$content =  list_approval_patern() ; 
	}
endif; 
generate_my_web($content, $modulname );
