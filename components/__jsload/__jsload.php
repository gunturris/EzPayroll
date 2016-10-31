<?php  
 
$task = isset($_GET['task']) ? $_GET['task'] : "";
my_component_load('testing');

  
if($task =="fbpg"){
	 
	 
 	
	facebox_page("testpage.php","ewtertyet" , 180 ); 
	
}elseif($task == "fb"){
	
	generate_facebox_template("Tralalal content","Title disini");
	
}else{


	load_facebox_script();
	$content = '<a href="index.php?com=testing&task=fb" rel="facebox">Test facebox load content</a>
	| <a href="index.php?com=testing&task=fbpg" rel="facebox">Test facebox load file</a>';
}
generate_my_web( $content  ,$titlename);