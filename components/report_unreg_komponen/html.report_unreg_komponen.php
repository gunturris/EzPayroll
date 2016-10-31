<?php
function list_komponen_hasil($periode_id){
	$headers= array(   
		'No' => array( 'width'=>'5%','style'=>'text-align:center;' ),  
		'Kode' => array( 'width'=>'10%','style'=>'text-align:center;' ),  
		'Nama komponen' => array( 'width'=>'35%','style'=>'text-align:center;' ), 
		'Kategori' => array( 'width'=>'15%','style'=>'text-align:center;' ), 
		'Karyawan' => array( 'width'=>'10%','style'=>'text-align:center;' ), 
		'Nominal' => array( 'width'=>'15%','style'=>'text-align:center;' ), 
		'Aksi' => array( 'width'=>'10%','style'=>'text-align:center;' ) 
	); 
	$query 	= "SELECT 
			COUNT(karyawan_id) AS jumlah_karyawan ,
			pay_komponen_gaji_code , 
			pay_komponen_gaji_id ,
			pay_komponen_gaji_type ,
			pay_komponen_gaji_name ,
			SUM(pay_komponen_nominal) AS nominal_komponen 
			FROM log_payroll_unreguler_komponen 
		WHERE unreg_periode_id = {$periode_id}"; 
	$query 	.= " 
		GROUP BY pay_komponen_gaji_id 
		ORDER BY pay_komponen_gaji_id ASC";
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
	$pager_url  ="index.php?com={$_GET['com']}&task={$task}&periode_id={$periode_id}&key={$key}&halaman=";	 
	 
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
			'href'=>'index.php?com='.$_GET['com'].'&task=detail_komponen&periode_id='.$periode_id.'&komponen_id=' . $ey['pay_komponen_gaji_id'] , 
			'title'=>'Rincian data komponen', 
		);
		$detail_button = button_icon( 'b_props.png' , $detailproperty  );
		
		$row[] = array( 
			'#' => position_text_align( $i, 'center'),  
			'Kode' => position_text_align( $ey['pay_komponen_gaji_code'] ,  'center'),  
			'Nama komponen' => $ey['pay_komponen_gaji_name'],  
			'kategori' => position_text_align( ucfirst($ey['pay_komponen_gaji_type']),    'center'),    
			'karyawan' =>  position_text_align($ey['jumlah_karyawan'],      'center'),  
			'total' =>  position_text_align( rp_format($ey['nominal_komponen']), 'right'),  
			'detail' => position_text_align( $detail_button, 'center'),   
		);
	}
	
	$datas = table_rows($row); 
	$paging = $kgPagerOBJ ->showPaging();
	
	$navigasi = array(
		'<input class="btn btn-primary" style="float:right;"  type="button" value="Excel" onclick="javascript:location.href=\'index.php?com='.$_GET['com'].'&task=excel&periode_id='.$periode_id.'\'"/>',
	//	'<input class="btn btn-primary" style="float:right;margin-right:5px"  type="button" value="Cetak masal" onclick="javascript:location.href=\'index.php?com='.$_GET['com'].'&task=cetak_masal&nik_start='.$nik_start.'&nik_end='.$nik_end.'&periode_id='.$periode_id.'\'"/>',
	);
	$form_Search  =
	' ';
	
	$view = form_header( "form_pay_slip_search_data" , "form_pay_slip_search_data"  );
	
	$periode_ids =  array( );
	$query = "SELECT unreg_periode_id , unreg_periode_name 
		FROM unreg_periode 
		WHERE status_proses = 'closed'
		ORDER BY unreg_periode_id DESC";	
	$result = my_query($query);
	while($row_periode_id = my_fetch_array($result)){
		$periode_ids[$row_periode_id['unreg_periode_id']] = $row_periode_id['unreg_periode_name'] ;
	}
	$periode_id = array(
		'name'=>'periode_id',
		'value'=>( isset($_GET['periode_id']) ? $_GET['periode_id'] : 0) ,
	);
	$form_periode_id = form_dropdown($periode_id , $periode_ids);
	$view .= form_field_display(  $form_periode_id , "Nama penghasilan tidak tetap"    ); 
	
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
	return  str_replace( 'method="post"' ,'method="get"',$view ).$box.
		table_builder($headers , $datas ,  7 , false , $paging  ).'<br/>'; 
}

function excel_komponen_all($periode_id){
	$header= array(  
		'Kode komponen' => array(  'style'=>'text-align:center;width:130px;' ), 
		'Nama komponen' => array( 'style'=>'text-align:left;width:220px;' ), 
		'Kategori' => array( 'style'=>'text-align:center;width:180px;' ),  
		'Karyawan' => array(  'style'=>'text-align:center;width:180px;' ),  
		'Nominal' => array(  'style'=>'text-align:center;width:180px;' ),   	
	);  
	$query 	= "SELECT 
			COUNT(karyawan_id) AS jumlah_karyawan ,
			pay_komponen_gaji_code ,
			pay_komponen_gaji_type ,
			pay_komponen_gaji_name ,
			SUM(pay_komponen_nominal) AS nominal_komponen 
			FROM log_payroll_unreguler_komponen 
		WHERE unreg_periode_id = {$periode_id}"; 
	$query 	.= " 
		GROUP BY pay_komponen_gaji_id 
		ORDER BY pay_komponen_gaji_id ASC";
	$result = my_query($query);
	$row = array();
	while( $ey = my_fetch_array($result) ){
		$row[] = array( 
			'Kode' =>   $ey['pay_komponen_gaji_code'] ,   
			'Namakomponen' => $ey['pay_komponen_gaji_name'],  
			'kategori' =>  ucfirst($ey['pay_komponen_gaji_type']),   
			'karyawan' =>  $ey['jumlah_karyawan'],    
			'total' =>   $ey['nominal_komponen']      
		);
	}
	
	$datas = table_rows_excel($row); 
	return table_builder_excel($header , $datas , 6 ,false ); 
}

// ------------------------ DETAIL PERKOMPONEN

function list_detail_karyawan_by_komponen($komponen_id , $periode_id){
	$headers= array(   
		'No' => array( 'width'=>'5%','style'=>'text-align:center;' ),  
		'NIK' => array( 'width'=>'15%','style'=>'text-align:center;' ),  
		'Nama karyawan' => array( 'width'=>'65%','style'=>'text-align:center;' ), 
		'Nominal' => array( 'width'=>'20%','style'=>'text-align:center;' ), 
	); 
	
	$query = "SELECT karyawan_nik , karyawan_nama , pay_komponen_nominal 
		FROM log_payroll_unreguler_komponen
		WHERE pay_komponen_gaji_id = {$komponen_id} 
		AND unreg_periode_id = {$periode_id} ";
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
	$pager_url  ="index.php?com={$_GET['com']}&task={$task}&komponen_id={$komponen_id}&periode_id={$periode_id}&key={$key}&halaman=";	 
	 
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
	while( $ey = my_fetch_array($result) ){
		$i++;
		$row[] = array( 
			'#' => position_text_align( $i, 'center'),  
			'NIK' => position_text_align( $ey['karyawan_nik'] ,  'center'),  
			'Karyawan' => $ey['karyawan_nama'],   
			'total' =>  position_text_align( rp_format($ey['pay_komponen_nominal']), 'right'), 
		);
	}
	
	$datas = table_rows($row); 
	$paging = $kgPagerOBJ ->showPaging();
	
	$navigasi = array(
		'<input class="btn btn-primary" style="float:right;"  type="button" value="Excel" onclick="javascript:location.href=\'index.php?com='.$_GET['com'].'&task=excel_per_komponen&komponen_id='.$komponen_id.'&periode_id='.$periode_id.'\'"/>',
		'<input class="btn btn-primary" style="float:right;margin-right:5px"  type="button" value="Cetak" onclick="javascript:location.href=\'index.php?com='.$_GET['com'].'&task=cetak_masal&komponen_id='.$komponen_id.'&periode_id='.$periode_id.'\'"/>',
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
	$datas_detail = get_komponen_detail( $komponen_id , $periode_id );
	$unreg = my_get_data_by_id('unreg_periode' ,'unreg_periode_id', $datas_detail['unreg_periode_id']);
	$view = form_header( "form_pay_komponen_manual" , "form_pay_komponen_manual"  );
	$view  .= form_field_display(  '<br/> &nbsp; &nbsp; '.$datas_detail['pay_komponen_gaji_code'].'/'.$datas_detail['pay_komponen_gaji_name']   , "Komponen gaji"    ); 
	$view  .= form_field_display(  '<br/> &nbsp; &nbsp; '.  strtoupper($unreg['unreg_periode_name'] )   , "Nama penghasilan tidak tetap"    ); 
	$view  .= form_field_display(  '<br/> &nbsp; &nbsp; '. date('d-m-Y',strtotime($datas_detail['pay_periode_date'] ))  , "Periode pembayaran gaji"    ); 
	$view .= form_field_display(  '<br/> &nbsp; &nbsp; '. ( (int) $datas_detail['jumlah_karyawan'] == 0 ? 'Tidak ada ':$datas_detail['jumlah_karyawan'] .' orang')   , "Jumlah karyawan"    ); 
	$view .= form_field_display( '<br/> &nbsp; &nbsp; '.'Rp. '.rp_format($datas_detail['nominal_komponen']) , "Total nominal"    ); 
	$view  .= form_footer();
	return $view. $box.table_builder($headers , $datas ,  6 , false , $paging  ).'<br/>'; 

}

function get_komponen_detail( $komponen_id , $periode_id ){
	$query = "SELECT COUNT(karyawan_id) AS jumlah_karyawan ,
			pay_komponen_gaji_code , unreg_periode_id,
			pay_komponen_gaji_id ,
			pay_komponen_gaji_type , 
			pay_komponen_gaji_name , 
			pay_periode_date ,
			SUM(pay_komponen_nominal) AS nominal_komponen 
			FROM log_payroll_unreguler_komponen 
		WHERE unreg_periode_id = {$periode_id} 
		AND pay_komponen_gaji_id = {$komponen_id} ";
	$query 	.= " 
		GROUP BY pay_komponen_gaji_id 
		ORDER BY pay_komponen_gaji_id ASC";
	$result = my_query($query);
	if( my_num_rows($result) > 0 ){
		
		return my_fetch_array($result);
	}
	return false;
}


function excel_komponen_detail($periode_id , $komponen_id){
	$header= array(  
		'Kode' => array(  'style'=>'text-align:center;width:100px;' ), 
		'Nama Komponen' => array(  'style'=>'text-align:center;width:220px;' ), 
		'NIK' => array(  'style'=>'text-align:center;width:100px;' ), 
		'Nama karyawan' => array( 'style'=>'text-align:left;width:220px;' ),  
		'Nominal' => array(  'style'=>'text-align:center;width:180px;' ),   	
	);  
	$query 	= "SELECT  
			karyawan_nik ,
			karyawan_nama ,
			pay_komponen_gaji_code ,
			pay_komponen_gaji_type ,
			pay_komponen_gaji_name ,
			pay_komponen_nominal  
			FROM log_payroll_unreguler_komponen 
		WHERE unreg_periode_id = {$periode_id} 
			AND pay_komponen_gaji_id = {$komponen_id}	  
		ORDER BY pay_komponen_gaji_id ASC";
	$result = my_query($query);
	$row = array();
	while( $ey = my_fetch_array($result) ){
		$row[] = array( 
			'Kode' =>   $ey['pay_komponen_gaji_code'] ,   
			'Namakomponen' => $ey['pay_komponen_gaji_name'],  
			'nik' =>  ucfirst($ey['karyawan_nik']),   
			'nama karyawan' =>  ucfirst($ey['karyawan_nama']),   
			'total' =>   $ey['pay_komponen_nominal']      
		);
	}
	
	$datas = table_rows_excel($row); 
	return table_builder_excel($header , $datas , 6 ,false ); 
}

function cetak_per_komponen($komponen_id , $periode_id){
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
	$datas = get_komponen_detail( $komponen_id , $periode_id );
	$unreg = my_get_data_by_id('unreg_periode' ,'unreg_periode_id', $datas['unreg_periode_id']);
	$periode_label =strtoupper($unreg['unreg_periode_name'] ) ;
	
	$query 	= "SELECT  
			karyawan_nik ,
			karyawan_nama ,
			pay_komponen_gaji_code ,
			pay_komponen_gaji_type ,
			pay_komponen_gaji_name ,
			pay_komponen_nominal  
			FROM log_payroll_unreguler_komponen 
		WHERE unreg_periode_id = {$periode_id} 
			AND pay_komponen_gaji_id = {$komponen_id}	  
		ORDER BY pay_komponen_gaji_id ASC";
	$result = my_query($query);
	$i=$n =0;
	$page = 1;
	$content = '<body  onload = "javascript:breakeveryheader()">';
	while( $ey =my_fetch_array($result) ){
	$n++;
	
	if( ($i%15) == 0){
	
	$content .='<P><pre>
<h2>PT GARIS TEGAK INDONESIA
<small>KOMPONEN PENGHASILAN TIDAK TETAP '.$periode_label.'</small></h2> 
Komponen gaji      : '.$datas['pay_komponen_gaji_code'].' / '.$datas['pay_komponen_gaji_name'].' 
Karyawan penerima  : '.$datas['jumlah_karyawan'].' karyawan  
Total nominal      : '.rp_format($datas['nominal_komponen']).'


';  
$content .=load_by_page($i , $komponen_id , $periode_id , $i , 15);
$content .='
	
'.str_pad(' ',40).'Mengetahui
'.str_pad(' ',40).'Kepala bagian penggajian



'.str_pad(' ',45).'( Julfikar )

Page : '.$page.'	
</pre></P>';
$page++;
	}
	$i++;
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

function load_by_page($n , $komponen_id , $periode_id ,$offset , $limit){
	$query 	= "SELECT  
			karyawan_nik ,
			karyawan_nama ,
			pay_komponen_gaji_code ,
			pay_komponen_gaji_type ,
			pay_komponen_gaji_name ,
			pay_komponen_nominal  
			FROM log_payroll_unreguler_komponen 
		WHERE unreg_periode_id = {$periode_id} 
			AND pay_komponen_gaji_id = {$komponen_id}	  
		ORDER BY pay_komponen_gaji_id ASC LIMIT {$offset} , {$limit}";
	$result = my_query($query);
	$content = '';
 
	while( $ey =my_fetch_array($result) ){
		$n++;
		$content .= content_komponen($n, $ey['karyawan_nik'], $ey['karyawan_nama'],$ey['pay_komponen_nominal'] )."\n";
		if( ($n%5) == 0)$content .= "\n";
	}
	return $content;
}

function content_komponen($i , $nik, $nama_karyawan , $nominal ){
	return str_pad($i, 5  , " ",STR_PAD_LEFT).' '.
		str_pad($nik, 10 , " ").
		str_pad($nama_karyawan, 45 , ".") .
		str_pad( rp_format($nominal), 15 ,'.' , STR_PAD_LEFT ) ;
}