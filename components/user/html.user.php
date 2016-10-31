<?php

function userlist(){
	$header = array(
		'#'=>array('style'=>'text-align:center;width:5%'),    
		'Username'=>array('style'=>'text-align:left;width:35%'), 
		'Groupname'=>array('style'=>'text-align:left;width:25%'),  
		'Last login'=>array('style'=>'text-align:center;width:25%'), 
		'Operasi'=>array('style'=>'text-align:center;width:10%'), 
	);
	$query = " SELECT * FROM apps_user  ORDER BY user_id DESC ";
	$result = my_query($query );
	$row = array();
	$n=1;
	while( $ey = my_fetch_array($result) ){
		$level = array(
			'1'	=> ' Super administrator ',
			'2'	=> ' Administrator ',
			'3'	=> ' Standard ',
		);
		
		$editproperty = array(
						'href'=>'index.php?com=user&task=edit&id='.$ey['user_id'], 
						'title'=>'Edit'
				);
		$edit_button = button_icon( 'b_edit.png' , $editproperty  );

		$deleteproperty = array(
						'href'=>'javascript:; ',
						'onclick'=>'javascript:confirmDelete('.$ey['user_id'].');',
						'title'=>'Delete'
				);
		$delete_button = button_icon( 'cut_on.gif' , $deleteproperty  );
		
		$row[] = array(
			'#'=>position_text_align ($n, 'center'), 
			'username'=>$ey['username'],   	 
			'level'=> $level[$ey['level_id']] ,  
			'lastlogin'=> position_text_align($ey['last_login'],   'center'),
			'operasi'=> position_text_align(  $edit_button.' '.$delete_button , 'center'),  
		);
		
		$n++;
	}
	
	$datas = table_rows($row); 
	 
	$navigasi = array(
		'<input class="btn btn-primary" style="float:right;" type="button" value="Tambah data" onclick="javascript:location.href=\'index.php?com='.$_GET['com'].'&task=edit\'"/>',
	); 
	$box = header_box('Daftar pengguna aplikasi', $navigasi );
	return $box .table_builder($header , $datas , 5 ,false  );
}


function form_create_user($id){
	$view = form_header( "calon karyawan" , "cp"  );
	$fields = my_get_data_by_id( 'apps_user' , 'user_id' , $id );
	/*
	$nama = array(
		'name'=>'nama',
		'value'=>( isset($_POST['nama']) ? $_POST['nama'] : $fields['nama'] )  ,
		'id'=>'nama',
		'type'=>'text'
	 );
	$form_nama = form_dynamic($nama);
	$view .= form_field_display(  $form_nama   , "Nama"    ); */
	$username = array(
		'name'=>'username',
		'value'=>( isset($_POST['username']) ? $_POST['username'] : $fields['username'] )  ,
		'id'=>'username',
		'type'=>'text'
	 );
	$form_username = form_dynamic($username);
	$view .= form_field_display(  $form_username   , "Username"    ); 
	if($id == 0):
		$password = array(
			'name'=>'password',
			'value'=>( isset($_POST['password']) ? $_POST['password'] : '')  ,
			'id'=>'password',
			'type'=>'password'
		 );
		$form_passwro = form_dynamic($password);
		$view .= form_field_display(  $form_passwro   , "Password"    ); 
		$passwordb = array(
			'name'=>'passwordb',
			'value'=>( isset($_POST['passwordb']) ? $_POST['passwordb'] : '')  ,
			'id'=>'passwordb',
			'type'=>'password'
		 );
		$form_passwro = form_dynamic($passwordb);
		$view .= form_field_display(  $form_passwro   , "Ulang Password"    ); 
	endif;
	$levels =  array(
			'1'	=> ' Super administrator ',
			'2'	=> ' Administrator ',
			'3'	=> ' Standard ',
		);
		
	$level = array(
		'name'=>'level',
		'value'=>( isset($_POST['level']) ? $_POST['level'] : $fields['level_id']) ,
	);
	$form_level = form_radiobutton($level , $levels);
	$view .= form_field_display(  $form_level   , "Level"    ); 
	 
	$submit = array(
		'value' => ( $id ==0 ? '  S i m p a n ' :'  U p d a t e  '),
		'name' => 'simpan', 
		'type'=>'submit','class'=>'main_button'
	);
	$form_submit= form_dynamic($submit); 
	$view .= form_field_display( $form_submit   , '&nbsp;' ,  "" );
	$view .= form_footer( );
	return $view;
} 

function form_create_user_submit($id){
	if($id == 0):
		$datas = array( 
			'nama'=>my_type_data_str($_POST['nama']),
			'username'=>my_type_data_str($_POST['username']),
			'password'=>my_type_data_str( md5($_POST['password'].'@t') ),
			'level_id'=>my_type_data_int($_POST['level']),
			'formulir'=>my_type_data_str($_POST['formulir'] ),
			'datetime_added'=>my_type_data_function('NOW()'),
			'last_login'=>my_type_data_function('NOW()'),
		);
		return my_insert_record('user',$datas);
		
	else: 
		$datas = array( 
			'nama'=>my_type_data_str($_POST['nama']),
			'username'=>my_type_data_str($_POST['username']),
			'password'=>my_type_data_str( md5($_POST['password'].'@t') ),
			'level_id'=>my_type_data_int($_POST['level']),
			'formulir'=>my_type_data_str($_POST['formulir'] ),
			'datetime_added'=>my_type_data_function('NOW()'),
			'last_login'=>my_type_data_function('NOW()'),
		);
		return my_update_record('apps_user', 'user_id' , $id , $datas);
		
	endif;
	
}

function form_create_user_validate(){
	$errsubmit = false;
	$err = array();

	$username = trim( $_POST['username'] );
	$password =  trim( $_POST['password'] );
	$query ="SELECT * FROM apps_user WHERE username = '{$username}' ";
	$result =my_query($query);
	
	if(strlen($username) < 4 ){
		$errsubmit =true;
		$err[] = "Username berjumlah 5 hingga 12 karakter";
	}
	elseif(strlen($username) > 12 ){
		$errsubmit =true;
		$err[] = "Username berjumlah 5 hingga 12 karakter";
	}
	elseif( my_num_rows($result) > 0 ){
		$errsubmit =true;
		$err[] = "Username sudah terdaftar";
	
	}
	
	if($_POST['password'] !=  $_POST['passwordb'] ){
		$errsubmit =true;
		$err[] = "Password konfirmasi";
	}
	elseif(strlen($password) < 4 ){
		$errsubmit =true;
		$err[] = "Password minimal 4 karakter";
	}
	
	if($_POST['level'] == ''){
		$errsubmit =true;
		$err[] = "Level user belum di pilih";
	}
	
	if( $errsubmit){
		return $err;
	}
	
	return $errsubmit;	
}


function form_password_user_validate(){
	$fields =   my_get_data_by_id('apps_user','user_id', $_SESSION['user_id']);
	$oldpasswr = $fields['password'];
	$errsubmit = false;
	$err = array();	
	
	if($oldpasswr != md5($_POST['oldpassword'].'@t')){
		$errsubmit =true;
		$err[] = "Password lama tidak cocok";
	}
	
	if( strlen($_POST['password']) < 3){
		$errsubmit =true;
		$err[] = "Jumlah Karakter Password antara 5 sampai 20";
	}elseif(strlen($_POST['password']) > 20){
		$errsubmit =true;
		$err[] = "Jumlah Karakter Password antara 5 sampai 20";
	}
	 
	if($_POST['password'] != $_POST['passwordb']){
		$errsubmit =true;
		$err[] = "Password baru dan konfirmasi password tidak sama";
	}
	if( $errsubmit){
		return $err;
	}
	
	return $errsubmit;
}

function form_ganti_pasword(){
	$view = form_header( "ganti_password" , "cp"  ); 
	$user = my_get_data_by_id(	'apps_user',	'user_id',	$_SESSION['user_id']);
	$view .= form_field_display(  $user['username']   , "Username"    ); 
	$oldpassword = array(
		'name'	=>'oldpassword',
		'value'	=>( isset($_POST['oldpassword']) ? $_POST['oldpassword'] : '')  ,
		'id'	=>'oldpassword',
		'type'	=>'password'
	 );
	$form_passwros = form_dynamic($oldpassword);
	$view .= form_field_display(  $form_passwros   , "Password lama"    ); 
	$password = array(
		'name'=>'password',
		'value'=>( isset($_POST['password']) ? $_POST['password'] : '')  ,
		'id'=>'password',
		'type'=>'password'
	 );
	$form_passwro = form_dynamic($password);
	$view .= form_field_display(  $form_passwro   , "Password baru"    ); 
	$passwordb = array(
		'name'=>'passwordb',
		'value'=>( isset($_POST['passwordb']) ? $_POST['passwordb'] : '')  ,
		'id'=>'passwordb',
		'type'=>'password'
	 );
	$form_passwro = form_dynamic($passwordb);
	$view .= form_field_display(  $form_passwro   , "Ulang password baru"    ); 
	$view .= form_field_display(  '<input type="checkbox" value="1" name="auto_lout" /> Auto logout'  , "&nbsp;"    ); 
	$submit = array(
		'value' =>  '  Update  ' ,
		'name' => 'simpan', 
		'type'=>'submit','class'=>'main_button'
	);
	$form_submit= form_dynamic($submit); 
	$view .= form_field_display( $form_submit   , "&nbsp;" ,  "" );
	$view .= form_footer( );
	return $view;
}

function form_ganti_pasword_submit($id){
	$password = md5($_POST['password'].'@t');
	$query = "UPDATE apps_user SET password='{$password}' WHERE user_id = {$id} ";
	return my_query($query);
}