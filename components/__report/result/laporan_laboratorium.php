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
Pemeriksaan Laboratorium :
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
<table style="width:100%;">
<tr>
<td style="width:50%;vertical-align:top;">
<?php
$column_a = array('align'=>'center' , 'value'=>'HAEMATOLOGI');
$column_b = array('align'=>'center' , 'value'=>'HASIL');
$column_c = array('align'=>'center' , 'value'=>'NILAI NORMAL');
echo table_top_three_column($column_a , $column_b , $column_c , true);

$pemeriksaan_laboratorium = my_get_data_by_id('pemeriksaan_laboratorium' , 'pemeriksaan_id' , $_GET['id']);
$sex = check_kelamin( $_GET['id'] );
$query = "SELECT 	normal_haematologi_id FROM laboratorium_normal_haematologi ORDER BY 	normal_haematologi_id DESC LIMIT 1";
$result = my_query($query);
$row = my_fetch_array($result);

$nilai_normal = my_get_data_by_id( 'laboratorium_normal_haematologi','normal_haematologi_id', $row['normal_haematologi_id']);
$value = my_get_data_by_id( 'laboratorium_haematologi','haematologi_id', $pemeriksaan_laboratorium['haematologi_id'] );
$nilai_normal_hemoglobin = ($sex == 'laki-laki') ?$nilai_normal['hemoglobin_pria']: $nilai_normal['hemoglobin_wanita'];

$form_field_hemoglobin = notice_text( $value['hemoglobin'], $nilai_normal_hemoglobin ) ;
$column_a1 = array('align'=>'left' , 'value'=>'Hemaglobin');
$column_b1 = array('align'=>'right' , 'value'=>$form_field_hemoglobin .' mg/L');
$column_c1 = array('align'=>'right' , 'value'=>$nilai_normal_hemoglobin  .' mg/L');
echo  table_body_three_column(   $column_a1 , $column_b1 ,$column_c1  );

$nilai_normal_eritrosit = ($sex == 'laki-laki' )? $nilai_normal['eritrosit_pria']: $nilai_normal['eritrosit_wanita'];
$form_field_eritrosit = notice_text ( $value['eritrosit'], $nilai_normal_eritrosit )   ;
$column_a2 = array('align'=>'left' , 'value'=>'Eritrosit');
$column_b2 = array('align'=>'right' , 'value'=>$form_field_eritrosit .' juta/mm<sup>3</sup>');
$column_c2 = array('align'=>'right' , 'value'=>$nilai_normal_eritrosit.' juta/mm<sup>3</sup>');
echo  table_body_three_column(   $column_a2 , $column_b2 ,$column_c2  );

 

$form_field_leukosit = notice_text ( $value['leukosit'], $nilai_normal['leukosit'] )   ;
$column_a3 = array('align'=>'left' , 'value'=>'Leukosit');
$column_b3 = array('align'=>'right' , 'value'=>$form_field_leukosit .' ribu/mm<sup>3</sup>');
$column_c3 = array('align'=>'right' , 'value'=>$nilai_normal['leukosit'] .' ribu/mm<sup>3</sup>');
echo  table_body_three_column(   $column_a3 , $column_b3 ,$column_c3  );
 
$nilai_normal_led = ($sex == 'laki-laki' )? $nilai_normal['led_pria']: $nilai_normal['led_wanita'];
$form_field_led =  notice_text ( $value['led'], $nilai_normal_led );
$column_a4 = array('align'=>'left' , 'value'=>'L E D');
$column_b4 = array('align'=>'right' , 'value'=>$form_field_led .'  mm/Jam ');
$column_c4 = array('align'=>'right' , 'value'=>$nilai_normal_led .'  mm/Jam ');
echo  table_body_three_column(   $column_a4 , $column_b4 ,$column_c4  );
$column_at = array('align'=>'left' , 'value'=>'<b>Hitung Jenis</b>');
$column_bt = array('align'=>'right' , 'value'=>'&nbsp;' );
$column_ct = array('align'=>'right' , 'value'=>'&nbsp;'); 
echo  table_body_three_column(   $column_at , $column_bt ,$column_ct  );

$form_field_basophil  = notice_text ( $value['basophil'], $nilai_normal['basophil']);
$column_a5 = array('align'=>'left' , 'value'=>'- Basophil');
$column_b5 = array('align'=>'right' , 'value'=>$form_field_basophil .' %' );
$column_c5 = array('align'=>'right' , 'value'=>$nilai_normal['basophil']  .' %' );
echo  table_body_three_column(   $column_a5 , $column_b5 ,$column_c5  ); 

$form_field_eosinophil  = notice_text ( $value['eosinophil'], $nilai_normal['eosinophil']);
$column_a8 = array('align'=>'left' , 'value'=>'- Eosinophil');
$column_b8 = array('align'=>'right' , 'value'=>$form_field_eosinophil .' %' );
$column_c8 = array('align'=>'right' , 'value'=>$nilai_normal['eosinophil'].' %' );
echo  table_body_three_column(   $column_a8 , $column_b8 ,$column_c8  );  


$form_field_batang  = notice_text ( $value['batang'], $nilai_normal['batang']);
$column_a9 = array('align'=>'left' , 'value'=>'- S. Batang');
$column_b9 = array('align'=>'right' , 'value'=>$form_field_batang .' %' );
$column_c9 = array('align'=>'right' , 'value'=>$nilai_normal['batang']  .' %' );
echo  table_body_three_column(   $column_a9 , $column_b9 ,$column_c9  );  


$form_field_segment  = notice_text ( $value['segment'] , $nilai_normal['segment']);
$column_a10 = array('align'=>'left' , 'value'=>'- Segment');
$column_b10 = array('align'=>'right' , 'value'=>$form_field_segment .' %' );
$column_c10 = array('align'=>'right' , 'value'=>$nilai_normal['segment']  .' %' );
echo  table_body_three_column(   $column_a10 , $column_b10 ,$column_c10  );  

$form_field_limposit  = notice_text ( $value['limposit'] , $nilai_normal['limposit']);
$column_a11 = array('align'=>'left' , 'value'=>'- Limposit');
$column_b11 = array('align'=>'right' , 'value'=>$form_field_limposit .' %' );
$column_c11 = array('align'=>'right' , 'value'=>$nilai_normal['limposit']  .' %' );
echo  table_body_three_column(   $column_a11 , $column_b11 ,$column_c11  ); 

$form_field_monosit  = notice_text ( $value['limposit'] , $nilai_normal['limposit']);
$column_a12 = array('align'=>'left' , 'value'=>'- Monosit');
$column_b12 = array('align'=>'right' , 'value'=>$form_field_monosit .' %' );
$column_c12 = array('align'=>'right' , 'value'=>$nilai_normal['monosit']  .' %' );
echo  table_body_three_column(   $column_a12 , $column_b12 ,$column_c12  ); 

$form_field_hematokrit = notice_text ( $value['hematokrit'], $nilai_normal['hematokrit'] );
$column_a5 = array('align'=>'left' , 'value'=>'Hematokrit');
$column_b5 = array('align'=>'right' , 'value'=>$form_field_hematokrit .' %');
$column_c5 = array('align'=>'right' , 'value'=>$nilai_normal['hematokrit'] .' %');
echo  table_body_three_column(   $column_a5 , $column_b5 ,$column_c5  );
 
$form_field_trombosit = notice_text ( $value['trombosit'], $nilai_normal['trombosit']);
$column_a6 = array('align'=>'left' , 'value'=>'Trombosit');
$column_b6 = array('align'=>'right' , 'value'=>$form_field_trombosit .' ribu/mm<sup>3</sup>');
$column_c6 = array('align'=>'right' , 'value'=>$nilai_normal['trombosit'] .' ribu/mm<sup>3</sup>');
echo  table_body_three_column(   $column_a6 , $column_b6 ,$column_c6  ); 

echo table_end_three_column( );
?> 
<br>
<b>URINE</b>
<?php
$column_a = array('align'=>'center' , 'value'=>'HAEMATOLOGI');
$column_b = array('align'=>'center' , 'value'=>'HASIL');
$column_c = array('align'=>'center' , 'value'=>'NILAI NORMAL');
echo table_top_three_column($column_a , $column_b , $column_c , false);

$pemeriksaan_laboratorium = my_get_data_by_id('pemeriksaan_laboratorium' , 'pemeriksaan_id' , $_GET['id']);
  
$query = "SELECT normal_urine_id FROM laboratorium_normal_urine ORDER BY normal_urine_id DESC LIMIT 1";
$result = my_query($query);
$row = my_fetch_array($result);

$nilai_normal = my_get_data_by_id( 'laboratorium_normal_urine','normal_urine_id', $row['normal_urine_id']);
$value = my_get_data_by_id( 'laboratorium_urine','urine_id', $pemeriksaan_laboratorium['urine_id'] );
/*
$form_field_warna = notice_text( $value['warna'],$nilai_normal['warna'] ) ;
$view .= table_body_three_column( $form_field_warna   , "Warna urine"  , $nilai_normal['warna'] );

 
$form_field_kejernihan = notice_text( $value['kejernihan'],$nilai_normal['kejernihan'] ) ;
$view .= table_body_three_column( $form_field_kejernihan     , "Kejernihan"  , $nilai_normal['kejernihan'] );	
 */
 
$form_field_bd = notice_text( $value['bd'],$nilai_normal['bd'] ) ;
$column_a1281 = array('align'=>'left' , 'value'=>'B.D');
$column_b1281 = array('align'=>'center' , 'value'=>$form_field_bd  );
$column_c1281 = array('align'=>'center' , 'value'=>  $nilai_normal['bd']   );
echo  table_body_three_column(   $column_a1281, $column_b1281 ,$column_c1281  );  
 
$form_field_ph = notice_text( $value['ph'],$nilai_normal['ph'] ) ;
$column_a128 = array('align'=>'left' , 'value'=>'Reaksi / PH');
$column_b128 = array('align'=>'center' , 'value'=>$form_field_ph  );
$column_c128 = array('align'=>'center' , 'value'=>  $nilai_normal['ph']   );
echo  table_body_three_column(   $column_a128, $column_b128 ,$column_c128  );     

  
$form_field_protein = notice_text( $value['protein'],$nilai_normal['protein'] ) ;
$column_a18 = array('align'=>'left' , 'value'=>'Protein');
$column_b18 = array('align'=>'center' , 'value'=>$form_field_protein  );
$column_c18 = array('align'=>'center' , 'value'=>label_positif_negatif( $nilai_normal['protein'] ) );
echo  table_body_three_column(   $column_a18, $column_b18 ,$column_c18  );    

$form_field_reduksi = notice_text( $value['reduksi'],$nilai_normal['reduksi'] ) ;
$column_a9 = array('align'=>'left' , 'value'=>'Reduksi');
$column_b9 = array('align'=>'center' , 'value'=>$form_field_reduksi  );
$column_c9 = array('align'=>'center' , 'value'=>label_positif_negatif( $nilai_normal['reduksi'] ) );
echo  table_body_three_column(   $column_a9, $column_b9 ,$column_c9  );   

$form_field_billirubin = notice_text( $value['billirubin'],$nilai_normal['billirubin'] ) ;
$column_a8 = array('align'=>'left' , 'value'=>'Billirubin');
$column_b8 = array('align'=>'center' , 'value'=>$form_field_billirubin  );
$column_c8 = array('align'=>'center' , 'value'=>label_positif_negatif( $nilai_normal['billirubin'] ) );
echo  table_body_three_column(   $column_a8, $column_b8 ,$column_c8  );    

$form_field_urobilin = notice_text( $value['urobilin'],$nilai_normal['urobilin'] ) ;
$column_a7 = array('align'=>'left' , 'value'=>'Urobilin');
$column_b7 = array('align'=>'center' , 'value'=>$form_field_urobilin  );
$column_c7 = array('align'=>'center' , 'value'=>label_positif_negatif( $nilai_normal['urobilin'] ) );
echo  table_body_three_column(   $column_a7, $column_b7 ,$column_c7  );   


$form_field_urobilinogen = notice_text( $value['urobilinogen'],$nilai_normal['urobilinogen'] ) ;
$column_a6 = array('align'=>'left' , 'value'=>'Urobilinogen');
$column_b6 = array('align'=>'center' , 'value'=>$form_field_urobilinogen  );
$column_c6 = array('align'=>'center' , 'value'=>label_positif_negatif($nilai_normal['urobilinogen']) );
echo  table_body_three_column(   $column_a6 , $column_b6 ,$column_c6  );  


 
echo table_end_three_column( );
?>
<br>
<b><u>MIKROSKOPIS :</u></b><br>
<?php
$column_a = array('align'=>'center' , 'value'=>'HAEMATOLOGI');
$column_b = array('align'=>'center' , 'value'=>'HASIL');
$column_c = array('align'=>'center' , 'value'=>'NILAI NORMAL');
echo table_top_three_column($column_a , $column_b , $column_c , false);
 		 
 
$form_field_lekosit = notice_text( $value['lekosit'],$nilai_normal['lekosit'] ) ;
$column_a61 = array('align'=>'left' , 'value'=>'- Lekosit');
$column_b61 = array('align'=>'center' , 'value'=>$form_field_lekosit  );
$column_c61 = array('align'=>'center' , 'value'=>label_positif_negatif($nilai_normal['lekosit']) );
echo  table_body_three_column(   $column_a61 , $column_b61 ,$column_c61  );  


$form_field_eritrosit = notice_text( $value['eritrosit'],$nilai_normal['eritrosit'] ) ;
$column_a62 = array('align'=>'left' , 'value'=>'- Eritrosit');
$column_b62 = array('align'=>'center' , 'value'=>$form_field_eritrosit  );
$column_c62 = array('align'=>'center' , 'value'=>label_positif_negatif($nilai_normal['eritrosit']) );
echo  table_body_three_column(   $column_a62 , $column_b62 ,$column_c62  );  


$form_field_ephitel = notice_text( $value['ephitel'],$nilai_normal['ephitel'] ) ;
$column_a63 = array('align'=>'left' , 'value'=>'- epithel');
$column_b63 = array('align'=>'center' , 'value'=>$form_field_ephitel  );
$column_c63 = array('align'=>'center' , 'value'=>label_positif_negatif($nilai_normal['ephitel']) );
echo  table_body_three_column(   $column_a63 , $column_b63 ,$column_c63 );  


$form_field_kristal = notice_text( $value['kristal'],$nilai_normal['kristal'] ) ;
$column_a64 = array('align'=>'left' , 'value'=>'- Kristal');
$column_b64 = array('align'=>'center' , 'value'=>$form_field_kristal  );
$column_c64 = array('align'=>'center' , 'value'=>label_positif_negatif($nilai_normal['kristal']) );
echo  table_body_three_column(   $column_a64 , $column_b64 ,$column_c64  );  


$form_field_silend = notice_text( $value['silend'],$nilai_normal['silend'] ) ;
$column_a65 = array('align'=>'left' , 'value'=>'- Silend');
$column_b65 = array('align'=>'center' , 'value'=>$form_field_silend  );
$column_c65 = array('align'=>'center' , 'value'=>label_positif_negatif($nilai_normal['silend']) );
echo  table_body_three_column(   $column_a65 , $column_b65 ,$column_c65  );  

$form_field_bakteri = notice_text( $value['bakteri'],$nilai_normal['bakteri'] ) ;
$column_a657 = array('align'=>'left' , 'value'=>'- Bakteri');
$column_b657 = array('align'=>'center' , 'value'=>$form_field_bakteri  );
$column_c657 = array('align'=>'center' , 'value'=>label_positif_negatif($nilai_normal['bakteri']) );
echo  table_body_three_column(   $column_a657 , $column_b657 ,$column_c657  );  

$form_field_jamur = notice_text( $value['jamur'],$nilai_normal['jamur'] ) ;
$column_a658 = array('align'=>'left' , 'value'=>'- Jamur');
$column_b658 = array('align'=>'center' , 'value'=>$form_field_jamur  );
$column_c658 = array('align'=>'center' , 'value'=>label_positif_negatif($nilai_normal['bakteri']) );
echo  table_body_three_column(   $column_a658 , $column_b658 ,$column_c658 );  

$form_field_lendir = notice_text( $value['lendir'],$nilai_normal['lendir'] ) ;
$column_a66 = array('align'=>'left' , 'value'=>'- Lendir');
$column_b66 = array('align'=>'center' , 'value'=>$form_field_lendir  );
$column_c66 = array('align'=>'center' , 'value'=>label_positif_negatif($nilai_normal['lendir']) );
echo  table_body_three_column(   $column_a65 , $column_b65 ,$column_c65  );  
 
echo table_end_three_column( );
?><br><br> <br><br>
<div style="text-align:center;width:300px">
Dokter<br><br><br><br><br><br> 
( &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; )</div>
</td>
<td style="width:50%;vertical-align:top;">
<?php
$column_a = array('align'=>'center' , 'value'=>'KIMIA DARAH');
$column_b = array('align'=>'center' , 'value'=>'HASIL');
$column_c = array('align'=>'center' , 'value'=>'NILAI NORMAL');
echo table_top_three_column($column_a , $column_b , $column_c , true);
$query = "SELECT normal_kimia_darah_id FROM laboratorium_normal_kimia_darah ORDER BY normal_kimia_darah_id DESC LIMIT 1";
$result = my_query($query);
$row = my_fetch_array($result);

$nilai_normal = my_get_data_by_id( 'laboratorium_normal_kimia_darah','normal_kimia_darah_id', $row['normal_kimia_darah_id']);
$value = my_get_data_by_id( 'laboratorium_kimia_darah','kimia_darah_id', $pemeriksaan_laboratorium['kimia_darah_id'] );

$form_field_gula_darah = notice_text( $value['gula_darah'],$nilai_normal['gula_darah'] ) ;
$column_a21 = array('align'=>'left' , 'value'=>'Gula darah');
$column_b21 = array('align'=>'right' , 'value'=>$form_field_gula_darah .' mg/dL');
$column_c21 = array('align'=>'right' , 'value'=>$nilai_normal['gula_darah'] .' mg/dL');
echo  table_body_three_column(   $column_a21 , $column_b21 ,$column_c21  ); 
 
$form_field_gula_2_jam_pp = notice_text( $value['gula_2_jam_pp'],$nilai_normal['gula_2_jam_pp'] ) ;
$column_a22 = array('align'=>'left' , 'value'=>'Gula 2 jam PP');
$column_b22 = array('align'=>'right' , 'value'=>$form_field_gula_2_jam_pp .' mg/dL');
$column_c22 = array('align'=>'right' , 'value'=>$value['gula_2_jam_pp'] .' mg/dL');
echo  table_body_three_column(   $column_a22 , $column_b22,$column_c22  );  

$form_field_cholesterol = notice_text( $value['cholesterol'],$nilai_normal['cholesterol'] ) ;
$column_a23 = array('align'=>'left' , 'value'=>'Cholesterol');
$column_b23 = array('align'=>'right' , 'value'=>$form_field_cholesterol .' mg/dL' );
$column_c23 = array('align'=>'right' , 'value'=>$nilai_normal['cholesterol'].' mg/dL' );
 echo  table_body_three_column(   $column_a23 , $column_b23,$column_c23  );  


 
$nilai_normal_hdl_chol = ($sex == 'laki-laki') ?$nilai_normal['hdl_chol_pria' ]: $nilai_normal['hdl_chol_wanita' ];
$form_field_hdl_chol = notice_text( $value['hdl_chol'], $nilai_normal_hdl_chol ) ;
$column_a24 = array('align'=>'left' , 'value'=>'HDL-Chol');
$column_b24 = array('align'=>'right' , 'value'=>$form_field_hdl_chol .' mg/dL' );
$column_c24 = array('align'=>'right' , 'value'=>$nilai_normal_hdl_chol .' mg/dL' );
 echo  table_body_three_column(   $column_a24 , $column_b24,$column_c24  );   


$form_field_ldl_chol = notice_text( $value['ldl_chol'],$nilai_normal['ldl_chol'] ) ;
$column_a25 = array('align'=>'left' , 'value'=>'LDL-Chol');
$column_b25 = array('align'=>'right' , 'value'=>$form_field_ldl_chol .' mg/dL' );
$column_c25 = array('align'=>'right' , 'value'=>$nilai_normal['ldl_chol'].' mg/dL' );
 echo  table_body_three_column(   $column_a25 , $column_b25,$column_c25  );   
 	
 
$form_field_triglyseride = notice_text( $value['triglyseride'],$nilai_normal['triglyseride'] ) ;
$column_a26 = array('align'=>'left' , 'value'=>'Triglyseride');
$column_b26 = array('align'=>'right' , 'value'=>$form_field_triglyseride .' mg/dL' );
$column_c26 = array('align'=>'right' , 'value'=>$nilai_normal['triglyseride'].' mg/dL' );
echo  table_body_three_column(   $column_a26 , $column_b26,$column_c26  );   
 
$form_field_ureum = notice_text( $value['ureum'],$nilai_normal['ureum'] ) ;
$column_a27 = array('align'=>'left' , 'value'=>'Ureum');
$column_b27 = array('align'=>'right' , 'value'=>$form_field_ureum .' mg/dL' );
$column_c27 = array('align'=>'right' , 'value'=>$nilai_normal['ureum'].' mg/dL' );
echo  table_body_three_column(   $column_a27 , $column_b27,$column_c27 );   

$nilai_normal_creatinine = ($sex == 'laki-laki') ?$nilai_normal['creatinine_pria' ]: $nilai_normal['creatinine_wanita' ];
$form_field_creatinine = notice_text( $value['creatinine'], $nilai_normal_creatinine ) ;	
$column_a28 = array('align'=>'left' , 'value'=>'Creatinine');
$column_b28 = array('align'=>'right' , 'value'=>$form_field_creatinine .' mg/dL' );
$column_c28 = array('align'=>'right' , 'value'=>$nilai_normal_creatinine.' mg/dL' );
echo  table_body_three_column(   $column_a28 , $column_b28,$column_c28 );   

$nilai_normal_uric_acide = ($sex == 'laki-laki') ?$nilai_normal['uric_acide_pria' ]: $nilai_normal['uric_acide_wanita' ];
$form_field_uric_acide = notice_text( $value['uric_acide'], $nilai_normal_uric_acide ) ;	 
$column_a29 = array('align'=>'left' , 'value'=>'Uric acide');
$column_b29 = array('align'=>'right' , 'value'=>$form_field_uric_acide .' mg/dL' );
$column_c29 = array('align'=>'right' , 'value'=>$nilai_normal_uric_acide.' mg/dL' );
echo  table_body_three_column(   $column_a29 , $column_b29,$column_c29 );  

$form_field_bill_total = notice_text( $value['bill_total'],$nilai_normal['bill_total'] ) ;
$column_a30 = array('align'=>'left' , 'value'=>'Bill Total');
$column_b30 = array('align'=>'right' , 'value'=>$form_field_bill_total .' mg/dL' );
$column_c30 = array('align'=>'right' , 'value'=>$nilai_normal['bill_total'].' mg/dL' );
echo  table_body_three_column(   $column_a30 , $column_b30,$column_c30 ); 

$form_field_bill_direct = notice_text( $value['bill_direct'],$nilai_normal['bill_direct'] ) ;
$column_a31 = array('align'=>'left' , 'value'=>'Bill Direct');
$column_b31 = array('align'=>'right' , 'value'=>$form_field_bill_direct .' mg/dL' );
$column_c31 = array('align'=>'right' , 'value'=>$nilai_normal['bill_direct'].' mg/dL' );
echo  table_body_three_column(   $column_a31 , $column_b31,$column_c31 ); 

$nilai_normal_sgpt = ($sex == 'laki-laki') ?$nilai_normal['sgpt_pria' ]: $nilai_normal['sgpt_wanita' ];
$form_field_sgpt = notice_text( $value['sgpt'],$nilai_normal_sgpt ) ;	 
$column_a32 = array('align'=>'left' , 'value'=>'S G P T');
$column_b32 = array('align'=>'right' , 'value'=>$form_field_sgpt.' u/l' );
$column_c32 = array('align'=>'right' , 'value'=>$nilai_normal_sgpt.' u/l');
echo  table_body_three_column(   $column_a32 , $column_b32,$column_c32 );  
 
$nilai_normal_sgot = ($sex == 'laki-laki') ?$nilai_normal['sgot_pria' ]: $nilai_normal['sgot_wanita' ];
$form_field_sgot = notice_text( $value['sgot'], $nilai_normal_sgot ) ;	 
$column_a33 = array('align'=>'left' , 'value'=>'S G O T');
$column_b33 = array('align'=>'right' , 'value'=>$form_field_sgot.' u/l' );
$column_c33 = array('align'=>'right' , 'value'=>$nilai_normal_sgot.' u/l');
echo  table_body_three_column(   $column_a33 , $column_b33 ,$column_c33 );  

 
$form_field_gama_gt 	=	$value['gamma_gt'] ;
$column_a34 = array('align'=>'left' , 'value'=>'Gamma GT');
$column_b34 = array('align'=>'right' , 'value'=>$form_field_gama_gt .' mg/dL' );
$column_c34 = array('align'=>'right' , 'value'=>'&nbsp;' );
echo  table_body_three_column(   $column_a34 , $column_b34 ,$column_c34 );  
  
$form_field_alk_phospat = notice_text( $value['alk_phospat'],$nilai_normal['alk_phospat'] ) ;
$column_a34 = array('align'=>'left' , 'value'=>'Alk. Phospat');
$column_b34 = array('align'=>'right' , 'value'=>$form_field_alk_phospat .' mg/dL' );
$column_c34 = array('align'=>'right' , 'value'=>$nilai_normal['alk_phospat'].' mg/dL' );
echo  table_body_three_column(   $column_a34 , $column_b34 ,$column_c34 );  
 
$form_field_total_protein = notice_text( $value['total_protein'],$nilai_normal['total_protein'] ) ;
$column_a35 = array('align'=>'left' , 'value'=>'Total Protein');
$column_b35 = array('align'=>'right' , 'value'=>$form_field_total_protein  .' mg/dL' );
$column_c35 = array('align'=>'right' , 'value'=>$nilai_normal['total_protein'].' mg/dL' );
echo  table_body_three_column(   $column_a35 , $column_b35 ,$column_c35 );   
 
$form_field_albumin = notice_text( $value['albumin'],$nilai_normal['albumin'] ) ;
$column_a36 = array('align'=>'left' , 'value'=>'Albumin');
$column_b36 = array('align'=>'right' , 'value'=>$form_field_albumin   .' mg/dL' );
$column_c36 = array('align'=>'right' , 'value'=>$nilai_normal['albumin'].' mg/dL' );
echo  table_body_three_column(   $column_a36 , $column_b36 ,$column_c36 );    
 		
echo table_end_three_column( );
?> 
<?php
$pemeriksaan_laboratorium = my_get_data_by_id('pemeriksaan_laboratorium' , 'pemeriksaan_id' , $_GET['id']);
$valuep = my_get_data_by_id( 'laboratorium_lain','laboratorium_lain_id', $pemeriksaan_laboratorium['laboratorium_lain_id'] );

?>
<br><br><br>  
<div style="width:350px;">
&nbsp; <b><u>LAIN-LAIN PEMERIKSAAN :</u></b><br><br><br>
<table style="width:100%">
<tr>
<td style="width:45%">
<b>&nbsp; Pregnancy Test</b>
</td>
<td style="width:55%"><b>: <?php echo $valuep['pregnancy_test'];?></b> 

</td>
</tr>
<tr>
<td style="width:45%">
<b>&nbsp; Golongan Darah</b>
</td>
<td style="width:55%"><b>: <?php echo $valuep['gol_da'];?></b> 

</td>
</tr>
<tr>
<td style="width:45%;height:40px">
<b>&nbsp; V.D.R.L</b>
</td>
<td style="width:55%;height:40px"><b>: <?php echo $valuep['vdrl'];?></b> 

</td>
</tr>

<tr>
<td style="width:45%;height:40px">
<b>&nbsp; Amphetamine/Shabu-shabu</b>
</td>
<td style="width:55%;height:40px"><b>: <?php echo label_positif_negatif( $valuep['amphetamine']);?></b> 

</td>
</tr>   
<tr>
<td style="width:45%;height:40px">
<b>&nbsp; Metamphetamine/Ekstasi</b>
</td>
<td style="width:55%;height:40px"><b>: <?php echo label_positif_negatif( $valuep['metamphetamine']);?></b> 

</td>
</tr>   
<tr>
<td style="width:45%;height:40px">
<b>&nbsp; Coccain</b>
</td>
<td style="width:55%;height:40px"><b>: <?php echo label_positif_negatif( $valuep['coccain']);?></b> 

</td>
</tr>
<tr>
<td style="width:45%;height:40px">
<b>&nbsp; Heroin/Morphin/Opiat/Putauw</b>
</td>
<td style="width:55%;height:40px"><b>: <?php echo label_positif_negatif( $valuep['heroin']);?></b> 

</td>
</tr>
<tr>
<td style="width:45%;height:40px">
<b>&nbsp; Marijuana/Canabis</b>
</td>
<td style="width:55%;height:40px"><b>: <?php echo label_positif_negatif( $valuep['marijuana']);?></b> 

</td>
</tr>
<tr>
<td style="width:45%;height:40px">
<b>&nbsp; Benzodiazepime</b>
</td>
<td style="width:55%;height:40px"><b>: <?php echo label_positif_negatif( $valuep['benzodiazepime']);?></b> 

</td>
</tr>
<tr>
<td style="width:45%;height:40px">
<b>&nbsp; Alkohol</b>
</td>
<td style="width:55%;height:40px"><b>: <?php echo label_positif_negatif( $valuep['alkohol']);?></b> 

</td>
</tr> 
</table>
<br>&nbsp; .....................................................................................  
<br>&nbsp; .....................................................................................  
    
</div>
<br><br><br><br><br><br><br>  
<div style="text-align:center;width:300px">
Pemeriksa<br><br><br><br><br><br> 
( &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; )</div>
</td>
</tr>
</table>