<?php 
 
 
function page_block_design(){ 
 
 my_set_code_css(
 '
 .title_welcome_box{
	font-size:22px;
	font-family:helvetica;
	margin-top:8px;
	margin-left:4px;
	padding-bottom:5px;
	border-bottom:2px solid;
 } 
 #calendar {margin:5px; width:98%;} 
 #calendar .th  {
	width:58px;
	text-align:center;
	background:brown;
	color:#fff;
	font-size:13px;
	padding-bottom:2px;
	padding-top:2px;
} 
 #calendar .month td {border: 1px solid grey;} 
 #calendar .tr {color:red;
 text-align:center;}  
 .td_empty{background-color:black}
 '
 );
 

$design2 ='<div style="background:#fff;width:97%;height:100%;text-align:left;border:1px solid grey;padding:3px;">
<div class="title_welcome_box">Data dan Summary</div><br/>'.data_sumary().'
 
</div>';


$design3 ='<div style="background:#fff;width:96%;height:100%;text-align:left;border:1px solid grey;padding:3px;">
<div class="title_welcome_box">Bank transfer terkini</div>    <br/>
 <img src="index.php?com=graph&task=bank_transfer"   />
</div>';
/*
$design4 ='<div style="background:#fff;width:97%;height:100%;text-align:left;border:2px solid brown;padding:3px;">
<div class="title_welcome_box">Periode lalu</div> <div id=\'calendar\'>'.display_caledar().'
<b><span style="color:blue">Biru untuk jumlah ijin</span><br/>
<span style="color:brown">Cokelat untuk jumlah dinas</span><br/>
<span style="color:orange">Orange untuk jumlah cuti</span>
</b></div>
</div>';
$design5 ='<div style="background:#fff;width:97%;height:100%;text-align:left;border:2px solid brown;padding:3px;">
<div class="title_welcome_box">Status seminggu akhir</div><br/> <img src="index.php?com=graph&task=bar_daily"   />
</div>';
*/ 
$view = '<div style="width:930px;text-align:center;">';
$view .= '<div style="width: 470px;padding:2px; height: 320px;text-align:left;float:left;">'.$design2.'</div> ';
$view .= '<div style="width: 460px;padding:2px; height: 320px;text-align:center;float:right;">'.$design3.'</div> ';
$view .= '<div style="clear:both"></div><br/>';
//$view .= '<div style="width: 460px;padding:2px; height: 380px;text-align:left;float:left;">'.$design4.'</div> '; 
//$view .= '<div style="width: 460px;padding:2px; height: 380px;text-align:left;float:right;">'.$design5.'</div> '; 
//$view .= '<div style="clear:both"></div>';
$view .= '</div>';

return $view;
} 

function data_sumary(){
 $aaaa = '<table width="100%" cellpadding="3" cellspacing="0" border="0">';
//$path = '../files/services/resume.json';  
//$contents = (string) file_get_contents($path);  
//json_decode($contents ,true);
$datas = get_data_karyawan();

$karyawan_tetap = count($datas['status_karyawan']['1']); 
$persen = (   round($karyawan_tetap / $datas['total_karyawan']  * 100 ) )  ;

$aaaa .= detail_rows_view( '<span style="font-size:11px">Karyawan pria / wanita</span>' ,  ' <font size="1">'.count($datas['kelamin']['Laki-laki']) .' / '.count($datas['kelamin']['Perempuan']) .'  </font>' ,false , "55%", "45%");
$aaaa .= detail_rows_view( '<span style="font-size:11px">Karyawan tetap</span>' , ' <font size="1">'.$persen.' % &nbsp; ('. $karyawan_tetap.' Orang)</font>'    , false , "55%", "45%");
$aaaa .= detail_rows_view( '<span style="font-size:11px">Kary. usia dibawah 25 tahun</span>' , ' <font size="1">'.get_umur_between( 15 , 25 ).' Orang</font>'    , false , "55%", "45%");
$aaaa .= detail_rows_view( '<span style="font-size:11px">Kary. usia antara 26 hingga 34 tahun</span>' , ' <font size="1">'.get_umur_between( 26 , 34 ).' Orang</font>'    , false , "55%", "45%");
$aaaa .= detail_rows_view( '<span style="font-size:11px">Kary. usia antara 35 hingga 42 tahun</span>' , ' <font size="1">'.get_umur_between( 35 , 42 ).' Orang</font>'    , false , "55%", "45%");
$aaaa .= detail_rows_view( '<span style="font-size:11px">Kary. usia diatas 43 tahun</span>' , ' <font size="1">'.get_umur_between( 43 , 60 ) .'  Orang</font>'    , false , "55%", "45%");
$aaaa .= detail_rows_view( '<span style="font-size:11px">Gaji range hingga 2.5 juta</span>' ,' <font size="1">'.(isset($datas['range_gaji']['1']) ? count($datas['range_gaji']['1']) : 0 ).' orang</font>'   ,false , "55%", "45%");
$aaaa .= detail_rows_view( '<span style="font-size:11px">Gaji range 2.5 hingga 5 juta</span>' ,
 ' <font size="1">'.(isset($datas['range_gaji']['2']) ? count($datas['range_gaji']['2']) : 0 ).' orang</font>' ,false , "55%", "45%");
$aaaa .= detail_rows_view( '<span style="font-size:11px">Gaji range 5 hingga 10 juta</span>' ,

	' <font size="1">'. (isset($datas['range_gaji']['3']) ? count($datas['range_gaji']['3']) : 0) .' orang</font>' ,
	false , "55%", "45%");
$aaaa .= detail_footer_view();

return $aaaa; 
}

function get_umur_between( $bawah , $atas ){
	$bawah = (int) $bawah;
	$atas = (int) $atas;

	$query = "SELECT 
		DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(tanggal_lahir)), '%Y')+ 0 AS age 
		FROM karyawan  
		WHERE DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(tanggal_lahir)), '%Y') >= {$bawah} 
		AND DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(tanggal_lahir)), '%Y') <= {$atas} ";
	$result = my_query($query);
	return my_num_rows($result);
}

function get_data_karyawan(){
	$query = "SELECT * FROM karyawan";
	$result = my_query($query);
	$agama = array();
	$status_karyawan = array();
	$range_gaji = array();
	$kelamin = array();
	$i = 0;
	while( $row = my_fetch_array($result) ){
		$i++;
		$agama[$row['karyawan_agama_id']][] = $row['karyawan_id'];
		$status_karyawan[$row['karyawan_status_id']][] = $row['karyawan_id'];
		
		$range_id = get_range_by_nominal($row['basic_salary']);
		$range_gaji[$range_id][] = $row['karyawan_id'];
		
		$kelamin[$row['kelamin']][] = $row['karyawan_id'];
	}
	$datas = array();
	$datas['total_karyawan'] = $i;
	$datas['agama'] = $agama;
	$datas['status_karyawan'] = $status_karyawan;
	$datas['range_gaji'] = $range_gaji;
	$datas['kelamin'] = $kelamin;
	return $datas;
}

function get_range_by_nominal( $nominal ){
	if($nominal <= 2500000){
		return 1;
	}elseif($nominal <= 5000000){
		return 2;
	}elseif($nominal <= 10000000){
		return 3;
	}elseif($nominal <= 18000000){
		return 4;
	}else{
		return 5;
	} 
}

function display_caledar(){
	$objCalendar = new Calendar();
	 $objCalendar->SetCalendarDimensions("480px", "315px");
    ## *** set week day name length - "short" or "long"
    $objCalendar->SetWeekDayNameLength("long");
    ## *** set start day of week: from 1 (Sanday) to 7 (Saturday)
    $objCalendar->SetWeekStartedDay("1");
    ## *** set calendar caption 

    ## +---------------------------------------------------------------------------+
    ## | 3. Draw Calendar:                                                         | 
    ## +---------------------------------------------------------------------------+
    
   return  $objCalendar->Show();
}

    