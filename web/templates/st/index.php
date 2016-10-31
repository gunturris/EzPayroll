<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	<title>Working hour :: <?php echo str_replace('_BN_','-',$sidebar); ?></title>
	<link rel="stylesheet" href="<?php echo my_template_position(); ?>/css/style.css" type="text/css" media="all" />
	<link rel="stylesheet" href="<?php echo my_template_position(); ?>/css/dropdownst.css" type="text/css" media="all" />

<script src="assets/jquery/jquery.js" type="text/javascript"></script>
<?php  
$com = $_GET['com'];
$user = my_get_data_by_id('user','user_id',$_SESSION['user_id']);
$periode = get_periode_aktif(); 
$periode_tutup_bulan = date('m-Y', strtotime($periode['periode_sampai']) );
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

function tutupBulan(){
	var t = confirm("Melakukan tutup bulan  <?php echo $periode_tutup_bulan; ?> ?");
	if(t){
		location.href="index.php?com=tutup_bulan&task=tutup_bulan_page";
	}
	return ;
}
</script>
<style>
<?php
//if(defined('CSS_CODE'))print CSS_CODE; 
if(isset($css_code))print $css_code;
?>
</style>
	
	</head>
<body>
<!-- Header -->
<div id="header">
	<div class="shell">
		<!-- Logo + Top Nav -->
		<div id="top">
			<h1><a href="#">Working Hour Monitor System 1.0</a></h1>
			<div id="top-navigation">
				Welcome <a href="index.php?com=user&task=edit_user"><strong><?php echo $user['username'];?></strong></a>
				<span>|</span>
				<a href="#">Kalender</a>
				<span>|</span>
				<a href="index.php?com=user&task=ganti_password">Ganti password</a>
				<span>|</span>
				<a href="login.php?logout=1">Log out</a>
			</div>
		</div>
		<!-- End Logo + Top Nav -->
		<?php
			$left = false;
			$nav = false;
			$components = isset($_GET['com']) ? $_GET['com'] : '';
			$administrasi = array('ijin_karyawan' , 'cuti_karyawan', 'cuti_karyawan_bersama' ,'spd_kegiatan_karyawan');
			$laporan 	= array('laporan_realisasi' , 'laporan_ijin', 'laporan_cuti' ,'laporan_spd');
			$karyawan 	= array('karyawan' ,'aktifitas_karyawan');
			$aktifitas = array('task_karyawan_group' , 'meeting', 'lembur_karyawan' ,'finger_karyawan','kalkulasi');
			$referensi = array(  'cuti_jenis', 'global_kegiatan' ,
				'ijin_jenis','spd_komponen','spd_komponen_tarif','lembur_tarif',
				'global_hari_libur','global_periode','global_jadwal');
			if( in_array( $components , $administrasi ) ){
				$nav = 'administrasi';
				$left = true;
			}
			elseif(in_array( $components , $laporan )){
				$nav = 'laporan'; 
			}
			elseif(in_array( $components , $karyawan )){
				$nav = 'karyawan'; 
			}
			elseif(in_array( $components , $aktifitas )){
				$nav = 'aktifitas';
				$left = true;
			}
			elseif(in_array( $components , $referensi )){
				$nav = 'referensi';
				$left = true;
			}
		?>
		<!-- Main Nav -->
		<div id="navigation">
			<ul id="strike">
			    <li><a href="index.php"<?php if(!$nav) echo ' class="active"';?>><span>Dashboard</span></a></li>
			    
				</li>
			    <li><a href="#"<?php if( $nav =='administrasi' ) echo ' class="active"';?>><span style="width:105px;text-align:center">Administrasi</span></a>
				<ul>
					<li><a href="index.php?com=ijin_karyawan">Administrasi Ijin</a></li>
					<li><a href="index.php?com=cuti_karyawan">Administrasi Cuti</a></li> 
					<li><a href="index.php?com=cuti_karyawan_bersama">Cuti bersama</a></li>
					<li><a href="index.php?com=spd_kegiatan_karyawan">Perjalanan dinas</a></li> 					
					
				</ul>
				</li>
				<li><a href="#"<?php if($nav =='aktifitas' ) echo ' class="active"';?>><span style="width:105px;text-align:center">Aktifitas</span></a>
				<ul>
					<li><a href="index.php?com=task_karyawan_group">Tugas</a></li>
					<li><a href="index.php?com=meeting">Rapat</a></li>
					<li><a href="index.php?com=lembur_karyawan">Lembur</a></li>
					<li><a href="index.php?com=finger_karyawan">Data Finger</a></li> 
					<li><a href="index.php?com=kalkulasi">Kalkulasi</a></li> 
					<li><a href="javascript:;" onclick="javacsript:tutupBulan();">Tutup periode</a></li> 
				</ul>	
				<li><a href="#"<?php if($nav =='laporan' ) echo ' class="active"';?>><span style="width:105px;text-align:center">Laporan</span></a>
				<ul>
					<li><a href="index.php?com=laporan_realisasi">Realisasi Kehadiran</a></li>
					<li><a href="index.php?com=laporan_ijin">Laporan Ijin</a></li>
					<li><a href="index.php?com=laporan_cuti">Laporan Cuti</a></li> 
					<li><a href="index.php?com=laporan_spd">Laporan Perjalanan</a></li> 
				</ul>				
			    <li><a href="#"<?php if($nav =='karyawan' ) echo ' class="active"';?>><span style="width:105px;text-align:center">Karyawan</span></a>
				<ul>
					<li><a href="index.php?com=karyawan">Data karyawan</a></li>
					<li><a href="index.php?com=aktifitas_karyawan">Aktifitas</a></li>
				</ul>
				</li>
			    <li><a href="#"<?php if($nav =='referensi' ) echo ' class="active"';?>><span style="width:105px;text-align:center">Data Referensi</span></a>
				<ul>
					<li><a href="index.php?com=spd_komponen">Referensi</a></li>
					<li><a href="index.php?com=user">Pengguna</a></li>
					<li><a href="#">Program</a></li> 
					<li><a href="#">Database</a></li>
				</ul>
				</li>
				<div style="float:right;padding-top:3px;height:30px;width:180px;text-align:right;">
				<?php
$settingproperty = array(
	'href'=>'javascript:alert(\'Setel aplikasi\');',
	'title'=>'Setelan program', 
);
$setting_button = button_icon_besar( 'b_tblops.png' , $settingproperty  );
$kalendarproperty = array(
	'href'=>'javascript:alert(\'Open Kalendar\');',
	'title'=>'Tampilkan Kalendar', 
);
$kalendar_button = button_icon_besar( 'b_calendar.png' , $kalendarproperty  );
//echo $kalendar_button.' '.$setting_button;
?>
				</div>
			</ul>
		</div>
		<!-- End Main Nav -->
	</div>
</div>
<!-- End Header -->

<!-- Container -->
<div id="container">
	<div class="shell">
		
		<!-- Small Nav -->
		<div class="small-nav">
			 <?php echo str_replace("_BN_"," / ",$sidebar); ?>
		</div>
		<!-- End Small Nav -->
		 
		<!-- Main -->
		<div id="main">
			<div class="cl">&nbsp;</div>
			
			<!-- Content -->
			<div id="content"<?php if(! $left) {echo ' style="width:1000px;"'; }?>>
				<?php echo $content; ?>

			</div>
			<!-- End Content -->
			<?php if($nav and $left){ ?>
			<!-- Sidebar -->
			<div id="sidebar">
				
				<!-- Box -->
				<div class="box">
					<?php /*
					<!-- Box Head -->
					<div class="box-head">
						<h2>Management</h2>
					</div>
					<!-- End Box Head-->
					*/ ?>
					
					<div class="box-content">
						
						<?php 
						if($nav =='administrasi' )
							setblocks('administrasi');
						elseif($nav == 'aktifitas' )
							setblocks('aktifitas');
						else	
							setblocks('referensi');
						?>
					</div>
				</div>
				<!-- End Box -->
			</div>
			<!-- End Sidebar -->	
			<?php } ?>
			<div class="cl">&nbsp;</div>			
		</div>
		<!-- Main -->
	</div>
</div>
<!-- End Container -->

<!-- Footer -->
<div id="footer">
	<div class="shell">
		<span class="left"><?php echo _CLIENT_LABEL; ?></span>
		<span class="right">
			<?php echo _FOOTER_LABEL; ?>	
		</span>
	</div>
</div>
<!-- End Footer -->
	
</body>
</html>