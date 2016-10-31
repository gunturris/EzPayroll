<?php 
function validatejadwal_eksepsi_karyawan(){
 
 
	$errsubmit = false;
	$err = array(); 
	
	if( (int)$_POST['status_hadir'] == 1 ){
			
		if( trim( $_POST['eksepsi_in'] ) ==''  ){
			$errsubmit =true;
			$err[] = "Jadwal datang belum di buat";
		} 	
		if( trim( $_POST['eksepsi_out'] ) ==''  ){
			$errsubmit =true;
			$err[] = "Jadwal pulang belum di buat";
		} 
	}  
	
	if( $errsubmit){
		return $err;
	}  
	return false;
}

function submit_jadwal_eksepsi_karyawan($karyawan_id,$hari_id){
	$query = "	DELETE FROM wt_jadwal_kerja_eksepsi 
				WHERE karyawan_id = {$karyawan_id} 
				AND hari_id = {$hari_id}";
	my_query($query);
	$datas = array(
		'karyawan_id'=>my_type_data_int($karyawan_id),
		'hari_id'=>my_type_data_int($hari_id),
		'status_hadir'=>my_type_data_str($_POST['status_hadir']),
		'eksepsi_in'=>my_type_data_str($_POST['eksepsi_in'].':00'),
		'eksepsi_out'=>my_type_data_str($_POST['eksepsi_out'].':00'),
	);
	return my_insert_record('wt_jadwal_kerja_eksepsi' , $datas);
}

function  edit_jadwal_eksepsi_karyawan($karyawan_id,$hari_id){
	my_set_file_js(
		array(
			'assets/jquery/combomulti/jquery.chainedSelects.js',
			'assets/js/calendar/calendarDateInput.js' 
		)
	);
	$view = form_header( "form_wt_jadwal_kerja" , "form_wt_jadwal_kerja"  );
	$query = "SELECT * FROM wt_jadwal_kerja_eksepsi 
			WHERE karyawan_id = {$karyawan_id} 
			AND hari_id = {$hari_id} LIMIT 1";
	 
	$result = my_query($query);
	if(my_num_rows($result) > 0){
		$fields = my_fetch_array($result);
	}else{
		$fields = array();
		$fields['status_hadir'] = '1';
		$fields['eksepsi_in'] = '00:00:00';
		$fields['eksepsi_out'] = '00:00:00';
	}
	$karyawan = my_get_data_by_id('karyawan','karyawan_id', $karyawan_id);
	$view .= form_field_display('<br />'. $karyawan['karyawan_nik'].'/ '.$karyawan['nama_karyawan']  , "Karyawan"  );
	
	
	$hari = array(
		'0'=>'Minggu',
		'1'=>'Senin',
		'2'=>'Selasa',
		'3'=>'Rabu',
		'4'=>'Kamis',
		'5'=>'Jumat',
		'6'=>'Sabtu'
	);
	
	$view .= form_field_display( '<br />'.$hari[$hari_id]  , "Hari"  ); 
	 
	$opsi = array('1'=>'Hadir kerja','0'=>'Tidak hadir');
	$status_hadir = array(
			'name'=>'status_hadir',
			'value'=>(isset($_POST['status_hadir'])? $_POST['status_hadir'] : $fields['status_hadir']),
			'id'=>'status_hadir',
			'type'=>'textfield' 
		);
	$form_status_hadir = form_radiobutton($status_hadir , $opsi);
	$view .= form_field_display( $form_status_hadir  , "Status hadir"  );
	 
	$eksepsi_in = array(
			'name'=>'eksepsi_in',
			'value'=>(isset($_POST['eksepsi_in'])? $_POST['eksepsi_in'] : date('H:i',strtotime($fields['eksepsi_in'])) ),
			'id'=>'eksepsi_in',
			'placeholder'=>'HH:mm',
			'type'=>'textfield' ,
			'style'=>'max-width:70px;'
		);
	$form_nama_jadwal_in = form_dynamic($eksepsi_in);
	$view .= form_field_display( $form_nama_jadwal_in  , "Jam datang"  );
	$eksepsi_out = array(
			'name'=>'eksepsi_out',
			'value'=>(isset($_POST['eksepsi_out'])? $_POST['eksepsi_out'] : date('H:i',strtotime( $fields['eksepsi_out']) ) ),
			'id'=>'eksepsi_out',
			'placeholder'=>'HH:mm',
			'type'=>'textfield' ,
			'style'=>'max-width:70px;'
		);
	$form_nama_jadwal_out = form_dynamic($eksepsi_out);
	$view .= form_field_display( $form_nama_jadwal_out  , "Jam pulang"  );
	
		 
	$submit = array(
		'value' =>  '  Update  ' ,
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

function edit_jadwal_karyawan($karyawan_id){
	my_set_file_js(
		array(
			'assets/jquery/combomulti/jquery.chainedSelects.js',
			'assets/js/calendar/calendarDateInput.js' 
		)
	);
	$view = form_header( "form_karyawan" , "form_karyawan"  );
	$karyawan = my_get_data_by_id('karyawan','karyawan_id', $karyawan_id);
	$karyawan_status = my_get_data_by_id( 'karyawan_status' ,'karyawan_status_id' , $karyawan['karyawan_status_id'] );
	$karyawan_gol_jab = my_get_data_by_id( 'karyawan_gol_jab' ,'karyawan_gol_jab_id' , $karyawan['karyawan_gol_jab_id'] );
	
  
	$view .= form_field_display( '<br/>'.$karyawan['karyawan_nik'].'/ '. $karyawan['nama_karyawan'] , "Karyawan"  );
	$view .= form_field_display('<br/>'. $karyawan_status['karyawan_status_label']  , "Karyawan status"  );
	$view .= form_field_display( '<br/>'.$karyawan_gol_jab['karyawan_gol_jab_label'] , "Golongan jabatan"  );
	 
	$query = "SELECT * FROM wt_kelompok_kerja_karyawan WHERE karyawan_id = {$karyawan_id} LIMIT 1";
	$result = my_query($query);
	$fields = my_fetch_array($result);
	$kelompok_kerjas =  array( );
	$query = "SELECT  kode_kelompok , nama_kelompok , id FROM wt_kelompok_kerja";	
	$result = my_query($query);
	while($res = my_fetch_array($result)){
		$kelompok_kerjas[$res['id']] = $res['kode_kelompok'].'/ '. $res['nama_kelompok'];
	}
	$kelompok_kerja_id = array(
		'name'=>'kelompok_kerja_id',
		'value'=>( isset($_POST['kelompok_kerja_id']) ? $_POST['kelompok_kerja_id'] : $fields['kelompok_kerja_id']) ,
	);
	$form_tax_ptkp_category_id = form_dropdown($kelompok_kerja_id , $kelompok_kerjas);
	$view .= form_field_display(  $form_tax_ptkp_category_id   , "Kelompok kerja"    ); 
	
	
	$status_option = array(
		'0' => "Diminta presensi sidik jari",
		'1' => "Abaikan presensi sidik jari",
	);
	$status_hadir = array(
			'name'=>'abaikan_finger',
			'value'=>(isset($_POST['abaikan_finger'])? $_POST['abaikan_finger'] : $fields['abaikan_finger']) 
		);
	$form_status_hadir = form_radiobutton($status_hadir , $status_option);
	$view .= form_field_display( $form_status_hadir  , "Penggunakan mesin sidik jari"  );
   
	
	$submit = array(
		'value' =>  '  Update  ' ,
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

function detail_jadwal_karyawan($karyawan_id){
	$karyawan = my_get_data_by_id('karyawan','karyawan_id',$karyawan_id);
	$karyawan_status = my_get_data_by_id( 'karyawan_status' ,'karyawan_status_id' , $karyawan['karyawan_status_id'] );
	$karyawan_gol_jab = my_get_data_by_id( 'karyawan_gol_jab' ,'karyawan_gol_jab_id' , $karyawan['karyawan_gol_jab_id'] );
	
	$kelompok_kerja_karyawan = my_get_data_by_id('wt_kelompok_kerja_karyawan','karyawan_id',$karyawan_id);	
	$kelompok_kerja = my_get_data_by_id('wt_kelompok_kerja','id', (int)$kelompok_kerja_karyawan['kelompok_kerja_id']);	
	 
	$view = '  <h4 id="grid-column-ordering">'.$karyawan['karyawan_nik'].' / '.$karyawan['nama_karyawan'].'</h3>
				<h5>'.$karyawan_status['karyawan_status_label'].' ('.$karyawan_gol_jab['karyawan_gol_jab_label'].') </h5>	 
				<h6><i>Kelompok kerja ( '.$kelompok_kerja['kode_kelompok'].'/ '. $kelompok_kerja['nama_kelompok'] .' ) </i></h6>	 
				' ;
	$headers= array( 
		'Jadwal' => array( 'width'=>'20%','style'=>'text-align:center;' ),   
		'Hari' => array( 'width'=>'30%','style'=>'text-align:center;' ),   
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
		'6'=>'Sabtu'
	);
	
	$query 	= "	SELECT * FROM wt_jadwal_kelompok a 
					INNER JOIN wt_jadwal_kerja b 
						ON a.jadwal_kerja_id = b.id
				WHERE a.kelompok_kerja_id = ". (int)$kelompok_kerja_karyawan['kelompok_kerja_id']." 
				ORDER BY hari_id ASC ";
	 
	$result = my_query($query);
	$row = array();
	while($ey = my_fetch_array($result)){
		$editproperty = array(
				'href'=>'index.php?com='.$_GET['com'].'&task=edit_jadwal_eksepsi_karyawan&hari_id='.$ey['hari_id'].'&karyawan_id=' .$kelompok_kerja_karyawan['kelompok_kerja_id'] , 
				'title'=>'Revisi eksepsi'
		);	
		$edit_button = button_icon( 'b_edit.png' , $editproperty  );
		if($eksepsi = get_eksepsi_jadwal($karyawan_id , $ey['hari_id'])){
			$deleteproperty = array(
				'href'=>'index.php?com='.$_GET['com'].'&task=hapus_jadwal_eksepsi_karyawan&hari_id='.$ey['hari_id'].'&karyawan_id=' .$kelompok_kerja_karyawan['kelompok_kerja_id'] , 
				'title'=>'Hapus eksepsi'
			);	
			$delete_button = button_icon( 'b_drop.png' , $deleteproperty  );
			$row[] = array(  
				'jadwal' => '<font color="blue">EX' .'/ Data eksepsi jadwal kerja</font>',  
				'hari' => '<font color="blue">'.$hari[$ey['hari_id']].'</font>',  
				'in' => position_text_align( '<font color="blue">'.date('H:i',strtotime($eksepsi['eksepsi_in'])).'</font>' , 'center'),  
				'out'=> position_text_align('<font color="blue">'. date('H:i',strtotime( $eksepsi['eksepsi_out'])) .'</font>', 'center'),
				'op'=> position_text_align( $delete_button . $edit_button   , 'right')
			);

		}else{
			$row[] = array(  
				'jadwal' => $ey['jadwal_kode'] .'/ '. $ey['nama_jadwal'],  
				'hari' => $hari[$ey['hari_id']],  
				'in' => position_text_align( date('H:i',strtotime($ey['jadwal_in'])) , 'center'),  
				'out'=> position_text_align( date('H:i',strtotime( $ey['jadwal_out'])) , 'center'),
				'op'=> position_text_align( $edit_button   , 'right')
			);
		}
	}
	
	$datas = table_rows($row); 			  
	return $view . table_builder($headers , $datas ,  5 , false    ); 
} 

function get_eksepsi_jadwal($karyawan_id , $hari_id){
	$query = "SELECT * FROM wt_jadwal_kerja_eksepsi 
			WHERE karyawan_id = {$karyawan_id} 
			AND hari_id = {$hari_id} LIMIT 1"; 
	$result = my_query($query);
	if(my_num_rows($result) > 0 ){ 
		return  my_fetch_array($result);
	}
	return false;
}

function get_jadwal_karyawan($hari_id , $karyawan_id){ 
	$kelompok_kerja_karyawan = my_get_data_by_id('wt_kelompok_kerja_karyawan','karyawan_id',$karyawan_id);	
	$kelompok_kerja = my_get_data_by_id('wt_kelompok_kerja','id',$kelompok_kerja_karyawan['kelompok_kerja_id']);
	$jadwal_kerja = my_get_data_by_id('wt_jadwal_kerja', 'id', $kelompok_kerja ['jadwal_kerja_dasar_id'] ); 
	return $jadwal_kerja['jadwal_kode']; 
}

function list_jadwal_karyawan(){

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
		'Nama karyawan' => array( 'width'=>'28%','style'=>'text-align:left;' ), 
		'TMB' => array( 'width'=>'10%','style'=>'text-align:center;' ), 
		'Kelompok kerja' => array( 'width'=>'29%','style'=>'text-align:left;' ), 
		'Sidik jari' => array( 'width'=>'10%','style'=>'text-align:center;' ), 
		'Aksi' => array( 'width'=>'8%','style'=>'text-align:center;' )
	);
	
	if(isset($_GET['key'])){ 
	$query 	= "SELECT a.karyawan_id , a.karyawan_nik , a.nama_karyawan , a.tanggal_bekerja , b.abaikan_finger , c.kode_kelompok , c.nama_kelompok FROM karyawan a 
		LEFT JOIN wt_kelompok_kerja_karyawan b ON a.karyawan_id = b.karyawan_id
		LEFT JOIN wt_kelompok_kerja c ON b.kelompok_kerja_id = c.id
		WHERE a.nama_karyawan LIKE '%{$_GET['key']}%' OR a.karyawan_nik = '{$_GET['key']}' ";
	$result = my_query($query);
	}else{ 	
	$query 	= "SELECT a.karyawan_id , a.karyawan_nik , a.nama_karyawan , a.tanggal_bekerja , b.abaikan_finger , c.kode_kelompok , c.nama_kelompok FROM karyawan a 
		LEFT JOIN wt_kelompok_kerja_karyawan b ON a.karyawan_id = b.karyawan_id
		LEFT JOIN wt_kelompok_kerja c ON b.kelompok_kerja_id = c.id";
	$result = my_query($query);
	}
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
	$opsi_abaikan = array('0'=>'Ya', '1'=>'Tidak');
	$row = array();
	while($ey = my_fetch_array($result)){
		$i++;
		$editproperty = array(
			'href'=>'index.php?com='.$_GET['com'].'&task=edit&karyawan_id=' . $ey['karyawan_id'] , 
			'title'=>'Pindah kelompok'
		);	
		$edit_button = button_icon( 's_passwd.png' , $editproperty  );
 
		$detailproperty = array(
			'href'=>'index.php?com='.$_GET['com'].'&task=detail_jadwal_karyawan&karyawan_id=' . $ey['karyawan_id'] , 
			'title'=>'Detail jadwal seminggu'
		);	
		$jadwal_button = button_icon( 'b_docsql.png' , $detailproperty  );
 
		$row[] = array( 
		'NIK' =>  position_text_align($ey['karyawan_nik'], 'center'),    
		'Nama karyawan' => $ey['nama_karyawan'],  
		'TMB' => position_text_align($ey['tanggal_bekerja'], 'center'),  
		'Kelompok kerja' => ( !is_null($ey['kode_kelompok']) ? $ey['kode_kelompok'].'/ '. $ey['nama_kelompok'] : '&nbsp;' ),  
		'Sidik jari' =>  position_text_align( ( !is_null($ey['kode_kelompok']) ? $opsi_abaikan[$ey['abaikan_finger']] : '&nbsp;' ), 'center'),   
		'op'=> position_text_align( $jadwal_button .' '.$edit_button   , 'center')
		);
	}
	
	$datas = table_rows($row);
	$navigasi = array(
		//'<input class="btn btn-primary" style="float:right;"  type="button" value="Tambah data" onclick="javascript:location.href=\'index.php?com='.$_GET['com'].'&task=edit\'"/>',
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
	return $box.table_builder($headers , $datas ,  7 , false , $paging  ); 
}

function validate_edit_jadwal_karyawan( ){
	$errsubmit = false;
	$err = array();
	 
	 
	if( (int)$_POST['kelompok_kerja_id'] == 0 ){
		$errsubmit =true;
		$err[] = "Kelompok jadwal belum dipilih";
	} 
	if( !isset( $_POST['abaikan_finger'] )  ){
		$errsubmit =true;
		$err[] = "Status sidik jari belum di tentukan";
	} 
	
	if( $errsubmit){
		return $err;
	} 
	return false;
}

function submit_edit_jadwal_karyawan($karyawan_id ){
 
	my_query("DELETE FROM wt_kelompok_kerja_karyawan WHERE karyawan_id = {$karyawan_id}");
	$datas = array(
		'kelompok_kerja_id'=>my_type_data_int($_POST['kelompok_kerja_id']),
		'karyawan_id'=>my_type_data_int( $karyawan_id ),
		'abaikan_finger'=>my_type_data_str( $_POST['abaikan_finger'] ),
	);
	return my_insert_record('wt_kelompok_kerja_karyawan',$datas); 
}