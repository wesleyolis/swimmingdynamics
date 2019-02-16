<?php

include ("jpgraph/jpgraph.php");
include ("jpgraph/jpgraph_bar.php");
include ("jpgraph/jpgraph_line.php");

function get_time ($Score)
{
 
     if($Score==0)
     return 0;
   else
     {$split = substr($Score, strlen($Score)-2, 2);
	$seconds = substr($Score, 0, strlen($Score)-2);
	return date('i:s', $seconds).'.'.$split;
	
     }
     
}
function get_time2 ($Score)
{
 
     if($Score==0)
     return 0;
   else
     {$split = substr($Score, strlen($Score)-2, 2);
	$seconds = substr($Score, 0, strlen($Score)-2);
	//return date('s', $seconds).'.'.$split;
	return $Score;
     }
     
}

// We need some data
//	$datay[] = 0;
$datay = null;
$data2y = null;
$data = null;


		$set=0;
		
		
		//echo 'sdf'.count($_REQUEST["dat"]).'sdf';
		
	while(count($_REQUEST["data".(($set)+1)])!=0)
	{
		$data2 = explode("|", $_REQUEST["data".($set+1)]);
		$data2y = array();
		for ($i=0; $i<count($data2); $i++)
		$data2y[] = $data2[$i];
		$data[$set] = $data2y;
		$set++;
		
	}


	$hdrs = explode("|", $_REQUEST["headers"]);
	for ($i=0; $i<count($hdrs); $i++)
		$databarx[] = $hdrs[$i];
		
		
		
		
		
// Setup the graph. 
$graph = new Graph(700,230,"gif"); 
$graph->img->SetMargin(75,10,10,70);
$graph->SetScale("textlin");
//$graph->SetY2Scale("lin");
$graph->img->SetImgFormat('gif'); 
$graph->img->SetExpired(true); 


//$graph->SetBackgroundImage("/site1/files/swimming.jpg", BGIMG_FILLFRAME);
//$graph->SetBackgroundImage("/home/sportza3/public_html/wpaquatics/jpgraph/swimming.jpg",BGIMG_FILLFRAME);

//$graph->title->Set('"GRAD_RAISED_PANEL"');
//$graph->title->SetColor('darkred');

// Setup font for axis
$graph->xaxis->SetTickLabels($databarx);
$graph->xaxis->SetFont(FF_FONT1,FS_NORMAL,9);
$graph->xaxis->SetLabelAngle(90);
$graph->yaxis->SetFont(FF_FONT1,FS_NORMAL,14);
//$graph->yaxis->title->Set("Seconds");



//$graph->y2axis->SetLabelFormatCallback('get_time2');

$colors = array('blue','yellow','red','green','orange','purple','brown');
for($i=0;$i<$set;$i++)
{
$p2 = new LinePlot($data[$i]);
$p2->mark->SetType(MARK_FILLEDCIRCLE);
$p2->mark->SetFillColor($colors[$i%7]);
$p2->mark->SetWidth(5);
$p2->SetColor($colors[$i%7]);
$p2->SetWeight(4);
$p2->SetCenter();
$graph->Add($p2);
}



//$p1->SetLegend("Triumph Tiger -98");
$graph->img->SetAntiAliasing(true); 


 

// Finally send the graph to the browser
$graph->Stroke();
?>
