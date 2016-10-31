<?php 
function set_post_time_data($fieldname){ 
	$jam = $_POST['jam_'.$fieldname];
	$menit = $_POST['menit_'.$fieldname]; 
	return $jam.':'.$menit.':00';
}

function form_time($forms){ 
list($forms['value_jam'],$forms['value_menit']) = explode(":",$forms['value']); 
	$jam_form = array(
				'name'=>'jam_'.$forms['name'],
				'value'=>$forms['value_jam'],
				'id'=>'jam_'.$forms['name'],
				'style'=>'width:22px;',
				'maxlength'=>'2'
			);
	$form_jam = form_dynamic($jam_form , $jams);
	 
	$menit_form = array(
				'name'=>'menit_'.$forms['name'],
				'value'=>$forms['value_menit'],
				'id'=>'menit_'.$forms['name'], 
				'style'=>'width:22px;',
				'maxlength'=>'2'
			);
	$form_menit = form_dynamic($menit_form , $menits);
	
	return '<span style="60px>"'.$form_jam.'<b> :</b>'.$form_menit.'</span>';
}


function dropdown_by_table($forms , $tablename ,$keys , $labels){
	$datas = array();
	$query = "SELECT {$keys} , {$labels} FROM {$tablename} ORDER BY {$labels} ASC ";
	$result = my_query($query);
	while($row = my_fetch_array($result)){
		$datas[$row[$keys]] = $row[$labels];
	}
	
	return form_dropdown($forms , $datas);
}
 
function form_dynamic($forms){

	if(! is_array($forms)) return false;
	
	$allow = array('submit' , 'reset'  );
	if(in_array($forms['type'] ,$allow ) )
		$input = '<input class="btn btn-default"';
	else
		$input = '<input class="form-control" ';
	foreach($forms as $attribut=>$value ){
		$input .= $attribut .' ="'.$value.'" '; 
	}
	if(in_array($forms['type'] ,$allow ) )
	$input .= ' />';
	else
	$input .= ' onkeypress="return handleEnter(this, event)" />';
	
	return $input;
}

function form_autocomplete($forms){

	if(! is_array($forms)) return false;
	 
	$input = '<input class="form-control" ';
	foreach($forms as $attribut=>$value ){
		$input .= $attribut .' ="'.$value.'" '; 
	} 
	$input .= ' />';
	 
	return $input;
}

function form_money($forms){
	if(! is_array($forms)) return false;
	my_set_code_css('.form_angka{text-align:right;}'); 
	
	$script = '
	$(document).ready(function() {';
	$input = '<input ';
	
	foreach($forms as $attribut=>$value ){
		$input .= $attribut .' ="'.$value.'" '; 
		if($attribut == 'id'){
		$script .='
				$(\'#'.$value.'\').change(function()
                {
                    $(\'#'.$value.'\').formatCurrency();
                });
				';
		}
	}
	$script .='
	});';
	my_set_code_js($script);
	$allow = array('submit' , 'reset'  );
	if(in_array($forms['type'] ,$allow ) )
	$input .= ' />';
 	else
 	$input .= ' onkeypress="return handleEnter(this, event);" class="form_angka" />';
	
	return $input;
} 

function form_hidden( $forms ){

	$name  =  isset($forms['name'])? $forms['name'] : rand(1000 , 9999 ) ;
	$class = isset( $forms['class'] ) ? $forms['class'] : $forms['name'] ;
	$value = isset( $forms['value'] ) ? $forms['value'] : "" ;
	$id    = isset( $forms['id'] ) ? $forms['id'] : $forms['name'] ;
	
	
	$input = '<input  type="hidden" ';
	$input .= ' name="'.$name.'" ';
	$input .= ' class="'.$class.'" ';
	$input .= ' id="'.$id.'" ';
	$input .= ' value="'.$value.'" '; 
	$input .= ' />';
	
		return $input;
}
 
/*
	FORM TEXTFIELD
	$forms  
*/
function form_textfield($forms){
	$type = isset($forms['type']) ? $forms['type'] : "text" ;
	$name =  isset($forms['name'])? $forms['name'] : rand(1000 , 9999 ) ;
	$class 	= isset( $forms['class'] ) ? $forms['class'] : 'form-control' ;
	$value = isset( $forms['value'] ) ? $forms['value'] : "" ;
	$id = isset( $forms['id'] ) ? $forms['id'] : $forms['name'] ;
	$text = ( $type =="password") ? "password" : $type;
	
	$input = '<input  onkeypress="return handleEnter(this, event)" type="'.$text.'" size="65" ';
	$input .= ' name="'.$name.'" ';
	$input .= ' class="'.$class.'" ';
	$input .= ' id="'.$id.'" ';
	$input .= ' value="'.$value.'" '; 
	$input .= ' />';

	return $input;
}
/*
	FORM FILE
	$forms  
*/
function form_file($forms){

	$name =  isset($forms['name'])  ? $forms['name'] : rand(1000 , 9999 ) ;
	$class 	= isset( $forms['class'] ) ? $forms['class'] : 'form-control' ;
	$value = isset( $forms['value'] ) ? $forms['value'] : "" ;
	$id = isset( $forms['id'] ) ? $forms['id'] : $forms['name'] ; 
	
	$input = '<input  onkeypress="return handleEnter(this, event)" type="file" size="52" ';
	$input .= ' name="'.$name.'" ';
	$input .= ' class="'.$class.'" ';
	$input .= ' id="'.$id.'" ';
	$input .= ' value="'.$value.'" '; 
	$input .= ' />';

	return $input;
}
/*
	FORM CHECKBOX
	$forms  
*/
function form_checkbox($forms){
	$name =  isset($forms['name'])  ? $forms['name'] : rand(1000 , 9999 ) ;
	$class 	= isset( $forms['class'] ) ? $forms['class'] : 'form-control' ;
	$checked =   $forms['value'] == '1' ?  "checked" : "" ;
	$id = isset( $forms['id'] ) ? $forms['id'] : $forms['name'] ;

	$input = '<input  onkeypress="return handleEnter(this, event)" type="checkbox" ';
	$input .= ' name="'.$name.'" ';
	$input .= ' class="'.$class.'" ';
	$input .= ' id="'.$id.'" ';
	$input .= ' value="1" '; 
	$input .= ' '.$checked.' />';

	return $input;

}

/*
	FORM TEXTAREA
	$forms  
*/
function form_textarea($forms){

	$name =  isset($forms['name'])  ? $forms['name'] : rand(1000 , 9999 ) ;
	$class 	= isset( $forms['class'] ) ? $forms['class'] : 'form-control' ;
	$value 	= isset( $forms['value'] ) ? $forms['value'] : "" ;
	$id 	= isset( $forms['id'] ) ? $forms['id'] : $forms['name'] ;
	$rows 	= isset( $forms['rows'] ) ? $forms['rows'] : 4 ;
	$cols 	= isset( $forms['cols'] ) ? $forms['cols'] : 35 ;
	
	$input = '<textarea cols="'.$cols.'" rows="'.$rows .'" ';
	$input .= ' name="'.$name.'" ';
	$input .= ' class="'.$class.'" ';
	$input .= ' id="'.$id.'" ';
	$input .= '>';
	$input .= $value;
	$input .= '</textarea>';

	return $input;

}

/*
	FORM DROP_DOWN
	$forms , $datas
	
	$datas = data yang di declare dari array
*/

function form_dropdown($forms , $datas){
	$name =  isset($forms['name'])  ? $forms['name'] : rand(1000 , 9999 ) ;
	$class 	= isset( $forms['class'] ) ? $forms['class'] : 'form-control' ;
	$value 	= isset( $forms['value'] ) ? $forms['value'] : "" ;
	$id 	= isset( $forms['id'] ) ? $forms['id'] : $forms['name'] ;
	$style	= isset( $forms['style'] ) ? $forms['style'] : "" ;
	$multiple = isset($forms['multiple']) ? $forms['multiple'] : 0 ;
	
	$input = '<select  onkeypress="return handleEnter(this, event)" ';
	$input .= ' name="'.$name.'" ';
	$input .= ' class="'.$class.'" ';
	$input .= ' id="'.$id.'" ';
	//$input .= ' style="'.$style.'"';
	if(isset($forms['onchange'])){
		$input .= ' onchange="'.$forms['onchange'].'" ';
	}
	if((int) $multiple > 0 ){
		$input .= ' scrolling="yes" multiple="multiple" size="'.$multiple.'" ';
	}
	$input .= '>';
	$input .= '<option value="0" selected ></option>
				';
	//$input .= '<option value="">- - - - - Pilih - - - - -</option>';
	if( is_array($datas) ){
		foreach($datas as $option_value => $option ){
			if( $option_value == $value )
				$input .= '<option value="'.$option_value.'" selected>'.$option.'</option>
				';
			else
				$input .= '<option value="'.$option_value.'">'.$option.'</option>
				';
		}  
	}
	$input .= '</select>';
	
	return $input;

} 


/*
	FORM CALENDER
	$forms  
*/
function form_calendar($forms){
	$name =  isset($forms['name'])  ? $forms['name'] : rand(1000 , 9999 ) ;
	$class 	= isset( $forms['class'] ) ? $forms['class'] : $forms['name'] ;
	$value 	= isset( $forms['value'] ) ? $forms['value'] : "" ; 
	
	return " <script>DateInput('".$name."', true, 'YYYY-MM-DD', '".$value."')</script>";
}

function form_daterange($forms){
	$name =  isset($forms['name'])  ? $forms['name'] : rand(1000 , 9999 ) ;
	$class 	= isset( $forms['class'] ) ? $forms['class'] : $forms['name'] ;
	$value 	= isset( $forms['value'] ) ? $forms['value'] : "" ; 
	my_set_code_js('$(function(){$(\'#'.$name.'\').dateRangePicker();}');
	return '<input id="'.$name.'" size="50" name="'.$name.'" value="'.$value.'">';
}
function form_radiobutton($forms , $datas , $flag=false){
	$name =  isset($forms['name'])  ? $forms['name'] : rand(1000 , 9999 ) ;
	$class 	= isset( $forms['class'] ) ? $forms['class'] : $forms['name'] ;
	$value 	= isset( $forms['value'] ) ? $forms['value'] : "" ;
	$id 	= isset( $forms['id'] ) ? $forms['id'] : $forms['name'] ;
	$style 	= isset( $forms['style'] ) ? $forms['style'] : "";
	$form_field='';
	if(!$flag){
		$flag = ' &nbsp; ';
	}
	$i=0;
	foreach($datas  as $key=>$data){ 
	$i++;
		if(strtolower($key) == strtolower($value) && trim($value) <> ''){
		$form_field .= '
		<div class="radio">
			<label>
				<input  onkeypress="return handleEnter(this, event)" type="radio" id="'.$id.$i.'" checked value="'.$key.'" name="'.$name.'"><span class="label_form">'.ucfirst($data).'</span>
			</label>
		</div>';
		}else
		$form_field .= '
		<div class="radio">
			<label><input  onkeypress="return handleEnter(this, event)" type="radio"  id="'.$id.$i.'" value="'.$key.'" name="'.$name.'"> <span class="label_form">'.ucfirst($data).'</span></label>
		</div>';
	}
	return $form_field;
}


function form_button($forms){
	$text= isset($forms['type']) ? $forms['type'] : "submit";
	$name =  isset($forms['name'])  ? $forms['name'] : rand(1000 , 9999 ) ;
	$class = isset( $forms['class'] ) ? $forms['class'] : $forms['name'] ;
	$value = isset( $forms['value'] ) ? $forms['value'] : "" ;
	$id = isset( $forms['id'] ) ? $forms['id'] : $forms['name'] ;
	 
	
	$input = '<input type="'.$text.'" size="30" ';
	$input .= ' name="'.$name .'" ';
	$input .= ' class="'.$class.'" ';
	$input .= ' id="'.$id.'" ';
	$input .= ' value="'.$value.'" '; 
	$input .= ' />';

	return $input;	
}

function form_field_display( $form , $label  ,$notice = NULL ){
	$view  ='
		<div class="form-group">
			<label>'.$label.'</label>
			'.$form.'
			'.(isset($notice) ? '<p class="help-block">'.$notice.'</p>': '').'
		</div>';
	return $view;
}
function form_field_display_three( $form , $label ,  $nilai ,$merge = false  ){
	if($merge)
	$view  ='
	<tr   style="border-top: 1px solid #FFFFFF;border-bottom: 1px solid #FFFFFF; ">
		<td colspan="3" width="100%" class="label_form" valign="top" style="padding:4px"><span>'.( $request ? '* ': '' ). ucfirst($label). '</span></td>
		 
	</tr>';

	else
	$view  ='
	<tr   style="_border-top: 1px solid #CDCDCD;_border-bottom: 1px solid #CDCDCD;border-top: 1px solid #CDCDCD;border-bottom: 1px solid #CDCDCD; ">
		<td width="20%" class="label_form" valign="top" style="padding:4px"><span>'.( $request ? '* ': '' ). ucfirst($label). '</span></td>
		<td width="40%" style="padding:4px">'.$form . ' </td>
		<td width="30%" style="padding:4px">'.$nilai . ' </td>
	</tr>';
	return $view;
}

function form_header( $label_name , $form_name  ){
/*
my_set_code_js('
function handleEnter (field, event) {
		var keyCode = event.keyCode ? event.keyCode : event.which ? event.which : event.charCode;
		if (keyCode == 13) {
			var i;
			for (i = 0; i < field.form.elements.length; i++)
				if (field == field.form.elements[i])
					break;
			i = (i + 1) % field.form.elements.length;
			field.form.elements[i].focus();
			return false;
		} 
		else
		return true;
}   
');
	$_SESSION['post_status'] = true;
	 */
	$viewed = '<form style="max-width: 700px;" method="post" id="'.$form_name.'" enctype="multipart/form-data" name="'.$form_name.'">	
	';
	return $viewed;
}

function form_footer(){
	  
	  $viewed = ' 
	  </form> ';
	return $viewed;
}
function form_header_in_tab(){

}
?>