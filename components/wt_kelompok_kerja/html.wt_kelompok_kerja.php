<?php

function  submit_wt_kelompok_kerja_detail(){
	my_query("DELETE FROM wt_jadwal_kelompok 
		WHERE hari_id = {$_GET['hari_id']} AND kelompok_kerja_id= {$_GET['id']}");
	$datas = array();  
	$datas['hari_id']	=  my_type_data_str($_GET['hari_id']);
	$datas['kelompok_kerja_id']	=  my_type_data_str($_GET['id']);
	$datas['jadwal_kerja_id']	=  my_type_data_str($_POST['jadwal_kerja_id']);
	return my_insert_record('wt_jadwal_kelompok',$datas);
}
function validate_wt_kelompok_kerja_detail(){
	return false;
}
 
function edit_wt_kelompok_kerja_detail( $hari_id , $id ){
	 
	$view = form_header( "form_wt_jadwal_kerja" , "form_wt_jadwal_kerja"  );
	$fields = my_get_data_by_id('wt_kelompok_kerja','id', $id);

	$hari = array(
		'0'=>'Minggu',
		'1'=>'Senin',
		'2'=>'Selasa',
		'3'=>'Rabu',
		'4'=>'Kamis',
		'5'=>'Jumat',
		'6'=>'Sabtu');
	 
      
	$view .= form_field_display( '<br />'.$fields['kode_kelompok'].'/ '. $fields['nama_kelompok'], "Kelompok kerja"  );
	$view .= form_field_display( '<br />'.$hari[$hari_id] , "Hari"  );
	
	$jadwals = array();
	$query_jadwal = "SELECT * FROM wt_jadwal_kerja ";
	$result_jadwal = my_query($query_jadwal);
	while( $row_jadwal = my_fetch_array($result_jadwal)){
		$jadwals[$row_jadwal['id']] = 
			$row_jadwal['jadwal_kode'] .'/ ( '	. $row_jadwal['jadwal_in'] .' s/d ' 
												. $row_jadwal['jadwal_out'].' ) '. $row_jadwal['nama_jadwal']; 
	}
	$jadwal_data = get_jadwal_by_hari_dan_kelompok( $hari_id, $id);
	
	$jadwal_kerja_id = array(
			'name'=>'jadwal_kerja_id',
			'value'=>(isset($_POST['jadwal_kerja_id'])? $_POST['jadwal_kerja_id'] : $jadwal_data['id']),
			'id'=>'jadwal_kerja_id' 
		);
	$form_nama_jadwal = form_dropdown( $jadwal_kerja_id , $jadwals );
	$view .= form_field_display( $form_nama_jadwal  , "Jadwal kerja"  ); 
		 
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

function detail_rincian_hari($id){

	$headers= array( 
		'Hari' => array( 'width'=>'20%','style'=>'text-align:center;' ),   
		'Jadwal' => array( 'width'=>'34%','style'=>'text-align:center;' ),   
		'Jam datang' => array( 'width'=>'15%','style'=>'text-align:center;' ), 
		'Jam pulang' => array( 'width'=>'15%','style'=>'text-align:center;' ), 
		'Aksi' => array( 'width'=>'6%','style'=>'text-align:center;' ) 
	);
	
	$hari = array(
		'0'=>'Minggu',
		'1'=>'Senin',
		'2'=>'Selasa',
		'3'=>'Rabu',
		'4'=>'Kamis',
		'5'=>'Jumat',
		'6'=>'Sabtu');
	 
	 
	$row = array();
	for($t= 0; $t<= 6; $t++){
		$editproperty = array(
				'href'=>'index.php?com='.$_GET['com'].'&task=edit_jadwal_hari&hari_id='.$t.'&id=' .$id , 
				'title'=>'Edit'
		);	
		$edit_button = button_icon( 'b_edit.png' , $editproperty  );
		$jadwal = get_jadwal_by_hari_dan_kelompok( $t, $id);
	 
		$row[] = array( 
			'hari' => $hari[$t],  
			'jadwal' => $jadwal['info'],  
			'in' => position_text_align( $jadwal['jadwal_in']  , 'center'),  
			'out'=> position_text_align(  $jadwal['jadwal_out']  , 'center'),
			'op'=> position_text_align( $edit_button   , 'right')
		);
	}
	
	$datas = table_rows($row);
	$navigasi = array(
	//	'<input class="btn btn-primary" style="float:right;"  type="button" value="Kembali" onclick="javascript:location.href=\'index.php?com='.$_GET['com'].'\'"/>',
		 
	);
	$box = header_box( '&nbsp;' , $navigasi ); 
	$dfs = my_get_data_by_id('wt_kelompok_kerja','id', $id);
	$view = '  <h4 id="grid-column-ordering">Kelompok kerja : </h4><h2>'.$dfs['kode_kelompok'].' / '.$dfs['nama_kelompok'].'</h2>';
				  
	return $view .$box.table_builder($headers , $datas ,  4 , false    ); 

}

function get_jadwal_by_hari_dan_kelompok( $hari_id, $id){
	$query = "SELECT * FROM wt_jadwal_kelompok a 
		INNER JOIN wt_jadwal_kerja b ON a.jadwal_kerja_id = b.id
		WHERE hari_id = {$hari_id} AND a.kelompok_kerja_id = {$id}"; 
	$result = my_query($query);
	if( my_num_rows($result) > 0){
		
		$row = my_fetch_array($result);
		$datas = array();
		$datas['id'] = $row['id'];
		$datas['info'] = $row['jadwal_kode'].'/ '.$row['nama_jadwal'];
		$datas['jadwal_in'] = date('H:i',strtotime($row['jadwal_in'])); 
		$datas['jadwal_out'] = date('H:i',strtotime($row['jadwal_out']));
		return $datas;	
	}
	$datas = array();
	$datas['id'] = 0;
	$datas['info'] = "Belum di definisikan";
	$datas['jadwal_in'] = '&nbsp;';
	$datas['jadwal_out'] =  '&nbsp;';
	return $datas;
}

function list_wt_kelompok_kerja(){
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
		'Kode' => array( 'width'=>'8%','style'=>'text-align:center;' ), 
		'Nama kelompok kerja' => array( 'width'=>'55%','style'=>'text-align:center;' ), 
		'Utama' => array( 'width'=>'10%','style'=>'text-align:center;' ),  
		'Aksi' => array( 'width'=>'7%','style'=>'text-align:center;' ), 
		
	);

	
	
	$query 	= "SELECT * FROM wt_kelompok_kerja  ORDER BY  id ASC";
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

		
		$detailproperty = array(
				'href'=>'index.php?com='.$_GET['com'].'&task=detail_rincian_hari&id=' . $ey['id'] , 
				'title'=>'Detail info'
		);	
		$detail_button = button_icon( 'b_props.png' , $detailproperty  );
		
		$deleteproperty = array(
			'href'=>'javascript:confirmDelete('.$ey['id'].');',
			'title'=>'Delete', 
		);
		$delete_button = button_icon( 'b_drop.png' , $deleteproperty  );
		$opsi = array('1'=>'Ya','0'=>'Tidak');
		$row[] = array( 
		'Kode' =>position_text_align(  $ey['kode_kelompok'],  'center'),
		'Nama' => $ey['nama_kelompok'],  
		'Utama' =>position_text_align(  $opsi[$ey['is_reguler']],  'center'),  
	//	'Jadwal' =>position_text_align(  $ey['jadwal_kode'].'/ '.$ey['nama_jadwal'],  'center'),  
		'op'=> position_text_align( $detail_button . $edit_button  .$delete_button , 'center')
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


function submit_wt_kelompok_kerja($id){
	 
	$datas = array();  
	$datas['kode_kelompok']	=  my_type_data_str($_POST['kode_kelompok']);
	$datas['nama_kelompok']	=  my_type_data_str($_POST['nama_kelompok']);
	$datas['is_reguler']	=  my_type_data_str($_POST['is_reguler']);
	 
	if($id > 0){
		$datas['updated_on']		= my_type_data_function('NOW()');
		$datas['user_updated_id']	= my_type_data_int($_SESSION['user_id']);
		$datas['version'] 			= my_type_data_function( '( version + 1 )' );		
		return my_update_record( 'wt_kelompok_kerja' , 'id' , $id , $datas );
	}
	$datas['created_on']	= my_type_data_function('NOW()');
	$datas['version'] = my_type_data_str(0);
	return my_insert_record( 'wt_kelompok_kerja' , $datas );
}

function form_wt_kelompok_kerja_validate(){
	return false;
}
	
	
function edit_wt_kelompok_kerja($id){
	
	my_set_file_js(
		array(
			'assets/jquery/combomulti/jquery.chainedSelects.js',
			'assets/js/calendar/calendarDateInput.js' 
		)
	);
	$view = form_header( "form_wt_kelompok_kerja" , "form_wt_kelompok_kerja"  );
	$fields = my_get_data_by_id('wt_kelompok_kerja','id', $id);

 
	$kode_kelompok = array(
			'name'=>'kode_kelompok',
			'value'=>(isset($_POST['kode_kelompok'])? $_POST['kode_kelompok'] : $fields['kode_kelompok']),
			'id'=>'kode_kelompok',
			'type'=>'textfield' ,
			'style'=>'max-width:105px;'
		);
	$form_kode_kelompok = form_dynamic($kode_kelompok);
	$view .= form_field_display( $form_kode_kelompok  , "Kode kelompok"  );
	
	 
	$nama_kelompok = array(
			'name'=>'nama_kelompok',
			'value'=>(isset($_POST['nama_kelompok'])? $_POST['nama_kelompok'] : $fields['nama_kelompok']),
			'id'=>'nama_kelompok',
			'type'=>'textfield' 
		);
	$form_nama_kelompok = form_dynamic($nama_kelompok);
	$view .= form_field_display( $form_nama_kelompok  , "Nama kelompok"  );
	
	

	$opsi = array('1'=>'Ya','0'=>'Tidak');
	$is_reguler = array(
			'name'=>'is_reguler',
			'value'=>(isset($_POST['is_reguler'])? $_POST['is_reguler'] : ($fields ?$fields['is_reguler']: 0)),
			'id'=>'is_reguler',
			'type'=>'textfield' 
		);
	$form_is_reguler = form_radiobutton($is_reguler , $opsi);
	$view .= form_field_display($form_is_reguler . ' <font size="1">Hanya 1 kelompok yang merupakan kelompok utama</font>'  , "Merupakan kelompok kerja utama"  );
	
		 
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