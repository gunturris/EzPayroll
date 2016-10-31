<?php
my_component_load('__jsload' , false);
my_component_load('__paging' , false);  
my_component_load('bank' );
$task = isset($_GET['task']) ? $_GET['task'] : ""; 
$id = isset( $_GET['id'] ) ? $_GET['id']:  0;
$modulname = ' Konfigurasi Data <i class="fa fa-caret-right fa-fw"></i>
 Data Referensi <i class="fa fa-caret-right fa-fw"></i> Bank';

if($_SERVER['REQUEST_METHOD'] == "POST" ):
	switch($task){
		case   "edit_bank" :
			$validatepost = valid_bank($id);
			if($validatepost){
				$errors = message_multi_error($validatepost);
				$content = $errors;
				$content .= form_bank($id); 
			}else{
				submit_bank($id);
				$content =  "Updated"; 
				my_direct('index.php?com='.$_GET['com']);
		     }
			break;
	}

else: 	
	if($task == "edit_bank"){ 
		$content  = form_bank($id); 
	}elseif($task == 'delete_bank'){ // DELETE ITEM	
		remove_bank($id);
		my_direct('index.php?com=bank');
	}else{  
		$content =  list_bank(); 
		
	}
endif;  
generate_my_web($content, $modulname );
?>