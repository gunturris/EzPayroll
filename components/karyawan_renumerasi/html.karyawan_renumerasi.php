<?php 

function list_karyawan_renumerasi(){


	$headers= array(  
		'NIK' => array( 'width'=>'10%','style'=>'text-align:center;' ), 
		'Nama karyawan' => array( 'width'=>'35%','style'=>'text-align:left;' ), 
		'TMB' => array( 'width'=>'15%','style'=>'text-align:center;' ),  
		'Gaji pokok' => array( 'width'=>'20%','style'=>'text-align:center;' ), 
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
				'href'=>'index.php?com='.$_GET['com'].'&task=detail&id=' . $ey['karyawan_id'] , 
				'title'=>'Edit'
		);	 
		$detail_button = button_icon( 'b_props.png' , $detailproperty  ); 
		$umur = 0;
		$row[] = array( 
		'NIK' =>  position_text_align($ey['karyawan_nik'], 'center'),    
		'Nama karyawan' => $ey['nama_karyawan'],  
		'TMB' => position_text_align($ey['tanggal_bekerja'], 'center'), 
		'Gapok' => position_text_align( rp_format($ey['basic_salary']), 'right'),   
		'op'=> position_text_align( $detail_button   , 'center')
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


function detail_renumerasi($karyawan_id = 0){ 
	$karyawan = my_get_data_by_id( 'karyawan' ,'karyawan_id' , $karyawan_id);
	$karyawan_status = my_get_data_by_id( 'karyawan_status' ,'karyawan_status_id' , $karyawan['karyawan_status_id'] );
	$karyawan_gol_jab = my_get_data_by_id( 'karyawan_gol_jab' ,'karyawan_gol_jab_id' , $karyawan['karyawan_gol_jab_id'] );
	 
	$view = '  <h4 id="grid-column-ordering">'.$karyawan['karyawan_nik'].' / '.$karyawan['nama_karyawan'].'</h3>
				<h5>'.$karyawan_status['karyawan_status_label'].' ('.$karyawan_gol_jab['karyawan_gol_jab_label'].') </h5>	
				'.( trim($karyawan['alamat']) <> '' ? '<p><b>Alamat</b><br/><i>'.$karyawan['alamat'].'<br/> '.$karyawan['alamat_kota'].'</i></p>' : '').'
				' ;
	$view .= tab_data($karyawan);
	return $view;
}

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
	</ul>

	<!-- Tab panes -->
	<div class="tab-content">
		<div class="tab-pane fade in active" id="pendapatan">
			<h4>Data pendapatan</h4> 
			'.get_load_renumerasi(  $karyawan  , 'pendapatan' ).'
		</div>
		<div class="tab-pane fade" id="subsidi">
			<h4>Data subsidi perusahaan</h4>
			'.get_load_renumerasi(  $karyawan  , 'subsidi' ).'
		</div>
		<div class="tab-pane fade" id="potongan">
			<h4>Data potongan</h4>
			'.get_load_renumerasi(  $karyawan  , 'potongan' ).'
		</div> 
	</div> 
	
	';
	return $view;
}

function get_load_renumerasi(  $karyawan  , $type ){
	
	$query = "SELECT * FROM pay_komponen_gaji a
	INNER JOIN pay_komponen_gaji_karyawan_status b 
		ON a.pay_komponen_gaji_id = b.pay_komponen_gaji_id
	WHERE a.type = '{$type}' AND b.karyawan_status_id = {$karyawan['karyawan_status_id']} ";
	$result = my_query($query);
	 
		$headers= array(  
			'Kode' => array( 'width'=>'15%','style'=>'text-align:center;' ), 
			'Nama komponen gaji' => array( 'width'=>'55%','style'=>'text-align:left;' ), 
			'Metode hitung' => array( 'width'=>'20%','style'=>'text-align:center;' ),   
			'Nominal' => array( 'width'=>'20%','style'=>'text-align:right;' ),   
			
		);
		$row = array();
		while( $ey = my_fetch_array($result) ){
			$nominal = get_value_from_komponen_gaji($karyawan['karyawan_id'] , $ey['pay_komponen_gaji_id']);
			if( (int) $nominal == 0)continue;
			
			$model = my_get_data_by_id('pay_model_komponen_gaji','pay_model_komponen_gaji_id',$ey['pay_model_komponen_gaji_id']);
			$row[] = array( 
				'Kode' =>  position_text_align($ey['pay_komponen_gaji_code'], 'center'),    
				'Nama karyawan' => $ey['pay_komponen_gaji_label'],   
				'Metode' => position_text_align( $model['metode_spesifik'], 'center'),   
				'Gapok' => position_text_align( rp_format($nominal), 'right'),   
			);
		}
		$datas = table_rows($row);
		return table_builder($headers , $datas ,  6 , false );
	 
}


