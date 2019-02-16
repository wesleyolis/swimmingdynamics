<?php
 $output.= athlete_heading($tm4db,$season,arg(3));

	     if(arg(4) ==null||arg(5) ==null)
	       {
		  $header[] = array('data' => t('Distance - Time'), 'width' => '110px');
		  //$header[] = array('data' => t('Stroke'), 'width' => '80px');

		  $result = db_query("select r.DISTANCE, r.STROKE from ".$tm4db."result_".$season." r where r.ATHLETE=%d and r.I_R <> 'R' and r.NT =0 group by r.STROKE, r.DISTANCE order by r.STROKE, r.DISTANCE",arg(3));
		  $output.='Please select an option.<br>';
		  $rows[] = array(l('ALL'.stroke($object->STROKE),'athlete/'.arg(1).'/graphs/'.arg(3).'/ALL/ALL'));
		  while ($object = db_fetch_object($result))
		    {
		       $rows[] = array(l($object->DISTANCE.' '.stroke($object->STROKE),'athlete/'.arg(1).'/graphs/'.arg(3).'/'.$object->DISTANCE.'/'.$object->STROKE));
		    }
		  $output.="<div style='padding:20px;' width='200px' align='left'>";
		    $output.= theme_table($header, $rows,array('id'=>'hyper','class'=>'hyper','align'=>'left'),null).'<br></div>';
		  $output.="<div align='center'>";
		  require('stroke_distance.php');
		  $output.="</div>";
	       }
	     else
	     if(arg(2)!='graphs_fina')
	       {


		  $header[] = array('data' => t('Time'), 'width' => '70px');
		  $header[] = array('data' => t('Distance'), 'width' => '70px');
		  $header[] = array('data' => t('Stroke'), 'width' => '80px');
		  $header[] = array('data' => t('Rounds'), 'width' => '50px');
		  $header[] = array('data' => t('Date'), 'width' => '90px');
		  $header[] = array('data' => t('Meet - Results'), 'width' => '300px');

		  
		  
		  $key_lookup = array();
		  if(arg(4)=='ALL' & arg(5)=='ALL')
		    $result = db_query("select r.COURSE, r.SCORE, r.DISTANCE, r.STROKE, m.MName, m.Start, r.F_P, r.MtEvent, m.Meet from ".$tm4db."result_".$season." r, ".$tm4db."meet_".$season." m where r.ATHLETE=%d and r.MEET=m.Meet and r.I_R <> 'R' and r.NT =0 and r.SCORE !=0 group by r.STROKE, r.DISTANCE order by r.STROKE, r.DISTANCE, r.SCORE",arg(3));
		  else
		    $result = db_query("select r.COURSE, r.SCORE, r.DISTANCE, r.STROKE,r.I_R, m.MName, m.Start, r.F_P, r.MtEvent, m.Meet from ".$tm4db."result_".$season." r, ".$tm4db."meet_".$season." m where r.ATHLETE=%d and r.distance= %d and r.stroke=%d and r.MEET=m.Meet and r.I_R <> 'R' and r.NT =0 and r.SCORE !=0 group by r.STROKE, r.DISTANCE order by r.STROKE, r.DISTANCE, m.Start,r.COURSE,r.F_P desc,r.Score",arg(3),arg(4),arg(5));
		  while ($object = db_fetch_object($result))
		    {
		       $key_lookup[] = array($object->COURSE, $object->STROKE, $object->DISTANCE);
		    }

		  for ($i=0; $i<count($key_lookup); $i++)
		    {
		       $query = "select r.COURSE, r.NT, r.SCORE, r.DISTANCE, r.STROKE,r.I_R, m.MName, m.Start, r.F_P, r.MtEvent, m.Meet from ".$tm4db."result_".$season." as r left join ".$tm4db."meet_".$season." as m on (r.MEET=m.Meet ) where r.SCORE !=0 AND r.NT=0 AND r.STROKE=".$key_lookup[$i][1]." and r.DISTANCE=".$key_lookup[$i][2]." and  r.ATHLETE=".arg(3)." order by r.STROKE,r.Distance, m.Start,r.COURSE,r.F_P desc,r.Score";

		       $result = db_query($query);
		       //			drupal_set_message($query);

		       $last_stroke = NULL;
		       $last_distance = NULL;
		       $rowsL = array();
		       $rowsS = array();
		       $graph_hdrs = array();
		       $graph_dataL = array();
		       $graph_dataS = array();

		       while ($object = db_fetch_object($result))
			 {
			    //				print_r($object);

			    $time = Score(0,$object->SCORE);
			    $f_p = (strtolower($object->F_P) == 'f') ? 'Final' : 'Prelim';

			    $start_date = explode("-", $object->Start);
			    $date = Date('d/m/Y', mktime(0, 0, 0, $start_date[1], $start_date[2], $start_date[0]));
			    $graph_hdrs[$date.$object->F_P] = $date.'|';
			    //$link = 'meets/'.arg(1).'/event/results/'.$object->MtEvent;
			    $link = 'athlete/'.arg(1).'/meet_times/'.arg(3).'/'.$object->Meet;

			    if (strtolower($object->COURSE) == "l")
			      {
				 $rowsL[] = array($time, $object->DISTANCE.'m', Stroke($object->STROKE), $f_p, $date, l(t($object->MName), $link));
				 $graph_dataL[$date.$object->F_P]=($object->SCORE).'|';

			      }
			    else
			      {
				 if($graph_hdrs[$date.$object->F_P] != null)
				   if($graph_dataS[$date.$object->F_P] != null)
				     $graph_dataL[$date.$object->F_P]='-|';
			      }

			    if (strtolower($object->COURSE) == "s")
			      {
				 $rowsS[] = array($time, $object->DISTANCE.'m', Stroke($object->STROKE), $f_p, $date, l(t($object->MName), $link));
				 $graph_dataS[$date.$object->F_P]=($object->SCORE).'|';
			      }
			    else
			      {
				 if($graph_hdrs[$date.$object->F_P] != null)
				   if($graph_dataL[$date.$object->F_P] != null)
				     $graph_dataS[$date.$object->F_P]='-|';
			      }

			    if (strtolower($object->COURSE) == "s")
			      {
				 if($graph_hdrs[$date.$object->F_P] != null)
				   if($graph_dataS[$date.$object->F_P] != null)
				     $graph_dataL[$date.$object->F_P]='-|';
			      }

			    $last_stroke = $object->STROKE;
			    $last_distance = $object->DISTANCE;
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
		      

		       $gdataS[] = array('data' => "<table cellspacing='0' cellpadding='0'><tr><td style='padding:0px' width='700px'><div class='cellrel'><div class='graphsback'>".theme_image(path()."/images/swimming.jpg",null,null,array('id'=>'img_no_print','width'=>'615px','height'=>'150px'),false)."</div>".theme_image(path()."/grapher.php".$graph_data,null,null,null,false)."</div></td></tr></table>", 'colspan' => 6,'align'=>'center');

		       $rows[] = $gdataS;

		       $output.= '<br><div style="font-weight: bold;">'.$last_distance.'m '.Stroke($last_stroke).'</div><hr>';
		       $output.= theme('table', $header, $rows).'<br>';
		       $gdataS = NULL;
		       $rows   = NULL;
		       $rowsL  = NULL;
		       $rowsS  = NULL;
		    }
	       }
	       else
	       {
		  $header[] = array('data' => t('Avg-Fina'), 'width' => '70px');
		  $header[] = array('data' => t('Events'), 'width' => '70px');
		  $header[] = array('data' => t('Date'), 'width' => '90px');
		  		  		  
		  
		 
		  $query= "select round(avg(r.fina),0) as fina_avg, count(*) as events, m.Start from ".$tm4db."result_".$season." as r, ".$tm4db."meet_".$season." as m where r.ATHLETE=%d and r.MEET=m.Meet and r.I_R <> 'R' and r.NT =0 and r.fina !=0 ".(arg(4)=='All'?'':' and r.stroke='.arg(4))." ".(arg(5)=='All'?'':' and r.distance='.arg(5))." group by m.Start order by m.start";
		   
		
		  $result = db_query($query,arg(3));
		 
		    	
		       $graph_data ='';
		       $graph_hdrs='';
		       while ($object = db_fetch_object($result))
			 {
			    //				print_r($object);

			     
			    $start_date = explode("-", $object->Start);
			    $date = Date('d/m/Y', mktime(0, 0, 0, $start_date[1], $start_date[2], $start_date[0]));
			    
			    $rows[] = array($object->fina_avg, $object->events, $date);
			    $graph_hdrs.= $date.'|';
			    $graph_data.= $object->fina_avg.'|';

		       
		    }  
		 
		  if($graph_hdrs!='')
		   $rows[] = array(array('data' => "<table cellspacing='0' cellpadding='0'><tr><td style='padding:0px' width='700px'><div class='cellrel'><div class='graphsback'>".theme_image(path()."/images/swimming.jpg",null,null,array('id'=>'img_no_print','width'=>'615px','height'=>'150px'),false)."</div>".theme_image(path()."/grapher_fina.php".($graph_hdrs!=''?'?headers='.$graph_hdrs.'&data1='.$graph_data:'?headers=&data1=0|'),null,null,null,false)."</div></td></tr></table>", 'colspan' => 3,'align'=>'center'));
		    $output.= '<br><div style="font-weight: bold;">';
		   if(arg(5)!='All')
		    $output.= arg(5).'m ';
		   if(arg(4)!='All')
		    $output.=Stroke(arg(4));
		    $output.='</div><hr>';
		       $output.= theme('table', $header, $rows).'<br>';
	       }
?>