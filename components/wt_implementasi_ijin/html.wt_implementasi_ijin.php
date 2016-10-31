<?php

function list_wt_implementasi_ijin(){
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
		'Nomor' => array( 'width'=>'15%','style'=>'text-align:center;' ), 
		'Karyawan' => array( 'width'=>'35%','style'=>'text-align:left;' ), 
		'Tanggal' => array( 'width'=>'15%','style'=>'text-align:center;' ), 
		'Jenis' => array( 'width'=>'30%','style'=>'text-align:center;' ), 
		'Aksi' => array( 'width'=>'5%','style'=>'text-align:center;' ), 
		
	);
 
	if(isset($_GET['key'])){
		$query 	= "SELECT * FROM wt_implementasi_ijin a 
		INNER JOIN karyawan b ON a.karyawan_id = b.karyawan_id
		WHERE b.nama_karyawan LIKE '%{$_GET['key']}%' OR b.karyawan_nik = '{$_GET['key']}'
		ORDER BY a.id DESC ";
	}else{
		$query 	= "SELECT * FROM wt_implementasi_ijin ORDER BY id DESC ";
	}
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
		$karyawan = my_get_data_by_id('karyawan','karyawan_id',$ey['karyawan_id']);
		$jenis_ijin = my_get_data_by_id('wt_tipe_ijin','tipe_id',$ey['ijin_tipe_id']);

		$row[] = array( 
		'Nomor' => position_text_align($ey['ijin_nomor'],  'center'), 
		'Karyawan' => $karyawan['karyawan_nik'].'/ ' .$karyawan['nama_karyawan'],  
		'Tanggal' => position_text_align($ey['tanggal_ijin'], 'center'),  
		'Jenis' => $jenis_ijin['tipe_code'].'/ '.$jenis_ijin['tipe_name'],   
				'op'=> position_text_align( $edit_button  .$delete_button , 'right')
		);
	}
	
	$datas = table_rows($row);
	$navigasi = array(
		'<input class="btn btn-primary" style="float:right;"  type="button" value="Tambah data" onclick="javascript:location.href=\'index.php?com='.$_GET['com'].'&task=edit\'"/>',
		 
	);
	$form_Search  =
	'<form method="GET"><div class="form-group input-group" style="width:280px">
	<input type="hidden" name="com" value="'.$_GET['com'].'" />
		<input type="text" class="form-control" name="key" value="'.( isset( $_GET['key'] ) ? $_GET['key'] : '' ).'">
		<span class="input-group-btn">
			<button class="btn btn-default" type="submit"><i class="fa fa-search"></i>
			</button>
		</span>
	
	</div></form>';
	$box = header_box( $form_Search, $navigasi );
	$paging = $kgPagerOBJ ->showPaging();
	return $box.table_builder($headers , $datas , 5 , false , $paging  ); 
}


function submit_wt_implementasi_ijin($id){
	$karyawan_id = get_karyawan_id_by_nik_name($_POST['karyawan_id']);
	

	$path_file = '../files/upload/'.date('YmdHis').'/'.str_replace(" ","_",$_FILES['lampiran_path']['name'] );
	$datas = array(); 
	$datas['ijin_nomor']	=  my_type_data_str($_POST['ijin_nomor']);
	$datas['karyawan_id']	=  my_type_data_int($karyawan_id);
	$datas['tanggal_ijin']	=  my_type_data_str($_POST['tanggal_ijin']);
	$datas['ijin_tipe_id']	=  my_type_data_int($_POST['ijin_tipe_id']);
	$datas['lampiran_title']=  my_type_data_str($_POST['lampiran_title']);
	$datas['lampiran_path']	=  my_type_data_str($path_file);
	$datas['deskripsi']		=  my_type_data_str($_POST['deskripsi']);
	 
	if($id > 0){
		$datas['user_updated_id']	= my_type_data_int($_SESSION['user_id']);  
		$datas['updated_on']	=  my_type_data_str($_POST['updated_on']);
		$datas['version'] = my_type_data_function('( version + 1 )');
		return my_update_record( 'wt_implementasi_ijin' , 'id' , $id , $datas );
	}
	$datas['created_on']	= my_type_data_function('NOW()');
	$datas['version'] = my_type_data_str(0);
	return my_insert_record( 'wt_implementasi_ijin' , $datas );
}

function form_wt_implementasi_ijin_validate(){
	$errsubmit = false;
	$err = array();
	 
	$karyawan_id = get_karyawan_id_by_nik_name($_POST['karyawan_id']);
	if($karyawan_id == 0 ){
		$errsubmit =true;
		$err[] = "Karyawan yang dimaksud belum benar";
	} 
	
	if((int) $_POST['ijin_tipe_id'] == 0){
		$errsubmit =true;
		$err[] = "Tipe ijin belum di pilih";
	
	}else{
		$tipe_ijin = my_get_data_by_id( 'wt_tipe_ijin','id' , (int) $_POST['ijin_tipe_id']);
		if($tipe_ijin['wajib_lampiran'] == '1'){
			if(!is_file($_FILES['lampiran_path']['tmp_name']) ){
				$errsubmit =true;
				$err[] = "Tipe ijin belum di pilih";
			}		
		}
	}
	
	if( $errsubmit){
		return $err;
	} 
	return false;
}
	
	
function edit_wt_implementasi_ijin($id){
	
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
	my_set_file_js(
		array(
			'assets/jquery/combomulti/jquery.chainedSelects.js',
			'assets/js/calendar/calendarDateInput.js' 
		)
	);
	my_set_file_js(
		array(
			'assets/jquery/combomulti/jquery.chainedSelects.js',
			'assets/js/calendar/calendarDateInput.js' 
		)
	);
	$view = form_header( "form_wt_implementasi_ijin" , "form_wt_implementasi_ijin"  );
	$fields = my_get_data_by_id('wt_implementasi_ijin','id', $id);
 
	$ijin_nomor = array(
			'name'=>'ijin_nomor',
			'value'=>(isset($_POST['ijin_nomor'])? $_POST['ijin_nomor'] : $fields['ijin_nomor']),
			'id'=>'ijin_nomor',
			'type'=>'textfield' 
		);
	$form_ijin_nomor = form_dynamic($ijin_nomor);
	$view .= form_field_display( $form_ijin_nomor  , "Nomot ijin"  );
	  

 
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
	
	$ftanggal_ijin = date('Y-m-d');
	if($fields){
		 $ftanggal_ijin = $fields['tanggal_ijin'];
	}
	
	$tanggal_ijin = array(
			'name'=>'tanggal_ijin',
			'value'=>(isset($_POST['tanggal_ijin'])? $_POST['tanggal_ijin'] : $ftanggal_ijin),
			'id'=>'tanggal_ijin',
			'type'=>'textfield',
			'size'=>'45'
		);
	$form_tanggal_ijin = form_calendar($tanggal_ijin);
	$view .= form_field_display( $form_tanggal_ijin  , "Tanggal_ijin" );
	

	$ijin_tipe_ids =  array( );
	$query = "SELECT tipe_id ,  tipe_code , tipe_name FROM wt_tipe_ijin";	
	$result = my_query($query);
	while($row_ijin_tipe_id = my_fetch_array($result)){
		$ijin_tipe_ids[$row_ijin_tipe_id['tipe_id']] = $row_ijin_tipe_id['tipe_code'].' - '.$row_ijin_tipe_id['tipe_name'];
	}
	$ijin_tipe_id = array(
		'name'=>'ijin_tipe_id',
		'value'=>( isset($_POST['ijin_tipe_id']) ? $_POST['ijin_tipe_id'] : $fields['ijin_tipe_id']) ,
	);
	$form_ijin_tipe_id = form_dropdown($ijin_tipe_id , $ijin_tipe_ids);
	$view .= form_field_display(  $form_ijin_tipe_id   , "Ijin tipe"    ); 
	

	
	$lampiran_title = array(
			'name'=>'lampiran_title',
			'value'=>(isset($_POST['lampiran_title'])? $_POST['lampiran_title'] : $fields['lampiran_title']),
			'id'=>'lampiran_title',
			'type'=>'textfield' 
		);
	$form_lampiran_title = form_dynamic($lampiran_title);
	$view .= form_field_display( $form_lampiran_title  , "Judul lampiran "  );
	
	

	
	$lampiran_path = array(
			'name'=>'lampiran_path',
			'value'=>(isset($_POST['lampiran_path'])? $_POST['lampiran_path'] : $fields['lampiran_path']),
			'id'=>'lampiran_path',
			'type'=>'file' 
		);
	$form_lampiran_path = form_dynamic($lampiran_path);
	$view .= form_field_display( $form_lampiran_path  , "Berkas lampiran"  );
	 
	$deskripsi = array(
			'name'=>'deskripsi',
			'value'=>(isset($_POST['deskripsi'])? $_POST['deskripsi'] : $fields['deskripsi']),
			'id'=>'deskripsi',
			'cols'=>'35' ,
			'rows'=>'4' 
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
		'onclick'=>'javascript:location.href=\''.$_SERVER['HTTP_REFERER'].'\'',
		'class'=>'main_button'
	);
	$form_cancel= form_dynamic($cancel);
	
	
	$view .= form_field_display( $form_submit .' '.$form_cancel, "&nbsp;" );
	$view .= form_footer( ) .'<br/><br/>';	
 
	return $view;
} 
?>