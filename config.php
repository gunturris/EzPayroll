<?php
/*
	Pastikan versi PHP adalah 5.2.X atau yang lebih baru
	Dengan MySQL ver 5.x atau yang lebih baru
*/
 
 
/* KONFIGURASI KONEKSI DATABASE */
ini_set("display_errors",0);
/*
 define( "DATABASE_HOST" , "db575379978.db.1and1.com" ); 
 define( "DATABASE_USER" , "dbo575379978" );
 define( "DATABASE_PASSWORD" , "gungun123" );
 define( "DATABASE_NAME" , "db575379978" );
define( "DATABASE_NAME" , "abbascoi_ezpayroll" );
define( "DATABASE_HOST" , "localhost" ); 
define( "DATABASE_USER" , "abbascoi_ezpay" );
define( "DATABASE_PASSWORD" , "ABCDE12345ABCDE12345" ); 
*/
define( "DATABASE_NAME" , "ez_payroll" );
define( "DATABASE_HOST" , "localhost" ); 
define( "DATABASE_USER" , "root" );
define( "DATABASE_PASSWORD" , "123123" ); 

/* KONFIGURASI PAGING */
define("PAGING_PERHALAMAN" , 10);
define("SCROLL_PERHALAMAN" ,  5);

/* KONFIGURASI TEMPLATE LABEL */
define( "_CLIENT_LABEL" , "Gunturris NetMedia");
define( "_FOOTER_LABEL" , "Design by <a href=\"#\">PrintDiGo Designer</a>");

/* WAKTU KERJA*/
define("WT_DATE_START" , "01");
define("WT_DATE_END" , "31");

/* DEFAULT AKSES */
define( "DEFAULT_WEB_URL" ,"welcome"); 
define("_NET_ADDR" ,"../"); 

//DISINI KEBAWAH JANGAN DI EDIT
/* ROOT PATH*/
define( "MY_ROOT_PATH" ,   _NET_ADDR.""); 
define( "MY_FILES_PATH" ,   _NET_ADDR."files/upload/");  
define( "MY_COMPONENT_PATH" ,   _NET_ADDR."components/"); 
define( "PATH_TEMPLATES" ,   "templates/");
define("__TEMPLATE_NAME__" , "sb-admin");
/* FILES PATH*/
define( "PATH_ICON" ,   __TEMPLATE_NAME__. "/icons/"); 
require_once('autoload.php'); 

//INISIASI KODE  