<?php
function get_mesin_finger_by_karyawan_id($karyawan_id){
	$query = "SELECT mesin_id , finger_id FROM mesin_finger_match 
		WHERE  karyawan_id = {$karyawan_id} LIMIT 1";
	$result = my_query($query);
	$row = my_fetch_array($result);
	return $row;

}
function get_realisasi_karyawan($karyawan_id , $tanggal , $jadwal  ){
	$dt = get_mesin_finger_by_karyawan_id($karyawan_id);
	$datas = array();
	//IN 
	list($hr,$mn,$sec) = explode(":",$jadwal['jadwal_in']); 
	
	$starttime =  mktime($hr,$mn,$sec) - (59 * 60 * 1) ; // 59 detik 60 menit dalam 1 jam
	$batas_bawah =  date( 'H:i:s' , $starttime );
		
	$endtime =  mktime($hr,$mn,$sec) + (59 * 60 * 1) ;
	$batas_atas =   date( 'H:i:s' , $endtime );
	$query = "SELECT TIME(datetime_swap) AS jam_datang ,manual FROM log_data_mesin 
		WHERE mesin_id = {$dt['mesin_id']} AND finger_swap_id = {$dt['finger_id']}
			AND DATE(datetime_swap) = '{$tanggal}'
			AND (  datetime_swap  BETWEEN  '{$tanggal} {$batas_bawah}'  AND  '{$tanggal} {$batas_atas}'  )
		ORDER BY datetime_swap ASC LIMIT 1 ";
	 
	$result = my_query($query);
	if(my_num_rows($result) > 0){
		$row = my_fetch_array($result);
		$datas['realisasi_in'] = $row['jam_datang'];
		$datas['manual_in'] = $row['manual'];
		$datang = true;
	}else{
		$datas['realisasi_in'] = null; 
		$datas['manual_in'] = '0';
		$datang = false;
	}
	
	//OUT 
	list($hr,$mn,$sec) = explode(":",$jadwal['jadwal_out']); 
	
	$starttime =  mktime($hr,$mn,$sec) - (59 * 60 * 3) ; // 59 detik 60 menit dalam 3 jam
	$batas_bawah =  date( 'H:i:s' , $starttime );
		
	$endtime =  mktime($hr,$mn,$sec) + (59 * 60 * 3) ;
	$batas_atas =   date( 'H:i:s' , $endtime );
	$query = "SELECT TIME( datetime_swap) AS jam_pulang ,manual FROM log_data_mesin 
		WHERE mesin_id = {$dt['mesin_id']} AND finger_swap_id = {$dt['finger_id']}
			AND DATE(datetime_swap) = '{$tanggal}'
			AND (  datetime_swap  BETWEEN '{$tanggal} {$batas_bawah}'  AND  '{$tanggal} {$batas_atas}'  )
		ORDER BY datetime_swap DESC LIMIT 1 ";
		
	$result = my_query($query);
	if(my_num_rows($result) > 0){
		$row = my_fetch_array($result);
		$datas['realisasi_out'] = $row['jam_pulang'];
		$datas['manual_out'] = $row['manual'];
		$pulang = true;
	}else{
		$datas['realisasi_out'] = null;
		$datas['manual_out'] = '0';
		$pulang = false;
	}
	
	if( $pulang OR $datang ){ 
		$datas['status_hadir'] = $jadwal['status_hadir'];
		$datas['jadwal_kode'] = $jadwal['jadwal_kode'];
	}else{
		$datas['status_hadir'] = 0;
		$datas['jadwal_kode'] = 'XY';
	}
	return $datas;
}

function karyawan_exception($karyawan_id , $hari_id){
	$query = "SELECT * FROM wt_jadwal_kerja_eksepsi 
		WHERE karyawan_id = {$karyawan_id} AND hari_id = {$hari_id} ";
	$result = my_query($query);
	if( $row = my_fetch_array($result)){
		return $row;
	}
	return false;
}

function get_jadwal_karyawan($karyawan_id , $tanggal){

	$hari_id = date('w' , strtotime($tanggal) );
	if($exception = karyawan_exception($karyawan_id , $hari_id)){
		$datas = array();
		$datas['jadwal_kode'] = 'EX';
		$datas['status_hadir'] = $exception['status_hadir'];
		$datas['jadwal_in'] = $exception['eksepsi_in'];
		$datas['jadwal_out'] = $exception['eksepsi_out'];
		return $datas;
	}
	
	$query = "
		SELECT a.jadwal_kode, a.status_hadir , a.jadwal_in , a.jadwal_out FROM wt_jadwal_kerja a 
		INNER JOIN wt_jadwal_kelompok b ON a.id = b.jadwal_kerja_id 
		INNER JOIN wt_kelompok_kerja c ON c.id = b.kelompok_kerja_id 
		INNER JOIN wt_kelompok_kerja_karyawan d ON c.id = d.kelompok_kerja_id 
		WHERE d.karyawan_id  = {$karyawan_id} AND b.hari_id = {$hari_id} ";
	$result = my_query($query);
	if( $row = my_fetch_array($result)){
		return $row;
	}
	return false;
}

function get_ijin_karyawan($karyawan_id , $tanggal){
	$query = "
	SELECT b.tipe_code , b.status_hadir FROM wt_implementasi_ijin a
		INNER JOIN wt_tipe_ijin b ON a.ijin_tipe_id = b.tipe_id 
	WHERE a.karyawan_id = {$karyawan_id}  
		AND  tanggal_ijin = '{$tanggal}'
	LIMIT 1 ";
	$result = my_query($query);
	if( $row = my_fetch_array($result)){
		return $row;
	}
	return false;
}

function get_cuti_karyawan($karyawan_id , $tanggal){
	$query = "
	SELECT b.tipe_code , b.status_hadir FROM wt_implementasi_cuti a
		INNER JOIN wt_tipe_cuti b ON a.cuti_tipe_id = b.id 
	WHERE a.karyawan_id = {$karyawan_id}  
		AND DATE('{$tanggal}') BETWEEN tanggal_mulai AND tanggal_berakhir
	LIMIT 1 ";
	$result = my_query($query);
	if( $row = my_fetch_array($result)){
		return $row;
	}
	return false;
}

function trap_data_status_kehadiran($karyawan_id , $tanggal   ){
		
	//GET CUTI
	if( $cuti = get_cuti_karyawan($karyawan_id , $tanggal)){
		status_kehadiran_karyawan( $cuti['status_hadir'] , $cuti['tipe_code'] ,$karyawan_id , $tanggal );
		
	}elseif( $ijin = get_ijin_karyawan($karyawan_id , $tanggal)){
		status_kehadiran_karyawan( $ijin['status_hadir'] , $ijin['tipe_code']  ,$karyawan_id , $tanggal );
	
	}else{
		$status_finger_diperiksa = get_status_finger_karyawan($karyawan_id);
		
		$jadwal = get_jadwal_karyawan($karyawan_id , $tanggal );
		if($status_finger_diperiksa){
			$realisasi = get_realisasi_karyawan($karyawan_id , $tanggal, $jadwal );
			status_kehadiran_karyawan( $realisasi['status_hadir'] , $realisasi['jadwal_kode'] ,
											$karyawan_id , $tanggal , 
											$realisasi['realisasi_in'] , $realisasi['realisasi_out'] , 
												$realisasi['manual_in'] , $realisasi['manual_out'] );
		
		}else{  
			status_kehadiran_karyawan( $jadwal['status_hadir'] , $jadwal['jadwal_kode'] ,$karyawan_id , $tanggal , $jadwal['jadwal_in'] , $jadwal['jadwal_out'] );
		
		}
	}

	return true;
}

function get_status_finger_karyawan($karyawan_id){
	$query = "SELECT * FROM wt_kelompok_kerja_karyawan 	
		WHERE karyawan_id = {$karyawan_id} 
		AND abaikan_finger = '0'";
	$result = my_query($query);
	
	if( my_num_rows($result) > 0){
		return true;
		
	}
	return false;
}

function status_kehadiran_karyawan( $status_hadir , $kode ,$karyawan_id , $tanggal , $finger_in = NULL , $finger_out = NULL ,$manual_in = '0' , $manual_out = '0'){

	$datas = array(
		'karyawan_id' => my_type_data_int($karyawan_id),
		'date_implementation' => my_type_data_str($tanggal ),
		'kode_presensi' => my_type_data_str( $kode ),
		'status_hadir' => my_type_data_str($status_hadir ),
		'finger_in' => my_type_data_str($finger_in ),
		'finger_out' => my_type_data_str($finger_out ),
		'manual_in' => my_type_data_str($manual_in ),
		'manual_out' => my_type_data_str($manual_out ),
	);
	 
	return my_insert_record( 'wt_temp_kehadiran' , $datas );

}

function proses_kalkulasi_waktu_kerja_karyawan($karyawan_id  ){
	 
	$current_periode_detail = get_current_periode_waktu_kerja();
	$dates = list_kalender($current_periode_detail['date_start'] , $current_periode_detail['date_end'] );
	foreach($dates as $tanggal){
		trap_data_status_kehadiran($karyawan_id , $tanggal );
	}
	trap_data_lembur_karyawan($current_periode_detail['date_start'], $current_periode_detail['date_end'] , $karyawan_id);
	return true;
}

function hitung_waktu_lembur_karyawan($date_start , $date_end , $karyawan_id){
	$query = "SELECT 
			SUM(durasi_jam_lembur) as total_jam_lembur , 
			SUM(durasi_jam_hitung) as total_jam_hitung  
		FROM wt_overtime WHERE karyawan_id = {$karyawan_id} 
			AND (  implement_date BETWEEN DATE('{$date_start}') AND DATE('{$date_end }') )";
	$result = my_query($query);
	if(my_num_rows($result) > 0){
		$row = my_fetch_array($result);
		return  $row;
	}
	return 0;
}

function trap_data_lembur_karyawan($date_start , $date_end , $karyawan_id){
	$data_lembur = hitung_waktu_lembur_karyawan($date_start , $date_end , $karyawan_id);
	if(!$data_lembur) return false;
	$datas = array(
		'karyawan_id' => my_type_data_int($karyawan_id),
		'jam_hitung' => my_type_data_str((double)$data_lembur['total_jam_hitung']),
		'jam_lembur' => my_type_data_str((double)$data_lembur['total_jam_lembur']),
	); 
	
	return my_insert_record('wt_temp_lembur' , $datas);
}



function kalkulasi_waktu_kerja(){

	my_query('TRUNCATE wt_temp_kehadiran'); 
	$query 	= "SELECT * FROM karyawan ";
	$result = my_query($query);
	$i = 1;
	$p = new ProgressBar();
	echo '<center><div style="width: 400px;">';
	$p->render();
	echo '</div></center>';
	$size = my_num_rows($result);
	while($row = my_fetch_array($result)){
		proses_kalkulasi_waktu_kerja_karyawan($row['karyawan_id'] ); 
		$p->setProgressBarProgress($i*100/$size); 
		$i++;
	}
	$p->setProgressBarProgress(100);
	return $i;
}

function get_current_periode_waktu_kerja(){
	$query = "SELECT * FROM wt_periode WHERE status_aktif = '1' LIMIT 1";
	$result = my_query($query);
	$row = my_fetch_array($result);
	return $row;
}

function detail_list_kalendar_proses( ){
	$current_periode_detail = get_current_periode_waktu_kerja();
	$dates = list_kalender($current_periode_detail['date_start'] , $current_periode_detail['date_end'] );
	$headers= array( 
		'Tanggal' => array( 'width'=>'15%','style'=>'text-align:center;' ), 
		'Kehadiran' => array( 'width'=>'75%','style'=>'text-align:center;' ) ,
		'%'=>array( 'width'=>'10%','style'=>'text-align:center;' ) ,
	);
	
	$rows = array();
	foreach($dates as $tanggal){
		$data_hitung = get_total_absen_by_date($tanggal); 
		if($data_hitung['jumlah_proses']> 0)$persen_hadir = round( ( $data_hitung['jumlah_hadir'] / $data_hitung['jumlah_proses'] ) * 100 , 2 );
		else $persen_hadir = 0;
		$rows[] = array(
			'tanggal'=> position_text_align( $tanggal ,'center'),
			'chart'=> '<img src="index.php?com=graph&task=bar_daily&data='.$persen_hadir .'" />',
			'persen'=> position_text_align( $persen_hadir. ' %', 'right')
		);
	}
	$datas = table_rows($rows);
	$navigasi = array(
		'<input class="btn btn-primary" style="float:right;"  type="button" value="KALKULASI" onclick="javascript:location.href=\'index.php?com='.$_GET['com'].'&task=kalkulasi\'"/>',
		 
	);
	$box = header_box( '&nbsp;' , $navigasi );
	return $box . table_builder($headers , $datas ,  2 , false   );
}

function get_total_absen_by_date($date){
	$query = "SELECT  COUNT(*) AS jumlah_proses , SUM(status_hadir)  AS jumlah_hadir  FROM wt_temp_kehadiran WHERE date_implementation = '{$date}'" ;
	$result = my_query($query);
	$row = my_fetch_array($result);
	return $row  ;
}

function list_wt_periode(){
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
		'Awal' => array( 'width'=>'20%','style'=>'text-align:center;' ), 
		'Akhir' => array( 'width'=>'20%','style'=>'text-align:center;' ), 
		'Kehadiran' => array( 'width'=>'25%','style'=>'text-align:center;' ), 
		'Status' => array( 'width'=>'25%','style'=>'text-align:center;' ), 
		'Aksi' => array( 'width'=>'10%','style'=>'text-align:center;' ) 
	);

	
	
	$query 	= "SELECT * FROM wt_periode ORDER BY id DESC ";
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
		$processproperty = array(
				'href'=>'index.php?com='.$_GET['com'].'&task=detail&id=0', 
				'title'=>'Kalkulasi'
		);	
		$process_button = button_icon( 's_process.png' , $processproperty  );
 
		$detailproperty = array(
				'href'=>'index.php?com='.$_GET['com'].'&task=detail&id='.$ey['id'] , 
				'title'=>'Rincian harian'
		);	
		$detail_button = button_icon( 's_process.png' , $detailproperty  );
 
		if($ey['status_aktif'] == '1'){
			$hadir = get_persen_hadir_current($ey['id']);
			$action_button = $process_button ;
			$status =  'Proses' ;
		}else{
			$hadir = get_log_persen_hadir($ey['id']);
			$action_button = $detail_button;
			$status = 'Lewat';
		}	
		$row[] = array( 
			'Awal' => position_text_align( date('d-m-Y' , strtotime( $ey['date_start'] ) ), 'center'), 
			'Akhir' => position_text_align( date('d-m-Y' , strtotime( $ey['date_end'] ) ),  'center'),
			'Persen' =>position_text_align( ( $hadir * 100 ).' %', 'center'),  
			'Status' =>position_text_align(  $status, 'center'),  
			'op'=> position_text_align( $action_button , 'right')
		);
	}
	
	$datas = table_rows($row);
	
	$paging = $kgPagerOBJ ->showPaging();
	return table_builder($headers , $datas ,  4 , false , $paging  ); 
}

function get_log_persen_hadir($id){
	return 0;
}

function get_persen_hadir_current($id){ 
	$query = "SELECT DATEDIFF(date_start , date_end) AS total_hari , date_end FROM wt_periode WHERE id = {$id}";
	$result = my_query($query);
	$row = my_fetch_array($result);
	$jumlah_hari = $row['total_hari'];
	$batas_tanggal = $row['date_end'];
	
	//JUMLAH KARYAWAN TOTAL
	$query_karyawan = "SELECT COUNT(*) AS jumlah_karyawan FROM karyawan WHERE tanggal_bekerja < '{$batas_tanggal}'";
	$result_karyawan = my_query($query_karyawan);
	$row_karyawan = my_fetch_array($result_karyawan);
	$jumlah_karyawan = $row_karyawan['jumlah_karyawan'];
	
	$hari_total = $jumlah_hari * $jumlah_karyawan;
	
	//JUMLAH ABSEN
	$query_tak_hadir = "SELECT COUNT(*) AS jumlah_tidak_hadir FROM wt_temp_kehadiran 
		WHERE status_hadir = 0";
	$result_tak_hadir = my_query($query_tak_hadir);
	$row_tak_hadir = my_fetch_array($result_tak_hadir);
	$jumlah_tak_hadir = $row_tak_hadir['jumlah_tidak_hadir'];
	
	$ketidakhadiran = ( $hari_total - $jumlah_tak_hadir ) / $hari_total;
	return round( $ketidakhadiran , 4 );
}

function submit_wt_periode($id){
	 
	$datas = array();  
	$datas['date_start']	=  my_type_data_str($_POST['date_start']);
	$datas['date_end']		=  my_type_data_str($_POST['date_end']);
	$datas['created_on']	= my_type_data_function('NOW()');
	$datas['updated_on']	=  my_type_data_str($_POST['updated_on']);
	$datas['version'] 		= my_type_data_str(0);
	$datas['user_updated_id']	= my_type_data_int($_SESSION['user_id']);  
	$datas['status_aktif']	=  my_type_data_str($_POST['status_aktif']);
	 
	if($id > 0){
		return my_update_record( 'wt_periode' , 'wt_periode_id' , $id , $datas );
	}
	return my_insert_record( 'wt_periode' , $datas );
}

function form_wt_periode_validate(){
	return false;
}
	
	
function edit_wt_periode($id){
	
	my_set_file_js(
		array(
			'assets/jquery/combomulti/jquery.chainedSelects.js',
			'assets/js/calendar/calendarDateInput.js' 
		)
	);
	$view = form_header( "form_wt_periode" , "form_wt_periode"  );
	$fields = my_get_data_by_id('wt_periode','wt_periode_id', $id);


	$ids =  array( );
	$query = "SELECT id , id_label FROM id";	
	$result = my_query($query);
	while($row_id = my_fetch_array($result)){
		$ids[$row_id['id']] = $row_id['id_label'];
	}
	$level = array(
		'name'=>'id',
		'value'=>( isset($_POST['id']) ? $_POST['id'] : $fields['id']) ,
	);
	$form_id = form_radiobutton($id , $ids);
	$view .= form_field_display(  $form_id   , "Id"    ); 
	

	$fdate_start = date('Y-m-d');
	if($fields){
		list($yyyydate_start , $mmdate_start, $dddate_start ) = explode("-" ,$fields['date_start'] );
		$fdate_start = $dddate_start.'-'.$mmdate_start.'-'.$yyyydate_start;
	}
	
	$date_start = array(
			'name'=>'date_start',
			'value'=>(isset($_POST['date_start'])? $_POST['date_start'] : $fdate_start),
			'id'=>'date_start',
			'type'=>'textfield',
			'size'=>'45'
		);
	$form_date_start = form_calendar($date_start);
	$view .= form_field_display( $form_date_start  , "Date_start" );
	

	$fdate_end = date('Y-m-d');
	if($fields){
		list($yyyydate_end , $mmdate_end, $dddate_end ) = explode("-" ,$fields['date_end'] );
		$fdate_end = $dddate_end.'-'.$mmdate_end.'-'.$yyyydate_end;
	}
	
	$date_end = array(
			'name'=>'date_end',
			'value'=>(isset($_POST['date_end'])? $_POST['date_end'] : $fdate_end),
			'id'=>'date_end',
			'type'=>'textfield',
			'size'=>'45'
		);
	$form_date_end = form_calendar($date_end);
	$view .= form_field_display( $form_date_end  , "Date_end" );
	

	
	$status_aktif = array(
			'name'=>'status_aktif',
			'value'=>(isset($_POST['status_aktif'])? $_POST['status_aktif'] : $fields['status_aktif']),
			'id'=>'status_aktif',
			'type'=>'textfield' 
		);
	$form_status_aktif = form_dynamic($status_aktif);
	$view .= form_field_display( $form_status_aktif  , "Status_aktif"  );
	
		 
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