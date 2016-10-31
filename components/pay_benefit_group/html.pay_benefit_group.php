<?php

function list_pay_benefit_group(){
	my_set_code_js('
		function confirmDelete(id){
			var t = confirm(\'Yakin akan menghapus data ?\');
			if(t){
				location.href=\'index.php?com='.$_GET['com'].'&task=delete&id=\'+id;
			}
			return false;
		}
	');	
	$headers= array( 
		'#' => array( 'width'=>'5%','style'=>'text-align:center;' ), 
		'Kode' => array( 'width'=>'20%','style'=>'text-align:center;' ), 
		'Nama tarif' => array( 'width'=>'55%','style'=>'text-align:left;' ), 
		'Term' => array( 'width'=>'10%','style'=>'text-align:left;' ), 
		'Aksi' => array( 'width'=>'10%','style'=>'text-align:center;' ),  
	);

	
	
	$query 	= "SELECT * FROM pay_benefit_group WHERE type_of_benefit = '{$_GET['type']}'";
	$result = my_query($query);
	
	//PAGING CONTROL START
	$total_records = my_num_rows($result );
	$scroll_page = SCROLL_PERHALAMAN;  
	$per_page = PAGING_PERHALAMAN;  
	$current_page = isset($_GET['halaman']) ? (int) $_GET['halaman'] : 1 ; 
	if($current_page < 1){
		$current_page = 1;
	}		 
	$task = isset($_GET['task']) ?$_GET['task'] :'' ;
	$field = isset($_GET['field']) ?$_GET['field'] :'' ;
	$key = isset($_GET['key']) ?$_GET['key'] :'' ;
	$pager_url  ="index.php?com={$_GET['com']}&task={$task}&field={$field}&key={$key}&halaman=";	 
	$pager_url_last='';
	$pager_url_last='';
	$inactive_page_tag = 'style="padding:4px;background-color:#BBBBBB"';  
	$previous_page_text = '<i class="fa fa-angle-left fa-fw"></i>'; 
	$next_page_text = '<i class"fa fa-angle-right fa-fw"></i>';  
	$first_page_text = '<i class="fa fa-angle-double-left fa-fw"></i>'; 
	$last_page_text = '<i class="fa fa-angle-double-right fa-fw"></i>';
	
	$kgPagerOBJ = new kgPager();
	$kgPagerOBJ->pager_set(
		$pager_url, 
		$total_records, 
		$scroll_page, 
		$per_page, 
		$current_page, 
		$inactive_page_tag, 
		$previous_page_text, 
		$next_page_text, 
		$first_page_text, 
		$last_page_text ,
		$pager_url_last
		); 
	 		
	$result = my_query($query ." LIMIT ".$kgPagerOBJ->start.", ".$kgPagerOBJ->per_page);  
	$i = ($current_page  - 1 ) * $per_page ;
	//PAGING CONTROL END
	
	$row = array();
	while($ey = my_fetch_array($result)){
		$i++;
		$editproperty = array(
				'href'=>'index.php?com='.$_GET['com'].'&type='.$_GET['type'].'&task=edit&id=' . $ey['pay_benefit_group_id'] , 
				'title'=>'Edit'
		);	
		$edit_button = button_icon( 'b_edit.png' , $editproperty  );

		$detailproperty = array(
				'href'=>'index.php?com='.$_GET['com'].'&type='.$_GET['type'].'&task=detail&id=' . $ey['pay_benefit_group_id'] , 
				'title'=>'Detail info'
		);	
		$detail_button = button_icon( 'b_props.png' , $detailproperty  );

		$deleteproperty = array(
			'href'=>'javascript:confirmDelete('.$ey['pay_benefit_group_id'].');',
			'title'=>'Delete', 
		);
		$delete_button = button_icon( 'b_drop.png' , $deleteproperty  ); 
		$row[] = array( 
		'#' => position_text_align( $i , 'center'),  
		'Kode' => $ey['pay_benefit_group_code'],  
		'Nama tarif' => $ey['pay_benefit_group_label'],  
		'Term' => ucfirst($ey['term']),  
		'op'=> position_text_align( $detail_button  . $edit_button  . $delete_button , 'center')
		);
	}
	
	$datas = table_rows($row);
	$navigasi = array(
		'<input class="btn btn-primary" style="float:right;"  type="button" value="Tambah data" onclick="javascript:location.href=\'index.php?com='.$_GET['com'].'&type='.$_GET['type'].'&task=edit\'"/>',
	);
	$box = header_box( '&nbsp;' , $navigasi );
	$paging = $kgPagerOBJ ->showPaging();
	return $box.table_builder($headers , $datas ,  4 , false , $paging  ); 
}


function submit_pay_benefit_group($id){
	 
	$datas = array(); 
	$datas['pay_benefit_group_code']	=  my_type_data_str($_POST['pay_benefit_group_code']);
	$datas['pay_benefit_group_label']	=  my_type_data_str($_POST['pay_benefit_group_label']);
	$datas['type_of_benefit']	=  my_type_data_str($_GET['type']);
	$datas['deskripsi']	=  my_type_data_str($_POST['deskripsi']);
	$datas['user_updated_id']	= my_type_data_int($_SESSION['user_id']);  

	if($id > 0){
		$datas['updated_on']	=  my_type_data_function('NOW()');
		$datas['version'] = my_type_data_function('( version + 1 )');
		return my_update_record( 'pay_benefit_group' , 'pay_benefit_group_id' , $id , $datas );
	}
	$datas['version']	= my_type_data_int(0);
	$datas['created_on']	= my_type_data_function('NOW()');
	return my_insert_record( 'pay_benefit_group' , $datas );
}

function form_pay_benefit_group_validate(){

	$errsubmit = false;
	$err = array();
	$pay_benefit_group_code = trim($_POST['pay_benefit_group_code']);
	if($pay_benefit_group_code == ''){
		$errsubmit =true;
		$err[] = "Kode nama tarif jabatan belum di isi";
	} 
	elseif(! pay_benefit_group_is_unique($pay_benefit_group_code) ){
		$errsubmit =true;
		$err[] = "Kode nama tarif jabatan sudah digunakan";
	}
	
	if( $errsubmit){
		return $err;
	} 
	return false;
}


function pay_benefit_group_is_unique($code){
	$id = isset($_GET['id']) ?  (int) $_GET['id'] : 0;
	if($id > 0 ) return true;
	$code = trim($code);
	$query = "SELECT * FROM pay_benefit_group  WHERE pay_benefit_group_code = '{$code}' ";
	$result = my_query($query);
	$row_count = my_num_rows($result);
	if($row_count > 0){
		return false;
	}
	return true;
}
		
	
function edit_pay_benefit_group($id){
	
	my_set_file_js(
		array(
			'assets/jquery/combomulti/jquery.chainedSelects.js',
			'assets/js/calendar/calendarDateInput.js' 
		)
	);
	$view = form_header( "form_pay_benefit_group" , "form_pay_benefit_group"  );
	$fields = my_get_data_by_id('pay_benefit_group','pay_benefit_group_id', $id);
 
	
	$pay_benefit_group_code = array(
			'name'=>'pay_benefit_group_code',
			'value'=>(isset($_POST['pay_benefit_group_code'])? $_POST['pay_benefit_group_code'] : $fields['pay_benefit_group_code']),
			'id'=>'pay_benefit_group_code',
			'type'=>'textfield' 
		);
	$form_pay_benefit_group_code = form_dynamic($pay_benefit_group_code);
	$view .= form_field_display( $form_pay_benefit_group_code  , "Kode"  );
	 
	$pay_benefit_group_label = array(
			'name'=>'pay_benefit_group_label',
			'value'=>(isset($_POST['pay_benefit_group_label'])? $_POST['pay_benefit_group_label'] : $fields['pay_benefit_group_label']),
			'id'=>'pay_benefit_group_label',
			'type'=>'textfield' 
		);
	$form_pay_benefit_group_label = form_dynamic($pay_benefit_group_label);
	$view .= form_field_display( $form_pay_benefit_group_label  , "Nama tarif {$_GET['type']}"  );
	
	$deskripsi = array(
			'name'=>'deskripsi',
			'value'=>(isset($_POST['deskripsi'])? $_POST['deskripsi'] : $fields['deskripsi']),
			'id'=>'deskripsi',
			'rows'=>'3' 
		);
	$form_deskripsi = form_textarea($deskripsi);
	$view .= form_field_display( $form_deskripsi  , "Deskripsi"  ); 
		 
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
	$form_cancel= form_dynamic($cancel); 
	
	$view .= form_field_display( $form_submit .' '.$form_cancel, "&nbsp;" );
	$view .= form_footer( );	
 
	return $view;
} 

function detail_tarif($id = 0){
	$falist = list_of_tarif_golongan($id);
	$query = "SELECT * FROM pay_benefit_group WHERE pay_benefit_group_id = {$id} ";
	$result = my_query($query);
	$row = my_fetch_array($result);
	$view = '  <h4 id="grid-column-ordering">'.$row['pay_benefit_group_code'].' / '.$row['pay_benefit_group_label'].'</h3>
				'.( trim($row['deskripsi']) <> '' ? '<p>'.$row['deskripsi'].'</p>' : '').'
				'.$falist ;
	return $view;
}

function get_nominal_by_goljab_benefit_group(  $goljab_id ,  $benefit_group_id){
	
	$goljab_id = (int) $goljab_id ;
	$benefit_group_id = (int) $benefit_group_id ;
	$query = "
		SELECT nominal,deskripsi FROM pay_benefit_gol_jab 
			WHERE karyawan_gol_jab_id = {$goljab_id} 
				AND pay_benefit_group_id = {$benefit_group_id} 
					";
	$result = my_query($query);
	$row = my_fetch_array($result);
	return $row;
}

function list_of_tarif_golongan($id){
	$query = "SELECT * FROM  karyawan_gol_jab ";
	$result = my_query($query);
	$headers= array( 
		'#' => array( 'width'=>'5%','style'=>'text-align:center;' ), 
		'Kode' => array( 'width'=>'10%','style'=>'text-align:center;' ), 
		'Tarif' => array( 'width'=>'20%','style'=>'text-align:center;' ), 
		'Keterangan' => array( 'width'=>'55%','style'=>'text-align:left;' ), 
		'Aksi' => array( 'width'=>'10%','style'=>'text-align:center;' ),  
	);
	$row = array();
	$i = 0;
	while($ey = my_fetch_array($result)){
		$i++;
		$prodata = get_nominal_by_goljab_benefit_group($ey['karyawan_gol_jab_id'] , $id); 
		$editproperty = array(
			'href'=>'index.php?com='.$_GET['com'].'&type='.$_GET['type'].'&task=edit_nominal&pay_benefit_group_id='.$id.'&karyawan_gol_jab_id='.$ey['karyawan_gol_jab_id'],
			'title'=>'Edit'
		);
		$edit_button = button_icon( 'b_edit.png' , $editproperty  );
		
		$row[] = array( 
		'#' => position_text_align( $i , 'center'),  
		'Kode' => position_text_align($ey['karyawan_gol_jab_label'], 'center'),  
		'Tarif' => position_text_align('Rp. '.rp_format($prodata['nominal']), 'right'),  
		'Deskripsi' => $prodata['deskripsi'],  
		'op'=> position_text_align( $edit_button , 'center')
		);
	}
	$datas = table_rows($row); 
	
	$navigasi = array(
		'<input class="btn btn-primary btn-sm" style="float:right;margin-bottom:5px;"  type="button" value="Kembali" onclick="location.href=\'index.php?com='.$_GET['com'].'&type='.$_GET['type'].'\'"/>',
	);
	$box = header_box( '&nbsp;' , $navigasi );
	
	return table_builder($headers , $datas , 5 , false    ) .$box; 
	 
}

function edit_nominal_tarif($karyawan_gol_jab_id , $pay_benefit_group_id){
 
	my_set_file_js(
		array(
			'assets/jquery/combomulti/jquery.chainedSelects.js',
			'assets/js/calendar/calendarDateInput.js' 
		)
	);
	
	$row = my_get_data_by_id('pay_benefit_group','pay_benefit_group_id', (int) $pay_benefit_group_id);
	$view = '  <h4 id="grid-column-ordering">'.$row['pay_benefit_group_code'].' / '.$row['pay_benefit_group_label'].'</h3>
				'.( trim($row['deskripsi']) <> '' ? '<p>'.$row['deskripsi'].'</p>' : '');
	$view .= form_header( "form_pay_benefit_group" , "form_pay_benefit_group"  ); 
 
	$goljab = my_get_data_by_id('karyawan_gol_jab','karyawan_gol_jab_id', (int)$karyawan_gol_jab_id);
 
	$karyawan_gol_jab_label = array(
			'name'=>'karyawan_gol_jab_label',
			'value'=>( $goljab['karyawan_gol_jab_label']),
			'id'=>'karyawan_gol_jab_label',
			'type'=>'textfield' ,
			'readonly'=>'readonly'
		);
	$form_karyawan_gol_jab_label = form_dynamic($karyawan_gol_jab_label);
	$view .= form_field_display( $form_karyawan_gol_jab_label  , "Kode level jabatan"  );
	
	$query = "SELECT * FROM pay_benefit_gol_jab 
		WHERE karyawan_gol_jab_id = {$karyawan_gol_jab_id}
		AND pay_benefit_group_id = {$pay_benefit_group_id} ";
	$result = my_query($query);
	$rowr = my_fetch_array($result);
	$nominal = array(
			'name'=>'nominal',
			'value'=>(isset($_POST['nominal'])? $_POST['nominal'] : $rowr['nominal']),
			'id'=>'nominal',
			'type'=>'textfield' 
		);
	$form_nominal = form_dynamic($nominal);
	$view .= form_field_display( $form_nominal  , "Nominal tarif"  );
	
	$deskripsi = array(
			'name'=>'deskripsi',
			'value'=>(isset($_POST['deskripsi'])? $_POST['deskripsi'] : $rowr['deskripsi']),
			'id'=>'deskripsi',
			'rows'=>'3' 
		);
	$form_deskripsi = form_textarea($deskripsi);
	$view .= form_field_display( $form_deskripsi  , "Deskripsi"  ); 
		 
 
 	$submit = array(
		'value' => ( '  Update  '),
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
	$form_cancel= form_dynamic($cancel); 
	
	$view .= form_field_display( $form_submit .' '.$form_cancel, "&nbsp;" );
	$view .= form_footer( );	
 
	return $view;
}

function update_tarif($karyawan_gol_jab_id , $pay_benefit_group_id){
	$nominal = (float) $_POST['nominal'];
	$deskripsi = stripslashes($_POST['deskripsi']);
	$query = "SELECT * FROM pay_benefit_gol_jab 
		WHERE karyawan_gol_jab_id = {$karyawan_gol_jab_id}
		AND pay_benefit_group_id = {$pay_benefit_group_id} ";
	$result = my_query($query);
	if(my_num_rows($result) > 0 ){
		$update = "UPDATE pay_benefit_gol_jab SET 
				version = (version+1),
				user_updated_id = {$_SESSION['user_id']},
				nominal = '{$nominal}',
				deskripsi = '{$deskripsi}'
				WHERE karyawan_gol_jab_id = {$karyawan_gol_jab_id}
				AND pay_benefit_group_id = {$pay_benefit_group_id}
				";
		return my_query($update);
	}
	$datas['karyawan_gol_jab_id']	= my_type_data_str($karyawan_gol_jab_id);
	$datas['pay_benefit_group_id']	= my_type_data_str($pay_benefit_group_id);
	$datas['nominal']	= my_type_data_str($nominal);
	$datas['deskripsi']	= my_type_data_str($_POST['deskripsi']);
	$datas['version']	= my_type_data_int(0);
	$datas['user_updated_id']	= my_type_data_int($_SESSION['user_id']);
	$datas['created_on']	= my_type_data_function('NOW()');
	return my_insert_record( 'pay_benefit_gol_jab' , $datas );
}

?>