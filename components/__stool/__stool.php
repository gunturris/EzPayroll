<?php
 //ini_set('display_errors',1);
my_component_load('list_kalender',false);
require_once("../components/__stool/dummy_lembur.php");
my_component_load('__stool');  

if( $_SERVER['REQUEST_METHOD'] == "POST"){
	$i= upload_sisa_file_od();
	$content = '<pre>'.$i;
}else{  $content = 'No Inisiasi';
	  $content = data_diskon_dummy();//upload_form_page(); 
}

generate_my_web($content , 	'Update finger'); 