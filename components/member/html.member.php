<?php

function list_member(){
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
		'Nomor' => array( 'width'=>'10%','style'=>'text-align:center;' ), 
		'Nama member' => array( 'width'=>'40%','style'=>'text-align:left;' ), 
		'Tanggal gabung' => array( 'width'=>'20%','style'=>'text-align:center;' ), 
		'Nomor Telepon' => array( 'width'=>'20%','style'=>'text-align:left;' ), 
		' ' => array( 'width'=>'10%','style'=>'text-align:right;' ),  
	);

	
	
	$query 	= "SELECT * FROM member ";
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
	$inactive_page_tag = 'style="padding:4px;background-color:#BBBBBB"';  
	$previous_page_text = ' Mundur '; 
	$next_page_text = ' Maju ';  
	$first_page_text = ' Awal '; 
	$last_page_text = ' Akhir ';
	
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
				'href'=>'index.php?com='.$_GET['com'].'&task=edit&id=' . $ey['member_id'] , 
				'title'=>'Edit'
		);	
		$edit_button = button_icon( 'b_edit.png' , $editproperty  );

		$deleteproperty = array(
			'href'=>'javascript:confirmDelete('.$ey['member_id'].');',
			'title'=>'Delete', 
		);
		$delete_button = button_icon( 'b_drop.png' , $deleteproperty  );

		$row[] = array( 
		'Nomor' => position_text_align($ey['member_number'],  'center'),
		'Nama member' => $ey['nama'],  
		'Tanggal gabung' =>  position_text_align($ey['join_date'],  'center'),
		'Nomor Telepon' => $ey['nomor_telepon1'],  
		'op'=> position_text_align( $edit_button  .$delete_button , 'right')
		);
	}
	
	$datas = table_rows($row);
	$navigasi = array(
		'<input class="button" type="button" value="Tambah data" onclick="javascript:location.href=\'index.php?com='.$_GET['com'].'&task=edit\'"/>',
		'<input class="button" type="button" value="Proses" />'
	);
	$box = header_box( 'Data member' , $navigasi );
	$paging = $kgPagerOBJ ->showPaging();
	return $box.table_builder($headers , $datas ,  4 , false , $paging  ); 
}


function submit_member($id){
	 
	$datas = array();  
	$datas['member_type_id']	=  my_type_data_str($_POST['member_type_id']);
	$datas['member_number']	=  my_type_data_str($_POST['member_number']);
	$datas['nama']	=  my_type_data_str($_POST['nama']);
	$datas['nomor_telepon1']	=  my_type_data_str($_POST['nomor_telepon1']);
	$datas['nomor_telepon2']	=  my_type_data_str($_POST['nomor_telepon2']);
	$datas['alamat']	=  my_type_data_str($_POST['alamat']);
	$datas['tanggal_lahir']	=  my_type_data_str($_POST['tanggal_lahir']);
	$datas['email']	=  my_type_data_str($_POST['email']);
	$datas['join_date']	=  my_type_data_str($_POST['join_date']);
	$datas['datetime_added']	= my_type_data_function('NOW()');  
	$datas['created_by']	= my_type_data_str($_SESSION['user_id']);  
		 
	if($id > 0){
		return my_update_record( 'member' , 'member_id' , $id , $datas );
	}
	return my_insert_record( 'member' , $datas );
}

function form_member_validate(){
	return false;
}
	
	
function edit_member($id){
	
	my_set_file_js(
		array(
			'assets/jquery/combomulti/jquery.chainedSelects.js',
			'assets/js/calendar/calendarDateInput.js' 
		)
	);
	$view = form_header( "form_member" , "form_member"  );
	$fields = my_get_data_by_id('member','member_id', $id);

 

	$member_type_ids =  array( );
	$query = "SELECT member_type_id, member_type_code , member_type_name FROM member_type";	
	$result = my_query($query);
	while($row_member_type_id = my_fetch_array($result)){
		$member_type_ids[$row_member_type_id['member_type_id']] = $row_member_type_id['member_type_code'].' / '.$row_member_type_id['member_type_name'];
	}
	$member_type_id = array(
		'name'=>'member_type_id',
		'value'=>( isset($_POST['member_type_id']) ? $_POST['member_type_id'] : $fields['member_type_id']) ,
	);
	$form_member_type_id = form_dropdown($member_type_id , $member_type_ids);
	$view .= form_field_display(  $form_member_type_id   , "Tipe anggota"    ); 
	
	$member_number = array(
			'name'=>'member_number',
			'value'=>(isset($_POST['member_number'])? $_POST['member_number'] : $fields['member_number']),
			'id'=>'member_number',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_member_number = form_dynamic($member_number);
	$view .= form_field_display( $form_member_number  , "Nomor anggota"  );
	
	$nama = array(
			'name'=>'nama',
			'value'=>(isset($_POST['nama'])? $_POST['nama'] : $fields['nama']),
			'id'=>'nama',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_nama = form_dynamic($nama);
	$view .= form_field_display( $form_nama  , "Nama"  );
	 
	$nomor_telepon1 = array(
			'name'=>'nomor_telepon1',
			'value'=>(isset($_POST['nomor_telepon1'])? $_POST['nomor_telepon1'] : $fields['nomor_telepon1']),
			'id'=>'nomor_telepon1',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_nomor_telepon1 = form_dynamic($nomor_telepon1);
	$view .= form_field_display( $form_nomor_telepon1  , "Nomor telepon"  );
	 
	$nomor_telepon2 = array(
			'name'=>'nomor_telepon2',
			'value'=>(isset($_POST['nomor_telepon2'])? $_POST['nomor_telepon2'] : $fields['nomor_telepon2']),
			'id'=>'nomor_telepon2',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_nomor_telepon2 = form_dynamic($nomor_telepon2);
	$view .= form_field_display( $form_nomor_telepon2  , "Nomor lain"  );
	
	$alamat = array(
			'name'=>'alamat',
			'value'=>(isset($_POST['alamat'])? $_POST['alamat'] : $fields['alamat']),
			'id'=>'alamat',
			'cols'=>'35',
			'rows'=>'5'
		);
	$form_alamat = form_textarea($alamat);
	$view .= form_field_display( $form_alamat  , "Alamat"  );
	
	$ftanggal_lahir = date('Y-m-d');
	if($fields){
		list($yyyytanggal_lahir , $mmtanggal_lahir, $ddtanggal_lahir ) = explode("-" ,$fields['tanggal_lahir'] );
		$ftanggal_lahir = $yyyytanggal_lahir.'-'.$mmtanggal_lahir.'-'.$ddtanggal_lahir;
	}
 
	$tanggal_lahir = array(
		'name'=>'tanggal_lahir',	'id'=>'tanggal_lahir',
		'value'=>( isset($_POST['tanggal_lahir']) ? $_POST['tanggal_lahir'] : $ftanggal_lahir) ,
	);
	$form_tanggal_lahir = form_calendar($tanggal_lahir );
	$view .= form_field_display(  $form_tanggal_lahir   , "Tanggal lahir"    ); 
	

	
	$email = array(
			'name'=>'email',
			'value'=>(isset($_POST['email'])? $_POST['email'] : $fields['email']),
			'id'=>'email',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_email = form_dynamic($email);
	$view .= form_field_display( $form_email  , "Email"  );
	
	

	$fjoin_date = date('Y-m-d');
	if($fields){
		list($yyyyjoin_date , $mmjoin_date, $ddjoin_date ) = explode("-" ,$fields['join_date'] );
		$fjoin_date = $yyyyjoin_date .'-'.$mmjoin_date.'-'.$ddjoin_date;
	}
	
	$join_date = array(
			'name'=>'join_date',
			'value'=>(isset($_POST['join_date'])? $_POST['join_date'] : $fjoin_date),
			'id'=>'join_date',
			'type'=>'textfield',
			'size'=>'45'
		);
	$form_join_date = form_calendar($join_date);
	$view .= form_field_display( $form_join_date  , "Tanggal terdaftar" );
		 
	$submit = array(
		'value' => ( $id ==0 ? ' Simpan ' :'  Update  '),
		'name' => 'simpan', 
		'type'=>'submit','class'=>'main_button'
	);
	$form_submit= form_dynamic($submit);
	
	$view .= form_field_display( "&nbsp;"   , "&nbsp;" ,  "" );
	$view .= form_field_display( $form_submit, "&nbsp;" ,  "" );
	$view .= form_footer( );	
	$navigasi = array(
		 
	);
	$box = header_box( ($id > 0 ? 'Edit':'Tambah').' data member' , $navigasi );
	return $box.$view;
} 
?>