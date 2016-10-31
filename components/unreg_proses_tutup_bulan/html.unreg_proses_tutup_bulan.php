<?php

function informasi_akhir_proses_unreguler($id){ 
	my_set_file_js(
		array(
			'assets/jquery/combomulti/jquery.chainedSelects.js',
			'assets/js/calendar/calendarDateInput.js' 
		)
	);
	$view = form_header( "form_pay_unreguler" , "form_pay_unreguler"  );
	$fields = my_get_data_by_id('unreg_periode','unreg_periode_id', $id);
 
	$sessid = array(
			'name'=>'sessid', 
			'value'=>session_id(), 
			'type'=>'hidden' 
		);
	$form_sessid = form_dynamic($sessid);
	
	$com = array(
			'name'=>'com', 
			'value'=>$_GET['com'], 
			'type'=>'hidden' 
		);
	$form_com = form_dynamic($com);
	
	
	$task = array(
			'name'=>'task', 
			'value'=>'tutup_bulan', 
			'type'=>'hidden' 
		);
	$form_task = form_dynamic($task);
	
	$code = array(
			'name'=>'unreg_periode_code',
			'value'=>(isset($_POST['unreg_periode_code'])? $_POST['unreg_periode_code'] : $fields['unreg_periode_code']),
			'id'=>'unreg_periode_code',
			'type'=>'textfield' ,
		'disabled'=>'disabled',
			'size' =>'15'
		);
	$form_code = form_dynamic($code);
	$view .= form_field_display( $form_code .$form_task.$form_com . $form_sessid, "Kode gaji unreguler"  );
	
	
	
	$title = array(
			'name'=>'unreg_periode_name',
		'disabled'=>'disabled',
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
			'value'=>( isset($fields['reguler_periode_based_id']) ? $pay_periodes[$fields['reguler_periode_based_id']] : 0 ),
			'id'=>'reguler_periode_based_id',
			'type'=>'textfield' ,
			'disabled'=>'disabled',
		);
	$form_reguler_periode_based_id = form_dynamic($reguler_periode_based_id  );
	$view .= form_field_display( $form_reguler_periode_based_id  , "Dasar perhitungan gaji reguler"  );
	
	$start_date = array(
			'name'=>'periode_start_date',
			'value'=> (isset($_POST['periode_start_date'])? $_POST['periode_start_date'] :($fields ? date('Y-m-d', strtotime($fields['periode_start_date'])) : date('Y-m-d' ))),
			'id'=>'periode_start_date',
			'type'=>'textfield' , 
			'disabled'=>'disabled',
		);
	$form_first_current_date_form = form_dynamic($start_date);
	$view .= form_field_display( $form_first_current_date_form  , "Awal periode" );
	
	 
	$periode_end = array(
			'name'=>'periode_end_date',
			'value'=>(isset($_POST['periode_end_date'])? $_POST['periode_end_date'] :($fields ? date('Y-m-d', strtotime($fields['periode_end_date'])) : date('Y-m-d' ))),
			'id'=>'periode_end_date',
			'type'=>'textfield',
			'disabled'=>'disabled', 
		);
	$form_periode_end = form_dynamic($periode_end);
	$view .= form_field_display( $form_periode_end  , "Akhir periode" );
	

	$payment_date  = array(
			'name'=>'payment_date',
			'value'=>(isset($_POST['payment_date'])? $_POST['payment_date'] : ($fields ? date('Y-m-d', strtotime($fields['payment_date'])) : date('Y-m-d' ))),
			'id'=>'payment_date',
			'type'=>'textfield', 
			'disabled'=>'disabled', 
		);
	$form_payment_date = form_dynamic($payment_date);
	$view .= form_field_display( $form_payment_date  , "Tanggal bayar" );
	
 
	$option_multiple_agama = '
	<select multiple class="form-control" name="karyawan_agama[]" readonly="readonly">';
	$rquery = "SELECT * FROM karyawan_agama ";
	$rresult = my_query($rquery);
	while($rrpw = my_fetch_array($rresult) ){
		if( is_param_select_agama( $id , $rrpw['karyawan_agama_id'] ) )
			$option_multiple_agama .= '<option   value="'.$rrpw['karyawan_agama_id'].'">'.strtoupper($rrpw['karyawan_agama_label']).'</option>';
	}
	$option_multiple_agama .= '</select> ';
	$view .= form_field_display(  $option_multiple_agama   , "Dasar keagamaan"    ); 
		
	$option_multiple = '
	<select multiple class="form-control" name="karyawan_status[]" readonly="readonly">';
	$rquery = "SELECT * FROM karyawan_status ORDER BY karyawan_status_id ASC";
	$rresult = my_query($rquery);
	while($rrpw = my_fetch_array($rresult) ){
		if( is_param_select_status( $id , $rrpw['karyawan_status_id'] ) )
			$option_multiple .= '<option   value="'.$rrpw['karyawan_status_id'].'">'.strtoupper($rrpw['karyawan_status_label']).'</option>';
	}
	$option_multiple .= '</select> ';
	$view .= form_field_display(  $option_multiple   , "Berlaku untuk status karyawan"    ); 
		
	$option_multiple_komponen = '
	<select multiple class="form-control" name="komponen_gaji[]"  readonly="readonly">';
	$rquery = "SELECT * FROM pay_komponen_gaji ORDER BY pay_komponen_gaji_id ASC";
	$rresult = my_query($rquery);
	while($rrpw = my_fetch_array($rresult) ){
		if( is_param_select_komponen( $id , $rrpw['pay_komponen_gaji_id'] ) )
			$option_multiple_komponen .= '<option   value="'.$rrpw['pay_komponen_gaji_id'].'">'.strtoupper($rrpw['pay_komponen_gaji_label']).'</option>';
	}
	$option_multiple_komponen .= '</select> ';
	$view .= form_field_display(  $option_multiple_komponen   , "Komponen hitung"    ); 
	
	$checked = $fields ? ( ($fields['hitung_pajak'] == '1') ? ' checked="checked" ' : '' ) : '' ;
	$view .= form_field_display(  '<br /><input type="checkbox" name="is_pajak" value="1" '.$checked.'  disabled="disabled" /> Ya, sesuai net/gross masing-masing', "Di kenakan pajak"    ); 
	 
	$deskripsi = array(
			'name'=>'deskripsi',
			'value'=>(isset($_POST['deskripsi'])? $_POST['deskripsi'] : $fields['deskripsi']),
			'id'=>'deskripsi',
			'rows'=>'3' ,
			'readonly'=>'readonly',
		);
	$form_deskripsi = '<div style="padding:3px;background-color:#EDEDED;border:1px solid grey; min-height:60px;">'.$fields['deskripsi'].'</div>';
	$view .= form_field_display( $form_deskripsi  , "Deskripsi"  );
	
		 
	$submit = array(
		'value' => ( ' Setel ' ),
		'name' => 'simpan', 
		'type'=>'submit','class'=>'main_button'
	);
	$form_submit= form_dynamic($submit);
	 
	
	$view .= form_field_display( $form_submit /*.' '.$form_cancel */, "&nbsp;" );
	$view .= form_footer( );	
  
	return str_replace('post','get',$view);
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
function get_data_periode_id(){
	$query = "SELECT * FROM pay_periode_reguler";
	$query .= " WHERE status_proses = 'current' ORDER BY pay_periode_reguler_id ASC LIMIT 1";
	$result = my_query($query);
	$row = my_fetch_array($result);
	return $row;
}


function get_last_date_periode(){
	$query = "SELECT periode_end 
			FROM pay_periode_reguler 
			WHERE status_proses = 'Closed' 
			ORDER BY pay_periode_reguler_id DESC LIMIT 0,1";
	$result = my_query($query);
	$row = my_fetch_array($result);
	return $row['periode_end'];
}

function proses_tutup_bulan_unreg($id){
	$periode = my_get_data_by_id('unreg_periode','unreg_periode_id', $id);
	if(session_id() == $_GET['sessid']){
	
		$query 	= " SELECT karyawan_id FROM temp_unreg_kalkulasi 
			GROUP BY karyawan_id  ";
		$result = my_query($query);
		$i = 1;
		$p = new ProgressBar();
		echo '<center><div style="width: 400px;">';
		$p->render();
		echo '</div></center>';
		$size = 3 + my_num_rows($result);
		//SAVE LOG KOMPONEN
		log_payroll_komponen($periode['unreg_periode_id'] , $periode['payment_date']);
		$p->setProgressBarProgress($i*100/$size); 
		$i++;
		
		//SAVE BANK TRANSFER
		log_payroll_bank_transfer($periode['unreg_periode_id'] , $periode['payment_date']);
		 
		$p->setProgressBarProgress($i*100/$size); 
		$i++;
		
		while($row = my_fetch_array($result)){
			save_komponen_pajak_unreg($row['karyawan_id'] , $periode['unreg_periode_id'] , $periode['payment_date']);
			log_payroll_pajak_unreg($periode['unreg_periode_id'] , $periode['payment_date'] , $row['karyawan_id'] );
			$p->setProgressBarProgress($i*100/$size); 
			$i++;
		}
		
		truncate_data_proses_unreg($periode['unreg_periode_id']); 
		$p->setProgressBarProgress($i*100/$size); 
		$i++;
		 
		$p->setProgressBarProgress(100);
		return $i;
	}	
	return false;
}
 

function get_last_date_periode_new(){
	$query = "SELECT periode_end 
			FROM pay_periode_reguler 
			WHERE status_proses = 'Closed' 
			ORDER BY pay_periode_reguler_id DESC LIMIT 0,1";
	$result = my_query($query);
	$row = my_fetch_array($result);
	return $row['periode_end'];
}

function truncate_data_proses_unreg($unreg_periode_id){
	my_query('TRUNCATE temp_unreg_rekening_nominal');
	my_query('TRUNCATE temp_unreg_kalkulasi_pajak');
	my_query('TRUNCATE temp_unreg_kalkulasi_pajak_gross_up');
	my_query('TRUNCATE temp_unreg_kalkulasi');
	my_query("UPDATE unreg_periode SET status_proses = 'closed' 
		WHERE unreg_periode_id = {$unreg_periode_id} AND status_proses = 'current' ");
	return true;
}


function log_payroll_pajak_unreg($unreg_periode_id , $payroll_date , $karyawan_id ){
	$karyawan = loaddata_karyawan($karyawan_id);
	if(! is_array($karyawan) )return false;
	$metode = array( '0'=>'Neto','1'=>'Gross' );
	$pajak_referensi = ( $karyawan['metode_pajak'] == '1' ) ? 'temp_unreg_kalkulasi_pajak_gross_up'  : 'temp_unreg_kalkulasi_pajak' ;
	
	$metode_pajak = $metode[$karyawan['metode_pajak']];
	$query = "INSERT INTO log_payroll_unreguler_pajak ";
	$query .= "(
				karyawan_id , karyawan_nik , karyawan_nama , karyawan_metode_pajak ,
				pajak_komponen_id , pajak_nama , nominal_hitung ,
				unreg_periode_id , unreg_periode_date ,
				created_on , user_updated_id
			)";
	$query .= "SELECT 
					{$karyawan['karyawan_id']} , '{$karyawan['karyawan_nik']}' , 
					'{$karyawan['nama_karyawan']}' , '{$metode_pajak}' ,
					a.komponen_pajak_id , b.label_pajak , ROUND(a.nominal_pajak) , 
					 {$unreg_periode_id} , '{$payroll_date}' , 
					 NOW() , {$_SESSION['user_id']} 
				FROM `{$pajak_referensi}` a 
				INNER JOIN pay_komponen_pajak b 
					ON a.komponen_pajak_id = b.komponen_pajak_id
				WHERE a.karyawan_id = {$karyawan['karyawan_id']}
					ORDER BY a.karyawan_id ASC , a.komponen_pajak_id ASC
					";
	return my_query($query);
}

function log_payroll_komponen($unreg_periode_id , $payroll_date){
	$query = "INSERT INTO log_payroll_unreguler_komponen ";
	$query .= "(	karyawan_id , karyawan_nik , karyawan_nama , pay_komponen_gaji_id , 
					pay_komponen_gaji_code , pay_komponen_gaji_type , pay_komponen_gaji_name ,
					pay_komponen_nominal , unreg_periode_id , pay_periode_date ,
					created_on , user_updated_id	)";
	$query .= "SELECT 
					a.karyawan_id , b.karyawan_nik , b.nama_karyawan , 
					a.komponen_gaji_id ,
					c.pay_komponen_gaji_code , c.type , c.pay_komponen_gaji_label , 
					ROUND(a.nominal_hitung) , {$unreg_periode_id} , '{$payroll_date}' ,
					NOW() , {$_SESSION['user_id']} 
				FROM temp_unreg_kalkulasi a
				INNER JOIN karyawan b ON a.karyawan_id = b.karyawan_id 
				INNER JOIN pay_komponen_gaji c 
				ON a.komponen_gaji_id = c.pay_komponen_gaji_id
				ORDER BY a.karyawan_id
					"; 
	return my_query($query);
}

function log_payroll_bank_transfer($unreg_periode_id , $payroll_date){
	$query = "INSERT INTO log_payroll_unreguler_bank_transfer ";
	$query .= "(	karyawan_id , karyawan_nik , karyawan_nama ,
					bank_id , bank_nama , bank_detail , 
					rekening_id , rekening_nama , rekening_nomor , 
					persen , nominal_transfer ,
					unreg_periode_id , unreg_periode_date ,
					created_on , user_updated_id	)";
	$query .= "SELECT 
					b.karyawan_id , c.karyawan_nik , c.nama_karyawan , 
					b.bank_id , d.bank_name , b.bank_detail ,
					a.rekening_id , b.account_name , b.account_number , 
					a.persen , ROUND(a.nominal) , 
					{$unreg_periode_id} , '{$payroll_date}' , 
					NOW() , {$_SESSION['user_id']} 
				FROM temp_unreg_rekening_nominal a
				INNER JOIN karyawan_bank_account b ON a.rekening_id = b.rekening_id 
				INNER JOIN karyawan c ON b.karyawan_id = c.karyawan_id   
				INNER JOIN bank d ON b.bank_id = d.bank_id   
				ORDER BY b.karyawan_id
					";print($query);exit;
	return my_query($query);
}

function save_komponen_pajak_unreg($karyawan_id , $unreg_periode_id ,$payroll_date){
	$karyawan = loaddata_karyawan( $karyawan_id );
	if($karyawan['metode_pajak'] == 1){
		$nominal = get_pph_netto($karyawan_id);
		$type = 'subsidi';
		$comp_code = '0000';
	}else{
		$nominal = get_pph_gross($karyawan_id);
		$type = 'potongan';
		$comp_code = '5000';
	}
	$query = "INSERT INTO log_payroll_unreguler_komponen ";
	$query .= "(	karyawan_id , karyawan_nik , karyawan_nama , pay_komponen_gaji_id , 
					pay_komponen_gaji_code , pay_komponen_gaji_type , pay_komponen_gaji_name ,
					pay_komponen_nominal , unreg_periode_id , pay_periode_date ,
					created_on , user_updated_id	)";
	$query .= "VALUES(
				{$karyawan['karyawan_id']} , '{$karyawan['karyawan_nik']}' , 
				'{$karyawan['nama_karyawan']}' , 100 ,
					'{$comp_code}' , '{$type}' , 'Pajak penghasilan' , 
					'{$nominal}' , {$unreg_periode_id} , '{$payroll_date}' ,
					NOW() , {$_SESSION['user_id']} 
			)";
	return my_query($query);
}


function get_pph_gross($karyawan_id){
	$query = "SELECT nominal_pajak FROM temp_kalkulasi_pajak_gross_up
		WHERE pay_komponen_pajak_id = 2 
		AND karyawan_id = {$karyawan_id}";
	$result = my_query($query);
	$row = my_fetch_array($result);
	return $row['nominal_pajak'];
}
function get_pph_netto($karyawan_id){
	$query = "SELECT nominal_pajak FROM temp_kalkulasi_pajak 
		WHERE pay_komponen_pajak_id = 23
		AND karyawan_id = {$karyawan_id}";
	$result = my_query($query);
	$row = my_fetch_array($result);
	return $row['nominal_pajak'];
} 
 