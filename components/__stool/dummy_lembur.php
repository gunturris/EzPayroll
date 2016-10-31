<?php


function simulasi_lembur(){
	$tugas = array();
	$tugas[1] = 'Menghitung data pemasukan';
	$tugas[2] = 'Rekapitulasi data harian';
	$tugas[3] = 'Memcatat pengembalian order';
	$tugas[4] = 'Penghtungan ulang data PO';
	$tugas[5] = 'Dokumentasi laporan bulan berjalan';
	$tugas[6] = 'Dokumentasi pembayaran';
	$tugas[7] = 'Mencetak tagihan';
	$tugas[8] = 'Mencetak tagihan';
	$tugas[9] = 'Mengemas kiriman yang tertunda';
	$tugas[10] = 'Jamuan tamu';
	$tugas[11] = 'Memastikan penerimaan kemasan utuh';
	$tugas[12] = 'Rekapitulasi data harian';
	
	
	$dates = list_kalender('2014-03-02' , '2014-03-31');
	
	$t=0;
	$nomor = 1003;
	foreach($dates as $date){
		$max = rand(3,8);
		$hari_jenis = 'HB';
		for($i = 1; $i<=$max ;$i++){
		$t++;
		$nomor++;
			$dari_jam =   '17:00:00';
			$sampai_jam = rand(18,21).':00:00';
			if(is_hari_libur($date)){
				$hari_jenis = 'HL';
				$jam = rand(8,9);
				$dari_jam =  sprintf('%02d',$jam).':00:00';
				$sampai_jam = rand(10,12).':00:00';
			}
			$karyawan_id = rand(3,118);
			$n = rand(1,12); 
			
			$durasi 		= durasi_lembur($dari_jam , $sampai_jam, false);
			$faktor_kali 	= get_hitung_jam($hari_jenis , $durasi);
			
			$datas = array(
				'nomor'		=> my_type_data_str($nomor),	
				'tanggal'		=> my_type_data_str($date),	
				'karyawan_id'	=> my_type_data_str($karyawan_id),		 
				'dari_jam'		=> my_type_data_str($dari_jam),	
				'sampai_jam'	=> my_type_data_str($sampai_jam),		
				'tugas'			=> my_type_data_str($tugas[$n]),	
				'jenis_hari'	=> my_type_data_str($hari_jenis),
				'jumlah_jam'	=> my_type_data_str($durasi),
				'akumulasi_jam'	=> my_type_data_str( 0 ),
				'hitung_jam'	=> my_type_data_str($faktor_kali * $durasi )  
			);
			my_insert_record( 'lembur_karyawan' , $datas );
		}
	}	
	return $t;
}


function get_hitung_jam($jenis_hari , $durasi){
	
	$query = "SELECT pengali FROM lembur_tarif WHERE jenis_hari ='{$jenis_hari}' AND jam >= '{$durasi}' ORDER BY pengali ASC LIMIT 1 ";
	$result = my_query($query);
	if( my_num_rows($result) > 0){
		$row = my_fetch_array($result);
		return $row['pengali'];
	}
	
	$query = "SELECT MAX(pengali) AS fct FROM lembur_tarif WHERE jenis_hari ='{$jenis_hari}'    LIMIT 1 ";
	$result = my_query($query);
	$row = my_fetch_array($result);
	return $row['fct'];
}


function durasi_lembur($start , $end , $t = true){
	$d1 = new DateTime($start);
	$d2 = new DateTime($end);
	$interval = $d2->diff($d1);
	if(!$t){
		return (int) $interval->format('%H');
	}
	return $interval->format('%H jam dan %I menit');
}	