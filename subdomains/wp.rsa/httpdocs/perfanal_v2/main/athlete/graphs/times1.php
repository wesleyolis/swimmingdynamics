<?php
$option = 'graphs';
require('../../../main_include.php');
require('../heading.php');

drupal_set_title((($_GET['dis']=='ALL' && $_GET['st'] =='ALL')?'All':$_GET['dis'].'m '.Stroke($_GET['st'])).' Time Graphs of Results - '.$heading);



		  $header[] = array('data' => 'Time', 'width' => '70px');
		  $header[] = array('data' => 'Distance', 'width' => '70px');
		  $header[] = array('data' => 'Stroke', 'width' => '80px');
		  $header[] = array('data' => 'Round', 'width' => '50px');
		  $header[] = array('data' => 'Date', 'width' => '90px');
		  $header[] = array('data' => 'Meet - Results', 'width' => '300px');

		  
		  
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
			    $date = Date('d/m/Y', mktime(0, 0, 0, $start_date[1], $start_date[2], $start_date[0]));
			    $graph_hdrs[$date.$object->F_P] = $date.'|';
			    //$link = 'meets/'.arg(1).'/event/results/'.$object->MtEvent;
			   // $link = 'athlete/'.arg(1).'/meet_times/'.arg(3).'/'.$object->Meet;
			    $link = 'evx='.$object->MtEvent;
			    if (strtolower($object->COURSE) == "l")
			      {
				 $rowsL[] = array($time, $object->DISTANCE.'m', Stroke($object->STROKE), $f_p, $date, l2(t($object->MName), $link,'../../meets/indi_results.php'));
				 $graph_dataL[$date.$object->F_P]=($object->SCORE).'|';
				 

			      }
			    else
			      {
				 if($graph_hdrs[$date.$object->F_P] != null)
				   if($graph_dataS[$date.$object->F_P] != null & isset($graph_dataL[$date.$object->F_P]) == false)
				     $graph_dataL[$date.$object->F_P]='-|';
			      }

			    if (strtolower($object->COURSE) == "s")
			      {
				 $rowsS[] = array($time, $object->DISTANCE.'m', Stroke($object->STROKE), $f_p, $date, l2(t($object->MName), $link,'../../meets/indi_results.php'));
				 $graph_dataS[$date.$object->F_P]=($object->SCORE).'|';
				 
			      }
			    else
			      {
				 if($graph_hdrs[$date.$object->F_P] != null)
				   if($graph_dataL[$date.$object->F_P] != null & isset($graph_dataS[$date.$object->F_P]) == false)
				     $graph_dataS[$date.$object->F_P]='-|';
			      }

			    if (strtolower($object->COURSE) == "s")
			      {
				 if($graph_hdrs[$date.$object->F_P] != null)
				   if($graph_dataS[$date.$object->F_P] != null  & isset($graph_dataL[$date.$object->F_P]) == false)
				     $graph_dataL[$date.$object->F_P]='-|';
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
		       foreach($graph_hdrs as $key => $value)
			 {
			    $graph_hdrs_data.= $value;
			 }
		       $graph_dataL_data = NULL;
		       foreach($graph_dataL as $key => $value)
			 {
				
			    $graph_dataL_data.= $value;
			 }
		       $graph_dataS_data = NULL;
		       foreach($graph_dataS as $key => $value)
			 {
			    $graph_dataS_data.= $value;
			 }
	
		       $graph_data = '?headers='.$graph_hdrs_data;
		       $graph_data.= (($graph_dataL_data) ? '&data1='.$graph_dataL_data : '');
		       $graph_data.= (($graph_dataS_data) ? '&data2='.$graph_dataS_data : '');
		      

		       $rows =  array_merge(array(array(array('data' => "<table cellspacing='0' cellpadding='0'><tr><td style='padding:0px' width='700px'><div class='cellrel'><div class='graphsback'>".theme_image("../../../images/swimming.jpg",null,null,array('id'=>'img_no_print','width'=>'615px','height'=>'150px'),false)."</div>".theme_image("../../../grapher.php".$graph_data,null,null,null,false)."</div></td></tr></table>", 'colspan' => 6,'align'=>'center'))),$rows);

		     //  $rows[] = $gdataS;

		       $output.= '<br><div style="font-weight: bold;">'.$last_distance.'m '.Stroke($last_stroke).'</div><hr>';
		       $output.= theme_table( $header, $rows,null,null).'<br>';
		       $gdataS = NULL;
		       $rows   = NULL;
		       $rowsL  = NULL;
		       $rowsS  = NULL;
		       
		      
		       
		       
		    }
		    
	       }
		    }
	       
	       render_page();

?>

