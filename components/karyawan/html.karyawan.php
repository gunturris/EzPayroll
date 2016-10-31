<?php

function list_karyawan(){
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
		'NIK' => array( 'width'=>'10%','style'=>'text-align:center;' ), 
		'Nama karyawan' => array( 'width'=>'35%','style'=>'text-align:left;' ), 
		'Umur' => array( 'width'=>'10%','style'=>'text-align:center;' ), 
		'PTKP' => array( 'width'=>'10%','style'=>'text-align:left;' ), 
		'Status' => array( 'width'=>'15%','style'=>'text-align:left;' ), 
		'Aksi' => array( 'width'=>'10%','style'=>'text-align:center;' ), 
		
	);

	if(isset($_GET['key'])){
		$query 	= "SELECT * FROM   karyawan  
		WHERE  nama_karyawan LIKE '%{$_GET['key']}%' OR karyawan_nik = '{$_GET['key']}'  ";
	}else{ 
		$query 	= "SELECT * FROM karyawan ";
	}
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
				'href'=>'index.php?com='.$_GET['com'].'&task=edit&id=' . $ey['karyawan_id'] , 
				'title'=>'Edit'
		);	
		$edit_button = button_icon( 'b_edit.png' , $editproperty  );

		$deleteproperty = array(
			'href'=>'javascript:confirmDelete('.$ey['karyawan_id'].');',
			'title'=>'Delete', 
		);
		$delete_button = button_icon( 'b_drop.png' , $deleteproperty  );
		$ptkp_karyawan = get_ptkp_karyawan($ey['tax_ptkp_category_id']);
		$status_karyawan = get_status_karyawan($ey['karyawan_status_id']);
		$umur = getage($ey['tanggal_lahir']);
		$row[] = array( 
		'NIK' =>  position_text_align($ey['karyawan_nik'], 'center'),    
		'Nama karyawan' => $ey['nama_karyawan'],  
		'Umur' => position_text_align($umur, 'center'),  
		'PTKP' => $ptkp_karyawan,  
		'Status' => $status_karyawan,   
		'op'=> position_text_align( $edit_button  .$delete_button , 'center')
		);
	}
	
	$datas = table_rows($row);
	$navigasi = array(
		'<input class="btn btn-primary" style="float:right;"  type="button" value="Tambah data" onclick="javascript:location.href=\'index.php?com='.$_GET['com'].'&task=edit\'"/>',
	);
	$form_Search  =
	'<form method="GET"><div class="form-group input-group" style="width:280px">
	<input type="hidden" name="com" value="'.$_GET['com'].'" />
		<input type="text" class="form-control" name="key">
		<span class="input-group-btn">
			<button class="btn btn-default" type="submit"><i class="fa fa-search"></i>
			</button>
		</span>
	
	</div></form>';
	$box = header_box( $form_Search , $navigasi );
	$paging = $kgPagerOBJ ->showPaging();
	return $box.table_builder($headers , $datas ,  6 , false , $paging  ); 
}

function get_ptkp_karyawan($status_id){
	$query = "SELECT * FROM tax_ptkp_categori WHERE tax_ptkp_categori_id = {$status_id}";
	$result = my_query($query);
	if(my_num_rows($result) > 0 ){
		$row = my_fetch_array($result);
		return $row['tax_ptkp_categori_code'];
	}
	return false;
}

function get_status_karyawan($karyawan_status_id){
	$query = "SELECT * FROM karyawan_status WHERE karyawan_status_id = {$karyawan_status_id}";
	$result = my_query($query);
	if(my_num_rows($result) > 0 ){
		$row = my_fetch_array($result);
		return $row['karyawan_status_label'];
	}
	return false;
}

function submit_karyawan($id){
	 
	$datas = array();  
	 $datas['karyawan_nik']		=  my_type_data_str($_POST['karyawan_nik']);
	 $datas['nama_karyawan']	=  my_type_data_str($_POST['nama_karyawan']);
	 $datas['tempat_lahir']		=  my_type_data_str($_POST['tempat_lahir']);
	 $datas['tanggal_lahir']	=  my_type_data_str($_POST['tanggal_lahir']);
	 $datas['kelamin']			=  my_type_data_str($_POST['kelamin']);
	 $datas['karyawan_agama_id']	=  my_type_data_int($_POST['karyawan_agama_id']);
	 $datas['npwp']				=  my_type_data_str($_POST['npwp']);
	 $datas['idno']				=  my_type_data_str($_POST['idno']);
	 $datas['alamat']			=  my_type_data_str($_POST['alamat']);
	 $datas['alamat_kota']		=  my_type_data_str($_POST['alamat_kota']);
	 $datas['alamat_kodepos']	=  my_type_data_str($_POST['alamat_kodepos']);
	 $datas['karyawan_gol_jab_id']	=  my_type_data_int($_POST['karyawan_gol_jab_id']);
	 $datas['karyawan_status_id']	=  my_type_data_int($_POST['karyawan_status_id']);
	 $datas['tax_ptkp_category_id']	=  my_type_data_int($_POST['tax_ptkp_category_id']);
	 $datas['tanggal_bekerja']		=  my_type_data_str($_POST['tanggal_bekerja']);
	 $datas['basic_salary']			=  my_type_data_str($_POST['basic_salary']);
	$datas['user_updated_id']		= my_type_data_int($_SESSION['user_id']);  
		 
		 
	 
	if($id > 0){
		$datas['version']	=  my_type_data_function('( version + 1 )');
		$datas['updated_on']	=  my_type_data_function('NOW()');
		return my_update_record( 'karyawan' , 'karyawan_id' , $id , $datas );
	}
	
	$datas['created_on']	= my_type_data_function('NOW()');
	$datas['version'] 		= my_type_data_int(0);
	
	$karyawan_id = my_insert_record( 'karyawan' , $datas );
	$default_kelompok = default_kelompok_kerja();
	
	$datas_kelompok = array(
		'karyawan_id' => my_type_data_int($karyawan_id),
		'kelompok_kerja_id' => my_type_data_int($default_kelompok),
		'abaikan_finger' => my_type_data_str('0'),
	);
	my_insert_record('wt_kelompok_kerja_karyawan', $datas_kelompok );
	return $karyawan_id;
}

function default_kelompok_kerja(){
	$query = "SELECT id FROM wt_kelompok_kerja WHERE is_reguler = '1' ORDER BY RAND() LIMIT 1";
	$res = my_query($query);
	$row = my_fetch_array($res);
	return $row['id'];
}

function form_karyawan_validate(){
	$errsubmit = false;
	$err = array();
	
	$karyawan_nik = trim($_POST['karyawan_nik']);
	$nik_exists = karyawan_spesific_is_unique($karyawan_nik , 'karyawan_nik');
	if($karyawan_nik == ''){
		$errsubmit =true;
		$err[] = "Nomor induk karyawan belum di isi";
	} 
	elseif(! $nik_exists ){
		$errsubmit =true;
		$err[] = "Nomor induk  karyawan sudah digunakan";
	}
	
	if( trim($_POST['nama_karyawan'] ) =='' ){
		$errsubmit =true;
		$err[] = "Nama belum di isi";
	}
	
	if( ! isset($_POST['kelamin'] ) ){
		$errsubmit =true;
		$err[] = "Jenis kelamin belum di isi";
	}	
	$npwp = trim($_POST['npwp']);
	$npwp_exists = karyawan_spesific_is_unique($karyawan_nik , 'npwp');
	if($npwp == ''){
		$errsubmit =true;
		$err[] = "NPWP karyawan belum di isi";
	} 
	elseif(! $npwp_exists ){
		$errsubmit =true;
		$err[] = "NPWP  karyawan sudah digunakan";
	}
	 
	$ktp = trim($_POST['idno']);
	$ktp_exists = karyawan_spesific_is_unique($karyawan_nik , 'ktp');
	if($ktp == ''){
		$errsubmit =true;
		$err[] = "Nomor KTP karyawan belum di isi";
	} 
	elseif(! $ktp_exists ){
		$errsubmit =true;
		$err[] = "Nomor KTP karyawan sudah digunakan";
	}
	
	if( $errsubmit){
		return $err;
	}
	return false;
}

	
function karyawan_spesific_is_unique($code , $type = 'karyawan_nik'){
	$id = isset($_GET['id']) ?  (int) $_GET['id'] : 0;
	if($id > 0 ) return true;
	$code = strtoupper(trim($code));
	if($type == 'ktp'){
		$query = "SELECT * FROM karyawan   WHERE UPPER(idno) = '{$code}' ";
	}elseif($type == 'npwp'){
		$query = "SELECT * FROM karyawan   WHERE UPPER(npwp) = '{$code}' ";
	}else{
		$query = "SELECT * FROM karyawan   WHERE UPPER(karyawan_nik) = '{$code}' ";
	}
	$result = my_query($query);
	$row_count = my_num_rows($result);
	 
	if($row_count > 0){
		return false;
	}
	return true;
}	
	
function edit_karyawan($id){
	
	my_set_file_js(
		array(
			'assets/jquery/combomulti/jquery.chainedSelects.js',
			'assets/js/calendar/calendarDateInput.js' 
		)
	);
	$view = form_header( "form_karyawan" , "form_karyawan"  );
	$fields = my_get_data_by_id('karyawan','karyawan_id', $id);

 
	
	$karyawan_nik = array(
			'name'=>'karyawan_nik',
			'value'=>(isset($_POST['karyawan_nik'])? $_POST['karyawan_nik'] : $fields['karyawan_nik']),
			'id'=>'karyawan_nik',
			'type'=>'textfield' 
		);
	$form_karyawan_nik = form_dynamic($karyawan_nik);
	$view .= form_field_display( $form_karyawan_nik  , "Nomor Induk Karyawan *"  );
	
	

	
	$nama_karyawan = array(
			'name'=>'nama_karyawan',
			'value'=>(isset($_POST['nama_karyawan'])? $_POST['nama_karyawan'] : $fields['nama_karyawan']),
			'id'=>'nama_karyawan',
			'type'=>'textfield' 
		);
	$form_nama_karyawan = form_dynamic($nama_karyawan);
	$view .= form_field_display( $form_nama_karyawan  , "Nama karyawan *"  );
		
	$tempat_lahir = array(
			'name'=>'tempat_lahir',
			'value'=>(isset($_POST['tempat_lahir'])? $_POST['tempat_lahir'] : $fields['tempat_lahir']),
			'id'=>'tempat_lahir',
			'type'=>'textfield' 
		);
	$form_tempat_lahir = form_dynamic($tempat_lahir);
	$view .= form_field_display( $form_tempat_lahir  , "Tempat lahir"  );
	
	

	$ftanggal_lahir = $fields ? $fields['tanggal_lahir'] : date('Y-m-d');
	 
	
	$tanggal_lahir = array(
			'name'=>'tanggal_lahir',
			'value'=>(isset($_POST['tanggal_lahir'])? $_POST['tanggal_lahir'] : $ftanggal_lahir),
			'id'=>'tanggal_lahir',
			'type'=>'textfield',
			'size'=>'45'
		);
	$form_tanggal_lahir = form_calendar($tanggal_lahir);
	$view .= form_field_display( $form_tanggal_lahir  , "Tanggal lahir *" );
	

	$opsi = array(
		'Laki-laki'=>'Laki-laki',
		'Perempuan'=>'Perempuan' 
	);
	$kelamin = array(
			'name'=>'kelamin',
			'value'=>(isset($_POST['kelamin'])? $_POST['kelamin'] : $fields['kelamin']),
			'id'=>'kelamin', 
		);
	$form_kelamin = form_radiobutton($kelamin , $opsi);
	$view .= form_field_display( $form_kelamin  , "Kelamin *"  );
	
	

	$karyawan_agama_ids =  array( );
	$query = "SELECT karyawan_agama_id , karyawan_agama_label FROM karyawan_agama";	
	$result = my_query($query);
	while($row_karyawan_agama_id = my_fetch_array($result)){
		$karyawan_agama_ids[$row_karyawan_agama_id['karyawan_agama_id']] = $row_karyawan_agama_id['karyawan_agama_label'];
	}
	$karyawan_agama_id = array(
		'name'=>'karyawan_agama_id',
		'value'=>( isset($_POST['karyawan_agama_id']) ? $_POST['karyawan_agama_id'] : $fields['karyawan_agama_id']) ,
	);
	$form_karyawan_agama_id = form_dropdown($karyawan_agama_id , $karyawan_agama_ids);
	$view .= form_field_display(  $form_karyawan_agama_id   , "Agama"    ); 
	

	
	$npwp = array(
			'name'=>'npwp',
			'value'=>(isset($_POST['npwp'])? $_POST['npwp'] : $fields['npwp']),
			'id'=>'npwp',
			'type'=>'textfield' 
		);
	$form_npwp = form_dynamic($npwp);
	$view .= form_field_display( $form_npwp  , "NPWP *"  );
	 
	$idno = array(
			'name'=>'idno',
			'value'=>(isset($_POST['idno'])? $_POST['idno'] : $fields['idno']),
			'id'=>'idno',
			'type'=>'textfield' 
		);
	$form_idno = form_dynamic($idno);
	$view .= form_field_display( $form_idno  , "Nomor KTP *"  );
	
	

	
	$alamat = array(
			'name'=>'alamat',
			'value'=>(isset($_POST['alamat'])? $_POST['alamat'] : $fields['alamat']),
			'id'=>'alamat',
			'rows'=>'3' 
		);
	$form_alamat = form_textarea($alamat);
	$view .= form_field_display( $form_alamat  , "Alamat"  );
	
	

	
	$alamat_kota = array(
			'name'=>'alamat_kota',
			'value'=>(isset($_POST['alamat_kota'])? $_POST['alamat_kota'] : $fields['alamat_kota']),
			'id'=>'alamat_kota',
			'type'=>'textfield' 
		);
	$form_alamat_kota = form_dynamic($alamat_kota);
	$view .= form_field_display( $form_alamat_kota  , "Kota"  );
	 
	$alamat_kodepos = array(
			'name'=>'alamat_kodepos',
			'value'=>(isset($_POST['alamat_kodepos'])? $_POST['alamat_kodepos'] : $fields['alamat_kodepos']),
			'id'=>'alamat_kodepos',
			'type'=>'textfield' 
		);
	$form_alamat_kodepos = form_dynamic($alamat_kodepos);
	$view .= form_field_display( $form_alamat_kodepos  , "Kodepos"  );
	 
	$karyawan_gol_jab_ids =  array( );
	$query = "SELECT karyawan_gol_jab_id , karyawan_gol_jab_label FROM karyawan_gol_jab";	
	$result = my_query($query);
	while($row_karyawan_gol_jab_id = my_fetch_array($result)){
		$karyawan_gol_jab_ids[$row_karyawan_gol_jab_id['karyawan_gol_jab_id']] = $row_karyawan_gol_jab_id['karyawan_gol_jab_label'];
	}
	$karyawan_gol_jab_id = array(
		'name'=>'karyawan_gol_jab_id',
		'value'=>( isset($_POST['karyawan_gol_jab_id']) ? $_POST['karyawan_gol_jab_id'] : $fields['karyawan_gol_jab_id']) ,
	);
	$form_karyawan_gol_jab_id = form_dropdown($karyawan_gol_jab_id , $karyawan_gol_jab_ids);
	$view .= form_field_display(  $form_karyawan_gol_jab_id   , "Klasifikasi level karyawan"    ); 
	

	$karyawan_status_ids =  array( );
	$query = "SELECT karyawan_status_id , karyawan_status_label FROM karyawan_status";	
	$result = my_query($query);
	while($row_karyawan_status_id = my_fetch_array($result)){
		$karyawan_status_ids[$row_karyawan_status_id['karyawan_status_id']] = $row_karyawan_status_id['karyawan_status_label'];
	}
	$karyawan_status_id = array(
		'name'=>'karyawan_status_id',
		'value'=>( isset($_POST['karyawan_status_id']) ? $_POST['karyawan_status_id'] : $fields['karyawan_status_id']) ,
	);
	$form_karyawan_status_id = form_dropdown($karyawan_status_id , $karyawan_status_ids);
	$view .= form_field_display(  $form_karyawan_status_id   , "Status kekaryawanan"    ); 
	

	$tax_ptkp_category_ids =  array( );
	$query = "SELECT tax_ptkp_categori_id , tax_ptkp_categori_code FROM tax_ptkp_categori";	
	$result = my_query($query);
	while($row_tax_ptkp_category_id = my_fetch_array($result)){
		$tax_ptkp_category_ids[$row_tax_ptkp_category_id['tax_ptkp_categori_id']] = $row_tax_ptkp_category_id['tax_ptkp_categori_code'];
	}
	$tax_ptkp_category_id = array(
		'name'=>'tax_ptkp_category_id',
		'value'=>( isset($_POST['tax_ptkp_category_id']) ? $_POST['tax_ptkp_category_id'] : $fields['tax_ptkp_category_id']) ,
	);
	$form_tax_ptkp_category_id = form_dropdown($tax_ptkp_category_id , $tax_ptkp_category_ids);
	$view .= form_field_display(  $form_tax_ptkp_category_id   , "Klasifikasi PTKP"    ); 
	

	$ftanggal_bekerja = $fields ? $fields['tanggal_bekerja'] : date('Y-m-d');
	 
	
	$tanggal_bekerja = array(
			'name'=>'tanggal_bekerja',
			'value'=>(isset($_POST['tanggal_bekerja'])? $_POST['tanggal_bekerja'] : $ftanggal_bekerja),
			'id'=>'tanggal_bekerja',
			'type'=>'textfield',
			'size'=>'45'
		);
	$form_tanggal_bekerja = form_calendar($tanggal_bekerja);
	$view .= form_field_display( $form_tanggal_bekerja  , "Tanggal mulai bekerja" );
	

	
	$basic_salary = array(
			'name'=>'basic_salary',
			'value'=>(isset($_POST['basic_salary'])? $_POST['basic_salary'] : $fields['basic_salary']),
			'id'=>'basic_salary',
			'type'=>'textfield' ,
			'placeholder'=>'1000000'
		);
	$form_basic_salary = form_dynamic($basic_salary);
	$view .= form_field_display(    $form_basic_salary   , "Gaji pokok"   );
	
		 
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
		'onclick'=>'javascript:location.href=\''.( isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'index.php').'\'',
		'class'=>'main_button'
	);
	$form_cancel= form_dynamic($cancel);
	
	
	$view .= form_field_display( $form_submit .' '.$form_cancel, "&nbsp;" );
	$view .= form_field_display( "&nbsp;" , "&nbsp;" );
	$view .= form_footer( );	
 
	return $view;
} 
?>