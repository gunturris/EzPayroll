<?php


function paket_periksa_detail($pemeriksaan_id){
	$view = '<table id= "myTable" width="100%" style="" cellspacing="0" cellpadding="2">';
	$view .= '
	<tr>
		<td style="border-bottom:1px solid;border-top:2px solid;" width="5%" align="center"><b>&nbsp;</b></td>
		<td style="border-bottom:1px solid;border-top:2px solid;" width="95%" align="center"><b>Jenis Pemeriksaan</b></td>
		<!--td style="border-bottom:1px solid;border-top:2px solid;" width="30%" align="center"><b>Price</b></td -->
		 </tr>';
	
	$query = "
	SELECT * FROM paket_pemeriksaan a 
		INNER JOIN paket_pilihan b
		ON a.paket_pemeriksaan_id = b.paket_pemeriksaan_id
	WHERE b.pemeriksaan_id = {$pemeriksaan_id} ";
	$result = my_query($query);
	$total = array();
	$i=0;
	while($row = my_fetch_array($result) ){
	$i++;
	$total[] = $row['price'];
	$view .= '
	<tr>
		<td  align="center"> '.$i.'</td>
		<td>'.$row['nama_paket'].'</td>
		 
	</tr>';	
	}
	$terbilang = rupiah_terbilang(array_sum($total));
	 $view .='<tr> 
		<td style="border-bottom:2px solid; " align="right" colspan="4"> <br/>
		 </td> 
		 
	</tr>';	 
	$view .= '</table>'; 
	/*$view .= '<div style="width:100%;text-align:right">
	 
	<b>Petugas :</b> '.$login['nama'].' <br><span style="font-size:10px">(<i>'.$login['username'].'</i>)</span>
	</div>';*/
	return $view;
}


function detail_paket_pemeriksaan($pemeriksaan_id){
	$periksa = my_get_data_by_id( 'pemeriksaan' ,'pemeriksaan_id', $pemeriksaan_id);
 	$code_periksa = date('Y',strtotime($periksa['datetime_added'])).sprintf("%05s", $pemeriksaan_id) ;
	$view = '';//'<div style="width:100%;text-align:right"><img src="components/barcode/barcodegen/html/image.php?code=code11&o=1&dpi=72&t=30&r=1&rot=0&text='.  $code_periksa.'&f1=-1&f2=8&a1=&a2=&a3="" /></div>';
	$view .= form_header( "peserta" , "peserta"  );
	$peserta_id = get_peserta_id_by_pemeriksaan_id($pemeriksaan_id); 
	$fields = loaddata_peserta( $peserta_id); 
	$view .= form_field_display( get_nomor_urut_peserta($peserta_id)  , "No Urut | <i>Queue no</i>"   );
	  $view .= form_field_display(  $code_periksa   , "Kode rekam |<i> Record code</i>"   );
	$view .= form_field_display( $fields['pekerjaan_code'] . $fields['nomor_lisensi']    , "ID"   );
	$view .= form_field_display( $fields['nama']    , "Nama | <i>Name</i>"   );
	$view .= form_field_display( ucfirst( $fields['kelamin'] )   , "Kelamin | <i>Sex</i>"   );
	$view .= form_field_display( date("d-m-Y" , strtotime($periksa['datetime_added']))    , "Tanggal | <i>Date</i>"   );
	$view .= form_field_display( strtoupper($fields['maskapai_label'] )   , "Maskapai | <i>Airlines</i>"   );
	
	$view .= '<tr><td colspan="2"><br/><b>PAKET PEMERIKSAAN</b></td></tr>';
	$view .= '<tr><td colspan="2">'.paket_periksa_detail($peserta_id).'</td></tr>';
	$submit = array(
		'value' =>  ' Cetak '  ,
		'name' => 'simpan', 
		'type'=>'button','class'=>'main_button'
	);
	$form_submit= form_dynamic($submit); 
	
	$view .= form_field_display( $form_submit  , "&nbsp;" ,  "" );
	$view .= form_footer( );
	return $view;
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
echo detail_paket_pemeriksaan($_GET['pid']);
?>