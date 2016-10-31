<?php
/*
if( ! is_dir( MY_FILES_PATH ) ){
	mk_dir(MY_FILES_PATH , 0755 , true);
}

*/

ini_set('allow_call_time_pass_reference',"0");
session_start(); 
require_once("../config.php");

function fatal_error( $msg  ){
 
	$error = array($msg);
	$content = message_multi_error($error);
	generate_my_web( $content  ,"FATAL ERROR" , "index.php");
	exit;

}

function my_link_control( $param , $value , $show = false ){
	$replace = false;
	$link = '?';  
	if($show){
			$link .=  $param .'='.$value .'&';
	}else{
		foreach($_GET as $params => $values ){
			if($params == $param ){
				$values = $value;
				$replace = true;
				
			}  
			$link .=  $params.'='.$values.'&';
		 }	
		 if(!$replace){ $link .= $param.'='.$value; }
	}
		
	return rtrim( $link , '&');
}

function my_href($com , $page="" , $task="", $params="" ){
	
	if(!my_is_component( $com ))return NULL;
	if($page == "")$page="index.php";
	
	$notation = "";
	if( is_array($params)){
		
		
		foreach($params as $key=>$value){
			$notation .= "&".$key."=".$value;	
		}
	}
	
	return $page."?com=".$com."&task=".$task.$notation; 
}
 


function get_api_file_list( $folder ){
	
	$PATH = MY_ROOT_PATH . $folder;
 
	if( ! is_dir( $PATH ) ){
		fatal_error( 'Folder core '.$folder.' tidak ditemukan!' );
	}
	
	if ($handle = opendir( $PATH )) {
		
		$files = array();
	    /* This is the correct way to loop over the directory. */
	    while (false !== ($file = readdir($handle))) { 
	         if( is_file( $PATH .'/'. $file ) and ( end(explode('.' , $file )) ) == 'php'  )$files[] = $folder.'/'.$file; 
	    }
		
		closedir($handle); 
		return $files;
	}
	
	fatal_error( 'File dalam folder core '.$folder.' tidak dapat di ambil!' ); 

}

 

if( class_exists("mysqli") ){ 
	$connection = new mysqli(DATABASE_HOST ,DATABASE_USER , DATABASE_PASSWORD, DATABASE_NAME);
	if( mysqli_connect_errno() ){
		fatal_error( 'Gagal koneksi ke database : '. mysqli_connect_error()    );
	}
}else{
	$connection = mysql_connect(DATABASE_HOST ,DATABASE_USER , DATABASE_PASSWORD);
	mysql_select_db(DATABASE_NAME);
	if( mysql_errno($connection)  ){   
		fatal_error( mysql_errno($connection) . ": " . mysql_error($connection)    );
	}
}	
 

function my_api_load(){
	 
	$sets = get_api_file_list('settings');
	foreach( $sets as $filename ){
		if($filename != "setting.php")
		require_once( MY_ROOT_PATH . $filename );
	}
}

/* COMPONENT LOAD */
function my_component_load( $component_name , $run = true , $files = array() ){

	$component_path = MY_COMPONENT_PATH . $component_name;

	if( ! is_dir($component_path) ){
		fatal_error( 'Tidak ditemukan component ' . $component_name ); 
	}
	
	if(! is_file( $component_path ."/".$component_name.".php") ){
		fatal_error( 'Tidak ditemukan component ' . $component_name );
	}
	
	
	
	if( file_exists( $component_path ."/class.".$component_name.".php" ) ){
		require_once( $component_path ."/class.".$component_name.".php" );
	}
	
	if( file_exists( $component_path ."/html.".$component_name.".php" ) ){
		require_once( $component_path ."/html.".$component_name.".php" );
	}

	if( file_exists( $component_path ."/common.".$component_name.".php" ) ){
		require_once( $component_path ."/common.".$component_name.".php" );
	}
	
	foreach( $files as $file ){
		if( file_exists( $component_path ."/". $file ) ){
			require_once( $component_path ."/". $file );
		}else{
			fatal_error( 'Tidak ditemukan file ' . $file . ' pada components ' . $component_name  );
		}
	}
	if($run)
	require_once($component_path ."/".$component_name.".php");

	
	return true;
	
	 
}
/* TEMPLATE PATERN LOAD */
define("MYLOADSYS" , 1 );
function my_patern_load( $filename , $component = false ){

	if($component)
		$PATH_TEMPLATE = MY_COMPONENT_PATH . $filename;
	else
		$PATH_TEMPLATE = MY_TEMPLATE_PATH . $filename;


	if( ! is_file( $PATH_TEMPLATE ) ) fatal_error( 'Template patern tidak ditemukan!' );
	
	$lext = end( explode( "." , $filename ));
	if( $lext != 'html'   ) fatal_error( 'Template patern bukan HTML!' );
	
	$view = file_get_contents( $PATH_TEMPLATE ); 
	return $view; 
}

/* SET BLOCK */
function setblocks($blockname){
	
	 $template_location = PATH_TEMPLATES . __TEMPLATE_NAME__.  '/blocks/'.$blockname.'.php';

	 if(! file_exists( $template_location ) ) return false;
	 require($template_location);

}
/* TEST ARRAY RESULT*/
function test_array_result($datas){
	if(! is_array($datas) ) return 'Data <b>'.$datas.'</b> bukan array' ;
	return '<pre>'.print_r($_POST , true).'</pre>';
}
/* SHOW WEBSITE */
function generate_my_web(   $content , $sidebar , $template = "index.php" , $module= false ){
	 
if( $module && my_is_component( $module ) )	{
	$template_path = MY_COMPONENT_PATH . $module . "/". $template;
}else{	
	$template_path = PATH_TEMPLATES . __TEMPLATE_NAME__.  "/". $template;
}
if(! file_exists($template_path) ){
	fatal_error('TIdak dapat memuat template <br/>[PATH: '.$template_path.']');
}	
	global $js_code,$js_jquery_code, $css_code, $css_file, $js_file;
	 
	include_once( $template_path );
	
}

 

function my_template_position(){
	return my_http_host().  '/templates/'. __TEMPLATE_NAME__;
}

function my_is_component( $component_name ){
	$component_control = MY_COMPONENT_PATH . $component_name . '/'.$component_name.'.php';
	 
	if( file_exists($component_control) ) return true;
	return false;
}
 
/*RUN COMPONENT */
function my_exec( $component_name ){
	$component_control = MY_COMPONENT_PATH . $component_name . '/'.$component_name.'.php';
	if(! is_file($component_control) ){
		fatal_error( 'File control '.$component_name.' tidak dapat di ambil!' );
	}
	include($component_control);
}

function my_shutdown(){
	global $connection; 
	unset($_SESSION);
	unset($_POST);
	session_regenerate_id();
}

function my_callback( $strtoreplace , $template ){
	$newtemplate = $template;
	if(! is_array( $strtoreplace ) ) return false;
	foreach( $strtoreplace as $patern => $newstr ){
		$str = "[<".  strtoupper( $patern )  .">]";
		$newtemplate = str_replace( $str , $newstr , $newtemplate );
	}	
	
	return $newtemplate;
}

/* GET COMPONENT HOST */
function my_http_host(){
	$webhost = $_SERVER['HTTP_HOST'];
	return "http://".$webhost;
}

/* LOAD JS CSS */
function my_set_file_js($files){
global $js_file;
	if(! is_array($files) ) return "";
	$file = "";
	foreach($files as $eachfile){
		$file .= '<script src="'.$eachfile.'" type="text/javascript"></script>'."\n";
	}
	if(isset($js_file))$js_file .=  $file  ;
	else $js_file = $file;
	if(!defined('JS_LIST'))define('JS_LIST' , $file );
}

function my_set_code_js($code){
global $js_code;
	if(isset($js_code))$js_code .= $code ;
	else $js_code=$code;
	if(!defined('JS_CODE'))define('JS_CODE' , $code );
	return $js_code;
}

function my_set_file_css($files){
global $css_file;
if(! is_array($files) ) return "";
	$file = "";
	foreach($files as $eachfile){
		$file .= '<link rel="stylesheet" href="'.$eachfile.'" />'."\n";
	}
	if(isset($css_file))$css_file .=  $file  ;
	else $css_file = $file;
	if(!defined('CSS_LIST'))define('CSS_LIST' , $file );
	
	 
}

function my_set_code_css($code){
	global $css_code;
	if(isset($css_code))$css_code  .= $code   ;
	else $css_code=$code;
	if(!defined('CSS_CODE'))define('CSS_CODE' , $code );
	 
}


function my_session_id(){
	return my_token();
}

/* DATE DEFAULT FORMAT BY MySQL DATETIME FORMAT */
function my_date($datetime , $format = "j-m-Y H:i" ){
	return date( $format , strtotime( $datetime ) ); 
}

/* BUILD token data*/
function my_token(){
	
	if(! isset ($_SESSION['control'])){
		$_SESSION['control'] = md5( rand( 0 , 1000 ) );
	}
	
	return md5( session_id() . $_SESSION['control'] .   $_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT'] );

}


function generate_facebox_template($content ,$sidebar=false , $template = "facebox.php" , $module= false){
	if( $module && my_is_component( $module ) )	{
		$template_path = MY_COMPONENT_PATH . $module . "/". $template;
	}else{	
		$template_path = PATH_TEMPLATES . __TEMPLATE_NAME__.  "/". $template;
	}
	if(! file_exists($template_path) ){
		fatal_error('TIdak dapat memuat template <br/>[PATH: '.$template_path.']');
	}	
		global $js_code, $css_code, $css_file, $js_file;
		include( $template_path );
		exit;
}

function facebox_page($content , $title =false , $iframe = 0){
 	$view ="";
 	 
	if($title)$view = '<div id="facebox_title_box"><b>'.$title.'</b></div>';
	$view .= '<div id="facebox_content_box">';
	if($iframe > 0)
		$view .= '<IFRAME src="'.$content.'" MARGINWIDTH="0"  MARGINHEIGHT="0" HSPACE="0" VSPACE="0" FRAMEBORDER="0" SCROLLING=AUTO WIDTH="680" HEIGHT="'.$iframe.'">test</IFRAME> '; 
	else
		$view .= $content;
	$view .='</div>';
	
	echo $view;
	exit(0);
}

function my_direct( $page  ){
	header("Location: ". $page ); 
	exit(0);
}


function my_get_path_files(){
	return MY_FILES_PATH;
}
 
my_api_load(); 
define( "my_load" , my_token() ); 
my_component_load('__system'); 
my_component_load('__viewapi');
