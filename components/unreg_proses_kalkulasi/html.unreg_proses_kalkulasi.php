<?php


function list_karyawan_kalkulasi(){ 
	$headers= array(  
		'NIK' => array( 'width'=>'10%','style'=>'text-align:center;' ), 
		'Nama karyawan' => array( 'width'=>'34%','style'=>'text-align:left;' ), 
		'Pendapatan' => array( 'width'=>'12%','style'=>'text-align:center;' ), 
		'Subsidi' => array( 'width'=>'12%','style'=>'text-align:center;' ), 
		'Potongan' => array( 'width'=>'12%','style'=>'text-align:center;' ),  
		'Aksi' => array( 'width'=>'10%','style'=>'text-align:center;' ), 
		
	);

	$query 	= "SELECT * FROM karyawan a 
		INNER JOIN unreg_salary_status b ON a.karyawan_status_id = b.karyawan_status_id
		INNER JOIN unreg_salary_agama x ON a.karyawan_agama_id = x.karyawan_agama_id";
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
				'href'=>'index.php?com=unreg_karyawan_hasil_kalkulasi&task=detail&karyawan_id=' . $ey['karyawan_id'] , 
				'title'=>'Detail hasil proses'
		);	
		$detail_button = button_icon( 'b_props.png' , $detailproperty  );

		$pendapatan = get_total_nominal_categori_group($ey['karyawan_id'] , 'pendapatan' ,$ey['metode_pajak'] );
		$subsidi = get_total_nominal_categori_group($ey['karyawan_id'] , 'subsidi',$ey['metode_pajak']);
		$potongan = get_total_nominal_categori_group($ey['karyawan_id'] , 'potongan',$ey['metode_pajak']);
		
		$row[] = array( 
		'NIK' =>  position_text_align($ey['karyawan_nik'], 'center'),    
		'Nama karyawan' => $ey['nama_karyawan'],  
		'Pendapatan' => position_text_align(rp_format($pendapatan), 'right'),  
		'Subsidi' => position_text_align(rp_format($subsidi), 'right'),  
		'Potongan' => position_text_align(rp_format($potongan), 'right'),    
		'op'=> position_text_align( $detail_button    , 'center')
		);
	}
	
	$datas = table_rows($row);
	$navigasi = array(
		'<input class="btn btn-primary" style="float:right;"  type="button" value="Excel" onclick="javascript:location.href=\'index.php?com='.$_GET['com'].'&task=excel\'"/>',
		'<input class="btn btn-primary" style="float:right;margin-right:5px"  type="button" value="Kalkulasi" onclick="javascript:location.href=\'index.php?com='.$_GET['com'].'&task=kalkulasi\'"/>',
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


function get_total_nominal_categori_group($karyawan_id , $type , $metode){
	$query = "SELECT SUM(a.nominal_hitung) AS ntotal FROM temp_unreg_kalkulasi a
			INNER JOIN pay_komponen_gaji b 
				ON a.komponen_gaji_id = b.pay_komponen_gaji_id 
			WHERE b.type = '{$type}' AND karyawan_id = {$karyawan_id} ";
	$result = my_query($query);
	$row = my_fetch_array($result);
	$total_nominal = 0; 
	if($metode == '1'){	
		if( $type == 'subsidi' ){
			$total_nominal = get_pph_netto($karyawan_id);
		}
	}else{
		if( $type == 'potongan' ){
			$total_nominal = get_pph_gross($karyawan_id);
		}
	}
	return $row['ntotal'] + $total_nominal;
}
 

 
function get_pph_gross($karyawan_id){
	$query = "SELECT nominal_pajak FROM temp_unreg_kalkulasi_pajak_gross_up
		WHERE komponen_pajak_id = 2 
		AND karyawan_id = {$karyawan_id}";
	$result = my_query($query);
	$row = my_fetch_array($result);
	if($row['nominal_pajak'] > 0)
		return $row['nominal_pajak']; 
	
	return 0;
}
function get_pph_netto($karyawan_id){
	$query = "SELECT nominal_pajak FROM temp_unreg_kalkulasi_pajak 
		WHERE komponen_pajak_id = 23
		AND karyawan_id = {$karyawan_id}";
	$result = my_query($query);
	$row = my_fetch_array($result);
	if($row['nominal_pajak'] > 0)
		return $row['nominal_pajak'];
	
	return 0;
} 

 
 
function proses_hitung_kalkulasi(){
	my_query('TRUNCATE TABLE temp_unreg_rekening_nominal');
	my_query('TRUNCATE TABLE temp_unreg_kalkulasi_pajak_gross_up');
	my_query('TRUNCATE TABLE temp_unreg_kalkulasi_pajak');
	my_query('TRUNCATE TABLE temp_unreg_kalkulasi');
	$periode_current = get_periode_unreguler_active();
	$periode_based_id = $periode_current['reguler_periode_based_id'];
	 
	$komponens = komponens_proses($periode_current['unreg_periode_id']);
	 
	$query 	= "SELECT * FROM karyawan a 
		INNER JOIN unreg_salary_status b ON a.karyawan_status_id = b.karyawan_status_id
		INNER JOIN unreg_salary_agama x ON a.karyawan_agama_id = x.karyawan_agama_id";
	$result = my_query($query);
	$i = 1;
	$p = new ProgressBar();
	echo '<center><div style="width: 400px;">';
	$p->render();
	echo '</div></center>';
	$size = my_num_rows($result);
	while($row = my_fetch_array($result)){
		proses_kalkulasi_unreg_karyawan($row['karyawan_id'] ,  $komponens , $periode_based_id); 
		if( $periode_current['hitung_pajak'] == '1' ){
			kalkulasi_pajak_unreg($row['karyawan_id'], $row['tax_ptkp_category_id']);
			kalkulasi_pajak_gross_up_unreg($row['karyawan_id'], $row['tax_ptkp_category_id']);
		}
		proses_distribusi_transfer_unreg($row['karyawan_id'] , $row['metode_pajak']);
		$p->setProgressBarProgress($i*100/$size); 
		$i++;
	}
	$p->setProgressBarProgress(100);
	return $i;
	
}

function komponens_proses($unreg_periode_id){
	$datas = array();
	$query = "SELECT pay_komponen_gaji_id FROM unreg_salary_komponen 
		WHERE unreg_periode_id = {$unreg_periode_id}";
	$result = my_query($query);
	if( my_num_rows($result) > 0){
		while( $row = my_fetch_array($result) ){
			$datas[] = $row['pay_komponen_gaji_id'];
		}
	}
	return $datas;
}

function proses_kalkulasi_unreg_karyawan( $karyawan_id , $komponens , $periode_based_id ){
	foreach($komponens as $komponen_id){
		$query = "SELECT * FROM log_payroll_reguler_komponen 
			WHERE karyawan_id = {$karyawan_id} 
			AND pay_komponen_gaji_id = {$komponen_id} 
			AND pay_periode_id = {$periode_based_id} 
			";
		$result = my_query($query);
		while($row = my_fetch_array($result) ){
			$datas = array(
				'karyawan_id'=> my_type_data_int($karyawan_id),
				'komponen_gaji_id'=> my_type_data_int($komponen_id),
				'nominal_hitung'=> my_type_data_str($row['pay_komponen_nominal']),
				'created_on'=> my_type_data_function('NOW()'),
				'user_updated_id'=> my_type_data_int($_SESSION['user_id']),
			);
			my_insert_record('temp_unreg_kalkulasi', $datas);
		}
	}
	return true;
}

function save_nominal_transfer_unreg($rekening_id ,$persen , $nominal){
	$datas = array(
		'rekening_id'=> my_type_data_int($rekening_id),
		'persen'=> my_type_data_str($persen),
		'nominal'=> my_type_data_str($nominal),
		'created_on'=>my_type_data_function('NOW()'),
		'user_updated_id'=>my_type_data_int($_SESSION['user_id'])
	
	);
	return my_insert_record('temp_unreg_rekening_nominal', $datas);
}

function proses_distribusi_transfer_unreg($karyawan_id , $metode){
	$total_thp  = take_home_pay($karyawan_id , $metode);
	$query = "SELECT * FROM karyawan_bank_account WHERE karyawan_id = {$karyawan_id}";
	$result = my_query($query);
	while($row = my_fetch_array($result)){
		$nominal =  round($total_thp * ( $row['persen']/ 100) );
		save_nominal_transfer_unreg($row['rekening_id'] , $row['persen'] , $nominal);
	}
	return true;
}


function take_home_pay($karyawan_id , $metode){
	$query = "SELECT b.type, a.nominal_hitung FROM temp_unreg_kalkulasi a
		INNER JOIN pay_komponen_gaji b 
		ON a.komponen_gaji_id = b.pay_komponen_gaji_id
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



function get_periode_unreguler_active(){
	$query = "SELECT * FROM unreg_periode WHERE status_proses = 'current' 
			ORDER BY unreg_periode_id DESC LIMIT 1";
	$result = my_query($query);
	$row = my_fetch_array($result );
	return $row;	
}


function get_biaya_jabatan($total_pendapatan){
	$hasil = 0.05 * $total_pendapatan;
	if($hasil > 500000)	return 500000;
	return $hasil;
}
