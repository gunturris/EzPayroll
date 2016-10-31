<?php 
my_component_load('__jsload' , false);
my_component_load('__paging' , false);  
my_component_load('approval_eksepsi' );
$task = isset($_GET['task']) ? $_GET['task'] : ''; 
$id = isset( $_GET['id'] ) ? $_GET['id']:  0;
$modulname = 'Persetujuan / Pola persetujuan';

if($_SERVER['REQUEST_METHOD'] == "POST" ):
 	switch($task){
		case   "edit" :
			$validatepost = approval_exeption_validate($id);
			if($validatepost){
				$errors = message_multi_error($validatepost);
				$content = $errors;
				$content .= approval_eksepsi($id);;
				generate_my_web($content,$modulname);
				exit; 
			}else{
				approval_exeption_submit($id);
				$content =  "Updated";
				my_direct('index.php?com='.$_GET['com']);
			}
		break; 
	}
else: 	
	if($task == "edit"){ 
		$content =  approval_eksepsi($id) ;
	}else{
		load_facebox_script();
		$content =  list_approval_eksepsi() ; 
	}
endif; 
generate_my_web($content, $modulname );