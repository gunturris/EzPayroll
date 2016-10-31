<?php

function template_text($module_name ,  $headers){
	$contents = '<?php' ."\n";
	$contents .= generator_function_list($module_name , $headers);
	$contents .= generator_function_edit($module_name , $headers);
	
	$contents .= "\n".'?>';
	return $contents;
}


function generator_function_edit($module_name, $headers ){
	$template = "
function edit_{$module_name}(\$id){
	
	my_set_file_js(
		array(
			'assets/jquery/combomulti/jquery.chainedSelects.js',
			'assets/js/calendar/calendarDateInput.js' 
		)
	);
	\$view = form_header( \"form_{$module_name}\" , \"form_{$module_name}\"  );
	\$fields = my_get_data_by_id('{$module_name}','{$module_name}_id', \$id);
";

$query = "SHOW fields FROM {$module_name}";
$result = my_query($query);
while($row = my_fetch_array($result)){
	
	if($row['Field'] =='version')continue;
	if($row['Field'] =='updated_by')continue;
	if($row['Field'] =='updated_on')continue;
	if($row['Field'] =='created_by')continue;
	if($row['Field'] =='datetime_added')continue;
	if($row['Field'] =='created_on')continue;
	if($row['Field'] =='user_updated_id')continue;
	
	if( preg_match("/date/i", $row['Type'] ) ){
		$template .= "\n".generate_form_calendar($row['Field']);	 
	
	}elseif(preg_match("/int/i", $row['Type'] )){
		$template .= "\n".generate_form_dropdown($row['Field']);
	
	}elseif(preg_match("/text/i", $row['Type'] )){
		$template .= "\n".generate_form_textarea($row['Field']);
	
	}else{
		$template .= "\n".generate_form_text($row['Field']);
	
	}
}
	$template .= "	 
	\$submit = array(
		'value' => ( \$id ==0 ? ' Simpan ' :'  Update  '),
		'name' => 'simpan', 
		'type'=>'submit','class'=>'main_button'
	);
	\$form_submit= form_dynamic(\$submit);
	
	\$cancel = array(
		'value' => (  ' Batal '  ),
		'name' => 'cancel', 
		'type'=>'reset',
		'onclick'=>'javascript:location.href=\''.\$_SERVER['HTTP_REFERER'].'\'',
		'class'=>'main_button'
	);
	\$form_cancel= form_dynamic(\$cancel);
	
	
	\$view .= form_field_display( \$form_submit .' '.\$form_cancel, \"&nbsp;\" );
	\$view .= form_footer( );	
 
	return \$view;
} ";
return $template;
}

function generator_function_list($module_name , $headers){
	$template = "
function list_{$module_name}(){
	my_set_code_js('
		function confirmDelete(id){
			var t = confirm(\'Yakin akan menghapus data ?\');
			if(t){
				location.href=\'index.php?com='.\$_GET['com'].'&task=delete&id=\'+id;
			}
			return false;
		}
	');	
	";
	
	$template .= "\$headers= array( 
		";
	foreach($headers as $key=>$dataheader){ 
		$template .= "'".$key."' => array( ".$dataheader." ), 
		";
	}
	$template .= "
	);\n";
	$template .= "
	
	
	\$query 	= \"SELECT * FROM {$module_name} \";
	\$result = my_query(\$query);
	
	//PAGING CONTROL START
	\$total_records = my_num_rows(\$result );
	\$scroll_page = SCROLL_PERHALAMAN;  
	\$per_page = PAGING_PERHALAMAN;  
	\$current_page = isset(\$_GET['halaman']) ? (int) \$_GET['halaman'] : 1 ; 
	if(\$current_page < 1){
		\$current_page = 1;
	}		 
	\$task = isset(\$_GET['task']) ?\$_GET['task'] :'' ;
	\$field = isset(\$_GET['field']) ?\$_GET['field'] :'' ;
	\$key = isset(\$_GET['key']) ?\$_GET['key'] :'' ;
	\$pager_url  =\"index.php?com={\$_GET['com']}&task={\$task}&field={\$field}&key={\$key}&halaman=\";	 
	\$pager_url_last='';
	\$pager_url_last='';
	\$inactive_page_tag = 'style=\"padding:4px;background-color:#BBBBBB\"';  
	\$previous_page_text = '<i class=\"fa fa-angle-left fa-fw\"></i>'; 
	\$next_page_text = '<i class=\"fa fa-angle-right fa-fw\"></i>';  
	\$first_page_text = '<i class=\"fa fa-angle-double-left fa-fw\"></i>'; 
	\$last_page_text = '<i class=\"fa fa-angle-double-right fa-fw\"></i>';
	
	\$kgPagerOBJ = new kgPager();
	\$kgPagerOBJ->pager_set(
		\$pager_url, 
		\$total_records, 
		\$scroll_page, 
		\$per_page, 
		\$current_page, 
		\$inactive_page_tag, 
		\$previous_page_text, 
		\$next_page_text, 
		\$first_page_text, 
		\$last_page_text ,
		\$pager_url_last
		); 
	 		
	\$result = my_query(\$query .\" LIMIT \".\$kgPagerOBJ->start.\", \".\$kgPagerOBJ->per_page);  
	\$i = (\$current_page  - 1 ) * \$per_page ;
	//PAGING CONTROL END
	
	\$row = array();
	while(\$ey = my_fetch_array(\$result)){
		\$i++;
		\$editproperty = array(
				'href'=>'index.php?com='.\$_GET['com'].'&task=edit&id=' . \$ey['{$module_name}_id'] , 
				'title'=>'Edit'
		);	
		\$edit_button = button_icon( 'b_edit.png' , \$editproperty  );

		\$deleteproperty = array(
			'href'=>'javascript:confirmDelete('.\$ey['{$module_name}_id'].');',
			'title'=>'Delete', 
		);
		\$delete_button = button_icon( 'b_drop.png' , \$deleteproperty  );

		\$row[] = array( 
		";
		 
	foreach($headers as $key=>$dataheader){ 
		$template .= "'".$key."' => \$ey['{$key}'],  
		";
	}			
$template .= "		'op'=> position_text_align( \$edit_button  .\$delete_button , 'right')
		);
	}
	
	\$datas = table_rows(\$row);
	\$navigasi = array(
		'<input class=\"btn btn-primary\" style=\"float:right;\"  type=\"button\" value=\"Tambah data\" onclick=\"javascript:location.href=\'index.php?com='.\$_GET['com'].'&task=edit\'\"/>',
		 
	);
	\$box = header_box( '&nbsp;' , \$navigasi );
	\$paging = \$kgPagerOBJ ->showPaging();
	return \$box.table_builder(\$headers , \$datas ,  4 , false , \$paging  ); 
}


function submit_{$module_name}(\$id){
	 
	\$datas = array();";
$query = "SHOW fields FROM {$module_name}";
$result = my_query($query);
while($row = my_fetch_array($result)){


	if($row['Field'] =='version'){
		$template .= " \$datas['version'] = my_type_data_str(0);
		";
		continue;
	}
	if($row['Field'] =='datetime_added'){
		$template .= "\$datas['datetime_added']	= my_type_data_function('NOW()');  
		";
		continue;
	}
	if($row['Field'] =='user_updated_id'){
		$template .= "\$datas['user_updated_id']	= my_type_data_int(\$_SESSION['user_id']);  
		";
		continue;
	}
	if($row['Field'] =='created_by'){
		$template .= "\$datas['created_by']	= my_type_data_str(\$_SESSION['user_id']);  
		";
		continue;
	}
	if($row['Field'] =='created_on'){
		$template .= " \$datas['created_on']	= my_type_data_function('NOW()');
		";
		continue;
	}
	
	$template .= " \$datas['{$row['Field']}']	=  my_type_data_str(\$_POST['{$row['Field']}']);
	";
		
}	
	$template .= " 
	if(\$id > 0){
		return my_update_record( '{$module_name}' , '{$module_name}_id' , \$id , \$datas );
	}
	return my_insert_record( '{$module_name}' , \$datas );
}

function form_{$module_name}_validate(){
	return false;
}
	
	";
	return $template;
}


function generate_form_calendar($name){
	$templates ="
	\$f{$name} = date('Y-m-d');
	if(\$fields){
		list(\$yyyy{$name} , \$mm{$name}, \$dd{$name} ) = explode(\"-\" ,\$fields['{$name}'] );
		\$f{$name} = \$dd{$name}.'-'.\$mm{$name}.'-'.\$yyyy{$name};
	}
	
	\${$name} = array(
			'name'=>'{$name}',
			'value'=>(isset(\$_POST['{$name}'])? \$_POST['{$name}'] : \$f{$name}),
			'id'=>'{$name}',
			'type'=>'textfield',
			'size'=>'45'
		);
	\$form_{$name} = form_calendar(\${$name});
	\$view .= form_field_display( \$form_{$name}  , \"".ucfirst($name)."\" );
	";
	return $templates;
}

function generate_form_dropdown($name){
	$templates ="
	\${$name}s =  array( );
	\$query = \"SELECT {$name} , {$name}_label FROM ".str_replace("_id","" ,$name)."\";	
	\$result = my_query(\$query);
	while(\$row_{$name} = my_fetch_array(\$result)){
		\${$name}s[\$row_{$name}['{$name}']] = \$row_{$name}['{$name}_label'];
	}
	\$level = array(
		'name'=>'{$name}',
		'value'=>( isset(\$_POST['{$name}']) ? \$_POST['{$name}'] : \$fields['{$name}']) ,
	);
	\$form_{$name} = form_radiobutton(\${$name} , \${$name}s);
	\$view .= form_field_display(  \$form_{$name}   , \"".ucfirst(str_replace(' id', '' ,str_replace ('_',' ',$name) ) )."\"    ); 
	";
	return $templates;
}

function generate_form_textarea($name){
	$template ="
	
	\${$name} = array(
			'name'=>'{$name}',
			'value'=>(isset(\$_POST['{$name}'])? \$_POST['{$name}'] : \$fields['{$name}']),
			'id'=>'{$name}',
			'cols'=>'35',
			'rows'=>'5'
		);
	\$form_{$name} = form_textarea(\${$name});
	\$view .= form_field_display( \$form_{$name}  , \"".ucfirst($name)."\"  );
	
	";
	return $template;
}

function generate_form_text($name){
	$template ="
	
	\${$name} = array(
			'name'=>'{$name}',
			'value'=>(isset(\$_POST['{$name}'])? \$_POST['{$name}'] : \$fields['{$name}']),
			'id'=>'{$name}',
			'type'=>'textfield' 
		);
	\$form_{$name} = form_dynamic(\${$name});
	\$view .= form_field_display( \$form_{$name}  , \"".ucfirst($name)."\"  );
	
	";
	return $template;
}

function content_text($module_name){
	$template ="<?php
/*
	Files generated by Kornea auto 
	On : ".date('Y-m-d H:i:s')."
*/	
my_component_load('__jsload' , false);
my_component_load('__paging' , false);  
my_component_load('{$module_name}' );
\$task = isset(\$_GET['task']) ? \$_GET['task'] : ''; 
\$id = isset( \$_GET['id'] ) ? \$_GET['id']:  0;
\$modulname = 'Admin _BN_ Konfigurasi Data _BN_ {$module_name}';

if(\$_SERVER['REQUEST_METHOD'] == \"POST\" ):
 	switch(\$task){
		case   \"edit\" :
			\$validatepost = form_{$module_name}_validate(\$id);
			if(\$validatepost){
				\$errors = message_multi_error(\$validatepost);
				\$content = \$errors;
				\$content .= edit_{$module_name}(\$id);
				generate_my_web(\$content,\"\",\"plain.php\");
				exit; 
			}else{
				submit_{$module_name}(\$id);
				\$content =  \"Updated\";
				my_direct('index.php?com='.\$_GET['com']);
			 }
			break; 
	}
else: 	
	if(\$task == \"edit\"){ 
		\$content =  edit_{$module_name}(\$id) ;
	}elseif(\$task == \"delete\"){ 
		my_delete_record('{$module_name}','{$module_name}_id', \$id);
		my_direct(\$_SERVER['HTTP_REFERER']);	
	}else{ 
		load_facebox_script();
		\$content =  list_{$module_name}() ; 
	}
endif; 
generate_my_web(\$content, \$modulname );
?>
";
return $template;
}

function generate_files($module_name,  $headers){
	$path = '/xampp/Kornea/kornea.2.0.1-EzPayroll/components/'.$module_name;
	if(file_exists($path)){
		return false;
	}
	mkdir($path , 0755 , true); 
	$html_content = template_text($module_name ,  $headers);
	$file_html = $path.'/html.'.$module_name.'.php';
	$handle = fopen($file_html, 'a+');
	fwrite($handle, $html_content);
	fclose($handle);
	
	$control_content = content_text($module_name); 
	$filename = $path.'/'.$module_name.'.php';
	$handle = fopen($filename, 'a+');
	fwrite($handle, $control_content);
	fclose($handle);
}