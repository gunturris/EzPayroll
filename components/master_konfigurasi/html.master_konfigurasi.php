<?php
function get_master_konfigurasi_data($label){
	$query = "SELECT `value` FROM pay_master_konfigurasi_pajak WHERE 
		kolom_label = '{$label}' ";
	$result = my_query($query);
	$row = my_fetch_array($result);
	return $row['value'];
}

function set_master_konfigurasi_data($label , $value){
	$value = addslashes($value);
	$query = "UPDATE pay_master_konfigurasi_pajak SET value = '{$value}' WHERE  kolom_label = '{$label}'";
	return my_query($query);
}

function save_update_konfigurasi(){
	
	set_master_konfigurasi_data('NPWP' , $_POST['npwp_organisasi']);
	set_master_konfigurasi_data('Nama WP' , $_POST['nama_wp']);
	set_master_konfigurasi_data('Alamat WP' , $_POST['alamat_wp']);
	set_master_konfigurasi_data('Kota' , $_POST['kota_wp']);
	set_master_konfigurasi_data('Penanda tangan 1' , $_POST['ttd1']);
	set_master_konfigurasi_data('Penanda tangan 2' , $_POST['ttd2']);
	return true;
}

function config_master(){
	$view = form_header( "form_pay_periode_reguler" , "form_pay_periode_reguler"  );
	 
	$npwp_organisasi = array(
			'name'=>'npwp_organisasi',
			'value'=>(isset($_POST['title'])? $_POST['title'] : get_master_konfigurasi_data('NPWP')),
			'id'=>'npwp_organisasi',
			'type'=>'textfield' 
		);
	$form_title = form_dynamic($npwp_organisasi);
	$view .= form_field_display( $form_title  , "Nomor NPWP Organisasi"  );
	
	 
	$nama_wp = array(
		'type'=>'textfield',
		'id'=>'nama_wp',
		'name'=>'nama_wp',
		'value'=>( isset($_POST['nama_wp']) ? $_POST['nama_wp'] :  get_master_konfigurasi_data('Nama WP') ) ,
	);
	$form_nama_wp = form_dynamic($nama_wp  );
	$view .= form_field_display(  $form_nama_wp   , "Nama wajib pajak"    ); 
	 
	$alamat_wp = array(
			'name'=>'alamat_wp',
			'value'=>(isset($_POST['alamat_wp'])? $_POST['alamat_wp'] :  get_master_konfigurasi_data('Alamat WP') ),
			'id'=>'alamat_wp',
			'rows'=>'3' 
		);
	$form_alamat_wp = form_textarea($alamat_wp);
	$view .= form_field_display( $form_alamat_wp  , "Alamat wajib pajak"  );
	
	$kota_wp = array(
		'type'=>'textfield',
		'id'=>'kota_wp',
		'name'=>'kota_wp',
		'value'=>( isset($_POST['kota_wp']) ? $_POST['kota_wp'] :  get_master_konfigurasi_data('Kota') ) ,
	);
	$form_kota_wp = form_dynamic($kota_wp  );
	$view .= form_field_display(  $form_kota_wp   , "Kota wajib pajak"    ); 
	 
	$ttd1 = array(
		'type'=>'textfield',
		'id'=>'ttd1',
		'name'=>'ttd1',
		'value'=>( isset($_POST['ttd1']) ? $_POST['ttd1'] :  get_master_konfigurasi_data('Penanda tangan 1') ) ,
	);
	$form_hari_ttd1 = form_dynamic($ttd1  );
	$view .= form_field_display(  $form_hari_ttd1   , "Penanda tangan 1"    ); 
	  
	$ttd2 = array(
		'type'=>'textfield',
		'id'=>'ttd2',
		'name'=>'ttd2',
		'value'=>( isset($_POST['ttd2']) ? $_POST['ttd2'] :  get_master_konfigurasi_data('Penanda tangan 2') ) ,
	);
	$form_hari_ttd2 = form_dynamic($ttd2  );
	$view .= form_field_display(  $form_hari_ttd2   , "Penanda tangan 1"    ); 
	 
	$opsi_metode_pajak = array(
				'0'=>'Netto',
				'1'=>'Gross up',
			);
	$metode = array( 
		'id'=>'metode',
		'name'=>'metode',
		'value'=>( isset($_POST['metode']) ? 
					$_POST['metode'] :  
					get_master_konfigurasi_data('Metode Pph') ) ,
	);
	$form_metode = form_radiobutton($metode , $opsi_metode_pajak );
	//$view .= form_field_display(  $form_metode   , "Sistem pajak umum di perusahaan"    );
	
	
	$submit = array(
		'value' => ( '  Setup  ' ),
		'name' => 'simpan', 
		'type'=>'submit','class'=>'main_button'
	);
	$form_submit= form_dynamic($submit);
	 
	
	$view .= form_field_display( $form_submit /*.' '.$form_cancel */, "&nbsp;" );
	$view .= form_field_display(   "&nbsp;", "&nbsp;" );
	$view .= form_footer( );	
 
	return $view;
}

function validasi_setel_wp(){
	return false;
}