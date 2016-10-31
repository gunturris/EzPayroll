<?php
function table_cetak_builder($header , $datas ,  $footer_length , $property_table = false, $footer_content = false  ){

 
	if(! $property_table){
		$property_table = array(
			'style'=>'border-collapse:collapse;margin:2px;',
			'cellpadding'=>'2',
			'cellspacing'=>'0'
		);
	}
	
	if(! is_array($property_table) ){
		return (-1);
	}
	
	$view = '<table cellspacing="4px" class="content_view" ';
	foreach($property_table as $table=>$property){
		$view .= $table.'="'.$property.'" ';
	}
	$view .='>'."\n";

	if(! is_array($header) ){
		return (-2);
	}	 
	$view .='<tr>';
	foreach($header as $field=>$length){
		$view .='<td ';
		foreach($length as $th => $value){
			$view .= $th.'="'.$value.'" ';
		}
		$view .='><b>'.strtoupper($field).'</b></td>';
 	}
	$view .='</tr>'."\n";	
	if( $datas ){
		$view .= $datas."\n";
	}else{
		$view .= '<tr>';
		$view .= '<td colspan="'.$footer_length.'" style="height:34px;text-align:center;border-bottom:2px solid;">No data available</td>';
		$view .= '</tr>'."\n";
	}
	$view .='<tr><th colspan='.  $footer_length .'>'."\n";
	if($footer_content && $datas ){
		$view .= $footer_content;
	}else{
		$view .= '&nbsp;';
	}
	$view .='</th></tr>'."\n";
	$view .='</table>'."\n<br/>";

	return $view;
}


function table_cetak_rows($fields){

	if(! is_array($fields) ){
		return (-1);
	}
	$n = 0; 
	if(count($fields) == 0):
			return false;
	else: 
		$total_rows = count($fields);
		foreach($fields as $key=>$values){
			
			$bgrows = ( ($n%2) == 0 ) ? '#DEDEDE' : '' ;
			
			$view .= '<tr>';
			foreach( $values as $k => $value){
				if(($total_rows -1 )== $n)
				$view .= '<td style="height:24px; border-bottom:2px solid;" class="'.$k .'">'.$value.'</td>';
				else
				$view .= '<td style="height:24px;" class="'.$k .'">'.$value.'</td>';
			}
			$view .= '</tr>';
			
			$n++;
		}
	endif;
	return $view;
}

function table_builder($header , $datas ,  $footer_length , $property_table = false, $footer_content = false  ){

 
	if(! $property_table){
		$property_table = array(
			'style'=>'border-collapse:collapse;margin:2px;',
			'cellpadding'=>'2',
			'cellspacing'=>'0'
		);
	}
	
	if(! is_array($property_table) ){
		return (-1);
	}
	
	                                
	$view = '<table class="table table-striped table-bordered table-hover"  id="dataTables-example"><thead>'."\n";

	if(! is_array($header) ){
		return (-2);
	}	 
	$view .='<tr  style="background:#CDCDCD;height:28px;font:12px verdana;font-weight:bold;text-align:left;color:#333;" >';
	foreach($header as $field=>$length){
		$view .='<th ';
		foreach($length as $th => $value){
			$view .= $th.'="'.$value.'" ';
		}
		$view .='> '. ($field).' </th>';
 	}
	$view .='</tr></thead>'."\n";
	 
	 
	if( $datas ){
		$view .= $datas."\n";
	}else{
		$view .= '<tr>';
		$view .= '<td colspan="'.$footer_length.'" style="height:38px;text-align:center;">No data available</td>';
		$view .= '</tr>'."\n";
	} 
	$view .='
	</table>';
	if($footer_content  ){
		$view .= $footer_content;
	} 
	$view .= "\n";

	return $view;
}

function header_box( $label , $navigasi ){
	$view = '
	<div style="height:30px;margin-bottom:10px;width:100%;"> 
	<div style="width:470px;float:left;">'.$label.' </div>
	<div style="width:550px;float:right;"> ';
		
		foreach($navigasi as $button){
			$view .= $button;
		}
	$view .=  '</div>
	<div style="clear:both"></div>
	</div>';
		return $view;	
}


function table_rows($fields , $style =false){
	$view = "";
	if(! is_array($fields) ){
		return (-1);
	}
	$n = 0; 
	$total_rows = count($fields);
	if($total_rows  == 0):
			return false;
	else:
		foreach($fields as $key=>$values){
			$n++;
			
			$bgrows = ( ($n%2) == 0 ) ? ' class="even"' : ' class="odd"' ;
			/*if($n == $total_rows ){
				$view .= '<tr>';
				foreach( $values as $k => $value){
					$view .= '<td>'.$value.'</td>';
				}
				$view .= '</tr>';
			}else{ */
				$view .= '<tr'.$bgrows.' style="height:24px;font:12px verdana;">';
				foreach( $values as $k => $value){
					$view .= '<td >'.$value.'</td>';
				}
				$view .= '</tr>';
			/*}*/
		}
	endif;
	return $view;
}

function table_builder_plain($header , $datas ,  $footer_length , $property_table = false, $footer_content = false  ){

 
	if(! $property_table){
		$property_table = array(
			'style'=>'border-collapse:collapse;margin:2px;',
			'cellpadding'=>'2',
			'cellspacing'=>'0'
		);
	}
	
	if(! is_array($property_table) ){
		return (-1);
	}
	
	$view = '<table width="100%" ';
	foreach($property_table as $table=>$property){
		$view .= $table.'="'.$property.'" ';
	}
	$view .='>'."\n";

	if(! is_array($header) ){
		return (-2);
	}	 
	$view .='<tr  style="background:#f5f5f5;height:30px;font:12px verdana;font-weight:bold;text-align:left;color:#000;" >';
	foreach($header as $field=>$length){
		$view .='<th valign="bottom" ';
		foreach($length as $th => $value){
			$view .= $th.'="'.$value.'" ';
		}
		$view .='><b>'.strtoupper($field).'</b></th>';
 	}
	$view .='</tr>'."\n";
	 
	if( $datas ){
		$view .= $datas."\n";
	}else{
		$view .= '<tr bgcolor="#FFFFFF" style="border:1px solid;">';
		$view .= '<td colspan="'.$footer_length.'" style="height:34px;text-align:center;font-size:14px;">No data available</td>';
		$view .= '</tr>'."\n";
	} 
	
	if( $footer_content ){
		$view .= '<tr bgcolor="#CDCDCD">';
		$view .= '<td colspan="'.$footer_length.'" style="height:20px;text-align:right;font-size:14px;">'.$footer_content.'</td>';
		$view .= '</tr>'."\n";	
	}
	$view .='</table>'."\n";

	return $view;
}


function table_rows_plain(	$fields ){
	$view = "";
	if(! is_array($fields) ){
		return (-1);
	}
	$n = 0; 
	if(count($fields) == 0):
			return false;
	else:
		foreach($fields as $key=>$values){ 
			$view .= '<tr style="border-bottom:1px solid #CDCDCD;border-top:1px solid #CDCDCD;font:12px verdana;" onMouseOver="this.style.backgroundColor=\'#E1EAFE\'"; onMouseOut="this.style.backgroundColor=\'transparent\'"style="border-top:1px solid #000;border-bottom:1px solid #000;">';
			foreach( $values as $k => $value){
				$view .= '<td>'.$value.'</td>';
			}
			$view .= '</tr>';
			
			$n++;
		}
	endif;
	return $view;
}

function position_text_align( $content , $position = 'left' ){
	return '<div style="text-align:'.$position.'">'.$content.'</div>';
}

?>