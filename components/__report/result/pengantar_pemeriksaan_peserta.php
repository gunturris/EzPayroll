<?php


function paket_periksa_detail_print($pemeriksaan_id){

	$header = array(
		' '=>array('style'=>'border-top:2px solid;border-bottom:2px solid;width:5%'),  
		'Jenis pemeriksaan'=>array('style'=>'border-top:2px solid;border-bottom:2px solid;width:80%'),   
		''=>array('style'=>'border-top:2px solid;border-bottom:2px solid;width:15%'),   
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
	$total[] = $row['price'];
	 
		 $row[] = array(
			'#'=> $i, 
			'paket'=> $rowx['nama_paket'],     
			' '=> ( ( ($i%2)== 0) ? ' &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; ': '' ) .$i ,     
		);
	
	}
	$terbilang = rupiah_terbilang(array_sum($total));
	 $datas = table_cetak_rows($row);   
	return  table_cetak_builder($header , $datas ,  2, false );
	 
}


function detail_paket_pemeriksaan_print($pemeriksaan_id){ 
	$periksa = my_get_data_by_id( 'pemeriksaan' ,'pemeriksaan_id', $pemeriksaan_id);
 	$code_periksa = date('Y',strtotime($periksa['datetime_added'])).sprintf("%05s", $pemeriksaan_id) ;
	$peserta_id = get_peserta_id_by_pemeriksaan_id($pemeriksaan_id); 
	$fields = loaddata_peserta( $peserta_id); 
 
	$page = detail_header_view("#000" , array('width'=>'100%' ));
	$page .= detail_rows_view('<img src="files/barcode/'.$code_periksa.'.jpeg" />' , '' ,true);
	$page .= detail_rows_view("No Urut | <i>Queue no</i>" , get_nomor_urut_peserta($peserta_id));
	$page .= detail_rows_view("Kode rekam |<i> Record code</i>"  , $code_periksa);
	$page .= detail_rows_view("ID" , $fields['pekerjaan_code'] . $fields['nomor_lisensi']  );
	$page .= detail_rows_view("Nama | <i>Name</i>" , $fields['nama'] );
	$page .= detail_rows_view("Kelamin | <i>Sex</i>" , ucfirst( $fields['kelamin'] ));
	$page .= detail_rows_view("Tanggal | <i>Date</i>", date("d-m-Y" , strtotime($periksa['datetime_added'])) );
	$page .= detail_rows_view("<br><b>PAKET PEMERIKSAAN</b>" , '' ,true);
 $page .= detail_rows_view(   paket_periksa_detail_print($pemeriksaan_id) ,'',true  );
	
	$page .=  detail_footer_view();
	$login = my_get_data_by_id('user' , 'user_id' , $_SESSION['user_id'] );
	 $view  = '<div style="width:100%;text-align:right">
	 
	<b>Petugas :</b> '.$login['nama'].' <br><span style="font-size:10px">(<i>'.$login['username'].'</i>)</span>
	</div>'; 
	return $page.$view;
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