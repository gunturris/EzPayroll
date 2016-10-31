<?php

function table_builder_excel($header , $datas ,  $footer_length , $property_table = false, $footer_content = false  ){

 
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
	
	$view = '<table border="1" ';
	foreach($property_table as $table=>$property){
		$view .= $table.'="'.$property.'" ';
	}
	$view .='>'."\n";

	if(! is_array($header) ){
		return (-2);
	}	 
	$view .='<tr>';
	foreach($header as $field=>$length){
		$view .='<td style="background-color:#CDCDCD"';
		foreach($length as $th => $value){
			$view .= $th.'="'.$value.'" ';
		}
		$view .='>'.strtoupper($field).'</td>';
 	}
	$view .='</tr>'."\n";
	 
	if( $datas ){
		$view .= $datas."\n";
	}else{
		$view .= '<tr bgcolor="#FFFFFF">';
		$view .= '<td colspan="'.$footer_length.'" style="height:32px;text-align:center;font:14px verdana;">No data available</td>';
		$view .= '</tr>'."\n";
	}
	 
	$view .='</table>'."\n";
	return $view;
}


function table_rows_excel($fields , $style =false){
	$view = "";
	if(! is_array($fields) ){
		return (-1);
	}
	$n = 0; 
	if(count($fields) == 0):
			return false;
	else:
		foreach($fields as $key=>$values){ 
			
			$view .= '<tr style="font:12px verdana;">';
			foreach( $values as $k => $value){
				$view .= '<td style="border:1px solid;">'.$value.'</td>';
			}
			$view .= '</tr>';
			
			$n++;
		}
	endif;
	return $view;
}