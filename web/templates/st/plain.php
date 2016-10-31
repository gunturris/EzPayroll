<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	<title>Active Logger :: <?php echo str_replace('_BN_','-',$sidebar); ?></title>
	<link rel="stylesheet" href="<?php echo my_template_position(); ?>/css/style.css" type="text/css" media="all" />
	<link rel="stylesheet" href="<?php echo my_template_position(); ?>/css/dropdownst.css" type="text/css" media="all" />

<script src="assets/jquery/jquery.js" type="text/javascript"></script>
<?php  
$com = $_GET['com'];

//if(defined('JS_LIST'))print JS_LIST; 
if(isset($js_file))print $js_file;
?>
<?php  
//if(defined('JS_LIST'))print JS_LIST; 
if(isset($css_file))print $css_file;
?>
<script language="Javascript">
<?php
//if(defined('JS_CODE'))print JS_CODE;
if(isset($js_code))print $js_code;
?>
<?php
if(isset($js_jquery_code))
print  '$(document).ready(function() {
'.
$js_jquery_code
.
'})';
?>
</script>
<style>
<?php
//if(defined('CSS_CODE'))print CSS_CODE; 
if(isset($css_code))print $css_code;
?>
</style>
	
	</head>
<body> 

<!-- Container -->
<div id="container" style="padding:5px;"> 
		  
			<!-- Content -->
			<div>
				<?php echo $content; ?>

			</div>
			<!-- End Content --> 
</div>
<!-- End Container -->
 
	
</body>
</html>