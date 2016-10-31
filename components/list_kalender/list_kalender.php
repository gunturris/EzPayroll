<?php 
my_component_load('list_kalender');

 $datas = list_kalender('20-06-2010', '30-06-2010');  
$content ='';
foreach($datas as $data){
	$content .=($data  )  ;
}
generate_my_web($content, 'sasa'  );