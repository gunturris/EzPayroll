<?php


function list_karyawan_pajak_proses(){ 
	$headers= array(  
		'No' => array( 'width'=>'5%','style'=>'text-align:center;' ), 
		'NIK' => array( 'width'=>'10%','style'=>'text-align:center;' ), 
		'Nama karyawan' => array( 'width'=>'39%','style'=>'text-align:left;' ), 
		'Metode' => array( 'width'=>'12%','style'=>'text-align:center;' ),  
		'PTKP' => array( 'width'=>'17%','style'=>'text-align:center;' ),  
		'PPh' => array( 'width'=>'12%','style'=>'text-align:center;' ),  
		'Aksi' => array( 'width'=>'5%','style'=>'text-align:center;' ),  
	);

	
	
	$query 	= " SELECT * FROM karyawan WHERE karyawan_id IN
				( SELECT karyawan_id FROM temp_unreg_kalkulasi_pajak 
				GROUP BY karyawan_id )";
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
				'href'=>'index.php?com='.$_GET['com'].'&task=detail&karyawan_id=' . $ey['karyawan_id'] , 
				'title'=>'Detail perhitungan pajak'
		);	
		$detail_button = button_icon( 'b_props.png' , $detailproperty  );
		$metode = array(
			'0'=>'Gross',
			'1'=>'Nett',
		);
		$ptkp = my_get_data_by_id('tax_ptkp_categori',
			'tax_ptkp_categori_id',
			$ey['tax_ptkp_category_id']);
		$nominal_pajak = ( (int) $ey['metode_pajak'] == 1) ? get_pph_netto($ey['karyawan_id']):get_pph_gross($ey['karyawan_id']);
		$nominal_pajak = ($nominal_pajak < 0) ? 0 : $nominal_pajak;
		$row[] = array( 
			'No' =>  position_text_align($i, 'center'),     
			'NIK' =>  position_text_align($ey['karyawan_nik'], 'center'),     
			'Nama karyawan' => $ey['nama_karyawan'],      
			'Metode' => $metode[$ey['metode_pajak']],      
			'PTKP' => position_text_align(rp_format($ptkp['ptkp_nominal'] ).' <b>('. $ptkp['tax_ptkp_categori_code'] .')</b>',   'right'),   
			'PPh' => position_text_align(rp_format($nominal_pajak),'right'),      
			'op'=> position_text_align( $detail_button    , 'center')
		);
	}
	
	$datas = table_rows($row);
	$navigasi = array(
		//'<input class="btn btn-primary" style="float:right;"  type="button" value="Kembali" onclick="javascript:location.href=\'index.php?com='.$_GET['com'].'\'"/>',
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

function list_pajak_by_karyawan($karyawan_id){
	$karyawan = my_get_data_by_id( 'karyawan' ,'karyawan_id' , $karyawan_id);
	$karyawan_status = my_get_data_by_id( 'karyawan_status' ,'karyawan_status_id' , $karyawan['karyawan_status_id'] );
	$karyawan_gol_jab = my_get_data_by_id( 'karyawan_gol_jab' ,'karyawan_gol_jab_id' , $karyawan['karyawan_gol_jab_id'] );
	 
	$view = '  <h4 id="grid-column-ordering">'.$karyawan['karyawan_nik'].' / '.$karyawan['nama_karyawan'].'</h3>
				<h5>'.$karyawan_status['karyawan_status_label'].' ('.$karyawan_gol_jab['karyawan_gol_jab_label'].') </h5>	
				' ;
	$view .= pajak_detail_karyawan($karyawan_id , $karyawan['metode_pajak']);
	return $view;
}

function pajak_detail_karyawan($karyawan_id , $metode){
	$headers= array(  
		'No' => array( 'width'=>'10%','style'=>'text-align:center;' ),  
		'Deskripsi' => array( 'width'=>'75%','style'=>'text-align:left;' ),   
		'Nominal' => array( 'width'=>'15%','style'=>'text-align:center;' ),  
	);
	if($metode == 1){
	$query = "SELECT * FROM temp_unreg_kalkulasi_pajak_gross_up a
		INNER JOIN pay_komponen_pajak b ON a.komponen_pajak_id = b.komponen_pajak_id
		WHERE karyawan_id = {$karyawan_id} 
		ORDER BY  a.komponen_pajak_id ASC";
	}else{
		$query = "SELECT * FROM temp_unreg_kalkulasi_pajak a
		INNER JOIN pay_komponen_pajak b ON a.komponen_pajak_id = b.komponen_pajak_id
		WHERE karyawan_id = {$karyawan_id} 
		ORDER BY  a.komponen_pajak_id ASC";
	}
	$result = my_query($query);
	$row = array();
	$i = 0;
	while( $ey = my_fetch_array($result) ){
		$i++;
		$row[] = array(
			'no'=>position_text_align($i  ,'center'),
			'nama'=>$ey['label_pajak'],
			'nominal'=>position_text_align( rp_format($ey['nominal_pajak']) ,'right')
		
		);
	}
	$datas = table_rows($row);
	$navigasi = array(
		'<input class="btn btn-primary" style="float:right;"  type="button" value="Kembali" onclick="javascript:location.href=\'index.php?com='.$_GET['com'].'\'"/>',
	); 
	$form_Search  =
	'';
	$box = header_box( $form_Search , $navigasi );
	return $box  . table_builder($headers , $datas ,  3 , false  ); 
}