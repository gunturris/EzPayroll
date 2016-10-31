<?php

function list_komponen_hasil(){
	$headers= array(   
		'No' => array( 'width'=>'5%','style'=>'text-align:center;' ),  
		'Kode' => array( 'width'=>'10%','style'=>'text-align:center;' ),  
		'Nama komponen' => array( 'width'=>'35%','style'=>'text-align:center;' ), 
		'Kategori' => array( 'width'=>'15%','style'=>'text-align:center;' ), 
		'Karyawan' => array( 'width'=>'10%','style'=>'text-align:center;' ), 
		'Nominal' => array( 'width'=>'15%','style'=>'text-align:center;' ), 
		'Aksi' => array( 'width'=>'10%','style'=>'text-align:center;' ) 
	);

	
	
	$query 	= "SELECT * FROM pay_komponen_gaji ";
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
		$datas =  get_sumary_komponen($ey['pay_komponen_gaji_id']);
		
		$detailproperty = array(
			'href'=>'index.php?com='.$_GET['com'].'&task=detail&id=' . $ey['pay_komponen_gaji_id'] , 
			'title'=>'Rincian data komponen', 
		);
		$detail_button = button_icon( 'b_props.png' , $detailproperty  );
		
		$row[] = array( 
			'#' => position_text_align( $i, 'center'),  
			'Kode' => position_text_align( $ey['pay_komponen_gaji_code'],  'center'),  
			'Nama komponen' => $ey['pay_komponen_gaji_label'],  
			'kategori' => position_text_align( ucfirst($ey['type']),    'center'),    
			'karyawan' =>  position_text_align($datas['total_karyawan'],      'center'),  
			'total' =>  position_text_align( rp_format($datas['total_nominal']), 'right'),  
			'detail' => position_text_align( $detail_button, 'center'),   
		);
	}
	
	$datas = table_rows($row); 
	$paging = $kgPagerOBJ ->showPaging();
	return  table_builder($headers , $datas ,  5 , false , $paging  ).'<br/>'; 
}

function get_sumary_komponen($komponen_id){
	$query = "SELECT COUNT(*) AS total_karyawan , SUM(nominal_hitung) AS total_nominal
	FROM temp_pay_kalkulasi WHERE pay_komponen_gaji_id = {$komponen_id}";
	$result =my_query($query);
	$row = my_fetch_array($result);
	return $row;
}

function list_karyawan_by_komponen($komponen_id){
	$komponen = my_get_data_by_id('pay_komponen_gaji','pay_komponen_gaji_id',$komponen_id);
	$jurnal = my_get_data_by_id('pay_jurnal_gaji' ,'pay_jurnal_gaji_id', $komponen['pay_jurnal_gaji_id']);
	$datas = get_sumary_komponen($komponen_id); 
	$view = '  <h4 id="grid-column-ordering">'.$komponen['pay_komponen_gaji_code']
										.' / '.$komponen['pay_komponen_gaji_label'].'</h3>
				<h5><b>Jurnal :</b> <i>'.$jurnal['pay_jurnal_gaji_label'].' ('.$jurnal['pay_jurnal_gaji_code'].')</i></h5>	
				 <h5>  <b>Total nominal :</b> <i>Rp. '.rp_format($datas['total_nominal']).' </i></h5>	
				 ' ;
	
	$headers= array(   
		'No' => array( 'width'=>'5%','style'=>'text-align:center;' ),    
		'NIK' => array( 'width'=>'15%','style'=>'text-align:center;' ), 
		'Karyawan' => array( 'width'=>'45%','style'=>'text-align:center;' ), 
		'TMB' => array( 'width'=>'15%','style'=>'text-align:center;' ), 
		'Nominal' => array( 'width'=>'20%','style'=>'text-align:center;' ) 
	);
	
	
	$query = "SELECT * FROM temp_pay_kalkulasi 
		WHERE pay_komponen_gaji_id = {$komponen_id}
		ORDER BY karyawan_id ASC";
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
	$pager_url  ="index.php?com={$_GET['com']}&task={$task}&field={$field}&key={$key}&id={$komponen_id}&halaman=";	 
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
		$karyawan = loaddata_karyawan($ey['karyawan_id']);
		$row[] = array(   
			'No' => position_text_align($i ,    'center'),
			'NIK' => position_text_align($karyawan['karyawan_nik'] ,  'center'),
			'Karyawan' =>  $karyawan['nama_karyawan'], 
			'TMB' =>position_text_align( date('d-m-Y', strtotime($karyawan['tanggal_bekerja'])), 'center'),
			'Nominal' => position_text_align(rp_format($ey['nominal_hitung']),'right')
		);
	}
	
	$datas = table_rows($row); 
	$paging = $kgPagerOBJ ->showPaging();
	$navigasi = array(
		'<input class="btn btn-primary" style="float:right;"  type="button" value="Excel" onclick="javascript:location.href=\'index.php?com='.$_GET['com'].'&task=excel&id='.$komponen_id.'\'"/>',
		'<input class="btn btn-primary" style="float:right;margin-right:5px;"  type="button" value="Kembali" onclick="javascript:location.href=\'index.php?com='.$_GET['com'].'\'"/>',
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
	return  $view . $box . table_builder($headers , $datas ,  5 , false , $paging  ).'<br/>'; 
}

function excel_komponen_download($id){
	$header = array(
		  
		'Kode Komponen'=>array('style'=>'text-align:left;border-bottom:2px solid;width:15%'),  
		'Nama Komponen'=>array('style'=>'text-align:left;border-bottom:2px solid;width:40%'),  
		'Tipe'=>array('style'=>'text-align:left;border-bottom:2px solid;width:15%'),  
		'Nik'=>array('style'=>'text-align:right;border-bottom:2px solid;width:20%'),   
		'Nama karyawan'=>array('style'=>'text-align:right;border-bottom:2px solid;width:40%'),   
		'Nominal'=>array('style'=>'text-align:right;border-bottom:2px solid;width:30%'),   
	);
	$query = "SELECT * FROM temp_pay_kalkulasi a
		INNER JOIN pay_komponen_gaji b ON a.pay_komponen_gaji_id = b.pay_komponen_gaji_id
		WHERE a.pay_komponen_gaji_id = {$id}
		ORDER BY a.karyawan_id ASC";
	$result = my_query($query);
	$row = array();
	while(	$ey = my_fetch_array($result) ){
		$karyawan = loaddata_karyawan($ey['karyawan_id']);
		$row[] = array(
			'kode' => $ey['pay_komponen_gaji_code'] ,
			'nama_komponen' => $ey['pay_komponen_gaji_label'] ,
			'tipe' => $ey['type'] ,
			'nik' => $karyawan['karyawan_nik'] ,
			'nama_karyawan' => $karyawan['nama_karyawan'] ,
			'nominal' => $ey['nominal_hitung'] ,
		);
	}
	$datas = table_rows_excel($row); 
	return table_builder_excel($header , $datas ,  24 ,false );
}