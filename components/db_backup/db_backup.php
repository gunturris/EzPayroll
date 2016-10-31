<?php
my_component_load('__jsload' , false); 
my_component_load('db_backup' );
$task = isset($_GET['task']) ? $_GET['task'] : ""; 
	$pagename = "Backup system";
if($task== 'compress'){
	if(!compress_file()){
		$view = "Download gagal";
	}
}elseif($task== 'compress_sql'){
	if(!compress_sql()){
		$view = "Download gagal";
	}
}else{
	$view = display_main_page();
}
generate_my_web($view, $pagename );