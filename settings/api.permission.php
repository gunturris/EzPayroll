<?php

//SET DIRECT PAGE TO ACCOUNT CANNOT ACCESS
define('PERMISSION_DENIED_PAGE' , 'index.php?com=user&access=denied');

/*
	CHECK ACCESS
*/
function permission_access(  $level_id  ){

	if( ! logined() ){
		permission_denied();
	}

	if( ! role_access( $_SESSION['user_id'], $level_id ) ){
		permission_denied();
	}
}


/*
	GO TO PAGE DENIED ACCESS
*/
function permission_denied(){
	header("Location: " . PERMISSION_DENIED_PAGE );
	exit();
}

/*
	ACCOUNT IS LOGINED
*/

function logined(){
	return isset( $_SESSION['user_id'] ) ? (int) $_SESSION['user_id']  : false ;
}

function logined_access( $level_access_id ){

	if( 1 == logined_level() ) return true;

	$query = "SELECT `level_id` FROM user WHERE user_id = ". $_SESSION['user_id'] ;
	$result = my_query($query);
	
	if( $row = my_fetch_array( $result ) ){
		if( $row['level_id']  == $level_access_id ){
			return true;
		}
		permission_denied();
	}
	
	permission_denied();
}

function logined_level(){

	if(! isset($_SESSION['user_id']) ) return 0;

	$query = "SELECT `level_id` FROM user WHERE user_id = ". $_SESSION['user_id'] ;
	$result = my_query($query);
	
	if( $row = my_fetch_array( $result ) ){
		return $row['level_id'];
	}
	return false;
}

function user_access($user_id , $flag = true ){
	if(! isset($_SESSION['user_id']) ){
		if(! $flag){
			return false;
		}	
		return permission_denied();
	
	}
	if( logined_level() == 1 )return true;
	if( $user_id == $_SESSION['user_id'] ) return true;
	
		if(! $flag){
			return false;
		}
	
	return permission_denied();
	
}