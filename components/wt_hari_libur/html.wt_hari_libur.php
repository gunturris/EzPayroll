<?php

function list_wt_hari_libur(){
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
		'Tanggal' => array( 'width'=>'20%','style'=>'text-align:center;' ), 
		'Keterangan' => array( 'width'=>'75%','style'=>'text-align:center;' ), 
		'Aksi' => array( 'width'=>'5%','style'=>'text-align:center;' ), 
		
	);

	
	
	$query 	= "SELECT * FROM wt_hari_libur ";
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

		$row[] = array( 
		'Tanggal' => $ey['tanggal_libur'],  
		'Keterangan' => $ey['nama_hari_libur'],   
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


function submit_wt_hari_libur($id){
	 
	$datas = array(); $datas['id']	=  my_type_data_str($_POST['id']);
	 $datas['tanggal_libur']	=  my_type_data_str($_POST['tanggal_libur']);
	 $datas['nama_hari_libur']	=  my_type_data_str($_POST['nama_hari_libur']);
	 
	if($id > 0){
		return my_update_record( 'wt_hari_libur' , 'wt_hari_libur_id' , $id , $datas );
	}
	$datas['created_on']	= my_type_data_function('NOW()');
	$datas['user_created_id']	=  my_type_data_str($_POST['user_created_id']);
	return my_insert_record( 'wt_hari_libur' , $datas );
}

function form_wt_hari_libur_validate(){
	return false;
}
	
	
function edit_wt_hari_libur($id){
	
	my_set_file_js(
		array(
			'assets/jquery/combomulti/jquery.chainedSelects.js',
			'assets/js/calendar/calendarDateInput.js' 
		)
	);
	$view = form_header( "form_wt_hari_libur" , "form_wt_hari_libur"  );
	$fields = my_get_data_by_id('wt_hari_libur','id', $id);

 
	$ftanggal_libur = date('Y-m-d');
	if($fields){ 
		$ftanggal_libur = $fields['tanggal_libur'];
	}
	
	$tanggal_libur = array(
			'name'=>'tanggal_libur',
			'value'=>(isset($_POST['tanggal_libur'])? $_POST['tanggal_libur'] : $ftanggal_libur),
			'id'=>'tanggal_libur',
			'type'=>'textfield',
			'size'=>'45'
		);
	$form_tanggal_libur = form_calendar($tanggal_libur);
	$view .= form_field_display( $form_tanggal_libur  , "Tanggal libur" );
	

	
	$nama_hari_libur = array(
			'name'=>'nama_hari_libur',
			'value'=>(isset($_POST['nama_hari_libur'])? $_POST['nama_hari_libur'] : $fields['nama_hari_libur']),
			'id'=>'nama_hari_libur',
			'type'=>'textfield' 
		);
	$form_nama_hari_libur = form_dynamic($nama_hari_libur);
	$view .= form_field_display( $form_nama_hari_libur  , "Nama hari libur"  );
	
	
 
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