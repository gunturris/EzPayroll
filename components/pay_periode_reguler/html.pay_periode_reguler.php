<?php

function list_pay_periode_reguler(){
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
		'Subjek gaji' => array( 'width'=>'20%','style'=>'text-align:left;' ), 
		'Tanggal gajian' => array( 'width'=>'15%','style'=>'text-align:left;' ), 
		'Periode' => array( 'width'=>'25%','style'=>'text-align:center;' ), 
		'Deskripsi' => array( 'width'=>'30%','style'=>'text-align:center;' ), 
		'Aksi' => array( 'width'=>'10%','style'=>'text-align:center;' ), 
		
	);

	
	
	$query 	= "SELECT * FROM pay_periode_reguler ";
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
				'href'=>'index.php?com='.$_GET['com'].'&task=edit&id=' . $ey['pay_periode_reguler_id'] , 
				'title'=>'Edit'
		);	
		$edit_button = button_icon( 'b_edit.png' , $editproperty  );

		$deleteproperty = array(
			'href'=>'javascript:confirmDelete('.$ey['pay_periode_reguler_id'].');',
			'title'=>'Delete', 
		);
		$delete_button = button_icon( 'b_drop.png' , $deleteproperty  );

		$row[] = array( 
		'Subjek gaji' => $ey['title'],  
		'Tanggal gajian' =>position_text_align( date('d-m-Y', strtotime(periode_start)) , 'center'), 
		'Periode' => position_text_align(date('d-m-Y', strtotime($ey['periode_start'])) 
					.'-'.date('d-m-Y', strtotime($ey['periode_end'])) , 'center'),  
		'Deskripsi' => $ey['deskripsi'],   
		'op'=> position_text_align( $edit_button  .$delete_button , 'right')
		);
	}
	
	$datas = table_rows($row);
	$navigasi = array(
		'<input class="btn btn-primary" style="float:right;"  type="button" value="Tambah data" onclick="javascript:location.href=\'index.php?com='.$_GET['com'].'&task=edit\'"/>',
		 
	);
	$box = header_box( '&nbsp;' , $navigasi );
	$paging = $kgPagerOBJ ->showPaging();
	return $box.table_builder($headers , $datas ,  5 , false , $paging  ); 
}


function submit_pay_periode_reguler( ){
 
	$periode_id = current_periode_id();	
	$last_periode_end_date = get_last_date_periode();

	$first_current_date = strtotime($last_periode_end_date) + ( 24*60*60 );
	$datas = array(); 
	$datas['title']	=  my_type_data_str($_POST['title']);
	$datas['periode_start']	=  my_type_data_str(date('Y-m-d' , $first_current_date));
	$datas['periode_end']	=  my_type_data_str($_POST['periode_end']);
	
	if(isset($_POST['payroll_date']))
		$datas['payroll_date']	=  my_type_data_str($_POST['payroll_date']);
	
	$datas['hari_kerja']	=  my_type_data_str($_POST['hari_kerja']);
	$datas['deskripsi']	=  my_type_data_str($_POST['deskripsi']); 
	$datas['user_updated_id']	= my_type_data_int($_SESSION['user_id']);  
	$datas['status_proses']	= my_type_data_str('Current');  
		 
 
	$datas['version'] = my_type_data_function( '(version + 1)' );
	$datas['updated_on']	=  my_type_data_function( 'NOW()' );
	return my_update_record( 'pay_periode_reguler' , 'pay_periode_reguler_id' , $periode_id , $datas );
  
}

function form_pay_periode_reguler_validate(){
	return false;
}
	
	
function edit_pay_periode_reguler($id){ 

	my_set_file_js(
		array(
			'assets/jquery/combomulti/jquery.chainedSelects.js',
			'assets/js/calendar/calendarDateInput.js' 
		)
	);
	$view = form_header( "form_pay_periode_reguler" , "form_pay_periode_reguler"  );
	$fields = my_get_data_by_id('pay_periode_reguler','pay_periode_reguler_id', $id);
 

	
	$title = array(
			'name'=>'title',
			'value'=>(isset($_POST['title'])? $_POST['title'] : $fields['title']),
			'id'=>'title',
			'type'=>'textfield' 
		);
	$form_title = form_dynamic($title);
	$view .= form_field_display( $form_title  , "Nama proses gaji"  );
	
	

	$last_periode_end_date = get_last_date_periode(); 
	$first_current_date = strtotime($last_periode_end_date) + ( 24*60*60 );
	$first_current_date_form = array(
			'name'=>'periode_start',
			'value'=>(  date('Y-m-d' , $first_current_date )),
			'id'=>'periode_start',
			'type'=>'textfield' ,
			'disabled'=>'disabled'
		);
	$form_first_current_date_form = form_dynamic($first_current_date_form);
	$view .= form_field_display( $form_first_current_date_form  , "Awal periode" );
	
	
	$fperiode_end = $fields['periode_end'] ;
	if($fperiode_end == '0000-00-00'){
		$fperiode_end = date('Y-m-d');
	}
	
	$periode_end = array(
			'name'=>'periode_end',
			'value'=>(isset($_POST['periode_end'])? $_POST['periode_end'] : $fperiode_end),
			'id'=>'periode_end',
			'type'=>'textfield',
			'size'=>'45'
		);
	$form_periode_end = form_calendar($periode_end);
	$view .= form_field_display( $form_periode_end  , "Akhir periode" );
	

	
	if($fields){ 
		if( $fields['payroll_date'] <> ''   )
			$fpayroll_date =$fields['payroll_date']  ;
		else
			$fpayroll_date = date('Y-m-d')  ;
	}else{
		$fpayroll_date = date('Y-m-d');
	} 
	$payroll_date = array(
			'name'=>'payroll_date',
			'value'=>(isset($_POST['payroll_date'])? $_POST['payroll_date'] : $fpayroll_date),
			'id'=>'payroll_date', 
			'size'=>'45'
		);
	$form_payroll_date = form_calendar($payroll_date);
	$view .= form_field_display( $form_payroll_date  , "Tanggal gajian" );
	
 
	$hari_kerja = array(
		'type'=>'textfield',
		'id'=>'hari_kerja',
		'name'=>'hari_kerja',
		'value'=>( isset($_POST['hari_kerja']) ? $_POST['hari_kerja'] : $fields['hari_kerja']) ,
	);
	$form_hari_kerja = form_dynamic($hari_kerja  );
	$view .= form_field_display(  $form_hari_kerja   , "Jumlah hari kerja"    ); 
	 
	$deskripsi = array(
			'name'=>'deskripsi',
			'value'=>(isset($_POST['deskripsi'])? $_POST['deskripsi'] : $fields['deskripsi']),
			'id'=>'deskripsi',
			'rows'=>'3' 
		);
	$form_deskripsi = form_textarea($deskripsi);
	$view .= form_field_display( $form_deskripsi  , "Deskripsi"  );
	
		 
	$submit = array(
		'value' => ( ' Setel ' ),
		'name' => 'simpan', 
		'type'=>'submit','class'=>'main_button'
	);
	$form_submit= form_dynamic($submit);
	 
	
	$view .= form_field_display( $form_submit /*.' '.$form_cancel */, "&nbsp;" );
	$view .= form_footer( );	
 
	return $view;
} 

function get_last_date_periode(){
	$query = "SELECT periode_end 
			FROM pay_periode_reguler 
			WHERE status_proses = 'Closed' 
			ORDER BY pay_periode_reguler_id DESC LIMIT 0,1";
	$result = my_query($query);
	$row = my_fetch_array($result);
	return $row['periode_end'];
}

function current_periode_id(){
	$query = "SELECT pay_periode_reguler_id 
			FROM pay_periode_reguler 
			WHERE status_proses = 'Current' "; 
	$result = my_query($query);
	if( my_num_rows($result) > 0 ){
		$row = my_fetch_array($result);
		return $row['pay_periode_reguler_id'];
	}
	return false;
}
?>