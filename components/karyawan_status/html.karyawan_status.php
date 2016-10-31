<?php

function list_karyawan_status(){
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
		'Status karyawan' => array( 'width'=>'85%','style'=>'text-align:left;' ), 
		'Aksi' => array( 'width'=>'10%','style'=>'text-align:center;' ), 
		
	);

	
	
	$query 	= "SELECT * FROM karyawan_status ";
	$result = my_query($query);
	
	//PAGING CONTROL START
	$total_records = my_num_rows($result );
	$scroll_page =  SCROLL_PERHALAMAN;  
	$per_page =  PAGING_PERHALAMAN;  
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
				'href'=>'index.php?com='.$_GET['com'].'&task=edit&id=' . $ey['karyawan_status_id'] , 
				'title'=>'Edit'
		);	
		$edit_button = button_icon( 'b_edit.png' , $editproperty  );

		$deleteproperty = array(
			'href'=>'javascript:confirmDelete('.$ey['karyawan_status_id'].');',
			'title'=>'Delete', 
		);
		$delete_button = button_icon( 'b_drop.png' , $deleteproperty  );

		$row[] = array( 
		'#' =>position_text_align( $i,   'center'),
		'Status karyawan' => $ey['karyawan_status_label'],  
		'op'=> position_text_align( $edit_button  .$delete_button , 'center')
		);
	}
	
	$datas = table_rows($row);
	$navigasi = array(
		'<input class="btn btn-primary" style="float:right;" type="button" value="Tambah data" onclick="javascript:location.href=\'index.php?com='.$_GET['com'].'&task=edit\'"/>',
	); 
	$box = header_box( '&nbsp;' , $navigasi );
	$paging = $kgPagerOBJ ->showPaging();
	return $box.table_builder($headers , $datas ,  4 , false , $paging  ); 
}


function submit_karyawan_status($id){
	 
	$datas = array();  
	$datas['karyawan_status_label']	=  my_type_data_str($_POST['karyawan_status_label']);
	$datas['created_on']			=  my_type_data_function('NOW()');
	$datas['user_updated_id']		=  my_type_data_str($_SESSION['user_id']);
	 
	if($id > 0){
		$datas['updated_on']			=  my_type_data_function('NOW()');
		$datas['version']				=  my_type_data_function('( version + 1 )' );
		return my_update_record( 'karyawan_status' , 'karyawan_status_id' , $id , $datas );
	}
	$datas['version']				=  my_type_data_int(0);
	return my_insert_record( 'karyawan_status' , $datas );
}

function form_karyawan_status_validate(){
	$errsubmit = false;
	$err = array();
	$karyawan_status_label = trim($_POST['karyawan_status_label']);
	$status_exists = karyawan_status_is_unique($karyawan_status_label);
	 
	if($karyawan_status_label == ''){
		$errsubmit =true;
		$err[] = "Status karyawan belum di isi";
	} 
	elseif(! $status_exists ){
		$errsubmit =true;
		$err[] = "Status karyawan sudah digunakan";
	}
	
	if( $errsubmit){
		return $err;
	}
	
	return false;
}
	
function karyawan_status_is_unique($code){
	$id = isset($_GET['id']) ?  (int) $_GET['id'] : 0;
	if($id > 0 ) return true;
	$code = strtoupper(trim($code));
	$query = "SELECT * FROM karyawan_status  WHERE UPPER(karyawan_status_label) = '{$code}' ";
 
	$result = my_query($query);
	$row_count = my_num_rows($result);
	 
	if($row_count > 0){
		return false;
	}
	return true;
}
	
	
function edit_karyawan_status($id){
	
	my_set_file_js(
		array(
			'assets/jquery/combomulti/jquery.chainedSelects.js',
			'assets/js/calendar/calendarDateInput.js' 
		)
	);
	$view = form_header( "form_karyawan_status" , "form_karyawan_status"  );
	$fields = my_get_data_by_id('karyawan_status','karyawan_status_id', $id);

 
	$karyawan_status_label = array(
			'name'=>'karyawan_status_label',
			'value'=>(isset($_POST['karyawan_status_label'])? $_POST['karyawan_status_label'] : $fields['karyawan_status_label']),
			'id'=>'karyawan_status_label',
			'type'=>'textfield' 
		);
	$form_karyawan_status_label = form_dynamic($karyawan_status_label);
	$view .= form_field_display( $form_karyawan_status_label  , "Status karyawan"  );
	 
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
	
	$view .= form_field_display( $form_submit . ' '.$form_cancel, "&nbsp;" );
	$view .= form_footer( );	 
	return  $view;
} 
?>