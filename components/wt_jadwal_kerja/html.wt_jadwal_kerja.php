<?php
function list_jadwal_harian($id){
	$headers= array( 
		'Hari' => array( 'width'=>'50%','style'=>'text-align:center;' ),   
		'Jam datang' => array( 'width'=>'22%','style'=>'text-align:center;' ), 
		'Jam pulang' => array( 'width'=>'22%','style'=>'text-align:center;' ), 
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
	 
	$query 	= "SELECT * FROM wt_jadwal_kerja_hari WHERE jadwal_kerja_id = {$id} ORDER BY hari_id ASC ";
	$result = my_query($query);
	$row = array();
	while($ey = my_fetch_array($result)){
		$editproperty = array(
				'href'=>'index.php?com='.$_GET['com'].'&task=edit_jadwal_hari&hari_id='.$ey['hari_id'].'&id=' .$id , 
				'title'=>'Edit'
		);	
		$edit_button = button_icon( 'b_edit.png' , $editproperty  );
		
		$row[] = array( 
			'hari' => $hari[$ey['hari_id']],  
			'in' => position_text_align( date('H:i',strtotime($ey['jadwal_in'])) , 'center'),  
			'out'=> position_text_align( date('H:i',strtotime( $ey['jadwal_out'])) , 'center'),
			'op'=> position_text_align( $edit_button   , 'right')
		);
	}
	
	$datas = table_rows($row);
	$navigasi = array(
		'<input class="btn btn-primary" style="float:right;"  type="button" value="Kembali" onclick="javascript:location.href=\'index.php?com='.$_GET['com'].'\'"/>',
		 
	);
	$box = header_box( '&nbsp;' , $navigasi ); 
	$dfs = my_get_data_by_id('wt_jadwal_kerja','id', $id);
	$view = '  <h4 id="grid-column-ordering">Jadwal kerja : </h4><h2>'.$dfs['jadwal_kode'].' / '.$dfs['nama_jadwal'].'</h2>';
				  
	return $view .$box.table_builder($headers , $datas ,  4 , false    ); 
}

function list_wt_jadwal_kerja(){
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
		'Kode' => array( 'width'=>'10%','style'=>'text-align:center;' ), 
		'Jadwal kehadiran' => array( 'width'=>'43%','style'=>'text-align:center;' ), 
		'Status' => array( 'width'=>'20%','style'=>'text-align:center;' ), 
		'Datang' => array( 'width'=>'10%','style'=>'text-align:center;' ), 
		'Pulang' => array( 'width'=>'10%','style'=>'text-align:center;' ), 
		'Aksi' => array( 'width'=>'7%','style'=>'text-align:center;' ), 
		
	);

	
	
	$query 	= "SELECT * FROM wt_jadwal_kerja ";
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
	
	 $opsi = array('1'=>'Hadir kerja','0'=>'Tidak hadir');
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
		'Kode' => $ey['jadwal_kode'],  
		'Jadwal kehadiran' => $ey['nama_jadwal'],  
		'Status' =>  $opsi[$ey['status_hadir']],   
		'in' => position_text_align( date('H:i',strtotime($ey['jadwal_in'])) , 'center'),  
		'out'=> position_text_align( date('H:i',strtotime( $ey['jadwal_out'])) , 'center'),
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


function submit_wt_jadwal_kerja($id){
	 
	$datas = array(); 
	$datas['nama_jadwal']	=  my_type_data_str($_POST['nama_jadwal']);
	$datas['status_hadir']	=  my_type_data_str($_POST['status_hadir']); 
	$datas['jadwal_in']	=  my_type_data_str($_POST['jadwal_in'].':00'); 
	$datas['jadwal_out']	=  my_type_data_str($_POST['jadwal_out'].':00'); 
	if($id > 0){
		$datas['updated_on']	=  my_type_data_str($_POST['updated_on']);
		$datas['version'] 		=  my_type_data_function(' (version + 1)');
		$datas['user_updated_id']	= my_type_data_int($_SESSION['user_id']);  
		return my_update_record( 'wt_jadwal_kerja' , 'id' , $id , $datas );
	}
	$datas['version'] 		=  my_type_data_str(0);
	$datas['jadwal_kode']	=  my_type_data_str($_POST['jadwal_kode']);
	$datas['created_on']	=  my_type_data_function('NOW()');
	return my_insert_record( 'wt_jadwal_kerja' , $datas ); 
}

function submit_jam_jadwal_kerja( $id , $hari_id ){
	$jadwal_in  = $_POST['jadwal_in'].':00';
	$jadwal_out = $_POST['jadwal_out'].':00';
	$query = "UPDATE wt_jadwal_kerja_hari 
	SET jadwal_in = '{$jadwal_in}', jadwal_out = '{$jadwal_out}'
	WHERE jadwal_kerja_id = {$id} AND hari_id = {$hari_id} LIMIT 1";
	return my_query($query);
}

function jadwal_kode_exist($kode){
	if(trim($kode) == 'EX'){
		return true;
	}
	$query = "SELECT * FROM wt_jadwal_kerja WHERE jadwal_kode = TRIM('{$kode}')";
	$result = my_query($query);
	if(my_num_rows($result) > 0){
		return true;
	}
	return false;
}  
function form_wt_jadwal_kerja_validate($id){
	$errsubmit = false;
	$err = array();
	 
	 
	if($id == 0 ){
		if( trim($_POST['jadwal_kode']) == '' ){
			$errsubmit =true;
			$err[] = "Kode jadwal belum di isi";
		}elseif( jadwal_kode_exist( $_POST['jadwal_kode'] ) ){ 
			$errsubmit =true;
			$err[] = "Kode sudah digunakan";
		}
	} 
	
	if( trim( $_POST['nama_jadwal'] ) == ''){
		$errsubmit =true;
		$err[] = "Nama jadwal belum di isi";
	
	}
	if( $errsubmit){
		return $err;
	} 
	return false;
}
	
	
function edit_wt_jadwal_kerja($id){
	
	my_set_file_js(
		array(
			'assets/jquery/combomulti/jquery.chainedSelects.js',
			'assets/js/calendar/calendarDateInput.js' 
		)
	);
	$view = form_header( "form_wt_jadwal_kerja" , "form_wt_jadwal_kerja"  );
	$fields = my_get_data_by_id('wt_jadwal_kerja','id', $id);

	if($id == 0){ 
		$jadwal_kode = array(
			'name'=>'jadwal_kode',
			'value'=>(isset($_POST['jadwal_kode'])? $_POST['jadwal_kode'] : $fields['jadwal_kode']),
			'id'=>'jadwal_kode',
			'type'=>'textfield',
			'style'=>'max-width:55px' 
		);
	}else{
		$jadwal_kode = array(
			'name'=>'jadwal_kode',
			'value'=>(isset($_POST['jadwal_kode'])? $_POST['jadwal_kode'] : $fields['jadwal_kode']),
			'id'=>'jadwal_kode',
			'type'=>'textfield',
			'style'=>'max-width:55px' ,
			'readonly'=>'readonly'
		);
	
	}
	$form_jadwal_kode = form_dynamic($jadwal_kode);
	$view .= form_field_display( $form_jadwal_kode  , "Kode jadwal"  );
	
	 
	$nama_jadwal = array(
			'name'=>'nama_jadwal',
			'value'=>(isset($_POST['nama_jadwal'])? $_POST['nama_jadwal'] : $fields['nama_jadwal']),
			'id'=>'nama_jadwal',
			'type'=>'textfield' ,
		);
	$form_nama_jadwal = form_dynamic($nama_jadwal);
	$view .= form_field_display( $form_nama_jadwal  , "Nama jadwal"  );
	 
	 $opsi = array('1'=>'Hadir kerja','0'=>'Tidak hadir');
	$status_hadir = array(
			'name'=>'status_hadir',
			'value'=>(isset($_POST['status_hadir'])? $_POST['status_hadir'] : $fields['status_hadir']),
			'id'=>'status_hadir',
			'type'=>'textfield' 
		);
	$form_status_hadir = form_radiobutton($status_hadir , $opsi);
	$view .= form_field_display( $form_status_hadir  , "Status hadir"  );
	 
	$jadwal_in = array(
			'name'=>'jadwal_in',
			'value'=>(isset($_POST['jadwal_in'])? $_POST['jadwal_in'] : date('H:i',strtotime($fields['jadwal_in'])) ),
			'id'=>'jadwal_in',
			'placeholder'=>'HH:mm',
			'type'=>'textfield' ,
			'style'=>'max-width:70px;'
		);
	$form_nama_jadwal_in = form_dynamic($jadwal_in);
	$view .= form_field_display( $form_nama_jadwal_in  , "Jam datang"  );
	$jadwal_out = array(
			'name'=>'jadwal_out',
			'value'=>(isset($_POST['jadwal_out'])? $_POST['jadwal_out'] : date('H:i',strtotime( $fields['jadwal_out']) ) ),
			'id'=>'jadwal_out',
			'placeholder'=>'HH:mm',
			'type'=>'textfield' ,
			'style'=>'max-width:70px;'
		);
	$form_nama_jadwal_out = form_dynamic($jadwal_out);
	$view .= form_field_display( $form_nama_jadwal_out  , "Jam pulang"  );
	
		 
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