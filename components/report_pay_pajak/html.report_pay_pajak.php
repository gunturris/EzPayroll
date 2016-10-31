<?php


function list_karyawan_pajak_proses( $periode_id){ 
	$headers= array(  
		'No' => array( 'width'=>'5%','style'=>'text-align:center;' ), 
		'NIK' => array( 'width'=>'10%','style'=>'text-align:center;' ), 
		'Nama karyawan' => array( 'width'=>'39%','style'=>'text-align:left;' ), 
		'Metode' => array( 'width'=>'12%','style'=>'text-align:center;' ),  
		'PTKP' => array( 'width'=>'17%','style'=>'text-align:center;' ),  
		'PPh' => array( 'width'=>'12%','style'=>'text-align:center;' ),  
		'Aksi' => array( 'width'=>'5%','style'=>'text-align:center;' ),  
	);

	
	$nik_start = isset($_GET['nik_start']) ? $_GET['nik_start'] : '00000000';
	$nik_end = isset($_GET['nik_end']) ? $_GET['nik_end'] : '99999999';
	$query 	= "SELECT a.karyawan_id , a.karyawan_nik , 
		a.karyawan_nama ,b.tax_ptkp_category_id , b.metode_pajak , b.nama_karyawan
		FROM log_payroll_reguler_pajak a
		INNER JOIN karyawan b ON a.karyawan_id = b.karyawan_id
		WHERE a.pay_periode_id = {$periode_id} ";
	if(isset($_GET['key'])){
			$query 	.= " AND (a.karyawan_nik BETWEEN '{$_GET['nik_start']}' AND '{$_GET['nik_end']}' )";
			 
	}	
	$query 	.= " GROUP BY a.karyawan_nik ";
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
	$pager_url  ="index.php?com={$_GET['com']}&nik_start={$nik_start}&nik_end={$nik_end}&task={$task}&periode_id={$periode_id}&key={$key}&halaman=";	 
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
				'href'=>'index.php?com='.$_GET['com'].'&task=detail_karyawan&periode_id='.$periode_id.'&karyawan_id=' . $ey['karyawan_id'] , 
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
		$nominal_pajak =  get_pph ($ey['karyawan_id'] , $periode_id);
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
		'<input class="btn btn-primary" style="float:right;"  type="button" value="Excel" onclick="javascript:location.href=\'index.php?com='.$_GET['com'].'&task=excel&periode_id='.$periode_id.'\'"/>',
	//	'<input class="btn btn-primary" style="float:right;margin-right:5px"  type="button" value="Cetak masal" onclick="javascript:location.href=\'index.php?com='.$_GET['com'].'&task=cetak_masal&nik_start='.$nik_start.'&nik_end='.$nik_end.'&periode_id='.$periode_id.'\'"/>',
	);
	$form_Search  =
	' ';
	
	$view = form_header( "form_pay_slip_search_data" , "form_pay_slip_search_data"  );
	
	$periode_ids =  array( );
	$query = "SELECT pay_periode_reguler_id , payroll_date 
		FROM pay_periode_reguler 
		WHERE status_proses = 'Closed'
		ORDER BY pay_periode_reguler_id DESC";	
	$result = my_query($query);
	while($row_periode_id = my_fetch_array($result)){
		$periode_ids[$row_periode_id['pay_periode_reguler_id']] = date('d M Y ' ,strtotime($row_periode_id['payroll_date']) );
	}
	$periode_id = array(
		'name'=>'periode_id',
		'value'=>( isset($_GET['periode_id']) ? $_GET['periode_id'] : 0) ,
	);
	$form_periode_id = form_dropdown($periode_id , $periode_ids);
	$view .= form_field_display(  $form_periode_id , "Tanggal payroll"    ); 
	
	$start_nik = array(
		'name'=>'nik_start',
		'type'=>'textfield',
		'id'=>'nik_start',
		'name'=>'nik_start',
		'value'=>( isset($_GET['nik_start']) ? $_GET['nik_start'] : '00000000' ) ,
	);
	$form_start_nik = form_dynamic($start_nik);  
	$end_nik = array(
		'name'=>'nik_end', 
		'id'=>'nik_end',
		'type'=>'textfield',
		'name'=>'nik_end',
		'value'=>( isset($_GET['nik_end']) ? $_GET['nik_end'] : '99999999' ) ,
	);
	$form_start_end = form_dynamic($end_nik  );
	$view .= form_field_display( $form_start_nik  , "Nik awal"    ); 
	$view .= form_field_display( $form_start_end  , "Nik akhir"    );

	$submit = array(
		'value' => ( ' Cari data ' ),
		'name' => 'simpan', 
		'type'=>'submit','class'=>'main_button'
	);
	$form_submit= form_dynamic($submit);
	 
	
	$view .= form_field_display( $form_submit , "&nbsp;" );	
	$hidden = '<input type="hidden" name="com" value="'.$_GET['com'].'" />';
	$hidden .= '<input type="hidden" name="key" value="1" />';
	$view .= form_field_display(  "&nbsp;".$hidden, "&nbsp;" );	
	$view .= form_footer();
	
	$box = header_box( $form_Search , $navigasi );
	$paging = $kgPagerOBJ ->showPaging();
	return str_replace( 'method="post"' ,'method="get"',$view ).$box.table_builder($headers , $datas ,  6 , false , $paging  ); 
}
 
function get_pph($karyawan_id , $periode_id){
	$query = "SELECT nominal_hitung FROM log_payroll_reguler_pajak 
		WHERE  pajak_komponen_id = 2
		AND karyawan_id = {$karyawan_id} AND pay_periode_id = {$periode_id} ";
	$result = my_query($query);
	$row = my_fetch_array($result);
	if((int) $row['nominal_hitung'] > 0){
		return $row['nominal_hitung'];
	}
	$query = "SELECT nominal_hitung FROM log_payroll_reguler_pajak 
		WHERE pajak_komponen_id = 23
		AND karyawan_id = {$karyawan_id} AND pay_periode_id = {$periode_id} ";
	$result = my_query($query);
	$row = my_fetch_array($result);
	return $row['nominal_hitung'];
} 


function excel_pajak_download($periode_id){
	$header= array(  
		'NIK' => array(  'style'=>'text-align:center;width:130px;' ), 
		'Nama karyawan' => array( 'style'=>'text-align:left;width:220px;' ), 
		'Metode' => array( 'style'=>'text-align:center;width:180px;' ),  
		'PTKP KATEGORI' => array(  'style'=>'text-align:center;width:180px;' ),  
		'PTKP NOMINAL' => array(  'style'=>'text-align:center;width:180px;' ),  
		'PPh' => array(  'style'=>'text-align:center;width:180px;' ),      	
	); 
	$nik_start = isset($_GET['nik_start']) ? $_GET['nik_start'] : '00000000';
	$nik_end = isset($_GET['nik_end']) ? $_GET['nik_end'] : '99999999';
	$query 	= "SELECT a.karyawan_id , a.karyawan_nik , 
		a.karyawan_nama ,b.tax_ptkp_category_id , b.metode_pajak , b.nama_karyawan
		FROM log_payroll_reguler_pajak a
		INNER JOIN karyawan b ON a.karyawan_id = b.karyawan_id
		WHERE a.pay_periode_id = {$periode_id} ";
	if(isset($_GET['key'])){
			$query 	.= " AND (a.karyawan_nik BETWEEN '{$_GET['nik_start']}' AND '{$_GET['nik_end']}' )";
			 
	}	
	$query 	.= " GROUP BY a.karyawan_nik "; 
	$result = my_query($query);
	$row = array();
	$i=0;
	while($ey = my_fetch_array($result)){
		$i++; 
		$metode = array(
			'0'=>'Gross',
			'1'=>'Nett',
		);
		$ptkp = my_get_data_by_id('tax_ptkp_categori',
			'tax_ptkp_categori_id',
			$ey['tax_ptkp_category_id']);
		$nominal_pajak =  get_pph ($ey['karyawan_id'] , $periode_id);
		$nominal_pajak = ($nominal_pajak < 0) ? 0 : $nominal_pajak;
		$row[] = array( 
			'NIK' 	=>  $ey['karyawan_nik'],    
			'Nama karyawan' => $ey['karyawan_nama'],  
			'Metode' 	=>  $metode[$ey['metode_pajak']],  
			'PTKP Kategori' 		=> $ptkp['tax_ptkp_categori_code'] ,    
			'PTKP Nominal' 		=> $ptkp['ptkp_nominal'] ,    
			'PPh' 		=> $nominal_pajak  
		);
		 
	}
	
	
	$datas = table_rows_excel($row); 
	return table_builder_excel($header , $datas , 6 ,false ); 
}

//-------------DETAIL

function pajak_detail_karyawan($karyawan_id , $periode_id ){
	$headers= array(  
		'No' => array( 'width'=>'10%','style'=>'text-align:center;' ),  
		'Deskripsi' => array( 'width'=>'75%','style'=>'text-align:left;' ),   
		'Nominal' => array( 'width'=>'15%','style'=>'text-align:center;' ),  
	);
	 
		$query = "SELECT * FROM log_payroll_reguler_pajak 
		WHERE karyawan_id = {$karyawan_id} 
		AND pay_periode_id = {$periode_id}
		ORDER BY  pajak_komponen_id ASC";
	 
	$result = my_query($query);
	$row = array();
	$i = 0;
	while( $ey = my_fetch_array($result) ){
		$i++;
		$row[] = array(
			'no'=>position_text_align($i  ,'center'),
			'nama'=>$ey['pajak_nama'],
			'nominal'=>position_text_align( rp_format($ey['nominal_hitung']) ,'right')
		
		);
	}
	$datas = table_rows($row);
	$navigasi = array(
		'<input class="btn btn-primary" style="float:right;"  type="button" value="Kembali" onclick="javascript:location.href=\'index.php?com='.$_GET['com'].'\'"/>',
		'<input class="btn btn-primary" style="float:right;margin-right:5px"  type="button" value="Cetak" onclick="javascript:location.href=\'index.php?com='.$_GET['com'].'&task=print_per_karyawan&periode_id='.$periode_id.'&karyawan_id='.$karyawan_id.'\'"/>',
	); 
	$form_Search  =
	'';
	$box = header_box( $form_Search , $navigasi );
	return $box  . table_builder($headers , $datas ,  3 , false  ); 
}


function list_pajak_by_karyawan($karyawan_id , $periode_id){
	$karyawan = my_get_data_by_id( 'karyawan' ,'karyawan_id' , $karyawan_id);
	$karyawan_status = my_get_data_by_id( 'karyawan_status' ,'karyawan_status_id' , $karyawan['karyawan_status_id'] );
	$karyawan_gol_jab = my_get_data_by_id( 'karyawan_gol_jab' ,'karyawan_gol_jab_id' , $karyawan['karyawan_gol_jab_id'] );
	
	$periode = my_get_data_by_id('pay_periode_reguler' , 'pay_periode_reguler_id', $periode_id );
	
	$view = '  <h3 id="grid-column-ordering">'.$karyawan['karyawan_nik'].' / '.$karyawan['nama_karyawan'].'</h3>
				<h5>'.$karyawan_status['karyawan_status_label'].' ('.$karyawan_gol_jab['karyawan_gol_jab_label'].') </h5>	
				<h4>Tanggal gaji '. $periode['payroll_date'].'  </h4>	
				' ;
	$view .= pajak_detail_karyawan($karyawan_id , $periode_id);
	return $view;
}

//--------CETAK
function laporan_pajak_karyawan($karyawan_id , $periode_id){
	$karyawan_ori_data = loaddata_karyawan($karyawan_id);

	$karyawan = array();
	$karyawan['karyawan_id']	= $karyawan_ori_data['karyawan_id'];
	$karyawan['karyawan_nik']	= $karyawan_ori_data['karyawan_nik'];
	$karyawan['karyawan_nama']	= $karyawan_ori_data['nama_karyawan'];
	$karyawan['npwp']			= $karyawan_ori_data['npwp'];
	$karyawan['nama_jabatan']	= $karyawan_ori_data['jabatan'];
	$ptkp = my_get_data_by_id('tax_ptkp_categori','tax_ptkp_categori_id', (int) $karyawan_ori_data['tax_ptkp_category_id']);
	$karyawan['tax_ptkp']		= $ptkp['tax_ptkp_categori_code'];
	$goljab =  my_get_data_by_id('karyawan_gol_jab','karyawan_gol_jab_id', (int)$karyawan_ori_data['karyawan_gol_jab_id']);
	$karyawan['gologan_jabatan'] = $goljab['karyawan_gol_jab_label'];
	
	return cetak_laporan_pajak($karyawan  , $periode_id);
}

function cetak_laporan_pajak($karyawan  , $periode_id){
 $periode = array(
		'01'=> 'Januari',
		'02'=> 'Februari',
		'03'=> 'Maret',
		'04'=> 'April',
		'05'=> 'Mei',
		'06'=> 'Juni',
		'07'=> 'Juli',
		'08'=> 'Agustus',
		'09'=> 'September',
		'10'=> 'Oktober',
		'11'=> 'Nopember',
		'12'=> 'Desember',
	);
	$periode_data = my_get_data_by_id('pay_periode_reguler' ,'pay_periode_reguler_id' ,$periode_id );
	$tanggal_gaji = date('m' , strtotime($periode_data['payroll_date']));
	$periode_label = $periode[$tanggal_gaji] .' '.date('Y' , strtotime($periode_data['payroll_date']));
	$content ='<pre>
<h2>PT GARIS TEGAK INDONESIA
<small>Perhitungan pajak gaji periode '.$periode_label.'</small></h2> 
Karyawan   : '.$karyawan['karyawan_nik'].' / '.$karyawan['karyawan_nama'].' 
Jabatan    : <i>'.$karyawan['nama_jabatan'].'</i> / <b>'.$karyawan['gologan_jabatan'].'</b>  
NPWP       : '.$karyawan['npwp'].' / <b>'.$karyawan['tax_ptkp'].'</b>


';  

	$query = "SELECT * FROM log_payroll_reguler_pajak 
		WHERE karyawan_id = {$karyawan['karyawan_id']} 
		AND pay_periode_id = {$periode_id}
		ORDER BY  pajak_komponen_id ASC";
	 
	$result = my_query($query);
	$i=0;
	while($row = my_fetch_array($result) ){
	$i++;
	$content .= 	content_komponen($row['pajak_nama'] , $row['nominal_hitung'] )."\n";
	if( ($i%5) == 0)$content .="\n";
	}
$content .='
'.str_pad(' ',40).'Mengetahui
'.str_pad(' ',40).'Kepala bagian penggajian



'.str_pad(' ',45).'( Julfikar )	
</pre>'; 
return $content;

}


function content_komponen($komponen_name , $nominal ){
	return str_pad($komponen_name, 60 , "."). str_pad( rp_format($nominal), 15 ,'.' ,STR_PAD_LEFT) ;
}