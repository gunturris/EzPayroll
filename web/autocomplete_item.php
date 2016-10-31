<?php
require_once('../config.php');

$query = "SELECT item_code , item_name FROM item WHERE item_code LIKE '{$_GET['q']}%' ORDER BY RAND() LIMIT 10";
$result = my_query($query);
while($rw = my_fetch_array($result)){
	echo strtoupper($rw['item_code']."/".$rw['item_name'])."\n";
}