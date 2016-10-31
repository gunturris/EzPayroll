<!DOCTYPE html>
<?php
$no_do = isset($_GET['no_do']) ? (int) $_GET['no_do'] : '';
$query = "SELECT *  ,c.posted_on AS waktu_masuk FROM surat_jalan a
INNER JOIN timbang b ON a.timbang_system_code = b.system_code	
INNER JOIN nota_muat c ON c.system_code = a.nota_system_code
INNER JOIN termin d ON d.system_code = c.termin_system_code 	
INNER JOIN purchase e ON e.system_code = d.po_system_code 	
WHERE a.no_do = '{$no_do}' ";
$result = my_query($query); 
$datas = my_fetch_array($result);
if(!$datas){ echo $query; exit; }
$company = load_data_system_code('company',$datas['company_buyer_system_code']);
$material = load_data_system_code('material_type',$datas['material_type_system']);
$stockpile = load_data_system_code('stockpile',$datas['stockpile_system_code']); 
$pengangkut = load_data_system_code('jenis_pengangkut',$datas['jenis_pengangkut_system_code']);
?>
<html>
<head>
	<title>HTML SURAT PENGIRIMAN</title> 
</head>
<body> 
	<table style="width:100%">
		<tr>
        	<td  style="width:20%"><img width="80px" src="<?php echo my_template_position(); ?>/logo_ggb.jpg"></td>
            <td style="width:50%" valign="top" align="center">
            	<span style="font-size:20px"><b>SURAT PENGIRIMAN BATU BARA</b></span><br>
                Desa Prabu Menang Kec. Merapi Timur Kab. Lahat, Prov. Sumatra Selatan<br><br>
                <span style="font-size:14px"><b>PT. GOLDEN GREAT BORNEO</b></span><br>
                Jl. Kolonel Barlian No.35 D Kabupaten Lahat Prov. Sumatra Selatan
            </td>
            <td style="width:30%" valign="top" align="center">No. GGB :<?php echo $datas['no_do']; ?><br>
			<img style="width:180px" src="http://<?php echo $_SERVER['HTTP_HOST']; ?>/cargostock/components/barcode/barcodegen/html/image.php?code=code11&o=1&dpi=72&t=20&r=1&rot=0&text=<?php echo $datas['no_do']; ?>&f1=-1&f2=8&a1=&a2=&a3="/>
			</td>
    	</tr>
    </table>
    <hr>

	<table style="width:100%">
    	<tr>
        	<td style="width:48%" >
            	<table style="width:100%">
                	<tr>
                    	<td style="width:50%"><strong>KEPADA</strong></td>
                        <td style="width:3%">: </td>
                        <td style="width:47%;"><?php echo $company['nama']; ?></td>
                    </tr>
                	<tr>
                    	<td style="width:50%"><strong>TUJUAN</strong></td>
                        <td style="width:3%">: </td>
                        <td style="width:47%;border-botom:2px solid;"><?php echo substr($stockpile['stockpile_name'],0,25); ?></td>
                    </tr>
                    <tr>
                    	<td style="width:50%"><strong>JENIS MUATAN BARANG</strong></td>
                        <td style="width:3%">: </td>
                        <td style="width:47%;border-botom:2px solid;"><?php echo substr($material['name'],0,25); ?></td>
                    </tr>
                    <tr>
                    	<td style="width:50%"><strong>ASAL MUATAN BARANG</strong></td>
                        <td style="width:3%">: </td>
                        <td style="width:47%;border-botom:2px solid;"><?php echo substr(ASAL_MUATAN  ,0,25); ?></td>
                    </tr> 
					<tr>
                    	<td style="width:50%"><strong>PENGIRIM</strong></td>
                        <td style="width:3%">: </td>
                        <td style="width:47%;border-botom:2px solid;"><?php echo substr(ID_PENGIRIM ,0,25);  ?></td>
                    </tr>
					<tr>
                    	<td style="width:50%"><strong>TANGGAL PENGIRIMAN</strong></td>
                        <td style="width:3%">: </td>
                        <td style="width:47%;border-botom:2px solid;"><?php echo date('d M Y', strtotime($datas['waktu_masuk']) ); ?></td>
                    </tr> 
                </table>
            </td>
            <td style="width:4%"></td>
        	<td style="width:48%">
            	<table style="width:100%">
                	<tr>
                    	<td style="width:55%"><strong>KENDARAAN PENGANGKUT</strong></td>
                        <td style="width:3%">: </td>
                        <td style="width:42%;border-botom:1px solid;"><?php echo substr($pengangkut['name'] ,0,25);  ?></td>
                    </tr>
                    <tr>
                    	<td style="width:55%"><strong>PLAT NO KENDARAAN</strong></td>
                        <td style="width:3%">: </td>
                        <td style="width:42%;border-botom:1px solid;"><?php echo $datas['nopol']; ?></td>
                    </tr>
                    <tr>
                    	<td style="width:55%"><strong>NAMA TRANSPORTIR</strong></td>
                        <td style="width:3%">: </td>
                        <td style="width:42%;border-botom:1px solid;"><?php echo substr($datas['transportir'] ,0,25); ?></td>
                    </tr>
                    <tr>
                    	<td style="width:55%"><strong>NAMA SUPIR</strong></td>
                        <td style="width:3%">: </td>
                        <td style="width:42%;border-botom:1px solid;"><?php echo substr($datas['pengemudi'] ,0,25); ?></td>
                    </tr>
                    <tr>
                    	<td style="width:55%"><strong>JAM MASUK</strong></td>
                        <td style="width:3%">: </td>
                        <td style="width:42%;border-botom:1px solid;"><?php echo date('H:s',strtotime($datas['waktu_masuk']) ); ?></td>
                    </tr>
                    
                    <tr>
                    	<td style="width:55%"><strong>JAM KELUAR</strong></td>
                        <td style="width:3%">: </td>
                        <td style="width:42%;border-botom:1px solid;"><?php echo date('H:s'); ?></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
<br>
	<span style="font-size:16px;"><b>A. STOCKPILE MULUT TAMBANG</b></span>
    <table style="width:100%" border="1" cellspacing="0">
		<tr><td style="width:40%;height:20px;font-size:14px;" align="center"><strong>BERAT</strong></td><td style="width:30%;height:20px;font-size:14px;"  align="center"><strong>TONASE</strong></td><td style="width:30%;height:20px;font-size:14px;" align="center"><strong>KETERANGAN</strong></td></tr>
		<tr><td style="width:40%;padding:3px;">Berat Kendaraan Kosong</td><td  style="width:30%;padding:3px;" align="right"><?php echo  number_format(($datas['timbang_kosong']/1000), 3, ',', ' '); ?> MT</td><td style="width:30%;padding:3px;"></td></tr>
		<tr><td style="width:40%;padding:3px;">Berat Kendaraan Isi</td><td  style="width:30%;padding:3px;" align="right"><?php echo   number_format(($datas['timbang_isi'] / 1000), 3, ',', ' ') ; ?> MT</td><td style="width:30%;padding:3px;"></td></tr>
		<tr><td style="width:40%;padding:3px;">Berat Bersih (Muatan)</td><td  style="width:30%;padding:3px;" align="right"><?php echo number_format((($datas['timbang_isi']- $datas['timbang_kosong'])/ 1000 ), 3, ',', ' '); ?> MT</td><td style="width:30%;padding:3px;">Diterima tanggal &amp; jam : </td></tr>
    </table>
	<br>
	<span style="font-size:16px;"><b>B. STOCKPILE PELABUHAN</b></span>
    <table style="width:100%" border="1" cellspacing="0">
		<tr><td style="width:40%;height:20px;font-size:14px;" align="center"><strong>BERAT</strong></td><td style="width:30%;height:22px;font-size:14px;"  align="center"><strong>TONASE</strong></td><td style="width:30%;height:22px;font-size:14px;" align="center"><strong>KETERANGAN</strong></td></tr>
		<tr><td style="width:40%;padding:3px;">Berat Kendaraan Kosong</td><td  style="width:30%;padding:3px;" align="right">MT</td><td style="width:30%;padding:3px;"></td></tr>
		<tr><td style="width:40%;padding:3px;">Berat Kendaraan Isi</td><td  style="width:30%;padding:3px;" align="right">MT</td><td style="width:30%;padding:3px;"></td></tr>
		<tr><td style="width:40%;padding:3px;">Berat Bersih (Muatan)</td><td  style="width:30%;padding:3px;" align="right">MT</td><td style="width:30%;padding:3px;">Diterima tanggal &amp; jam : </td></tr>
    </table>
    <br>
    
  	<table style="width:100%">
		<tr>
        	<td style="width:20%;" align="center"><b>Stockpile</b></td> 
            <td style="width:20%;" align="center"><b>Timbangan</b></td> 
            <td style="width:20%;" align="center"><b>Timbangan</b></td> 
            <td style="width:20%;" align="center"><b>Pembeli/Buyer</b></td> 
            <td style="width:20%;" align="center"><b>Supir Transportir</b></td>
		</tr>
        <tr>
        	<td height="70" valign="bottom" align="center">__________</td> 
            <td  valign="bottom" align="center">__________</td> 
            <td  valign="bottom" align="center">__________</td> 
            <td  valign="bottom" align="center">__________</td> 
            <td  valign="bottom" align="center">__________</td>
        </tr>
		<tr>
        	<td valign="bottom" align="center"> PT. GBB</td> 
            <td valign="bottom" align="center"> PT. GBB</td> 
            <td valign="bottom" align="center"> PT. GBB</td> 
            <td valign="bottom" align="center">  PT. _ _ _ _</td> 
            <td valign="bottom" align="center">  _ _ _ _ _ _</td>
    	</tr>
    </table>
	<br>
    <span style="letter-spacing: 0.3em;"><b>Lembar Putih</b> Untuk petugas Penerima<br>
			<b>Lembar Merah</b> Untuk Sopir Transportir<br>
			<b>Lembar Kuning</b> Untuk Pembelian FOR CV/PT .....<br>
			<b>Lembar Biru</b> Untuk Petugas Timbangan PT.GGB<br>
			<b>Lembar Hijau </b> Untuk Petugas Timbangan Buyer<br>
		 
    </span> 
</body>
</html>