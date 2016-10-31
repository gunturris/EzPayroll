<?php
//if(!is_admin())fatal_error('Akses ditolak');
my_component_load('__jsload' , false);
my_component_load('__paging' , false);  
my_component_load('user');
$task = isset($_GET['task']) ? $_GET['task'] : ""; 
$id = isset( $_GET['id'] ) ? $_GET['id']:  0;


if($_SERVER['REQUEST_METHOD'] == "POST" ):
	switch($task){
		case   "edit" :
			form_create_user_submit($id);
			my_direct('index.php?com=user');
		break;
		case "ganti_password":
		
			$pagename =   "Ganti password";
			$validate =  form_password_user_validate();
			if($validate){ 
				$content = message_multi_error($validate);
				$content .= form_ganti_pasword(  ); 
			}else{
				form_ganti_pasword_submit($_SESSION['user_id']);
				if($_POST['auto_lout'])my_direct('login.php?logout='.md5(rand(0,100)));
				else my_direct('index.php');
			}
		break;
		 
	}

else: 	
	if($task == "edit"){
		$pagename = ($id ==0) ? "Tambah pengguna" : "Ubah data pengguna";
		$content = form_create_user( $id); 	 
	}elseif($task == "delete"){	
	}elseif($task == "ganti_password"){	
		$pagename =   "Ganti password";
		$content = form_ganti_pasword(  ); 
	}else{
		$pagename = "Data daftar pengguna";
		load_facebox_script();
		$content = userlist();	
	}
endif; 
generate_my_web($content, $pagename  );