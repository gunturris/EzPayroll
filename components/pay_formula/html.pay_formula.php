<?php

function formula_umum($komponen_formula , $karyawan_id){
	$formulas = array();
	$formulas['[GAPOK]'] = 'formula_get_gapok($karyawan_id)';
	$formulas['[KERJA]'] = 'formula_get_hari_kerja($karyawan_id)';
	$text = $komponen_formula;
	foreach($formulas as $key => $val ){ 
		$text  = str_replace($key , $val , $text); 
	}
	$formula_text = strtolower($text);
	eval("\$hasil_formula  =  ".  $formula_text .";" ) ; 
	return  $hasil_formula ;
} 


function get_value_from_komponen_gaji (  $karyawan_id ,$komponen_id ){
	$komponen = loaddata_komponen($komponen_id);
	$karyawan = loaddata_karyawan($karyawan_id);
	
	if( (int) $komponen['pay_model_komponen_gaji_id'] == 1 ){
		return $karyawan['basic_salary'];
	}
	
	if( (int) $komponen['pay_model_komponen_gaji_id'] == 3  ){
		return get_tarif_by_gol_jab(trim($komponen['formula']) ,
					$karyawan['karyawan_gol_jab_id'] , $karyawan_id );
		
	}
	
	if( (int) $komponen['pay_model_komponen_gaji_id'] == 5  ){
		return get_data_manual( $karyawan_id   ,$komponen_id);
	}
	
	if((int) $komponen['pay_model_komponen_gaji_id'] == 4 ){
		return formula_umum(trim($komponen['formula']) , $karyawan_id); 
	}
}

function formula_get_gapok($karyawan_id){
	$query = "SELECT basic_salary FROM karyawan WHERE karyawan_id = {$karyawan_id}";
	$result = my_query($query);
	$row = my_fetch_array($result);
	return (int) $row['basic_salary'];
}
	
function formula_get_hari_kerja($karyawan_id){ 
	return (int) formula_get_umum( );
}
	
function formula_get_umum( ){
	$query = "SELECT hari_kerja FROM pay_periode_reguler 
		WHERE status_proses = 'Current' ORDER BY pay_periode_reguler_id DESC LIMIT 1";
		$result = my_query($query);
		$r = my_fetch_array($result);
	return (int) $r['hari_kerja'];
}
	
	 
function get_data_manual( $karyawan_id   ,$komponen_id){
	$query = "SELECT nominal_tetap FROM pay_komponen_manual WHERE 
		pay_komponen_gaji_id = {$komponen_id} AND karyawan_id = {$karyawan_id}
		ORDER BY pay_komponen_manual_id DESC	LIMIT 1 ";
	$result = my_query($query);
	$row = my_fetch_array($result);
	return $row['nominal_tetap'];
}


function get_benefit_group_by_code($code){
	$query = "SELECT pay_benefit_group_id FROM pay_benefit_group
	WHERE  pay_benefit_group_code = '{$code}' ";
	$result = my_query($query);
	$row = my_fetch_array($result);
	return (int) $row['pay_benefit_group_id'];
}

function get_tarif_by_gol_jab( $code , $gol_jab_id ,$karyawan_id){
	$pay_benefit_group_id = get_benefit_group_by_code($code);
	$pay_benefit_group = my_get_data_by_id('pay_benefit_group', 'pay_benefit_group_id' , $pay_benefit_group_id);
	$factor_kali = 1;
	if($pay_benefit_group['term'] == 'daily'){
		$factor_kali = formula_get_hari_kerja($karyawan_id);
	}
	$query = "SELECT  nominal FROM pay_benefit_gol_jab 
		WHERE karyawan_gol_jab_id = {$gol_jab_id}
	AND pay_benefit_group_id = {$pay_benefit_group_id} ";
	$result = my_query($query);
	$row =my_fetch_array($result);
	return $row['nominal'] * $factor_kali;
}


function nominal_pajak_karyawan_by_kalkulasi( $karyawan_id , $pay_komponen_pajak_id ){
	 
	$query_by_view = "SELECT SUM(nominal_hitung) AS nominal FROM view_temp_kalkulasi_pajak 
		WHERE karyawan_id = {$karyawan_id} 
		AND komponen_pajak_id = {$pay_komponen_pajak_id} ";
	$result = my_query($query_by_view);
	$row = my_fetch_array($result);
	return $row['nominal'];
}


function kalkulasi_pajak($karyawan_id , $tax_ptkp_category_id){
	
	$query = "SELECT * FROM pay_komponen_pajak ORDER BY komponen_pajak_id asc";
	$result = my_query($query );
	$nominal_pajak7 = 0;
	while($row = my_fetch_array($result)){
		$nominal = 0;
		if((int) $row['select_option'] == 1){
			$nominal = (int) nominal_pajak_karyawan_by_kalkulasi( $karyawan_id , $row['komponen_pajak_id'] );
			$nominal_pajak7 += $nominal;
		}else{
			if((int) $row['komponen_pajak_id'] == 7){
				$nominal = $nominal_pajak7;
			}elseif( (int) $row['komponen_pajak_id'] == 9 ){
				$nominal_pajak8 = nominal_pajak_karyawan_by_kalkulasi( $karyawan_id , 8 );
				$nominal_pajak9 = $nominal_pajak7 + $nominal_pajak8;
				$nominal = $nominal_pajak9;
			}elseif( (int) $row['komponen_pajak_id'] == 10 ){
				$nominal =   get_biaya_jabatan($nominal_pajak7);
				 $nominal_pajak10 = $nominal;
			}elseif( (int) $row['komponen_pajak_id'] == 11 ){
				$nominal =   get_biaya_jabatan($nominal_pajak8);
				$nominal_pajak11 = $nominal; 
			}elseif( (int) $row['komponen_pajak_id'] == 13 ){ 
				$nominal_pajak12 = nominal_pajak_karyawan_by_kalkulasi( $karyawan_id , 12 );
				$nominal_pajak13 = $nominal_pajak10 +$nominal_pajak11 + $nominal_pajak12;
				$nominal  = $nominal_pajak13;
			}elseif( (int) $row['komponen_pajak_id'] == 14 ){
				$nominal_pajak14  = $nominal_pajak9 - $nominal_pajak13;
				$nominal = $nominal_pajak14;
			}elseif( (int) $row['komponen_pajak_id'] == 16 ){
				$nominal_pajak16  = $nominal_pajak14  * 12 ;
				$nominal = $nominal_pajak16 ; 
			}elseif( (int) $row['komponen_pajak_id'] == 17 ){
				$nominal_pajak17  = get_ptkp_karyawan($tax_ptkp_category_id  ) ;
				$nominal = $nominal_pajak17 ; 			 
			}elseif( (int) $row['komponen_pajak_id'] == 18 ){
				$nominal_pajak18  = $nominal_pajak16 - $nominal_pajak17;
				$nominal = $nominal_pajak18;
			}elseif( (int) $row['komponen_pajak_id'] == 19 ){
				$nominal_pajak19  = tarif_progressif($nominal_pajak18);
				$nominal = $nominal_pajak19;
			}elseif((int) $row['komponen_pajak_id'] == 21){
				$pajak_bulanan = $nominal_pajak19 / 12;
				$nominal_pajak21 = round($pajak_bulanan,-2);
				$nominal = $nominal_pajak21;
			}elseif((int) $row['komponen_pajak_id'] == 23){
				$nominal = $nominal_pajak21;
			}
		}
		$datas = array(
			'pay_komponen_pajak_id' => my_type_data_int($row['komponen_pajak_id']),
			'karyawan_id' => my_type_data_int($karyawan_id),
			'nominal_pajak' => my_type_data_str($nominal),
			'created_on' => my_type_data_function('NOW()'),
			'user_updated_id' => my_type_data_int($_SESSION['user_id']),
		);
		my_insert_record('temp_kalkulasi_pajak' , $datas);
	}
	return true;
}

 
function kalkulasi_pajak_gross_up($karyawan_id , $tax_ptkp_category_id ){
	$query_pajak = "SELECT * FROM temp_kalkulasi_pajak 
		WHERE karyawan_id = {$karyawan_id}";
	$result = my_query($query_pajak);
	$pajaks = array();
	while($row = my_fetch_array($result)){
		$pajaks[$row['pay_komponen_pajak_id']] = $row['nominal_pajak']; 
	}
	$query_dua = "SELECT * FROM pay_komponen_pajak ORDER BY komponen_pajak_id asc";
	$result_dua = my_query($query_dua );
	
	 $nominal_pajak7 = 0;
	while($row_dua = my_fetch_array($result_dua)){
		$nominal = 0;
		if((int) $row_dua['select_option'] == 1){
			$nominal = $pajaks[$row_dua['komponen_pajak_id']]  ;
			$nominal_pajak7 += $nominal;
		}else{
			if((int) $row_dua['komponen_pajak_id'] == 2){
				$nominal = $pajaks[21];
				$nominal_pajak7 += $nominal;
			}elseif( (int) $row_dua['komponen_pajak_id'] == 7 ){
				$nominal = $nominal_pajak7;
				
			}elseif( (int) $row_dua['komponen_pajak_id'] == 9 ){
				$nominal_pajak8 = nominal_pajak_karyawan_by_kalkulasi( $karyawan_id , 8 );
				 $nominal_pajak9  = $nominal =  $nominal_pajak7 + $nominal_pajak8;
				 
			}elseif( (int) $row_dua['komponen_pajak_id'] == 10 ){
				$nominal = $nominal_pajak10 = get_biaya_jabatan($nominal_pajak7);
				
			}elseif( (int) $row_dua['komponen_pajak_id'] == 11 ){
				$nominal = $nominal_pajak11 = get_biaya_jabatan($nominal_pajak8);
				
			}elseif( (int) $row_dua['komponen_pajak_id'] == 13 ){
				$nominal = $nominal_pajak13 =  $nominal_pajak10 + $pajaks[11] + $pajaks[12];
				
			}elseif( (int) $row_dua['komponen_pajak_id'] == 14 ){
				$nominal_pajak14  = $nominal_pajak9 - $nominal_pajak13;
				$nominal = $nominal_pajak14;
			 }elseif( (int) $row_dua['komponen_pajak_id'] == 16 ){
				$nominal_pajak16  = $nominal_pajak14  * 12 ;
				$nominal = $nominal_pajak16 ; 
			}elseif( (int) $row_dua['komponen_pajak_id'] == 17 ){
				$nominal_pajak17  = get_ptkp_karyawan($tax_ptkp_category_id  ) ;
				$nominal = $nominal_pajak17 ; 			 
			}elseif( (int) $row_dua['komponen_pajak_id'] == 18 ){
				$nominal_pajak18  = $nominal_pajak16 - $nominal_pajak17;
				$nominal = $nominal_pajak18;
			}elseif( (int) $row_dua['komponen_pajak_id'] == 19 ){
				$nominal_pajak19  = tarif_progressif($nominal_pajak18);
				$nominal = $nominal_pajak19;
			}elseif((int) $row_dua['komponen_pajak_id'] == 21){
				$pajak_bulanan = $nominal_pajak19 / 12;
				$nominal_pajak21 = round($pajak_bulanan,-2);
				$nominal = $nominal_pajak21;
			}elseif((int) $row_dua['komponen_pajak_id'] == 23){
				$nominal = $nominal_pajak21;
			}
		}
		$datas = array(
			'pay_komponen_pajak_id' => my_type_data_int($row_dua['komponen_pajak_id']),
			'karyawan_id' => my_type_data_int($karyawan_id),
			'nominal_pajak' => my_type_data_str($nominal),
			'created_on' => my_type_data_function('NOW()'),
			'user_updated_id' => my_type_data_int($_SESSION['user_id']),
		);
		my_insert_record('temp_kalkulasi_pajak_gross_up' , $datas);
	} 
	return true;
}


//PROSES UNREGULER
function nominal_pajak_karyawan_by_kalkulasi_unreg( $karyawan_id , $pay_komponen_pajak_id ){
	 
	$query_by_view = "SELECT SUM(nominal_hitung) AS nominal FROM view_temp_kalkulasi_pajak_unreg 
		WHERE karyawan_id = {$karyawan_id} 
		AND komponen_pajak_id = {$pay_komponen_pajak_id} ";
	$result = my_query($query_by_view);
	$row = my_fetch_array($result);
	return $row['nominal'];
}

function kalkulasi_pajak_unreg($karyawan_id , $tax_ptkp_category_id){
	
	$query = "SELECT * FROM pay_komponen_pajak ORDER BY komponen_pajak_id asc";
	$result = my_query($query );
	$nominal_pajak7 = 0;
	while($row = my_fetch_array($result)){
		$nominal = 0;
		if((int) $row['select_option'] == 1){
			$nominal = (int) nominal_pajak_karyawan_by_kalkulasi_unreg( $karyawan_id , $row['komponen_pajak_id'] );
			$nominal_pajak7 += $nominal;
		}else{
			if((int) $row['komponen_pajak_id'] == 7){
				$nominal = $nominal_pajak7;
			}elseif( (int) $row['komponen_pajak_id'] == 9 ){
				$nominal_pajak8 = nominal_pajak_karyawan_by_kalkulasi_unreg( $karyawan_id , 8 );
				$nominal_pajak9 = $nominal_pajak7 + $nominal_pajak8;
				$nominal = $nominal_pajak9;
			}elseif( (int) $row['komponen_pajak_id'] == 10 ){
				$nominal =   get_biaya_jabatan($nominal_pajak7);
				 $nominal_pajak10 = $nominal;
			}elseif( (int) $row['komponen_pajak_id'] == 11 ){
				$nominal =   get_biaya_jabatan($nominal_pajak8);
				$nominal_pajak11 = $nominal; 
			}elseif( (int) $row['komponen_pajak_id'] == 13 ){ 
				$nominal_pajak12 = nominal_pajak_karyawan_by_kalkulasi_unreg( $karyawan_id , 12 );
				$nominal_pajak13 = $nominal_pajak10 +$nominal_pajak11 + $nominal_pajak12;
				$nominal  = $nominal_pajak13;
			}elseif( (int) $row['komponen_pajak_id'] == 14 ){
				$nominal_pajak14  = $nominal_pajak9 - $nominal_pajak13;
				$nominal = $nominal_pajak14;
			}elseif( (int) $row['komponen_pajak_id'] == 16 ){
				$nominal_pajak16  = $nominal_pajak14  * 12 ;
				$nominal = $nominal_pajak16 ; 
			}elseif( (int) $row['komponen_pajak_id'] == 17 ){
				$nominal_pajak17  = get_ptkp_karyawan($tax_ptkp_category_id  ) ;
				$nominal = $nominal_pajak17 ; 			 
			}elseif( (int) $row['komponen_pajak_id'] == 18 ){
				$nominal_pajak18  = $nominal_pajak16 - $nominal_pajak17;
				$nominal = $nominal_pajak18;
			}elseif( (int) $row['komponen_pajak_id'] == 19 ){
				$nominal_pajak19  = tarif_progressif($nominal_pajak18);
				$nominal = $nominal_pajak19;
			}elseif((int) $row['komponen_pajak_id'] == 21){
				$pajak_bulanan = $nominal_pajak19 / 12;
				$nominal_pajak21 = round($pajak_bulanan,-2);
				$nominal = $nominal_pajak21;
			}elseif((int) $row['komponen_pajak_id'] == 23){
				$nominal = $nominal_pajak21;
			}
		}
		$datas = array(
			'komponen_pajak_id' => my_type_data_int($row['komponen_pajak_id']),
			'karyawan_id' => my_type_data_int($karyawan_id),
			'nominal_pajak' => my_type_data_str($nominal),
			'created_on' => my_type_data_function('NOW()'),
			'user_updated_id' => my_type_data_int($_SESSION['user_id']),
		);
		my_insert_record('temp_unreg_kalkulasi_pajak' , $datas);
	}
	return true;
}

 
function kalkulasi_pajak_gross_up_unreg($karyawan_id , $tax_ptkp_category_id ){
	$query_pajak = "SELECT * FROM temp_unreg_kalkulasi_pajak 
		WHERE karyawan_id = {$karyawan_id}";
	$result = my_query($query_pajak);
	$pajaks = array();
	while($row = my_fetch_array($result)){
		$pajaks[$row['komponen_pajak_id']] = $row['nominal_pajak']; 
	}
	$query_dua = "SELECT * FROM pay_komponen_pajak ORDER BY komponen_pajak_id asc";
	$result_dua = my_query($query_dua );
	
	 $nominal_pajak7 = 0;
	while($row_dua = my_fetch_array($result_dua)){
		$nominal = 0;
		if((int) $row_dua['select_option'] == 1){
			$nominal = $pajaks[$row_dua['komponen_pajak_id']]  ;
			$nominal_pajak7 += $nominal;
		}else{
			if((int) $row_dua['komponen_pajak_id'] == 2){
				$nominal = $pajaks[21];
				$nominal_pajak7 += $nominal;
			}elseif( (int) $row_dua['komponen_pajak_id'] == 7 ){
				$nominal = $nominal_pajak7;
				
			}elseif( (int) $row_dua['komponen_pajak_id'] == 9 ){
				$nominal_pajak8 = nominal_pajak_karyawan_by_kalkulasi_unreg( $karyawan_id , 8 );
				 $nominal_pajak9  = $nominal =  $nominal_pajak7 + $nominal_pajak8;
				 
			}elseif( (int) $row_dua['komponen_pajak_id'] == 10 ){
				$nominal = $nominal_pajak10 = get_biaya_jabatan($nominal_pajak7);
				
			}elseif( (int) $row_dua['komponen_pajak_id'] == 11 ){
				$nominal = $nominal_pajak11 = get_biaya_jabatan($nominal_pajak8);
				
			}elseif( (int) $row_dua['komponen_pajak_id'] == 13 ){
				$nominal = $nominal_pajak13 =  $nominal_pajak10 + $pajaks[11] + $pajaks[12];
				
			}elseif( (int) $row_dua['komponen_pajak_id'] == 14 ){
				$nominal_pajak14  = $nominal_pajak9 - $nominal_pajak13;
				$nominal = $nominal_pajak14;
			 }elseif( (int) $row_dua['komponen_pajak_id'] == 16 ){
				$nominal_pajak16  = $nominal_pajak14  * 12 ;
				$nominal = $nominal_pajak16 ; 
			}elseif( (int) $row_dua['komponen_pajak_id'] == 17 ){
				$nominal_pajak17  = get_ptkp_karyawan($tax_ptkp_category_id  ) ;
				$nominal = $nominal_pajak17 ; 			 
			}elseif( (int) $row_dua['komponen_pajak_id'] == 18 ){
				$nominal_pajak18  = $nominal_pajak16 - $nominal_pajak17;
				$nominal = $nominal_pajak18;
			}elseif( (int) $row_dua['komponen_pajak_id'] == 19 ){
				$nominal_pajak19  = tarif_progressif($nominal_pajak18);
				$nominal = $nominal_pajak19;
			}elseif((int) $row_dua['komponen_pajak_id'] == 21){
				$pajak_bulanan = $nominal_pajak19 / 12;
				$nominal_pajak21 = round($pajak_bulanan,-2);
				$nominal = $nominal_pajak21;
			}elseif((int) $row_dua['komponen_pajak_id'] == 23){
				$nominal = $nominal_pajak21;
			}
		}
		$datas = array(
			'komponen_pajak_id' => my_type_data_int($row_dua['komponen_pajak_id']),
			'karyawan_id' => my_type_data_int($karyawan_id),
			'nominal_pajak' => my_type_data_str($nominal),
			'created_on' => my_type_data_function('NOW()'),
			'user_updated_id' => my_type_data_int($_SESSION['user_id']),
		);
		my_insert_record('temp_unreg_kalkulasi_pajak_gross_up' , $datas);
	} 
	return true;
}

//END PROSES UNREGULER




function get_ptkp_karyawan($ptkp_karyawan_status_id  ){
	 $data = my_get_data_by_id( 'tax_ptkp_categori' ,'tax_ptkp_categori_id' , (int) $ptkp_karyawan_status_id);
	 return $data['ptkp_nominal'];
}


function tarif_progressif($total_pendapatan_yang_dipajak){
	$nilai = (int) $total_pendapatan_yang_dipajak;
	if($nilai <= 50000000){
		//$potongan = 0.05 * $nilai;
		$potongan = 5/95 * $nilai;
		
	}

	elseif($nilai <= 250000000){
		//$potongan = 0.15   * ( $nilai - 50000000 ) + 2500000;
		$potongan = 15/85  * ( $nilai - 50000000 ) + 2500000;
		
	}

	elseif($nilai <= 500000000){
		//$potongan = 0.25  * ( $nilai - 250000000 ) + 30000000 + 2500000;
		$potongan = 25/75  * ( $nilai - 250000000 ) + 30000000 + 2500000;
		
	}else{
		//$potongan = 0.30  * ( $nilai - 500000000 ) + 62500000 + 30000000 + 2500000;
		$potongan = 30/70  * ( $nilai - 500000000 ) + 62500000 + 30000000 + 2500000;
		
	}
	return $potongan;
}