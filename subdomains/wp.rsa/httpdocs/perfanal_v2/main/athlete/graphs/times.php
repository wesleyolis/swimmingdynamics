<?php
$option = 'graphs';
require('../../../main_include.php');
require('../heading.php');

$js_include.="<script type='text/javascript' src='".$app_relative."js/rgraph_v1/RGraph.common.core.js'></script>";
$js_include.="<script type='text/javascript' src='".$app_relative."js/rgraph_v1/RGraph.line.js'></script>";
$js_include.="<script type='text/javascript' src='".$app_relative."js/rgraph_v1/RGraph.common.zoom.js'></script>";
$js_include.="<script type='text/javascript' src='".$app_relative."js/rgrapher_v1.js'></script>";

drupal_set_title((($_GET['dis']=='ALL' && $_GET['st'] =='ALL')?'All':$_GET['dis'].'m '.Stroke($_GET['st'])).' Time Graphs of Results - '.$heading);



		  $header[] = array('data' => 'Time', 'width' => '70px');
		  $header[] = array('data' => 'Distance', 'width' => '70px');
		  $header[] = array('data' => 'Stroke', 'width' => '80px');
		  $header[] = array('data' => 'Round', 'width' => '50px');
		  $header[] = array('data' => 'Date', 'width' => '90px');
		  $header[] = array('data' => 'Meet - Results', 'width' => '300px');

		  $output.="Click and drag rectangle on the graph to zoom in.";
		  
		  $key_lookup = array();
		  if($_GET['dis']=='ALL' && $_GET['st'] =='ALL')
		    $result = db_query("select r.COURSE,r.NT, r.SCORE, r.DISTANCE, r.STROKE, m.MName, m.Start, r.F_P, r.MtEvent, m.Meet from ".$db_name."result as r, ".$db_name."meet as m where r.ATHLETE=".inj_int($_GET['ath'])." and r.MEET=m.Meet and r.I_R <> 'R' and r.NT =0 and r.SCORE !=0 order by r.STROKE, r.DISTANCE, m.Start,r.COURSE,r.F_P desc,r.Score");
		  else
		    $result = db_query("select r.COURSE,r.NT, r.SCORE, r.DISTANCE, r.STROKE,r.I_R, m.MName, m.Start, r.F_P, r.MtEvent, m.Meet from ".$db_name."result as r, ".$db_name."meet as m where r.ATHLETE=".inj_int($_GET['ath'])." and r.distance=".inj_int($_GET['dis'])." and r.stroke=".inj_int($_GET['st'])." and r.MEET=m.Meet and r.I_R <> 'R' and r.NT =0 and r.SCORE !=0 order by r.STROKE, r.DISTANCE, m.Start,r.COURSE,r.F_P desc,r.Score");
		
		    {


		       $last_stroke = NULL;
		       $last_distance = NULL;
		       
		        if(!mysql_error())
			{
			$object = mysql_fetch_object($result);
			
		       while ($object)
		       {
			       $last_distance = $object->DISTANCE;
			       $last_stroke = $object->STROKE;
		       
		       $rowsL = array();
		       $rowsS = array();
		       $graph_hdrs = array();
		       $graph_dataL = array();
		       $graph_dataS = array();
		       
		       
		       while ($object && $last_distance == $object->DISTANCE && $last_stroke == $object->STROKE)
			 {
			

			    $time = Score(0,$object->SCORE);
			    $f_p = (strtolower($object->F_P) == 'f') ? 'Final' : 'Prelim';

			    $start_date = explode("-", $object->Start);
			    $date = Date('d/m/Y', mktime(0, 0, 0, intval($start_date[1]), intval($start_date[2]), intval($start_date[0])));
			    $graph_hdrs[$date.$object->F_P] = $date;
			    
			    $link = 'evx='.$object->MtEvent;
			    if (strtolower($object->COURSE) == "l")
			      {
				 $rowsL[] = array($time, $object->DISTANCE.'m', Stroke($object->STROKE), $f_p, $date, l2(t($object->MName), $link,'../../meets/indi_results.php'));
				 $graph_dataL[$date.$object->F_P]=($object->SCORE);
			      }
			    else  if (strtolower($object->COURSE) == "s")
			      {
				 $rowsS[] = array($time, $object->DISTANCE.'m', Stroke($object->STROKE), $f_p, $date, l2(t($object->MName), $link,'../../meets/indi_results.php'));
				 $graph_dataS[$date.$object->F_P]=($object->SCORE);
				 
			      }



			    $last_stroke = $object->STROKE;
			    $last_distance = $object->DISTANCE;
			    $object = mysql_fetch_object($result);
			 }
			
		       $splitterL = array();
		       $splitterS = array();

		       $splitterL[] = array(array('data' => '<div style="font-weight: bold; color: blue;">Long Course</div>', 'colspan' => 6));
		       $splitterS[] = array(array('data' => '<div style="font-weight: bold; color: orange;">Short Course</div>', 'colspan' => 6));

		       $rows = array_merge($splitterL, $rowsL);
		       $rowsL = array_merge($rows, $splitterS);
		       $rows = array_merge($rowsL, $rowsS);

		       $graph_hdrs_data = NULL;
		        $graph_dataL_data = NULL;
			$graph_dataS_data = NULL;
		       
		       foreach($graph_hdrs as $key => $value)
			 {
			    $graph_hdrs_data.= "'".$value."',";
			  if(isset($graph_dataL[$key]) == true)
			    $graph_dataL_data.= $graph_dataL[$key].',';
			    else
			    $graph_dataL_data.= 'null,';
			    if(isset($graph_dataS[$key]) == true)
			    $graph_dataS_data.= $graph_dataS[$key].',';
			    else
			    $graph_dataS_data.= 'null,';
			 }
			 
			
			 $graph_data = '{\'type\':\'lcsc\',\'clabels\':['.$graph_hdrs_data.'null],\'plot\':[';
		       $graph_data.= (($graph_dataL_data) ? '['.$graph_dataL_data.'null],' : '[null],');
		       $graph_data.= (($graph_dataS_data) ? '['.$graph_dataS_data.'null]' : '[null]');
			 $graph_data .="]};";

		       $rows =  array_merge(array(array(array('data' => "<table cellspacing='0' cellpadding='0'><tr><td style='padding:0px' width='700px'><canvas class='rgraph wes'  id=\"".$graph_data."\" width='720' height='230' style='border: 1px solid #000000;background-color: #ffffff;'>[Please wait...<br> YOu may need to upgrade your browser to more modern browser]</canvas></td></tr></table>", 'colspan' => 6,'align'=>'center'))),$rows);

		     	
		       $output.= '<br><div style="font-weight: bold;">'.$last_distance.'m '.Stroke($last_stroke).'</div><hr>';
		       $output.= theme_table( $header, $rows,null,null).'<br>';
		       $gdataS = NULL;
		       $rows   = NULL;
		       $rowsL  = NULL;
		       $rowsS  = NULL;
		       
		      
		       
		       
		    }
		    
	       }
		    }
		    //$output.="<script type='text/javascript'>try{hook_graphs();}catch(e){}</script>";
	       $output.='<br>Power by Rgraph.';
	       render_page();

?>


