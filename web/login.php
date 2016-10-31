<?php  
require_once('../config.php'); 
 
if(isset($_GET['logout'])){  
	log_data('OUT');
	unset($_SESSION['user_id']);
	session_destroy();

	my_direct("index.php");
}
if(isset($_SESSION['user_id'])){
	my_direct("index.php");
}
$errormsg = false;

 

function anti_injection( $user   ) {
	   // We'll first get rid of any special characters using a simple regex statement.
	   // After that, we'll get rid of any SQL command words using a string replacment.
		$banlist = array (
				"insert", "select", "update", "delete", "distinct", "having", "truncate", "replace",
				"handler", "like", " as ", "or ", "procedure", "limit", "order by", "group by", "asc", "desc"
		);
		// ---------------------------------------------
		if ( eregi ( "[a-zA-Z0-9]+", $user ) ) {
				$user = trim ( str_replace ( $banlist, '', strtolower ( $user ) ) );
		} else {
				$user = NULL;
		}  
		
		return addslashes($user);
}

function log_data($status){
	$datas = array(
		'user_id'=>my_type_data_int($_SESSION['user_id']),
		'status'=>my_type_data_str($status),
		'log_time'=>my_type_data_function('NOW()'),
		'visit_from'=>my_type_data_str($_SERVER['REMOTE_ADDR']),
		'environtment'=>my_type_data_str( print_r( $_SERVER ,true) ),
	);
	return my_insert_record('log_login_access' , $datas);
}
	
if( $_SERVER['REQUEST_METHOD'] == "POST" ){
$username = str_replace(" ","",$_POST['username']); 
$username = anti_injection( $username  );
 
	$password = md5(trim($_POST['password'].'@t'));
	$query = "
		SELECT  user_id  FROM apps_user 
			WHERE username ='$username'   
			AND password='{$password}' 
			AND level_id > 0 ";
 
	$result = my_query($query);
	if(my_num_rows($result) > 0 ){
		if($row= my_fetch_array($result )){ 
			$_SESSION['user_id'] = $row['user_id'];
			log_data('IN');
			if(isset($_GET['pr']))
				my_direct($_GET['pr']);
			else
				my_direct('index.php');
		}
	}else{
		$errormsg = "Invalid Login!";
	}
} 
?><!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>eZ Penggajian - Penggajian elektronik sederhana Indonesia</title>

    <!-- Bootstrap Core CSS -->
    <link href="templates/sb-admin/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="templates/sb-admin/css/plugins/metisMenu/metisMenu.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="templates/sb-admin/css/sb-admin-2.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="templates/sb-admin/font-awesome-4.1.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Login pengguna</h3>
                    </div>
                    <div class="panel-body">
                        <form role="form" name="submit_login" method="POST">
                            <fieldset>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Username" name="username" type="username" autofocus>
                                </div>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Password" name="password" type="password" value="">
                                </div>
                                <div class="checkbox">
                                    <label>
                                        <input name="remember" type="checkbox" value="Remember Me">Ingat komputer ini
                                    </label>
                                </div>
                                <!-- Change this to a button or input when using this as a form -->
                                <a href="javascript:;" onclick="javascript:document.submit_login.submit()" class="btn btn-lg btn-success btn-block">Login</a>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="templates/sb-admin/font-awesome-4.1.0/js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="templates/sb-admin/font-awesome-4.1.0/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="templates/sb-admin/font-awesome-4.1.0/js/plugins/metisMenu/metisMenu.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="templates/sb-admin/font-awesome-4.1.0/js/sb-admin-2.js"></script>

</body>

</html>