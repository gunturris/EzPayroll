<?php
 
function load_facebox_script(){
my_set_file_js(
	array(
		'assets/jquery/facebox/facebox.js' 
	)
); 
my_set_file_css(
	array(
		'assets/jquery/facebox/facebox.css' 
	)
);
my_set_code_js('
 

jQuery(document).ready(function($) {
      $(\'a[rel*=facebox]\').facebox({
        loading_image : \'templates/temp2/icons/loading.gif\',
        close_image   : \'templates/temp2/icons/closelabel.gif\'
      }) 
}) ' 
);
return true;
}

function load_multicombo_script($cssloading , $cssfieldhidden , $formfieldmaster, $formfieldsekunder ,$fileattach){
	my_set_file_js(
		array( 
			'assets/jquery/combomulti/jquery.chainedSelects.js' 
		)
	); 

	my_set_code_css('
			
			'.$cssloading.'
			{  
				 
				background:#ff0000;
				color:#fff;
				font-size:14px;
				font-familly:Arial;
				padding:2px; 
				display:none;
				float:left;
			}
			'.$cssfieldhidden.' 
			{  
				  
				display:inline; 
			}
	');
	my_set_code_js('
		$(function()
		{ 
			$(\''.$formfieldmaster.'\').chainSelect(\''.$formfieldsekunder.'\',\''.$fileattach.'\',
			{ 
				before:function (target) 
				{ 
					$("'.$cssloading.'").css("display","block"); 
					$("'.$cssfieldhidden.'").css("display","none");
					$(target).css("display","none");
				},
				after:function (target) 
				{ 
					$("'.$cssloading.'").css("display","none");
					$("'.$cssfieldhidden.'").css("display","none");
					$(target).css("display","inline");
				}
			});
		});
		'
	);
	return true;
}

function load_editor(){
my_set_file_js(
		array( 
			'assets/GTReditor/wysiwyg.js' 
		)
	); 
}

function load_autocomplete(){

}