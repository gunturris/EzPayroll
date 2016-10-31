<?php  
my_component_load( '__viewapi',false);
$task = isset( $_GET['task'] ) ? $_GET['task'] : "";
require_once('html2pdf.class.php');
// récupération de l'html 
ob_start(); 
if($task == "surat_pengiriman"): 
	$path = 'files/nodo/'; 
	if(! is_dir($path))mkdir($path ,0777,true);
	include(MY_COMPONENT_PATH.'__report/result/surat_pengiriman.php'); 
	$content = ob_get_clean(); 
	$html2pdf = new HTML2PDF('L','A4','fr', array(5, 5, 12,  5));
	$html2pdf->pdf->SetDisplayMode('fullpage'); 
	$html2pdf->WriteHTML($content, isset($_GET['vuehtml'])); 
	$html2pdf->Output($path . date('Ymd').'_'.$_GET['no_do'].'.pdf' ,'F'); 
	exit; 
 
elseif($task == "sertifikat"):
	if(!isset($_GET['id']))my_direct("index.php");
	include(MY_COMPONENT_PATH.'__report/result/sertifikat.php');  
	$content = ob_get_clean(); 
	$html2pdf = new HTML2PDF('P','A4','fr', array(8,0,0,0));
	$html2pdf->pdf->SetDisplayMode('fullpage'); 
	$html2pdf->WriteHTML($content, isset($_GET['vuehtml'])); 
	$html2pdf->Output(md5(rand(0,2000)).'.pdf'); 
	exit;  
	  
 elseif($task == "laporan_resume"):
	if(!isset($_GET['id']))my_direct("index.php");
	include(MY_COMPONENT_PATH.'__report/result/laporan_resume.php');
	$content = ob_get_clean(); 
	$html2pdf = new HTML2PDF('P','Legal','fr', array(6, 10, 6,  5));
	$html2pdf->pdf->SetDisplayMode('fullpage'); 
	$html2pdf->WriteHTML($content, isset($_GET['vuehtml'])); 
	$html2pdf->Output(md5(rand(0,2000)).'.pdf'); 
	exit; 
 
else:
	 my_direct("index.php"); 
 
endif;