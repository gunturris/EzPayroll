<?php


function antropometri_form_detail( ){
	$fields =   my_get_data_by_id('antropometri','no_mcu', $_SESSION['no_mcu']);
	
	$view = report_header( "detail antropometri" , "detail-antropometri"  ); 
	
	$view .= form_field_report(": ". $fields['pemeriksa'] , "Pemeriksa" ,  "#FFFFFF"  );
	$view .= form_field_report(": ". $fields['tinggi'] ." cm" , "Tinggi Badan" ,  "#FFFFFF"  );
	
	$view .= form_field_report(": ". $fields['berat'] ." cm" , "Berat Badan" ,  "#FFFFFF"  );
	
	$view .= form_field_report(": ". $fields['lingkar_pinggang'] ." cm" , "Lingkar Pinggang" ,  "#FFFFFF"  );
	
	$view .= form_field_report(": ". $fields['lingkar_panggul'] ." cm" , "Lingkar Panggul" ,  "#FFFFFF"  );
	
	$view .= form_field_report(": ". $fields['suhu']. " &deg;C" , "Suhu" ,  "#FFFFFF"  );
	
	$view .= form_field_report(": ". $fields['frekuensi_nadi'] , "Frekuensi Nadi" ,  "#FFFFFF"  );
	
	$view .= form_field_report(": ". $fields['irama_nadi'] , "Irama Nadi" ,  "#FFFFFF"  );
	
	$view .= form_field_report(": ". $fields['vol_nadi'] , "Isi" ,  "#FFFFFF"  );
	
	$view .= form_field_report(": ". $fields['frekuensi_napas'] , "Frekuensi Napas" ,  "#FFFFFF"  );
	
	$view .= form_field_report(": ". $fields['irama_napas'] , "Irama Napas" ,  "#FFFFFF"  );
	
	$view .= form_field_report(": ". $fields['sistolik'] , "Tekanan Darah Sistolik" ,  "#FFFFFF"  );
	
	$view .= form_field_report(": ". $fields['diastolik'] , "Tekanan Darah Diastolik" ,  "#FFFFFF"  );
	$petugas = my_get_data_by_id('petugas','petugas_id' , $fields['petugas_id']);
//	$view .= form_field_report(": ". $petugas['nama'] , "Nama pemeriksa" ,  "#FFFFFF"  );
	
	$view .= form_field_report(": ". date("d-m-Y",strtotime($fields['created'])) , "Tanggal Dibuat" ,  "#FFFFFF"  );
	 
	$view .= report_footer( );
	return $view;
}

function audiometri_form_report(){
	$fields =   my_get_data_by_id('audiometri' , 'no_mcu' , $_SESSION['no_mcu']);
	
	$view = report_header( "detail audiometri" , "detail-audiometri"  );
	
	//$view .= choosen_peserta( ": ");
	$view .= form_field_report( ": ".  $fields['pemeriksa']   , "Pemeriksa" ,  "#FFFFFF"  );
	$view .= form_field_report( ": ". ($fields['telinga_kn'] == 1 ? "Normal" : "Tidak Normal") , "<b>TELINGA KANAN</b>" ,  "#FFFFFF"  );
	
	$tuli_konduktif_kn_split = split("#", $fields['tuli_konduktif_kn']);
	$view .= form_field_report( ": ". ucfirst($tuli_konduktif_kn_split[0]) , "Tuli Konduktif" ,  "#FFFFFF"  );
	$view .= form_field_report( " &nbsp; <i>Catatan :</i> ". ucfirst($tuli_konduktif_kn_split[1]) , "&nbsp;" ,  "#FFFFFF"  );
	
	$tuli_perseptif_kn_split = split("#", $fields['tuli_perseptif_kn']);
	$view .= form_field_report( ": ". ucfirst($tuli_perseptif_kn_split[0]) , "Tuli Konduktif" ,  "#FFFFFF"  );
	$view .= form_field_report( " &nbsp; <i>Catatan :</i> ". ucfirst($tuli_perseptif_kn_split[1]) , "&nbsp;" ,  "#FFFFFF"  );
	
	$tuli_campuran_kn_split = split("#", $fields['tuli_campuran_kn']);
	$view .= form_field_report( ": ". ucfirst($tuli_campuran_kn_split[0]) , "Tuli Konduktif" ,  "#FFFFFF"  );
	$view .= form_field_report( " &nbsp; <i>Catatan :</i> ". ucfirst($tuli_campuran_kn_split[1]) , "&nbsp;" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". ($fields['telinga_kr'] == 1 ? "Normal" : "Tidak Normal") , "<b>TELINGA KIRI</b>" ,  "#FFFFFF"  );
	
	$tuli_konduktif_kr_split = split("#", $fields['tuli_konduktif_kr']);
	$view .= form_field_report( ": ". ucfirst($tuli_konduktif_kr_split[0]) , "Tuli Konduktif" ,  "#FFFFFF"  );
	$view .= form_field_report( " &nbsp; <i>Catatan :</i> ". ucfirst($tuli_konduktif_kr_split[1]) , "&nbsp;" ,  "#FFFFFF"  );
	
	$tuli_perseptif_kr_split = split("#", $fields['tuli_perseptif_kr']);
	$view .= form_field_report( ": ". ucfirst($tuli_perseptif_kr_split[0]) , "Tuli Konduktif" ,  "#FFFFFF"  );
	$view .= form_field_report( " &nbsp; <i>Catatan :</i> ". ucfirst($tuli_perseptif_kr_split[1]) , "&nbsp;" ,  "#FFFFFF"  );
	
	$tuli_campuran_kr_split = split("#", $fields['tuli_campuran_kr']);
	$view .= form_field_report( ": ". ucfirst($tuli_campuran_kr_split[0]) , "Tuli Konduktif" ,  "#FFFFFF"  );
	$view .= form_field_report( " &nbsp; <i>Catatan :</i> ". ucfirst($tuli_campuran_kr_split[1]) , "&nbsp;" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". ($fields['catatan'] == 1 ? "Telinga Anomali" : "Telinga Tidak Anomali") , "<b><u>CATATAN</u></b>" ,  "#FFFFFF"  );
	
	$view .= form_field_report( " &nbsp; ". $fields['catatan_text'] , " &nbsp; " ,  "#FFFFFF"  );
	 
	
	$view .= form_field_report( $form_submit , "&nbsp;" ,  "#FFFFFF" );
	$view .= report_footer( );
	return $view;
}


function fisik_form_detail( ){
	$fields =   my_get_data_by_id('fisik','no_mcu', $_SESSION['no_mcu']);
	
	$view = report_header( "detail fisik" , "detail-fisik"  ); 
	
	$view .= form_field_report( ": ". $fields['pemeriksa'] , "Pemeriksa" ,  "#FFFFFF"  );
	$view .= form_field_report( "&nbsp;" , "<b><u>UMUM</u></b>" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['keadaan'] , "keadaan umum" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['gizi'] , "Gizi" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['kesadaran'] , "Kesadaran" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['kulit'] , "Kulit" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['kepala'] , "Kepala" ,  "#FFFFFF"  );
	
	$view .= form_field_report( "&nbsp;" , "<b><u>MATA KANAN</u></b>" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['ikterik_mata_kanan'] , "Ikterik pada sclera" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['ketajaman kanan'] , "Tajam Penglihatan" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['bola_mata_kanan'] , "Bola Mata" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['kongjungtive_kanan'] , "Konjungtive" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['kornea_kanan'] , "kornea" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['iris_kanan'] , "iris" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['pupil_kanan'] , "pupil" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['lensa_kanan'] , "lensa" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['lain_kanan'] , "lainnya" ,  "#FFFFFF"  );
	
	$view .= form_field_report( "&nbsp;" , "<b><u>MATA KIRI</u></b>" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['ikterik_mata_kiri'] , "Ikterik pada sclera" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['ketajaman kiri'] , "Tajam Penglihatan" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['bola_mata_kiri'] , "Bola Mata" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['kongjungtive_kiri'] , "konjungtive" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['kornea_kiri'] , "kornea" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['iris_kiri'] , "iris" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['pupil_kiri'] , "pupil" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['lensa_kiri'] , "lensa" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['lain_kiri'] , "lainnya" ,  "#FFFFFF"  );
	
	$view .= form_field_report( "&nbsp;" , "<b><u>THT</u></b>" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['telinga'] , "telinga" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['hidung'] , "hidung" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['tenggorokan'] , "mulut & Tenggorokan" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['tht_lain'] , "lainnya" ,  "#FFFFFF"  );
	
	$view .= form_field_report( "&nbsp;" , "<b><u>LEHER</u></b>" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['kelenjar_limpa'] , "kelenjar limpa" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['kelenjar_gondok'] , "kelenjar gondok" ,  "#FFFFFF"  );
	
	$view .= form_field_report( "&nbsp;" , "<b><u>DADA</u></b>" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['bentuk_dada'] , "bentuk " ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['pembuluh_darah'] , "pembuluh darah melebar" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['buah_dada'] , "buah dada" ,  "#FFFFFF"  );
	
	$view .= form_field_report( "&nbsp;" , "<b><u>PARU</u></b>" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['suara_perkusi'] , "suara perkusi" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['rhonkhi'] , "rhonkhi" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['wheezing'] , "wheezing" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['krepitasi'] , "krepitasi" ,  "#FFFFFF"  );
	
	$view .= form_field_report( "&nbsp;" , "<b><u>JANTUNG</u></b>" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['irama_jantung'] , "irama" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['bunyi_jantung'] , "bunyi jantung" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['heart_rate'] , "heart rate" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['murmur'] , "bising / murmur" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['galop'] , "galop" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['besar_jantung'] , "besar jantung" ,  "#FFFFFF"  );
	
	$view .= form_field_report( "&nbsp;" , "<b><u>RONGGA PERUT</u></b>" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['bentuk_perut'] , "bentuk perut" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['hati'] , "hati" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['limpa'] , "limpa" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['asites'] , "asites" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['vena_melebar'] , "vena melebar" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['paristaltik_usus'] , "paristaltik usus" ,  "#FFFFFF"  );
	
	$view .= form_field_report( "&nbsp;" , "<b><u>ALAT KELAMIN</u></b>" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['kondisi_alat_kelamin'] , "kondisi" ,  "#FFFFFF"  );
	
	$view .= form_field_report( "&nbsp;" , "<b><u>ANGGOTA TUBUH</u></b>" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['edema'] , "edema" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['syanosis'] , "syanosis" ,  "#FFFFFF"  );
	
	$view .= form_field_report( "&nbsp;" , "<b><u>REFLEKS</u></b>" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['fisiologis'] , "fisiologis" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['tremor'] , "tremor" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['bengkak_sendi'] , "bengkak sendi" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['varices'] , "varices" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['refleks_lain'] , "Lainnya" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['lain_lain'] , "<b>LAINNYA</b>" ,  "#FFFFFF"  ); 
	$view .= form_field_report(": ". date("d-m-Y",strtotime($fields['created'])) , "Tanggal Dibuat" ,  "#FFFFFF"  );

	$view .= report_footer( );
	return $view;
}


function radiologi_form_detail( ){
	$fields =   my_get_data_by_id('radiologi','no_mcu', $_SESSION['no_mcu']);
	
	$view = report_header( "detail radiologi" , "detail-radiologi"  ); 
	$view .= form_field_report( ": ". $fields['no_foto'] , "Nomor Foto Rontgen" ,  "#FFFFFF"  );
	$view .= form_field_report( ": ". $fields['pemeriksa'] , "Pemerisa" ,  "#FFFFFF"  );
	 
	$view .= form_field_report( ": ". $fields['jenis'] , "Jenis Pemeriksaan" ,  "#FFFFFF"  );
	
	$view .= form_field_report( "&nbsp;"  , "<b>PEMBACAAN FOTO</b>" ,  "#FFFFFF"  );
	$view .= form_field_report( ": ". $fields['jantung'] , "- Jantung" ,  "#FFFFFF"  );
	$view .= form_field_report( ": ". $fields['paru'] , "- Paru" ,  "#FFFFFF"  );
	$view .= form_field_report( ": ". $fields['diafragma'] , "- Diafragma" ,  "#FFFFFF"  );
	
	$view .= form_field_report( "&nbsp;"  , "&nbsp;" ,  "#FFFFFF"  );
	$view .= form_field_report( ": ". $fields['kesimpulan'] , "Kesimpulan Radiologi" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['saran'] , "Saran" ,  "#FFFFFF"  );
	 
	$view .= report_footer( );
	return $view;
}


function kardiologi_form_detail( ){
	$fields =   my_get_data_by_id('kardiologi','no_mcu', $_SESSION['no_mcu']);
	
	$view = report_header( "detail kardiologi" , "detail-kardiologi"  ); 
	$view .= form_field_report( ": ". $fields['pemeriksa'] , "Pemeriksa" ,  "#FFFFFF"  );
	
	$view .= form_field_report( "&nbsp;" , "<b>NADI</b>" ,  "#FFFFFF"  );
	$view .= form_field_report( ": ". $fields['frekuensi_nadi'] , "- Frekuensi" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['irama_nadi'] , "- Irama" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['vol_nadi'] , "- Isi" ,  "#FFFFFF"  );
	$view .= form_field_report( "&nbsp;" , "&nbsp;" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['tekanan_darah'] ." mmHg" , "Tekanan Darah" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['venajugularis']. " cm H2O" , "Tekanan Venajugularis" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['syanosis'] , "Syanosis" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['inspeksi'] , "Inspeksi" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['prepesi'] , "Prepesi" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['auskultasi'] , "Auskultasi" ,  "#FFFFFF"  );
	$view .= form_field_report( "&nbsp;" , "&nbsp;" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['elektrokardiogram'] , "Elektrokardiogram" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['treadmill'] , "Treadmill" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['ekokardiografi'] , "Ekokardiografi" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['kesimpulan'] , "Kesimpulan" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['saran'] , "Saran" ,  "#FFFFFF"  );
	
$view .= form_field_report(": ". date("d-m-Y",strtotime($fields['created'])) , "Tanggal Dibuat" ,  "#FFFFFF"  );
 
	$view .= report_footer( );
	return $view;
}


function tht_form_detail( ){
	$fields =   my_get_data_by_id('tht','no_mcu', $_SESSION['no_mcu']);
	
	$view = report_header( "detail tht" , "detail-tht"  ); 
	
	$view .= form_field_report( ": ". $fields['pemeriksa'] , "Pemeriksa" ,  "#FFFFFF"  );
	
	$view .= form_field_report( "&nbsp;" , "<b><u>TELINGA</u></b>" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['aurica'] , "Aurica" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['canalis_audi_extem'] , "canalis auditoris extemus" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['kulit_canalis'] , "kulit canalis" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['discharge_telinga'] , "discharge" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['membrana_tympani'] , "membrana tympani" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['cavum_tympani'] , "cavum tympani" ,  "#FFFFFF"  );
	
	$view .= form_field_report( "&nbsp;" , "<b><u>HIDUNG</u></b>" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['mucosa_cavum_nasi'] , "mucosa cavum nasi" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['concha'] , "concha" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['septum_nasi'] , "septum nasi" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['discharge_hidung'] , "discharge" ,  "#FFFFFF"  );
	
	$view .= form_field_report( "&nbsp;" , "<b><u>TENGGOROKAN</u></b>" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['pharynx'] , "pharynx" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['naso_pharynx'] , " - naso pharynx" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['oro_pharynx'] , " - Oro pharynx" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['laryngo_pharynx'] , " - Laryngo pharynx" ,  "#FFFFFF"  );
	
	$view .= form_field_report( "&nbsp;" , "<b><u>LARYNX</u></b>" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['supra_glotis'] , "supra glotis" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['glotis'] , "glotis" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['sub_glotis'] , "sub glotis" ,  "#FFFFFF"  );
	
	$view .= form_field_report( "&nbsp;" , "<b><u>AUDIOMETRI</u></b>" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['puretone_audiometri'] , "pure Tone Audiometri" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['sisi_test'] , "sisi test" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['tone_decay'] , "tone decay" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['impedance'] , "impedance" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['speelh_audiometri'] , "speelh audiometri" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['ringkasan'] , "<b>RINGKASAN</b>" ,  "#FFFFFF"  );
	$petugas = my_get_data_by_id('petugas' , 'petugas_id' , $fields['petugas_id']);
	$view .= form_field_report( ": ". $petugas['nama'] , "Nama Petugas" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". date("d-m-Y",strtotime($fields['created'] )), "Tanggal Dibuat" ,  "#FFFFFF"  );
	 
	$view .= report_footer( );
	return $view;
}

function paru_form_detail( ){
	$fields =   my_get_data_by_id('paru','no_mcu', $_SESSION['no_mcu']);
	
	$view = report_header( "detail paru" , "detail-paru"  );
	 
	$view .= form_field_report( ": ". $fields['pemeriksa'] , "Pemeriksa" ,  "#FFFFFF"  );
	$view .= form_field_report( "&nbsp;" , "<b><u>INSPEKSI</u></b>" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['inspeksi_statis'] , "statis" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['inspeksi_dinamis'] , "Dinamis" ,  "#FFFFFF"  );
	
	$view .= form_field_report( "&nbsp;" , "<b><u>PALPASI</u></b>" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['fremitus'] , "Fremitus ",  "#FFFFFF"  );
	
	$view .= form_field_report( "&nbsp;" , "<b><u>PERKUSI</u></b>" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['perkusi_dada'] , "Bunyi Ketok Dada" ,  "#FFFFFF"  );
	
	$view .= form_field_report( "&nbsp;" , "<b><u>AUSKULTASI</u></b>" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['auskultasi_utama'] , "Suara Napas Utama" ,  "#FFFFFF"  );
	
	$view .= form_field_report( "&nbsp;" , "Suara Napas Tambahan" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['auskultasi_ronki'] , "- Ronki" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['auskultasi_wheezing'] , "- Wheezing" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['auskultasi_lain'] , "- Lain-lain" ,  "#FFFFFF"  ); 
	$view .= form_field_report( ": ". date("d-m-Y",strtotime($fields['created'] )), "Tanggal Dibuat" ,  "#FFFFFF"  );

	 
	$view .= report_footer( );
	return $view;
}


function mata_form_detail( ){
	$fields =   my_get_data_by_id('mata','no_mcu', $_SESSION['no_mcu']);
	
	$view = report_header( "detail mata" , "detail-mata"  );
	$view .= form_field_report( ": ". $fields['pemeriksa'] , "Pemeriksa" ,  "#FFFFFF"  );	
	$view .= form_field_report( "&nbsp;" , "<b><u>MATA KANAN</u></b>" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['ketajaman_kanan'] , "TAJAM PENGLIHATAN" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['koreksi_kanan'] , "KOREKSI" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['add_kanan'] , "ADD" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['kelopak_kanan'] , "Kelopak Mata" ,  "#FFFFFF"  );
		
	$view .= form_field_report( ": ". $fields['bola_mata_kanan'] , "bola mata" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['kongjungtive_kanan'] , "Konjungtiva" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['kornea_kanan'] , "kornea" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['iris_kanan'] , "iris" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['pupil_kanan'] , "pupil" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['lensa_kanan'] , "lensa" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['fundus_kanan'] , "Fundus" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": 1. ". $fields['fundus1_kanan'] , "" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": 2. ". $fields['fundus2_kanan'] , "" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": 3. ". $fields['fundus3_kanan'] , "" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": 4. ". $fields['fundus4_kanan'] , "" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['tekanan_kanan'] , "tekanan bola mata" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['ishihara_kanan'] , "ishihara" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['amsler_grid_kanan'] , "Amsler grid" ,  "#FFFFFF"  );
	
	$view .= form_field_report( "&nbsp;" , "<b><u>MATA KIRI</u></b>" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['ketajaman kiri'] , "TAJAM PENGLIHATAN" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['koreksi_kiri'] , "KOREKSI" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['add_kiri'] , "ADD" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['kelopak_kiri'] , "Kelopak Mata" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['bola_mata_kiri'] , "bola mata" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['kongjungtive_kiri'] , "konjungtiva" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['kornea_kiri'] , "kornea" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['iris_kiri'] , "iris" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['pupil_kiri'] , "pupil" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['lensa_kiri'] , "lensa" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['fundus_kiri'] , "Fundus" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": 1. ". $fields['fundus1_kiri'] , "" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": 2. ". $fields['fundus2_kiri'] , "" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": 3. ". $fields['fundus3_kiri'] , "" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": 4. ". $fields['fundus4_kiri'] , "" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['tekanan_kiri'] , "Tekanan bola mata" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['ishihara_kiri'] , "ishihara" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['amsler_grid_kiri'] , "amsler grid" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ". $fields['ringkasan'] , "<b>RINGKASAN</b>" ,  "#FFFFFF"  );
	
 	$view .= form_field_report( ": ". date("d-m-Y",strtotime($fields['created'])) , "Tanggal Dibuat" ,  "#FFFFFF"  );
	 
	$view .= report_footer( );
	return $view;
}


function neurologi_form_detail( ){ 
	$fields =   my_get_data_by_id('neurologi','no_mcu', $_SESSION['no_mcu']);
	$view = report_header( "detail neurologi" , "detail-neurologi"  ); 
	
	$view .= form_field_report( ": ".	$fields['pemeriksa'] , "Pemeriksa" ,  "#FFFFFF"  );
	
	$view .= form_field_report( "&nbsp;" , "<b>STATUS NEUROLOGI</b>" ,  "#FFFFFF"  ); 
	
	$view .= form_field_report( "&nbsp;" , "<b>A. RANGSANG MENINGEAL :</b>" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ".$fields['kaku_kuduk'] , "- Kaku Kuduk" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ".$fields['laseque'] , "- Laseque" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ".$fields['kernig'] , "- Kernig" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ".$fields['brudzinski1'] , "- Brudzinski I" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ".$fields['brudzinski2'] , "- Brudzinski II" ,  "#FFFFFF"  );
	
	$view .= form_field_report( "&nbsp;" , "<b>B. SARAF OTAK : </b>" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ".$fields['n1'] , "- N I (Olfaktorius)" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ".$fields['n2'] , "- N II (Optikus)" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ".$fields['n3'] , "- N III (Okulomotorius)" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ".$fields['n4'] , "- N IV (Troklearis)" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ".$fields['n5'] , "- N V (Trigeminus)" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ".$fields['n6'] , "- N VI (Abducens)" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ".$fields['n7'] , "- N VII (Fasilialis)" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ".$fields['n8'] , "- N VIII (Vestibulo koklearis)" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ".$fields['n9'] , "-  N IX (Glosofaringeus)" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ".$fields['n10'] , "- N X (Vagus)" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ".$fields['n11'] , "- N XI (Assesonius)" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ".$fields['n12'] , "- N XII (Hipoglosus)" ,  "#FFFFFF"  );
	
	$view .= form_field_report( "&nbsp;" , "<b>C. SISTEM MOTORIK :</b>" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ".$fields['motorik_atas'] , "- Anggota Gerak Atas" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ".$fields['motorik_bawah'] , "- Anggota Gerak Bawah" ,  "#FFFFFF"  );
	
	$view .= form_field_report( "&nbsp;" , "<b>D. SISTEM SENSIBILITAS : </b>" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ".$fields['sensibilitas_atas'] , "- Anggota Gerak Atas" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ".$fields['sensibilitas_bawah'] , "- Anggota Gerak Bawah" ,  "#FFFFFF"  );
	
	$view .= form_field_report( "&nbsp;" , "<b>E. REFLEKS : </b>" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ".$fields['refleks_fisiologis'] , "- Refleks Fisiologis" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ".$fields['refleks_patologis'] , "- Refleks Patologis" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ".$fields['koordinasi'], "Koordinasi" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ".$fields['vegetatif'] , "Vegetatif" ,  "#FFFFFF"  );
	
	$view .= form_field_report( "&nbsp;" , "<b>F. FUNGSI LUHUR :</b>" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ".$fields['bicara_spontan'] , "- Bicara spontan" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ".$fields['mengerti_pembicaraan'] , "- Mengerti pembicaraan" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ".$fields['menghitung'] , "- Menghitung" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ".$fields['daya_ingat'] , "- Daya ingat" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ".$fields['tanda_regresi'] , "<b>G. TANDA REGRESI :</b>" ,  "#FFFFFF"  );
	
	$view .= form_field_report( ": ".$fields['kesimpulan'] , "<b>KESIMPULAN</b>" ,  "#FFFFFF"  );
	 
	$view .= form_field_report( ": ".date("d-m-Y", strtotime($fields['created'] )), "Tanggal dibuat",  "#FFFFFF"  );
	 
	$view .= report_footer( );
	return $view;
}
