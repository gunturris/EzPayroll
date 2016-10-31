<?php

function list_tax_ptkp_categori(){
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
		'Kode' => array( 'width'=>'15%','style'=>'text-align:left;' ), 
		'Nominal' => array( 'width'=>'15%','style'=>'text-align:center;' ), 
		'Deskripsi' => array( 'width'=>'55%','style'=>'text-align:left;' ), 
		'Aksi' => array( 'width'=>'10%','style'=>'text-align:center;' ), 
		
	);

	
	
	$query 	= "SELECT * FROM tax_ptkp_categori ";
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
	
	$row = array();
	while($ey = my_fetch_array($result)){
		$i++;
		$editproperty = array(
				'href'=>'index.php?com='.$_GET['com'].'&task=edit&id=' . $ey['tax_ptkp_categori_id'] , 
				'title'=>'Edit'
		);	
		$edit_button = button_icon( 'b_edit.png' , $editproperty  );

		$deleteproperty = array(
			'href'=>'javascript:confirmDelete('.$ey['tax_ptkp_categori_id'].');',
			'title'=>'Delete', 
		);
		$delete_button = button_icon( 'b_drop.png' , $deleteproperty  );

		$row[] = array( 
		'#' => position_text_align( $i,   'center'),  
		'Kode' => $ey['tax_ptkp_categori_code'],
		'Nominal' =>  position_text_align( rp_format($ey['ptkp_nominal']), 'right'),
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


function submit_tax_ptkp_categori($id){
	 
	$datas = array();   
	$datas['tax_ptkp_categori_code']	=  my_type_data_str($_POST['tax_ptkp_categori_code']);
	$datas['ptkp_nominal']					=  my_type_data_str($_POST['ptkp_nominal']);
	$datas['deskripsi']					=  my_type_data_str($_POST['deskripsi']);
	$datas['user_updated_id']			= my_type_data_int($_SESSION['user_id']);  

	if($id > 0){
		
		$datas['updated_on']	=  my_type_data_function('NOW()');
		$datas['version'] 	= my_type_data_int(0);
		return my_update_record( 'tax_ptkp_categori' , 'tax_ptkp_categori_id' , $id , $datas );
	}
	$datas['created_on']	= my_type_data_function('NOW()');
	return my_insert_record( 'tax_ptkp_categori' , $datas );
}

function form_tax_ptkp_categori_validate(){
	
	$errsubmit = false;
	$err = array();
	$code = trim($_POST['tax_ptkp_categori_code']);
	if($code == ''){
		$errsubmit =true;
		$err[] = "Kode kategori belum di isi";
	}elseif(!tax_code_is_unique($code)){
		$errsubmit =true;
		$err[] = "Kode kategori sudah digunakan";
	}
	
	if( $errsubmit){
		return $err;
	}
	return false;
}

function tax_code_is_unique($code){
	$id = isset($_GET['id']) ?  (int) $_GET['id'] : 0;
	if($id > 0 ) return true;
	$code = trim($code);
	$query = "SELECT * FROM tax_ptkp_categori WHERE tax_ptkp_categori_code = '{$code}' ";
	$result = my_query($query);
	$row_count = my_num_rows($result);
	if($row_count > 0){
		return false;
	}
	return true;
}	
	
function edit_tax_ptkp_categori($id){
	
	my_set_file_js(
		array(
			'assets/jquery/combomulti/jquery.chainedSelects.js',
			'assets/js/calendar/calendarDateInput.js' 
		)
	);
	$view = form_header( "form_tax_ptkp_categori" , "form_tax_ptkp_categori"  );
	$fields = my_get_data_by_id('tax_ptkp_categori','tax_ptkp_categori_id', $id);
 
	$tax_ptkp_categori_code = array(
			'name'=>'tax_ptkp_categori_code',
			'value'=>(isset($_POST['tax_ptkp_categori_code'])? $_POST['tax_ptkp_categori_code'] : $fields['tax_ptkp_categori_code']),
			'id'=>'tax_ptkp_categori_code',
			'type'=>'textfield' 
		);
	$form_tax_ptkp_categori_code = form_dynamic($tax_ptkp_categori_code);
	$view .= form_field_display( $form_tax_ptkp_categori_code  , "Kode kategori"  );
	
	$ptkp_nominal = array(
			'name'=>'ptkp_nominal',
			'value'=>(isset($_POST['ptkp_nominal'])? $_POST['ptkp_nominal'] : $fields['ptkp_nominal']),
			'id'=>'ptkp_nominal',
			'type'=>'textfield' 
		);
	$form_ptkp_nominal = form_dynamic($ptkp_nominal);
	$view .= form_field_display( $form_ptkp_nominal  , "Nominal"  );
	
	 
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
	
	
	$view .= form_field_display( $form_submit .' '.$form_cancel, "&nbsp;" );
	$view .= form_footer( );	
 
	return $view;
} 
?>