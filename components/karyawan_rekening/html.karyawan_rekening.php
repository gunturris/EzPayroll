<?php

function list_karyawan(){
	
	$headers= array(  
		'NIK' => array( 'width'=>'15%','style'=>'text-align:center;' ), 
		'Nama karyawan' => array( 'width'=>'55%','style'=>'text-align:left;' ), 
		'Umur' => array( 'width'=>'10%','style'=>'text-align:center;' ), 
		'Rekening' => array( 'width'=>'10%','style'=>'text-align:center;' ), 
		'Aksi' => array( 'width'=>'10%','style'=>'text-align:center;' ), 
		
	);

	
	
	$query 	= "SELECT * FROM karyawan ";
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
		$detailproperty = array(
				'href'=>'index.php?com='.$_GET['com'].'&task=rekening&id=' . $ey['karyawan_id'] , 
				'title'=>'Lihat daftar rekening'
		);	
		$detail_button = button_icon( 'b_props.png' , $detailproperty  ); 
		
		$jumlah_rekening = jumlah_rekening( $ey['karyawan_id']);  
		$umur = getage($ey['tanggal_lahir']);
		$row[] = array( 
		'NIK' =>  position_text_align($ey['karyawan_nik'], 'center'),    
		'Nama karyawan' => $ey['nama_karyawan'],  
		'Umur' => position_text_align($umur, 'center'),  
		'Rekening' => position_text_align($jumlah_rekening , 'center'),    
		'op'=> position_text_align( $detail_button    , 'center')
		);
	}
	
	$datas = table_rows($row);
	$navigasi = array(
	//	'<input class="btn btn-primary" style="float:right;"  type="button" value="Tambah data" onclick="javascript:location.href=\'index.php?com='.$_GET['com'].'&task=edit\'"/>',
	);
	$form_Search  =
	'<form method="GET"><div class="form-group input-group" style="width:280px">
	<input type="hidden" name="com" value="'.$_GET['com'].'" />
		<input type="text" class="form-control" name="key">
		<span class="input-group-btn">
			<button class="btn btn-default" type="submit"><i class="fa fa-search"></i>
			</button>
		</span>
	
	</div></form>';
	$box = header_box( $form_Search , $navigasi );
	$paging = $kgPagerOBJ ->showPaging();
	return $box.table_builder($headers , $datas ,  6 , false , $paging  ); 
}

function jumlah_rekening($karyawan_id){

	$query = "SELECT * FROM karyawan_bank_account 
		WHERE karyawan_id = {$karyawan_id}";
	$result = my_query($query);
	return my_num_rows($result);
	
}


function detail_rekening($karyawan_id = 0){ 
my_set_code_js('
		function confirmDelete(id){
			var t = confirm(\'Yakin akan menghapus data ?\');
			if(t){
				location.href=\'index.php?com='.$_GET['com'].'&task=delete&id=\'+id;
			}
			return false;
		}
	');	
my_set_code_js('
$(document).ready(
			function(){
				$(".txt").each(
					function(){
						$(this).change(
							function(){
								calculateSum(); 
							}
						);
					}
				);
			}
		);
	function calculateSum(){
		var sum=0;
		$(".txt").each(
			function(){
				if(!isNaN(this.value)&&this.value.length!=0){
					sum+=parseFloat(this.value);
				}
			}
		); 
		var tt = 100 - sum;
		 
		$("#sum").html(sum.toFixed(0)); 
		$("#sum2").html(tt.toFixed(0));
		$("#sum3").val(tt);
	} 
');
	$karyawan = my_get_data_by_id( 'karyawan' ,'karyawan_id' , $karyawan_id);
	$karyawan_status = my_get_data_by_id( 'karyawan_status' ,'karyawan_status_id' , $karyawan['karyawan_status_id'] );
	$karyawan_gol_jab = my_get_data_by_id( 'karyawan_gol_jab' ,'karyawan_gol_jab_id' , $karyawan['karyawan_gol_jab_id'] );
	 
	$view = '  <h4 id="grid-column-ordering">'.$karyawan['karyawan_nik'].' / '.$karyawan['nama_karyawan'].'</h3>
				<h5>'.$karyawan_status['karyawan_status_label'].' ('.$karyawan_gol_jab['karyawan_gol_jab_label'].') </h5>	
				'.( trim($karyawan['alamat']) <> '' ? '<p><b>Alamat</b><br/><i>'.$karyawan['alamat'].'<br/> '.$karyawan['alamat_kota'].'</i></p>' : '').'
				' ;
	$view .= get_view_rekening($karyawan_id);
	return $view;
}


function get_view_rekening(  $karyawan_id   ){
	
	$query = "SELECT * FROM karyawan_bank_account a
		INNER JOIN bank b ON a.bank_id = b.bank_id
		WHERE a.karyawan_id = {$karyawan_id} ORDER BY rekening_id ASC";
	$result = my_query($query); 
	 
		$headers= array(  
			'Bank' => array( 'width'=>'15%','style'=>'text-align:center;' ), 
			'Nama rekening' => array( 'width'=>'20%','style'=>'text-align:left;' ), 
			'Nomor ' => array( 'width'=>'15%','style'=>'text-align:center;' ),   
			'Deskripsi' => array( 'width'=>'35%','style'=>'text-align:center;' ),   
			'Persen' => array( 'width'=>'7%','style'=>'text-align:center;' ),   
			'Aksi' => array( 'width'=>'8%','style'=>'text-align:center;' ),   
			
		);
		$row = array();
		$total_persen = 0;
		while( $ey = my_fetch_array($result) ){
			
			$deleteproperty = array(
				'href'=>'javascript:confirmDelete('.$ey['rekening_id'].');',
				'title'=>'Delete', 
			);
			$delete_button = button_icon( 'b_drop.png' , $deleteproperty  );
			
			$editproperty = array(
				'href'=>'index.php?com='.$_GET['com'].'&id='.$karyawan_id.'&task=edit&rekening_id=' . $ey['rekening_id'] , 
				'title'=>'Edit'
			);	
			$edit_button = button_icon( 'b_edit.png' , $editproperty  );
			
			
			$form_persen = '<input name="pers['.$ey['rekening_id'].']" type="number" style="width:50px;text-align:right"  class="txt" value="'.$ey['persen'].'"/>';
			$row[] = array( 
				'Bank' =>   $ey['bank_name'],    
				'Nama rekening' => $ey['account_name'],   
				'Nomor' => $ey['account_number'],   
				'Deskripsi' => $ey['bank_detail'] ,  
				'Persen' => $form_persen ,  
				'Aksi' =>  position_text_align($edit_button . $delete_button,'center')  
			);
			$total_persen += $ey['persen'];
		}
		$row[] = array( 
				'Bank' =>  '&nbsp;',    
				'Nama rekening' =>'&nbsp;',   
				'Nomor' => '&nbsp;',   
				'Deskripsi' =>   '<b>Total  </b>',  
				'Persen' => position_text_align('<span id="sum">'.$total_persen.'</span>' ,'center'),  
				'Aksi' => '&nbsp;',  
			);
		$datas = table_rows($row);
		$navigasi = array(
			' <input class="btn btn-primary" style="float:right;"  type="button" value="Edit persen" onclick="javascript:document.form_persen.submit()"/>',
			' <input class="btn btn-primary" style="float:right;"  type="button" value="Tambah data" onclick="javascript:location.href=\'index.php?com='.$_GET['com'].'&task=edit&id='.$karyawan_id.'\'"/>',
		);
		$box = header_box( '&nbsp;', $navigasi );
		return '<form method="post" name="form_persen" id="form_persen">'.$box . table_builder($headers , $datas ,  6 , false ).'</form>';
	 
}

function set_auto_portion($karyawan_id){
	$query = "SELECT * FROM karyawan_bank_account 
		WHERE karyawan_id = {$karyawan_id} 
		ORDER BY rekening_id ASC";
	$result = my_query($query);
	$num_rows =  my_num_rows($result); 
	$sisa =  (int) (20 %  $num_rows);   
	$items =     round( ( 20 - $sisa ) / $num_rows ); 
	$i = 0;
	while($ey = my_fetch_array( $result )){
		
		if($i == 0){
			$persen = 5 * ($items + $sisa);
		}else{
			$persen = 5 * $items;
		} 
		$datas_update = array(
			'persen'=> my_type_data_int( $persen)
		);
		my_update_record('karyawan_bank_account' , 'rekening_id', $ey['rekening_id'] , $datas_update);
		$i++;
	}
	return true;
}

	
function edit_karyawan_rekening($id , $rekening_id){
	
	$fields = my_get_data_by_id('karyawan_bank_account','rekening_id', $rekening_id);
	my_set_file_js(
		array(
			'assets/jquery/combomulti/jquery.chainedSelects.js',
			'assets/js/calendar/calendarDateInput.js' 
		)
	);
	$karyawan = my_get_data_by_id( 'karyawan' ,'karyawan_id' , $id);
	$karyawan_status = my_get_data_by_id( 'karyawan_status' ,'karyawan_status_id' , $karyawan['karyawan_status_id'] );
	$karyawan_gol_jab = my_get_data_by_id( 'karyawan_gol_jab' ,'karyawan_gol_jab_id' , $karyawan['karyawan_gol_jab_id'] );
	 
	$view = '  <h4 id="grid-column-ordering">'.$karyawan['karyawan_nik'].' / '.$karyawan['nama_karyawan'].'</h3>
				<h5>'.$karyawan_status['karyawan_status_label'].' ('.$karyawan_gol_jab['karyawan_gol_jab_label'].') </h5>	
				'.( trim($karyawan['alamat']) <> '' ? '<p><b>Alamat</b><br/><i>'.$karyawan['alamat'].'<br/> '.$karyawan['alamat_kota'].'</i></p>' : '').'
				' ;
	$view .= form_header( "form_karyawan_rekening" , "form_karyawan_rekening"  );


	$bank_ids =  array( );
	$query = "SELECT bank_id , bank_name FROM bank";	
	$result = my_query($query);
	while($row_bank_id = my_fetch_array($result)){
		$bank_ids[$row_bank_id['bank_id']] = $row_bank_id['bank_name'];
	}
	$bank_id = array(
		'name'=>'bank_id',
		'value'=>( isset($_POST['bank_id']) ? $_POST['bank_id'] : $fields['bank_id']) ,
	);
	$form_bank_id = form_dropdown($bank_id , $bank_ids);
	$view .= form_field_display(  $form_bank_id   , "Bank"    ); 
	 	
	$account_name = array(
			'name'=>'account_name',
			'value'=>(isset($_POST['account_name'])? $_POST['account_name'] : $fields['account_name']),
			'id'=>'account_name',
			'type'=>'textfield' 
		);
	$form_karyawan_account_name = form_dynamic($account_name);
	$view .= form_field_display( $form_karyawan_account_name  , "Nama pemilik rekening"  );
 
	
	$account_number = array(
			'name'=>'account_number',
			'value'=>(isset($_POST['account_number'])? $_POST['account_number'] : $fields['account_number']),
			'id'=>'account_number',
			'type'=>'textfield' 
		);
	$form_karyawan_account_number = form_dynamic($account_number);
	$view .= form_field_display( $form_karyawan_account_number  , "Nomor rekening"  );
 
 
 
	$bank_detail = array(
			'name'=>'bank_detail',
			'value'=>(isset($_POST['bank_detail'])? $_POST['bank_detail'] : $fields['bank_detail']),
			'id'=>'bank_detail',
			'rows'=>'3' 
		);
	$form_bank_detail = form_textarea($bank_detail);
	$view .= form_field_display( $form_bank_detail  , "Bank detail"  );
	
		 
	$submit = array(
		'value' => ( $rekening_id ==0 ? ' Simpan ' :'  Update  '),
		'name' => 'simpan', 
		'type'=>'submit','class'=>'main_button'
	);
	$form_submit= form_dynamic($submit);
	
	$cancel = array(
		'value' => (  ' Batal '  ),
		'name' => 'cancel', 
		'type'=>'reset',
		'onclick'=>'javascript:location.href=\'index.php?com='.$_GET['com'].'&task=rekening&id='.$fields['karyawan_id'].'\'',
		'class'=>'main_button'
	);
	$form_cancel= form_dynamic($cancel);
	
	
	$view .= form_field_display( $form_submit . '  '.$form_cancel, "&nbsp;" );
	$view .= form_footer( );	
 
	return $view;
}

function save_rekening($id , $rekening_id){
	$datas = array();
	$datas['karyawan_id'] = my_type_data_int($id);
	$datas['bank_id'] = my_type_data_int($_POST['bank_id']);
	$datas['account_name'] = my_type_data_str($_POST['account_name']);
	$datas['account_number'] = my_type_data_str($_POST['account_number']);
	$datas['bank_detail'] = my_type_data_str($_POST['bank_detail']);
	$datas['user_updated_id'] = my_type_data_int($_SESSION['user_id']);
	if($rekening_id > 0 ){ 
		$datas['version'] = my_type_data_function( '(version+1)' );
		$datas['updated_on'] = my_type_data_function('NOW()');
		return my_update_record('karyawan_bank_account', 'rekening_id', $rekening_id  , $datas);
	}
	$datas['created_on'] = my_type_data_function('NOW()');
	$datas['version'] = my_type_data_int(0);
	my_insert_record('karyawan_bank_account' , $datas);
	return set_auto_portion($id);
}


function form_karyawan_rekening_validate($id , $rekening_id){
	$errsubmit = false;
	$err = array(); 
	if( trim($_POST['account_name'] ) =='' ){
		$errsubmit =true;
		$err[] = "Nama pemilik rekening belum di isi";
	}
	
	$account_number = trim($_POST['account_number']);
	 
	$account_number_uniq = rekening_spesific_is_unique($account_number );
	if($account_number == ''){ 
		$errsubmit =true;
		$err[] = "Nomor rekening  belum di isi";
	} 
	if($id == 0){
		if(! $account_number_uniq ){
			$errsubmit =true;
			$err[] = "Nomor rekening  sudah digunakan";
		}
	}
	if( (int) $_POST['bank_id'] == 0   ){
		$errsubmit =true;
		$err[] = "Nama bank belum dipilih";
	}	 
	  
	
	if( $errsubmit){
		return $err;
	}
	return false;
}

function rekening_spesific_is_unique($account_number ){
	$query = "SELECT * FROM karyawan_bank_account WHERE account_number = '{$account_number}' ";
	$result = my_query($query);
	if(my_num_rows($result) > 0){
		return false;
	}
	return true;
}

function balance_100_rekening($id){
	
	$errsubmit = false;
	$err = array();
	$sum = 0;
	foreach($_POST['pers'] as $rekening_id => $persen){
		$sum += $persen;
	}
	if($sum <> 100){
		$errsubmit =true;
		$err[] = "Total distribusi tidak penuh";
	}
	
	if( $errsubmit){
		return $err;
	}
	return false;
}

function balance_distribusi($id){
	foreach($_POST['pers'] as $rekening_id => $persen){
		$datas_update = array(
			'persen'=> my_type_data_int( $persen)
		);
		my_update_record('karyawan_bank_account' , 'rekening_id', $rekening_id , $datas_update);
			
	}
	return true;
}