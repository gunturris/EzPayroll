<?php
my_component_load('__jsload' , false);
my_component_load('__paging' , false);  
my_component_load('tax_ptkp_categori' );
$task = isset($_GET['task']) ? $_GET['task'] : ''; 
$id = isset( $_GET['id'] ) ? $_GET['id']:  0;
$modulname = 'Konfigurasi <i class="fa fa-caret-right fa-fw"></i> Data referensi <i class="fa fa-caret-right fa-fw"></i> Kategori PTKP';

if($_SERVER['REQUEST_METHOD'] == "POST" ):
 	switch($task){
		case   "edit" :
			$validatepost = form_tax_ptkp_categori_validate($id);
			if($validatepost){
				$errors = message_multi_error($validatepost);
				$content = $errors;
				$content .= edit_tax_ptkp_categori($id); 
			}else{
				submit_tax_ptkp_categori($id);
				$content =  "Updated";
				my_direct('index.php?com='.$_GET['com']);
			 }
			break; 
	}
else: 	
	if($task == "edit"){ 
		$content =  edit_tax_ptkp_categori($id) ;
	}elseif($task == "delete"){ 
		my_delete_record('tax_ptkp_categori','tax_ptkp_categori_id', $id);
		my_direct($_SERVER['HTTP_REFERER']);
	}else {
		load_facebox_script();
		$content =  list_tax_ptkp_categori() ; 
	}
endif; 
generate_my_web($content, $modulname );
?>
