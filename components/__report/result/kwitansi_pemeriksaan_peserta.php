<?php
function payment($pemeriksaan_id){

	$query = "SELECT * FROM pembayaran WHERE pemeriksaan_id = {$pemeriksaan_id}";
	$result = my_query($query);
	if(my_num_rows($result) > 0){
		return true;
	}
	
	$datas = array(
		'pemeriksaan_id'=>my_type_data_int($pemeriksaan_id),
		'petugas_user_id'=>my_type_data_int($_SESSION['user_id']),
		'datetime_added'=>my_type_data_function('NOW()'),
	);
	return my_insert_record( 'pembayaran' , $datas );
}

function dokter_pemeriksa($pemeriksaan_id , $paket_pemeriksa_id ){
	return '-';
}

function paket_periksa_detail_print($pemeriksaan_id){

	$header = array(
		' '=>array('style'=>'border-top:2px solid;border-bottom:2px solid;width:5%'),  
		'Jenis pemeriksaan'=>array('style'=>'border-top:2px solid;border-bottom:2px solid;width:55%'),   
		'Pemeriksa'=>array('style'=>'border-top:2px solid;border-bottom:2px solid;width:25%'),   
		'Biaya'=>array('style'=>'border-top:2px solid;border-bottom:2px solid;width:15%'),   
	); 
	 
	$query = "
	SELECT * FROM paket_pemeriksaan a 
		INNER JOIN paket_pilihan b
		ON a.paket_pemeriksaan_id = b.paket_pemeriksaan_id
	WHERE b.pemeriksaan_id = {$pemeriksaan_id} ";
	$result = my_query($query);
	$total = array();
	$i=0;
	while($rowx = my_fetch_array($result) ){
	$i++;
	$total[] = $rowx['price'];
	 
		 $row[] = array(
			'#'=> $i, 
			'paket'=> $rowx['nama_paket'],     
			'pemeriksa'=>  dokter_pemeriksa($pemeriksaan_id , $rowx['paket_pemeriksa_id'] ),     
			'biaya'=>  '<div style="width:100px;text-align:right">Rp. '.rupiah_format(  $rowx['price'] ).'</div>',     
		);
	
	}
	$login = my_get_data_by_id('user' , 'user_id' , $_SESSION['user_id'] );
	$tbiaya =array_sum($total);
	$terbilang = rupiah_terbilang($tbiaya);
	 $datas = table_cetak_rows($row);   
	$view = table_cetak_builder($header , $datas ,  2, false );
	$view .= '<div style="width:100%;text-align:right">
	<b>Total biaya :</b> Rp. '.rupiah_format($tbiaya).'<br>
	( <i>'.$terbilang.'</i> )<br><br>
	
	<b>Petugas :</b> '.$login['nama'].' <br><span style="font-size:10px">(<i>'.$login['username'].'</i>)</span>
	</div>';
	return $view;
}


function detail_paket_pemeriksaan_print($pemeriksaan_id){ 
	$periksa = my_get_data_by_id( 'pemeriksaan' ,'pemeriksaan_id', $pemeriksaan_id);
 	$code_periksa = date('Y',strtotime($periksa['datetime_added'])).sprintf("%05s", $pemeriksaan_id) ;
	$peserta_id = get_peserta_id_by_pemeriksaan_id($pemeriksaan_id); 
	$fields = loaddata_peserta( $peserta_id); 
 
	$page = detail_header_view("#000" , array('width'=>'100%' ));
	$page .= detail_rows_view('<img src="files/barcode/'.$code_periksa.'.jpeg" />' , '' ,true);
//	$page .= detail_rows_view("No Urut | <i>Queue no</i>" , get_nomor_urut_peserta($peserta_id));
	$page .= detail_rows_view("Kode rekam |<i> Record code</i>"  , $code_periksa);
	$page .= detail_rows_view("ID" , $fields['pekerjaan_code'] . $fields['nomor_lisensi']  );
	$page .= detail_rows_view("Nama | <i>Name</i>" , $fields['nama'] );
	$page .= detail_rows_view("Kelamin | <i>Sex</i>" , ucfirst( $fields['kelamin'] ));
	$page .= detail_rows_view("Tanggal | <i>Date</i>", date("d-m-Y" , strtotime($fields['tanggal_daftar'])) );
	$page .= detail_rows_view("<br><b>PAKET PEMERIKSAAN</b>" , '' ,true);
 $page .= detail_rows_view(   paket_periksa_detail_print($pemeriksaan_id) ,'',true  );
	
	$page .=  detail_footer_view();
	
	return $page;
}

?>

<style type="text/css">
<!--

	table.page_header {width: 100%; border: none; background-color: #CDCDCD; border-bottom: solid 1mm #000; padding: 2mm }
	table.page_footer {width: 100%; border: none;  border-top: solid 1mm #000; padding: 2mm}
div.zone
{
	border: solid 2mm #66AACC;
	border-radius: 3mm;
	padding: 1mm;
	background-color: #FFEEEE;
	color: #440000;
}
div.zone_over
{
	width: 30mm;
	height: 35mm;
	overflow: hidden;
}

-->
</style> 
<?php
echo detail_paket_pemeriksaan_print($_GET['pid']);
?>