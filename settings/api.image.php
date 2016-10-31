<?php

function image_thumbnail( $resourse , $width , $height , $copy = false ){

}

function image_watermark( $image , $logo , $alpha , $width = 0 , $height = 0 ,  $copy = false ){

}

function image_captcha( $width=80, $height=20,  $length="6" ){
 
	settype($length,"integer");

	//range length 3-9
	if($length>9)
	$length = 9;

	elseif($length < 3)
	$length = 3;

	// create a 100*20 image
	$im = imagecreate($width, $height);

	// white background and blue text
	$bg 		= imagecolorallocate($im, 128, 128, 128);
	$textcolor 	= imagecolorallocate($im, 250, 250, 255);

	$str_random  = md5(rand(1,6000));
	$str_captcha = strtoupper(substr( $str_random, 0, $length ));
	$str_captcha = str_replace("0","5", $str_captcha);
	$str_captcha = str_replace("O","T", $str_captcha);

	$_SESSION['inti_captcha'] =$str_captcha ;

	$col_poly = imagecolorallocate($im, 220,rand( 100,140), 200);

	// draw the polygon
	for($i=0;$i<=rand(7,20);$i++)
	@imageline($im , rand(-4,3), 5*$i , rand(80,170), rand(4,7)*$i, $col_poly);
	// write the string at the top left
	@imagestring($im, 5, 0, 0, " ".$str_captcha, $textcolor);

	// output the image
	header("Content-type: image/png");
	imagejpeg($im); 
	exit;
}



function image_get_info($file) {
  if (!is_file($file)) {
    return FALSE;
  }

  $details = FALSE;
  $data = @getimagesize($file);
  $file_size = @filesize($file);

  if (isset($data) && is_array($data)) {
    $extensions = array('1' => 'gif', '2' => 'jpg', '3' => 'png');
    $extension = array_key_exists($data[2], $extensions) ?  $extensions[$data[2]] : '';
    $details = array('width'     => $data[0],
                     'height'    => $data[1],
                     'extension' => $extension,
                     'file_size' => $file_size,
                     'mime_type' => $data['mime']);
  }

  return $details;
}

 
?>