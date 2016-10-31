<?php

/*
	FILE untuk menuliskan hasil return message untuk error ataupun sukses
*/

function error_no($message , $flag = true ){
	$text = "<span style='color: red; font-size: 12px; font-family: verdana;'>".$message."</span>";
	if($flag){
		echo $text ;
	}
	return $text ;

}

function message_error($message){

return '<div style="color:red;border:1px solid red;padding:4px" class="error-line">' . $message . '</div><br/>';
}

function message_multi_error($messages){

	if(! is_array($messages) ) return false;
	$show = '
		<div class="alert alert-danger">
			 <p>';
			foreach( $messages as $message ): 	
			$show .= '<i class="fa fa-times-circle fa-fw"></i> '.$message .'<br/></p>';
 			 endforeach; 
	$show .= '</div>';
	 	
	return 	 	$show ;		

}


function message_correct($message){
return '<div   class="alert alert-success"><i class="fa fa-check-square fa-fw"></i> ' . $message . '</div> ';

}

function new_session_control(){
	session_regenerate_id();
	$newCode = md5(rand(0,100000000));
	$_SESSION['control'] = md5(sha1($newCode));

return true;
}	
 

function valid_username($username){
	if( strlen(trim($username)) < 4  ) return (-1);
	if( strlen(trim($username)) > 12 ) return (-2);
	$allowed = "/[a-zA-Z0-9]/i" ;
	return preg_match(  $allowed , $username );
}

/*
*	CHECK EMAIL ADDRESS
*	$mail = Email address
*/
function valid_email_address($mail) {
  $user = '[a-zA-Z0-9_\-\.\+\^!#\$%&*+\/\=\?\`\|\{\}~\']+';
  $domain = '(?:(?:[a-zA-Z0-9]|[a-zA-Z0-9][a-zA-Z0-9\-]*[a-zA-Z0-9])\.?)+';
  $ipv4 = '[0-9]{1,3}(\.[0-9]{1,3}){3}';
  $ipv6 = '[0-9a-fA-F]{1,4}(\:[0-9a-fA-F]{1,4}){7}';

  return preg_match("/^$user@($domain|(\[($ipv4|$ipv6)\]))$/", $mail);
} 

/*
*	CHECK URL
*	$url = URL address
*/
function valid_url($url, $absolute = FALSE) {
  $allowed_characters = '[a-z0-9\/:_\-_\.\?\$,;~=#&%\+]';
  if ($absolute) {
    return preg_match("/^(http|https|ftp):\/\/". $allowed_characters ."+$/i", $url);
  }
  else {
    return preg_match("/^". $allowed_characters ."+$/i", $url);
  }
}

/*
*	CHECK Name
*	$name = name
*/
function valid_name($name, $maxLength = 50)
{
	if (trim ($name) == '') {
		return false;
	}
	else {
		return (  strlen ($name) <= $maxLength );
	}
}

/*
*	Valid comment
*	$name = name
*/
function valid_comment($textString)
{
	if (trim ($textString) == '') {
		return -1;
	}
	else {
		return preg_match ('/^[-+\/\w\s\'\\\\"&%!?\.,*;:`]+$/i', $textString);
	}
}
/*
	CHECK PASSWORD
*/
function check_password( $password ){

	if( strlen(trim($password)) < 3  ) return (-1);
	if( strlen(trim($password)) > 25 ) return (-2);
	$allowed = "/[a-zA-Z0-9]/i";
	return preg_match(  $allowed , $password );

}

/*
*	CHECK FILENAME, Replace strange char with _ ( underline ) 
*/
function verify_filename($filename){
  $allowed = "/[^a-z0-9\\.\\-\\_]/i";
  return  preg_replace($allowed,"_",$filename );	  
}

// bersih-bersih input
function clean_input($string){
	return trim(addslashes($string));
}
 

/*File size by folder */
function folder_file_info( $folder_id ){
	
	$cekfolerbyid = " SELECT dokumen_id FROM dokumen WHERE folder_id =". $folder_id ;
	$result = my_query( $query );
	
	$data = aray();
	$data['filesize'] = 0;
	$data['filecount'] = 0;
	while( $row = my_fetch_array($result) ){
	
		$datas = dokumen_file_info( $row['dokumen_id'] );
		
		$filecount[] = $datas['filecount'];
		$filesize[] = $datas['filesize'];
	}
	
	$data['filesize'] = array_sum( $filesize  ) ;
	$data['filecount'] = array_sum( $filecount  );
	
	return $data;
}

function button_top($buttons){
 
	if(! is_array($buttons)) return false;
	
	$view = '<div align="right"><ul class="button_top">';
	foreach($buttons as $button=>$pagelink){
	 	$properties = explode("|" , $pagelink);
		$view .='<li><a href="'.$properties[0].'" rel="facebox"><img border="0" src="'.$button.'" height="20"> '.$properties[1].'</a></li>';
	}
	$view .= '</ul></div>&nbsp;<br/>';
	return $view;
}


 

function display_name($name , $length=12){
	$not_allow=array(
		'prof.','prof','dr',
		'dr.','mr','mr.','bpk',
		'bpk.','ibu','ibu.',
		'haji','ir','ir.'
	);
	
	
	
	$length_real = strlen($name);
	if($length_real < $length ) return $name;
	$names = explode(" ",$name);
	if(! in_array($names[0],$not_allow)){
		if(strlen($names[0]) < $length)return $names[0] .'<a title="'.$name.'" href="javascript:void;">...</a>'; 
		$text = substr($names[0] , 0, $length).'...';
		return $text;
	}else{
		if(strlen($names[1]) < $length)return $names[1] .'<a title="'.$name.'" href="javascript:void;">...</a>'; 
		$text = substr($names[1] , 0, $length).'...';
		return $text;
	}
}
function recurse_copy($src,$dst) {
    $dir = opendir($src);
    @mkdir($dst);
    while(false !== ( $file = readdir($dir)) ) {
        if (( $file != '.' ) && ( $file != '..' )) {
            if ( is_dir($src . '/' . $file) ) {
                recurse_copy($src . '/' . $file,$dst . '/' . $file);
            }
            else {
                copy($src . '/' . $file,$dst . '/' . $file);
            }
        }
    }
    closedir($dir);
} 


function recursive_delete($str){
	if(is_file($str)){
		return @unlink($str);
	}
	elseif(is_dir($str)){
		$scan = glob(rtrim($str,'/').'/*');
		foreach($scan as $index=>$path){
			recursiveDelete($path);
		}
		return @rmdir($str);
	}
}

function getage($date) {
	list(	$bYear, $bMonth, $bDay 	) = explode("-",$date);
	$cMonth = date('n');
	$cDay = date('j');
	$cYear = date('Y');

	if(($cMonth >= $bMonth && $cDay >= $bDay) || ($cMonth > $bMonth)) {
		return ($cYear - $bYear);
	} else {
		return ($cYear - $bYear - 1);
	}
}

function get_age($date) {
	list(	$bYear, $bMonth, $bDay 	) = explode("-",$date);
	$cMonth = date('n');
	$cDay = date('j');
	$cYear = date('Y');

	if(($cMonth >= $bMonth && $cDay >= $bDay) || ($cMonth > $bMonth)) {
		return ($cYear - $bYear);
	} else {
		return ($cYear - $bYear - 1);
	}
}

function get_hari_by_date($date){
	$hari = array(
		'0'=>'minggu',
		'1'=>'senin',
		'2'=>'selasa',
		'3'=>'rabu',
		'4'=>'kamis',
		'5'=>'jumat',
		'6'=>'sabtu' 
	);
	$d = date( 'w', strtotime( $date ) );
	return $hari[$d];
}

function get_value($label){
	$uquery = "SELECT setvalue FROM applicatin_set WHERE setname='{$label}'";
	$result = my_query($uquery);
	if($row = my_fetch_array($result));
	
	return $row['setvalue'];
	
	return false;
}

function set_value($label , $value){ 
	$query = "UPDATE applicatin_set SET setvalue='{$value}' WHERE setname ='{$label}'";
	return my_query($query);
}

function generate_code($len  , $table , $id = ID_SITE ){
	if($len > 10)$len = 10;
	
	$randnum = rand(1000,9999);
	$code = md5($randnum);
	$code = strtoupper($code);
	$start = rand(0,6); 
	$new_code = $id.substr( $code, $start , $len  );
	return generate_check_code($table ,$new_code); 
}

function generate_check_code($table , $code){

	$query = "SELECT * FROM {$table} WHERE system_code = '{$code}'";
	$result = my_query($query);
		if( my_num_rows($result) > 0){
			return generate_check_code($table , $code);
		}
	 
	return $code;
}

function get_current_periode(){
	$query = "SELECT periode_id FROM global_periode ORDER BY periode_id DESC LIMIT 1";
	$result = my_query($query);
	$row = my_fetch_array($result);
	return $row['periode_id'];
}	
 
function loaddata_karyawan($id){
	$path = '../files/services/karyawan_'.$id.'.json'; 
	if(!file_exists($path))return loaddata_karyawan_start($id);
	$contents = (string) file_get_contents($path); 
	$datas = json_decode($contents ,true); 
	return  $datas ;
}

function loaddata_karyawan_start($karyawan_id){
	$datas = my_get_data_by_id('karyawan','karyawan_id',$karyawan_id);
	return $datas;
}


function set_karyawan_json($id){
	$path = '../files/services/karyawan_'.$id.'.json';
	if(file_exists($path))unlink($path);
	 
	if (!$handle = fopen(	$path, 'a'	)) {
		 return false;
	}
	 
	$datas 	= loaddata_karyawan_start($id);
	$res 	= json_encode($datas);

	if (fwrite($handle, $res) === FALSE) {
		return false;
	}
	fclose($handle); 
	return $res;
}

function start_new_services(){
	$query = "SELECT karyawan_id FROM karyawan";
	$result = my_query($query);
	while( $row = my_fetch_array($result) ){
		set_karyawan_json($row['karyawan_id']);
		$i++;
	}
	return $i;
}


function interpreter_symbol($code){
	$code = strtoupper($code);
	$codes = array(
		'[GAPOK]' , '[KERJA]'
	);
	
	
}