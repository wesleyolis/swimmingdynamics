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

	/*$datay = explode("|", $_REQUEST["data"]);
	for ($i=0; $i<count($data); $i++)
		$datay[] = $data[$i];

	$data2 = explode("|", $_REQUEST["data2"]);
	for ($i=0; $i<count($data2); $i++)
		$data2y[] = $data2[$i];
		
		$data[0] = $datay;
		$data[1] = $data2y;
		$set=2;
		*/
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
	/*
	$data =array();
		$data = explode("|", $_REQUEST["data"]);
		$datay=array();
		for ($i=0; $i<count($data); $i++)
			$datay[] = $data[$i];
			$data[0] = $datay;
			$set=1;
	*/
	
	/*$data = array(array(1000,2000),array(3000,4000),array(5000,6000),array(7000,8000));
	
	$data=array();
	$data_sub=array();
	array_push($data_sub,1000);
	array_push($data_sub,2000);
	array_push($data,$data_sub);
	$data_sub=array();
	array_push($data_sub,3000);
	array_push($data_sub,4000);
	array_push($data,$data_sub);
	$data_sub=array();
	array_push($data_sub,1000);
	array_push($data_sub,2000);
	array_push($data,$data_sub);
	$data_sub=array();
	array_push($data_sub,1000);
	array_push($data_sub,2000);
	array_push($data,$data_sub);*/

	$hdrs = explode("|", $_REQUEST["headers"]);
	for ($i=0; $i<count($hdrs); $i++)
		$databarx[] = $hdrs[$i];
		
		/*
		$data = array($datay,$data2y);
		
		$min=$datay[0];
		$max=0;
		
		for ($i=0; $i<count($datay); $i++)
		{
			for($c=0;$c<2;$c++)
			{
				if($min>$data[$c][$i])
				{
					$min=$data[$c][$i];
				}
				if($max<$data[$c][$i])
				{
					$max=$data[$c][$i];
				}
			}
			
		}*/
		/*$min=$_REQUEST["min"];
		/*$min=$_REQUEST["max"];
		/*
		for ($i=0; $i<count($datay)-1; $i++)
		{	if($datay2[$i]!='-')
			$datay[$i]=  $datay[$i] - $min;
			if($data2y[$i]!='-')
			$data2y2[$i]=$data2y[$i] - $min;
		}*/
		/*
		$datay2[]=$min-$min;
		$datay2[]=$max-$min;*/
		
		
		
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



$graph->yaxis->SetLabelFormatCallback('get_time');
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



/*
$datay = $data[0];

$p1 = new LinePlot($data[1]);
$p1->mark->SetType(MARK_FILLEDCIRCLE);
$p1->mark->SetFillColor('blue');
$p1->mark->SetWidth(5);
$p1->SetColor("blue");
$p1->SetWeight(4);
$p1->SetCenter();
$graph->Add($p1);
*/
//$graph->Add($p1);

/*
$py2 = new LinePlot($datay2);

$py2->mark->SetWidth(0);

$py2->SetWeight(0);
$py2->SetCenter();
$graph->AddY2($py2);
*/

//$p1->SetLegend("Triumph Tiger -98");
$graph->img->SetAntiAliasing(true); 


 

// Finally send the graph to the browser
$graph->Stroke();
?>
