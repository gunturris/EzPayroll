<?php

function list_datas_karyawan($periode_id){
	$headers= array(  
		'NIK' => array( 'width'=>'10%','style'=>'text-align:center;' ), 
		'Nama karyawan' => array( 'width'=>'34%','style'=>'text-align:left;' ), 
		'Pendapatan' => array( 'width'=>'12%','style'=>'text-align:center;' ),  
		'Potongan' => array( 'width'=>'12%','style'=>'text-align:center;' ),  
		'Take home pay' => array( 'width'=>'14%','style'=>'text-align:center;' ),  
		'Aksi' => array( 'width'=>'8%','style'=>'text-align:center;' ), 
		
	);
	$nik_start = isset($_GET['nik_start']) ? $_GET['nik_start'] : '00000000';
	$nik_end = isset($_GET['nik_end']) ? $_GET['nik_end'] : '99999999';
	$query 	= "SELECT karyawan_id , karyawan_nik , karyawan_nama FROM log_payroll_reguler_komponen 
		WHERE pay_periode_id = {$periode_id} ";
	if(isset($_GET['key'])){
			$query 	.= " AND (karyawan_nik BETWEEN '{$_GET['nik_start']}' AND '{$_GET['nik_end']}' )";
			$query 	.= " OR karyawan_nama LIKE '%{$_GET['key']}%'";
	}	
	$query 	.= " GROUP BY karyawan_nik ";
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
				'href'=>'index.php?com='.$_GET['com'].'&task=print_per_karyawan&karyawan_id=' . $ey['karyawan_id'] .'&periode_id='.$periode_id , 
				'title'=>'Cetak slip gaji '.$ey['karyawan_nama']
		);	
		$detail_button = button_icon( 'b_props.png' , $detailproperty  );

		$pendapatan = get_total_nominal_categori_group_hitung($ey['karyawan_id'] ,$periode_id , 'pendapatan' );
		$potongan = get_total_nominal_categori_group_hitung($ey['karyawan_id'] ,$periode_id, 'potongan' );
		$take_home_pay = ($pendapatan - $potongan);
		$row[] = array( 
		'NIK' =>  position_text_align($ey['karyawan_nik'], 'center'),    
		'Nama karyawan' => $ey['karyawan_nama'],  
		'Pendapatan' => position_text_align(rp_format($pendapatan), 'right'),  
		'Potongan' => position_text_align(rp_format($potongan), 'right'),    
		'Take homepay' => position_text_align(rp_format($take_home_pay), 'right'),    
		'op'=> position_text_align( $detail_button    , 'center')
		);
	}
	
	$datas = table_rows($row);
	$navigasi = array(
		'<input class="btn btn-primary" style="float:right;"  type="button" value="Excel" onclick="javascript:location.href=\'index.php?com='.$_GET['com'].'&task=excel&periode_id='.$periode_id.'\'"/>',
		'<input class="btn btn-primary" style="float:right;margin-right:5px"  type="button" value="Cetak masal" onclick="javascript:location.href=\'index.php?com='.$_GET['com'].'&task=cetak_masal&nik_start='.$nik_start.'&nik_end='.$nik_end.'&periode_id='.$periode_id.'\'"/>',
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

function get_total_nominal_categori_group_hitung($karyawan_id , $periode_id , $type ){
	$query = "	SELECT  SUM(pay_komponen_nominal) AS nominal
				FROM log_payroll_reguler_komponen
				WHERE pay_periode_id = {$periode_id}  
				AND pay_komponen_gaji_type = '{$type}' 
				AND karyawan_id = '{$karyawan_id}' 
				";
	$result = my_query($query);
	$row = my_fetch_array($result);
	return $row['nominal'];
}

function excel_slip_gaji_download($periode_id){
	$header= array(  
		'NIK' => array(  'style'=>'text-align:center;width:130px;' ), 
		'Nama karyawan' => array( 'style'=>'text-align:left;width:220px;' ), 
		'Pendapatan' => array( 'style'=>'text-align:center;width:180px;' ),  
		'Subsidi' => array(  'style'=>'text-align:center;width:180px;' ),  
		'Potongan' => array(  'style'=>'text-align:center;width:180px;' ),  
		'Take home pay' => array(  'style'=>'text-align:center;width:180px;' ),    	
	);
	$query 	= "SELECT karyawan_id , 
				karyawan_nik , karyawan_nama FROM log_payroll_reguler_komponen 
		WHERE pay_periode_id = {$periode_id} ";
	if(isset($_GET['nik_start']) AND isset($_GET['nik_end']) ){
			$query 	.= " AND (karyawan_nik BETWEEN '{$_GET['nik_start']}' AND '{$_GET['nik_end']}' )";
	}	
	$query 	.= " GROUP BY karyawan_nik ";
	$result = my_query($query);
	$row = array();
	$i=0;
	while($ey = my_fetch_array($result)){
		$i++;
		$detailproperty = array(
				'href'=>'index.php?com='.$_GET['com'].'&task=print_per_karyawan&karyawan_id=' . $ey['karyawan_id'] , 
				'title'=>'Cetak slip gaji '.$ey['karyawan_nama']
		);	
		$detail_button = button_icon( 'b_props.png' , $detailproperty  );

		$pendapatan = get_total_nominal_categori_group_hitung($ey['karyawan_id'] ,$periode_id , 'pendapatan' );
		$subsidi = get_total_nominal_categori_group_hitung($ey['karyawan_id'] ,$periode_id, 'subsidi' );
		$potongan = get_total_nominal_categori_group_hitung($ey['karyawan_id'] ,$periode_id, 'potongan' );
		$take_home_pay = ($pendapatan - $potongan);
		$row[] = array( 
			'NIK' 	=>  $ey['karyawan_nik'],    
			'Nama karyawan' => $ey['karyawan_nama'],  
			'Pendapatan' 	=> $pendapatan ,  
			'Subsidi' 		=> $subsidi ,    
			'Potongan' 		=> $potongan ,    
			'Take homepay'  => $take_home_pay 
		);
	}
	
	
	$datas = table_rows_excel($row); 
	return table_builder_excel($header , $datas , 6 ,false ); 
}


function slip_gaji_cetak($karyawan ,  $periode_id ){
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
<small>Slip gaji periode '.$periode_label.'</small></h2> 
Karyawan   : '.$karyawan['karyawan_nik'].' / '.$karyawan['karyawan_nama'].' 
Jabatan    : <i>'.$karyawan['nama_jabatan'].'</i> / <b>'.$karyawan['gologan_jabatan'].'</b>  
NPWP       : '.$karyawan['npwp'].' / <b>'.$karyawan['tax_ptkp'].'</b>  

<u>A. Pendapatan</u> :
';
 
$total_pendapatan = 0; 
$result = list_category('pendapatan' , $periode_id , $karyawan['karyawan_id']);
while($datas_pendapatan = my_fetch_array($result)){
	$content .= content_komponen(
		$datas_pendapatan['pay_komponen_gaji_kode'].'/ '.$datas_pendapatan['pay_komponen_gaji_name'].' ' , 
		$datas_pendapatan['pay_komponen_nominal'] )."\n";
	$total_pendapatan += $datas_pendapatan['pay_komponen_nominal'];
}
$content .=   content_komponen('Subtotal',$total_pendapatan);
$content .='
	
<u>B. Potongan</u> :	
';
$total_potongan = 0; 
$result = list_category('potongan' , $periode_id , $karyawan['karyawan_id']);

while($datas_pendapatan = my_fetch_array($result)){
	$content .= content_komponen(
		$datas_pendapatan['pay_komponen_gaji_kode'].'/ '.$datas_pendapatan['pay_komponen_gaji_name'] .' ', 
		$datas_pendapatan['pay_komponen_nominal'] )."\n";
	$total_potongan += $datas_pendapatan['pay_komponen_nominal'];
} 
$content .=   content_komponen('Subtotal (B) ',$total_potongan);
$content .='

<u>C. Subsidi</u> :
';
$total = 0; 
$result = list_category('subsidi' , $periode_id , $karyawan['karyawan_id']);

while($datas_pendapatan = my_fetch_array($result)){
	$content .= content_komponen(
		$datas_pendapatan['pay_komponen_gaji_kode'].'/ '.$datas_pendapatan['pay_komponen_gaji_name'] .' ', 
		$datas_pendapatan['pay_komponen_nominal'] )."\n";
	$total += $datas_pendapatan['pay_komponen_nominal'];
} 
$content .=   content_komponen('Subtotal (C) ',$total);
$content .='

<u>D. Take homepay</u> : 
'.content_komponen('Total (A-B)' , ($total_pendapatan - $total_potongan) ).'

'.bank_transfer( $karyawan['karyawan_id'], $periode_id).'


'.str_pad(' ',40).'Mengetahui
'.str_pad(' ',40).'Kepala bagian penggajian



'.str_pad(' ',45).'( Julfikar )	
</pre>'; 
return $content;
}

function bank_transfer($karyawan_id , $periode_id){
	$query = "SELECT * FROM log_payroll_reguler_bank_transfer 
		WHERE karyawan_id = {$karyawan_id} AND pay_periode_id = {$periode_id} ";
	$result = my_query($query);
	$content = '<u>E. Bank transfer</u> :
';
	$total = 0;
	$i=0;
	while($row = my_fetch_array($result) ){
	$i++;
	$content .= content_komponen(
		str_pad( substr($row['bank_nama'],0,20),20).' '. $row['rekening_nomor'].'/'.$row['rekening_nama'] .' ', 
		$row['nominal_transfer'] )."\n";
		$total +=$row['nominal_transfer'];
	}
	if($i> 1)	$content .= content_komponen(  str_pad(' ',40).'Total'   , $total);
	return $content;
	
}


function content_komponen($komponen_name , $nominal ){
	return str_pad($komponen_name, 52 , "."). str_pad( rp_format($nominal), 15 ,'.' ,STR_PAD_LEFT) ;
}

function print_per_karyawan($karyawan_id , $periode_id){
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
	
	return slip_gaji_cetak($karyawan ,  $periode_id );	
}

function list_category($type , $periode_id , $karyawan_id){
	$query = "SELECT 
				pay_komponen_gaji_kode ,
				pay_komponen_gaji_name ,
				pay_komponen_nominal
			FROM log_payroll_reguler_komponen
			WHERE pay_periode_id = {$periode_id} 
			AND karyawan_id = {$karyawan_id} 
			AND pay_komponen_gaji_type = '{$type}'
			ORDER BY pay_komponen_gaji_id ASC";
	return my_query($query); 
}

function cetak_masal($nik_start ,$nik_end , $periode_id){
	$query 	= "SELECT karyawan_id , karyawan_nik , karyawan_nama FROM log_payroll_reguler_komponen 
		WHERE pay_periode_id = {$periode_id} ";
	if(isset($_GET['nik_start']) AND isset($_GET['nik_end']) ){
			$query 	.= " AND (karyawan_nik BETWEEN '{$_GET['nik_start']}' AND '{$_GET['nik_end']}' )";
	}	
	$query 	.= " GROUP BY karyawan_nik LIMIT 20";
	
	$result = my_query($query);
	$content = '<body  onload = "javascript:breakeveryheader()">';
	while($row = my_fetch_array($result)){
		$karyawan_ori_data = loaddata_karyawan($row['karyawan_id']);

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
		$content .=  slip_gaji_cetak($karyawan ,  $periode_id ).'<P>';
	}
	$content .= '</body>
	
	<script>
function breakeveryheader(){
if (!document.getElementById){
	alert("You need IE5 or NS6 to run this example")
	return
}
var thestyle=  "always" ;
for (i=0; i<document.getElementsByTagName("P").length; i++)
	document.getElementsByTagName("P")[i].style.pageBreakBefore=thestyle
}
//window.print();
</script>   
	';
	return $content;
}