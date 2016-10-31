<?php
my_component_load('__jsload' , false);
my_component_load('__paging' , false);
my_component_load('__generator_com' );

$headers = array(); 
$headers['Awal'] = "'width'=>'30%','style'=>'text-align:center;'";       
$headers['Akhir']= "'width'=>'30%','style'=>'text-align:center;'";       
$headers['Status']= "'width'=>'35%','style'=>'text-align:center;'";       
$headers['Aksi'] = "'width'=>'5%','style'=>'text-align:center;'"; 
$module_name = "wt_periode";
 
generate_files($module_name,  $headers) ;
exit;