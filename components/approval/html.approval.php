<?php 
function list_approval(){
	
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
		'No' => array( 'width'=>'5%','style'=>'text-align:center;' ), 
		'Definisi' => array( 'width'=>'55%','style'=>'text-align:left;' ),  
		'Persetujuan' => array( 'width'=>'15%','style'=>'text-align:left;' ),  
		'Eskalasi' => array( 'width'=>'15%','style'=>'text-align:left;' ),  
		'Operasi' => array( 'width'=>'10%','style'=>'text-align:center;' ), 
	);
 
	$query 	= "SELECT * FROM approval_definition ";
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
	$px = array( '1' => 'Ya' , '0'=>'Tidak' );
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
		 
		$row[] = array( 
		'No' => position_text_align($i,  'center'),
		'Nama' => $ey['name'],   
		'Assg' => $ey['assignation'],   
		'ESC' => $px[$ey['escalate']],   
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

function edit_approval_definition($id){
	
	
	my_set_file_js(
		array(
			'assets/jquery/combomulti/jquery.chainedSelects.js',
			'assets/js/calendar/calendarDateInput.js' 
		)
	);
	$view = form_header( "approval_definition" , "approval_definition"  );
	$fields = my_get_data_by_id('approval_definition','id', $id);
 

	
	$name = array(
			'name'=>'name',
			'value'=>(isset($_POST['name'])? $_POST['name'] : $fields['name']),
			'id'=>'name',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_label = form_dynamic($name);
	$view .= form_field_display( $form_label  , "Nama klasifikasi"  );
	
	$app_types = array( 'CUTI'=>'Cuti' , 'ABSEN'=>'Absen' , 'LEMBUR'=>'Lembur');
	$app_type  = array(
			'name'=>'app_type',
			'value'=>(isset($_POST['app_type'])? $_POST['app_type'] : $fields['app_type']),
			'id'=>'app_type', 
		);
	$form_assg = form_radiobutton($app_type , $app_types);
	$view .= form_field_display( $form_assg  , "Tipe pengajuan"  );
	
	$assignations = array( 'MAP User'=>'MAP User' , 'Korlap'=>'Koordinator lapangan' );
	$assignation = array(
			'name'=>'assignation',
			'value'=>(isset($_POST['assignation'])? $_POST['assignation'] : $fields['assignation']),
			'id'=>'assignation', 
		);
	$form_assg = form_radiobutton($assignation , $assignations);
	$view .= form_field_display( $form_assg  , "Persetujuan final"  );
	
	$escalate_value = array('1'=>'checked="checked"' , '0'=>'');
	
	
	$waiting_duration = array(
			'name'=>'waiting_duration',
			'value'=>(isset($_POST['waiting_duration'])? $_POST['waiting_duration'] : $fields['waiting_duration']),
			'id'=>'waiting_duration',
			'type'=>'number', 
			'style'=>'width:60%'
		);
	$form_batas = form_dynamic($waiting_duration);
	$view .= form_field_display( $form_batas  , "Waktu tunggu" .' (hari)'  );
	
	$form_eskalasi = '<input type="checkbox" name="escalate" value="1" '.
		(isset($_POST['escalate'])? ' checked="checked" ' : 
			( isset($fields['escalate']) ? $escalate_value[$fields['escalate']] : '' )
		).'/>';
	$view .= form_field_display( $form_eskalasi  , "Perlu eskalasi"  );
 
	
	
	$user_require = array('1'=>'checked="checked"' , '0'=>''); 
	$form_eskalasi = '<input type="checkbox" name="user_required" value="1" '.
		(isset($_POST['user_required'])? ' checked="checked" ' : 
			( isset($fields['user_required']) ? $user_require[$fields['user_required']] : '' )
		).'/>';
	$view .= form_field_display( $form_eskalasi  , "Wajib disetujui"  );
	
	$submit = array(
		'value' => ( $id ==0 ? ' Simpan ' :'  Update  '),
		'name' => 'simpan', 
		'type'=>'submit','class'=>'main_button'
	);
	$form_submit= form_dynamic($submit);
	
	$view .= form_field_display(  "&nbsp;" ,"&nbsp;" ,  "" );
	$view .= form_field_display( $form_submit, "&nbsp;" ,  "" );
	$view .= form_footer( );	
	$navigasi = array(
		 
	); 
	return  $view;
}

function form_approval_definition_validate($id){
	$errsubmit = false;
	$err = array();
	
	return $err;
}

function approval_definition_submit($id){
	
	$user_rec = isset( $_POST['user_required'] ) ? '1' : '0' ;
	$datas = array();  
	$datas['name']	=  my_type_data_str($_POST['name']);
	$datas['app_type']	=  my_type_data_str($_POST['app_type']);
	$datas['assignation']	=  my_type_data_str($_POST['assignation']);
	$datas['escalate']	=  my_type_data_int($_POST['escalate']);
	$datas['user_required']	=  my_type_data_int( $user_rec );
	$datas['waiting_duration']	=  my_type_data_str($_POST['waiting_duration']); 
	
	if($id > 0){
		return my_update_record( 'approval_definition' , 'id' , $id , $datas ); 
	}
	$id = rand(10000,99999);
	$datas['id']	=  my_type_data_int( $id );
	return my_insert_record( 'approval_definition' , $datas );
}