<?php


require_once("../autoload.php");
$datas = array( 
  'log_cron_access_time' => my_type_data_function('NOW()'),
  'respon'	=> my_type_data_str(md5(rand(0,99999))) 
);

return my_insert_record('log_cron_access' , $datas);