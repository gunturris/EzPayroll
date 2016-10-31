<?php

function informasi_akhir_proses_reguler(){
	my_set_file_js(
		array(
			'assets/jquery/combomulti/jquery.chainedSelects.js',
			'assets/js/calendar/calendarDateInput.js' 
		)
	);
	$view = form_header( "form_pay_periode_reguler" , "form_pay_periode_reguler"  );
	$fields = get_data_periode_id();
 
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
	
	$title = array(
			'name'=>'title',
		'disabled'=>'disabled',
			'value'=>(isset($_POST['title'])? $_POST['title'] : $fields['title']),
			'id'=>'title',
			'type'=>'textfield' 
		);
	$form_title = form_dynamic($title);
	$view .= form_field_display( $form_title .$form_task .$form_com . $form_sessid , "Nama proses gaji"  );
	
	

	$last_periode_end_date = get_last_date_periode(); 
	$first_current_date = strtotime($last_periode_end_date) + ( 24*60*60 );
	$first_current_date_form = array(
			'name'=>'periode_start',
			'value'=>(  date('Y-m-d' , $first_current_date )),
			'id'=>'periode_start',
			'type'=>'textfield' ,
			'disabled'=>'disabled'
		);
	$form_first_current_date_form = form_dynamic($first_current_date_form);
	$view .= form_field_display( $form_first_current_date_form  , "Awal periode" );
	 
	$periode_end = array(
			'name'=>'periode_end',
			'value'=>(isset($_POST['periode_end'])? $_POST['periode_end'] : $fields['periode_end']),
			'id'=>'periode_end',
			'type'=>'textfield',
			'size'=>'45',
			'disabled'=>'disabled'
		);
	$form_periode_end = form_dynamic($periode_end);
	$view .= form_field_display( $form_periode_end  , "Akhir periode" );
	
 
	$payroll_date = array(
			'name'=>'payroll_date',
			'value'=>(isset($_POST['payroll_date'])? $_POST['payroll_date'] : $fields['payroll_date']),
			'id'=>'payroll_date',
			'type'=>'textfield',
			'size'=>'45',
			'disabled'=>'disabled'
		);
	$form_payroll_date = form_dynamic($payroll_date);
	$view .= form_field_display( $form_payroll_date  , "Tanggal gajian " );
	
 
	$hari_kerja = array(
		'type'=>'textfield',
		'id'=>'hari_kerja',
		'name'=>'hari_kerja',
		'disabled'=>'disabled',
		'value'=>( isset($_POST['hari_kerja']) ? $_POST['hari_kerja'] : $fields['hari_kerja']) ,
	);
	$form_hari_kerja = form_dynamic($hari_kerja  );
	$view .= form_field_display(  $form_hari_kerja   , "Jumlah hari kerja"    ); 
	  
	$view .= form_field_display('<p>'. nl2br($fields['deskripsi'] ) .'</p>' , "Deskripsi"  );
	
		 
	$submit = array(
		'value' => ( ' Tutup bulan ' ),
		'name' => 'simpan', 
		'type'=>'submit','class'=>'main_button'
	);
	$form_submit= form_dynamic($submit);
	 
	
	$view .= form_field_display( $form_submit /*.' '.$form_cancel */, "&nbsp;" );
	$view .= form_footer( );	
 
	return str_replace('post','get',$view);
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

function proses_tutup_bulan(){
	$periode = get_data_periode_id();
	if(session_id() == $_GET['sessid']){
	
		$query 	= "SELECT * FROM karyawan ";
		$result = my_query($query);
		$i = 1;
		$p = new ProgressBar();
		echo '<center><div style="width: 400px;">';
		$p->render();
		echo '</div></center>';
		$size = 3 + my_num_rows($result);
		//SAVE LOG KOMPONEN
		log_payroll_komponen($periode['pay_periode_reguler_id'] , $periode['payroll_date']);
		$p->setProgressBarProgress($i*100/$size); 
		$i++;
		
		//SAVE BANK TRANSFER
		log_payroll_bank_transfer($periode['pay_periode_reguler_id'] , $periode['payroll_date']);
		$p->setProgressBarProgress($i*100/$size); 
		$i++;
		
		while($row = my_fetch_array($result)){
			save_pajak($row['karyawan_id'] , $periode['pay_periode_reguler_id'] , $periode['payroll_date']);
			log_payroll_pajak($periode['pay_periode_reguler_id'] , $periode['payroll_date'] , $row );
			$p->setProgressBarProgress($i*100/$size); 
			$i++;
		}
		truncate_data_proses();
		set_new_periode( $periode['pay_periode_reguler_id'] );
		$p->setProgressBarProgress($i*100/$size); 
		$i++;
		
		
		
		$p->setProgressBarProgress(100);
		return $i;
	}	
	return false;
}

function set_new_periode($periode_current_id){
	$query = "
		UPDATE pay_periode_reguler 
			SET status_proses  = 'Closed' 
		WHERE pay_periode_reguler_id > 0";
	my_query($query);
	
	$last_periode_end_date = get_last_date_periode(); 
	$first_current_date = strtotime($last_periode_end_date) + ( 24*60*60 );
	 
	$datas = array();  
	$datas['periode_start']	=  my_type_data_str(date('Y-m-d' , $first_current_date)); 
	$datas['user_updated_id']	= my_type_data_int($_SESSION['user_id']);  
	$datas['status_proses']	= my_type_data_str('Current');  
	$datas['created_on']	= my_type_data_function('NOW()');
	$datas['version'] = my_type_data_str(0);
	return my_insert_record( 'pay_periode_reguler' , $datas );
	
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

function truncate_data_proses(){
	my_query('TRUNCATE temp_kalkulasi_pajak');
	my_query('TRUNCATE temp_kalkulasi_pajak_gross_up');
	my_query('TRUNCATE temp_pay_rekening_nominal');
	my_query('TRUNCATE temp_pay_kalkulasi');
	return true;
}


function log_payroll_pajak($periode_payroll_id , $payroll_date , $karyawan ){

	if(! is_array($karyawan) )return false;
	$metode = array( '0'=>'Neto','1'=>'Gross' );
	$pajak_referensi = ( $karyawan['metode_pajak'] == '1' ) ? 'temp_kalkulasi_pajak_gross_up'  : 'temp_kalkulasi_pajak' ;
	
	$metode_pajak = $metode[$karyawan['metode_pajak']];
	$query = "INSERT INTO log_payroll_reguler_pajak ";
	$query .= "(
				karyawan_id , karyawan_nik , karyawan_nama , karyawan_metode_pajak ,
				pajak_komponen_id , pajak_nama , nominal_hitung ,
				pay_periode_id , pay_periode_date ,
				created_on , user_updated_id
			)";
	$query .= "SELECT 
					{$karyawan['karyawan_id']} , '{$karyawan['karyawan_nik']}' , '{$karyawan['nama_karyawan']}' , '{$metode_pajak}' ,
					a.pay_komponen_pajak_id , b.label_pajak , ROUND(a.nominal_pajak) , 
					 {$periode_payroll_id} , '{$payroll_date}' , 
					 NOW() , {$_SESSION['user_id']} 
				FROM `{$pajak_referensi}` a 
				INNER JOIN pay_komponen_pajak b 
					ON a.pay_komponen_pajak_id = b.komponen_pajak_id
				WHERE a.karyawan_id = {$karyawan['karyawan_id']}
					ORDER BY a.karyawan_id ASC , a.pay_komponen_pajak_id ASC
					";
	return my_query($query);
}

function log_payroll_komponen($periode_payroll_id , $payroll_date){
	$query = "INSERT INTO log_payroll_reguler_komponen ";
	$query .= "(	karyawan_id , karyawan_nik , karyawan_nama , pay_komponen_gaji_id , 
					pay_komponen_gaji_kode , pay_komponen_gaji_type , pay_komponen_gaji_name ,
					pay_komponen_nominal , pay_periode_id , pay_periode_date ,
					created_on , user_updated_id	)";
	$query .= "SELECT 
					a.karyawan_id , b.karyawan_nik , b.nama_karyawan , a.pay_komponen_gaji_id ,
					c.pay_komponen_gaji_code , c.type , c.pay_komponen_gaji_label , 
					ROUND(a.nominal_hitung) , {$periode_payroll_id} , '{$payroll_date}' , NOW() , {$_SESSION['user_id']} 
				FROM temp_pay_kalkulasi a
				INNER JOIN karyawan b ON a.karyawan_id = b.karyawan_id 
				INNER JOIN pay_komponen_gaji c 
				ON a.pay_komponen_gaji_id = c.pay_komponen_gaji_id
				ORDER BY a.karyawan_id
					";
	return my_query($query);
}

function log_payroll_bank_transfer($periode_payroll_id , $payroll_date){
	$query = "INSERT INTO log_payroll_reguler_bank_transfer ";
	$query .= "(	karyawan_id , karyawan_nik , karyawan_nama ,
					bank_id , bank_nama , bank_detail , 
					rekening_id , rekening_nama , rekening_nomor , 
					persen , nominal_transfer ,
					pay_periode_id , pay_periode_date ,
					created_on , user_updated_id	)";
	$query .= "SELECT 
					b.karyawan_id , c.karyawan_nik , c.nama_karyawan , 
					b.bank_id , d.bank_name , b.bank_detail ,
					a.rekening_id , b.account_name , b.account_number , 
					a.persen , ROUND(a.nominal) , 
					{$periode_payroll_id} , '{$payroll_date}' , 
					NOW() , {$_SESSION['user_id']} 
				FROM temp_pay_rekening_nominal a
				INNER JOIN karyawan_bank_account b ON a.rekening_id = b.rekening_id 
				INNER JOIN karyawan c ON b.karyawan_id = c.karyawan_id   
				INNER JOIN bank d ON b.bank_id = d.bank_id   
				ORDER BY b.karyawan_id
					";
	return my_query($query);
}

function save_pajak($karyawan_id , $periode_payroll_id ,$payroll_date){
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
	$query = "INSERT INTO log_payroll_reguler_komponen ";
	$query .= "(	karyawan_id , karyawan_nik , karyawan_nama , pay_komponen_gaji_id , 
					pay_komponen_gaji_kode , pay_komponen_gaji_type , pay_komponen_gaji_name ,
					pay_komponen_nominal , pay_periode_id , pay_periode_date ,
					created_on , user_updated_id	)";
	$query .= "VALUES(
				{$karyawan['karyawan_id']} , '{$karyawan['karyawan_nik']}' , 
				'{$karyawan['nama_karyawan']}' , 100 ,
					'{$comp_code}' , '{$type}' , 'Pajak penghasilan' , 
					'{$nominal}' , {$periode_payroll_id} , '{$payroll_date}' ,
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
 