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
td 
{
	font-size:12px;
}
-->
</style>
<page  orientation="P" backtop="0" backbottom="0" backleft="6mm" backright="6mm"> 
 
<table style="width:90%;font-family:times"> 
<tr>
<td style="width:70%;">
	<table style="width:90%;vertical-align:top;" cellspacing="0">
	<tr>
		<td style="width:20%;vertical-align:top;">
		<img width="58" src="templates/hatpen/imgs/logo_dephub.jpeg">
		</td>
		<td style="letter-spacing:2px;line-height:15px;font-size:11px;width:80%;vertical-align:top;">
		MINISTRY OF TRANSPORTATION<br>
		DIRECTORATE GENERAL OF CIVIL AVIATION<br>
		CIVIL AVIATION MEDICAL CENTRE<span style="font-size:10px;"><br>
Kota Baru Bandar Kemayoran Blok B11 Kav. No. 4 Jakarta 10610<br>
Phone : (62)(021)65867830 Fax : (62)(021)65867832<br>
email : indavmed@cbn.net.id
		</span>
	</td></tr></table>
</td>
<td style="width:20%;font-face:helvetica">
	<div style="width:220px;border:1px solid;">
	<table cellspacing="0" style="border:1px solid;">
	<tr>
	<td style="vertical-align:middle;height:30px;width:210px;text-align:center;font-size:22px;border:1px solid;">
	CONFIDENTIAL
	</td>
	</tr>
	<tr>
	<td style="vertical-align:middle;height:30px;width:210px;text-align:center;font-size:22px;border:1px solid;">
	NO-COPY
	</td>
	</tr>
	
	</table>
	</div>
</td>
</tr> 
</table>

<table style="width:100%;font-family:times">
<tr>
<td style="width:45%;height:24px">
&nbsp;
</td>
<td style="width:53%;text-align:right;">
MEDICAL EXAMINATION REPORT<br>
INITIAL/PERIODICAL
</td>
</tr> 
</table>  
<table style="width:98%;font-family:times">
<tr>
<td style="width:49%;vertical-align:top;">
<?php
$maskapai = my_get_data_by_id('maskapai' , 'maskapai_id' , $peserta['maskapai_id']);
$datas = array(
	'Nama (Full)'=>$peserta['nama'],
	'Address'=>$peserta['alamat'],
	'Airlines'=>$maskapai['maskapai_label'],
	'Departemen'=>'? ? ? ?',
	'Occupation'=>'? ? ? ?',
	'Date of Entry Service'=>'? ? ? ?',
);
echo display_result($datas);
?>
</td>
<td style="width:49%;vertical-align:top;">
 <?php
$pekerjaan = my_get_data_by_id('pekerjaan' , 'pekerjaan_id' , $peserta['pekerjaan_id']);
$datas2 = array(
	'Sex'=>ucfirst($peserta['kelamin']),
	'Place / Date of Birth'=>date('d-m-Y' , strtotime($peserta['tanggal_lahir']) ),
	'Single / Married'=>'? ? '.' Child : '.'? ? ?',
	'Departemen'=>'? ? ? ?',
	'Application for'=>$pekerjaan['pekerjaan_label'] .' ('.$pekerjaan['pekerjaan_code'].')'
);
echo display_result($datas2);
?>
</td>
</tr> 
</table> 
<table style="width:98%;font-family:times">
<tr><td style="border-bottom:1px solid;border-top:1px solid;">
<table style="width:98%;font-family:times">
<tr>
<td class="data" cellspacing="0" style="width:40%;vertical-align:top;">
 
General Appearance :

</td>
<td class="data" style="width:20%;vertical-align:top;">

Height : ? cm
</td> 
<td class="data" style="width:20%;vertical-align:top;">

Weight : ? kg
</td> 
<td class="data" style="width:20%;vertical-align:top;">
Lab : 


</td> 
</tr> 
</table> 
</td> 
</tr> 
</table>  
<table style="width:98%;font-family:times">
<tr><td style="width:98%;height:90px;vertical-align:top">
	
	<table style="width:98%;font-family:times">
	<tr>
	<td class="data" cellspacing="0" style="width:49%;vertical-align:top;">
	 
	Medical History :

	</td>
	<td class="data" style="width:49%;vertical-align:top;">
	Family Medical History:
	</td> 
	</tr> 
	</table> 

</td> 
</tr> 
</table>
<table style="width:98%;font-family:times">
<tr>
<td class="data" style="line-height:20px;width:98%;border-bottom:1px solid;vertical-align:top">
Currently use Medication &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; :
<br>
Habits (drugs, alcohol, tobacco) &nbsp; :
</td> 
</tr> 
</table>	
<table style="width:98%;font-family:times">
<tr>
<td class="data" style="width:98%;vertical-align:top">
Note : Fill N if Normal - ABN id Abnormal 
</td> 
</tr> 
</table>
<table style="width:98%;font-family:times">
<tr>
<td class="data" style="width:39%;vertical-align:top">
  <?php
$pekerjaan = my_get_data_by_id('pekerjaan' , 'pekerjaan_id' , $peserta['pekerjaan_id']);
$datasa = array(
	'Head, Neck'=>'????',
	'Nose'=>'????',
	'Sinuses'=>'????',
	'Mouth, throat'=>'????',
	'Chest'=>'????',
	'Lungs'=>'????',
	'Abdomen, Viscera'=>'????',
	'Genito - Urinary System'=>'????',
	'Skin, Lymphatic'=>'????',
	'Spine, Extremites'=>'????',
	'Neurologic/Reflex'=>'????',
	);
echo display_result($datasa , false);
?>
</td> 
<td class="data" style="width:39%;vertical-align:top">
  <?php
$pekerjaan = my_get_data_by_id('pekerjaan' , 'pekerjaan_id' , $peserta['pekerjaan_id']);
$datasb = array(
	'Ears'=>'????',
	'Audiometry'=>'????',
	'Eyes'=>'????',
	'Colour Vision'=>'????',
	'Breast'=>'????',
	'Heart'=>'????',
	'Dental'=>'????',
	'Last Menstrual'=>'????',
);
echo display_result($datasb , false);
?>
</td> 
<td class="data" style="line-height:22px;width:20%;vertical-align:top;text-align:center;">
 Detail Description of:<br>
 Abnormal (ABN) Findings
 <br>
 <br>
 <br>
 <br>
 ______________________<br>
 Other Particulairs
</td> 
</tr> 
</table>

<table style="width:98%;font-family:times">
<tr><td style="border-bottom:1px solid;border-top:1px solid;">
<table style="width:98%;font-family:times">
<tr>
<td class="data" cellspacing="0" style="width:40%;vertical-align:top;">
 
Blood pressure :   &nbsp; ? &nbsp; mmHg.

</td>
<td class="data" style="width:30%;vertical-align:top;">
Pulse : &nbsp; ? &nbsp; X/Minute
</td> 
<td class="data" style="width:30%;vertical-align:top;">
ECG/Treadmill : &nbsp; ? &nbsp; X/Minute
</td> 
</tr> 
</table> 
</td> 
</tr> 
</table> 
</page> 