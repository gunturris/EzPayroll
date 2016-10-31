<?php

require_once('../../_lib/website_config.php');

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>jqUploader demo</title>
<link rel="stylesheet" type="text/css" media="screen" href="style.css"/>
<script type="text/javascript" src="jquery-1.2.1.min.js"></script>
<script type="text/javascript" src="jquery.flash.js"></script>
<script type="text/javascript" src="jquery.jqUploader.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	$('#example1').jqUploader({background:'FFFFDF',barColor:'FFDD00',allowedExt:'*.avi; *.jpg; *.jpeg; *.png',allowedExtDescr: 'what you want',validFileMessage: 'Thanks, now hit Upload!',endMessage: 'and don\'t you come back ;)',hideSubmit: false});
	$("#example2").jqUploader({
		afterScript:	"redirected.php",
		background:	"FFFFDF",
		barColor:	"64A9F6",
		allowedExt:     "*.avi; *.jpg; *.jpeg; *.png",
		allowedExtDescr: "Images and movies (*.avi; *.jpg; *.jpeg; *.png)"
	});

	$("#example3").jqUploader({background:	"FFFFDF",barColor:	"FF00FF"});
});
</script>
</head>
<body>
<div id="page">
  <h1>jqUploader Demonstration</h1>
  <div id="menu"> <a href="./" title="back to index">What's it?</a>&nbsp; &nbsp; <a href="test.php" class="current" title="demo">Demo</a>&nbsp; &nbsp; <a href="setup.php" title="Installation guide">Installation
      guide</a>&nbsp; &nbsp; <a href="doc.php"  title="Documentation">Documentation</a>&nbsp; &nbsp; <a href="forum/" title="post a comment for help">Contact
      / help!</a> </div>
  <h2>Use Case 1: as part of a form with other input fields</h2>
  <p>Example: send a picture by email, the user must fill in its email and its correspondant
    email address</p>
  <p>In this scenario, When the upload is finished, the jquUploader is replaced by
    a text input field.</p>
  <p>This makes it easy to then submit your form and save the file path to a database
    field for instance, along with the rest of the form fields.</p>
  <p>Note that this behaviour can be switched off by providing a url to the "afterScript" option.
    in such case, after the upload is finished, the flash file will redirect to a
    new page, allowing, for instance, to show the user its uploaded image.</p>
  <p>Note that if you wish to limit the maximum file size, simply add this bit of
    html in your upload form, right before the file input field: <code style="color:#FF00FF">&lt;input
    name="MAX_FILE_SIZE" value="1048576" type="hidden" /></code>. In this way, even
    users without javascript enabled will not have to wait the end of the transfer
    to discover that their file is too heavy according to the server settings. <strong>This
    is for ergonomy, NOT security, so make sure you enforce a filesize check in your
    serverside upload script!</strong> </p>
  <h3>The code</h3>
  <div class="code">
  <code class="javascript" style="white-space:pre" >
	$("#example1").jqUploader({
			background:	"FFFFDF",
			barColor:	"FFDD00",
			allowedExt: "*.avi; *.jpg; *.jpeg; *.png",
			allowedExtDescr: "what you want",
			params: {quality:'low', menu: false},
			validFileMessage: 'Thanks, now hit Upload!',
			endMessage: 'and don\'t you come back ;)',
			hideSubmit: false
			});
	</code> </div>
  <h3>The form</h3>
  <form enctype="multipart/form-data" action="flash_upload.php" method="POST" class="a_form">
    <fieldset>
    <legend>Your name</legend>
    <ol>
      <li>
        <label for="your_email">Your email:</label>
        <input name="your_email" id="your_email" type="text" value="john@doe.com" />
      </li>
    </ol>
    </fieldset>
    <fieldset>
    <legend>Your picture</legend>
    <ol>
      <li id="example1">
        <label for="example1_field">Choose a file to upload: </label>
        <input name="MAX_FILE_SIZE" value="1048576" type="hidden" />
        <input name="myFile"  id="example1_field"  type="file" />
      </li>
    </ol>
    </fieldset>
    <input type="submit" name="submit" value="Send" />
  </form>
  <h2 style="margin-top:2em">Usecase 2: upload and go !</h2>
  <p>In example 2, the optional afterScript is used to point to a page where the
    flash should redirect when the upload is finished.</p>
  <div class="code"> <code class="javascript"  style="white-space:pre">

  	$("#example2").jqUploader({
		afterScript:	"redirected.php",
		background:	"FFFFDF",
		barColor:	"64A9F6",
		allowedExt:     "*.avi; *.jpg; *.jpeg; *.png",
		allowedExtDescr: "Images and movies (*.avi; *.jpg; *.jpeg; *.png)"
	});

  </code> </div>
  <h3>The form</h3>
  <form enctype="multipart/form-data" action="flash_upload.php" method="POST" class="a_form">
    <fieldset>
    <legend>Upload your file</legend>
    <ol>
      <li id="example2">
        <label for="example2_field">Choose a file to upload:</label>
        <input name="myFile2" id="example2_field"  type="file" />
      </li>
    </ol>
    </fieldset>
    <input type="submit"  name="submit" value="Upload File" />
  </form>
  <h2 style="margin-top:2em">Usecase 3: upload and stay !</h2>
  <p>Example 3 is the same as example 1, but there is no redirection.</p>
  <div class="code"> <code class="javascript"> $("#example3").jqUploader({background:	"FFFFDF",barColor:	"FF00FF"}); </code> </div>
  <h3>The form</h3>
  <form enctype="multipart/form-data" action="flash_upload.php" method="POST" class="a_form">
    <fieldset>
    <legend>Upload your file</legend>
    <ol>
      <li id="example3">
        <label for="example3_field">Choose a file to upload:</label>
        <input name="myFile3" id="example3_field"  type="file" />
      </li>
    </ol>
    </fieldset>
    <input type="submit" name="submit" value="Upload File" />
  </form>
</div>
</body>
</html>
<?php
//define("_BBC_PAGE_NAME", "jqUploader Index");
define("_BBCLONE_DIR", $localpath ."stats/");
define("COUNTER", _BBCLONE_DIR . "mark_page.php");
if (is_readable(COUNTER)) include_once(COUNTER);
?>
