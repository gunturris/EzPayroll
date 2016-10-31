<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- jQuery -->
    <script src="<?php echo my_template_position(); ?>/js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="<?php echo my_template_position(); ?>/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="<?php echo my_template_position(); ?>/js/plugins/metisMenu/metisMenu.min.js"></script>


    <title>Sistim penghitung upah : V 1.3</title>

    <!-- Bootstrap Core CSS -->
    <link href="<?php echo my_template_position(); ?>/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="<?php echo my_template_position(); ?>/css/plugins/metisMenu/metisMenu.min.css" rel="stylesheet">

    <!-- Timeline CSS -->
    <link href="<?php echo my_template_position(); ?>/css/plugins/timeline.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="<?php echo my_template_position(); ?>/css/sb-admin-2.css" rel="stylesheet">

    <!-- Morris Charts CSS -->
    <link href="<?php echo my_template_position(); ?>/css/plugins/morris.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="<?php echo my_template_position(); ?>/font-awesome-4.1.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
<?php   
if(isset($js_file))print $js_file; 
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
</head>

<body>

    <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.php">Penggajian elektronik</a>
            </div>
            <!-- /.navbar-header -->

            <ul class="nav navbar-top-links navbar-right"> 
				<li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-user fa-fw"></i>  <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-messages" style="width:170px">
                        <li><a href="index.php?com=user&task=ganti_password"><i class="fa fa-gear fa-fw"></i> Ganti password</a>
                        </li>
                        <li><a href="#"><i class="fa fa-user fa-fw"></i> Riwayat login</a>
                        </li>
                        <li class="divider"></li>
                        <li><a href="login.php?logout=<?php echo sha1(rand(0,10000));?>"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                        </li>
                    </ul>
                    <!-- /.dropdown-user -->
                </li>
                <!-- /.dropdown -->
            </ul>
            <!-- /.navbar-top-links -->

            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                        <!-- li class="sidebar-search">
                            <div class="input-group custom-search-form">
                                 
                            </div> 
                        </li -->
                        <li>
                            <a href="/"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a>
                        </li> 
                        <li>
                            <a href="#"><i class="fa fa-table fa-fw"></i> Remunerasi<span class="fa arrow"></span></a>
							<ul class="nav nav-second-level"> 
                                <li>
                                    <a href="index.php?com=karyawan_renumerasi"><i class="fa fa-codepen fa-fw"></i> Dasar penghitungan</a>
                                </li>
								<li>
                                    <a href="index.php?com=pay_komponen_manual"><i class="fa fa-floppy-o fa-fw"></i> Data manual</a>
                                </li>
								<li>
                                    <a href="index.php?com=pay_komponen_exception"><i class="fa fa-external-link fa-fw"></i> Data eksepsi</a>
                                </li>
							</ul>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-clock-o fa-fw"></i> Waktu kerja<span class="fa arrow"></span></a>
							<ul class="nav nav-second-level">  
								<li>
                                    <a href="index.php?com=wt_jadwal_karyawan"><i class="fa fa-book fa-fw"></i> Jadwal karyawan</a>
                                </li>
								<li>
                                    <a href="index.php?com=wt_overtime"><i class="fa fa-comment-o fa-fw"></i> Data lembur</a>
                                </li>
								<li>
                                    <a href="index.php?com=wt_implementasi_ijin"><i class="fa fa-info-circle fa-fw"></i> Data ijin</a>
                                </li>
								<li>
                                    <a href="index.php?com=wt_implementasi_cuti"><i class="fa fa-beer fa-fw"></i> Data cuti</a>
                                </li>
								<li>
                                    <a href="#"><i class="fa fa-dribbble fa-fw"></i> Perhitungan<span class="fa arrow"></span></a>
                                    <ul class="nav nav-third-level">
                                        <li>
                                            <a href="index.php?com=wt_periode"><i class="fa fa-check-circle fa-fw"></i> Periode kerja</a>
                                        </li>
                                        <li>
                                            <a href="index.php?com=wt_kehadiran_karyawan"><i class="fa fa-check-circle fa-fw"></i> Kehadiran karyawan</a>
                                        </li>
                                        <li>
                                            <a href="index.php?com=wt_lembur_karyawan"><i class="fa fa-check-circle fa-fw"></i> Perhitungan lembur</a>
                                        </li>
                                        <li>
                                            <a href="index.php?com=pay_proses_pajak_reguler"><i class="fa fa-check-circle fa-fw"></i> Tutup bulan kerja</a>
                                        </li>  
                                    </ul> 
                                </li>
							</ul>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-tasks fa-fw"></i> Proses gaji<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="#"><i class="fa fa-calendar fa-fw"></i> Reguler<span class="fa arrow"></span></a>
                                    <ul class="nav nav-third-level">
                                        <li>
                                            <a href="index.php?com=pay_periode_reguler"><i class="fa fa-check-circle fa-fw"></i> Setel periode</a>
                                        </li>
                                        <li>
                                            <a href="index.php?com=pay_proses_kalkulasi_reguler"><i class="fa fa-check-circle fa-fw"></i> Kalkulasi</a>
                                        </li>
                                        <li>
                                            <a href="index.php?com=pay_proses_komponen_reguler"><i class="fa fa-check-circle fa-fw"></i> Komponen</a>
                                        </li>
                                        <li>
                                            <a href="index.php?com=pay_proses_pajak_reguler"><i class="fa fa-check-circle fa-fw"></i> Pajak</a>
                                        </li> 
                                        <li>
                                            <a href="index.php?com=pay_proses_tutup_bulan"><i class="fa fa-check-circle fa-fw"></i> Tutup</a>
                                        </li> 
                                    </ul> 
                                </li>
								
								<li>
                                    <a href="#"><i class="fa fa-clipboard fa-fw"></i> Unreguler<span class="fa arrow"></span></a>
                                    <ul class="nav nav-third-level">
                                        <li>
                                            <a href="index.php?com=unreg_set_param"><i class="fa fa-check-circle fa-fw"></i> Setel parameter</a>
                                        </li>
                                        <li>
                                            <a href="index.php?com=unreg_proses_kalkulasi"><i class="fa fa-check-circle fa-fw"></i> Kalkulasi</a>
                                        </li>
                                        <li>
                                            <a href="index.php?com=unreg_proses_komponen"><i class="fa fa-check-circle fa-fw"></i> Komponen</a>
                                        </li>
                                        <li>
                                            <a href="index.php?com=unreg_proses_pajak"><i class="fa fa-check-circle fa-fw"></i> Pajak</a>
                                        </li> 
                                        <li>
                                            <a href="index.php?com=unreg_proses_tutup_bulan"><i class="fa fa-check-circle fa-fw"></i> Tutup</a>
                                        </li> 
                                    </ul> 
                                </li>
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
						
						
                        <li>
                            <a href="#"><i class="fa fa-check-circle-o fa-fw"></i> Matrik Persetujuan<span class="fa arrow"></span></a>
							<ul class="nav nav-second-level">
                                <li>
                                    <a href="index.php?com=approval_exception"><i class="fa fa-user-md fa-fw"></i> Pengecualian</a>
                                </li>
                                <li>
                                    <a href="index.php?com=approval_patern"><i class="fa fa-chain-broken fa-fw"></i> Pola persetujuan</a>
                                </li>
                                <li>
                                    <a href="index.php?com=approval"><i class="fa fa-ge fa-fw"></i> Klasifikasi persetujuan</a>
                                </li>
                            </ul>
                        </li> 
						
						
                        <li>
                            <a href="#"><i class="fa fa-users fa-fw"></i> Karyawan<span class="fa arrow"></span></a>
							<ul class="nav nav-second-level">
                                <li>
                                    <a href="index.php?com=karyawan"><i class="fa fa-user-md fa-fw"></i> Data karyawan</a>
                                </li>
                                <li>
                                    <a href="index.php?com=karyawan_rekening"><i class="fa fa-dollar fa-fw"></i> Rekening bank</a>
                                </li>
                            </ul>
                        </li> 
                        <li>
                            <a href="#"><i class="fa fa-cogs fa-fw"></i> Konfigurasi<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level"> 
                                <li>
                                    <a href="#"><i class="fa fa-suitcase fa-fw"></i> Komponen gaji<span class="fa arrow"></span></a>
                                    <ul class="nav nav-third-level">
                                        <li>
                                            <a href="index.php?com=pay_komponen_gaji&type=pendapatan">&nbsp; <i class="fa fa-check-circle fa-fw"></i> Pendapatan</a>
                                        </li>
                                        <li>
                                            <a href="index.php?com=pay_komponen_gaji&type=subsidi">&nbsp; <i class="fa fa-check-circle fa-fw"></i> Subsidi</a>
                                        </li>
                                        <li>
                                            <a href="index.php?com=pay_komponen_gaji&type=potongan">&nbsp; <i class="fa fa-check-circle fa-fw"></i> Potongan</a>
                                        </li>
                                    </ul>
                                    <!-- /.nav-third-level --> 
                                </li>
								<li>
                                    <a href="#"><i class="fa fa-building-o fa-fw"></i> Tarif dasar<span class="fa arrow"></span></a>
                                    <ul class="nav nav-third-level">
                                        <li>
                                            <a href="index.php?com=pay_benefit_group&type=tunjangan">&nbsp; <i class="fa fa-check-circle fa-fw"></i> Tunjangan</a>
                                        </li>
                                        <li>
                                            <a href="index.php?com=pay_benefit_group&type=potongan">&nbsp; <i class="fa fa-check-circle fa-fw"></i> Potongan</a>
                                        </li>
                                        <li>
                                            <a href="index.php?com=pay_benefit_group&type=subsidi">&nbsp; <i class="fa fa-check-circle fa-fw"></i> Subsidi</a>
                                        </li> 
                                    </ul>
                                    <!-- /.nav-third-level --> 
                                </li>
                                <li>
                                    <a href="#"><i class="fa fa-clock-o fa-fw"></i> Setel waktu kerja<span class="fa arrow"></span></a>
                                    <ul class="nav nav-third-level">
                                        <li>
                                            <a href="index.php?com=wt_jadwal_kerja">&nbsp; <i class="fa fa-check-circle fa-fw"></i> Jadwal kerja</a>
                                        </li>
                                        <li>
                                            <a href="index.php?com=wt_kelompok_kerja">&nbsp; <i class="fa fa-check-circle fa-fw"></i> Kelompok kerja</a>
                                        </li>
                                        <li>
                                            <a href="index.php?com=wt_hari_libur">&nbsp; <i class="fa fa-check-circle fa-fw"></i> Hari libur</a>
                                        </li>
                                        <li>
                                            <a href="index.php?com=wt_tipe_ijin">&nbsp; <i class="fa fa-check-circle fa-fw"></i> Jenis ijin</a>
                                        </li>
                                        <li>
                                            <a href="index.php?com=wt_tipe_cuti">&nbsp; <i class="fa fa-check-circle fa-fw"></i> Jenis cuti</a>
                                        </li>
                                    </ul>
                                    <!-- /.nav-third-level --> 
                                </li> 
								<li>
                                    <a href="#"><i class="fa fa-folder-open fa-fw"></i> Data referensi<span class="fa arrow"></span></a>
                                    <ul class="nav nav-third-level">
                                        <li>
                                            <a href="index.php?com=karyawan_status">&nbsp; <i class="fa fa-check-circle fa-fw"></i> Status karyawan</a>
                                        </li>
                                        <li>
                                            <a href="index.php?com=karyawan_gol_jab">&nbsp; <i class="fa fa-check-circle fa-fw"></i> Klasifikasi level</a>
                                        </li>
                                        <li>
                                            <a href="index.php?com=tax_ptkp_categori">&nbsp; <i class="fa fa-check-circle fa-fw"></i> Kategori PTKP</a>
                                        </li>
                                        <li>
                                            <a href="index.php?com=pay_jurnal_gaji">&nbsp; <i class="fa fa-check-circle fa-fw"></i> Jurnal gaji</a>
                                        </li> 
                                    </ul>
                                    <!-- /.nav-third-level --> 
                                </li> 
                                <li>
                                    <a href="#"><i class="fa fa-users fa-fw"></i> Setup<span class="fa arrow"></span></a>
									<ul class="nav nav-third-level">
                                        <li>
                                            <a href="index.php?com=master_konfigurasi">&nbsp; <i class="fa fa-check-circle fa-fw"></i> Data wajib pajak</a>
                                        </li> 
										<li>
											<a href="index.php?com=approval_group">&nbsp; <i class="fa fa-check-circle fa-fw"></i> Kelompok persetujuan</a>
										</li>
										<li>
											<a href="index.php?com=user">&nbsp; <i class="fa fa-check-circle fa-fw"></i> Pengguna aplikasi</a>
										</li>
                                        <li>
                                            <a href="index.php?com=broadcast">&nbsp; <i class="fa fa-check-circle fa-fw"></i> Notifikasi/ Publikasi</a>
                                        </li> 
                                    </ul>
                                </li> 
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-files-o fa-fw"></i> Laporan-laporan<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="#"><i class="fa fa-clipboard fa-fw"></i> Gaji reguler<span class="fa arrow"></span></a>
                                    <ul class="nav nav-third-level">
                                        <li>
                                            <a href="index.php?com=report_pay_slip_gaji"><i class="fa fa-check-circle fa-fw"></i> Slip gaji</a>
                                        </li>
                                        <li>
                                            <a href="index.php?com=report_pay_pajak"><i class="fa fa-check-circle fa-fw"></i> Laporan Pajak</a>
                                        </li>
                                        <li>
                                            <a href="index.php?com=report_pay_komponen"><i class="fa fa-check-circle fa-fw"></i> Komponen &amp; Item</a>
                                        </li>
                                        <li>
                                            <a href="index.php?com=report_pay_jurnal"><i class="fa fa-check-circle fa-fw"></i> Jurnal gaji</a>
                                        </li>  
                                    </ul> 
                                </li>
                                <li>
                                    <a href="#"><i class="fa fa-clipboard fa-fw"></i> Unreguler<span class="fa arrow"></span></a>
                                    <ul class="nav nav-third-level">
                                        <li>
                                            <a href="index.php?com=report_unreg_slip_gaji"><i class="fa fa-check-circle fa-fw"></i> Slip gaji</a>
                                        </li>
                                        <li>
                                            <a href="index.php?com=report_unreg_pajak"><i class="fa fa-check-circle fa-fw"></i> Laporan Pajak</a>
                                        </li>
                                        <li>
                                            <a href="index.php?com=report_unreg_komponen"><i class="fa fa-check-circle fa-fw"></i> Komponen &amp; Item</a>
                                        </li>
                                        <li>
                                            <a href="index.php?com=report_unreguler_jurnal"><i class="fa fa-check-circle fa-fw"></i> Jurnal gaji</a>
                                        </li>
                                    </ul> 
                                </li>
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->
        </nav>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="page-header"><?php echo $sidebar; ?></h4>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <?php echo $content; ?> 
        </div>
        <!-- /#page-wrapper -->

    </div>  
	<div style="width:100%;background-color:red;">Text	</div>
    <!-- Custom Theme JavaScript -->
    <script src="<?php echo my_template_position(); ?>/js/sb-admin-2.js"></script>
</body>
</html>
