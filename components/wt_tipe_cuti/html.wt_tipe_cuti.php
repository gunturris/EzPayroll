<?php

function list_wt_tipe_cuti(){
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
		'Kode' => array( 'width'=>'5%','style'=>'text-align:center;' ), 
		'Jenis Cuti' => array( 'width'=>'75%','style'=>'text-align:left;' ), 
		'Kehadiran' => array( 'width'=>'10%','style'=>'text-align:center;' ), 
		'Aksi' => array( 'width'=>'10%','style'=>'text-align:center;' ), 
		
	);

	
	
	$query 	= "SELECT * FROM wt_tipe_cuti ";
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
				'href'=>'index.php?com='.$_GET['com'].'&task=edit&id=' . $ey['id'] , 
				'title'=>'Edit'
		);	
		$edit_button = button_icon( 'b_edit.png' , $editproperty  );

		$deleteproperty = array(
			'href'=>'javascript:confirmDelete('.$ey['id'].');',
			'title'=>'Delete', 
		);
		$delete_button = button_icon( 'b_drop.png' , $deleteproperty  );

		$status_hadir = ( $ey['status_hadir'] == '1' ) ? 'Hadir' : 'Tidak hadir';
		$row[] = array( 
		'Kode' => position_text_align( $ey['tipe_code'], 'center'),  
		'Jenis Cuti' => $ey['tipe_name'],  
		'Kehadiran' =>  position_text_align( $status_hadir  , 'center'), 
 				'op'=> position_text_align( $edit_button  .$delete_button , 'right')
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


function submit_wt_tipe_cuti($id){
	 
	$datas = array();  
	$datas['tipe_code']	=  my_type_data_str($_POST['tipe_code']);
	$datas['tipe_name']	=  my_type_data_str($_POST['tipe_name']);
	$datas['deskripsi']	=  my_type_data_str($_POST['deskripsi']);
	$datas['status_hadir']	=  my_type_data_str($_POST['status_hadir']);
	$datas['status_aktif']	=  my_type_data_str($_POST['status_aktif']);
		 
	if($id > 0){
		$datas['version']	=  my_type_data_function('( version + 1 )');
		$datas['updated_on']	=  my_type_data_function('NOW()');
		$datas['user_updated_id']	=  my_type_data_int($_SESSION['user_id']);
		return my_update_record( 'wt_tipe_cuti' , 'wt_tipe_cuti_id' , $id , $datas );
	}
	$datas['created_on']	= my_type_data_function('NOW()'); 
	$datas['version'] = my_type_data_str(0); 
	return my_insert_record( 'wt_tipe_cuti' , $datas );
}

function form_wt_tipe_cuti_validate(){
	return false;
}
	
	
function edit_wt_tipe_cuti($id){
	
	my_set_file_js(
		array(
			'assets/jquery/combomulti/jquery.chainedSelects.js',
			'assets/js/calendar/calendarDateInput.js' 
		)
	);
	$view = form_header( "form_wt_tipe_cuti" , "form_wt_tipe_cuti"  );
	$fields = my_get_data_by_id('wt_tipe_cuti','id', $id);

 
	
	$tipe_code = array(
			'name'=>'tipe_code',
			'value'=>(isset($_POST['tipe_code'])? $_POST['tipe_code'] : $fields['tipe_code']),
			'id'=>'tipe_code',
			'type'=>'textfield' ,
			'style'=>'max-width:55px' 
		);
	$form_tipe_code = form_dynamic($tipe_code);
	$view .= form_field_display( $form_tipe_code  , "Kode jenis cuti"  );
 
	
	$tipe_name = array(
			'name'=>'tipe_name',
			'value'=>(isset($_POST['tipe_name'])? $_POST['tipe_name'] : $fields['tipe_name']),
			'id'=>'tipe_name',
			'type'=>'textfield' 
		);
	$form_tipe_name = form_dynamic($tipe_name);
	$view .= form_field_display( $form_tipe_name  , "Nama jenis cuti"  );
	 
	
	$status_option = array(
		'0' => "Dihitung tidak hadir",
		'1' => "Dihitung hadir",
	);
	$status_hadir = array(
			'name'=>'status_hadir',
			'value'=>(isset($_POST['status_hadir'])? $_POST['status_hadir'] : $fields['status_hadir']) 
		);
	$form_status_hadir = form_radiobutton($status_hadir , $status_option);
	$view .= form_field_display( $form_status_hadir  , "Status hadir"  );
	 
	$deskripsi = array(
			'name'=>'deskripsi',
			'value'=>(isset($_POST['deskripsi'])? $_POST['deskripsi'] : $fields['deskripsi']),
			'id'=>'deskripsi',
			'cols'=>'35', 
			'rows'=>'4', 
		);
	$form_deskripsi = form_textarea($deskripsi);
	$view .= form_field_display( $form_deskripsi  , "Deskripsi"  );
	 
	$checked = $fields  ? ( $fields['hari_libur_diabaikan'] == '1' ? ' checked="checked" ': '' ) : '';  
	$form_status_aktif = '<br/><input type="checkbox" name="hari_libur_diabaikan" value="1" '.$checked.'/> Tetap dihitung sebagai cuti<br/><br/>';
	$view .= form_field_display( $form_status_aktif  , "Hari libur<br/>"  );
	
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
		'onclick'=>'javascript:location.href=\'index.php?com='.$_GET['com'].'\'',
		'class'=>'main_button'
	);
	$form_cancel= form_dynamic($cancel);
	
	
	$view .= form_field_display( $form_submit .' '.$form_cancel, "&nbsp;" );
	$view .= form_footer( );	
 
	return $view;
} 
?>