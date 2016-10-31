<?php

if( class_exists('mysqli') ):
	/* EXECUTE QUERY */
	function my_query( $query ){
		global $connection;  
		$check = $connection->query($query);
		  
		if(! $check){
			fatal_error('Query mySQL tidak benar! : '.$query);
		}	
		return  $check ;
	}

	/* AMBIL HASIL RESULT  QUERY */
	function my_result($query){
		$objQuery = my_query($query);
		$row = $objQuery->fetch_array(MYSQLI_ASSOC);  
		return $row[0]; 
	}
	
	function my_num_rows( &$objQuery ){
		return $objQuery->num_rows;
	}

	function my_fetch_object( &$objQuery ){ 
		return $objQuery->fetch_object(); 
	}

	function my_fetch_array( &$objQuery ){  
		return $objQuery->fetch_array(MYSQLI_ASSOC); 
	}
	function my_fetch_row(  $objQuery ){  
		return $objQuery->fetch_row( ); 
	}
	function my_result_close( &$objQuery ){
		return $objQuery->close();
	}

	/* AMBIL RESPON DATABASE */
	function my_affected_rows( ){ 
		global $connection; 
		return  $connection->affected_rows();  
		
	}

	/* CLOSE DATABASE */
	function my_close( ){
		global $connection;
		return $connection->close();
	}
else:
	/* EXECUTE QUERY */
	function my_query( $query ){
		global $connection;  
		$check = mysql_query($query); 
		if(! $check){
			fatal_error('Query mySQL tidak benar! : '.$query);
		}	
		return  $check ;
	}

	/* AMBIL HASIL RESULT  QUERY */
	function my_num_rows(  $objQuery ){
		return mysql_num_rows( $objQuery );
	}

	function my_result( $query){
		$objQuery = my_query($query);
		$row = my_fetch_array(  $objQuery );  
		return $row[0]; 
	}
	
	function my_fetch_object(  $objQuery ){ 
		return mysql_fetch_object(); 
	}

	function my_fetch_array(  $objQuery ){  
		return mysql_fetch_array( $objQuery ); 
	}

	function my_fetch_row(  $objQuery ){  
		return mysql_fetch_row( $objQuery ); 
	}
	function my_result_close(  $objQuery ){
		return mysql_free_result($objQuery);
	}

	/* AMBIL RESPON DATABASE */
	function my_affected_rows( ){ 
		global $connection; 
		return  mysql_affected_rows($connection);  
		
	}

	/* CLOSE DATABASE */
	function my_close( ){
		global $connection;
		return mysql_close( $connection );
	}
endif;

/* Get table fields list*/
function my_get_field_list( $table_name ){
	
	$query = " SHOW fields FROM `".$table_name."` ";
	$result = my_query( $query );
	 
	while($row = my_fetch_array(  $result ) ){
		$data  = $row['Field'];
		break;
	}
	
	return $data;
	
}

/* 
INSERTING DATA  
Depend my_type_data_str(), my_type_data_function (), my_type_data_int 
Cara ,udah untuk menyimpan record;

SAMPLE:

$table_name = 'siswa'

$datas = array();
$datas['umur'] =my_type_data_str('12');
$datas['kelamin'] =my_type_data_str('Perempuan');
$datas['jurusan'] =my_type_data_str('IPS');
$datas['masuk'] = my_type_data_function('NOW()') 
*/
function my_insert_record( $table_name , $datas ){
 
	global $connection;

	$build_query = " INSERT INTO `".$table_name ."` SET "  ;
	foreach( $datas as $field => $value ){
	
		$build_query .= "`".$field ."` = ".  $value  . ", ";
	
	}
	
	$insert_query = rtrim( trim($build_query) , "," ); 
 
	if( my_query( $insert_query )  ){
		return $connection->insert_id; 
	}
	return false;

}

function my_type_data_str($val){
	return "'".trim( addslashes($val))."'";
}

function my_type_data_function($val){
	return trim( addslashes($val));
}

function my_type_data_int($val){
	$val = (int) $val;
	return trim( addslashes($val));
}

/*
	DELETE DATA ON TABLE
*/

function my_delete_record( $table_name , $field_key , $data_id){
	global $connection;	
	
	$query = "DELETE FROM `".$table_name."` WHERE `".$field_key ."` = ". $data_id ;
	$result = my_query( $query );
	
	return $connection->affected_rows ;
}
 
/*
 
Depend my_type_data_str(), my_type_data_function (), my_type_data_int 
Cara ,udah untuk menyimpan record;

SAMPLE:

$table_name = 'siswa'
$field_key = 'siswa_id'
$data_id = 12

$datas = array();
$datas['umur'] =my_type_data_str('12');
$datas['kelamin'] =my_type_data_str('Perempuan');
$datas['jurusan'] =my_type_data_str('IPS');
$datas['masuk'] = my_type_data_function('NOW()') 
*/
function my_update_record( $table_name , $primary_key , $data_id , $datas ){

	global $connection;

	$build_query = " UPDATE `".$table_name ."` SET "  ;
	foreach( $datas as $field => $value ){
	
		$build_query .= "`".$field ."` = ".  $value  . ", ";
	
	}
	
	$query = rtrim( trim($build_query) , "," );
	
	$update_query = $query ." WHERE `".$primary_key."` = ". $data_id ; 
 
	$result = my_query( $update_query );
	 
	return $connection->affected_rows ; 

}


/* 
	GET DATA BY DATAS
	
	$table_name = 'siswa';
	
	$datas['siswa_id'] = my_type_data_int('2');
	
*/
function my_browse_data( $table_name , $datas ){
	
	global $connection;
	
	$primary_field = my_get_field_list($table_name);
	foreach($datas as $field => $value){
		$fieldname = $field ;
		$fieldvalue =  $value;
	}
	
	$query = " SELECT `".$primary_field. "` FROM `".$table_name."` WHERE `".$fieldname."` = ". $fieldvalue  ;
	 
	$result = my_query( $query );
	if( my_num_rows($result) > 0 ){
		
		$data = array();
		
		while(	$row = my_fetch_array($result) ){
			$data[] = $row[$primary_field];
		}
		
		return $data;
	
	}
	
	return false;
	
}

/*
	GET_DATA_BY_PRIMARY
*/
function my_get_data_by_id( $table_name , $primary_field , $id ){
	
	global $connection; 
	
	$query = " SELECT * FROM `".$table_name."` WHERE `".$primary_field."` = ". $id  ;
	 
	$result = my_query( $query );
	if( my_num_rows($result) > 0 ){ 
		return my_fetch_array($result); 
	}
	
	return false;
	
}

function my_get_result_query($query){
	$result = my_query($query);
	if($row = my_fetch_row($result)){
		return $row[0];
	}
	return false;
}

function get_data_revisi($table , $primary_fields , $id){
	$query = "SELECT revisi FROM {$table} WHERE {$primary_fields} = {$id}";
	$result = my_query($query);
	if( my_num_rows($result) > 0){
		$row = my_fetch_array($result);
		return $row['revisi'];
	}
	return 1;
}