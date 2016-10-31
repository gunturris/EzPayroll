<?php

function form_set_parameter($id){
my_set_file_js(
		array(
			'assets/jquery/combomulti/jquery.chainedSelects.js',
			'assets/js/calendar/calendarDateInput.js' 
		)
	);
	$view = form_header( "form_pay_unreguler" , "form_pay_unreguler"  );
	$fields = my_get_data_by_id('unreg_periode','unreg_periode_id', $id);
 

	
	$code = array(
			'name'=>'unreg_periode_code',
			'value'=>(isset($_POST['unreg_periode_code'])? $_POST['unreg_periode_code'] : $fields['unreg_periode_code']),
			'id'=>'unreg_periode_code',
			'type'=>'textfield' ,
			'size' =>'15'
		);
	$form_code = form_dynamic($code);
	$view .= form_field_display( $form_code  , "Kode gaji unreguler"  );
	
	
	
	$title = array(
			'name'=>'unreg_periode_name',
			'value'=>(isset($_POST['unreg_periode_name'])? $_POST['unreg_periode_name'] : $fields['unreg_periode_name']),
			'id'=>'unreg_periode_name',
			'type'=>'textfield' 
		);
	$form_title = form_dynamic($title);
	$view .= form_field_display( $form_title  , "Nama gaji unreguler"  );
	
	//DASAR PERHITUNGAN
	$query = "SELECT * FROM pay_periode_reguler 
	WHERE status_proses = 'Closed'
	ORDER BY pay_periode_reguler_id DESC ";
	$result = my_query($query);
	$pay_periodes=array();
	while( $row = my_fetch_array( $result ) ){
		$pay_periodes[$row['pay_periode_reguler_id']] = $row['title']; 
	}
	
	$reguler_periode_based_id = array(
			'name'=>'reguler_periode_based_id',
			'value'=>(isset($_POST['reguler_periode_based_id'])? $_POST['reguler_periode_based_id'] : $fields['reguler_periode_based_id']),
			'id'=>'reguler_periode_based_id',
			'type'=>'textfield' 
		);
	$form_reguler_periode_based_id = form_dropdown($reguler_periode_based_id ,$pay_periodes );
	$view .= form_field_display( $form_reguler_periode_based_id  , "Dasar perhitungan gaji reguler"  );
	
	$start_date = array(
			'name'=>'periode_start_date',
			'value'=> (isset($_POST['periode_start_date'])? $_POST['periode_start_date'] :($fields ? date('Y-m-d', strtotime($fields['periode_start_date'])) : date('Y-m-d' ))),
			'id'=>'periode_start_date',
			'type'=>'textfield' , 
		);
	$form_first_current_date_form = form_calendar($start_date);
	$view .= form_field_display( $form_first_current_date_form  , "Awal periode" );
	
	 
	$periode_end = array(
			'name'=>'periode_end_date',
			'value'=>(isset($_POST['periode_end_date'])? $_POST['periode_end_date'] :($fields ? date('Y-m-d', strtotime($fields['periode_end_date'])) : date('Y-m-d' ))),
			'id'=>'periode_end_date',
			'type'=>'textfield',
			'size'=>'45'
		);
	$form_periode_end = form_calendar($periode_end);
	$view .= form_field_display( $form_periode_end  , "Akhir periode" );
	

	$payment_date  = array(
			'name'=>'payment_date',
			'value'=>(isset($_POST['payment_date'])? $_POST['payment_date'] : ($fields ? date('Y-m-d', strtotime($fields['payment_date'])) : date('Y-m-d' ))),
			'id'=>'payment_date',
			'type'=>'textfield',
			'size'=>'45'
		);
	$form_payment_date = form_calendar($payment_date);
	$view .= form_field_display( $form_payment_date  , "Tanggal bayar" );
	

	 
  
	$option_multiple_agama = '
	<select multiple class="form-control" name="karyawan_agama[]">';
	$rquery = "SELECT * FROM karyawan_agama ";
	$rresult = my_query($rquery);
	while($rrpw = my_fetch_array($rresult) ){
		if( is_param_select_agama( $id , $rrpw['karyawan_agama_id'] ) )
			$option_multiple_agama .= '<option selected value="'.$rrpw['karyawan_agama_id'].'">'.strtoupper($rrpw['karyawan_agama_label']).'</option>';
		else
			$option_multiple_agama .= '<option value="'.$rrpw['karyawan_agama_id'].'">'.strtoupper($rrpw['karyawan_agama_label']).'</option>';
	}
	$option_multiple_agama .= '</select> ';
	$view .= form_field_display(  $option_multiple_agama   , "Dasar keagamaan"    ); 
		
	$option_multiple = '
	<select multiple class="form-control" name="karyawan_status[]">';
	$rquery = "SELECT * FROM karyawan_status ORDER BY karyawan_status_id ASC";
	$rresult = my_query($rquery);
	while($rrpw = my_fetch_array($rresult) ){
		if( is_param_select_status( $id , $rrpw['karyawan_status_id'] ) )
			$option_multiple .= '<option selected value="'.$rrpw['karyawan_status_id'].'">'.strtoupper($rrpw['karyawan_status_label']).'</option>';
		else
			$option_multiple .= '<option value="'.$rrpw['karyawan_status_id'].'">'.strtoupper($rrpw['karyawan_status_label']).'</option>';
	}
	$option_multiple .= '</select> ';
	$view .= form_field_display(  $option_multiple   , "Berlaku untuk status karyawan"    ); 
		
	$option_multiple_komponen = '
	<select multiple class="form-control" name="komponen_gaji[]">';
	$rquery = "SELECT * FROM pay_komponen_gaji ORDER BY pay_komponen_gaji_id ASC";
	$rresult = my_query($rquery);
	while($rrpw = my_fetch_array($rresult) ){
		if( is_param_select_komponen( $id , $rrpw['pay_komponen_gaji_id'] ) )
			$option_multiple_komponen .= '<option selected value="'.$rrpw['pay_komponen_gaji_id'].'">'.strtoupper($rrpw['pay_komponen_gaji_label']).'</option>';
		else
			$option_multiple_komponen .= '<option value="'.$rrpw['pay_komponen_gaji_id'].'">'.strtoupper($rrpw['pay_komponen_gaji_label']).'</option>';
	}
	$option_multiple_komponen .= '</select> ';
	$view .= form_field_display(  $option_multiple_komponen   , "Komponen hitung"    ); 
	
	$checked = $fields ? ( ($fields['hitung_pajak'] == '1') ? ' checked="checked" ' : '' ) : '' ;
	$view .= form_field_display(  '<br /><input type="checkbox" name="is_pajak" value="1" '.$checked.'/> Ya, sesuai net/gross masing-masing', "Di kenakan pajak"    ); 
	 
	$deskripsi = array(
			'name'=>'deskripsi',
			'value'=>(isset($_POST['deskripsi'])? $_POST['deskripsi'] : $fields['deskripsi']),
			'id'=>'deskripsi',
			'rows'=>'3' 
		);
	$form_deskripsi = form_textarea($deskripsi);
	$view .= form_field_display( $form_deskripsi  , "Deskripsi"  );
	
		 
	$submit = array(
		'value' => ( ' Setel ' ),
		'name' => 'simpan', 
		'type'=>'submit','class'=>'main_button'
	);
	$form_submit= form_dynamic($submit);
	 
	
	$view .= form_field_display( $form_submit /*.' '.$form_cancel */, "&nbsp;" );
	$view .= form_footer( );	
 
	return $view;

}


function is_param_select_komponen( $unreg_periode_id , $pay_komponen_gaji_id){
	$query = "SELECT * FROM unreg_salary_komponen 
		WHERE unreg_periode_id = {$unreg_periode_id}
		AND pay_komponen_gaji_id = {$pay_komponen_gaji_id} ";
	$result = my_query($query);
	if(my_num_rows($result) > 0 ){
		return true;
	}
	return false;
}
function is_param_select_status( $unreg_periode_id , $karyawan_status_id){
	$query = "SELECT * FROM unreg_salary_status 
		WHERE unreg_periode_id = {$unreg_periode_id}
		AND karyawan_status_id = {$karyawan_status_id} ";
	$result = my_query($query);
	if(my_num_rows($result) > 0 ){
		return true;
	}
	return false;
}


function is_param_select_agama( $unreg_periode_id , $karyawan_agama_id){
	$query = "SELECT * FROM unreg_salary_agama 
		WHERE unreg_periode_id = {$unreg_periode_id}
		AND karyawan_agama_id = {$karyawan_agama_id} ";
	$result = my_query($query);
	if(my_num_rows($result) > 0 ){
		return true;
	}
	return false;
}


function set_param_validate($id){
	
	return false;
}

function set_param_submit($id){
	
	$datas = array();
	$datas['unreg_periode_code'] = my_type_data_str($_POST['unreg_periode_code']);
	$datas['unreg_periode_name'] = my_type_data_str($_POST['unreg_periode_name']);
	$datas['reguler_periode_based_id']	= my_type_data_int($_POST['reguler_periode_based_id']);
	$datas['periode_start_date']	= my_type_data_str($_POST['periode_start_date']);
	$datas['periode_end_date']	= my_type_data_str($_POST['periode_end_date']); 
	$datas['deskripsi']	= my_type_data_str($_POST['deskripsi']); 
	$datas['status_proses']	= my_type_data_str('current');  
	$datas['user_updated_id']	= my_type_data_int($_SESSION['user_id']);  
	if(isset($_POST['is_pajak']))	$datas['hitung_pajak']	= my_type_data_str('1');  
	else 							$datas['hitung_pajak']	= my_type_data_str('0');
	
	if($id > 0){
		set_agama_update($id);
		set_karyawan_update($id);
		set_komponen_update($id);
		$datas['version'] = my_type_data_function( '(version + 1)' );
		$datas['updated_on']	=  my_type_data_function( 'NOW()' );
		
		return my_update_record( 'unreg_periode' , 'unreg_periode_id' , $id , $datas );
	}
	$datas['version'] = my_type_data_int(0);
	$datas['created_on']	=  my_type_data_function( 'NOW()' );
	$id = my_insert_record( 'unreg_periode' , $datas);
	set_agama_update($id);
	set_karyawan_update($id);
	set_komponen_update($id);
	return true;
}

function set_agama_update($id){
	my_query("DELETE FROM unreg_salary_agama WHERE unreg_periode_id = {$id}");
	$i = 0;
	foreach($_POST['karyawan_agama'] as  $value){
		$i++;
		$datas = array(); 
		$datas['karyawan_agama_id']	=  my_type_data_int( $value );
		$datas['unreg_periode_id']	=  my_type_data_int( $id );
		$datas['created_on']	=  my_type_data_function( 'NOW()' );
		my_insert_record( 'unreg_salary_agama' , $datas);
	}
	return $i;
}

function set_komponen_update($id){
	my_query("DELETE FROM unreg_salary_komponen WHERE unreg_periode_id = {$id}");
	$i = 0;
	foreach($_POST['komponen_gaji'] as  $value){
		$i++;
		$datas = array(); 
		$datas['pay_komponen_gaji_id']	=  my_type_data_int( $value );
		$datas['unreg_periode_id']	=  my_type_data_int( $id );
		$datas['created_on']	=  my_type_data_function( 'NOW()' );
		my_insert_record( 'unreg_salary_komponen' , $datas);
	}
	return $i;
}

function set_karyawan_update($id){
	my_query("DELETE FROM unreg_salary_status WHERE unreg_periode_id = {$id}");
	$i = 0;
	foreach($_POST['karyawan_status'] as  $value){
		$i++;
		$datas = array(); 
		$datas['karyawan_status_id']	=  my_type_data_int( $value );
		$datas['unreg_periode_id']	=  my_type_data_int( $id );
		$datas['created_on']	=  my_type_data_function( 'NOW()' );
		my_insert_record( 'unreg_salary_status' , $datas);
	}
	return $i;
}

function check_current_periode_unreg(){
	$query = "SELECT * FROM unreg_periode WHERE status_proses = 'current' 
	ORDER BY unreg_periode_id DESC LIMIT 1";
	$result = my_query($query);
	if( my_num_rows($result) > 0 ){
		$row = my_fetch_array($result);
		return $row['unreg_periode_id'];
	}
	return 0;
}