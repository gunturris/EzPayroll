<?php
$peserta_id  = get_peserta_id_by_pemeriksaan_id($_GET['id']);
$peserta = loaddata_peserta($peserta_id);
$date_cetak	 = date('d-m-Y');

?>
<page  format="86x120" orientation="P" backtop="0" backbottom="0" backleft="4" backright="2mm"> 
<div style="font-size:14px;width:230px;height:30px;text-align:center;">
&nbsp;
</div> 
<div style="font-size:14px;width:230px;height:28px;text-align:center;">
Second
</div>
  
<div style="padding:0;font-size:11px;width:230px;text-align:right;height:22px;">
<?php echo $peserta['nama']; ?></div> 
<div style="padding:0;font-size:11px;width:230px;height:63px;text-align:right;">
<?php echo nl2br(trim($peserta['alamat'])); ?>
<br>
<?php echo nl2br($peserta['kota']); ?>
</div>
<div style="padding:0;font-size:11px;width:230px;height:53px">
<br>
<br>
<table style="width:200px;">
<tr>
	<td style="text-align:center;width:40px">A1</td>
	<td style="text-align:center;width:30px">B1</td>
	<td style="text-align:center;width:40px">C1</td>
	<td style="text-align:center;width:45px">v1</td>
	<td style="text-align:center;width:40px">b1</td>
	<td style="text-align:center;width:30px">D1</td>
</tr>
</table>  
</div>
<br>  
<div style="padding:0;font-size:11px;width:230px;height:85px;text-align:right;">
limitation
</div>  
<div style="padding:0;font-size:11px;width:230px;height:22px;">
15-April-2010 &nbsp;   &nbsp;  &nbsp;   &nbsp; 
 &nbsp;   &nbsp;  &nbsp;     &nbsp;   &nbsp; 15 April 2014
</div>
<div style="padding:0;font-size:11px;width:230px;height:28px;text-align:right;">
 Signature2
</div>
<div style="padding:0;font-size:11px;width:230px;height:28px;text-align:right;">
 Typed name2
</div>
</page>