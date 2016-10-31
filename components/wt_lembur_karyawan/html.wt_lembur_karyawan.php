<?php 

function list_lembur_karyawan(){ 
	$headers = array();
	$headers['No']  = array( 'width'=>'5%','style'=>'text-align:center;' );
	$headers['Karyawan'] =  array( 'width'=>'65%','style'=>'text-align:center;' );
	$headers['Jam lembur dibayar'] =  array( 'width'=>'30%','style'=>'text-align:center;' ); 
	$headers[' '] =  array( 'width'=>'5%','style'=>'text-align:center;' );
	
	if(isset($_GET['key'])){
		$query 	= "SELECT * FROM   karyawan  a 
			INNER JOIN wt_temp_lembur b ON a.karyawan_id = b.karyawan_id
				WHERE  a.nama_karyawan LIKE '%{$_GET['key']}%' OR a.karyawan_nik = '{$_GET['key']}' 
					ORDER BY b.jam_hitung DESC ";
	}else{ 
		$query 	= "SELECT * FROM karyawan a 
			INNER JOIN wt_temp_lembur b ON a.karyawan_id = b.karyawan_id
			ORDER BY b.jam_hitung DESC";
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
		
		$detailproperty = array(
				'href'=>'index.php?com='.$_GET['com'].'&task=detail_lembur&id=' . $ey['karyawan_id'] , 
				'title'=>'Edit'
		);	 
		$detail_button = button_icon( 'b_props.png' , $detailproperty  ); 
		 
		$row[] = array(  
				'no' => $i,  
				'karyawan' =>   $ey['karyawan_nik'] .'/ '. $ey['nama_karyawan'] ,  
				'tidak'=> position_text_align( $ey['jam_hitung'] .' jam' , 'center'),
				'op'=> position_text_align( $detail_button  , 'center')
			);
	}
	$datas = table_rows($row);
	$navigasi = array(
		//'<input class="btn btn-primary" style="float:right;"  type="button" value="Tambah data" onclick="javascript:location.href=\'index.php?com='.$_GET['com'].'&task=edit\'"/>',
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