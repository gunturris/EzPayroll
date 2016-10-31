<?php

function list_pay_jurnal_gaji(){
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
		'Kode' => array( 'width'=>'10%','style'=>'text-align:left;' ), 
		'Nama jurnal' => array( 'width'=>'60%','style'=>'text-align:left;' ), 
		'D/K' => array( 'width'=>'15%','style'=>'text-align:left;' ), 
		'Aksi' => array( 'width'=>'10%','style'=>'text-align:center;' ),  
	);

	
	
	$query 	= "SELECT * FROM pay_jurnal_gaji ";
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
	$next_page_text = '<i class"fa fa-angle-right fa-fw"></i>';  
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
	$dk = array('d'=>'Debet' , 'k'=>'Kredit');
	$row = array();
	while($ey = my_fetch_array($result)){
		$i++;
		$editproperty = array(
				'href'=>'index.php?com='.$_GET['com'].'&task=edit&id=' . $ey['pay_jurnal_gaji_id'] , 
				'title'=>'Edit'
		);	
		$edit_button = button_icon( 'b_edit.png' , $editproperty  );

		$deleteproperty = array(
			'href'=>'javascript:confirmDelete('.$ey['pay_jurnal_gaji_id'].');',
			'title'=>'Delete', 
		);
		$delete_button = button_icon( 'b_drop.png' , $deleteproperty  );

		$row[] = array( 
		'#' =>position_text_align($i , 'center'),  
		'Kode' => $ey['pay_jurnal_gaji_code'],  
		'Nama jurnal' => $ey['pay_jurnal_gaji_label'],  
		'D/K' => $dk[$ey['debet_kredit']], 
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


function submit_pay_jurnal_gaji($id){
	 
	$datas = array(); 
	$datas['pay_jurnal_gaji_code']	=  my_type_data_str($_POST['pay_jurnal_gaji_code']);
	$datas['pay_jurnal_gaji_label']	=  my_type_data_str($_POST['pay_jurnal_gaji_label']);
	$datas['debet_kredit']	=  my_type_data_str($_POST['debet_kredit']);
	$datas['deskripsi']	=  my_type_data_str($_POST['deskripsi']);
	$datas['user_updated_id']	= my_type_data_int($_SESSION['user_id']);  
	 
	if($id > 0){
		$datas['updated_on']	=my_type_data_function('NOW()');
		$datas['version'] = my_type_data_function( '(version + 1 )');
		return my_update_record( 'pay_jurnal_gaji' , 'pay_jurnal_gaji_id' , $id , $datas );
	}
	$datas['created_on']	= my_type_data_function('NOW()');
	$datas['version'] = my_type_data_function( '0');
	return my_insert_record( 'pay_jurnal_gaji' , $datas );
}

function form_pay_jurnal_gaji_validate(){

	$errsubmit = false;
	$err = array();
	$pay_jurnal_gaji_code = trim($_POST['pay_jurnal_gaji_code']);
	if($pay_jurnal_gaji_code == ''){
		$errsubmit =true;
		$err[] = "Kode jurnal gaji belum di isi";
	} 
	elseif(! jurnal_gaji_code_is_unique($pay_jurnal_gaji_code) ){
		$errsubmit =true;
		$err[] = "Kode jurnal gaji sudah digunakan";
	}
	
	if(!isset($_POST['debet_kredit']) ){
		$errsubmit =true;
		$err[] = "Debet/ kredit belum di pilih";
	}
	if( $errsubmit){
		return $err;
	} 
	return false;
}
	

function jurnal_gaji_code_is_unique($code){
	$id = isset($_GET['id']) ?  (int) $_GET['id'] : 0;
	if($id > 0 ) return true;
	$code = trim($code);
	$query = "SELECT * FROM pay_jurnal_gaji  WHERE pay_jurnal_gaji_code = '{$code}' ";
	$result = my_query($query);
	$row_count = my_num_rows($result);
	if($row_count > 0){
		return false;
	}
	return true;
}
	
function edit_pay_jurnal_gaji($id){
	
	my_set_file_js(
		array(
			'assets/jquery/combomulti/jquery.chainedSelects.js',
			'assets/js/calendar/calendarDateInput.js' 
		)
	);
	$view = form_header( "form_pay_jurnal_gaji" , "form_pay_jurnal_gaji"  );
	$fields = my_get_data_by_id('pay_jurnal_gaji','pay_jurnal_gaji_id', $id);

  
	$pay_jurnal_gaji_code = array(
			'name'=>'pay_jurnal_gaji_code',
			'value'=>(isset($_POST['pay_jurnal_gaji_code'])? $_POST['pay_jurnal_gaji_code'] : $fields['pay_jurnal_gaji_code']),
			'id'=>'pay_jurnal_gaji_code',
			'type'=>'textfield' 
		);
	$form_pay_jurnal_gaji_code = form_dynamic($pay_jurnal_gaji_code);
	$view .= form_field_display( $form_pay_jurnal_gaji_code  , "Kode jurnal gaji"  );
	
	

	
	$pay_jurnal_gaji_label = array(
			'name'=>'pay_jurnal_gaji_label',
			'value'=>(isset($_POST['pay_jurnal_gaji_label'])? $_POST['pay_jurnal_gaji_label'] : $fields['pay_jurnal_gaji_label']),
			'id'=>'pay_jurnal_gaji_label',
			'type'=>'textfield' 
		);
	$form_pay_jurnal_gaji_label = form_dynamic($pay_jurnal_gaji_label);
	$view .= form_field_display( $form_pay_jurnal_gaji_label  , "Nama jurnal gaji"  );
	
	$opsi = array(
			'd'=>'Debet',
			'k'=>'Kreditt',
		);
	$debet_kredit = array(
			'name'=>'debet_kredit',
			'value'=>(isset($_POST['debet_kredit'])? $_POST['debet_kredit'] : $fields['debet_kredit']),
			'id'=>'debet_kredit', 
		);
	$form_debet_kredit = form_radiobutton($debet_kredit , $opsi);
	$view .= form_field_display( $form_debet_kredit  , "Debet/ Kredit"  );
	 
	$deskripsi = array(
			'name'=>'deskripsi',
			'value'=>(isset($_POST['deskripsi'])? $_POST['deskripsi'] : $fields['deskripsi']),
			'id'=>'deskripsi',
			'type'=>'textfield' 
		);
	$form_deskripsi = form_textarea($deskripsi);
	$view .= form_field_display( $form_deskripsi  , "Deskripsi"  );
	
		 
	$submit = array(
		'value' => ( $id ==0 ? ' Simpan ' :'  Update  '),
		'name' => 'simpan', 
		'type'=>'submit', 
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
	
	
	$view .= form_field_display( $form_submit .' '.$form_cancel, "&nbsp;" );
	$view .= form_footer( );	
 
	return $view;
} 
?>