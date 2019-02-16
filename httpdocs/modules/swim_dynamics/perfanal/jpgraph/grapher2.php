<?php


include ("jpgraph.php");
include ("jpgraph_bar.php");
include ("jpgraph_line.php");

// We need some data
//	$datay[] = 0;
	$data = explode("|", $_REQUEST["data"]);
	for ($i=0; $i<count($data); $i++)
		$datay[] = $data[$i];

	$data2 = explode("|", $_REQUEST["data2"]);
	for ($i=0; $i<count($data2); $i++)
		$data2y[] = $data2[$i];

	$hdrs = explode("|", $_REQUEST["headers"]);
	for ($i=0; $i<count($hdrs); $i++)
		$databarx[] = $hdrs[$i];

// Setup the graph. 
$graph = new Graph(700,225,"png");   
$graph->img->SetImgFormat('png'); 
$graph->img->SetExpired(false); 
$graph->SetScale("linlin");
$graph->img->SetMargin(45,10,10,65);
//$graph->SetBackgroundImage("/site1/files/swimming.jpg", BGIMG_FILLFRAME);
//$graph->SetBackgroundImage("/home/sportza3/public_html/wpaquatics/jpgraph/swimming.jpg",BGIMG_FILLFRAME);

//$graph->title->Set('"GRAD_RAISED_PANEL"');
$graph->title->SetColor('darkred');

// Setup font for axis
$graph->xaxis->SetFont(FF_ARIAL);
$graph->yaxis->SetFont(FF_FONT2);
//$graph->yaxis->title->SetFont(FF_FONT1,FS_BOLD);
//$graph->yaxis->title->Set("Seconds");

$graph->xaxis->SetTickLabels($databarx);
$graph->xaxis->SetLabelAngle(35);


// Create the bar pot
$p1 = new LinePlot($datay);
$p1->mark->SetType(MARK_FILLEDCIRCLE);
$p1->mark->SetFillColor("red");
$p1->mark->SetWidth(5);
$p1->SetColor("blue");
$p1->SetWeight(4);
$p1->SetCenter();

$p2 = new LinePlot($data2y);
$p2->mark->SetType(MARK_FILLEDCIRCLE);
$p2->mark->SetFillColor("purple");
$p2->mark->SetWidth(5);
$p2->SetColor("orange");
$p2->SetWeight(4);
$p2->SetCenter();


//$p1->SetLegend("Triumph Tiger -98");
$graph->img->SetAntiAliasing(true); 
$graph->Add($p1);
$graph->Add($p2);
 

// Finally send the graph to the browser
$graph->Stroke();
?>
