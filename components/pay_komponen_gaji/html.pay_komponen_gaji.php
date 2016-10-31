<?php

function list_pay_komponen_gaji(){
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
		'Kode' => array( 'width'=>'10%','style'=>'text-align:center;' ), 
		'Nama komponen' => array( 'width'=>'45%','style'=>'text-align:left;' ), 
		'Metode' => array( 'width'=>'30%','style'=>'text-align:left;' ), 
		'Aksi' => array( 'width'=>'10%','style'=>'text-align:center;' ) 
	);

	
	
	$query 	= "SELECT * FROM pay_komponen_gaji WHERE type = '{$_GET['type']}' ";
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
	$next_page_text = '<i class="fa fa-angle-right fa-fw"></i>';  
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
				'href'=>'index.php?com='.$_GET['com'].'&type='.$_GET['type'].'&task=edit&id=' . $ey['pay_komponen_gaji_id'] , 
				'title'=>'Edit'
		);	
		$edit_button = button_icon( 'b_edit.png' , $editproperty  );

		$deleteproperty = array(
			'href'=>'javascript:confirmDelete('.$ey['pay_komponen_gaji_id'].');',
			'title'=>'Delete', 
		);
		$delete_button = button_icon( 'b_drop.png' , $deleteproperty  );
		$method = my_get_data_by_id( 'pay_model_komponen_gaji', 'pay_model_komponen_gaji_id' , $ey['pay_model_komponen_gaji_id'] ); 
		if( (int) $ey['pay_model_komponen_gaji_id'] ==  3 ){
			$nama_tarif = get_tunjangan_name($ey['formula']); 
			$method['metode_spesifik'] .= ' '. strtolower($nama_tarif) ;
		}
		
		$row[] = array( 
		'#' => position_text_align( $i, 'center'),  
		'Kode' => position_text_align( $ey['pay_komponen_gaji_code'],  'center'),  
		'Nama komponen' => $ey['pay_komponen_gaji_label'],  
		'Metode' =>  $method['metode_spesifik'],   
		'op'=> position_text_align( $edit_button  .$delete_button , 'center')
		);
	}
	
	$datas = table_rows($row);
	$navigasi = array(
		'<input class="btn btn-primary" style="float:right;"  type="button" value="Tambah data" onclick="javascript:location.href=\'index.php?com='.$_GET['com'].'&type='.$_GET['type'].'&task=edit\'"/>',
		 
	);
	$box = header_box( '&nbsp;' , $navigasi );
	$paging = $kgPagerOBJ ->showPaging();
	return $box.table_builder($headers , $datas ,  5 , false , $paging  ); 
}

function get_tunjangan_name($code){
	$code = trim($code);
	$query = "SELECT * FROM pay_benefit_group  
		WHERE pay_benefit_group_code = '{$code}' ";
	$result = my_query($query);
	$row_count = my_num_rows($result);
	if($row_count > 0){
		$row = my_fetch_array($result);
		 
		return $row['pay_benefit_group_label'];
	}
	return null;
}


function submit_pay_komponen_gaji($id){
	 
	$datas = array(); 
	
	$datas['pay_komponen_gaji_code']	=  my_type_data_str($_POST['pay_komponen_gaji_code']);
	$datas['pay_komponen_gaji_label']	=  my_type_data_str($_POST['pay_komponen_gaji_label']);
	$datas['pay_model_komponen_gaji_id']	=  my_type_data_str($_POST['pay_model_komponen_gaji_id']);
	if( (int) $_POST['pay_jurnal_gaji_id'] > 0)
		$datas['pay_jurnal_gaji_id']	=  my_type_data_str($_POST['pay_jurnal_gaji_id']);
	$datas['formula']		=  my_type_data_str($_POST['formula']);
	$datas['type']	=  my_type_data_str( strtolower($_GET['type']));
	$datas['pay_komponen_pajak_id1']	=  my_type_data_str($_POST['pay_komponen_pajak_id1']);
	$datas['pay_komponen_pajak_id2']	=  my_type_data_str($_POST['pay_komponen_pajak_id2']);
	 
	$datas['user_updated_id']	= my_type_data_int($_SESSION['user_id']);  
	 
	if($id > 0){
		$datas['version'] = my_type_data_function( '(version + 1 )');
		$datas['updated_on']	=my_type_data_function('NOW()');
		set_data_karyawan_status($id);
		return my_update_record( 'pay_komponen_gaji' , 'pay_komponen_gaji_id' , $id , $datas );
	}
	$datas['created_on']	= my_type_data_function('NOW()');
	$datas['version'] = my_type_data_function( '0');
	$pay_komponen_gaji_id = my_insert_record( 'pay_komponen_gaji' , $datas );
	return set_data_karyawan_status($pay_komponen_gaji_id);
}

function set_data_karyawan_status($pay_komponen_gaji_id){
	my_query("DELETE FROM pay_komponen_gaji_karyawan_status 
		WHERE pay_komponen_gaji_id = {$pay_komponen_gaji_id}");
	foreach($_POST['komponen_karyawan_status'] as $key => $karyawan_status_id){
		$insert = "INSERT into pay_komponen_gaji_karyawan_status 
					SET pay_komponen_gaji_id = {$pay_komponen_gaji_id},
					karyawan_status_id = {$karyawan_status_id} ";
		my_query($insert);					
	}
	return true;
}


function form_pay_komponen_gaji_validate(){
	$errsubmit = false;
	$err = array();
	$pay_komponen_gaji_code = trim($_POST['pay_komponen_gaji_code']);
	if($pay_komponen_gaji_code == ''){
		$errsubmit =true;
		$err[] = "Kode komponen gaji belum di isi";
	} 
	elseif(! pay_komponen_gaji_is_unique($pay_komponen_gaji_code) ){
		$errsubmit =true;
		$err[] = "Kode komponen gaji sudah digunakan";
	}
	
	$pay_komponen_gaji_label = trim($_POST['pay_komponen_gaji_label']);
	if($pay_komponen_gaji_label == ''){
		$errsubmit =true;
		$err[] = "Nama komponen gaji belum di isi";
	}
	 
	if( ! isset($_POST['pay_model_komponen_gaji_id']) ){
		$errsubmit =true;
		$err[] = "Metode sumber data";
	}elseif((int) $_POST['pay_model_komponen_gaji_id'] == 3){
		$tarif_code = trim($_POST['formula']);
		if($tarif_code == ''){
			$errsubmit =true;
			$err[] = "Tarif referensi kode ( pada formula ) belum di isi";
		}elseif(!tarif_code($tarif_code)){
			$errsubmit =true;
			$err[] = "Kode tarif referensi tidak dikenal";
		}
	}elseif((int) $_POST['pay_model_komponen_gaji_id'] == 4){
		$formula_string = trim($_POST['formula']);
		if($formula_string == ''){
			$errsubmit =true;
			$err[] = "Tarif referensi kode ( pada formula ) belum di isi";
		}elseif(!test_formula($formula_string)){
			$errsubmit =true;
			$err[] = "Kode tarif referensi tidak dikenal";
		}
	}
	
	if(! isset($_POST['komponen_karyawan_status']) ){
		$errsubmit =true;
		$err[] = "Status karyawan berlaku tidak ada";
	}	
	
	if( $errsubmit){
		return $err;
	} 
	return false;
}

function test_formula($formula_string){
	return true;
}


function tarif_code($code){
	$code = trim($code);
	$query = "SELECT * FROM pay_benefit_group  WHERE pay_benefit_group_code = '{$code}' ";
	$result = my_query($query);
	$row_count = my_num_rows($result);
	if($row_count > 0){
		return true;
	}
	return false;
}	

function pay_komponen_gaji_is_unique($code){
	$id = isset($_GET['id']) ?  (int) $_GET['id'] : 0;
	if($id > 0 ) return true;
	$code = trim($code);
	$query = "SELECT * FROM pay_komponen_gaji  WHERE pay_komponen_gaji_code = '{$code}' ";
	$result = my_query($query);
	$row_count = my_num_rows($result);
	if($row_count > 0){
		return false;
	}
	return true;
}
	
function edit_pay_komponen_gaji($id){
	$fields = my_get_data_by_id('pay_komponen_gaji','pay_komponen_gaji_id', $id);
	$view =  help_formula_button();
	$view .= form_header( "form_pay_komponen_gaji" , "form_pay_komponen_gaji"  );
	  
	$pay_komponen_gaji_code = array(
			'name'=>'pay_komponen_gaji_code',
			'value'=>(isset($_POST['pay_komponen_gaji_code'])? $_POST['pay_komponen_gaji_code'] : $fields['pay_komponen_gaji_code']),
			'id'=>'pay_komponen_gaji_code',
			'type'=>'textfield' 
		);
	$form_pay_komponen_gaji_code = form_dynamic($pay_komponen_gaji_code);
	$view .= form_field_display( $form_pay_komponen_gaji_code  , "Kode komponen *"  );
	 
	$pay_komponen_gaji_label = array(
			'name'=>'pay_komponen_gaji_label',
			'value'=>(isset($_POST['pay_komponen_gaji_label'])? $_POST['pay_komponen_gaji_label'] : $fields['pay_komponen_gaji_label']),
			'id'=>'pay_komponen_gaji_label',
			'type'=>'textfield' 
		);
	$form_pay_komponen_gaji_label = form_dynamic($pay_komponen_gaji_label);
	$view .= form_field_display( $form_pay_komponen_gaji_label  , "Nama komponen penggajian *"  );
	
	 
	$pay_model_komponen_gaji_ids =  array( );
	$query = "SELECT pay_model_komponen_gaji_id , metode_spesifik FROM pay_model_komponen_gaji WHERE componen_list_option = '1' ";	
	$result = my_query($query);
	while($row_pay_model_komponen_gaji_id = my_fetch_array($result)){
		$pay_model_komponen_gaji_ids[$row_pay_model_komponen_gaji_id['pay_model_komponen_gaji_id']] = $row_pay_model_komponen_gaji_id['metode_spesifik'];
	}
	$pay_model_komponen_gaji_id = array(
		'name'=>'pay_model_komponen_gaji_id',
		'value'=>( isset($_POST['pay_model_komponen_gaji_id']) ? $_POST['pay_model_komponen_gaji_id'] : $fields['pay_model_komponen_gaji_id']) ,
	);
	$form_pay_model_komponen_gaji_id = form_radiobutton($pay_model_komponen_gaji_id , $pay_model_komponen_gaji_ids);
	$view .= form_field_display(  $form_pay_model_komponen_gaji_id   , "Metode sumber data *"    ); 
	 
	$formula = array(
			'name'=>'formula',
			'value'=>(isset($_POST['formula'])? $_POST['formula'] : $fields['formula']),
			'id'=>'formula',
			'rows'=>'3' 
		);
	$form_formula = form_textarea($formula);
	$view .= form_field_display( $form_formula  , "Formula | Kode Tarif &nbsp; " . '
			<!-- Button trigger modal -->
			<a href="javascript:;" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#myModal">
				?
			</a>' );
	 
	$pay_jurnal_gaji_ids =  array( );
	$query = "SELECT pay_jurnal_gaji_id , pay_jurnal_gaji_label FROM pay_jurnal_gaji";	
	$result = my_query($query);
	while($row_pay_jurnal_gaji_id = my_fetch_array($result)){
		$pay_jurnal_gaji_ids[$row_pay_jurnal_gaji_id['pay_jurnal_gaji_id']] = $row_pay_jurnal_gaji_id['pay_jurnal_gaji_label'];
	}
	$pay_jurnal_gaji_id = array(
		'name'=>'pay_jurnal_gaji_id',
		'value'=>( isset($_POST['pay_jurnal_gaji_id']) ? $_POST['pay_jurnal_gaji_id'] : $fields['pay_jurnal_gaji_id']) ,
	);
	$form_pay_jurnal_gaji_id = form_dropdown($pay_jurnal_gaji_id , $pay_jurnal_gaji_ids);
	$view .= form_field_display(  $form_pay_jurnal_gaji_id   , "Jurnal buku gaji"    ); 
	 
	
	$option_multiple = '
	<select multiple class="form-control" name="komponen_karyawan_status[]">';
	$rquery = "SELECT * FROM karyawan_status ORDER BY karyawan_status_id ASC";
	$rresult = my_query($rquery);
	while($rrpw = my_fetch_array($rresult) ){
		if( is_select_status( $id , $rrpw['karyawan_status_id'] ) )
			$option_multiple .= '<option selected value="'.$rrpw['karyawan_status_id'].'">'.strtoupper($rrpw['karyawan_status_label']).'</option>';
		else
			$option_multiple .= '<option value="'.$rrpw['karyawan_status_id'].'">'.strtoupper($rrpw['karyawan_status_label']).'</option>';
	}
	$option_multiple .= '</select> ';
	$view .= form_field_display(  $option_multiple   , "Berlaku untuk status karyawan"    ); 
		
		
	$pay_komponen_pajak_ids =  array( );
	$query = "SELECT  komponen_pajak_id,  label_pajak  FROM pay_komponen_pajak
	WHERE select_option = 1 ";	
	$result = my_query($query);
	while($row_pay_komponen_pajak_id1 = my_fetch_array($result)){
		$pay_komponen_pajak_id1s[$row_pay_komponen_pajak_id1['komponen_pajak_id']] = $row_pay_komponen_pajak_id1['label_pajak'];
	}
	$pay_komponen_pajak_id1 = array(
		'name'=>'pay_komponen_pajak_id1',
		'value'=>( isset($_POST['pay_komponen_pajak_id1']) ? $_POST['pay_komponen_pajak_id1'] : $fields['pay_komponen_pajak_id1']) ,
	);
	$form_pay_komponen_pajak_id1 = form_dropdown($pay_komponen_pajak_id1 , $pay_komponen_pajak_id1s);
	$view .= form_field_display(  $form_pay_komponen_pajak_id1   , "Pajak komponen"    ); 
	
	if($_GET['type'] == 'subsidi'){
		$pay_komponen_pajak_id2 = array(
			'name'=>'pay_komponen_pajak_id2',
			'value'=>( isset($_POST['pay_komponen_pajak_id2']) ? $_POST['pay_komponen_pajak_id2'] : $fields['pay_komponen_pajak_id2']) ,
		);
		$form_pay_komponen_pajak_id2 = form_dropdown($pay_komponen_pajak_id2 , $pay_komponen_pajak_id1s);
		$view .= form_field_display(  $form_pay_komponen_pajak_id2   , "Pajak komponen lain"    ); 
	}
										
											
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
		'onclick'=>'javascript:location.href=\''.( isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'index.php' ).'\'',
		'class'=>'main_button'
	);
	$form_cancel= form_dynamic($cancel);
	
	
	$view .= form_field_display( $form_submit .' '.$form_cancel , "&nbsp;" );
	$view .= form_footer( ); 
   
	return $view;
} 

function is_select_status( $pay_komponen_gaji_id , $karyawan_status_id){
	$query = "SELECT * FROM pay_komponen_gaji_karyawan_status 
		WHERE pay_komponen_gaji_id = {$pay_komponen_gaji_id}
		AND karyawan_status_id = {$karyawan_status_id} ";
	$result = my_query($query);
	if(my_num_rows($result) > 0 ){
		return true;
	}
	return false;
}


function help_formula_button(){
	$view = ' 
	 
		<!-- /.panel-heading -->
		<div class="panel-body">
			
			<!-- Modal -->
			<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							<h4 class="modal-title" id="myModalLabel">Formula atau table referensi</h4>
						</div>
						<div class="modal-body">
							
<p>Daftar simbol untuk formula:</p>
<table width="100%" padding="2px" border="1px" style="border-collapse:collapse;">
	<tr>
		<thead>
			<td width="20%">SIMBOL</td> 
			<td width="80%">DESKRIPSI</td> 
		<thead>
	<tr>
	<tr>
		<td valign="top">[GAPOK]</td> 
		<td valign="top">System akan memberikan data gaji pokok ketika proses berlangsung</td>
	</tr>
	<tr>
		<td valign="top">[UMURKERJA]</td> 
		<td valign="top">System akan memberikan data sudah berapa lama karyawan kerja di perusahaan ( dalam satuan bulan )</td>
	</tr>
</table>      
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button> 
						</div>
					</div>
					<!-- /.modal-content -->
				</div>
				<!-- /.modal-dialog -->
			</div>
			<!-- /.modal -->
		</div>
		<!-- .panel-body --> 
	';
	return $view ;
}
?>