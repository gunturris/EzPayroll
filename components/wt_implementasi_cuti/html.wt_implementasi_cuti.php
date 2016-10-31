<?php

function list_wt_implementasi_cuti(){
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
		'Karyawan' => array( 'width'=>'30%','style'=>'text-align:left;' ), 
		'Mulai' => array( 'width'=>'10%','style'=>'text-align:center;' ), 
		'Selesai' => array( 'width'=>'10%','style'=>'text-align:center;' ), 
		'Penganti' => array( 'width'=>'30%','style'=>'text-align:center;' ), 
		'Aksi' => array( 'width'=>'5%','style'=>'text-align:center;' ),  
	);

	
	if(isset($_GET['key'])){
		$query 	= "SELECT a.id, a.cuti_nomor , a.karyawan_id , a.over_tugas_karyawan_id , a.tanggal_mulai , a.tanggal_berakhir FROM wt_implementasi_cuti a 
		INNER JOIN karyawan b ON a.karyawan_id = b.karyawan_id
		INNER JOIN karyawan c ON a.over_tugas_karyawan_id = c.karyawan_id
		WHERE ( b.nama_karyawan LIKE '%{$_GET['key']}%' OR b.karyawan_nik = '{$_GET['key']}' )
		OR ( c.nama_karyawan LIKE '%{$_GET['key']}%' OR c.karyawan_nik = '{$_GET['key']}' )
		ORDER BY a.id DESC ";
	}else{
		$query 	= "SELECT * FROM wt_implementasi_cuti  ORDER BY id DESC";
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
		$karyawan_penganti = my_get_data_by_id('karyawan','karyawan_id',$ey['over_tugas_karyawan_id']);
		$row[] = array( 
		'Nomor' => position_text_align($ey['cuti_nomor'],  'center'),
		'Karyawan' => $karyawan['karyawan_nik'].'/ ' .$karyawan['nama_karyawan'],  
		'Tanggal' => position_text_align($ey['tanggal_mulai'],   'center'),
		'Durasi' => position_text_align($ey['tanggal_berakhir'],   'center'),
		'Penganti' =>  $karyawan_penganti['karyawan_nik'].'/ ' .$karyawan_penganti['nama_karyawan'],   
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
	return $box.table_builder($headers , $datas ,  7 , false , $paging  ); 
}


function submit_wt_implementasi_cuti($id){
	 $karyawan_id = get_karyawan_id_by_nik_name($_POST['karyawan_id']);
	 $over_tugas_karyawan_id = get_karyawan_id_by_nik_name($_POST['over_tugas_karyawan_id']);
	$datas = array();  
	 $datas['cuti_nomor']	=  my_type_data_str($_POST['cuti_nomor']);
	 $datas['cuti_tipe_id']	=  my_type_data_int($_POST['cuti_tipe_id']);
	 $datas['karyawan_id']	=  my_type_data_int($karyawan_id);
	 $datas['tanggal_mulai']	=  my_type_data_str($_POST['tanggal_mulai']);
	 $datas['tanggal_berakhir']	=  my_type_data_str($_POST['tanggal_berakhir']);
	 $datas['over_tugas_karyawan_id']	=  my_type_data_int($over_tugas_karyawan_id);
	 $datas['emergency_call']	=  my_type_data_str($_POST['emergency_call']);
	 $datas['deskripsi']	=  my_type_data_str($_POST['deskripsi']); 
	if($id > 0){
		$datas['user_updated_id']	= my_type_data_int($_SESSION['user_id']);
		$datas['updated_on']		=  my_type_data_str($_POST['updated_on']);		
		$datas['version'] = my_type_data_function(' (version + 1)');		
		return my_update_record( 'wt_implementasi_cuti' , 'id' , $id , $datas );
	}
	$datas['created_on']	= my_type_data_function('NOW()');
	$datas['version'] = my_type_data_str(0);
	return my_insert_record( 'wt_implementasi_cuti' , $datas );
}

function form_wt_implementasi_cuti_validate(){
	$errsubmit = false;
	$err = array();
		
	if(trim($_POST['cuti_nomor']) == '' ){
		$errsubmit =true;
		$err[] = "Nomor cuti belum di isi";
	}elseif( nomor_cuti_exists(trim($_POST['cuti_nomor']))){
		$errsubmit =true;
		$err[] = "Nomor cuti sudah digunakan";
	}	
	
	$karyawan_id = get_karyawan_id_by_nik_name($_POST['karyawan_id']);
	if($karyawan_id == 0 ){
		$errsubmit =true;
		$err[] = "Karyawan yang dimaksud belum benar";
	} 
	
	if((int) $_POST['cuti_tipe_id'] == 0){
		$errsubmit =true;
		$err[] = "Tipe cuti belum di pilih";
	
	}
	
	if( $errsubmit){
		return $err;
	} 
	return false;
}
	
function nomor_cuti_exists($nomor){
	$query = "SELECT * FROM wt_implementasi_cuti WHERE cuti_nomor ='{$nomor}' ";
	$result = my_query($query);
	if( my_num_rows($result) > 0){
		return true;
	}
	return false;
}	
	
function edit_wt_implementasi_cuti($id){
	my_set_code_js('  
		function findValue(li) {
			if( li == null ) return alert("No match!"); 
			if( !!li.extra ) var sValue = li.extra[0]; 
			else var sValue = li.selectValue;
		}
		function selectItemOver(li) {
			findValue(li);
		}
		
		function findValueOver(li) {
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
			$("#nama_karyawan_over").autocomplete(
				"autocomplete_daftar_nama_karyawan.php",
				{
					delay:10,
					minChars:2,
					matchSubset:1,
					matchContains:1,
					cacheLength:5,
					onItemSelect:selectItemOver,
					onFindValue:findValueOver,
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
	$view = form_header( "form_wt_implementasi_cuti" , "form_wt_implementasi_cuti"  );
	$fields = my_get_data_by_id('wt_implementasi_cuti','id', $id);
 
	
	$cuti_nomor = array(
			'name'=>'cuti_nomor',
			'value'=>(isset($_POST['cuti_nomor'])? $_POST['cuti_nomor'] : $fields['cuti_nomor']),
			'id'=>'cuti_nomor',
			'type'=>'textfield' 
		);
	$form_cuti_nomor = form_dynamic($cuti_nomor);
	$view .= form_field_display( $form_cuti_nomor  , "Nomor pelaksanaan cuti*"  );
	
	

	$cuti_tipe_ids =  array( );
	$query = "SELECT id , tipe_code , tipe_name FROM wt_tipe_cuti";	
	$result = my_query($query);
	while($row_cuti_tipe_id = my_fetch_array($result)){
		$cuti_tipe_ids[$row_cuti_tipe_id['id']] = $row_cuti_tipe_id['tipe_code'].' - '.$row_cuti_tipe_id['tipe_name'];
	}
	$cuti_tipe_id = array(
		'name'=>'cuti_tipe_id',
		'value'=>( isset($_POST['cuti_tipe_id']) ? $_POST['cuti_tipe_id'] : $fields['cuti_tipe_id']) ,
	);
	$form_cuti_tipe_id = form_dropdown($cuti_tipe_id , $cuti_tipe_ids);
	$view .= form_field_display(  $form_cuti_tipe_id   , "Tipe cuti*"    ); 
	
 
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
	$view .= form_field_display(  $form_karyawan_id   , "Karyawan*"    ); 
	
	$ftanggal_mulai = date('Y-m-d');
	if($fields){
		$ftanggal_mulai =$fields['tanggal_mulai'];
	}
	$ftanggal_berakhir = date('Y-m-d');
	if($fields){
		$ftanggal_berakhir =$fields['tanggal_berakhir'];
	}
	
	$tanggal_mulai = array(
			'name'=>'tanggal_mulai',
			'value'=>(isset($_POST['tanggal_mulai'])? $_POST['tanggal_mulai'] : $ftanggal_mulai),
			'id'=>'tanggal_mulai',
			'type'=>'textfield',
			'size'=>'45'
		);
	$form_tanggal_mulai = form_calendar($tanggal_mulai);
	$view .= form_field_display( $form_tanggal_mulai  , "Tanggal mulai" );
	

	$tanggal_berakhir = array(
			'name'=>'tanggal_berakhir',
			'value'=>(isset($_POST['tanggal_berakhir'])? $_POST['tanggal_berakhir'] : $ftanggal_berakhir),
			'id'=>'tanggal_berakhir',
			'type'=>'textfield',
			'size'=>'45'
		);
	$form_tanggal_berakhir = form_calendar($tanggal_berakhir);
	$view .= form_field_display( $form_tanggal_berakhir  , "Tanggal berakhir" );
	
 
	$karyawan_over_nik = '';

	if($fields){
		$karyawan = my_get_data_by_id('karyawan' ,'karyawan_id' ,$fields['over_tugas_karyawan_id']);
		$karyawan_over_nik = $karyawan['karyawan_nik'].'/'.$karyawan['nama_karyawan'];
	}
	$karyawan_over_id  = array(
		'id'=>'nama_karyawan_over',
		'type'=>'textfield',
		'name'=>'over_tugas_karyawan_id',
		'value'=>( isset($_POST['over_tugas_karyawan_id']) ? $_POST['over_tugas_karyawan_id'] :   $karyawan_over_nik) ,
	);
	$form_karyawan_id = form_autocomplete($karyawan_over_id  );
	$view .= form_field_display(  $form_karyawan_id   , "Karyawan pengganti sementara"    );  
	
	$emergency_call = array(
			'name'=>'emergency_call',
			'value'=>(isset($_POST['emergency_call'])? $_POST['emergency_call'] : $fields['emergency_call']),
			'id'=>'emergency_call',
			'type'=>'textfield' 
		);
	$form_emergency_call = form_dynamic($emergency_call);
	$view .= form_field_display( $form_emergency_call  , "Telepon untuk darurat"  );
	 
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
	$view .= form_footer( );	
 
	return $view .'<br /><br />';
} 
?>