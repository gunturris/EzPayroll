<?php 

function list_kalender($startdate , $enddate  ){ 
	list(   $year1 ,$month1 ,$day1 ) = explode( "-" , $startdate );
	$day1 = $day1 +1;
	list($year2 ,$month2 ,$day2) = explode( "-" , $enddate );
 
  
	$date = mktime(0,0,0,$month1,$day1,$year1); //Gets Unix timestamp START DATE
	$date1 = mktime(0,0,0,$month2,$day2,$year2); //Gets Unix timestamp END DATE
	$difference = $date1-$date; //Calcuates Difference
	$daysago = ceil($difference /60/60/24); //Calculates Days Old	
	 
	$i = 0;
 	$dates = array();
	while ($i <= $daysago +1) {
		if ($i != 0) { $date = $date + 86400; }
		else { $date = $date - 86400; }
		$today = date('Y-m-d',$date);

		$yy = date('Y',$date);
		$mm = date('m',$date);
		$dd = date('d',$date);
		 
		$dates[]= $yy.'-'.$mm.'-'.$dd ;
		$i++;
	}
	
	return $dates;
}

function week_start_date($wk_num  , $yr  , $first = 1, $format = 'F d, Y')
{
    $wk_ts  = strtotime('+' . $wk_num . ' weeks', strtotime($yr . '0101'));
    $mon_ts = strtotime('-' . date('w', $wk_ts) + $first . ' days', $wk_ts);
    return date($format, $mon_ts);
}

function jumlah_hari_not_weekend($startdate , $enddate  ){ 
	list(   $year1 ,$month1 ,$day1 ) = explode( "-" , $startdate );
	$day1 = $day1 +1;
	list($year2 ,$month2 ,$day2) = explode( "-" , $enddate );

 
	$start_date = "$year1-$month1-$day1";
	$view =$v."Start Date = $start_date ";
	$end_date = "$year2-$month2-$day2";
	$view .= "End Date = $end_date <br>";
 
	$date = mktime(0,0,0,$month1,$day1,$year1); //Gets Unix timestamp START DATE
	$date1 = mktime(0,0,0,$month2,$day2,$year2); //Gets Unix timestamp END DATE
	$difference = $date1-$date; //Calcuates Difference
	$daysago = ceil($difference /60/60/24); //Calculates Days Old	
	$jumlah_hari=0;
	$i = 0;
 	$dates = array();
	while ($i <= $daysago +1) {

	
		if ($i != 0) { $date = $date + 86400; }
		else { $date = $date - 86400; }
		

		$yy = date('Y',$date);
		$mm = date('m',$date);
		$dd = date('d',$date);
		if(date('D',strtotime($yy.'-'.$mm.'-'.$dd))=='Sun'){
		}elseif(date('D',strtotime($yy.'-'.$mm.'-'.$dd))=='Sat'){
		}else{
			$jumlah_hari++ ;
		}		
		$i++;

	}
	//$jumlah_hari-=count_hari_libur($startdate , $enddate  );
	return $jumlah_hari;
}


function get_last_date_by_month($year,$month){
	if(checkdate($month, 31, $year))return $year.'-'.sprintf( '%02d',$month).'-31';
	if(checkdate($month, 30, $year))return $year.'-'.sprintf( '%02d',$month).'-30';
	if(checkdate($month, 29, $year))return $year.'-'.sprintf( '%02d',$month).'-29';
	if(checkdate($month, 28, $year))return $year.'-'.sprintf( '%02d',$month).'-28';
	return false;
}

function count_hari_libur($startdate , $enddate  ){ 
	$query  = "SELECT DISTINCT tanggal FROM a_hari_libur 
	WHERE tanggal BETWEEN '".$startdate."' AND '".$enddate."' GROUP BY tanggal"; 
	$res = my_query($query);
	$jumlah_hari=0;
	while($ey = my_fetch_array($res)){
		$date=$ey['tanggal'];
		$yy = date('Y',$date);
		$mm = date('m',$date);
		$dd = date('d',$date);
		if(date('D',strtotime($yy.'-'.$mm.'-'.$dd))=='Sun'){
		}elseif(date('D',strtotime($yy.'-'.$mm.'-'.$dd))=='Sat'){
		}else{
			$jumlah_hari++ ;
		}
	}
	return $jumlah_hari ;	

}
