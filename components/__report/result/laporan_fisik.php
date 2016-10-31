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
<page> 
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
Pemeriksaan Fisik :
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
</table>
 <br>
<table style="width:100%;">
<tr>
<td style="width:50%;vertical-align:top;"> 
<b>FISIK UMUM</b>
<?php
$column_a = array('align'=>'center' , 'value'=>'UMUM');
$column_b = array('align'=>'center' , 'value'=>'HASIL'); 
echo table_top_two_column($column_a , $column_b ,   false);
 
$pemeriksaan = my_get_data_by_id(	'pemeriksaan_fisik',	'pemeriksaan_id', $_GET['id'] );
$fields = my_get_data_by_id('fisik_umum' ,'umum_id', $pemeriksaan['umum_id'] );
 

$column_a1 = array('align'=>'left' , 'value'=>"Tinggi badan" );
$column_b1 = array('align'=>'left' , 'value'=> $fields['tinggi_badan'] ." cm"); 
echo table_body_two_column( $column_a1,$column_b1   );

$column_a2 = array('align'=>'left' , 'value'=>"Berat badan" );
$column_b2 = array('align'=>'left' , 'value'=>$fields['berat_badan'] ." kg"); 
echo table_body_two_column( $column_a2,$column_b2  );

$column_a3 = array('align'=>'left' , 'value'=>"Bentuk badan" );
$column_b3 = array('align'=>'left' , 'value'=>ucfirst($fields['bentuk_badan'])); 
echo table_body_two_column(   $column_a3 ,$column_b3   );

$column_a4 = array('align'=>'left' , 'value'=>"Sikap" );
$column_b4 = array('align'=>'left' , 'value'=>ucfirst($fields['sikap'])); 
echo table_body_two_column(  $column_a4 ,$column_b4   );

$column_a5 = array('align'=>'left' , 'value'=>"Otot-otot" );
$column_b5 = array('align'=>'left' , 'value'=>ucfirst($fields['otot'])); 
echo table_body_two_column(   $column_a5 ,$column_b5   );

$column_a6 = array('align'=>'left' , 'value'=>"Peniculus adiposus" );
$column_b6 = array('align'=>'left' , 'value'=>label_positif_negatif( $fields['peniculus']) ); 
echo table_body_two_column( $column_a6 ,$column_b6  );

$column_a7 = array('align'=>'left' , 'value'=>"Busung (Oedema)"  );
$column_b7 = array('align'=>'left' , 'value'=>label_positif_negatif( $fields['busung'] )  ); 
echo table_body_two_column( $column_a7 ,$column_b7  );


$column_a8 = array('align'=>'left' , 'value'=>"Kelainan - kelainan pada kulit"  );
$column_b8 = array('align'=>'left' , 'value'=>label_positif_negatif( $fields['kelainan_kulit'] )  ); 
echo table_body_two_column( $column_a8 ,$column_b8    );

$column_a9 = array('align'=>'left' , 'value'=>"Pembengkakan kelenjar-kelenjar "  );
$column_b9 = array('align'=>'left' , 'value'=>label_positif_negatif( $fields['pembengkakan_kelenjar' ] )  ); 
echo table_body_two_column( $column_a9 ,$column_b9   );

$column_a10 = array('align'=>'left' , 'value'=>"Tanda-tanda nyata untuk identifikasi"   );
$column_b10 = array('align'=>'left' , 'value'=>ucfirst( $fields['tanda_identifikasi'] )  ); 
echo table_body_two_column( $column_a10 ,$column_b10 );

$column_a11 = array('align'=>'left' , 'value'=>"Conjungtiva palpabera"   );
$column_b11 = array('align'=>'left' , 'value'=>ucfirst( $fields['conjungtiva_palpabera']  )  ); 
echo table_body_two_column( $column_a11 ,$column_b11 );

$column_a12 = array('align'=>'left' , 'value'=>"Thyroid"    );
$column_b12 = array('align'=>'left' , 'value'=> answer_with_text(  $fields['thyroid']  , $fields['thyroid_text'] )  ); 
echo table_body_two_column(  $column_a12 ,$column_b12  );
 
echo table_end_two_column( );
?>
<br>
<br>
<b>PERNAPASAN</b>
<?php
$column_a = array('align'=>'center' , 'value'=>'UMUM');
$column_b = array('align'=>'center' , 'value'=>'HASIL'); 
echo table_top_two_column($column_a , $column_b ,   false);
 
$pemeriksaan = my_get_data_by_id(	'pemeriksaan_fisik',	'pemeriksaan_id', $_GET['id'] );
$fields_pernapasan = my_get_data_by_id('fisik_pernapasan' ,'pernapasan_id', $pemeriksaan['pernapasan_id'] );
 

$column_e1 = array('align'=>'left' , 'value'=>"Inspeksi" );
$column_f1 = array('align'=>'left' , 'value'=> $fields_pernapasan['inspeksi']  ); 
echo table_body_two_column( $column_e1,$column_f1   );

$column_e2 = array('align'=>'left' , 'value'=>"Perkusi" );
$column_f2 = array('align'=>'left' , 'value'=>$fields_pernapasan['perkusi']  ); 
echo table_body_two_column( $column_e2 ,$column_f2  );

$column_e3 = array('align'=>'left' , 'value'=>"Palpasi" );
$column_f3 = array('align'=>'left' , 'value'=>ucfirst($fields_pernapasan['palpasi'])); 
echo table_body_two_column(   $column_e3 ,$column_f3   );

$column_e4 = array('align'=>'left' , 'value'=>"Aukultasi" );
$column_f4 = array('align'=>'left' , 'value'=>ucfirst($fields_pernapasan['aukultasi'])); 
echo table_body_two_column(  $column_e4 ,$column_f4   );
 $column_e5 = array('align'=>'left' , 'value'=>"Hal lain" );
$column_f5 = array('align'=>'left' , 'value'=>ucfirst($fields_pernapasan['lainnya'])); 
echo table_body_two_column(  $column_e5 ,$column_f5   );

echo table_end_two_column( );
?><br>
<br>
<b>PENCERNAAN</b>
<?php
$column_a = array('align'=>'center' , 'value'=>'UMUM');
$column_b = array('align'=>'center' , 'value'=>'HASIL'); 
echo table_top_two_column($column_a , $column_b ,   false);
 
$pemeriksaan = my_get_data_by_id(	'pemeriksaan_fisik',	'pemeriksaan_id', $_GET['id'] );
$fields_pencernaan = my_get_data_by_id('fisik_pencernaan' ,'pencernaan_id', $pemeriksaan['pencernaan_id'] );
 

$column_e1 = array('align'=>'left' , 'value'=>"Inspeksi" );
$column_f1 = array('align'=>'left' , 'value'=> $fields_pencernaan['inspeksi']  ); 
echo table_body_two_column( $column_e1,$column_f1   );

$column_e2 = array('align'=>'left' , 'value'=>"Perkusi" );
$column_f2 = array('align'=>'left' , 'value'=>$fields_pencernaan['perkusi']  ); 
echo table_body_two_column( $column_e2 ,$column_f2  );

$column_e3 = array('align'=>'left' , 'value'=>"Palpasi" );
$column_f3 = array('align'=>'left' , 'value'=>ucfirst($fields_pencernaan['palpasi'])); 
echo table_body_two_column(   $column_e3 ,$column_f3   );

$column_e4 = array('align'=>'left' , 'value'=>"Aukultasi" );
$column_f4 = array('align'=>'left' , 'value'=>ucfirst($fields_pencernaan['aukultasi'])); 
echo table_body_two_column(  $column_e4 ,$column_f4   );
$column_e5 = array('align'=>'left' , 'value'=>"Hal lain" );
$column_f5 = array('align'=>'left' , 'value'=>ucfirst($fields_pencernaan['lainnya'])); 
echo table_body_two_column(  $column_e5 ,$column_f5   );
 
echo table_end_two_column( );
?> 

</td>
<td style="width:50%;vertical-align:top;">
<b>ANAMNESE</b>

<?php

$column_c = array('align'=>'center' , 'value'=>'ANAMNESE');
$column_d = array('align'=>'center' , 'value'=>'HASIL'); 
echo table_top_two_column($column_a , $column_b ,   false);
 
$pemeriksaan = my_get_data_by_id(	'pemeriksaan_fisik',	'pemeriksaan_id', $_GET['id'] );
$fields = my_get_data_by_id('fisik_anamase' ,'anamase_id', $pemeriksaan['anamase_id'] );
 
$column_c1 = array('align'=>'left' , 'value'=>"Tinggi badan" );
$column_d1 = array('align'=>'left' , 'value'=> $fields['tinggi_badan'] ." cm"); 
echo table_body_two_column( $column_c1,$column_d1   );

$column_c2 = array('align'=>'left' , 'value'=>"Keluhan" );
$column_d2 = array('align'=>'left' , 'value'=> $fields['keluhan'] ); 
echo table_body_two_column(   $column_c2,$column_d2  );

$column_c3 = array('align'=>'left' , 'value'=>"" );
$column_d3 = array('align'=>'left' , 'value'=>  "&nbsp;");  
echo  table_body_span_column( "<i><br>Kelainan penyakit yang diderita</i>"    );
 
$column_c4 = array('align'=>'left' , 'value'=>"Asinha" );
$column_d4 = array('align'=>'left' , 'value'=> ucfirst($fields['asinha']) ); 
echo table_body_two_column(  $column_c4 , $column_d4  );

$column_c5 = array('align'=>'left' , 'value'=>"K. P" );
$column_d5 = array('align'=>'left' , 'value'=>ucfirst($fields['kp'] )); 
echo table_body_two_column( $column_c5 , $column_d5 );
 
$column_c6 = array('align'=>'left' , 'value'=>"Asma" );
$column_d6 = array('align'=>'left' , 'value'=>ucfirst($fields['asma']) ); 
echo table_body_two_column( $column_c6 , $column_d6   );

$column_c7 = array('align'=>'left' , 'value'=>"Hepatitis" );
$column_d7 = array('align'=>'left' , 'value'=>ucfirst($fields['kuning'])  ); 
echo table_body_two_column(  $column_c7 , $column_d7   );

$column_c8 = array('align'=>'left' , 'value'=>"I S K" );
$column_d8 = array('align'=>'left' , 'value'=>ucfirst($fields['isk']) ); 
echo table_body_two_column(  $column_c8 , $column_d8   );
/*
$column_c9 = array('align'=>'left' , 'value'=>"Penyakit jantung" );
$column_d9 = array('align'=>'left' , 'value'=>$fields['keluhan']  ); 
echo table_body_two_column( $column_c9 , $column_d9  );
*/
$column_c10 = array('align'=>'left' , 'value'=>"Kencing manis" );
$column_d10 = array('align'=>'left' , 'value'=>ucfirst($fields['kencing_manis']) ); 
echo table_body_two_column(  $column_c10 , $column_d10  );

$column_c11 = array('align'=>'left' , 'value'=>"C O R" );
$column_d11 = array('align'=>'left' , 'value'=>ucfirst($fields['cor']) ); 
echo table_body_two_column(  $column_c11 , $column_d11  );

$column_c12 = array('align'=>'left' , 'value'=>"Obat obatan" );
$column_d12 = array('align'=>'left' , 'value'=>ucfirst($fields['obat-obatan'] ) ); 
echo table_body_two_column( $column_c12 , $column_d12 );

$column_c13 = array('align'=>'left' , 'value'=>"Merokok" );
$column_d13 = array('align'=>'left' , 'value'=> ucfirst($fields['merokok']) ); 
echo table_body_two_column(  $column_c13 , $column_d13   );

$column_c14 = array('align'=>'left' , 'value'=>"Alkohol" );
$column_d14 = array('align'=>'left' , 'value'=>ucfirst($fields['alkohol'] ) ); 
echo table_body_two_column(  $column_c14 , $column_d14  );

$column_c15 = array('align'=>'left' , 'value'=>"Pingsan" );
$column_d15 = array('align'=>'left' , 'value'=>ucfirst($fields['pingsan'])  ); 
echo table_body_two_column( $column_c15 , $column_d15  );

$column_c16 = array('align'=>'left' , 'value'=>"K.P.T" );
$column_d16 = array('align'=>'left' , 'value'=>ucfirst($fields['kpr'] ) ); 
echo table_body_two_column( $column_c16 , $column_d16  );

$column_c17 = array('align'=>'left' , 'value'=>"Kejang-kejang" );
$column_d17 = array('align'=>'left' , 'value'=>ucfirst($fields['kejang_kejang'] ) ); 
echo table_body_two_column( $column_c17 , $column_d17   );

$column_c18 = array('align'=>'left' , 'value'=>"Operasi" );
$column_d18 = array('align'=>'left' , 'value'=>ucfirst($fields['operasi'])  ); 
echo table_body_two_column($column_c18 , $column_d18   );

$column_c19 = array('align'=>'left' , 'value'=>"Opname" );
$column_d19 = array('align'=>'left' , 'value'=>ucfirst($fields['opname'] ) ); 
echo table_body_two_column($column_c19 , $column_d19);

$column_c20 = array('align'=>'left' , 'value'=>"O T M" );
$column_d20 = array('align'=>'left' , 'value'=>ucfirst($fields['otm'] ) ); 
echo table_body_two_column($column_c20 , $column_d20);

$column_c21 = array('align'=>'left' , 'value'=>"Hemorrhoid" );
$column_d21 = array('align'=>'left' , 'value'=>ucfirst($fields['hemorrhoid'])  ); 
echo table_body_two_column( $column_c21 , $column_d21 );

$column_c22 = array('align'=>'left' , 'value'=>"Kecelakaan" );
$column_d22 = array('align'=>'left' , 'value'=>ucfirst($fields['kecelakaan'])  ); 
echo table_body_two_column( $column_c22 , $column_d22 );

$column_c23 = array('align'=>'left' , 'value'=>"<i><br>Menstruasi</i>" );
$column_d23 = array('align'=>'left' , 'value'=>"&nbsp;"  ); 
echo table_body_span_column( "<i><br>Menstruasi</i>" );

$column_c24 = array('align'=>'left' , 'value'=>"Teratur" );
$column_d24 = array('align'=>'left' , 'value'=>ucfirst($fields['menstruasi_teratur'])  ); 
echo table_body_two_column( $column_c24 , $column_d24 );

$column_c25 = array('align'=>'left' , 'value'=>"Dysmenoroherea" );
$column_d25 = array('align'=>'left' , 'value'=>ucfirst($fields['menstruasi_dysmenoroherea']) ); 
echo table_body_two_column( $column_c25 , $column_d25 );

$column_c26 = array('align'=>'left' , 'value'=>"Tremor" );
$column_d26 = array('align'=>'left' , 'value'=>ucfirst($fields['tremor'])  ); 
echo table_body_two_column( $column_c26 , $column_d26   );

$column_c27 = array('align'=>'left' , 'value'=>"Air sickness" );
$column_d27 = array('align'=>'left' , 'value'=>ucfirst($fields['air_sickness']) ); 
echo table_body_two_column( $column_c27 , $column_d27  );

echo table_end_two_column( );

?> 
</td>
</tr>
</table> 
<table style="width:100%;">
<tr>
<td style="width:50%;vertical-align:top;"> 

<b>MATA</b>
<?php
$column_a = array('align'=>'center' , 'value'=>'UMUM');
$column_b = array('align'=>'center' , 'value'=>'HASIL'); 
echo table_top_two_column($column_a , $column_b ,   false);
 
$pemeriksaan = my_get_data_by_id(	'pemeriksaan_fisik',	'pemeriksaan_id', $_GET['id'] );
$fields_mata = my_get_data_by_id('fisik_mata' ,'mata_id', $pemeriksaan['mata_id'] );
 

$column_e1 = array('align'=>'left' , 'value'=>"Selaput dan kelopak mata" );
$column_f1 = array('align'=>'left' , 'value'=> $fields_pencernaan['inspeksi']  ); 
echo table_body_two_column( $column_e1,$column_f1   );

$column_e2 = array('align'=>'left' , 'value'=>"Exophthalmus Strabismus, dll" );
$column_f2 = array('align'=>'left' , 'value'=>$fields_pencernaan['perkusi']  ); 
echo table_body_two_column( $column_e2 ,$column_f2  );

$column_e3 = array('align'=>'left' , 'value'=>"a. Bentuk" );
$column_f3 = array('align'=>'left' , 'value'=>ucfirst($fields_pencernaan['palpasi'])); 
echo table_body_two_column(   $column_e3 ,$column_f3   );

$column_e4 = array('align'=>'left' , 'value'=>"b. Simetri" );
$column_f4 = array('align'=>'left' , 'value'=>ucfirst($fields_pencernaan['aukultasi'])); 
echo table_body_two_column(  $column_e4 ,$column_f4   );
 
$column_e5 = array('align'=>'left' , 'value'=>"b. Simetri" );
$column_f5 = array('align'=>'left' , 'value'=>ucfirst($fields_pencernaan['aukultasi'])); 
echo table_body_two_column(  $column_e5 ,$column_f5   );
 
$column_e6 = array('align'=>'left' , 'value'=>"c. Reaksi" );
$column_f6 = array('align'=>'left' , 'value'=>ucfirst($fields_pencernaan['aukultasi'])); 
echo table_body_two_column(  $column_e6 ,$column_f6   );
 
echo table_end_two_column( );
?>
<br><b>Alat saluran air kemih</b>
<?php
$column_a = array('align'=>'center' , 'value'=>'UMUM');
$column_b = array('align'=>'center' , 'value'=>'HASIL');  
echo table_top_two_column($column_a , $column_b ,   false);
 
$pemeriksaan = my_get_data_by_id(	'pemeriksaan_fisik',	'pemeriksaan_id', $_GET['id'] ); 
 $fields_kemih = my_get_data_by_id('fisik_kemih' ,'kemih_id', $pemeriksaan['kemih_id'] );
	

$column_ea1 = array('align'=>'left' , 'value'=>"Hernia" );
$column_fa1 = array('align'=>'left' , 'value'=> $fields_kemih['hernia']  ); 
echo table_body_two_column( $column_ea1,$column_fa1   );

$column_ea2 = array('align'=>'left' , 'value'=>"Hudrocele/ Varicolel" );
$column_fa2 = array('align'=>'left' , 'value'=>$fields_kemih['varicolel']  ); 
echo table_body_two_column( $column_ea2 ,$column_fa2  );

$column_ea3 = array('align'=>'left' , 'value'=>"Hal-hal lain" );
$column_fa3 = array('align'=>'left' , 'value'=>ucfirst($fields_kemih['hal_lain'])); 
echo table_body_two_column(   $column_ea3 ,$column_fa3   );
 
echo table_end_two_column( );
?>

<br><b>Pemeriksaaan Neurologis</b>
<?php
$column_a = array('align'=>'center' , 'value'=>'UMUM');
$column_b = array('align'=>'center' , 'value'=>'HASIL');  
echo table_top_two_column($column_a , $column_b ,   false);
 
$pemeriksaan = my_get_data_by_id(	'pemeriksaan_fisik',	'pemeriksaan_id', $_GET['id'] ); 
$fields_neurologis= my_get_data_by_id('fisik_neurologis' ,'neurologis_id', $pemeriksaan['neurologis_id'] );
$form_field_sensibilitas =answer_with_text($fields_neurologis['sensibilitas'] , $fields_neurologis['sensibilitas_text']);
$form_field_koordinasi = answer_with_text($fields_neurologis['koordinasi'] , $fields_neurologis['koordinasi_text']);
$form_field_tremor = answer_with_text($fields_neurologis['tremor'] , $fields_neurologis['tremor_text']);
$form_field_ataxia = answer_with_text($fields_neurologis['ataxia'] , $fields_neurologis['ataxia_text']);
$form_field_romberg = answer_with_text($fields_neurologis['romberg'] , $fields_neurologis['romberg_text']);
$form_field_demografi = answer_with_text($fields_neurologis['demografi'] , $fields_neurologis['reflex_kulit_text']);
$form_field_reflex_kulit =answer_with_text($fields_neurologis['reflex_kulit'] , $fields_neurologis['kpr_text']);
$form_field_kpr = label_positif_negatif( $fields_neurologis['kpr']);
$form_field_apr =answer_with_text($fields_neurologis['apr']	,$fields_neurologis['apr_text']);
$form_field_reflex_cremaster = answer_with_text($fields_neurologis['reflex_cremaster']	,$fields_neurologis['reflex_cremaster_text']);
$form_field_reflex_perut =answer_with_text($fields_neurologis['reflex_perut']	, $fields_neurologis['reflex_perut_text']);
$form_field_reflex_pathologi = answer_with_text($fields_neurologis['reflex_pathologis'],	$fields_neurologis['reflex_pathologis_text']);
$form_field_jalan_tutup_mata  =answer_with_text($fields_neurologis['jalan_tutup_mata'] ,	$fields_neurologis['jalan_tutup_mata_text']);
   
  
$column_ea1 = array('align'=>'left' , 'value'=>"Syaraf otak" );
$column_fa1 = array('align'=>'left' , 'value'=> answer_with_text($fields_neurologis['syaraf_otak'],$fields_neurologis['syaraf_otak_text'])  ); 
echo table_body_two_column( $column_ea1,$column_fa1   );

$column_ea2 = array('align'=>'left' , 'value'=>"Motorik" );
$column_fa2 = array('align'=>'left' , 'value'=>answer_with_text($fields_neurologis['motorik']  , $fields_neurologis['motorik_text'])   ); 
echo table_body_two_column( $column_ea2 ,$column_fa2  );

$column_ea3 = array('align'=>'left' , 'value'=>"Sensibilitas (raba,nyeri,panas)" );
$column_fa3 = array('align'=>'left' , 'value'=>$form_field_sensibilitas ); 
echo table_body_two_column(   $column_ea3 ,$column_fa3   );

$column_eb1 = array('align'=>'left' , 'value'=>"Koordinasi" );
$column_fb1 = array('align'=>'left' , 'value'=>  $form_field_koordinasi  ); 
echo table_body_two_column( $column_eb1,$column_fb1   );

$column_eb2 = array('align'=>'left' , 'value'=>"Tremor" );
$column_fb2 = array('align'=>'left' , 'value'=> $form_field_tremor   ); 
echo table_body_two_column( $column_eb2 ,$column_fb2  );

$column_eb3 = array('align'=>'left' , 'value'=>"Ataxia (tumit,lutut,jari,hidung)" );
$column_fb3 = array('align'=>'left' , 'value'=>$form_field_ataxia ); 
echo table_body_two_column(   $column_eb3 ,$column_fb3   );

$column_ec1 = array('align'=>'left' , 'value'=>"Reaksi romberg" );
$column_fc1 = array('align'=>'left' , 'value'=>$form_field_romberg   ); 
echo table_body_two_column( $column_ec1,$column_fc1   );

$column_ec2 = array('align'=>'left' , 'value'=>"Demografi" );
$column_fc2 = array('align'=>'left' , 'value'=> $form_field_demografi   ); 
echo table_body_two_column( $column_ec2 ,$column_fc2  );

$column_ec3 = array('align'=>'left' , 'value'=>"Reflex kulit" );
$column_fc3 = array('align'=>'left' , 'value'=>$form_field_reflex_kulit ); 
echo table_body_two_column(   $column_ec3 ,$column_fc3   );
 
$column_ed1 = array('align'=>'left' , 'value'=>"K. P. R." );
$column_fd1 = array('align'=>'left' , 'value'=>$form_field_kpr   ); 
echo table_body_two_column( $column_ed1,$column_fd1   );

$column_ed2 = array('align'=>'left' , 'value'=>"A. P. R." );
$column_fd2 = array('align'=>'left' , 'value'=> $form_field_apr   ); 
echo table_body_two_column( $column_ed2 ,$column_fd2  );

$column_ed3 = array('align'=>'left' , 'value'=>"Reflex cremaster" );
$column_fd3 = array('align'=>'left' , 'value'=>$form_field_reflex_cremaster ); 
echo table_body_two_column(   $column_ed3 ,$column_fd3   );
 
$column_ee1 = array('align'=>'left' , 'value'=>"Reflex perut" );
$column_fe1 = array('align'=>'left' , 'value'=>$form_field_reflex_perut   ); 
echo table_body_two_column( $column_ee1,$column_fe1   );

$column_ee2 = array('align'=>'left' , 'value'=>"Reflex  pathologis" );
$column_fe2 = array('align'=>'left' , 'value'=> $form_field_reflex_pathologi   ); 
echo table_body_two_column( $column_ee2 ,$column_fe2  );

$column_ee3 = array('align'=>'left' , 'value'=>"Jalan lurus kedepan <br> &nbsp; &nbsp; &nbsp; dengan tutup mata" );
$column_fe3 = array('align'=>'left' , 'value'=>$form_field_jalan_tutup_mata ); 
echo table_body_two_column(   $column_ee3 ,$column_fe3   );

$column_eew1 = array('align'=>'left' , 'value'=>"Berdiri satu kaki" );
$column_few1 = array('align'=>'left' , 'value'=>answer_with_text($fields_neurologis['satu_kaki'] , $fields_neurologis['satu_kaki_text'])  ); 
echo table_body_two_column( $column_ewe1,$column_few1   );

$column_eew2 = array('align'=>'left' , 'value'=>"Hal-hal lain" );
$column_few2 = array('align'=>'left' , 'value'=>$fields_neurologis['hal_lains_neurologis']   ); 
echo table_body_two_column( $column_eew2 ,$column_few2  );
 
echo table_end_two_column( );
?>

</td>
<td style="width:50%;vertical-align:top;">

<b>JANTUNG DAN PEMBULUH DARAH</b>
<?php
$column_a = array('align'=>'center' , 'value'=>'UMUM');
$column_b = array('align'=>'center' , 'value'=>'HASIL'); 
echo table_top_two_column($column_a , $column_b ,   false);
 
$pemeriksaan = my_get_data_by_id(	'pemeriksaan_fisik',	'pemeriksaan_id', $_GET['id'] );
$fields_jantung = my_get_data_by_id('fisik_jantung' ,'jantung_id', $pemeriksaan['jantung_id'] );
 
$column_g2 = array('align'=>'left' , 'value'=>"1. C O R" );
$column_h2 = array('align'=>'left' , 'value'=>"&nbsp;" ); 
echo table_body_two_column(  $column_g2 ,  $column_h2  );

$column_g3 = array('align'=>'left' , 'value'=>"&nbsp; &nbsp; &nbsp; &nbsp; a. Inspeksi" );
$column_h3 = array('align'=>'left' , 'value'=>$fields_jantung['cor_inspeksi']   ); 
echo table_body_two_column(   $column_g3 ,  $column_h3  );

$column_g4 = array('align'=>'left' , 'value'=>"&nbsp; &nbsp; &nbsp; &nbsp; b. Perkusi" );
$column_h4 = array('align'=>'left' , 'value'=> $fields_jantung['cor_perkusi']  ); 
echo table_body_two_column(  $column_g4 ,  $column_h4  );

$column_g4a = array('align'=>'left' , 'value'=>"&nbsp; &nbsp; &nbsp; &nbsp; c. Palpasi" );
$column_h4a = array('align'=>'left' , 'value'=> $fields_jantung['cor_palpasi']  ); 
echo table_body_two_column(  $column_g4a ,  $column_h4a );

$column_g5 = array('align'=>'left' , 'value'=>"&nbsp; &nbsp; &nbsp; &nbsp; d. Aukultasi" );
$column_h5 = array('align'=>'left' , 'value'=> $fields_jantung['cor_aukultasi']  ); 
echo table_body_two_column( $column_g5 ,  $column_h5  );

$column_g6 = array('align'=>'left' , 'value'=>"2. Pembuluh darah tepi" );
$column_h6 = array('align'=>'left' , 'value'=> "&nbsp;"   ); 
echo table_body_two_column( $column_g6 ,  $column_h6 );

$column_g7 = array('align'=>'left' , 'value'=>"&nbsp; &nbsp; &nbsp; &nbsp; a. Varises" );
$column_h7 = array('align'=>'left' , 'value'=> $fields_jantung['pembuluh_varises']   ); 
echo table_body_two_column(   $column_g7 ,  $column_h7  );

$column_g8 = array('align'=>'left' , 'value'=>"&nbsp; &nbsp; &nbsp; &nbsp; b. Hemorrhoid" );
$column_h8 = array('align'=>'left' , 'value'=> $fields_jantung['pembuluh_hemorhoid']   ); 
echo table_body_two_column(    $column_g8 ,  $column_h8   );

$column_g9 = array('align'=>'left' , 'value'=>"&nbsp; &nbsp; &nbsp; &nbsp; c. Venectasis" );
$column_h9 = array('align'=>'left' , 'value'=> $fields_jantung['pembuluh_venectasis']   ); 
echo table_body_two_column(   $column_g9 ,  $column_h9   );

$column_g10 = array('align'=>'left' , 'value'=>"3. Pulpasi" );
$column_h10 = array('align'=>'left' , 'value'=>"&nbsp;" ); 
echo table_body_two_column( $column_g10 ,  $column_h10  );

$column_g11 = array('align'=>'left' , 'value'=>"&nbsp; &nbsp; &nbsp; &nbsp; a. Arteri radialis" );
$column_h11 = array('align'=>'left' , 'value'=>$fields_jantung['pulpasi_radialis']  ); 
echo table_body_two_column( $column_g11 ,  $column_h11   );

$column_g12 = array('align'=>'left' , 'value'=>"&nbsp; &nbsp; &nbsp; &nbsp; c. Dursalis pedis");
$column_h12 = array('align'=>'left' , 'value'=>$fields_jantung['pulpasi_dursalis'] ); 
echo table_body_two_column( $column_g12 ,  $column_h12  );

$column_g13 = array('align'=>'left' , 'value'=>"&nbsp; &nbsp; &nbsp; &nbsp; d. Kecepatan nadi duduk" );
$column_h13 = array('align'=>'left' , 'value'=>$fields_jantung['kecepatan_nadi_duduk']  .' x/ menit'  ); 
echo table_body_two_column( $column_g13 ,  $column_h13 );

$column_g14 = array('align'=>'left' , 'value'=>"&nbsp; &nbsp; &nbsp; &nbsp; e. Kecepatan   berdiri" );
$column_h14 = array('align'=>'left' , 'value'=>$fields_jantung['kecepatan_berdiri'] .' x/ menit'); 
echo table_body_two_column($column_g14 ,  $column_h14 );

$column_g15 = array('align'=>'left' , 'value'=>"&nbsp; &nbsp; &nbsp; &nbsp; f. Kecepatan sesudah latihan" );
$column_h15 = array('align'=>'left' , 'value'=>$fields_jantung['kecepatan_sesudah_latihan'] .' x/ menit'  ); 
echo table_body_two_column( $column_g15 ,  $column_h15 );
	
$column_g16 = array('align'=>'left' , 'value'=>"&nbsp; &nbsp; &nbsp; &nbsp; g. Kecepatan standar setelah" );
$column_h16 = array('align'=>'left' , 'value'=>$fields_jantung['menjadi_biasa_setelah'] .' detik'  ); 
echo table_body_two_column( $column_g16 ,  $column_h16  );

$column_g17 = array('align'=>'left' , 'value'=>"4. Tek. darah duduk");
$column_h17 = array('align'=>'left' , 'value'=>$fields_jantung['tekanan_darah_duduk_a'] ." / ". $fields_jantung['tekanan_darah_duduk_b'] ." mmHg"  ); 
echo table_body_two_column( $column_g17 ,  $column_h17  );	

$column_g18 = array('align'=>'left' , 'value'=>"5. Tek. darah baring");
$column_h18 = array('align'=>'left' , 'value'=>$fields_jantung['tekanan_darah_berbaring_a'] ." / ". $fields_jantung['tekanan_darah_berbaring_a'] ." mmHg"  ); 
echo table_body_two_column(  $column_g18 ,  $column_h18   );	

$column_g19 = array('align'=>'left' , 'value'=>"6. Lain - lain");
$column_h19 = array('align'=>'left' , 'value'=>$fields_jantung['lain_lain_a'] ." / ". $fields_jantung['lain_lain_b'] ." mmHg" ); 
echo table_body_two_column( $column_g19 ,  $column_h19  );	

/*	*/ 
echo table_end_two_column( );
?><br>
<b>Pemeriksaaan T. H. T. dan Mulut</b>
<?php
$column_a = array('align'=>'center' , 'value'=>'UMUM');
$column_b = array('align'=>'center' , 'value'=>'HASIL'); 
echo table_top_two_column($column_a , $column_b ,   false);
 
$pemeriksaan = my_get_data_by_id(	'pemeriksaan_fisik',	'pemeriksaan_id', $_GET['id'] ); 
$fields_mulut = my_get_data_by_id('fisik_mulut' ,'mulut_id', $pemeriksaan['mulut_id'] );
	
$form_field_ozaena=answer_with_text( $fields_mulut['ozaena'] ,	$fields_mulut['ozaena_text']);	 
	
$column_g2 = array('align'=>'left' , 'value'=>"Susunan gigi" );
$column_h2 = array('align'=>'left' , 'value'=>$fields_mulut['susunan_gigi']  ); 
echo table_body_two_column(  $column_g2 ,  $column_h2  ); 

$column_g3 = array('align'=>'left' , 'value'=>"Kelainan pada lidah" );
$column_h3 = array('align'=>'left' , 'value'=>$fields_mulut['kelainan_lidah']   ); 
echo table_body_two_column(   $column_g3 ,  $column_h3  );

$column_g4 = array('align'=>'left' , 'value'=>"Selaput lender mulut giginval dan palatum" );
$column_h4 = array('align'=>'left' , 'value'=>  $fields_mulut['selaput_lender']  ); 
echo table_body_two_column(  $column_g4 ,  $column_h4  );

$column_g4a = array('align'=>'left' , 'value'=>"Lubang hidung terbuka" );
$column_h4a = array('align'=>'left' , 'value'=> "Kanan: ".$fields_mulut['lubang_hidung_kanan']  ." &nbsp; Kiri: ".$fields_mulut['lubang_hidung_kiri'] ); 
echo table_body_two_column(  $column_g4a ,  $column_h4a );

$column_g5 = array('align'=>'left' , 'value'=>"Concha" );
$column_h5 = array('align'=>'left' , 'value'=> $fields_mulut['concha']); 
echo table_body_two_column( $column_g5 ,  $column_h5  );

$column_g6 = array('align'=>'left' , 'value'=>"Septum" );
$column_h6 = array('align'=>'left' , 'value'=> $fields_mulut['septum']  ); 
echo table_body_two_column( $column_g6 ,  $column_h6 );

$column_g7 = array('align'=>'left' , 'value'=>"Ozaena" );
$column_h7 = array('align'=>'left' , 'value'=> $form_field_ozaena  ); 
echo table_body_two_column(   $column_g7 ,  $column_h7  );
/*
$column_g8 = array('align'=>'left' , 'value'=>"Tonsil (pembesaran akibat operasi)" );
$column_h8 = array('align'=>'left' , 'value'=> $fields_mulut['tonsil']   ); 
echo table_body_two_column(    $column_g8 ,  $column_h8   );

$column_g9 = array('align'=>'left' , 'value'=>"Adenoid" );
$column_h9 = array('align'=>'left' , 'value'=> $fields_mulut['adenoid'] ); 
echo table_body_two_column(   $column_g9 ,  $column_h9   );
*/
$column_g10 = array('align'=>'left' , 'value'=>"Polyp" );
$column_h10 = array('align'=>'left' , 'value'=>$fields_mulut['polyp']  ); 
echo table_body_two_column( $column_g10 ,  $column_h10  );

$column_g11 = array('align'=>'left' , 'value'=>"Kelainan pada larynk" );
$column_h11 = array('align'=>'left' , 'value'=> $fields_mulut['kelainan_larynk'] ); 
echo table_body_two_column( $column_g11 ,  $column_h11   );

$column_g12 = array('align'=>'left' , 'value'=>"Liang telinga");
$column_h12 = array('align'=>'left' , 'value'=>"Kanan: ".$fields_mulut['telinga_kanan']  ." &nbsp; <br>Kiri: ".$fields_mulut['telinga_kiri']); 
echo table_body_two_column( $column_g12 ,  $column_h12  );

$column_g13 = array('align'=>'left' , 'value'=>" Membrana tympani" );
$column_h13 = array('align'=>'left' , 'value'=>'&nbsp;'  ); 
echo table_body_two_column( $column_g13 ,  $column_h13 );

$column_g14 = array('align'=>'left' , 'value'=>"&nbsp; &nbsp; &nbsp; &nbsp; a. Warna" );
$column_h14 = array('align'=>'left' , 'value'=>"Kanan: ".$fields_mulut['tympani_warna_kanan']." &nbsp; <br>Kiri: ".$fields_mulut['tympani_warna_kiri'] ); 
echo table_body_two_column($column_g14 ,  $column_h14 );

$column_g15 = array('align'=>'left' , 'value'=>"&nbsp; &nbsp; &nbsp; &nbsp; b. Reflex cahaya" );
$column_h15 = array('align'=>'left' , 'value'=>"Kanan: ".$fields_mulut['tympani_reflex_kanan']." &nbsp; <br>Kiri: ".$fields_mulut['tympani_reflex_kiri']  ); 
echo table_body_two_column( $column_g15 ,  $column_h15 );
	
$column_g16 = array('align'=>'left' , 'value'=>"&nbsp; &nbsp; &nbsp; &nbsp; c. Perforatie (besar &amp; lokasi)" );
$column_h16 = array('align'=>'left' , 'value'=>"Kanan: ".$fields_mulut['tympani_perforatie_kanan']." &nbsp; <br>Kiri: ".$fields_mulut['tympani_perforatie_kiri']   ); 
echo table_body_two_column( $column_g16 ,  $column_h16  );

$column_g17 = array('align'=>'left' , 'value'=>"&nbsp; &nbsp; &nbsp; &nbsp; d. Cicatrix");
$column_h17 = array('align'=>'left' , 'value'=> "Kanan: ".$fields_mulut['tympani_cicatrix_kanan']." &nbsp; <br>Kiri: ".$fields_mulut['tympani_cicatrix_kiri']   ); 
echo table_body_two_column( $column_g17 ,  $column_h17  );	

$column_g18 = array('align'=>'left' , 'value'=>"&nbsp; &nbsp; &nbsp; &nbsp; e. Calcifasi");
$column_h18 = array('align'=>'left' , 'value'=> "Kanan: ".$fields_mulut['tympani_calcifasi_kanan']." &nbsp; <br>Kiri: ".$fields_mulut['tympani_calcifasi_kiri']  ); 
echo table_body_two_column(  $column_g18 ,  $column_h18   );	

$column_g19 = array('align'=>'left' , 'value'=>"&nbsp; &nbsp; &nbsp; &nbsp; f. Refraksi ");
$column_h19 = array('align'=>'left' , 'value'=> "Kanan: ".$fields_mulut['tympani_refraksi_kanan']." &nbsp; <br>Kiri: ".$fields_mulut['tympani_refraksi_kiri'] ); 
echo table_body_two_column( $column_g19 ,  $column_h19  );	

$column_g129 = array('align'=>'left' , 'value'=>"&nbsp; &nbsp; &nbsp; &nbsp; g. Bombans ");
$column_h129 = array('align'=>'left' , 'value'=>"Kanan: ".$fields_mulut['tympani_bombans_kanan']." &nbsp; <br>Kiri: ".$fields_mulut['tympani_bombans_kiri']  ); 
echo table_body_two_column( $column_g129 ,  $column_h129  );	

$column_g1219 = array('align'=>'left' , 'value'=>"&nbsp; &nbsp; &nbsp; &nbsp; h. Lainnya ");
$column_h1219 = array('align'=>'left' , 'value'=>"Kanan: ".$fields_mulut['tympani_lainnya_kanan']." &nbsp; <br>Kiri: ".$fields_mulut['tympani_lainnya_kiri']  ); 
echo table_body_two_column( $column_g1219 ,  $column_h1219  );	

/*	*/ 
echo table_end_two_column( );
?>
</td>
</tr>
</table>
</page>