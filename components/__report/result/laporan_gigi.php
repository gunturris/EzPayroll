<?php
$peserta_id = get_peserta_id_by_pemeriksaan_id($_GET['id']);
$peserta = loaddata_peserta($peserta_id);
$date_cetak= date('d-m-Y');

function load_gigi_image($pid , $poid){
	$odontogram   = my_get_data_by_id('odontogram' ,'pemeriksaan_id' ,$pid);
  
	$location = 'http://'.$_SERVER['HTTP_HOST'].'/'._PREFIXDOMAIN_.'/';
	$odid = $odontogram['odontogram_id'];
	
	if(check_marker($poid , $odontogram['odontogram_id']))
		return  '<img src="'.$location.'index.php?com=gigi&task=image&odid='.$odid.'&kode='.$poid .'&t='.rand(0,2321).'" width="35" border="0"/>';
	else
		return  '<img src="'.$location.'index.php?com=gigi&task=image&odid=0&kode='.$poid .'&t='.rand(0,2321).'" width="35" border="0"/>';
}



function check_marker($kode , $odid){
	$query = "SELECT * FROM odontogram_gigi 
	WHERE gigi_pos_kode='{$kode}' AND odontogram_id='{$odid}' ";
	 
	$result = my_query($query);
	$rows =my_num_rows($result); 
	if( $rows > 0 ){ 
		return true;
	}
	return false;
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
Pemeriksaan Audiometri :
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
$pemeriksaan_id = (int) $_GET['id'];
$code_periksa = date('Y',strtotime($periksa['datetime_added'])).sprintf("%05s", $_GET['id']) ;
?>	
No. file : <?php echo $code_periksa; ?>
</td>
</tr>
</table> <br><br>

<table style="width:90%;font-family:times;border:1px solid;">
<tr>

	<td width="6%" style="text-align:center"><?php echo load_gigi_image($pemeriksaan_id , 18); ?><br>18</td>
	<td width="6%" style="text-align:center"><?php echo load_gigi_image($pemeriksaan_id , 17); ?><br>17</td>
	<td width="6%" style="text-align:center"><?php echo load_gigi_image($pemeriksaan_id , 16); ?><br>16</td>
	<td width="6%" style="text-align:center"><?php echo load_gigi_image($pemeriksaan_id , 15); ?><br>15</td>
	<td width="6%" style="text-align:center"><?php echo load_gigi_image($pemeriksaan_id , 14); ?><br>14</td>
	<td width="6%" style="text-align:center"><?php echo load_gigi_image($pemeriksaan_id , 13); ?><br>13</td>
	<td width="6%" style="text-align:center"><?php echo load_gigi_image($pemeriksaan_id , 12); ?><br>12</td>
	<td width="6%" style="text-align:center"><?php echo load_gigi_image($pemeriksaan_id , 11); ?><br>11</td>
	
	<td width="2%" style="text-align:center;font-size:34px;">  | </td>
	
	<td width="6%" style="text-align:center"><?php echo load_gigi_image($pemeriksaan_id , 21); ?><br>21</td>
	<td width="6%" style="text-align:center"><?php echo load_gigi_image($pemeriksaan_id , 22); ?><br>22</td>
	<td width="6%" style="text-align:center"><?php echo load_gigi_image($pemeriksaan_id , 23); ?><br>23</td>
	<td width="6%" style="text-align:center"><?php echo load_gigi_image($pemeriksaan_id , 24); ?><br>24</td>
	<td width="6%" style="text-align:center"><?php echo load_gigi_image($pemeriksaan_id , 25); ?><br>25</td>
	<td width="6%" style="text-align:center"><?php echo load_gigi_image($pemeriksaan_id , 26); ?><br>26</td>
	<td width="6%" style="text-align:center"><?php echo load_gigi_image($pemeriksaan_id , 27); ?><br>27</td>
	<td width="6%" style="text-align:center"><?php echo load_gigi_image($pemeriksaan_id , 28); ?><br>28</td>
	
</tr>
<tr>
	<td width="6%" style="text-align:center"><?php echo load_gigi_image($pemeriksaan_id , 48); ?><br>48</td>
	<td width="6%" style="text-align:center"><?php echo load_gigi_image($pemeriksaan_id , 47); ?><br>47</td>
	<td width="6%" style="text-align:center"><?php echo load_gigi_image($pemeriksaan_id , 46); ?><br>46</td>
	<td width="6%" style="text-align:center"><?php echo load_gigi_image($pemeriksaan_id , 45); ?><br>45</td>
	<td width="6%" style="text-align:center"><?php echo load_gigi_image($pemeriksaan_id , 44); ?><br>44</td>
	<td width="6%" style="text-align:center"><?php echo load_gigi_image($pemeriksaan_id , 43); ?><br>43</td>
	<td width="6%" style="text-align:center"><?php echo load_gigi_image($pemeriksaan_id , 42); ?><br>42</td>
	<td width="6%" style="text-align:center"><?php echo load_gigi_image($pemeriksaan_id , 41); ?><br>41</td>
	
	<td width="2%" style="text-align:center;font-size:34px;">  | </td>
	
	<td width="6%" style="text-align:center"><?php echo load_gigi_image($pemeriksaan_id , 31); ?><br>31</td>
	<td width="6%" style="text-align:center"><?php echo load_gigi_image($pemeriksaan_id , 32); ?><br>32</td>
	<td width="6%" style="text-align:center"><?php echo load_gigi_image($pemeriksaan_id , 33); ?><br>33</td>
	<td width="6%" style="text-align:center"><?php echo load_gigi_image($pemeriksaan_id , 34); ?><br>34</td>
	<td width="6%" style="text-align:center"><?php echo load_gigi_image($pemeriksaan_id , 35); ?><br>35</td>
	<td width="6%" style="text-align:center"><?php echo load_gigi_image($pemeriksaan_id , 36); ?><br>36</td>
	<td width="6%" style="text-align:center"><?php echo load_gigi_image($pemeriksaan_id , 37); ?><br>37</td>
	<td width="6%" style="text-align:center"><?php echo load_gigi_image($pemeriksaan_id , 38); ?><br>38</td>
	
</tr>
</table>