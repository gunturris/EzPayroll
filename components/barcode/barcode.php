<?php 
my_component_load('barcode');
$ref = isset($_GET['ref']) ?$_GET['ref'] : 0;
echo 'components/barcode/barcodegen/html/image.php?code=code11&o=1&dpi=72&t=30&r=1&rot=0&text='.$ref.'&f1=-1&f2=8&a1=&a2=&a3="';