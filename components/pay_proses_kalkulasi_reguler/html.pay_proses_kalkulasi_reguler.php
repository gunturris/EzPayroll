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
 
	$query 	= "SELECT * FROM karyawan ";
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
				'href'=>'index.php?com=karyawan_hasil_kalkulasi&task=detail&karyawan_id=' . $ey['karyawan_id'] , 
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
	$query = "SELECT SUM(a.nominal_hitung) AS ntotal FROM temp_pay_kalkulasi a
			INNER JOIN pay_komponen_gaji b 
				ON a.pay_komponen_gaji_id = b.pay_komponen_gaji_id 
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

function proses_kalkulasi_karyawan($karyawan_id){
	$karyawan = loaddata_karyawan($karyawan_id);
	$query = "SELECT * FROM pay_komponen_gaji a
		INNER JOIN pay_komponen_gaji_karyawan_status b 
		ON a.pay_komponen_gaji_id = b.pay_komponen_gaji_id
		WHERE b.karyawan_status_id = {$karyawan['karyawan_status_id']} ";
	$result = my_query($query);
	while( $row = my_fetch_array($result) ){
		$data_in_eksepsi = eksepsi_data_komponen($karyawan_id , $row['pay_komponen_gaji_id'] );
		if($data_in_eksepsi){
			$nominal = $data_in_eksepsi;
		}else{
			$nominal = get_value_from_komponen_gaji( $karyawan_id , $row['pay_komponen_gaji_id']);
		}
		 
		if((int) $nominal == 0)continue;
		$datas = array(
			'karyawan_id'=>my_type_data_int($karyawan_id),
			'pay_komponen_gaji_id'=>my_type_data_int($row['pay_komponen_gaji_id']),
			'nominal_hitung'=>my_type_data_str($nominal),
			'created_on'=>my_type_data_function('NOW()'),
			'user_updated_id'=>my_type_data_int($_SESSION['user_id']),
		);
		my_insert_record( 'temp_pay_kalkulasi' , $datas);
	
	}
	return true;
}

function eksepsi_data_komponen($karyawan_id , $pay_komponen_gaji_id ){
	$query = "SELECT komponen_nominal_tetap FROM pay_komponen_exception 
		WHERE karyawan_id = {$karyawan_id}
		AND pay_komponen_gaji_id = {$pay_komponen_gaji_id} LIMIT 1";
	$result = my_query($query);
	if(my_num_rows($result) > 0){
		$row = my_fetch_array($result);
		return $row['komponen_nominal_tetap'];
	}
	return false;
}

function proses_kalkulasi_all(){
	my_query('TRUNCATE temp_pay_rekening_nominal');
	my_query('TRUNCATE temp_pay_kalkulasi');
	my_query('TRUNCATE temp_kalkulasi_pajak');
	my_query('TRUNCATE temp_kalkulasi_pajak_gross_up');
	$query 	= "SELECT * FROM karyawan ";
	$result = my_query($query);
	$i = 1;
	$p = new ProgressBar();
	echo '<center><div style="width: 400px;">';
	$p->render();
	echo '</div></center>';
	$size = my_num_rows($result);
	while($row = my_fetch_array($result)){
		proses_kalkulasi_karyawan($row['karyawan_id']);
		kalkulasi_pajak($row['karyawan_id'] , $row['tax_ptkp_category_id']);
		kalkulasi_pajak_gross_up( $row['karyawan_id'] , $row['tax_ptkp_category_id'] );
		proses_distribusi_transfer($row['karyawan_id'] , $row['metode_pajak']);
		$p->setProgressBarProgress($i*100/$size); 
		$i++;
	}
	$p->setProgressBarProgress(100);
	return $i;
}

function proses_distribusi_transfer($karyawan_id , $metode){
	$total_thp  = take_home_pay($karyawan_id , $metode);
	$query = "SELECT * FROM karyawan_bank_account WHERE karyawan_id = {$karyawan_id}";
	$result = my_query($query);
	while($row = my_fetch_array($result)){
		$nominal =  round($total_thp * ( $row['persen']/ 100) );
		save_nominal_transfer($row['rekening_id'] , $row['persen'] , $nominal);
	}
	return true;
}


function get_biaya_jabatan($total_pendapatan){
	$hasil = 0.05 * $total_pendapatan;
	if($hasil > 500000)	return 500000;
	return $hasil;
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


function get_pph_gross($karyawan_id){
	$query = "SELECT nominal_pajak FROM temp_kalkulasi_pajak_gross_up
		WHERE pay_komponen_pajak_id = 2 
		AND karyawan_id = {$karyawan_id}";
	$result = my_query($query);
	$row = my_fetch_array($result);
	if($row['nominal_pajak'] > 0)
	return $row['nominal_pajak'];
	
	return 0;
}
function get_pph_netto($karyawan_id){
	$query = "SELECT nominal_pajak FROM temp_kalkulasi_pajak 
		WHERE pay_komponen_pajak_id = 23
		AND karyawan_id = {$karyawan_id}";
	$result = my_query($query);
	$row = my_fetch_array($result);
	if($row['nominal_pajak'] > 0)
		return $row['nominal_pajak'];
	
	return 0;
} 

function save_nominal_transfer($rekening_id ,$persen , $nominal){
	$datas = array(
		'rekening_id'=> my_type_data_int($rekening_id),
		'persen'=> my_type_data_str($persen),
		'nominal'=> my_type_data_str($nominal),
		'created_on'=>my_type_data_function('NOW()'),
		'user_updated_id'=>my_type_data_int($_SESSION['user_id'])
	
	);
	return my_insert_record('temp_pay_rekening_nominal', $datas);
}


function excel_kalkulasi_download( ){
	$header = array(
		  
		'Nik'=>array('style'=>'text-align:center;border-bottom:2px solid;width:20%'),   
		'Nama karyawan'=>array('style'=>'text-align:right;border-bottom:2px solid;width:40%'),   
		'Pendapatan'=>array('style'=>'text-align:right;border-bottom:2px solid;width:35%'),   
		'Potongan'=>array('style'=>'text-align:right;border-bottom:2px solid;width:50%'),   
		'Subsidi'=>array('style'=>'text-align:right;border-bottom:2px solid;width:35%'),   
		'Metode pajak'=>array('style'=>'text-align:right;border-bottom:2px solid;width:20%'),   
		'Nilai PPh'=>array('style'=>'text-align:right;border-bottom:2px solid;width:30%'),   
		'Take home pay'=>array('style'=>'text-align:right;border-bottom:2px solid;width:35%'),   
	);
	$query 	= "SELECT * FROM karyawan ";
	$result = my_query($query); 
	$row = array();
	while(	$ey = my_fetch_array($result) ){
		
		$pendapatan = get_total_nominal_categori_group($ey['karyawan_id'] , 'pendapatan' , $ey['metode_pajak'] );
		$subsidi 	= get_total_nominal_categori_group($ey['karyawan_id'] , 'subsidi' , $ey['metode_pajak'] );
		$potongan 	= get_total_nominal_categori_group($ey['karyawan_id'] , 'potongan' , $ey['metode_pajak'] );
		$thp 		= take_home_pay($ey['karyawan_id'] , $ey['metode_pajak']);
		
		$metode = array(
					'0'=>'Nett',
					'1'=>'Gross'
				);
		$pajak = ($ey['metode_pajak'] == '1') ? get_pph_gross($ey['karyawan_id']) : get_pph_netto($ey['karyawan_id']) ;
		$row[] = array( 
			'nik' => $ey['karyawan_nik'] ,
			'nama_karyawan' => $ey['nama_karyawan'] ,
			'pendapatan' => $pendapatan ,
			'potongan' => $potongan ,
			'subsidi' => $subsidi  ,
			'metode' => $metode[$ey['metode_pajak']] ,
			'PPh' => $pajak ,
			'THP' => $thp  ,
		);
	}
	$datas = table_rows_excel($row); 
	return table_builder_excel($header , $datas ,  24 ,false );
}