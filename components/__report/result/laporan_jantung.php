<?php
$peserta_id = get_peserta_id_by_pemeriksaan_id($_GET['id']);
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
Pemeriksaan Jantung :
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
</table>  <br><br><br> 
 <?php 

$column_c = array('align'=>'center' , 'value'=>'ANAMNESE');
$column_d = array('align'=>'center' , 'value'=>'HASIL'); 
echo table_top_two_column($column_a , $column_b ,   false);;  

	$fields = my_get_data_by_id('jantung' , 'pemeriksaan_id' , $_GET['id']);
	 
	echo table_body_two_column_default( "&nbsp;" , "<b>Treadmill</b>"   ); 
	  
	$form_field_endpoint = $fields['endpoint']  .' % of target HR achived';
	echo table_body_two_column_default( $form_field_endpoint  , "Endpoint of test"   );
	 
	$form_field_durante =  $fields['durante']  ;
	echo table_body_two_column_default( $form_field_durante  , "Durante / post stress test"   );
	 
	$form_field_hemodynamic =  $fields['hemodynamic']  ;
	echo table_body_two_column_default( $form_field_hemodynamic  , "Hemodynamic response  " );
	  
	$form_field_electrocardio =  $fields['electrocardio']  ;
	echo table_body_two_column_default( $form_field_electrocardio  , "Electrocardiographic response  " );
	  
	$form_field_electrocardio = $fields['physical'];
	echo table_body_two_column_default( $form_field_electrocardio  , "Physical condition  " );
	   
	$form_field_functional =  $fields['functional'] ;
	echo table_body_two_column_default( $form_field_functional , "Functional classification  " );
	 
	$form_field_aerobic= $fields['aerobic']  .' Mets';
	echo table_body_two_column_default( $form_field_aerobic , "Aerobic capacity  " );
	 
	$form_field_conclusion =  $fields['conclusion']  ;
	echo table_body_two_column_default( $form_field_conclusion , "Conclusion " );
	  
	$form_field_advice = $fields['advice'] ;
	echo table_body_two_column_default( $form_field_advice  , "Advice" );
	echo table_body_two_column_default("&nbsp;" , "&nbsp;" );
	
	
	echo table_body_two_column_default( "&nbsp;" , "<b>E C G</b>"   ); 
	  
	$form_field_hasil =   ($fields['hasil'] == 'normal' ) ? "Normal" : "Abnormal, ".$fields['hasil_text'] ;
	echo table_body_two_column_default( $form_field_hasil  , "Hasil pemeriksaan"   );
 
	 
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