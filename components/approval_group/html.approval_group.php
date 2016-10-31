<?php 

function list_approval_group(){
	
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
		'Nama kelompok' => array( 'width'=>'85%','style'=>'text-align:left;' ),   
		'Operasi' => array( 'width'=>'10%','style'=>'text-align:center;' ), 
	);
 
	$query 	= "SELECT * FROM approval_karyawan_group ";
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
		'Nama' => $ey['label'],    
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



function approval_group_form($id){
	my_set_file_js(
		array(
			'assets/jquery/combomulti/jquery.chainedSelects.js',
			'assets/js/calendar/calendarDateInput.js' 
		)
	);
	$view = form_header( "form_karyawan" , "form_karyawan"  );
	$fields = my_get_data_by_id('approval_karyawan_group','id', $id);  
	
	
	$label = array(
			'name'=>'label',
			'value'=>(isset($_POST['label'])? $_POST['label'] : $fields['label']),
			'id'=>'label',
			'type'=>'textfield' 
		);
	$form_nama_karyawan = form_dynamic($label);
	$view .= form_field_display( $form_nama_karyawan  , "Nama kelompok *"  );
	
	$deskripsi = array(
			'name'=>'deskripsi',
			'value'=>(isset($_POST['deskripsi'])? $_POST['label'] : $fields['deskripsi']),
			'id'=>'deskripsi',
			'cols'=>'35' ,
			'rows'=>'4' ,
		);
	$formdeskripsi = form_textarea($deskripsi);
	$view .= form_field_display( $formdeskripsi  , "Deskripsi"  );
	
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

function approval_group_submit_validate($id){
	
	return false;
}

function approval_group_submit($id){
	$datas = array();
	$datas['label'] = my_type_data_str($_POST['label']);
	$datas['deskripsi'] = my_type_data_str($_POST['deskripsi']);
	if( $id > 0 ){
		return my_update_record('approval_karyawan_group' ,'id',$id , $datas);
	}
	$nid = rand(10000,99999);
	$datas['id']	= my_type_data_int($nid);
	$datas['created_on'] = my_type_data_function('NOW()');
	
	return my_insert_record('approval_karyawan_group' , $datas);
}