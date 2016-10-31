<?php

function list_pay_komponen_exception(){
	my_set_code_js('
		function confirmDelete(id){
			var t = confirm(\'Yakin akan menghapus data ?\');
			if(t){
				location.href=\'index.php?com='.$_GET['com'].'&task=delete&id=\'+id;
			}
			return false;
		}
	');	
	$headers= array( 
		'#' => array( 'width'=>'5%','style'=>'text-align:center;' ), 
		'Komponen gaji' => array( 'width'=>'25%','style'=>'text-align:left;' ), 
		'Karyawan' => array( 'width'=>'30%','style'=>'text-align:left;' ), 
		'Nominal tetap' => array( 'width'=>'15%','style'=>'text-align:center;' ), 
		'Pajak tetap' => array( 'width'=>'15%','style'=>'text-align:center;' ), 
		'Aksi' => array( 'width'=>'10%','style'=>'text-align:center;' ), 
		
	);

	
	
	$query 	= "SELECT * FROM view_pay_komponen_exception ";
	$result = my_query($query);
	
	//PAGING CONTROL START
	$total_records = my_num_rows($result );
	$scroll_page = SCROLL_PERHALAMAN;  
	$per_page = PAGING_PERHALAMAN;  
	$current_page = isset($_GET['halaman']) ? (int) $_GET['halaman'] : 1 ; 
	if($current_page < 1){
		$current_page = 1;
	}		 
	$task = isset($_GET['task']) ?$_GET['task'] :'' ;
	$field = isset($_GET['field']) ?$_GET['field'] :'' ;
	$key = isset($_GET['key']) ?$_GET['key'] :'' ;
	$pager_url  ="index.php?com={$_GET['com']}&task={$task}&field={$field}&key={$key}&halaman=";	 
	$pager_url_last='';
	$pager_url_last='';
	$inactive_page_tag = 'style="padding:4px;background-color:#BBBBBB"';  
	$previous_page_text = '<i class="fa fa-angle-left fa-fw"></i>'; 
	$next_page_text = '<i class="fa fa-angle-right fa-fw"></i>';  
	$first_page_text = '<i class="fa fa-angle-double-left fa-fw"></i>'; 
	$last_page_text = '<i class="fa fa-angle-double-right fa-fw"></i>';
	
	$kgPagerOBJ = new kgPager();
	$kgPagerOBJ->pager_set(
		$pager_url, 
		$total_records, 
		$scroll_page, 
		$per_page, 
		$current_page, 
		$inactive_page_tag, 
		$previous_page_text, 
		$next_page_text, 
		$first_page_text, 
		$last_page_text ,
		$pager_url_last
		); 
	 		
	$result = my_query($query ." LIMIT ".$kgPagerOBJ->start.", ".$kgPagerOBJ->per_page);  
	$i = ($current_page  - 1 ) * $per_page ;
	//PAGING CONTROL END
	
	$row = array();
	while($ey = my_fetch_array($result)){
		$i++;
		$editproperty = array(
				'href'=>'index.php?com='.$_GET['com'].'&task=edit&id=' . $ey['id'] , 
				'title'=>'Edit'
		);	
		$edit_button = button_icon( 'b_edit.png' , $editproperty  );

		$deleteproperty = array(
			'href'=>'javascript:confirmDelete('.$ey['id'].');',
			'title'=>'Delete', 
		);
		$delete_button = button_icon( 'b_drop.png' , $deleteproperty  );
		
		if( (int) $ey['pajak'] == 0){
			$pajak_tetap = '&nbsp;';
		}else{
			$pajak_tetap ='Rp. '.rp_format($ey['pajak']);
		}
		
		if( (int) $ey['nominal'] == 0 ){
			$nominal_tetap = '&nbsp;' ;
		}else{
			$nominal_tetap = 'Rp. '.rp_format($ey['nominal']);
		}	
		
		$row[] = array( 
		'#' => position_text_align($i, 'center'),  
		'Komponen gaji' => $ey['komponen_kode'].'/ '.$ey['komponen_name'],  
		'Karyawan' => $ey['nik'] .'/ '.$ey['nama'],  
		'Nominal tetap' => position_text_align( $nominal_tetap ,'right'),  
		'Proses pajak' =>position_text_align(  $pajak_tetap,   'right'),
		'op'=> position_text_align( $edit_button  .$delete_button , 'center')
		);
	}
	
	$datas = table_rows($row);
	$navigasi = array(
		'<input class="btn btn-primary" style="float:right;"  type="button" value="Tambah data" onclick="javascript:location.href=\'index.php?com='.$_GET['com'].'&task=edit\'"/>',
		 
	);
	$box = header_box( '&nbsp;' , $navigasi );
	$paging = $kgPagerOBJ ->showPaging();
	return $box.table_builder($headers , $datas ,  6, false , $paging  ); 
}


function submit_pay_komponen_exception($id){
	 
	$datas = array(); 
	$karyawan_id = get_karyawan_id_by_nik_name($_POST['karyawan_id']);
	$datas['pay_komponen_gaji_id']	=  my_type_data_str($_POST['pay_komponen_gaji_id']);
	$datas['karyawan_id']		=  my_type_data_str($karyawan_id);
	
	if(trim($_POST['komponen_nominal_tetap'] ) <> '')
		$datas['komponen_nominal_tetap']=  my_type_data_str($_POST['komponen_nominal_tetap']);
	else $datas['komponen_nominal_tetap']=  my_type_data_function('NULL');
	
	
	
	if(trim($_POST['pajak_nominal_tetap']) <> '')
		$datas['pajak_nominal_tetap']	=  my_type_data_str($_POST['pajak_nominal_tetap']);
	else $datas['pajak_nominal_tetap']=  my_type_data_function('NULL');
	
	$datas['deskripsi']			=  my_type_data_str($_POST['deskripsi']);
	
	$datas['user_updated_id']	= my_type_data_int($_SESSION['user_id']);  

	if($id > 0){
		$datas['updated_on']		=  my_type_data_function('NOW()');
		$datas['version'] 			= my_type_data_function('(version + 1)');
		return my_update_record( 'pay_komponen_exception' , 'pay_komponen_exception_id' , $id , $datas );
	}
	
	$datas['created_on']		= my_type_data_function('NOW()');
	$datas['version'] 			= my_type_data_int(0);
	return my_insert_record( 'pay_komponen_exception' , $datas );
}

function form_pay_komponen_exception_validate(){
	
	$errsubmit = false;
	$err = array();
	
	if( (int) $_POST['pay_komponen_gaji_id'] == 0){
		$errsubmit =true;
		$err[] = "Komponen gaji belum dipilih";	
	}
	
	$karyawan_id = get_karyawan_id_by_nik_name($_POST['karyawan_id']);
	if($karyawan_id == 0 ){
		$errsubmit =true;
		$err[] = "Karyawan yang dimaksud belum benar";
	}
	
	if( (trim($_POST['komponen_nominal_tetap'] ) =='')  AND (trim($_POST['pajak_nominal_tetap']) == '') ){
		$errsubmit =true;
		$err[] = "Tidak ada data eksepsi";
	}
	
	if( $errsubmit){
		return $err;
	} 
	return false;
}
	
	
function edit_pay_komponen_exception($id){
	my_set_code_js('  
		function findValue(li) {
			if( li == null ) return alert("No match!"); 
			if( !!li.extra ) var sValue = li.extra[0]; 
			else var sValue = li.selectValue;
		}

		function selectItem(li) {
			findValue(li);
		}

		function formatItem(row) {
			return   row[0] ;
		}

		function lookupAjax(){
			var oSuggest = $("#nama_karyawan")[0].autocompleter;
			oSuggest.findValue(); 
			return false;
		}
		$(document).ready(function() {
			$("#nama_karyawan").autocomplete(
				"autocomplete_daftar_nama_karyawan.php",
				{
					delay:10,
					minChars:2,
					matchSubset:1,
					matchContains:1,
					cacheLength:5,
					onItemSelect:selectItem,
					onFindValue:findValue,
					formatItem:formatItem,
					autoFill:true
				}
			);
			 
		});
/*
		function checkName(name){
			$.get("check_karyawan_names.php", { nama: name  },
			   function(data){
				 if(data ==  \'0\' ){
					alert(\'Nama karyawan \'+ name+\' tidak ditemukan\nHarap diperiksa kembali\');
					$(\'#nama_karyawan\').val(\'\');
					return false;
				 } 
			   });
		}*/
	');	
	 
	my_set_file_js(
		array(
			'assets/jquery/autocomplete/jquery.autocomplete.js'  
		)
	);
	my_set_file_css(
		array(
				'assets/jquery/autocomplete/jquery.autocomplete.css' 
			)
	); 
	$view = form_header( "form_pay_komponen_exception" , "form_pay_komponen_exception"  );
	$fields = my_get_data_by_id('pay_komponen_exception','pay_komponen_exception_id', $id);

	$pay_komponen_gaji_ids =  array( );
	$query = "SELECT pay_komponen_gaji_id , pay_komponen_gaji_code,pay_komponen_gaji_label FROM pay_komponen_gaji";	
	$result = my_query($query);
	while($row_pay_komponen_gaji_id = my_fetch_array($result)){
		$pay_komponen_gaji_ids[$row_pay_komponen_gaji_id['pay_komponen_gaji_id']] = $row_pay_komponen_gaji_id['pay_komponen_gaji_code'].'/ '.$row_pay_komponen_gaji_id['pay_komponen_gaji_label'];
	}
	$pay_komponen_gaji_id = array(
		'name'=>'pay_komponen_gaji_id',
		'value'=>( isset($_POST['pay_komponen_gaji_id']) ? $_POST['pay_komponen_gaji_id'] : $fields['pay_komponen_gaji_id']) ,
	);
	$form_pay_komponen_gaji_id = form_dropdown($pay_komponen_gaji_id , $pay_komponen_gaji_ids);
	$view .= form_field_display(  $form_pay_komponen_gaji_id   , "Pay komponen gaji"    ); 
	
	$karyawan_nik = '';

	if($fields){
		$karyawan = my_get_data_by_id('karyawan' ,'karyawan_id' ,$fields['karyawan_id']);
		$karyawan_nik = $karyawan['karyawan_nik'].'/'.$karyawan['nama_karyawan'];
	}
	

	$karyawan_id  = array(
		'id'=>'nama_karyawan',
		'type'=>'textfield',
		'name'=>'karyawan_id',
		'value'=>( isset($_POST['karyawan_id']) ? $_POST['karyawan_id'] :   $karyawan_nik) ,
	);
	$form_karyawan_id = form_autocomplete($karyawan_id  );
	$view .= form_field_display(  $form_karyawan_id   , "Karyawan"    ); 
	 
	$komponen_nominal_tetap = array(
			'name'=>'komponen_nominal_tetap',
			'value'=>(isset($_POST['komponen_nominal_tetap'])? $_POST['komponen_nominal_tetap'] : $fields['komponen_nominal_tetap']),
			'id'=>'komponen_nominal_tetap',
			'type'=>'textfield' 
		);
	$form_komponen_nominal_tetap = form_dynamic($komponen_nominal_tetap);
	$view .= form_field_display( $form_komponen_nominal_tetap 
		  , "Nominal eksepsi<br /><font size='1'><i>Kosongkan jika 
		  tidak ada eksepsi</i></font> "  );
	
	$pajak_nominal_tetap = array(
			'name'=>'pajak_nominal_tetap',
			'value'=>(isset($_POST['pajak_nominal_tetap'])? $_POST['pajak_nominal_tetap'] : $fields['pajak_nominal_tetap']),
			'id'=>'pajak_nominal_tetap',
			'type'=>'textfield' 
		);
	$form_pajak_nominal_tetap = form_dynamic($pajak_nominal_tetap);
	$view .= form_field_display( $form_pajak_nominal_tetap  , "Pajak tetap" 
.'<br /><font size="1">Kosongkan jika nominal pajak oleh system</font>'	);
	
	 
	$deskripsi = array(
			'name'=>'deskripsi',
			'value'=>(isset($_POST['deskripsi'])? $_POST['deskripsi'] : $fields['deskripsi']),
			'id'=>'deskripsi',
			'rows'=>'3' 
		);
	$form_deskripsi = form_textarea($deskripsi);
	$view .= form_field_display( $form_deskripsi  , "Keterangan"  );
	
		 
	$submit = array(
		'value' => ( $id ==0 ? ' Simpan ' :'  Update  '),
		'name' => 'simpan', 
		'type'=>'submit','class'=>'main_button'
	);
	$form_submit= form_dynamic($submit);
	
	$cancel = array(
		'value' => (  ' Batal '  ),
		'name' => 'cancel', 
		'type'=>'reset',
		'onclick'=>'javascript:location.href=\''.$_SERVER['HTTP_REFERER'].'\'',
		'class'=>'main_button'
	);
	$form_cancel= form_dynamic($cancel);
	
	
	$view .= form_field_display( $form_submit .' '.$form_cancel, "&nbsp;" );
	$view .= form_footer( );	
 
	return $view;
} 


?>