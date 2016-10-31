<?php


function tab_data($karyawan){
	$view =' <br/>
	<!-- Nav tabs -->
	<ul class="nav nav-tabs">
		<li class="active"><a href="#pendapatan" data-toggle="tab">Pendapatan</a>
		</li>
		<li><a href="#subsidi" data-toggle="tab">Subsidi</a>
		</li>
		<li><a href="#potongan" data-toggle="tab">Potongan</a>
		</li>
		<li><a href="#bank_transfer" data-toggle="tab">Bank transfer</a>
		</li> 
	</ul>

	<!-- Tab panes -->
	<div class="tab-content">
		<div class="tab-pane fade in active" id="pendapatan">
			<h4>Data kalkulasi pendapatan</h4> 
			'.get_load_hasil(  $karyawan  , 'pendapatan' ).'
		</div>
		<div class="tab-pane fade" id="subsidi">
			<h4>Data kalkulasi subsidi perusahaan</h4>
			'.get_load_hasil(  $karyawan  , 'subsidi' ).'
		</div>
		<div class="tab-pane fade" id="potongan">
			<h4>Data kalkulasi potongan</h4>
			'.get_load_hasil(  $karyawan  , 'potongan' ).'
		</div> 
		<div class="tab-pane fade" id="bank_transfer">
			<h4>Data bank transfer</h4>
			'.get_load_bank_transfer(  $karyawan  ).'
		</div> 
	</div> 
	
	';
	return $view;
}

function get_load_bank_transfer(  $karyawan  ){
	$headers= array(  
		'Nama bank' => array( 'width'=>'35%','style'=>'text-align:center;' ), 
		'Pemegang rekening' => array( 'width'=>'35%','style'=>'text-align:center;' ), 
		'Nomor rekening' => array( 'width'=>'15%','style'=>'text-align:center;' ),   
		'Persen' => array( 'width'=>'5%','style'=>'text-align:center;' ) ,
		'Nominal' => array( 'width'=>'10%','style'=>'text-align:center;' ) 
	);
	$query = "SELECT * FROM temp_pay_rekening_nominal a
	INNER JOIN karyawan_bank_account b ON a.rekening_id = b.rekening_id
	INNER JOIN bank c ON b.bank_id = c.bank_id
	WHERE b.karyawan_id = {$karyawan['karyawan_id']} ORDER BY b.rekening_id ASC";
	$result = my_query($query);
	$row = array();
	while($ey = my_fetch_array($result)){
		$row[] = array( 
			'nama_bank' => $ey['bank_name'],
			'nama_rekening' =>$ey['account_name'],
			'nomor_rekening' =>$ey['account_number'],
			'persen' =>position_text_align( $ey['persen'],'center'),
			'nominal' =>position_text_align( rp_format($ey['nominal']),'right'),
		);
	}
	$datas = table_rows($row);
	return table_builder($headers , $datas ,  6 , false );
	
}

function get_load_hasil(  $karyawan  , $type ){
	
	$query = "SELECT * FROM temp_pay_kalkulasi a
	INNER JOIN pay_komponen_gaji b 
		ON a.pay_komponen_gaji_id = b.pay_komponen_gaji_id
	WHERE b.type = '{$type}' AND a.karyawan_id = {$karyawan['karyawan_id']} ";
	$result = my_query($query);
	 
		$headers= array(  
			'Kode' => array( 'width'=>'15%','style'=>'text-align:center;' ), 
			'Nama komponen gaji' => array( 'width'=>'65%','style'=>'text-align:left;' ), 
			'Nominal' => array( 'width'=>'30%','style'=>'text-align:right;' ),   
			
		);
		$row = array();
		$total_nominal = 0; 
		while( $ey = my_fetch_array($result) ){
			$total_nominal += $ey['nominal_hitung'];
			$row[] = array( 
				'Kode' =>  position_text_align($ey['pay_komponen_gaji_code'], 'center'),    
				'Nama komponen' => $ey['pay_komponen_gaji_label'],   
				'Nominal' => position_text_align( rp_format($ey['nominal_hitung']), 'right'),   
			);
		}
		if($karyawan['metode_pajak'] == 0){
			if($type == 'potongan'){
				$pajak_nominal = get_pph_gross($karyawan['karyawan_id']);
				$total_nominal += $pajak_nominal;
				$row[] = array( 
					'Kode' =>  position_text_align('5000', 'center'),    
					'Nama komponen' =>'Pajak Pph dibayar sendiri',   
					'Nominal' => position_text_align( rp_format($pajak_nominal), 'right'),   
				);
			}
		}elseif($karyawan['metode_pajak'] == 1){
			if($type == 'subsidi'){
				$pajak_nominal = get_pph_netto($karyawan['karyawan_id']);
				$total_nominal += $pajak_nominal;
				$row[] = array( 
					'Kode' =>  position_text_align('0000', 'center'),    
					'Nama komponen' => 'Pajak Pph dibayar perusahaan',   
					'Nominal' => position_text_align( rp_format($pajak_nominal), 'right'),   
				);
			}
		}
		$row[] = array( 
			'Kode' =>  position_text_align('&nbsp;', 'center'),    
			'Nama komponen' => '<b>Total</b>',   
			'Nominal' => position_text_align( rp_format($total_nominal), 'right'),   
		);
		$datas = table_rows($row);
		return table_builder($headers , $datas ,  6 , false );
}


function detail_kalkulasi($karyawan_id = 0){ 
	$karyawan = my_get_data_by_id( 'karyawan' ,'karyawan_id' , $karyawan_id);
	$karyawan_status = my_get_data_by_id( 'karyawan_status' ,'karyawan_status_id' , $karyawan['karyawan_status_id'] );
	$karyawan_gol_jab = my_get_data_by_id( 'karyawan_gol_jab' ,'karyawan_gol_jab_id' , $karyawan['karyawan_gol_jab_id'] );
	 
	$view = '  <h4 id="grid-column-ordering">'.$karyawan['karyawan_nik'].' / '.$karyawan['nama_karyawan'].'</h3>
				<h5>'.$karyawan_status['karyawan_status_label'].' ('.$karyawan_gol_jab['karyawan_gol_jab_label'].') </h5>	
				'. '<p><b>Take home pay</b><br/><i>Rp. '. rp_format(take_home_pay($karyawan_id , $karyawan['metode_pajak'])).' </i></p>   
				' ;
	$view .= tab_data($karyawan);
	return $view;
}

function get_pph_gross($karyawan_id){
	$query = "SELECT nominal_pajak FROM temp_kalkulasi_pajak_gross_up
		WHERE pay_komponen_pajak_id = 2 
		AND karyawan_id = {$karyawan_id}";
	$result = my_query($query);
	$row = my_fetch_array($result);
	return $row['nominal_pajak'];
}
function get_pph_netto($karyawan_id){
	$query = "SELECT nominal_pajak FROM temp_kalkulasi_pajak 
		WHERE pay_komponen_pajak_id = 23
		AND karyawan_id = {$karyawan_id}";
	$result = my_query($query);
	$row = my_fetch_array($result);
	return $row['nominal_pajak'];
} 

function take_home_pay($karyawan_id , $metode){
	$query = "SELECT b.type, a.nominal_hitung FROM temp_pay_kalkulasi a
		INNER JOIN pay_komponen_gaji b 
		ON a.pay_komponen_gaji_id = b.pay_komponen_gaji_id
	WHERE a.karyawan_id = {$karyawan_id}";
	$result = my_query($query);
	$total = 0;
	while($row = my_fetch_array($result) ){
		if($row['type'] == 'pendapatan'){
			$total += $row['nominal_hitung'];
		}elseif($row['type'] == 'potongan'){
			$total -= $row['nominal_hitung'];
		}
	}
	if( $metode == '0'){
		$total -= get_pph_netto($karyawan_id);
	}
	return $total;
}