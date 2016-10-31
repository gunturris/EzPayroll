<?php 
function list_kehadiran_karyawan(){
	$datas = get_current_periode_waktu_kerja();
	$headers = array();
	$headers['No']  = array( 'width'=>'5%','style'=>'text-align:center;' );
	$headers['Karyawan'] =  array( 'width'=>'35%','style'=>'text-align:center;' );
	$headers['Chart'] =  array( 'width'=>'35%','style'=>'text-align:center;' );
	$headers['Kehadiran'] =  array( 'width'=>'10%','style'=>'text-align:center;' );
	$headers['Tidak hadir'] =  array( 'width'=>'10%','style'=>'text-align:center;' );
	$headers[' '] =  array( 'width'=>'5%','style'=>'text-align:center;' );
	
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
		
		$detailproperty = array(
				'href'=>'index.php?com='.$_GET['com'].'&task=detail_hadir&id=' . $ey['karyawan_id'] , 
				'title'=>'Edit'
		);	 
		$detail_button = button_icon( 'b_props.png' , $detailproperty  ); 
		
		$kehadiran = get_total_absen_karyawan_proses($ey['karyawan_id']);
		$tidak_hadir = $kehadiran['jumlah_proses'] - $kehadiran['jumlah_hadir'];
		$row[] = array(  
				'no' => $i,  
				'karyawan' =>   $ey['karyawan_nik'] .'/ '. $ey['nama_karyawan'] ,  
				'chart' => position_text_align(   '<img src="index.php?com=graph&task=bar_kehadiran_karyawan&data='.round(  100 *$kehadiran['jumlah_hadir']	/ $kehadiran['jumlah_proses'] , 2 ) .'" />' , 'center'),  
				'hadir'=> position_text_align( $kehadiran['jumlah_hadir'] .' hari', 'center'),
				'tidak'=> position_text_align( $tidak_hadir .' hari' , 'center'),
				'op'=> position_text_align( $detail_button  , 'center')
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
	return $box.table_builder($headers , $datas ,  6 , false , $paging  ); 
}	 


function get_total_absen_karyawan_proses($karyawan_id  ){
	$query = "SELECT  COUNT(*) AS jumlah_proses , SUM(status_hadir)  AS jumlah_hadir  
		FROM wt_temp_kehadiran WHERE karyawan_id = {$karyawan_id}  " ;
	$result = my_query($query);
	$row = my_fetch_array($result);
	return $row  ;
}

function get_kode_status($karyawan_id , $tanggal){
	$query = "SELECT kode_presensi FROM wt_temp_kehadiran 
		WHERE karyawan_id = {$karyawan_id} AND date_implementation = '{$tanggal}' 
		LIMIT 1";
	$result = my_query($query);
	$row = my_fetch_array($result);
	return $row['kode_presensi'];
}


function detail_kehadiran($karyawan_id = 0){ 
	my_set_code_js(" 
		function check_manual_in( jadwal_karyawan , tanggal , id , status   ){
			$.get( 'revisi_finger.php' , 
				{ jadwal: jadwal_karyawan , date: tanggal, karyawan_id: id , status_check: status }, 
				function( data ) {
					$('#'+status+'_'+id+'_'+tanggal).replaceWith('<span id=\"'+status+'_'+id+'_'+tanggal+'\">'+data+'</span>');
				}
			);  
		}
	");
	$karyawan = my_get_data_by_id( 'karyawan' ,'karyawan_id' , $karyawan_id);
	$karyawan_status = my_get_data_by_id( 'karyawan_status' ,'karyawan_status_id' , $karyawan['karyawan_status_id'] );
	$karyawan_gol_jab = my_get_data_by_id( 'karyawan_gol_jab' ,'karyawan_gol_jab_id' , $karyawan['karyawan_gol_jab_id'] );
	 
	$view = '  <h4 id="grid-column-ordering">'.$karyawan['karyawan_nik'].' / '.$karyawan['nama_karyawan'].'</h3>
				<h5>'.$karyawan_status['karyawan_status_label'].' ('.$karyawan_gol_jab['karyawan_gol_jab_label'].') </h5>	
				'.( trim($karyawan['alamat']) <> '' ? '<p><b>Alamat</b><br/><i>'.$karyawan['alamat'].'<br/> '.$karyawan['alamat_kota'].'</i></p>' : '').'
				' ;
	$view .= kehadiran_detail_view($karyawan);
	return $view;
}

function kehadiran_detail_view($karyawan){
	$view = '<div style="padding-right:2px;float:left;width:50%;">'.
	list_half_periode('2015-04-01' , '2015-04-15' , $karyawan)
	.'</div>';
	$view .= '<div style="padding-left:2px;float:right;width:50%;">'.
	list_half_periode('2015-04-16' , '2015-04-30' , $karyawan)
	.'</div>';
	$view .= '<div style="clear:both"></div><br />&nbsp;<br />&nbsp;';
	return $view;
}

function list_half_periode($datestart , $enddate , $karyawan){

	$headers = array();
	$headers['Hari']  		= array( 'width'=>'20%','style'=>'text-align:center;' );
	$headers['Tanggal'] 	=  array( 'width'=>'20%','style'=>'text-align:center;' ); 
	$headers['Jdw'] 		=  array( 'width'=>'7%','style'=>'text-align:center;' );
	$headers['Rls'] 		=  array( 'width'=>'7%','style'=>'text-align:center;' );
	$headers['Datang'] 		=  array( 'width'=>'10%','style'=>'text-align:center;' );
	$headers['Pulang'] 		=  array( 'width'=>'10%','style'=>'text-align:center;' );
	$headers['IN'] 			=  array( 'width'=>'8%','style'=>'text-align:center;' );
	$headers['OU'] 			=  array( 'width'=>'8%','style'=>'text-align:center;' );

	$row = array();
	$tanggals = list_kalender($datestart , $enddate );
	$hari = array(
		'0'=>'Minggu',
		'1'=>'Senin',
		'2'=>'Selasa',
		'3'=>'Rabu',
		'4'=>'Kamis',
		'5'=>'Jumat',
		'6'=>'Sabtu'
	);
	
	foreach($tanggals as $tanggal){
		$hari_id = date('w' , strtotime( $tanggal));
		
		$jadwal_karyawan = get_jadwal_karyawan_info($hari_id , $karyawan['karyawan_id']); 
		$realisasi = get_realisasi_karyawan_info($karyawan['karyawan_id'] );
		$form_check_in = '<input '.($realisasi[$tanggal]['manual_in'] == '1' ? 'checked="checked" ': '').' onchange="javascript:check_manual_in(\''.$jadwal_karyawan.'\' , \''.$tanggal.'\','.$karyawan['karyawan_id'].' , \'in\' )" type="checkbox" name="manual[\'karyawan_id\'][{$tanggal}]" />';
		$form_check_out = ' <input '.($realisasi[$tanggal]['manual_out'] == '1' ? 'checked="checked" ': '').'onchange="javascript:check_manual_in(\''.$jadwal_karyawan.'\' , \''.$tanggal.'\','.$karyawan['karyawan_id'].' , \'out\'  )" type="checkbox" name="manual[\'karyawan_id\'][{$tanggal}]" />';
		
		if($realisasi[$tanggal]['status_hadir'] == '0'){
			$row[] = array(
				'hari'=>  '<font color="red">'. $hari[$hari_id] .'</font>' ,
				'tanggal'=> position_text_align(   '<font color="red">'. $tanggal .'</font>' , 'center'),
				'jadwal'=>position_text_align(  '<font color="red">'. $jadwal_karyawan .'</font>' , 'center'),
				'realisasi' =>position_text_align(  '<font color="red">'.  $realisasi[$tanggal]['date'] .'</font>', 'center'),
				'datang' => position_text_align(  '<font color="red"><span id="in_'.$karyawan['karyawan_id'].'_'.$tanggal.'">'. date('H:i',strtotime( $realisasi[$tanggal]['datang'] )) .'</span></font>', 'center'),
				'pulang' => position_text_align( '<font color="red"><span id="out_'.$karyawan['karyawan_id'].'_'.$tanggal.'">'. date('H:i',strtotime( $realisasi[$tanggal]['pulang'] )).'</span></font>', 'center'),
				'in' => position_text_align( $form_check_in , 'center')  ,
				'out' => position_text_align( $form_check_out , 'center') 
			);
		}else{
			$row[] = array(
				'hari'=>  $hari[$hari_id] ,
				'tanggal'=> position_text_align(  $tanggal , 'center'),
				'jadwal'=>position_text_align( $jadwal_karyawan , 'center'),
				'realisasi' =>position_text_align(  $realisasi[$tanggal]['date'], 'center'),
				'datang' => position_text_align( date('H:i',strtotime( $realisasi[$tanggal]['datang'] )), 'center'),
				'pulang' => position_text_align( date('H:i',strtotime( $realisasi[$tanggal]['pulang'] )), 'center'),
				'in' => position_text_align( '&nbsp;' , 'center')  ,
				'out' => position_text_align( '&nbsp;' , 'center') 
			);
		} 	
	}
	$datas = table_rows($row);
	return table_builder($headers , $datas ,  6 , false    ); 
}

function get_realisasi_karyawan_info($karyawan_id  ){
	$query = "SELECT * FROM wt_temp_kehadiran WHERE karyawan_id = {$karyawan_id}";
	$result = my_query($query);
	$datas = array();
	while($row = my_fetch_array($result)){
		$datas[$row['date_implementation']]['status_hadir'] = $row['status_hadir'];
		$datas[$row['date_implementation']]['date'] = $row['kode_presensi'];
		$datas[$row['date_implementation']]['datang'] = $row['finger_in'];
		$datas[$row['date_implementation']]['pulang'] = $row['finger_out'];
		$datas[$row['date_implementation']]['manual_in'] = $row['manual_in'];
		$datas[$row['date_implementation']]['manual_out'] = $row['manual_out'];
	}
	return $datas;
}
 
function get_jadwal_karyawan_info($hari_id , $karyawan_id){ 
	$kelompok_kerja_karyawan = my_get_data_by_id('wt_kelompok_kerja_karyawan','karyawan_id',$karyawan_id);	 
	$jadwal_kelompok_query = "SELECT jadwal_kerja_id FROM wt_jadwal_kelompok 
		WHERE hari_id = {$hari_id} AND kelompok_kerja_id = {$kelompok_kerja_karyawan['kelompok_kerja_id']}";
	$result = my_query($jadwal_kelompok_query);
	if(my_num_rows($result) > 0){ 
		$row= my_fetch_array($result);
		$jadwal_kerja  = my_get_data_by_id('wt_jadwal_kerja' ,'id' , $row['jadwal_kerja_id'] );
		return $jadwal_kerja['jadwal_kode'];
	}
	return false; 
}



 