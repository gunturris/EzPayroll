<?php
/*
	Files generated by Kornea auto 
	On : 2015-03-15 19:33:31
*/	
my_component_load('__jsload' , false);
my_component_load('__paging' , false); 
my_component_load('xl_builder' , false); 
my_component_load('pay_formula' , false);  
my_component_load('pay_proses_komponen_reguler' );
$task = isset($_GET['task']) ? $_GET['task'] : ''; 
$id = isset( $_GET['id'] ) ? $_GET['id']:  0;
$modulname = 'Proses gaji <i class="fa fa-caret-right fa-fw"></i> 
		Reguler <i class="fa fa-caret-right fa-fw"></i> Komponen hasil';

if($task == "excel"){
	$komponen = my_get_data_by_id('pay_komponen_gaji', 'pay_komponen_gaji_id', $id);
	header("Content-Type: application/xls");;
	header("Content-Disposition: attachment;filename=kalkulasi_komponen_{$komponen['pay_komponen_gaji_label']}_".date('Ymd_His').".xls");
	echo excel_komponen_download($id);
	exit;
}elseif($task == "detail"){
	$content = list_karyawan_by_komponen($id); 
}else{
	$content = list_komponen_hasil();
}

generate_my_web($content, $modulname );		