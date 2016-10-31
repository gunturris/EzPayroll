<?php

function list_wt_overtime(){
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
		'Nomor' => array( 'width'=>'15%','style'=>'text-align:center;' ), 
		'Karyawan' => array( 'width'=>'35%','style'=>'text-align:left;' ), 
		'Tanggal' => array( 'width'=>'10%','style'=>'text-align:center;' ), 
		'Kat. Hari' => array( 'width'=>'15%','style'=>'text-align:center;' ), 
		'Durasi' => array( 'width'=>'15%','style'=>'text-align:center;' ), 
		'Aksi' => array( 'width'=>'10%','style'=>'text-align:center;' ), 
		
	);

	
	if(isset($_GET['key'])){
		$query 	= "SELECT * FROM wt_overtime a 
		INNER JOIN karyawan b ON a.karyawan_id = b.karyawan_id
		WHERE b.nama_karyawan LIKE '%{$_GET['key']}%' OR b.karyawan_nik = '{$_GET['key']}'
		ORDER BY a.id DESC ";
	}else{
		$query 	= "SELECT * FROM wt_overtime  ORDER BY id DESC "; 
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
				'href'=>'index.php?com='.$_GET['com'].'&task=edit&id=' . $ey['id'] , 
				'title'=>'Edit'
		);	
		$edit_button = button_icon( 'b_edit.png' , $editproperty  );

		$deleteproperty = array(
			'href'=>'javascript:confirmDelete('.$ey['id'].');',
			'title'=>'Delete', 
		);
		$delete_button = button_icon( 'b_drop.png' , $deleteproperty  );
		$karyawan = my_get_data_by_id('karyawan','karyawan_id', $ey['karyawan_id']);
		$hari_overtime = my_get_data_by_id('wt_jenis_hari_overtime','jenis_id',$ey['jenis_hari']);
		$row[] = array( 
		'Nomor' 	=>  position_text_align($ey['overtime_number'] , 'center'),  
		'Karyawan' 	=> $karyawan['karyawan_nik'].'/ '.$karyawan['nama_karyawan'],  
		'Tanggal' 	=>  position_text_align($ey['implement_date'],  'center'),
		'Kat. Hari' => $hari_overtime['label'],  
		'Durasi' 	=>  position_text_align(date( 'H:i',strtotime( $ey['start_time'])).' s/d '.date( 'H:i',strtotime( $ey['end_time'])),   'center'),
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
		<input type="text" class="form-control" name="key" value="'.( isset( $_GET['key'] ) ? $_GET['key'] : '' ).'">
		<span class="input-group-btn">
			<button class="btn btn-default" type="submit"><i class="fa fa-search"></i>
			</button>
		</span>
	
	</div></form>';
	$box = header_box( $form_Search, $navigasi ); 
	$paging = $kgPagerOBJ ->showPaging();
	return $box.table_builder($headers , $datas ,  6 , false , $paging  ); 
}

function get_total_jam_lembur( $start , $end ){
	$timestart 	= strtotime($start);
	$timeend 	=  strtotime($end);
	$selisih = $timeend -$timestart;
	return $selisih / (3600);	
}

function get_total_jam_hitung( $jam_lembur , $jenis_id ){
	$jam_lembur_fix = (int) floor( $jam_lembur ); 
	$hourpay =  $jam_lembur - $jam_lembur_fix;
	for($i = 1; $i <= $jam_lembur_fix; $i++ ){ 
		$faktor = get_jenis_hari_dan_jam_ke($jenis_id , $i);
		$hourpay += $faktor;
	}
	return $hourpay;
}

function get_jenis_hari_dan_jam_ke($jenis_id , $jam_ke){
	$query = "SELECT faktor_hitung FROM wt_jenis_hari_overtime_hitung 
		WHERE jenis_id = {$jenis_id} 
		AND (  {$jam_ke} BETWEEN jam_awal AND jam_akhir ) ORDER BY jam_awal ASC LIMIT 1";
	$result = my_query($query);
	$row = my_fetch_array($result);
	return $row['faktor_hitung'];
}

function submit_wt_overtime($id){
	$karyawan_id = get_karyawan_id_by_nik_name($_POST['karyawan_id']);
	 
	$datas = array();  
	$datas['karyawan_id']	=  my_type_data_str($karyawan_id);
	$datas['overtime_number']	=  my_type_data_str($_POST['overtime_number']);
	$datas['implement_date']	=  my_type_data_str($_POST['implement_date']);
	$datas['start_time']	=  my_type_data_str($_POST['start_time']);
	$datas['end_time']	=  my_type_data_str($_POST['end_time']);
	$datas['task_info']	=  my_type_data_str($_POST['task_info']);
	$datas['jenis_hari']	=  my_type_data_str($_POST['jenis_hari']);
	
	$jam_lembur = get_total_jam_lembur( $_POST['start_time'] , $_POST['end_time'] );
	$datas['durasi_jam_lembur']	=  my_type_data_str( $jam_lembur );
	
	$durasi_jam_hitung = get_total_jam_hitung( $jam_lembur , (int) $_POST['jenis_hari']);
	$datas['durasi_jam_hitung']	=  my_type_data_str($durasi_jam_hitung);
	 
	if($id > 0){
		$datas['version']	=  my_type_data_function('( version + 1 )');
		$datas['updated_on']	=  my_type_data_function('NOW()');
		$datas['user_updated_id']	=  my_type_data_int($_SESSION['user_id']);
		return my_update_record( 'wt_overtime' , 'id' , $id , $datas );
	}
	$datas['created_on']	= my_type_data_function('NOW()'); 
	$datas['version'] = my_type_data_str(0);
	return my_insert_record( 'wt_overtime' , $datas );
}

function form_wt_overtime_validate(){
	$errsubmit = false;
	$err = array();
	 
	$karyawan_id = get_karyawan_id_by_nik_name($_POST['karyawan_id']);
	if($karyawan_id == 0 ){
		$errsubmit =true;
		$err[] = "Karyawan yang dimaksud belum benar";
	} 
	
	if((int) $_POST['jenis_hari'] == 0){
		$errsubmit =true;
		$err[] = "Jenis hari pelaksanaan lembur belum di pilih";
	
	}
	if( $errsubmit){
		return $err;
	} 
	return false;
}
	
	
function edit_wt_overtime($id){
	my_set_code_js('  
		function findValue(li) {
			if( li == null ) return alert("No match!"); 
			if( !!li.extra ) var sValue = li.extra[0]; 
			else var sValue = li.selectValue;
		}

		function selectItem(li) {
			findValue(li);
		}

		function formatItem(row) {
			return   row[0] ;
		}

		function lookupAjax(){
			var oSuggest = $("#nama_karyawan")[0].autocompleter;
			oSuggest.findValue(); 
			return false;
		}
		$(document).ready(function() {
			$("#nama_karyawan").autocomplete(
				"autocomplete_daftar_nama_karyawan.php",
				{
					delay:10,
					minChars:2,
					matchSubset:1,
					matchContains:1,
					cacheLength:5,
					onItemSelect:selectItem,
					onFindValue:findValue,
					formatItem:formatItem,
					autoFill:true
				}
			);
			 
		}); 
	');	
	 
	my_set_file_js(
		array(
			'assets/jquery/autocomplete/jquery.autocomplete.js'  
		)
	);
	my_set_file_css(
		array(
				'assets/jquery/autocomplete/jquery.autocomplete.css' 
			)
	); 
	my_set_file_js(
		array(
			'assets/jquery/combomulti/jquery.chainedSelects.js',
			'assets/js/calendar/calendarDateInput.js' 
		)
	);
	$view = form_header( "form_wt_overtime" , "form_wt_overtime"  );
	$fields = my_get_data_by_id('wt_overtime','id', $id);

 
	$overtime_number = array(
			'name'=>'overtime_number',
			'value'=>(isset($_POST['overtime_number'])? $_POST['overtime_number'] : $fields['overtime_number']),
			'id'=>'overtime_number',
			'type'=>'textfield' 
		);
	$form_overtime_number = form_dynamic($overtime_number);
	$view .= form_field_display( $form_overtime_number  , "Nomor lembur"  );
	
	

	$karyawan_nik = '';

	if($fields){
		$karyawan = my_get_data_by_id('karyawan' ,'karyawan_id' ,$fields['karyawan_id']);
		$karyawan_nik = $karyawan['karyawan_nik'].'/'.$karyawan['nama_karyawan'];
	}
	

	$karyawan_id  = array(
		'id'=>'nama_karyawan',
		'type'=>'textfield',
		'name'=>'karyawan_id',
		'value'=>( isset($_POST['karyawan_id']) ? $_POST['karyawan_id'] :   $karyawan_nik) ,
	);
	$form_karyawan_id = form_autocomplete($karyawan_id  );
	$view .= form_field_display(  $form_karyawan_id   , "Karyawan"    ); 
	

	$fimplement_date = date('Y-m-d');
	if($fields){
		list($yyyyimplement_date , $mmimplement_date, $ddimplement_date ) = explode("-" ,$fields['implement_date'] );
		$fimplement_date = $fields['implement_date'];
	}
	
	$implement_date = array(
			'name'=>'implement_date',
			'value'=>(isset($_POST['implement_date'])? $_POST['implement_date'] : $fimplement_date),
			'id'=>'implement_date',
			'type'=>'textfield',
			'size'=>'45'
		);
	$form_implement_date = form_calendar($implement_date);
	$view .= form_field_display( $form_implement_date  , "Tanggal pelaksanaan" );
	

	
	$start_time = array(
			'name'=>'start_time',
			'value'=>(isset($_POST['start_time'])? $_POST['start_time'] : date('H:i',strtotime( $fields['start_time'])) ),
			'id'=>'start_time',
			'type'=>'textfield' ,
			'style'=>'max-width:80px',
			'placeholder'=>'HH:MM'
		);
	$form_start_time = form_dynamic($start_time);
	$view .= form_field_display( $form_start_time  , "Jam mulai"  );
	
	

	
	$end_time = array(
			'name'=>'end_time',
			'value'=>(isset($_POST['end_time'])? $_POST['end_time'] : date('H:i',strtotime( $fields['end_time'])) ),
			'id'=>'end_time',
			'type'=>'textfield',
			'style'=>'max-width:80px',
			'placeholder'=>'HH:MM' 
		);
	$form_end_time = form_dynamic($end_time);
	$view .= form_field_display( $form_end_time  , "Jam selesai"  ); 
	$task_info = array(
			'name'=>'task_info',
			'value'=>(isset($_POST['task_info'])? $_POST['task_info'] : $fields['task_info']),
			'id'=>'task_info',
			'cols'=>'35' ,
			'rows'=>'4' 
		);
	$form_task_info = form_textarea($task_info);
	$view .= form_field_display( $form_task_info  , "Informasi tugas"  );
	 
	
	$qjenis = "SELECT * FROM  wt_jenis_hari_overtime ORDER BY jenis_id ASC";
	$rjenis = my_query($qjenis);
	$opsi_hari = array() ;
	while( $row = my_fetch_array($rjenis)){
		$opsi_hari[$row['jenis_id']] = $row['label']; 
	}
	$jenis_hari = array(
			'name'=>'jenis_hari',
			'value'=>(isset($_POST['jenis_hari'])? $_POST['jenis_hari'] : $fields['jenis_hari']),
			'id'=>'jenis_hari',  
		);
	$form_jenis_hari = form_dropdown($jenis_hari ,$opsi_hari );
	$view .= form_field_display( $form_jenis_hari  , "Jenis hari kerja"  );
	
		 
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
 
	return $view.'<br/><br/>';
} 
?>