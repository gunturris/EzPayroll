<?php 

function call_data_ref( $ref_id , $ref_type = 'person'   ){ 
	$datas = array();
	if($ref_type == 'group'){
		$query = "SELECT * FROM karyawan_groups WHERE id = {$ref_id} ";
		$result = my_query($query);
		$row = my_fetch_array($result);
		$datas['id'] = $row['id'];
		$datas['label'] = $row['groups_name'];
		return $datas;
	}else{
		$query = "SELECT * FROM karyawan  WHERE karyawan_id = {$ref_id} ";
		$result = my_query($query);
		$row = my_fetch_array($result);
		$datas['id'] = $row['karyawan_id'];
		$datas['label'] = $row['nik'].'/ '. $row['nama'];
		return $datas;
	}
	
}
function list_approval_patern(){
	
	my_set_code_js('
		function confirmDelete(id){
			var t = confirm(\'Yakin akan menghapus data ?\');
			if(t){
				location.href=\'index.php?com='.$_GET['com'].'&task=delete&id=\'+id;
			}
			return false;
		}
	');	
	$headers= array(  
		'Definisi Setuju' => array( 'width'=>'15%','style'=>'text-align:left;' ),   
		'Group' => array( 'width'=>'25%','style'=>'text-align:left;' ),  
		'Setuju I' => array( 'width'=>'25%','style'=>'text-align:left;' ),  
		'Setuju II' => array( 'width'=>'25%','style'=>'text-align:left;' ),  
		'Operasi' => array( 'width'=>'5%','style'=>'text-align:center;' ), 
	);
 
	$query 	= "SELECT a.id ,
				b.name AS approval_definition , 
				a.group_id , 
				a.app_first_type , a.app_second_type,
				a.first_ref_id , a.second_ref_id 
			FROM approval_patern a 
			INNER JOIN approval_definition b ON a.approval_id = b.id  ";
	$result = my_query($query);
	
	//PAGING CONTROL START
	$total_records = my_num_rows($result );
	$scroll_page = SCROLL_PERHALAMAN;  
	$per_page = PAGING_PERHALAMAN;  
	$current_page = isset($_GET['halaman']) ? (int) $_GET['halaman'] : 1 ; 
	if($current_page < 1){
		$current_page = 1;
	}		 
	$task = isset($_GET['task']) ?$_GET['task'] :'' ;
	$field = isset($_GET['field']) ?$_GET['field'] :'' ;
	$key = isset($_GET['key']) ?$_GET['key'] :'' ;
	$pager_url  ="index.php?com={$_GET['com']}&task={$task}&field={$field}&key={$key}&halaman=";	 
	$pager_url_last='';
	$inactive_page_tag = 'style="padding:4px;background-color:#BBBBBB"';  
	$previous_page_text = ' Mundur '; 
	$next_page_text = ' Maju ';  
	$first_page_text = ' Awal '; 
	$last_page_text = ' Akhir ';
	
	$kgPagerOBJ = new kgPager();
	$kgPagerOBJ->pager_set(
		$pager_url, 
		$total_records, 
		$scroll_page, 
		$per_page, 
		$current_page, 
		$inactive_page_tag, 
		$previous_page_text, 
		$next_page_text, 
		$first_page_text, 
		$last_page_text ,
		$pager_url_last
		); 
	 		
	$result = my_query($query ." LIMIT ".$kgPagerOBJ->start.", ".$kgPagerOBJ->per_page);  
	$i = ($current_page  - 1 ) * $per_page ;
	//PAGING CONTROL END
	
	$row = array();
	 
	while($ey = my_fetch_array($result)){
		$i++;
		$editproperty = array(
			'href'=>'index.php?com='.$_GET['com'].'&task=edit&id='.$ey['id']  , 
			'title'=>'Edit'
		);	
		$edit_button = button_icon( 'b_edit.png' , $editproperty  );

		$deleteproperty = array(
			'href'=>'javascript:confirmDelete('.$ey['id'].');',
			'title'=>'Delete', 
		);
		$delete_button = button_icon( 'b_drop.png' , $deleteproperty  );
		
		$karyawan_groups = my_get_data_by_id('karyawan_groups' , 'id' , (int) $ey['group_id']);
		$app_first = call_data_ref( $ey['first_ref_id']  ,  $ey['app_first_type'] );
		$app_first_text = $app_first['label'] ;
		if( (int) $ey['second_ref_id']  > 0 ){ 
			$app_second = call_data_ref( $ey['second_ref_id']  ,  $ey['app_second_type'] );
			$app_second_text = $app_second['label'] ;
		}else 
			$app_second_text = 'Otomatis';
			
		$row[] = array(  
		'Nama' => $ey['approval_definition'],    
		'group' => $karyawan_groups['groups_name'],   
		'first_ref' => $app_first_text,   
		'second_ref' => $app_second_text,   
		'op'=> position_text_align(  $edit_button  .$delete_button , 'right')
		);
	}
	
	$datas = table_rows($row);
	$navigasi = array(
		'<input class="btn btn-primary" style="float:right;" type="button" value="Tambah data" onclick="javascript:location.href=\'index.php?com='.$_GET['com'].'&task=edit\'"/>',
	 
	); 
	
	$box = header_box( ' ' , $navigasi );
	$paging = $kgPagerOBJ ->showPaging();
	return $box.table_builder($headers , $datas ,  5 , false , $paging  ); 
}

function generate_form_approval_list($ref = 'person'){
	if($ref == 'person'){ 
		$query 	= "SELECT * FROM karyawan ";
		$result = my_query($query);
		$datas = array();
		while( $row = my_fetch_array($result) ){
			$datas[$row['karyawan_id']] = $row['nik'].'/'.$row['nama'];
		}
	}
}


function edit_approval_definition($id){
	
	my_set_code_js('
	function app_second_type_choose(type){ 
		$(\'#form_app_second\').html(\'\');
		if(type == \'person\'){ 
			$.ajax({
				url:\'/json_karyawan_list.php\',
				type:\'GET\',
				dataType: \'json\',
				success: function( json ) { 
					var listitems = \'\';
					var i = 0; 
					$.each(json, function(key, value){  
						$.each(json[key], function(r, val){ 
							listitems += \'<option value=\' + value[i].id + \'>\' + value[i].nik +\'/ \' +  value[i].nama + \'</option>\';
							 
							i++;
						});
					}); 
					
					$(\'#form_app_second\').append(listitems);
				}
			});
		}else if(type == \'group\'){ 
			$.ajax({
				url:\'/json_group_list.php\',
				type:\'GET\',
				dataType: \'json\',
				success: function( json ) { 
					var listitems = \'\';
					var i = 0;
					$.each(json, function(key, value){  
						$.each(json[key], function(r, val){ 
							listitems += \'<option value=\' + value[i].id + \'>\' + value[i].nama + \'</option>\';
							 
							i++;
						});
					}); 
					
					$(\'#form_app_second\').append(listitems);
				}
			});
		}else{
				$(\'#form_app_second\').append(\'<option>-- otomatis --</option>\');
		}
		
	}
	
	function app_first_type_choose(type){ 
		$(\'#form_app_first\').html(\'\');
		if(type == \'person\'){ 
			$.ajax({
				url:\'/json_karyawan_list.php\',
				type:\'GET\',
				dataType: \'json\',
				success: function( json ) { 
					var listitems = \'\';
					var i = 0; 
					$.each(json, function(key, value){  
						$.each(json[key], function(r, val){ 
							listitems += \'<option value=\' + value[i].id + \'>\' + value[i].nik +\'/ \' +  value[i].nama + \'</option>\';
							 
							i++;
						});
					}); 
					
					$(\'#form_app_first\').append(listitems);
				}
			});
		}else{ 
			$.ajax({
				url:\'/json_group_list.php\',
				type:\'GET\',
				dataType: \'json\',
				success: function( json ) { 
					var listitems = \'\';
					var i = 0;
					$.each(json, function(key, value){  
						$.each(json[key], function(r, val){ 
							listitems += \'<option value=\' + value[i].id + \'>\' + value[i].nama + \'</option>\';
							 
							i++;
						});
					}); 
					
					$(\'#form_app_first\').append(listitems);
				}
			});
		}
		
	}
	');
	my_set_file_js(
		array(
			'assets/jquery/combomulti/jquery.chainedSelects.js',
			'assets/js/calendar/calendarDateInput.js' 
		)
	);
	$view = form_header( "form_karyawan" , "form_karyawan"  );
	$fields = my_get_data_by_id('approval_patern','id', $id);  
	
	$query_approval_definition = "SELECT * FROM approval_definition";
	$result_approval_definition = my_query($query_approval_definition);
	$approval_ids = array();
	while( $row_approval_definition = my_fetch_array($result_approval_definition) ){
		
		$approval_ids[$row_approval_definition['id']] = $row_approval_definition['name'];
	}
	
	$approval_id = array(
			'name'=>'approval_id',
			'value'=>(isset($_POST['approval_id'])? $_POST['approval_id'] : $fields['approval_id']),
			'id'=>'approval_id', 
		);
	$form_approval_id = form_dropdown($approval_id ,$approval_ids );
	$view .= form_field_display( $form_approval_id  , "Definisi persetujuan"  );
	
	
	
	
	$query_karyawan_groups = "SELECT * FROM karyawan_status";
	$result_karyawan_groups = my_query($query_karyawan_groups);
	$groups_ids = array();
	while( $row_karyawan_groups = my_fetch_array($result_karyawan_groups) ){
		
		$groups_ids[$row_karyawan_groups['karyawan_status_id']] = $row_karyawan_groups['karyawan_status_label'];
	}
	
	$groups_id = array(
			'name'=>'groups_id',
			'value'=>(isset($_POST['groups_id'])? $_POST['groups_id'] : $fields['groups_id']),
			'id'=>'groups_id', 
		);
	$form_groups_id = form_dropdown($groups_id ,$groups_ids );
	$view .= form_field_display( $form_groups_id  , "Kelompok kerja"  );
	
	 
	$opsi_app_first = array('person'=>'Perorangan' , 'group'=>'Kelompok');
	$app_first_type = array(
				'id'	=>'app_first_type',
				'onclick'	=>'javascript:app_first_type_choose(this.value)',
				'name'	=>'app_first_type',
				'value'	=> (isset($_POST['app_first_type'])? $_POST['app_first_type'] : $fields['app_first_type']),
			);
	$form_app_first_type  = form_radiobutton( $app_first_type , $opsi_app_first );
	$view .= form_field_display( $form_app_first_type  , "Persetujuan tingkat satu"  ); 
	$view .= form_field_display( '<select  onkeypress="return handleEnter(this, event)" class="form-control" name="first_ref_id" id="form_app_first"><option value="0">- pilih tipe -</option></select>'  , "Disetujui oleh"  ); 
	
	$opsi_app_second = array('person'=>'Perorangan' , 'group'=>'Kelompok' , 'otomatis' => 'Otomatis');
	$app_second_type = array(
				'id'	=>'app_second_type',
				'onclick'	=>'javascript:app_second_type_choose(this.value)',
				'name'	=>'app_second_type',
				'value'	=> (isset($_POST['app_second_type'])? $_POST['app_second_type'] : $fields['app_second_type']),
			);
	$form_app_second_type  = form_radiobutton( $app_second_type , $opsi_app_second );
	$view .= form_field_display( $form_app_second_type  , "Persetujuan tingkat dua"  ); 
	$view .= form_field_display( '<select onkeypress="return handleEnter(this, event)" class="form-control" name="second_ref_id" id="form_app_second"><option value="0">- pilih tipe -</option></select>'  , "Disetujui oleh"  ); 
	
	
	$submit = array(
		'value' => ( $id ==0 ? ' Simpan ' :'  Update  '),
		'name' => 'simpan', 
		'type'=>'submit','class'=>'main_button'
	);
	$form_submit= form_dynamic($submit);
	
	 
	$view .= form_field_display( $form_submit.' '.$form_cancel, "&nbsp;" ,  "" );
	$view .= form_footer( );	 
	return  $view;
	
}

function approval_patern_validate($id){
	
	return false;
}


function approval_patern_submit($id){
	$datas = array();
	$datas['approval_id'] 	= my_type_data_int($_POST['approval_id']); 
	$datas['company_id'] 	= my_type_data_int($_POST['company_id']); 
	$datas['group_id'] 		= my_type_data_int($_POST['group_id']); 
	$datas['app_first_type']	= my_type_data_str($_POST['app_first_type']); 
	$datas['first_ref_id'] 		= my_type_data_int($_POST['first_ref_id']); 
	$datas['app_second_type'] 		= my_type_data_str($_POST['app_second_type']); 
	$datas['second_ref_id'] 		= my_type_data_int($_POST['second_ref_id']); 
	if($id == 0){
		$datas['created_on']	= my_type_data_function('NOW()');
		return my_insert_record('approval_patern' , $datas);
	}
	$datas['updated_on']	= my_type_data_function('NOW()');
	return my_insert_record('approval_patern' , $datas); 
}