<?php
require_once('../components/jpgraph/src/jpgraph.php');
function load_graphic_model($graph_model){
	$graph_model = strtolower($graph_model);
	switch($graph_model){
		case "line" : 
		case "garis" : 
			require_once('../components/jpgraph/src/jpgraph_line.php');
		break;
		case "bar" : 
			require_once('../components/jpgraph/src/jpgraph_bar.php');
		break;
		case "odo" : 
			require_once('../components/jpgraph/src/jpgraph_odo.php');
		break;

		case "pie":
			require_once('../components/jpgraph/src/jpgraph_pie.php');
		break;
		
		case "pie3d":
			require_once('../components/jpgraph/src/jpgraph_pie.php');
			require_once('../components/jpgraph/src/jpgraph_pie3d.php'); 
		break;
		
		case "scatter":
			require_once('../components/jpgraph/src/jpgraph_scatter.php');
		break;
		return true;
	}
}