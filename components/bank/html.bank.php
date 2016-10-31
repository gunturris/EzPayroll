<?php
	
function list_bank(){

//PASANG JAVASCRIPT
my_set_code_js('
	function confirmDelete(id){
		var t = confirm(\'Yakin akan menghapus data ?\');
		if(t){
			location.href=\'index.php?com=bank&task=delete_bank&id=\'+id;
		}
		return false;
	}
');
	$header = array(
		'#'=>array('style'=>'width:5%'),  
		'Kode'=>array('style'=>'width:15%'), 
		'Nama Bank'=>array('style'=>'width:70%'), 
		' '=>array('style'=>'width:10%'), 
	);
	$query = "SELECT * FROM bank ORDER BY bank_code";
	$result = my_query($query);
	$i = 0;
	$row=array();
	while($ey = my_fetch_array($result)){
		$i++;
		$editproperty = array(
			'href'=>'index.php?com=bank&task=edit_bank&id='.$ey['bank_id'],
			'rel'=>'facebox',
			'title'=>'Edit'
		);
		$edit_button = button_icon( 'b_edit.png' , $editproperty  );

		$deleteproperty = array(
			'href'=>'javascript:; ',
			'onclick'=>'javascript:confirmDelete('.$ey['bank_id'].');',
			'title'=>'Delete'
		);
		$delete_button = button_icon( 'b_drop.png' , $deleteproperty  );
		 
		$row[] = array(
			'#'=>position_text_align ($i, 'center'), 
			'singkat'=>  $ey['bank_code'] ,   
			'bank'=>  $ey['bank_name'] ,   
			'operasi'=> position_text_align(  $edit_button.' '.$delete_button , 'center'),  
		);
		 
	}
	$navigasi = array(
		'<input class="btn btn-primary" style="float:right;"  type="button" value="Tambah data" onclick="javascript:location.href=\'index.php?com='.$_GET['com'].'&task=edit_bank\'"/>',
	);
	$form_Search  =
	' ';
	$box = header_box( $form_Search , $navigasi );
	$datas = table_rows($row);   
	return $box . table_builder($header , $datas ,  3 , false );
}

function form_bank($id = 0){
	$view = form_header( "bank" , "bank"  );
 	$fields = my_get_data_by_id('bank','bank_id',$id);

	$temp = array(
		'name'=>'bank_code',
		'value'=>( isset($_POST['bank_code']) ? $_POST['bank_code'] : $fields['bank_code'] ),
		'id'=>'bank_code',
		'type'=>'text', 
	);
	$form_temp = form_dynamic($temp);	
	$view .= form_field_display( $form_temp  , "Kode bank"   );	

	$temp = array(
		'name'=>'bank_name',
		'value'=>( isset($_POST['bank_name']) ? $_POST['bank_name'] : $fields['bank_name'] ),
		'id'=>'bank_name',
		'type'=>'text', 
	);
	$form_temp = form_dynamic($temp);	
	$view .= form_field_display( $form_temp   , "Nama Bank"   );
	
	$submit = array(
		'value' => ( $id ==0 ? ' Simpan ' :'  Update  '),
		'name' => 'simpan', 
		'type'=>'submit','class'=>'main_button'
	);
	$form_submit= form_dynamic($submit);
	
	$cancel = array(
		'value' => (  ' Batal '  ),
		'name' => 'cancel', 
		'type'=>'reset',
		'onclick'=>'javascript:location.href=\''.$_SERVER['HTTP_REFERER'].'\'',
		'class'=>'main_button'
	);
	$form_cancel = form_dynamic($cancel);
	
	$view .= form_field_display( $form_submit  .' '. $form_cancel , "&nbsp;" ,  "" );
	$view .= form_footer( );
	return $view;
}

function valid_bank($id){
	$errsubmit = false;
	$err = array();
	 
	$temp =  trim($_POST["bank_code"]); 
	if( $temp == ''){
		$errsubmit = true;
		$err[] = "Singkatan belum diisi";
	}else{
		$query="SELECT bank_id FROM bank WHERE bank_code='{$temp}' AND bank_id<>{$id}"; 
		if (my_num_rows( my_query($query)) >0){
			$errsubmit = true;
			$err[] = "Singkatan sudah ada";	
		}	
	}
	
	$temp =  trim($_POST["bank_name"]); 
	if( $temp == ''){
		$errsubmit = true;
		$err[] = "Nama bank belum diisi";
	}else{
		$query="SELECT bank_id FROM bank WHERE bank_name='{$temp}' AND bank_id<>{$id}"; 
		if (my_num_rows( my_query($query)) >0){
			$errsubmit = true;
			$err[] = "Nama bank sudah ada";	
		}	
	}	
	if( $errsubmit){
		return $err;
	}	
	return $errsubmit;
}

function submit_bank($id){
	$datas = array();
	$datas['bank_code']= my_type_data_str( $_POST['bank_code'] );
	$datas['bank_name']= my_type_data_str( $_POST['bank_name'] );	 
	$datas['user_updated_id']	= my_type_data_int($_SESSION['user_id']); 
	if($id > 0){
		$datas['updated_on']	=my_type_data_function('NOW()');
		$datas['version'] = my_type_data_function( '(version + 1 )');
		return my_update_record('bank' ,'bank_id' , $id , $datas);
	}

	$datas['created_on']= my_type_data_function('NOW()');
	$datas['version'] 	= my_type_data_function( '0');
	return my_insert_record('bank' , $datas);
}


function remove_bank($id){
	$query= "DELETE FROM bank WHERE bank_id = {$id}";
	return my_query($query);
} 
 