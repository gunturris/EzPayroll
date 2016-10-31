<?php

function list_komponen(){
	$headers= array( 
		'Komponen gaji' => array( 'width'=>'50%','style'=>'text-align:left;' ), 
		'Karyawan' => array( 'width'=>'15%','style'=>'text-align:center;' ), 
		'Nominal ' => array( 'width'=>'25%','style'=>'text-align:right;' ), 
		'Aksi' => array( 'width'=>'10%','style'=>'text-align:center;' ), 
	);
		
	
	$query 	= "SELECT * FROM pay_komponen_gaji 
		WHERE pay_model_komponen_gaji_id = 5 ";
	$result = my_query($query);
	$row = array();
	$i =0;
	while($ey = my_fetch_array($result)){
		$i++;
		$detailproperty = array(
				'href'=>'index.php?com='.$_GET['com'].'&task=list_karyawan_by_komponen&komponen_id=' . $ey['pay_komponen_gaji_id'] , 
				'title'=>'Daftar karyawan'
		);	 
		$detail_button = button_icon( 'b_props.png' , $detailproperty  );
		$dt = get_total_data_komponen($ey['pay_komponen_gaji_id']);
		$row[] = array( 
			'Komponen gaji' => $ey['pay_komponen_gaji_code'].'/ '.$ey['pay_komponen_gaji_label'],  
			'Karyawan'		=>  position_text_align( $dt['nkaryawan'], 'center'), 
			'Nominal tetap' => position_text_align( 'Rp. '.rp_format($dt['ntotal']), 'right'),
			'op' 	=> position_text_align(  $detail_button , 'center')
		);
	}
	
	$datas = table_rows($row);
  
	return  table_builder($headers , $datas ,5  , false   ); 
}


function list_pay_komponen_manual($komponen_id =0){
 
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
	 	'ID' => array( 'width'=>'5%','style'=>'text-align:center;' ), 
		'Karyawan' => array( 'width'=>'30%','style'=>'text-align:left;' ), 
		'Nominal tetap' => array( 'width'=>'15%','style'=>'text-align:right;' ), 
		'Deskripsi' => array( 'width'=>'40%','style'=>'text-align:center;' ), 
		'Aksi' => array( 'width'=>'10%','style'=>'text-align:center;' ), 
	);

	
	
	$query 	= "SELECT * FROM pay_komponen_manual 
			WHERE pay_komponen_gaji_id = {$komponen_id} ";
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
	$pager_url  ="index.php?com={$_GET['com']}&task={$task}&komponen_id={$komponen_id}&field={$field}&key={$key}&halaman=";	 
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
				'href'=>'index.php?com='.$_GET['com'].'&task=edit&komponen_id='.$ey['pay_komponen_gaji_id'].'&id=' . $ey['pay_komponen_manual_id'] , 
				'title'=>'Edit'
		);	
		$edit_button = button_icon( 'b_edit.png' , $editproperty  );

		$deleteproperty = array(
			'href'=>'javascript:confirmDelete('.$ey['pay_komponen_manual_id'].');',
			'title'=>'Delete', 
		);
		$delete_button = button_icon( 'b_drop.png' , $deleteproperty  );

		$komponen = my_get_data_by_id('pay_komponen_gaji','pay_komponen_gaji_id',$ey['pay_komponen_gaji_id'] );
		$karyawan = my_get_data_by_id('karyawan','karyawan_id', $ey['karyawan_id']);
		
		$row[] = array( 
		 	'ID' =>  position_text_align($i,  'center'),
			'Karyawan'		=> $karyawan['karyawan_nik'].'/ '. $karyawan['nama_karyawan'],  
			'Nominal tetap' => position_text_align( 'Rp. '. rp_format($ey['nominal_tetap']) , 'right'),  
			'Deskripsi'		=> $ey['deskripsi'], 
			'op' 	=> position_text_align( $edit_button  .$delete_button , 'center')
		);
	}
	
	$datas = table_rows($row);
	$navigasi = array(
		'<input class="btn btn-primary"  style="margin:0 5px 0 5px; float:right;"  
			type="button" value="Tambah data" 
			onclick="javascript:location.href=\'index.php?com='.$_GET['com'].'&task=edit&komponen_id='.$komponen_id.'\'"/>',
		 ' <input class="btn btn-primary" style="float:right;"  
			type="button" value="Upload data" 
			onclick="javascript:location.href=\'index.php?com='.$_GET['com'].'&task=upload&komponen_id='.$komponen_id.'\'"/>',
		 
	);
	$komponen = my_get_data_by_id('pay_komponen_gaji','pay_komponen_gaji_id',$komponen_id);
	$datas_resume = get_total_data_komponen($komponen_id);
	$view = form_header( "form_pay_komponen_manual" , "form_pay_komponen_manual"  );
	$view  .= form_field_display(  '<br/> &nbsp; &nbsp; '.$komponen['pay_komponen_gaji_code'].'/'.$komponen['pay_komponen_gaji_label']   , "Komponen gaji"    ); 
	$view .= form_field_display(  '<br/> &nbsp; &nbsp; '. ( (int) $datas_resume['nkaryawan'] == 0 ? 'Tidak ada ':$datas_resume['nkaryawan'] .' orang')   , "Jumlah karyawan"    ); 
	$view .= form_field_display( '<br/> &nbsp; &nbsp; '.'Rp. '.rp_format($datas_resume['ntotal']) , "Total nominal"    ); 
	$view  .= form_footer();
	$box = header_box( '&nbsp;' , $navigasi );
	$paging = $kgPagerOBJ ->showPaging();
	return $view  . $box.table_builder($headers , $datas ,5  , false , $paging  ).'<br />'; 
}

function get_total_data_komponen($komponen_id){
	$query = "SELECT COUNT(*) AS nkaryawan , SUM(nominal_tetap) AS ntotal 
		FROM pay_komponen_manual WHERE pay_komponen_gaji_id = {$komponen_id}";
	$result = my_query($query );
	$row = my_fetch_array($result);
	return $row;
}


function submit_pay_komponen_manual($id){
	 
	$datas = array();
	$karyawan_id = get_karyawan_id_by_nik_name($_POST['karyawan_id']);
	
	$datas['pay_komponen_gaji_id']	=  my_type_data_str($_GET['komponen_id']);
	$datas['karyawan_id']	=  my_type_data_int($karyawan_id );
	$datas['nominal_tetap']	=  my_type_data_int($_POST['nominal_tetap']);
	$datas['deskripsi']	=  my_type_data_str($_POST['deskripsi']); 
		$datas['user_updated_id']	= my_type_data_int($_SESSION['user_id']);  
		 
	if($id > 0){
		$datas['version']	= my_type_data_function('(version + 1)');
		$datas['updated_on']	= my_type_data_function('NOW()');
		return my_update_record( 'pay_komponen_manual' , 'pay_komponen_manual_id' , $id , $datas );
	}
	
	$datas['version']	= my_type_data_int('0');
	$datas['created_on']	= my_type_data_function('NOW()');
	return my_insert_record( 'pay_komponen_manual' , $datas );
}

function form_pay_komponen_manual_validate(){
	return false;
}
	
	
function edit_pay_komponen_manual($id, $komponen_id){
	
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
	$view = form_header( "form_pay_komponen_manual" , "form_pay_komponen_manual"  );
	$fields = my_get_data_by_id('pay_komponen_manual','pay_komponen_manual_id', $id);

	$komponen = my_get_data_by_id('pay_komponen_gaji','pay_komponen_gaji_id',$komponen_id);
	$pay_komponen_gaji_id  = array(
		'id'=>'nama_komponen',
		'readonly'=>'readonly',
		'type'=>'textfield',
		'name'=>'pay_komponen_gaji_id',
		'value'=>(  $komponen['pay_komponen_gaji_code'].'/ '. $komponen['pay_komponen_gaji_label']) ,
	);
	$form_pay_komponen_gaji_id = form_autocomplete($pay_komponen_gaji_id  );
	$view .= form_field_display(  $form_pay_komponen_gaji_id   , "Komponen gaji"    ); 
	 
	 $karyawan_nik ='';
	 if($fields){
		$karyawan = my_get_data_by_id('karyawan','karyawan_id',$fields['karyawan_id']);
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
	 
	$nominal_tetap = array(
			'name'=>'nominal_tetap',
			'value'=>(isset($_POST['nominal_tetap'])? $_POST['nominal_tetap'] : $fields['nominal_tetap']),
			'id'=>'nominal_tetap',
			'type'=>'textfield' 
		);
	$form_nominal_tetap = form_dynamic($nominal_tetap);
	$view .= form_field_display( $form_nominal_tetap  , "Nominal tetap"  );
	
	

	
	$deskripsi = array(
			'name'=>'deskripsi',
			'value'=>(isset($_POST['deskripsi'])? $_POST['deskripsi'] : $fields['deskripsi']),
			'id'=>'deskripsi',
			'rows'=>'3' 
		);
	$form_deskripsi = form_textarea($deskripsi);
	$view .= form_field_display( $form_deskripsi  , "Deskripsi"  );
	
		 
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
		'onclick'=>'javascript:location.href=\'index.php?com='.$_GET['com'].'\'',
		'class'=>'main_button'
	);
	$form_cancel= form_dynamic($cancel);
	
	
	$view .= form_field_display( $form_submit .' '.$form_cancel, "&nbsp;" );
	$view .= form_footer( );	
 
	return $view;
} 

function komponen_upload($komponen_id){
	$view = form_header( "form_pay_komponen_manual" , "form_pay_komponen_manual"  );
	 
	$komponen = my_get_data_by_id('pay_komponen_gaji','pay_komponen_gaji_id',$komponen_id);
	$pay_komponen_gaji_id  = array(
		'id'=>'nama_komponen',
		'readonly'=>'readonly',
		'type'=>'textfield',
		'name'=>'pay_komponen_gaji_id',
		'value'=>(  $komponen['pay_komponen_gaji_code'].'/ '. $komponen['pay_komponen_gaji_label']) ,
	);
	$form_pay_komponen_gaji_id = form_autocomplete($pay_komponen_gaji_id  );
	$view .= form_field_display(  $form_pay_komponen_gaji_id   , "Komponen gaji"    ); 
  
	$file_upload  = array(
		'id'=>'file_upload',
		'type'=>'file',
		'name'=>'file_upload',
			);
	$form_karyawan_id = form_dynamic($file_upload  );
	$view .= form_field_display(  $form_karyawan_id
	.'<span style="font-size:10px;font-face:verdana;">
	<a href="sample_komponen_manual.csv">Download template</a></span>'  , "Upload csv"    ); 
	  
	
	$deskripsi = array(
			'name'=>'deskripsi',
			'value'=>(isset($_POST['deskripsi'])? $_POST['deskripsi'] : ''),
			'id'=>'deskripsi',
			'rows'=>'3' 
		);
	$form_deskripsi = form_textarea($deskripsi);
	$view .= form_field_display( $form_deskripsi   , "Deskripsi"  );
	
		 
	$submit = array(
		'value' => (   '  Upload  '),
		'name' => 'simpan', 
		'type'=>'submit','class'=>'main_button'
	);
	$form_submit= form_dynamic($submit);
	
	$cancel = array(
		'value' => (  ' Batal '  ),
		'name' => 'cancel', 
		'type'=>'reset',
		'onclick'=>'javascript:location.href=\'index.php?com='.$_GET['com'].'\'',
		'class'=>'main_button'
	);
	$form_cancel= form_dynamic($cancel);
	
	
	$view .= form_field_display( $form_submit .' '.$form_cancel, "&nbsp;" );
	$view .= form_footer( );	
 
	return $view;
}


function form_pay_komponen_upload_validate(){
	
	$errsubmit = false;
	$err = array();
	$allow = array('csv');
	$filename = $_FILES['file_upload']['name']; 
	$file_explode = explode(".", $filename);
	$file_extension = end($file_explode);
	if( ! in_array($file_extension , $allow) ){
		$errsubmit =true;
		$err[] = "File yang diupload tidak dikenal";	
	} 
	
	if( $errsubmit){
		return $err;
	} 
	return false;
}

function form_upload_proses(){
	
	$dest ='../files/csv/upload_manual_'.date('Ymd_his').'.csv';
	$ori = $_FILES['file_upload']['tmp_name'];
	$upload = move_uploaded_file($ori , $dest); 
	if($upload){
		$handle = fopen($dest, "r"); 
		while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
			if(trim($data[0])<>'' && $data[1]<> ''){
			$nik = sprintf('%07d',$data[0]);
			if((int)$nik == 0)continue; 
			
			$karyawan_id = check_karyawan_by_nik($nik);
			insert_data_upload($karyawan_id, $data[1] );
			}
		}
		fclose($handle);
	}
	return $dest ;
}

function insert_data_upload( $karyawan_id, $nominal){
	$query = "SELECT * FROM pay_komponen_manual 
		WHERE pay_komponen_gaji_id = {$_GET['komponen_id']}
		AND karyawan_id = 	{$karyawan_id} ";
	$res = my_query($query);
	if(my_num_rows($res) > 0){
		return false;
	}
	$datas = array(
		'pay_komponen_gaji_id' => my_type_data_int($_GET['komponen_id']),	
		'karyawan_id' => my_type_data_int($karyawan_id),	
		'nominal_tetap' => my_type_data_str($nominal),	
		'deskripsi' => my_type_data_str($_POST['deskripsi']),	
	);
	return my_insert_record('pay_komponen_manual' ,$datas);	
}
	
?>