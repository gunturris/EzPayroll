<?php
my_component_load('__jsload' , false);
my_component_load('__paging' , false);
my_component_load('app-calendar' , false);
//my_component_load('jpgraph' , false); 
my_component_load('welcome');
$task = isset($_GET['task']) ? $_GET['task'] : ""; 
$id = isset( $_GET['id'] ) ? $_GET['id']:  0;

if($_SERVER['REQUEST_METHOD'] == "POST" ):
	
else: 	
	$pagename = 'eZ Payroll dashboard'; 
	$content =  page_block_design();
endif; 
generate_my_web($content, $pagename  );
?>