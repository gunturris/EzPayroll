<?php 

function list_approval_eksepsi(){
	
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
		'Person (user)' => array( 'width'=>'20%','style'=>'text-align:left;' ),  
		'Persetujuan' => array( 'width'=>'5%','style'=>'text-align:left;' ),  
		'Masa berlaku' => array( 'width'=>'20%','style'=>'text-align:left;' ),  
		'Disetujui I' => array( 'width'=>'15%','style'=>'text-align:left;' ),  
		'Disetujui II' => array( 'width'=>'15%','style'=>'text-align:left;' ),  
		'Operasi' => array( 'width'=>'5%','style'=>'text-align:center;' ), 
	);
 
	$query 	= "
			SELECT * FROM approval_exception a 
			INNER JOIN karyawan b ON a.karyawan_id = b.karyawan_id";
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
		'Nama' => $ey['nik'].'/ '. substr( $ey['nama'] , 0 , 12 ),   
		'Persetujuan' => $ey['app_type'],   
		'Masa' => $ey['start_date'].' s.d '.$ey['end_date'],
		'Satu' => get_group_name_by_id ( $ey['first_group_id']),   
		'Dua' => get_group_name_by_id( $ey['second_group_id']),      
		'op'=> position_text_align(  $edit_button  .$delete_button , 'right')
		);
	}
	
	$datas = table_rows($row);
	$navigasi = array(
		'<input class="button" type="button" value="Tambah data" onclick="javascript:location.href=\'index.php?com='.$_GET['com'].'&task=edit\'"/>',
	 
	);
	$box = header_box( 'Data  approval definisi' , $navigasi );
	$paging = $kgPagerOBJ ->showPaging();
	return $box.table_builder($headers , $datas ,  7, false , $paging  ); 
}

function approval_eksepsi($id){
	
	my_set_file_js(
		array(
			'assets/jquery/autocomplete/jquery.autocomplete.js' ,
			'assets/js/calendar/calendarDateInput.js' )
	);
	
	$view = form_header( "form_karyawan" , "form_karyawan"  );
	$fields = my_get_data_by_id('approval_exception','id', $id);
	
	$query = " SELECT * FROM karyawan WHERE nik <> '' ORDER BY nik";
	$result = my_query($query);
	$namas = array();
	while( $row = my_fetch_array($result) ){
		$namas[$row['karyawan_id']] = $row['nik'].'/'.$row['nama'];
	}
	$karyawan_id  = array(
		'name'=>'karyawan_id',
		'value'=>(isset($_POST['karyawan_id'])? $_POST['karyawan_id'] : $fields['karyawan_id']),
		'id'=>'karyawan_id', 
	);
	
	$form_field_nama_orang = form_dropdown($karyawan_id , $namas );
	$view .= form_field_display( $form_field_nama_orang , "Nama orang "  );
	
	
	$app_types = array( 'CUTI'=>'Cuti' , 'ABSEN'=>'Absen' );
	$app_type  = array(
			'name'=>'app_type',
			'value'=>(isset($_POST['app_type'])? $_POST['app_type'] : $fields['app_type']),
			'id'=>'app_type', 
		);
	$form_assg = form_radiobutton($app_type , $app_types);
	$view .= form_field_display( $form_assg  , "Tipe pengajuan"  );
	
	
	$query = "SELECT * FROM karyawan_groups";
	$res = my_query($query);
	$grp = array();
	while( $row = my_fetch_array($res)){
		$grp[$row['id']] = $row['groups_name'];
	}
	$first_group_id  = array(
		'name'=>'first_group_id',
		'value'=>(isset($_POST['first_group_id'])? $_POST['first_group_id'] : $fields['first_group_id']),
		'id'=>'first_group_id', 
	); 
	$form_field_first_group_id = form_dropdown($first_group_id , $grp );
	
	$second_group_id  = array(
		'name'=>'second_group_id',
		'value'=>(isset($_POST['second_group_id'])? $_POST['second_group_id'] : $fields['second_group_id']),
		'id'=>'second_group_id', 
	); 
	$form_field_snd_group_id = form_dropdown($second_group_id , $grp );
	$view .= form_field_display( $form_field_first_group_id  , "Kelompok penyetuju I"  );
	$view .= form_field_display( $form_field_snd_group_id  , "Kelompok penyetuju II"  );
	
	
	$ftanggal_mulai = date('Y-m-d');
	if($fields){
		$ftanggal_mulai =$fields['start_date'] ;
	}
	
	$start_date = array(
			'name'=>'start_date',
			'value'=>(isset($_POST['start_date'])? $_POST['start_date'] : $ftanggal_mulai),
			'id'=>'start_date', 
		);
	$form_start_date = form_calendar($start_date);
	$view .= form_field_display( $form_start_date  , "Tanggal berlaku" );
	
	
	$ftanggal_akhir = date('Y-m-d');
	if($fields){
		$ftanggal_akhir =$fields['end_date'] ;
	}
	
	$end_date = array(
			'name'=>'end_date',
			'value'=>(isset($_POST['end_date'])? $_POST['end_date'] : $ftanggal_akhir),
			'id'=>'end_date', 
		);
	$form_end_date = form_calendar($end_date);
	$view .= form_field_display( $form_end_date  , "Tanggal berakhir" );
	
	
	$submit = array(
		'value' => ( $id ==0 ? ' Simpan ' :'  Update  '),
		'name' => 'simpan', 
		'type'=>'submit','class'=>'main_button'
	);
	$form_submit= form_dynamic($submit);
	
	$cancel = array(
		'value' =>  '  Kembali  ' ,
		'name' => 'cancel', 
		'onclick'=>'javascript:location.href=\'index.php?com='.$_GET['com'].'\'',
		'type'=>'button',
		'class'=>'main_button'
	);
	$form_cancel= form_dynamic($cancel);
	
	$view .= form_field_display( "&nbsp;"   , "&nbsp;" ,  "" );
	$view .= form_field_display( $form_submit.' '.$form_cancel, "&nbsp;" ,  "" );
	$view .= form_footer( );	
	$navigasi = array(
		 
	);
	$box = header_box( ($id > 0 ? 'Edit':'Tambah').' pola persetujuan' , $navigasi );
	return $box.$view;
	
}

function approval_exeption_submit($id){
	$datas = array();
	$datas['karyawan_id'] 	= my_type_data_int($_POST['karyawan_id']);
	$datas['app_type'] 		= my_type_data_str( $_POST['app_type'] );
	$datas['first_group_id'] = my_type_data_int($_POST['first_group_id']);
	$datas['second_group_id'] = my_type_data_int($_POST['second_group_id']);
	$datas['start_date'] = my_type_data_str( $_POST['start_date'] ); 
	$datas['end_date'] = my_type_data_str( $_POST['end_date'] ); 
	
	if( (int) $id > 0){
		return my_update_record('approval_exception' , 'id' , $id , $datas );
	}
	return my_insert_record('approval_exception'  , $datas );
}

function get_group_name_by_id($id){
	$query = "SELECT groups_name FROM karyawan_groups WHERE id = {$id}";
	$result = my_query($query);
	$row = my_fetch_array($result);
	return $row['groups_name'];
}

function approval_exeption_validate($id){
	
	return false;
	
}