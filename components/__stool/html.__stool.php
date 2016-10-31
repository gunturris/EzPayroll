<?php

function create_dummy_finger( $start , $end ){
ini_set("max_execution_time","10000");
	$dates = list_kalender( $start , $end );
	$i =1;
	foreach($dates as $date){
		$query = "SELECT karyawan_id FROM karyawan ORDER BY RAND() ";
		$result = my_query($query);
		while( $row = my_fetch_array($result) ){
			if( is_hari_libur($date) ){
				$finger_log = '0000-00-00 00:00:00';
			}else{
				$in_jam = rand(6,7);
				if($in_jam ==  6)$in_menit = rand(45 , 59); 
				else $in_menit = rand(3 , 20);
				$in_detik = rand(0 , 50); 
				$finger_log = $date.' '.sprintf('%02d',$in_jam).':'.sprintf('%02d',$in_menit).':'.sprintf('%02d',$in_detik);
			}
			 
			insert_finger_data($row['karyawan_id'] , $finger_log);
			$i++;
		}
	} 
	return $i;
}

function insert_finger_data($karyawan_id , $datetime_finger){
	$datas = array(
		'karyawan_id'	=> my_type_data_int($karyawan_id),
		'finger_data'	=> my_type_data_str($datetime_finger),
		'manual'	=> my_type_data_str('N'),
		'mesin_id'	=> my_type_data_int(0),
		'datetime_added'	=> my_type_data_function('NOW()'),
	);
	return my_insert_record( 'finger_log' , $datas);
}

function test_compare_time(){
	$tanggal_mulai  = '19:12:18';
	$tanggal_selesai = '19:12:15';
	
	if(strtotime($tanggal_mulai) > strtotime($tanggal_selesai) ){
		return 1;
	}
	return 0;
}
function test_compare(){
	$tanggal_mulai  = new DateTime('2014-09-10');
	$tanggal_selesai = new DateTime('2014-03-08');
	
	if($tanggal_mulai > $tanggal_selesai){
		return 1;
	}
	return 0;
} 
 
function upload_sisa_file_od(){
	$dest ='../files/csv/item_'.date('Ymd_his').'.csv';
	$ori = $_FILES['filexl']['tmp_name'];
	$upload = move_uploaded_file($ori , $dest);  
	$i=0;
	if($upload){ 
	
		$handle = fopen($dest, "r"); 
		while (($datas = fgetcsv($handle, 3000, ",")) !== FALSE) {  
				parse_transaksi_detail($datas);//parse_kalender_shift($data); 
				$i++;
		}
		fclose($handle);
	}
	return $i ;
}


function upload_form_page(){

 
	$view = form_header( "upload" , "upload"  );
	 
	$file = array(
			'name'=>'filexl',
			'value'=>(isset($_POST['filexl'])? $_POST['filexl'] : ''),
			'id'=>'file' ,'size'=>'70',
			'type'=>'file'
	);
	$form_nominal = form_dynamic($file);
	$view .= form_field_display( $form_nominal , "File CSV" );
	
	$submit = array(
		'value' => ' Proses ',
		'name' => 'simpan', 
		'type'=>'submit','class'=>'main_button'
	);
	$form_submit= form_dynamic($submit); 
	
	$view .= form_field_display( $form_submit  , "&nbsp;" ,  "" );
	$view .= form_footer( );
	return $view;
}

function parse_transaksi_detail($datas){
	if((int) $datas[1] > 0){
	$price_1 = get_price_from_item_default($datas[1]);
	$datas_insert = array(
		'item_id' => my_type_data_int($datas[1]),
		'transaksi_id' => my_type_data_int($datas[0]),
		'price' =>   my_type_data_str($price_1),
		'datetime_added' => my_type_data_function('NOW()'),
		'created_by' => my_type_data_int(0),
	);
	my_insert_record('transaksi_detail', $datas_insert);
	}
	
	if((int) $datas[2] > 0){
	$price_2 = get_price_from_item_default($datas[2]);
	$datas_insert = array(
		'item_id' => my_type_data_int($datas[2]),
		'transaksi_id' => my_type_data_int($datas[0]),
		'price' =>  my_type_data_str($price_2),
		'datetime_added' => my_type_data_function('NOW()'),
		'created_by' => my_type_data_int(0),
	);
	my_insert_record('transaksi_detail', $datas_insert);
	}
	
	if((int) $datas[3] > 0){
	$price_3 = get_price_from_item_default($datas[3]);
	$datas_insert = array(
		'item_id' => my_type_data_int($datas[3]),
		'transaksi_id' => my_type_data_int($datas[0]),
		'price' =>  my_type_data_str($price_3),
		'datetime_added' => my_type_data_function('NOW()'),
		'created_by' => my_type_data_int(0),
	);
	my_insert_record('transaksi_detail', $datas_insert);
	}
	
	if((int) $datas[4] > 0){
	$price_4 = get_price_from_item_default($datas[4]);
	$datas_insert = array(
		'item_id' => my_type_data_int($datas[4]),
		'transaksi_id' => my_type_data_int($datas[0]),
		'price' =>  my_type_data_str($price_4),
		'datetime_added' => my_type_data_function('NOW()'),
		'created_by' => my_type_data_int(0),
	);
	my_insert_record('transaksi_detail', $datas_insert);
	}
	
	if((int) $datas[5] > 0){
	$price_1 = get_price_from_item_default($datas[5]);
	$datas_insert = array(
		'item_id' => my_type_data_int($datas[5]),
		'transaksi_id' => my_type_data_int($datas[0]),
		'price' => my_type_data_str($price_1),
		'datetime_added' => my_type_data_function('NOW()'),
		'created_by' => my_type_data_int(0),
	);
	my_insert_record('transaksi_detail', $datas_insert);
	}
	
	if((int) $datas[6] > 0){
	$price_2 = get_price_from_item_default($datas[6]);
	$datas_insert = array(
		'item_id' => my_type_data_int($datas[6]),
		'transaksi_id' => my_type_data_int($datas[0]),
		'price' =>  my_type_data_str($price_2),
		'datetime_added' => my_type_data_function('NOW()'),
		'created_by' => my_type_data_int(0),
	);
	my_insert_record('transaksi_detail', $datas_insert);
	}
	
	if((int) $datas[7] > 0){
	$price_3 = get_price_from_item_default($datas[7]);
	$datas_insert = array(
		'item_id' => my_type_data_int($datas[7]),
		'transaksi_id' => my_type_data_int($datas[0]),
		'price' => my_type_data_str($price_3),
		'datetime_added' => my_type_data_function('NOW()'),
		'created_by' => my_type_data_int(0),
	);
	my_insert_record('transaksi_detail', $datas_insert);
	}
	
	if((int) $datas[8] > 0){
	$price_4 = get_price_from_item_default($datas[8]);
	$datas_insert = array(
		'item_id' => my_type_data_int($datas[8]),
		'transaksi_id' => my_type_data_int($datas[0]),
		'price' => my_type_data_str($price_4),
		'datetime_added' => my_type_data_function('NOW()'),
		'created_by' => my_type_data_int(0),
	);
	my_insert_record('transaksi_detail', $datas_insert);
	}
}

function get_price_from_item_default($item_id){
	$query = "SELECT  price_default FROM item WHERE item_id = {$item_id}";
	$result = my_query($query);
	$row = my_fetch_array($result);
	return $row['price_default'];
}

function parse_transaction($datas){
	
	$hour = rand(8,16);
	$menit = rand(10,55);
	$detik = rand(10,55);
	$time = sprintf('%02d',$hour).':'.$menit.':'.$detik;
	$datas_insert = array(
		'transaksi_code' => my_type_data_str($datas[0]),
		'tanggal_transaksi' => my_type_data_str($datas[2] .' '. $time),
		'member_id' => my_type_data_str($datas[1]),
		'debet_credit' => my_type_data_str('deb'),
		'periode_id'=> my_type_data_str(0),
		'catatan'	 => my_type_data_str(''), 
		'datetime_added' => my_type_data_function('NOW()'),
		'created_by' => my_type_data_int(0),
	);
	return my_insert_record('transaksi', $datas_insert);
}

function parse_member($datas){
	$alamat = $datas[7];
	if($datas[8]){
		$alamat .= "\n".$datas[8];
	}
	if($datas[9]){
		$alamat .= "\n".$datas[9];
	}
	 
		$alamat .= "\n".$datas[10];
	 
	$data_insert = array(
		'member_type_id'	=> my_type_data_int(1),
		'member_number'		=> my_type_data_str($datas[0]),
		'nama'				=> my_type_data_str($datas[1]),
		'nomor_telepon1'	=> my_type_data_str($datas[3]),
		'nomor_telepon2'	=> my_type_data_str($datas[4]),
		'alamat'		=> my_type_data_str($alamat),
		'tanggal_lahir'=> my_type_data_str($datas[2]),
		'email'=> my_type_data_str($datas[6]),
		'join_date'=> my_type_data_str($datas[5]),
		'datetime_added' => my_type_data_function('NOW()'),
		'created_by' => my_type_data_int(0),
	);
	return my_insert_record('member' , $data_insert);
}

function parse_item($datas){
	 
	$data_insert = array(
		'item_category_id'=> my_type_data_int($datas[5]),
		'item_code'			=> my_type_data_str($datas[1]),
		'item_name'			=> my_type_data_str($datas[2]),
		'barcode'			=> my_type_data_str(trim($datas[3],"'")),
		'price_default'		=> my_type_data_str($datas[4]),
		'description'		 => my_type_data_str(''),
		'datetime_added' => my_type_data_function('NOW()'),
		'created_by' => my_type_data_int(0),
	);
	return my_insert_record('item' , $data_insert);
}
//DUMMY SPD BIAYA
function set_spd_tarif_all(){
	$query = "SELECT spd_kegiatan_karyawan_id FROM spd_kegiatan_karyawan 
		WHERE spd_kegiatan_karyawan_id > 130";
	$res = my_query($query);
	$i=0;
	while( $row = my_fetch_array($res) ){
		set_tarif_dan_klasifikasi($row['spd_kegiatan_karyawan_id']);
		$i++;
	}
	return $i;
}

function set_tarif_dan_klasifikasi($spd_id){
	$skip = "SELECT * FROM spd_kegiatan_karyawan_biaya 
		WHERE spd_kegiatan_karyawan_id = {$spd_id}";
	$res = my_query($skip);
	if(my_num_rows($res) > 0){
		return false;
	}
	$i = 0;
	$spd = my_get_data_by_id( 'spd_kegiatan_karyawan','spd_kegiatan_karyawan_id', $spd_id);
	$datetime1 = new DateTime($spd['tanggal_mulai']);
	
	$datetime2 = new DateTime($spd['tanggal_selesai']);
	$interval = $datetime1->diff($datetime2);
	 
	$karyawan = my_get_data_by_id( 'karyawan', 'karyawan_id' ,$spd['karyawan_id'] );
	if( (int) $karyawan['klasifikasi_id'] == 0){
		return false;
	}
	$query = "SELECT * FROM spd_komponen_tarif WHERE klasifikasi_id = {$karyawan['klasifikasi_id']}";
	$result = my_query($query);
	while( $row = my_fetch_array($result) ){
		$komponen = my_get_data_by_id( 'spd_komponen' , 'komponen_id' ,$row['komponen_id'] );
		if($komponen['satuan'] == 'Paket'){
			$t = rand(1, 15);
			if( ($t % 3) == 0)continue;
		}
		
		if($komponen['satuan'] == 'Waktu' ){
			$jumlah =   $interval->days;
		}else{
			$jumlah = 1;
		}
		$tarif = $jumlah * $row['tarif_referensi'];
		if((int)$tarif == 0)continue;
		set_biaya_spd($spd_id , $row['spd_komponen_tarif_id'] , $jumlah , $tarif );
		$i++;
	}
	return $i;
}
function set_biaya_spd($spd_id , $spd_komponen_tarif_id , $jumlah , $tarif ){
	$datas = array(
		'spd_kegiatan_karyawan_id'	=> my_type_data_str($spd_id),
		'spd_komponen_tarif_id'	=> my_type_data_str($spd_komponen_tarif_id),
		'jumlah'	=> my_type_data_str($jumlah),
		'nominal_biaya'	 => my_type_data_str($tarif),
		'revisi'	=> my_type_data_str(1),
		'posted_on'	=> my_type_data_function('NOW()'),
		'posted_by'=> my_type_data_str(1) 
	);
	return my_insert_record( 'spd_kegiatan_karyawan_biaya' , $datas);
}

//DUMMY SPD

function data_spd_dummy(){
	for($i = 180; $i<=205; $i++){
		$nomor = '42'.sprintf('%06d' , $i);
		
		if( $i<= 185){
			$t = rand(1,10);
		}elseif($i<= 190){
			$t = rand(11,20);
		}elseif($i<= 200){
			$t = rand(21,28); 
		}else{
			$t = rand(29,31);  
		}
		$tanggal_pengajuan = '2014-03-'.sprintf('%02d' , $t) ;
		if( is_hari_libur($tanggal_pengajuan))continue;
		simulate_data_spd($i ,$nomor , $tanggal_pengajuan);
	}
} 
function simulate_data_spd($i ,$nomor , $tanggal_pengajuan){
	$karyawan_id = rand( 1 , 220);
	$karyawan = my_get_data_by_id('karyawan' ,'karyawan_id' , $karyawan_id);
	if($i<=185){
		$tanggal = rand(1,5);
	}elseif($i<=190){
		$tanggal = rand(6,10); 
	}elseif($i<=200){
		$tanggal = rand(11,24); 
	}else{
		$tanggal = rand(25,31); 
	}
	if( is_hari_libur($tanggal))return false;
	if(($i%3) == 0)
		$tanggal2 = ($tanggal < 31 ) ? rand(3,6) : 0;
	else
		$tanggal2 = 0;
	

	$tanggal_mulai = '2014-03-'.sprintf('%02d' , $tanggal); 
	$tanggal_selesai = '2014-03-'.sprintf('%02d',($tanggal+$tanggal2)); 	
		
		$tujuan_opsi = array
			(	1=>'Pekanbaru',
				2=>'Semarang',
				3=>'Singapura', 
				4=>'Kuala lumpur', 
				5=>'Balikpapan', 
				6=>'Makasar', 
				7=>'Surabaya', 
				8=>'Singapura', 
				9=>'Denpasar', 
				10=>'Denpasar', 
				11=>'Pekalongan', 
				12=>'Yogyakarta', 
				13=>'Pontianak', 
				14=>'London', 
				15=>'Singapura', 
				16=>'Singapura', 
				17=>'Singapura',
				18=>'Surabaya',
				19=>'Semarang',
				20=>'Singapura',

				21 => 'Aceh besar',
				22 => 'Semarang',
				23 => 'Medan',
				24 => 'Salatiga',
				25 => 'Palembang',
				26 => 'Padang',
				27 => 'Cilegon',
				28 => 'Tanggerang',
				29 => 'Jakarta',
				30 => 'Bandung',

				31 => 'Garut',
				32 => 'Batang',
				33 => 'Madiun',
				34 => 'Surabaya',
				35 => 'Yogyakarta',

				36 => 'Denpasar',
				37 => 'Semarang',
				38 => 'Banyuwangi',
				39 => 'Ambarawa',
				40 => 'Tegal',

				41 => 'Balikpapan',
				42 => 'Samarinda',
				43 => 'Palangkaraya',
				44 => 'Makasar',
				45 => 'Palu',
			);
			$is = rand(1,45);
			$tujuan = $tujuan_opsi[$is];
		$nama = array(
			1=>'Demonstrasi produk',
			2=>'Meeting dengan pejabat daerah',
			3=>'Interview vendor',
			4=>'Perjalanan umum',
			5=>'Lihat produk vendor',
			6=>'Implementasi produk',
			7=>'Ikut tender daerah',
			8=>'Diskusi asosiasi',
			9=>'Seminar dan presentasi produk',
			10=>'Diskusi dengan pejabat daerah',
		);
		$yy =rand(1,10);
		$nama_spd = $nama[$yy];
	
	$datas = array(
		'nomor'	=> my_type_data_str($nomor),	
		'nama_spd'	=> my_type_data_str($nama_spd),	
		'karyawan_id'	=> my_type_data_str($karyawan_id),
		'kegiatan_id'	=> my_type_data_str('1'),
		'tanggal_mulai'	=> my_type_data_str($tanggal_mulai),
		'tanggal_selesai'	=> my_type_data_str($tanggal_selesai),
		'tanggal_pengajuan'	=> my_type_data_str($tanggal_pengajuan),
		'deskripsi'		=> my_type_data_str(''),
		'tujuan'	=> my_type_data_str($tujuan), 
		'revisi'	=> my_type_data_str(1),
		'posted_on'	=> my_type_data_function('NOW()'),
		'posted_by'=> my_type_data_str(1) 
	);
	return my_insert_record('spd_kegiatan_karyawan', $datas);
} 


//DUMMY IJIN

function data_ijin_dummy(){
	for($i = 305; $i<=365; $i++){
		$nomor = '12'.sprintf('%06d' , $i);
		
		if( $i<= 310){
			$t = rand(1,10);
		}elseif($i<= 325){
			$t = rand(11,20);
		}elseif($i<= 355){
			$t = rand(21,24); 
		}else{
			$t = rand(25,31);  
		}
		$tanggal_pengajuan = '2014-03-'.sprintf('%02d' , $t) ;
		if( is_hari_libur($tanggal_pengajuan))continue;
		simulate_data_ijin($i ,$nomor , $tanggal_pengajuan);
	}
}  
function simulate_data_ijin($i ,$nomor , $tanggal_pengajuan){
	$karyawan_id = rand( 1 , 220);
	$karyawan = my_get_data_by_id('karyawan' ,'karyawan_id' , $karyawan_id);
	if($i<=310){
		$tanggal = rand(1,5);
	}elseif($i<=325){
		$tanggal = rand(6,10); 
	}elseif($i<=355){
		$tanggal = rand(11,22); 
	}else{
		$tanggal = rand(23,31); 
	}
	if( is_hari_libur($tanggal))return false;
	if(($i%3) == 0)
		$tanggal2 = ($tanggal < 30 ) ? rand(1,2) : 0;
	else
		$tanggal2 = 0;
	
	$jenis_ijin = rand(1,5);	
	$keterangan = '';
	$jam_mulai = '08:00:00';
	$jam_selesai  = '17:30:00';
	if($jenis_ijin == 2){
		$tstart = array(
			1=>'08:00:00',
			2=>'13:00:00',
		);
		$tend = array(
			1=>'12:00:00',
			2=>'17:30:00',
		);
		$o = rand(1,2);
		$jam_mulai = $tstart[$o];
		$jam_selesai = $tend[$o];
		$keterangan_opsi = array
		(	1=>'Ban kendaraan bocor',
			2=>'Banjir',
			3=>'Jalanan macet', 
		);
		$is = rand(1,3);
		$keterangan = $keterangan_opsi[$is];
	} 
	$telepon = ( ($i%7) == 0) ? '' :$karyawan['telepon'];
	$datas = array(
		'nomor_ijin'	=> my_type_data_str($nomor),	
		'karyawan_id'	=> my_type_data_str($karyawan_id),
		'ijin_jenis_id'	=> my_type_data_str($jenis_ijin),
		'jam_mulai'	=> my_type_data_str($jam_mulai),
		'jam_selesai'	=> my_type_data_str($jam_selesai),
		'tanggal_ijin'	=> my_type_data_str($tanggal_pengajuan),
		'keterangan'		=> my_type_data_str($keterangan),
		'nomor_kontak_selama_ijin'	=> my_type_data_str($telepon), 
		'revisi'	=> my_type_data_str(1),
		'posted_on'	=> my_type_data_function('NOW()'),
		'posted_by'=> my_type_data_str(1),
	);
	return my_insert_record('ijin_karyawan', $datas);
} 

//DUMMY CUTI
function data_diskon_dummy(){
	$query = "SELECT * FROM item ORDER BY RAND() LIMIT 22";
	$result = my_query($query);
	while( $row = my_fetch_array($result)){
		$newprice = 95 / 100 * $row['price_default'];
		$newfixprice = ceil($newprice);
		$datas = array(
			'diskon_group_id'=> my_type_data_str(1), 
			'item_id'=> my_type_data_str($row['item_id']), 
			'final_price'=> my_type_data_str($newfixprice), 
			'datetime_added'=> my_type_data_str('2013-01-01'), 
			'created_by'=> my_type_data_int(0), 
		);
		my_insert_record( 'diskon_item' , $datas );
	}
} 
  
//END DUMMY CUTI
 