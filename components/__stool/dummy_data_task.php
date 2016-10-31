<?php

function entry_data_task($i, $label){
	if($i < 15)
		$date = rand(1,4);
	elseif($i < 23)
		$date = rand(5,13);
	elseif($i < 39)
		$date = rand(14,19);
	elseif($i < 55)
		$date = rand(20,25);
	else
		$date = rand(26,30); 
	
	if($date > 30){
		$date_target = rand(1,3);
		$tanggal_target = '2014-02-'.sprintf('%02d' , $date_target);
	}elseif($date > 26){
		$date_target = $date + rand(2);
		$tanggal_target = '2014-01-'.sprintf('%02d' , $date_target);
	}else{
		$date_target = $date + rand(1,5);
		$tanggal_target = '2014-01-'.sprintf('%02d' , $date_target); 
	}	
	$tanggal_mulai = '2014-01-'.sprintf('%02d' , $date); 
	
	$datas = array(
		'kegiatan_id' => my_type_data_int(rand(1,4)),	
		'karyawan_id' => my_type_data_int(rand(1,100)),	
		'label'		=> my_type_data_str($label),
		'tanggal_mulai'	=> my_type_data_str($tanggal_mulai),	
		'tanggal_target'=> my_type_data_str($tanggal_target ),		
		'revisi'	=> my_type_data_int(0),	
		'posted_on'	=> my_type_data_function('NOW()'),	
		'posted_by'=> my_type_data_int(rand(1,5)),	
	);
	return my_insert_record('task_karyawan_group',$datas);

}

function dump_data(){
	$label = array(
		'Menawarkan produk',
		'Mencari material pekerjaan',
		'Pencatatan umum',
		
		'Pembelian barang pokok',
		'Korespondensi',
		'Analisis data', 
	
		'Data processing', 
		'Penyusunan table',
		'Penyusunan laporan',
		
		'Statistik perusahaan',
		'Pengadaan barang',
		'Penagihan hutang',
		
		'Penarikan barang',
		'Penyusunan data tagihan',
	);
	for($i =1; $i<55; $i++){
		$t = rand(0,12);
		$text= $label[$t];
		entry_data_task($i,$text);
	}
	return $i;

}


//DETAIL

function dump_detail(){
	$query = "SELECT * FROM task_karyawan_group";
	$result = my_query($query);
	$i=0;
	while($ey =my_fetch_array($result)){
		$i++;
		 $text = $ey['label'];
		 entry_detail($ey['task_karyawan_group_id'] , $text);
	}
}
function entry_detail($task_id , $text){
	
	$labels = array(
		'Menawarkan produk' ,
		'Mencari material pekerjaan',
		'Pencatatan umum',
		
		'Pembelian barang pokok',
		'Korespondensi',
		'Analisis data', 
	
		'Data processing', 
		'Penyusunan table',
		'Penyusunan laporan',
		
		'Statistik perusahaan',
		'Pengadaan barang',
		'Penagihan hutang',
		
		'Penarikan barang',
		'Penyusunan data tagihan',
	);
	
	$pekerjaan = array(
		array(	'Menawarkan etalun ke PT Humikom Pratama',
				'Follow up PT Humikom Pratama',
				'Closing penawaran PT Humikom Pratama'
				),
		array(	'Mencari suplier komska kualitas 2',
				'Negosiasi harga dan agreement letter',
				'Mencari uplier tako liquid kualitas 3'
				),
		array(	'Pencatatan penjualan',
				'Pencatatan penerimaan faktur',
				'Pencatatan ketahanan barang',
				'Pranalar sumber informasi' 
				),
		
		array(	'Negosiasi harga barang',
				'Mengeluarkan PO barang',
				'Pengambilan barang'
				),
		array(	'Korespondensi domestik',
				'Pencatatan surat masuk domestik',
				'Pencatatan surat keluar domestik',
				'Pencatatan surat masuk internasional',
				'Pencatatan surat keluar internasional'
				),
		array(	'Analisis data', 
				'Pengumpulan data', 
				'Penyusunan table', 
				'Daftar ikhtisar'
				),
				//7
		array(	'Analisis data', 
				'Pengumpulan data', 
				'Penarikan sumber informasi', 
				'Laporan umum'
				),
		array(	'Pemecahan data', 
				'Pengelompokan data',  
				'Penentuan kolom data',  
				),
		array(	'Analisis data', 
				'Pengumpulan data', 
				'Penarikan sumber informasi', 
				'Laporan umum'
				),
		array(	'Pemecahan data', 
				'Pengelompokan data',  
				'Pengukuran data dan kolom',  
				),
		array(	'Mencari suplier komska kualitas 2',
				'Negosiasi harga dan agreement letter',
				'Mencari uplier tako liquid kualitas 3'
				),
				//12
		array(	'Pencatatan penjualan',
				'Pencatatan tagihan',
				'Pengiriman tagihan',
				'Pemeriksaan hasil tagih' 
				),
		array(	'Negosiasi ulang barang',
				'Penarikan barang',
				'Pencatatan barang retur'
				),
		array(	 
				'Pencatatan tagihan',
				'Pengiriman tagihan' 
				),
	);
	
	$key = get_index($labels , $text);
	$group = my_get_data_by_id('task_karyawan_group','task_karyawan_group_id' , $task_id );
	
	$op = array();
	$op[1] = '08:00';
	$op[2] = '13:00';

	$sp = array();
	$sp[1] = '12:00:00';
	$sp[2] = '17:30:00';


	list($yyyy , $mm, $dd) = explode("-",$group['tanggal_target']  );
	$ndd = (int) $dd + rand(1,4);
	$end = $yyyy.'-'.$mm.'-'.sprintf('%02d', $ndd);
	$dates = list_kalender( $group['tanggal_mulai'] , $end );
	foreach($dates as $date){	
		
		$n = count($pekerjaan[$key]);
		$ti = rand(0,($n-1));
		foreach($pekerjaan[$key] as $to=>$kerja){
			if(busy_date($date , $group['task_karyawan_group_id']))continue;
			$t = rand(1,2);
			if($t == 1){
				$y = rand(1,2);
			}else{
				$y = 2;
			}  
			$datas = array(
				'task_karyawan_group_id'	=> my_type_data_int($task_id),
				'tanggal'		=> 	my_type_data_str($date ),
				'task_label'	=>	my_type_data_str($pekerjaan[$key][$ti] ),
				'deskripsi'		=>	my_type_data_str($pekerjaan[$key][$ti] ),
				'jam_mulai'		=>  my_type_data_str($op[$t]),
				'jam_selesai'	=>  my_type_data_str($sp[$y]),
				'task_group_status'	=>	my_type_data_str('Open'),
				'revisi'	=> my_type_data_int(1),	
				'posted_on'	=> my_type_data_function('NOW()'),	
				'posted_by'=> my_type_data_int(rand(1,5)),	
			);
			
			my_insert_record(  'task_karyawan_detail', $datas);
		}
	}
}

function busy_date($date , $task_karyawan_group_id){
	$query = "SELECT * FROM task_karyawan_detail WHERE task_karyawan_group_id = {$task_karyawan_group_id} AND tanggal='{$date}'";
	$result = my_query($query);
	if( my_num_rows($result) > 0 ){
		return true;
	}	
	return false;
}

function get_index($labels , $text){
	foreach($labels as $key=>$label){
		if($label == $text){
			return $key;
		}
	}
	return false;
}