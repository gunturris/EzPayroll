<?php
$peserta_id = get_peserta_id_by_pemeriksaan_id($_GET['id']);
$ar = array(
	'edit_status'=>my_type_data_str('close')
);
my_update_record('mata','pemeriksaan_id',$_GET['id'] ,$ar);
$peserta = loaddata_peserta($peserta_id);
$date_cetak= date('d-m-Y');
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
<table style="width:90%;font-family:times">
<tr><td colspan="2" style="width:90%;font-size:18px">
<b><u>DIREKTORAT KESELAMATAN PENERBANGAN<br>
BALAI KESEHATAN PENERBANGAN</u></b>
</td>

</tr>
<tr>
<td style="width:60%;">
&nbsp;
</td>
<td style="width:30%;text-align:right;vertical-align:bottom;">
Jakarta, <?php echo $date_cetak; ?>
</td>
</tr> 
</table>
<table style="width:90%;font-family:times">
<tr>
<td style="width:45%;height:24px">
Pemeriksaan Mata :
</td>
<td style="width:45%;text-align:right;">
&nbsp;
</td>
</tr>

<tr>
<td style="width:45%;height:24px">
Nama : <?php echo $peserta['nama'];?>  
</td>
<td style="width:45%;text-align:right;height:24px">
<?php echo ucfirst($peserta['kelamin']);?> <?php echo getage($peserta['tanggal_lahir']);?> tahun<br>
</td>
</tr>
<tr>
<td style="width:45%;height:24px">  
Dari &nbsp; &nbsp;: <?php echo $peserta['maskapai_label'];?>
</td>
<td style="width:45%;text-align:right;height:24px">
<?php
$periksa = my_get_data_by_id( 'pemeriksaan' ,'pemeriksaan_id', $_GET['id']);
$code_periksa = date('Y',strtotime($periksa['datetime_added'])).sprintf("%05s", $_GET['id']) ;
?>	
No. file : <?php echo $code_periksa; ?>
</td>
</tr>
</table>  <br> 
 <?php
 $fields = my_get_data_by_id("mata" , 'pemeriksaan_id' ,$_GET['id']);

$column_c = array('align'=>'center' , 'value'=>'ANAMNESE');
$column_d = array('align'=>'center' , 'value'=>'HASIL'); 
echo table_top_two_column($column_a , $column_b ,   false);;  

	$form_field_od_ucva = "OD ". $fields['od_ucva'];  
	$form_field_os_ucva = "OS ".$fields['os_ucva'] ;  
	$form_field_ods_ucva = "ODS ". $fields['ods_ucva']  ;
	echo table_body_two_column_default($form_field_od_ucva." , &nbsp; " .$form_field_os_ucva." , &nbsp; " .$form_field_ods_ucva , "1. U C V A"   );
	  
	 
	$form_field_od_nva = "OD ". $fields['od_nva'] ;  
	$form_field_os_nva = "OS ".$fields['os_nva']  ;   
	$form_field_ods_nva = "ODS ".$fields['ods_nva'];
	echo table_body_two_column_default($form_field_od_nva." , &nbsp; " .$form_field_os_nva." , &nbsp; " .$form_field_ods_nva , "2. N V A"   );
	
	echo table_body_two_column_default(" &nbsp; "   , "3. Kacamata "  );
	
   
	$form_field_od_visus = "OD ".$fields['od_visus']  ;  
	  
	$form_field_os_visus = "OS ".$fields['os_visus']  ;  
	  
	$form_field_ods_visus = "ODS ". $fields['ods_visus']  ;
	echo table_body_two_column_default($form_field_od_visus." , &nbsp; " .$form_field_os_visus." , &nbsp; " .$form_field_ods_visus , "&nbsp; &nbsp; &nbsp; a. Visus"   );
	 
	  
	$form_field_od_kcnva = "OD ".$fields['od_kcnva']  ;  
	  
	$form_field_os_kcnva = "OS ".$fields['os_kcnva']  ;  
	  
	$form_field_ods_kcnva = "ODS ". $fields['ods_kcnva']  ;
	echo table_body_two_column_default($form_field_od_kcnva." , &nbsp; " .$form_field_os_kcnva." , &nbsp; " .$form_field_ods_kcnva , "&nbsp; &nbsp; &nbsp; b. N V A"   );
	 
	  
	$form_field_od_tk = "OD ". $fields['od_tk']  ;   
	$form_field_os_tk = "OS ".$fields['os_tk']  ; 
	$form_field_ods_tk = "ODS ".$fields['ods_tk']  ;
	echo table_body_two_column_default($form_field_od_tk." , &nbsp; " .$form_field_os_tk." , &nbsp; " .$form_field_ods_tk , "&nbsp; &nbsp; &nbsp; c. T K"   );
	
	  
	$form_field_od_contact_lens = "OD ". $fields['od_contact_lens']; 
	$form_field_os_contact_lens = "OS ". $fields['os_contact_lens']; 
	$form_field_ods_contact_lens = "ODS ".$fields['ods_contact_lens'];
	echo table_body_two_column_default($form_field_od_contact_lens." , &nbsp; " .$form_field_os_contact_lens." , &nbsp; " .$form_field_ods_contact_lens , "4. Contact Lens"   );
	  
	$form_field_persepsi_warna =  $fields['persepsi_warna'];
	echo table_body_two_column_default($form_field_persepsi_warna   , "5. Persepsi warna"   );
	  
	$form_field_anomaloscope = $fields['anomaloscope'];
//	echo table_body_two_column_default($form_field_anomaloscope   , "&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;  Anomaloscope"   );
  
	$form_field_od_tonometri = "OD ".$fields['od_tonometri'] ;  
	$form_field_os_tonometri = "OS ".$fields['os_tonometri'] ;  
	echo table_body_two_column_default($form_field_od_tonometri . " , &nbsp; ".$form_field_os_tonometri   , "6. Tonometri"   );
 
	$form_field_od_a_autorefraktion = "OD ". $fields['od_a_autorefraktion']  ."D";  
 
	  
	$form_field_c_a_autorefraktion = "C ". $fields['c_a_autorefraktion']  ."D";  
 
	  
	$form_field_x_a_autorefraktion = "x ". $fields['x_a_autorefraktion']  ."D";  
 
	echo table_body_two_column_default($form_field_od_a_autorefraktion . " , &nbsp; ".$form_field_c_a_autorefraktion  . " , &nbsp; ".$form_field_x_a_autorefraktion   , "7. Autorefreaktion"   );
	  
	$form_field_od_b_autorefraktion = "OD ".$fields['od_b_autorefraktion']."D";  
 
   
	$form_field_c_b_autorefraktion = "C ". $fields['c_b_autorefraktion'] ."D";  
 
	 
	$form_field_x_b_autorefraktion = "x ".  $fields['x_b_autorefraktion']  ."D";  
 
	echo table_body_two_column_default($form_field_od_b_autorefraktion . " , &nbsp; ".$form_field_c_b_autorefraktion  . " , &nbsp; ".$form_field_x_b_autorefraktion   , "&nbsp;"   );
  
	$form_field_divergency =   $fields['divergency']." D";  
	echo table_body_two_column_default($form_field_divergency   , "8. Divergency"   );
	echo table_body_two_column_default("&nbsp;"  , "9. Heterophoria"   );
 
	$form_field_esophoria = $fields['esophoria']  ." D";  
	echo table_body_two_column_default( $form_field_esophoria  , "&nbsp; &nbsp; &nbsp; a. Esophoria"   );
	
 
		  
	 
	$form_field_exophoria =   $fields['exophoria'] ." D";  
	echo table_body_two_column_default( $form_field_exophoria  , "&nbsp; &nbsp; &nbsp; b. Exophoria"   );
	
 
			 
	$form_field_hiperphoria =   $fields['hiperphoria']  ." D";  
	echo table_body_two_column_default( $form_field_hiperphoria  , "&nbsp; &nbsp; &nbsp; c. Hiperphoria"   );
	
 
	 
	$form_field_od_konvergency = "OD ".$fields['od_konvergency'] ." D";  
	 
	$form_field_os_konvergency = "OS ".$fields['os_konvergency'] ." D";  
	 		 
	$form_field_ods_konvergency = "ODS ". $fields['ods_konvergency'] ." cm";
	echo table_body_two_column($form_field_od_konvergency." , &nbsp; " .$form_field_os_konvergency." , &nbsp; " .$form_field_ods_konvergency , "10. Konvergency"   );
	
	 
	$form_field_panjang_kaki =  $fields['panjang_kaki']." cm";
	echo table_body_two_column_default($form_field_panjang_kaki   , "11. Panjang kaki"   );
	
	$form_field_hold_dortman =    $fields['hold_dortman']  ;
	echo table_body_two_column_default($form_field_hold_dortman   , "12. Hold dortman"   );
		
	$form_field_diplopia =  $fields['diplopia'];
	echo table_body_two_column_default($form_field_diplopia  , "13. Port Dot test / Diplopia"   );
	
	$form_field_lain =  $fields['hal_lain'];
	echo table_body_two_column_default($form_field_diplopia  , " Hal lain"   );
 
	echo table_end_two_column( );
 ?>
 <br><br><br> 
<table style="width:100%;">
<tr>
<td style="width:50%;vertical-align:top;"> 
 <div style="text-align:center;width:300px">
Dokter<br><br><br><br><br><br> 
( &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; )</div>


</td>
<td style="width:50%;vertical-align:top;">
 <div style="text-align:center;width:300px">
Pemeriksa<br><br><br><br><br><br> 
( &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; )</div>

</td>
</tr>
</table> 