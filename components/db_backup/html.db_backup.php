<?php

function display_main_page(){
	$query = "SHOW TABLES";
	$hasil = my_query($query);
	
	$foldersize = filesize_r(MY_FILES_PATH);
	$gb = 1024 * 1024 * 1024;
	$mb = 1024 * 1024;
	$kb = 1024;
	if( $foldersize > $gb ){
		$fsize = sprintf("%02d",$foldersize/$gb )." Gb";
	}
	elseif( $foldersize > $mb ){
		$fsize = sprintf("%02d",$foldersize/$mb )." Mb";
	}
	elseif( $foldersize > $kb ){
		$fsize = sprintf("%02d",$foldersize/$kb )." Kb";
	}
	else{
		$fsize = $foldersize . " byte";
	}
	$view ='
	<fieldset>
			<legend>Informasi database	</legend><br/>
			<table width="100%" border="0">
			<tr>
				<td width="25%"><b>Nama database</b>	 </td>
				<td width="75%">'. DATABASE_NAME.'</td>
			</tr>
			<tr>
				<td width="25%"><b>Jumlah tabel</b></td>
				<td width="75%">'. my_num_rows($hasil).' </td>
			</tr>
		 
			</table><br/>
			<input type="button" class="main_button" style="width:210px;" onclick="javascript:location.href=\'index.php?com=db_backup&task=compress_sql\'" value="DOWNLOAD DATABASE"/>
	</fieldset><br/><hr style="width:85%"/><br/><br/><fieldset>
			<legend>Informasi file uploaded	</legend><br/>
			<table width="100%" border="0">
			<tr>
				<td width="25%"><b>PATH</b>	 </td>
				<td width="75%">'. MY_FILES_PATH.'</td>
			</tr>
			<tr>
				<td width="25%"><b>Jumlah folder</b></td>
				<td width="75%">5 </td>
			</tr>
			<tr>
				<td width="25%"><b>File size</b></td>
				<td width="75%">'.$fsize.'</td>
			</tr>
			</table><br/>
			<input type="button" class="main_button" style="width:210px;" onclick="javascript:location.href=\'index.php?com=db_backup&task=compress\'" value="DOWNLOAD ALL FILES"/>
	</fieldset><br/><br/>';
	
	return $view;
}

function filesize_r($path){
  if(!file_exists($path)) return 0;
  if(is_file($path)) return filesize($path);
  $ret = 0;
  foreach(glob($path."/*") as $fn)
    $ret += filesize_r($fn);
  return $ret;
}

function compress_file(){
	$ftar = "files/compressing_backup/backup_".date("Ymd").".tar";
	exec("tar -cf {$ftar} files/upload/" );
	if(file_exists($ftar)){
		my_direct($ftar);
	}
	return false; 
}

function compress_sql(){
	$fsql =  "files/compressing_backup/database_".DATABASE_NAME."_".date("Ymd").".sql";
	$ftar = $_SERVER['DOCUMENT_ROOT']."/files/compressing_backup/database_".DATABASE_NAME."_".date("Ymd").".sql.tar";
	//$link = "files/compressing_backup/database_".DATABASE_NAME."_".date("Ymd").".sql.tar";
	$query = "SHOW TABLES";
	$hasil = my_query($query);
	$tbls='';
	while($r = my_fetch_row($hasil)){
		$tbls .= $r[0].' ';
	}
	//$mysqldump_command = "mysqldump -h ".DATABASE_HOST." -u ".DATABASE_USER." -p".DATABASE_PASSWORD."  hris_live_25_mei   > {$fsql}";
 	$mysqldump_command = "/xampp/mysql/bin/mysqldump -h ".DATABASE_HOST." -u ".DATABASE_USER." -p".DATABASE_PASSWORD." ".DATABASE_NAME."   > {$fsql}";
 
	exec($mysqldump_command);
	if(file_exists($fsql)){
		//exec("tar -cvvf {$ftar} --no-recursion {$fsql}" );
		if(file_exists($fsql)){
			my_direct($fsql );
		}
		return false;
	}
	return false;
}