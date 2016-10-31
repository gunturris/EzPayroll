<?php

function list_karyawan_gol_jab(){
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
		'#' => array( 'width'=>'5%','style'=>'text-align:center;' ), 
		'Kode level' => array( 'width'=>'20%','style'=>'text-align:left;' ), 
		'Deskripsi' => array( 'width'=>'65%','style'=>'text-align:left;' ), 
		'Aksi' => array( 'width'=>'10%','style'=>'text-align:center;' ), 
	);

	
	
	$query 	= "SELECT * FROM karyawan_gol_jab ";
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
	$pager_url_last='';
	$inactive_page_tag = 'style="padding:4px;background-color:#BBBBBB"';  
	$previous_page_text = '<i class="fa fa-angle-left fa-fw"></i>'; 
	$next_page_text = '<i class="fa fa-angle-right fa-fw"></i>';  
	$first_page_text = '<i class="fa fa-angle-double-left fa-fw"></i>'; 
	$last_page_text = '<i class="fa fa-angle-double-right fa-fw"></i>';
	
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
				'href'=>'index.php?com='.$_GET['com'].'&task=edit&id=' . $ey['karyawan_gol_jab_id'] , 
				'title'=>'Edit'
		);	
		$edit_button = button_icon( 'b_edit.png' , $editproperty  );

		$deleteproperty = array(
			'href'=>'javascript:confirmDelete('.$ey['karyawan_gol_jab_id'].');',
			'title'=>'Delete', 
		);
		$delete_button = button_icon( 'b_drop.png' , $deleteproperty  );

		$row[] = array( 
		'#' => position_text_align( $i, 'center'),  
		'Kode Level' => $ey['karyawan_gol_jab_label'],  
		'Deskripsi' => $ey['deskripsi'],    
				'op'=> position_text_align( $edit_button  .$delete_button , 'center')
		);
	}
	
	$datas = table_rows($row);
	$navigasi = array(
		'<input class="btn btn-primary" style="float:right;"  type="button" value="Tambah data" onclick="javascript:location.href=\'index.php?com='.$_GET['com'].'&task=edit\'"/>',
		 
	);
	$box = header_box( '&nbsp;' , $navigasi );
	$paging = $kgPagerOBJ ->showPaging();
	return $box.table_builder($headers , $datas ,  4 , false , $paging  ); 
}


function submit_karyawan_gol_jab($id){
	 
	$datas = array();   
	 $datas['karyawan_gol_jab_label']	=  my_type_data_str($_POST['karyawan_gol_jab_label']);
	 $datas['deskripsi']	=  my_type_data_str($_POST['deskripsi']);
		$datas['user_updated_id']	= my_type_data_int($_SESSION['user_id']);  
		 
	if($id > 0){
		$datas['version'] = my_type_data_function('(version + 1)');
		 $datas['updated_on']	=  my_type_data_str($_POST['updated_on']);
	
		return my_update_record( 'karyawan_gol_jab' , 'karyawan_gol_jab_id' , $id , $datas );
	} 
	$datas['created_on']	= my_type_data_function('NOW()');
	$datas['version'] = my_type_data_str(0);
	return my_insert_record( 'karyawan_gol_jab' , $datas );
}

function form_karyawan_gol_jab_validate(){
	
	$errsubmit = false;
	$err = array();
	$karyawan_gol_jab_label = trim($_POST['karyawan_gol_jab_label']);
	if($karyawan_gol_jab_label == ''){
		$errsubmit =true;
		$err[] = "Kode klasifikasi level jabatan belum di isi";
	} 
	elseif(! goljab_code_is_unique($karyawan_gol_jab_label) ){
		$errsubmit =true;
		$err[] = "Kode klasifikasi level jabatan sudah digunakan";
	}
	
	if( $errsubmit){
		return $err;
	} 
	return false;
}
	
function goljab_code_is_unique($code){
	$id = isset($_GET['id']) ?  (int) $_GET['id'] : 0;
	if($id > 0 ) return true;
	$code = trim($code);
	$query = "SELECT * FROM karyawan_gol_jab  WHERE karyawan_gol_jab_label = '{$code}' ";
	$result = my_query($query);
	$row_count = my_num_rows($result);
	if($row_count > 0){
		return false;
	}
	return true;
}
	
function edit_karyawan_gol_jab($id){
	
	my_set_file_js(
		array(
			'assets/jquery/combomulti/jquery.chainedSelects.js',
			'assets/js/calendar/calendarDateInput.js' 
		)
	);
	$view = form_header( "form_karyawan_gol_jab" , "form_karyawan_gol_jab"  );
	$fields = my_get_data_by_id('karyawan_gol_jab','karyawan_gol_jab_id', $id);

 

	
	$karyawan_gol_jab_label = array(
			'name'=>'karyawan_gol_jab_label',
			'value'=>(isset($_POST['karyawan_gol_jab_label'])? $_POST['karyawan_gol_jab_label'] : $fields['karyawan_gol_jab_label']),
			'id'=>'karyawan_gol_jab_label',
			'type'=>'textfield' 
		);
	$form_karyawan_gol_jab_label = form_dynamic($karyawan_gol_jab_label);
	$view .= form_field_display( $form_karyawan_gol_jab_label  , "Kode level jabatan"  );
	
	

	
	$deskripsi = array(
			'name'=>'deskripsi',
			'value'=>(isset($_POST['deskripsi'])? $_POST['deskripsi'] : $fields['deskripsi']),
			'id'=>'deskripsi',
			'rows'=>'3' 
		);
	$form_deskripsi = form_textarea($deskripsi);
	$view .= form_field_display( $form_deskripsi  , "Deskripsi"  );
	
		 
	$submit = array(
		'value' => ( $id ==0 ? ' Simpan ' :'  Update  '),
		'name' => 'simpan', 
		'type'=>'submit','class'=>'main_button'
	);
	$form_submit= form_dynamic($submit);
	
	$cancel = array(
		'value' => (  ' Batal '  ),
		'name' => 'cancel', 
		'type'=>'reset',
		'onclick'=>'javascript:location.href=\''.$_SERVER['HTTP_REFERER'].'\'',
		'class'=>'main_button'
	);
	$form_cancel= form_dynamic($cancel);
	
	
	$view .= form_field_display( $form_submit . '  '.$form_cancel, "&nbsp;" );
	$view .= form_footer( );	
 
	return $view;
} 
?>